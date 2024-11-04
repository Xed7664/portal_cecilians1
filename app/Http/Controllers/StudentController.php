<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Semester;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use App\Models\SubjectEnrolled;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function index()
    {
        // Logic specific to the student's dashboard
        return view('student.dashboard');
    }

    public function edit($id)
    {
        $student = Student::with(['subjectsEnrolled.subject', 'subjectsEnrolled.grades', 'subjectsEnrolled.schoolYear', 'subjectsEnrolled.semester'])
            ->findOrFail($id);

        $schoolYears = SchoolYear::orderBy('name', 'desc')->get();
        $semesters =Semester::whereIn('name', ['2nd Semester', '1st Semester'])
        ->orderBy('name', 'desc')
        ->distinct()
        ->pluck('name');

        $subjectsWithGrades = $student->subjectsEnrolled->map(function ($subjectEnrolled) {
            $grade = $subjectEnrolled->grades->first();
            return [
                'subject_code' => $subjectEnrolled->subject->subject_code ?? 'N/A',
                'description' => $subjectEnrolled->subject->description ?? 'N/A',
                'school_year' => $subjectEnrolled->schoolYear->year ?? 'N/A',
                'semester' => $subjectEnrolled->semester->name ?? 'N/A',
                'prelim' => $grade->prelim ?? 'N/A',
                'midterm' => $grade->midterm ?? 'N/A',
                'prefinal' => $grade->prefinal ?? 'N/A',
                'final' => $grade->final ?? 'N/A',
                'remarks' => $grade->remarks ?? 'N/A',
                'status' => $grade->status ?? 'N/A',
            ];
        });

        return view('admin.users.edit', compact('student', 'subjectsWithGrades', 'schoolYears', 'semesters'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        
        $validatedData = $request->validate([
            'StudentID' => 'required|unique:students,StudentID,' . $id,
            'FullName' => 'required',
            'Birthday' => 'required|date',
            'Course' => 'required',
            'Gender' => 'required|in:male,female',
            'Status' => 'required|boolean',
        ]);

        $student->update($validatedData);

        return redirect()->route('admin.users.student.edit', $student->id)
                         ->with('success', 'Student updated successfully');
    }

    public function showGrades($id)
    {
        $student = Student::findOrFail($id);
    
        // Fetch all the school years and semesters
        $schoolYears = SchoolYear::orderBy('name', 'desc')->get();
        $semesters =Semester::whereIn('name', ['2nd Semester', '1st Semester'])
                        ->orderBy('name', 'desc')
                        ->distinct()
                        ->pluck('name');
    
        // Get the student's grades grouped by school year, semester, section, and year level
        $groupedGrades = SubjectEnrolled::where('student_id', $student->id)
            ->with(['subject', 'schoolYear', 'semester', 'section', 'yearLevel', 'grades'])
            ->orderBy('school_year_id', 'desc')
            ->orderBy('semester_id', 'desc')
            ->get()
            ->groupBy([
                'school_year_id',
                'semester_id',
                'section_id',
                'year_level_id'
            ]);
    
        return view('admin.users.grades', compact('student', 'schoolYears', 'semesters', 'groupedGrades'));
    }
    
   
    
}
