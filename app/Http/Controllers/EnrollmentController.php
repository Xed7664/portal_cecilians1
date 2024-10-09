<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Semester;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\Department;
use App\Models\YearLevel;
use App\Models\Section;
use App\Models\SubjectsEnrolled;
use Illuminate\Http\Request;
use Auth;

class EnrollmentController extends Controller
{
    // Create Enrollment Form
    public function create()
    {
        // Get the currently authenticated student
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->back()->with('error', 'No student data found.');
        }

        // Fetch the latest semester and school year
        $currentSemester = Semester::latest()->first();
        $currentSchoolYear = SchoolYear::latest()->first();

        if (!$currentSemester || !$currentSchoolYear) {
            return redirect()->back()->with('error', 'No semesters or school years found in the system.');
        }

        // Fetch available departments, year levels, and sections for the student to select from
        $departments = Department::all();
        $yearLevels = YearLevel::all();
        $sections = Section::all();

        // Initially, no subjects are loaded
        $schedule = [];

        return view('enrollment.form', compact('student', 'currentSemester', 'currentSchoolYear', 'departments', 'yearLevels', 'sections', 'schedule'));
    }

    // Store Enrollment Records
    public function store(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'department_id' => 'required|exists:departments,id',
            'year_level_id' => 'required|exists:year_levels,id',
        ]);
    
        $student = Auth::user()->student;
        $currentSemester = Semester::latest()->first();
        $section = Section::find($request->section_id);
    
        // Enroll student in each subject linked to the selected section
        foreach ($section->subjects as $subject) {
            SubjectsEnrolled::create([
                'student_id' => $student->id,
                'subject_id' => $subject->id,
                'section_id' => $section->id,
                'semester_id' => $currentSemester->id,
                'school_year_id' => $currentSemester->school_year_id,
                'teacher_id' => $subject->teacher_id,
                'year_level_id' => $request->year_level_id,
            ]);
        }
    
        return redirect()->route('enrollment.create')->with('success', 'Enrollment successful!');
    }
    
    public function getSectionSchedule(Request $request)
    {
        $section_id = $request->input('section_id');
        $semester_id = $request->input('semester_id');
        $year_level_id = $request->input('year_level_id');
        $department_id = $request->input('department_id');
        
        // Fetch section based on provided filters
        $section = Section::with(['subjects' => function ($query) use ($semester_id) {
            $query->where('semester_id', $semester_id);
        }])
        ->where('id', $section_id)
        ->where('year_level_id', $year_level_id)
        ->where('department_id', $department_id)
        ->first();
        
        if (!$section || $section->subjects->isEmpty()) {
            return response()->json(['subjects' => []], 200);
        }
    
        // Prepare schedule data
        $schedule = $section->subjects->map(function ($subject) {
            return [
                'subject_code' => $subject->subject_code,
                'subject_description' => $subject->description,
                'room_name' => $subject->room_name,
                'days' => $subject->day,
                'time' => $subject->time,
                'instructor_name' => $subject->instructor_name
            ];
        });
    
        return response()->json(['subjects' => $schedule], 200);
    }
// In EnrollmentController
public function getStudentDetails()
{
    $student = Auth::user()->student;

    // Check if the student exists
    if (!$student) {
        return response()->json(['error' => 'No student found for this user'], 404);
    }

    // Get department and other relationships (eager load if needed)
    $student->load('department', 'semester', 'subjectsEnrolled');

    return response()->json([
        'department' => $student->department ? $student->department->name : null,
        'currentYearLevel' => $student->YearLevel, // Assuming YearLevel is a direct column
        'semester' => $student->semester ? $student->semester->name : null,
        'subjectsEnrolled' => $student->subjectsEnrolled
    ]);
}

// Determine the student's current year level based on their previous enrollment
private function getStudentYearLevel($student)
{
    $lastEnrollment = $student->enrollments()->latest()->first();

    if (!$lastEnrollment) {
        return null; // New student or no previous enrollment
    }

    $currentSemester = Semester::latest()->first();

    // If student is in the next semester of the same year, maintain the year level
    if ($lastEnrollment->semester_id == $currentSemester->id && $lastEnrollment->school_year_id == $currentSemester->school_year_id) {
        return $lastEnrollment->yearLevel;
    }

    // If it's a new academic year, increment the year level
    if ($this->isNewAcademicYear($lastEnrollment->school_year_id, $currentSemester->school_year_id)) {
        return YearLevel::find($lastEnrollment->year_level_id + 1);
    }

    return $lastEnrollment->yearLevel;
}

// Helper to check if it's a new academic year
private function isNewAcademicYear($lastYearId, $currentYearId)
{
    return $lastYearId !== $currentYearId;
}

}
