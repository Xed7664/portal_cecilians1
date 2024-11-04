<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\YearLevel;
use Illuminate\Http\Request;
use App\Imports\StudentsImport;
use App\Models\SubjectEnrolled;
use App\Models\SubjectsProspectus;
use Maatwebsite\Excel\Facades\Excel;

class StudentPheadController extends Controller
{
    public function view($id)
{
    $student = Student::with(['semester', 'yearLevel', 'section', 'program', 'schoolYear'])->findOrFail($id);

    return view('phead.view-student', compact('student'));
}

    public function index()
    {
        // Retrieve the program_id associated with the program head's department
        $programHeadProgramId = session('program_id');
    
        // Fetch students associated with the program head's program_id
        $data = Student::with(['semester', 'yearLevel', 'section', 'program'])
                        ->where('program_id', $programHeadProgramId)
                        ->get();
    
        return view('phead.students-list', compact('data'));
    }

    
    public function grades($id)

            {
                $student = Student::findOrFail($id);
                $grades = Grade::where('student_id', $id)->with(['subject', 'semester', 'schoolYear'])->get();
                return view('phead.view-grades', compact('student', 'grades'));
            }

    public function viewProspectus($studentId)
            {
            
                $student = Student::with('program')->findOrFail($studentId);
                $program = $student->program;
                $programId = $program->id;
            
            
                $prospectusData = SubjectsProspectus::where('program_id', $programId)
                    ->with(['subject', 'yearLevel', 'semester'])
                    ->get()
                    ->groupBy([
                        function ($item) { return $item->yearLevel->name; },
                        function ($item) { return $item->semester->name; }
                    ]);
            
            
                $enrolledSubjects = SubjectEnrolled::where('student_id', $studentId)
                    ->with(['subject', 'grades' => function ($query) {
                        $query->select('subject_enrolled_id', 'final'); 
                    }])
                    ->get()
                    ->keyBy('subject_id'); 
            
                $programName = $program->name;
                $programDescription = $program->description;
            
                
                return view('phead.view-prospectus', compact('student', 'prospectusData', 'programName', 'programDescription', 'enrolledSubjects'));
            }

            public function yearAndSection()
            {
                $departmentId = session()->get('department_id');
                $yearLevels = YearLevel::all();
                $sectionsByYear = [];
            
                foreach ($yearLevels as $yearLevel) {
                    $sections = Section::whereIn('id', function ($query) use ($yearLevel, $departmentId) {
                        $query->select('section_id')
                              ->from('subjects_enrolled')
                              ->where('year_level_id', $yearLevel->id) 
                              ->whereExists(function ($query) use ($departmentId) {
                                  $query->select('program_id')
                                        ->from('students')
                                        ->where('program_id', $departmentId);
                              })
                              ->distinct();
                    })->get();
            
                    $sectionsByYear[$yearLevel->name] = $sections;
                }
            
                return view('phead.yearandsection', compact('yearLevels', 'sectionsByYear'));
            }
            

            public function studentsBySection($sectionId, $yearLevelId)
            {
                $section = Section::with('yearLevel')->findOrFail($sectionId);
            
                
                $studentIds = SubjectEnrolled::where('section_id', $sectionId)
                                             ->where('year_level_id', $yearLevelId) 
                                             ->pluck('student_id');
            
                $students = Student::whereIn('id', $studentIds)
                                   ->select(['StudentID', 'FullName', 'Birthday', 'Gender', 'Address', 'contact'])
                                   ->get();
            
                return view('phead.students-by-section', compact('section', 'students'));
            }
            


}