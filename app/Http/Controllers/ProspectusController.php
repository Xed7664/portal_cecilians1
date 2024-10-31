<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProspectusController extends Controller
{
    public function index(Request $request)
{
    $student = session('student');

    if (!$student) {
        return redirect()->back()->with('error', 'Student information not found.');
    }

    $studentId = $student->getAttribute('id');
    $programId = $student->program_id; // Get the program_id directly from the student session

    // Check if programId is valid
    if (!$programId) {
        return redirect()->back()->with('error', 'Invalid program or department not found.');
    }

    // Fetch subjects based on the student's program_id
    $subjects = DB::table('subjects')
        ->join('subjects_prospectus', 'subjects.id', '=', 'subjects_prospectus.subject_id')
        ->join('departments', 'subjects_prospectus.program_id', '=', 'departments.id') // Join departments to fetch code and name
        ->join('semesters', 'subjects_prospectus.semester_id', '=', 'semesters.id') // Join semesters to fetch name
        ->where('subjects_prospectus.program_id', $programId) // Use the program_id from subjects_prospectus
        ->where('subjects_prospectus.archive_status', 0)
        ->orderBy('subjects_prospectus.semester_id') // Adjust ordering based on your needs
        ->select(
            'subjects.*', 
            'subjects_prospectus.semester_id', 
            'subjects_prospectus.year_level_id', 
            'departments.code as department_code', // Fetch department code
            'departments.name as department_name', // Fetch department name
            'semesters.name as semester_name' // Fetch semester name
        )
        ->get();

    // Fetch grades for the student
    $grades = DB::table('grades')
        ->join('subjects_enrolled', 'grades.subject_enrolled_id', '=', 'subjects_enrolled.id')
        ->where('subjects_enrolled.student_id', $studentId)
        ->select('grades.*', 'subjects_enrolled.subject_id')
        ->get();

    $prospectus = [];
    $semesterTotals = [];
    $studentGrades = [];

    foreach ($grades as $grade) {
        $finalGrade = isset($grade->final) ? $grade->final : round(($grade->prelim + $grade->midterm + $grade->prefinal) / 3, 2);
        $studentGrades[$grade->subject_id] = [
            'grade' => $finalGrade,
            'pass_status' => $finalGrade <= 3.0, // true for pass, false for fail
        ];
    }
    

    // Organize the prospectus data
    foreach ($subjects as $subject) {
        // Ensure year level names are joined from a separate table if needed
        $yearLevelName = DB::table('year_levels')->where('id', $subject->year_level_id)->value('name');

        // Organizing the prospectus data by year level and semester
        $prospectus[$yearLevelName][$subject->semester_id][] = [
            'subject' => $subject,
            'department_code' => $subject->department_code, // Include department code
            'department_name' => $subject->department_name, // Include department name
            'semester_name' => $subject->semester_name, // Include semester name
        ];

        if (!isset($semesterTotals[$yearLevelName][$subject->semester_id])) {
            $semesterTotals[$yearLevelName][$subject->semester_id] = [
                'total_lec_units' => 0,
                'total_lab_units' => 0,
                'total_units' => 0,
                'total_hours' => 0,
            ];
        }

        $semesterTotals[$yearLevelName][$subject->semester_id]['total_lec_units'] += $subject->lec_units;
        $semesterTotals[$yearLevelName][$subject->semester_id]['total_lab_units'] += $subject->lab_units;
        $semesterTotals[$yearLevelName][$subject->semester_id]['total_units'] += $subject->total_units;
        $semesterTotals[$yearLevelName][$subject->semester_id]['total_hours'] += $subject->total_hours;
    }

    return view('prospectus.index', compact('prospectus', 'semesterTotals', 'studentGrades', 'student'));
}

}
