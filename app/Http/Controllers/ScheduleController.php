<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\{Subject,SchoolYear,Semester, SubjectEnrolled, Schedule};
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ScheduleController extends Controller
{

    
    public $timelineData = [
        "07:00", "07:30", "08:00", "08:30", "09:00", "09:30", "10:00", "10:30", "11:00", "11:30",
        "12:00", "12:30", "01:00", "01:30", "02:00", "02:30", "03:00", "03:30", "04:00", "04:30",
        "05:00", "05:30", "06:00", "06:30", "07:00", "07:30", "08:00", "08:30"
    ];
    public function teacherSchedule()
    {
        $user = Auth::user();
    
        // Fetch school years and semesters
        $schoolYears = SchoolYear::all();  // Adjust model name as necessary
        $semesters = Semester::all();      // Adjust model name as necessary
    
        // Selected filters from request
        $selectedSchoolYearId = request('school_year_id');
        $selectedSemesterId = request('semester_id');
    
        // Fetch schedule data for the current teacher based on assigned subjects and selected school year and semester
        $scheduleData = SubjectEnrolled::with(['subject', 'schedule'])
            ->whereHas('schedule', function ($query) use ($user, $selectedSchoolYearId, $selectedSemesterId) {
                $query->where('teacher_id', $user->employee->id);
    
                if ($selectedSchoolYearId) {
                    $query->where('school_year_id', $selectedSchoolYearId);
                }
                if ($selectedSemesterId) {
                    $query->where('semester_id', $selectedSemesterId);
                }
            })
            ->get();
    
        $organizedScheduleData = $this->organizeScheduleData($scheduleData);
        $timelineData = $this->timelineData;
        $latestDate = $this->getLatestDate($scheduleData);
        $formattedLatestDate = Carbon::parse($latestDate)->format('F d, Y (h:i A)');
    
        return view('teacher.full-schedule', compact(
            'timelineData',
            'organizedScheduleData',
            'formattedLatestDate',
            'selectedSchoolYearId',
            'selectedSemesterId',
            'schoolYears',
            'semesters'
        ));
    }
    
    


    public function index()
    {
        $user = Auth::user();

        // Fetch schedule data for the current student based on enrolled subjects
        $scheduleData = SubjectEnrolled::with(['subject', 'schedule'])
            ->where('student_id', $user->student->id)
            ->whereHas('schedule', function($query) {
                $query->where('school_year_id', Session::get('current_school_year_id'))
                      ->where('semester_id', Session::get('current_semester_id'));
            })
            ->get();

        // Organize the data by corrected_day for easy rendering in the view
        $organizedScheduleData = $this->organizeScheduleData($scheduleData);

        $timelineData = $this->timelineData;
        $latestDate = $this->getLatestDate($scheduleData);
        $formattedLatestDate = Carbon::parse($latestDate)->format('F d, Y (h:i A)');

        if ($scheduleData->isEmpty()) {
            return view('schedule_empty');
        }

        return view('schedule', compact('timelineData', 'organizedScheduleData', 'formattedLatestDate'));
    }

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
            if (stripos(strtolower($event->subject->subject_code), '(lab)') !== false) {
                continue;
            }

            $event->subject->subject_code = str_ireplace('-(LEC)', '', strtoupper($event->subject->subject_code));
            $correctedDays = explode(',', $event->schedule->corrected_day);
            $correctedTimes = explode(',', $event->schedule->corrected_time);

            foreach ($correctedDays as $correctedDay) {
                if (in_array($correctedDay, $daysOrder)) {
                    foreach ($correctedTimes as $correctedTime) {
                        $correctedTimeParts = explode('-', $correctedTime);
                        $startTime = trim($correctedTimeParts[0]);
                        $endTime = count($correctedTimeParts) > 1 ? trim($correctedTimeParts[1]) : '';

                        $subjectId = $event->subject_id;
                        if (!isset($subjectEventMapping[$subjectId])) {
                            $eventId = 'event-' . $eventCounter;
                            $subjectEventMapping[$subjectId] = $eventId;
                            $eventCounter++;
                            if ($eventCounter > 20) $eventCounter = 1;
                        } else {
                            $eventId = $subjectEventMapping[$subjectId];
                        }

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

        $sortedData = [];
        foreach ($daysOrder as $day) {
            if (isset($organizedData[$day])) {
                $sortedData[$day] = $organizedData[$day];
            }
        }

        return $sortedData;
    }
}
