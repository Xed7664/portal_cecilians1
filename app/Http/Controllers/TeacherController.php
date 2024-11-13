<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\{Student,Grade,Subject, SubjectEnrolled, Schedule, Department, YearLevel, Section, Semester, SchoolYear};
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class TeacherController extends Controller
{
    
    public function dashboard()
{
    $teacherId = Auth::user()->employee->id;

    // Fetch the teacher's schedules with necessary relationships
    $schedules = Schedule::with(['subject', 'section', 'yearLevel', 'department'])
        ->where('teacher_id', $teacherId)
        ->get();

    // Count enrolled students, subjects, programs, and sections related to the schedules
    $studentCount = SubjectEnrolled::whereIn('schedule_id', $schedules->pluck('id'))
        ->distinct('student_id')
        ->count('student_id');

    $subjectCount = $schedules->pluck('subject_id')->unique()->count();

    $programCount = $schedules->pluck('program_id')->unique()->count();

    $sectionCount = $schedules->pluck('section_id')->unique()->count();

    // Fetch grade analytics for students in the teacher's subjects
    $gradeAnalytics = Grade::whereIn('subject_enrolled_id', SubjectEnrolled::whereIn('schedule_id', $schedules->pluck('id'))->pluck('id'))
        ->select(
            DB::raw('AVG(prelim) as avg_prelim'),
            DB::raw('AVG(midterm) as avg_midterm'),
            DB::raw('AVG(prefinal) as avg_prefinal'),
            DB::raw('AVG(final) as avg_final'),
            DB::raw('COUNT(DISTINCT student_id) as total_students')
        )
        ->first();

    // Store counts and grade analytics in session for use in views
    session([
        'student_count' => $studentCount,
        'subject_count' => $subjectCount,
        'program_count' => $programCount,
        'section_count' => $sectionCount,
        'avg_prelim' => $gradeAnalytics->avg_prelim ?? 0,
        'avg_midterm' => $gradeAnalytics->avg_midterm ?? 0,
        'avg_prefinal' => $gradeAnalytics->avg_prefinal ?? 0,
        'avg_final' => $gradeAnalytics->avg_final ?? 0,
        'total_students' => $gradeAnalytics->total_students ?? 0,
    ]);

    return view('teacher.dashboard', compact('schedules'));
}
    
public function getSchedulesJson()
{
    $teacherId = Auth::user()->employee->id;

    $schedules = Schedule::with(['subject', 'section', 'department'])
        ->where('teacher_id', $teacherId)
        ->get();

    $scheduleEvents = $schedules->map(function ($schedule) {
        return [
            'title' => $schedule->subject->subject_code . ' - ' . $schedule->section->name,
            'start' => Carbon::parse($schedule->start_time)->format('H:i:s'),
            'end' => Carbon::parse($schedule->end_time)->format('H:i:s'),
            'daysOfWeek' => explode(',', $schedule->days), // Assumes 'days' is a comma-separated list like "1,3" for Monday and Wednesday
        ];
    });

    return response()->json($scheduleEvents);
}

public function teacherSchedule()
{
    $teacherId = Auth::user()->employee->id;

    // Fetch the teacher's schedules with necessary relationships from the Schedule model
    $scheduleData = Schedule::with(['subject', 'section', 'yearLevel', 'program'])
        ->where('teacher_id', $teacherId)
        ->get();

    // Organize data by day and time for calendar display
    $organizedSchedule = $this->organizeScheduleData($scheduleData);

    // Use $this->timelineData to pass the public property to the view
    return view('teacher.full-schedule', compact('organizedSchedule'))->with('timelineData', $this->timelineData);
}


public $timelineData = [
    "07:00", "07:30", "08:00", "08:30", "09:00", "09:30", "10:00", "10:30", "11:00", "11:30",
    "12:00", "12:30", "01:00", "01:30", "02:00", "02:30", "03:00", "03:30", "04:00", "04:30",
    "05:00", "05:30", "06:00", "06:30", "07:00", "07:30", "08:00", "08:30"
];

private function getLatestDate($scheduleData)
{
    $timestamps = $scheduleData->pluck('created_at')->merge($scheduleData->pluck('updated_at'));
    return $timestamps->max();
}

private function organizeScheduleData($scheduleData)
{
    $daysOrder = ['MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY'];
    $organizedData = [];
    $eventCounter = 1;
    $subjectEventMapping = [];

    foreach ($scheduleData as $event) {
        // Check if schedule and subject exist to avoid null errors
        if (!isset($event->schedule) || !isset($event->subject)) {
            continue;
        }

        // Skip lab subjects
        if (stripos(strtolower($event->subject->subject_code), '(lab)') !== false) {
            continue;
        }

        // Format the subject code
        $event->subject->subject_code = str_ireplace('-(LEC)', '', strtoupper($event->subject->subject_code));

        // Retrieve corrected_day and corrected_time, only if they exist
        $correctedDays = explode(',', $event->schedule->corrected_day ?? '');
        $correctedTimes = explode(',', $event->schedule->corrected_time ?? '');

        foreach ($correctedDays as $correctedDay) {
            if (in_array($correctedDay, $daysOrder)) {
                foreach ($correctedTimes as $correctedTime) {
                    $correctedTimeParts = explode('-', $correctedTime);
                    $startTime = trim($correctedTimeParts[0]);
                    $endTime = count($correctedTimeParts) > 1 ? trim($correctedTimeParts[1]) : '';

                    // Map subject ID to a unique event ID
                    $subjectId = $event->subject_id;
                    if (!isset($subjectEventMapping[$subjectId])) {
                        $eventId = 'event-' . $eventCounter;
                        $subjectEventMapping[$subjectId] = $eventId;
                        $eventCounter++;
                        if ($eventCounter > 20) $eventCounter = 1;
                    } else {
                        $eventId = $subjectEventMapping[$subjectId];
                    }

                    // Organize the event details
                    $eventDetails = [
                        'event_id' => $eventId,
                        'subject_id' => $event->subject->id,
                        'subject_code' => $event->subject->subject_code,
                        'description' => $event->subject->description,
                        'room_name' => $event->schedule->room,
                        'start_military_time' => Carbon::parse($startTime)->format('H:i'),
                        'end_military_time' => Carbon::parse($endTime)->format('H:i'),
                        'start_civilian_time' => $startTime,
                        'end_civilian_time' => $endTime,
                        'corrected_time' => $correctedTime,
                    ];

                    $organizedData[$correctedDay][] = $eventDetails;
                }
            }
        }
    }

    // Sort data by daysOrder
    $sortedData = [];
    foreach ($daysOrder as $day) {
        if (isset($organizedData[$day])) {
            $sortedData[$day] = $organizedData[$day];
        }
    }

    return $sortedData;
}

    public function fetchEnrolledStudents(Request $request)
    {
        $teacherId = Auth::user()->employee->id;

        // Capture filter inputs
        $selectedSchoolYearId = $request->get('school_year_id');
        $selectedSemesterId = $request->get('semester_id');
        $selectedDepartmentId = $request->get('department_id');
        $selectedSectionId = $request->get('section_id');
        $selectedYearLevelId = $request->get('year_level_id');

        // Build query to fetch students based on teacher's assigned subjects and filters
        $studentsQuery = Student::whereHas('subjectsEnrolled.schedule', function ($query) use ($teacherId, $selectedSchoolYearId, $selectedSemesterId, $selectedDepartmentId, $selectedSectionId, $selectedYearLevelId) {
            $query->where('teacher_id', $teacherId);
        
            if ($selectedSchoolYearId) {
                $query->where('school_year_id', $selectedSchoolYearId);
            }
            if ($selectedSemesterId) {
                $query->where('semester_id', $selectedSemesterId);
            }
            if ($selectedDepartmentId) {
                $query->where('program_id', $selectedDepartmentId);
            }
            if ($selectedSectionId) {
                $query->where('section_id', $selectedSectionId);
            }
            if ($selectedYearLevelId) {
                $query->where('year_level_id', $selectedYearLevelId);
            }
        })->with([
            'program', 
            'yearLevel', 
            'section', 
            'schoolYear', 
            'semester', 
            'subjectsEnrolled.schedule.schoolYear', 
            'subjectsEnrolled.schedule.yearLevel', 
            'subjectsEnrolled.schedule.semester'
        ]);

        $students = $studentsQuery->get();

        // Check if the request is an AJAX call
        if ($request->ajax()) {
            // Log the students data for debugging
            \Log::info('Filtered Students:', $students->toArray());

            return response()->json(['students' => $students]);
        }

        // For non-AJAX requests, retrieve all necessary data
        $schoolYears = SchoolYear::all();
        $semesters = Semester::all();
        $departments = Department::all();
        $sections = Section::all();
        $yearLevels = YearLevel::all();

        return view('teacher.students.student-list', compact(
            'students', 'schoolYears', 'semesters', 'departments', 'sections', 'yearLevels',
            'selectedSchoolYearId', 'selectedSemesterId', 'selectedDepartmentId', 'selectedSectionId', 'selectedYearLevelId'
        ));
    }
      
    public function viewGrades($id)
    {
        // Get the logged-in teacher's ID
        $teacherId = Auth::user()->employee->id;
    
        // Find the student
        $student = Student::findOrFail($id);
    
        // Fetch grades for subjects assigned to the teacher
        $grades = Grade::where('student_id', $id)
            ->whereHas('subject.schedules', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->with(['subject', 'semester', 'schoolYear'])
            ->get();
    
        return view('teacher.students.view-grades', compact('student', 'grades'));
    }
    
}
