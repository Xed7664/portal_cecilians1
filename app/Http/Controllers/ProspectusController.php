<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProspectusController extends Controller
{
    public function index(Request $request)
    {
        
        $student = session('student');
        
       
        if (!$student) {
            return redirect()->back()->with('error', 'Student information not found.');
        }

        
        $studentId = $student->getAttribute('id');

        
        $departmentMap = [
            'BSIT' => 1,
            'BSBA' => 2,
            'BEED' => 3
        ];
        $departmentId = $departmentMap[$student->Course] ?? null;

        if (!$departmentId) {
            return redirect()->back()->with('error', 'Invalid course or department not found.');
        }

       
        $subjects = DB::table('subjects')
            ->join('subjects_prospectus', 'subjects.id', '=', 'subjects_prospectus.subject_id')
            ->join('year_levels', 'subjects.year_level_id', '=', 'year_levels.id')
            ->where('subjects.department_id', $departmentId)
            ->where('subjects_prospectus.archive_status', 0) 
            ->orderBy('year_levels.id')
            ->orderBy('subjects.semester')
            ->select('subjects.*', 'year_levels.name as year_level_name')
            ->get();

        $grades = DB::table('grades')
            ->join('subjects_enrolled', 'grades.subject_enrolled_id', '=', 'subjects_enrolled.id')
            ->where('subjects_enrolled.student_id', $studentId)
            ->select('grades.*', 'subjects_enrolled.subject_id')
            ->get();

        
        $prospectus = [];
        $semesterTotals = [];
        $studentGrades = [];

       
        foreach ($grades as $grade) {
            $finalGrade = ($grade->prelim + $grade->midterm + $grade->prefinal + $grade->final) / 4;
            $studentGrades[$grade->subject_id] = round($finalGrade, 2);
        }

       
        foreach ($subjects as $subject) {
            $prospectus[$subject->year_level_name][$subject->semester][] = $subject;

            if (!isset($semesterTotals[$subject->year_level_name][$subject->semester])) {
                $semesterTotals[$subject->year_level_name][$subject->semester] = [
                    'total_lec_units' => 0,
                    'total_lab_units' => 0,
                    'total_units' => 0,
                    'total_hours' => 0,
                ];
            }

            $semesterTotals[$subject->year_level_name][$subject->semester]['total_lec_units'] += $subject->lec_units;
            $semesterTotals[$subject->year_level_name][$subject->semester]['total_lab_units'] += $subject->lab_units;
            $semesterTotals[$subject->year_level_name][$subject->semester]['total_units'] += $subject->total_units;
            $semesterTotals[$subject->year_level_name][$subject->semester]['total_hours'] += $subject->total_hours;
        }

       
        return view('prospectus.index', compact('prospectus', 'semesterTotals', 'studentGrades', 'student'));
    }
}