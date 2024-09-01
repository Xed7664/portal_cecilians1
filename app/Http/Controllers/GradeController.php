<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{YearLevel, Section, Grade, Employee, Semester, SchoolYear, Department, Subject,SubjectEnrolled,Student};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Imports\GradesImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Exports\GradesTemplateExport; // Correct Import Statement
use App\Services\ExcelService;
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Support\Facades\Mail;
use App\Mail\GradesNotification; // Assuming you have created a Mailable class
use Illuminate\Support\Facades\Session;

class GradeController extends Controller
{
    // For Program Heads
    public function programHeadIndex() {
        $user = Auth::user();
        if ($user->type === 'program_head' && $user->employee) {
            $departmentId = $user->employee->department_id;
            $yearLevels = YearLevel::with(['sections' => function ($query) use ($departmentId) {
                $query->where('department_id', $departmentId);
            }])->get();

            return view('program-head.grades.index', compact('yearLevels'));
        }

        return redirect()->route('newsfeed');
    }

    public function programHeadShow(Section $section) {
        $user = Auth::user();
        if ($user->type === 'program_head' && $user->employee) {
            $departmentId = $user->employee->department_id;

            if ($section->department_id == $departmentId) {
                $grades = $section->grades()->with('student', 'subject', 'semester.schoolYear')->get();
            } else {
                $grades = collect(); // Empty collection if section doesn't belong to the program head's department
            }

            return view('program-head.grades.show', compact('section', 'grades'));
        }

        return redirect()->route('newsfeed');
    }
    public function approveGrades(Request $request, $gradeId)
    {
        // Assuming the grade approval logic here...
        $grade = Grade::findOrFail($gradeId);
        $grade->status = 'approved';
        $grade->save();

        // Send the grade release notification email
        Mail::to($grade->student->email)->send(new GradeReleaseNotification($grade));

        return redirect()->back()->with('success', 'Grades approved and student notified.');
    }



    public function teacherIndex(Request $request)
    {
        $user = Auth::user();
    
        if ($user->type === 'teacher') {
            // Retrieve all the necessary data for the dropdowns
            $schoolYears = SchoolYear::all();
            $semesters = Semester::all();
            $departments = Department::all();
            $sections = Section::all(); // Fetch all sections
    
            // Get the selected department, section, school year, and semester or default to all
            $selectedDepartmentId = $request->get('department_id', null);
            $selectedSectionId = $request->get('section_id', null);
            $selectedSchoolYearId = $request->get('school_year_id', Session::get('current_school_year_id'));
            $selectedSemesterId = $request->get('semester_id', Session::get('current_semester_id'));
    
            // Fetch all subjects assigned to the teacher, filter by department, section, school year, and semester
            $subjectsEnrolled = SubjectEnrolled::with(['subject.department', 'section', 'schoolYear', 'semester'])
                ->where('teacher_id', $user->employee->id)
                ->when($selectedDepartmentId, function($query) use ($selectedDepartmentId) {
                    $query->whereHas('subject', function($subQuery) use ($selectedDepartmentId) {
                        $subQuery->where('department_id', $selectedDepartmentId);
                    });
                })
                ->when($selectedSectionId, function($query) use ($selectedSectionId) {
                    $query->where('section_id', $selectedSectionId);
                })
                ->when($selectedSchoolYearId, function($query) use ($selectedSchoolYearId) {
                    $query->where('school_year_id', $selectedSchoolYearId);
                })
                ->when($selectedSemesterId, function($query) use ($selectedSemesterId) {
                    $query->where('semester_id', $selectedSemesterId);
                })
                ->get()
                ->groupBy(function($subjectEnrolled) {
                    // Extract the base code, removing any "(lab)" or "(lec)" suffixes
                    return preg_replace('/\s*\(.*?\)\s*/', '', $subjectEnrolled->subject->subject_code);
                })
                ->map(function($group) {
                    // Return the first subject in the group to avoid duplicates
                    return $group->first();
                });
    
            // Pass the retrieved data to the view
            return view('teacher.grades.index', compact('schoolYears', 'semesters', 'departments', 'sections', 'subjectsEnrolled', 'selectedDepartmentId', 'selectedSectionId', 'selectedSchoolYearId', 'selectedSemesterId'));
        }
    
        return redirect()->route('newsfeed');
    }
 
public function fetchSubjects(Request $request)
{
    $schoolYearId = $request->input('school_year_id');
    $semesterId = $request->input('semester_id');

    $subjects = Subject::where('school_year_id', $schoolYearId)
                        ->where('semester_id', $semesterId)
                        ->get();

    return response()->json(['subjects' => $subjects]);
}

    public function teacherShow($subjectEnrolledId)
    {
        $user = Auth::user();

        if ($user->type === 'teacher') {
            $subjectEnrolled = SubjectEnrolled::findOrFail($subjectEnrolledId);
            $subject = $subjectEnrolled->subject;
            $section = $subjectEnrolled->section;

            $students = Student::join('subjects_enrolled', 'students.id', '=', 'subjects_enrolled.student_id')
                ->where('subjects_enrolled.subject_id', $subjectEnrolled->subject_id)
                ->where('subjects_enrolled.section_id', $subjectEnrolled->section_id)
                ->where('subjects_enrolled.semester_id', $subjectEnrolled->semester_id)
                ->where('subjects_enrolled.school_year_id', $subjectEnrolled->school_year_id)
                ->where('subjects_enrolled.teacher_id', $user->employee->id)
                ->select('students.id', 'students.StudentID', 'students.FullName')
                ->get();

            // Pass the correct variables to the view
            return view('teacher.grades.show', compact('subjectEnrolled', 'subject', 'section', 'students'));
        }

        abort(403, 'Unauthorized action.');
    }
    
    

    
    public function update(Request $request, $subjectEnrolledId)
{
    // Temporary logic (You can leave it empty if you just want to test)
    return redirect()->back()->with('success', 'Update method reached!');
}

    
public function storeOrUpdateGrades(Request $request, $subjectEnrolledId)
{
    $user = Auth::user();

    if ($user->type === 'teacher') {
        $subjectEnrolled = SubjectEnrolled::where('id', $subjectEnrolledId)
            ->where('teacher_id', $user->employee->id)
            ->firstOrFail();

        foreach ($request->students as $studentId => $data) {
            // Calculate remarks based on the final grade
            $finalGrade = $data['final'];
            $remarks = $finalGrade > 3 ? 'Failed' : 'Passed';

            Grade::updateOrCreate(
                [
                    'subject_enrolled_id' => $subjectEnrolled->id,
                    'student_id' => $studentId,
                ],
                [
                    'prelim' => $data['prelim'],
                    'midterm' => $data['midterm'],
                    'prefinal'=> $data['prefinal'],
                    'final' => $finalGrade,
                    'remarks' => $remarks,
                ]
            );
        }

        return back()->with('success', 'Grades updated successfully!');
    }

    return redirect()->route('newsfeed');
}

public function importGrades(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,csv',
    ]);

    try {
        Excel::import(new GradesImport, $request->file('file'));
        
        // Assuming you're passing the enrolled subject ID and fetching section_id
        $subjectEnrolled = SubjectEnrolled::findOrFail($request->input('subjectEnrolledId'));
        $sectionId = $subjectEnrolled->section_id;

        return view('teacher.grades.import', ['sectionId' => $sectionId])
                     ->with('success', 'Grades Imported Successfully!');
    } catch (\Exception $e) {
        \Log::error('Error importing grades: ' . $e->getMessage());
        return back()->with('error', 'Failed to import grades. Please check the file and try again.');
    }
}

private function calculateRemarks($finalGrade)
{
    return $finalGrade > 3 ? 'Failed' : 'Passed';
}
public function showMappingForm(Request $request)
{
    // Extract the headers from the uploaded file
    $file = $request->file('file');
    $fileHeaders = (new HeadingRowImport)->toArray($file)[0][0];

    return view('teacher.grades.mapping', compact('fileHeaders'));
}

public function mapHeaders(Request $request)
{
    $mappings = $request->input('mappings');
    session(['grade_import_mappings' => $mappings]);

    // Redirect to import the file using the mappings
    return redirect()->route('teacher.grades.import')->with('success', 'Header mappings saved! Please proceed with the import.');
}

public function showUploadForm()
{
    return view('teacher.grades.upload');
}


public function uploadFile(Request $request)
{
    $file = $request->file('file');
    $headers = ExcelService::readHeaders($file); // Get the file headers using a custom service

    // Store the uploaded file temporarily
    $request->session()->put('uploaded_file', $file);

    return view('teacher.grades.upload', compact('headers'));
}


public function mapColumns(Request $request)
{
    $file = $request->session()->get('uploaded_file'); // Retrieve the uploaded file
    $mapping = $request->input('mapping'); // Get column mappings

    Excel::import(new GradesImport($mapping), $file); // Pass mapping to the import

    return redirect()->route('grades.upload.form')->with('success', 'Grades imported successfully.');
}

public function downloadTemplate($subjectId)
{
    return Excel::download(new GradesTemplateExport($subjectId), 'grades_template.xlsx');
}


    // public function filter(Request $request)
    // {
       
    //     $schoolYearId = $request->get('school_year');
    //     $semesterId = $request->get('semester');
    //     $departmentId = $request->get('department');
        
   
    //     $subjectsEnrolled = SubjectEnrolled::with(['subject', 'section'])
    //         ->whereHas('subject', function ($query) use ($departmentId, $schoolYearId, $semesterId) {
    //             $query->where('department_id', $departmentId)
    //                   ->where('school_year_id', $schoolYearId)
    //                   ->where('semester_id', $semesterId);
    //         })
    //         ->get();
        
        
    //     return view('teacher.grades.partials.subjects', compact('subjectsEnrolled'));
    // }
    
    
    // For Students
    public function studentIndex() {
        $user = Auth::user();
        if ($user->type === 'student') {
            // Determine the earliest semester and school year from the subjects_enrolled table
            $earliestEnrollment = SubjectEnrolled::with('semester', 'schoolYear', 'section', 'yearLevel')
                ->where('student_id', $user->student->id) // Ensure we only get enrollments for the logged-in student
                ->orderBy('semester_id', 'asc')
                ->orderBy('school_year_id', 'asc')
                ->orderBy('section_id', 'asc')
                ->orderBy('year_level_id', 'asc')
                ->first();

            if (!$earliestEnrollment) {
                // If no enrollments are found, return an empty result
                return view('student.grades.index', [
                    'enrollments' => collect(), // Return an empty collection
                    'hasGrades' => false
                ]);
            }

            // Filter enrollments based on the earliest semester, section, and year level
            $enrollments = SubjectEnrolled::with(['subject', 'semester', 'schoolYear', 'section', 'yearLevel'])
                ->where('student_id', $user->student->id) // Ensure we only get enrollments for the logged-in student
                ->whereHas('semester', function ($query) use ($earliestEnrollment) {
                    $query->where('id', $earliestEnrollment->semester_id);
                })
                ->whereHas('section', function ($query) use ($earliestEnrollment) {
                    $query->where('id', $earliestEnrollment->section_id);
                })
                ->where('year_level_id', $earliestEnrollment->year_level_id)
                ->whereHas('schoolYear', function ($query) use ($earliestEnrollment) {
                    $query->where('id', $earliestEnrollment->school_year_id);
                })
                ->get();

            $hasGrades = $enrollments->isNotEmpty();

            return view('student.grades.index', [
                'enrollments' => $enrollments,
                'hasGrades' => $hasGrades
            ]);
        }

        // Redirect to a different page if the user is not a student
        return redirect()->route('newsfeed');
    }

    public function showAllGradesForStudent($studentId)
    {
        // Fetch all grades for the current student, including subject enrollment details
        $grades = Grade::with(['subject', 'subjectEnrolled', 'subjectEnrolled.semester', 'subjectEnrolled.schoolYear', 'subjectEnrolled.section', 'subjectEnrolled.yearLevel'])
            ->where('student_id', $studentId)
            ->get()
            ->groupBy(function ($grade) {
                return $grade->subjectEnrolled->schoolYear->id; // Group by school year ID
            })
            ->map(function ($group) {
                return $group->groupBy(function ($grade) {
                    return $grade->subjectEnrolled->semester->id; // Group by semester ID within each school year
                })->map(function ($semesterGroup) {
                    return $semesterGroup->groupBy(function ($grade) {
                        return $grade->subjectEnrolled->section->id; // Group by section ID within each semester
                    })->map(function ($sectionGroup) {
                        return $sectionGroup->groupBy(function ($grade) {
                            return $grade->subjectEnrolled->yearLevel->id; // Group by year level ID within each section
                        });
                    });
                });
            });
    
        return view('student.grades.show', [
            'groupedGrades' => $grades,
        ]);
    }
    public function requestReview($studentId)
{
// Fetch all enrollments for the student
$enrollments = SubjectEnrolled::with(['semester', 'schoolYear', 'section', 'yearLevel', 'subject', 'grades'])
->where('student_id', $studentId)
->orderBy('semester_id', 'asc')
->orderBy('school_year_id', 'asc')
->orderBy('section_id', 'asc')
->orderBy('year_level_id', 'asc')
->get();

// Determine the earliest semester and school year from the enrollments
$earliestEnrollment = $enrollments->first();

if (!$earliestEnrollment) {
// If no enrollments are found, return an empty result
return view('student.grades.review', [
'enrollments' => collect(), // Return an empty collection
'hasGrades' => false
]);
}

// Filter enrollments based on the earliest semester, section, and year level
$filteredEnrollments = $enrollments->filter(function ($enrollment) use ($earliestEnrollment) {
return $enrollment->semester->id == $earliestEnrollment->semester_id &&
$enrollment->schoolYear->id == $earliestEnrollment->school_year_id &&
$enrollment->section->id == $earliestEnrollment->section_id &&
$enrollment->yearLevel->id == $earliestEnrollment->year_level_id;
});

$hasGrades = $filteredEnrollments->isNotEmpty();

// Pass data to the view
return view('student.grades.review', [
'enrollments' => $filteredEnrollments,
'hasGrades' => $hasGrades
]);
}


    
public function submitReviewRequest(Request $request, $gradeId)
{
    // Find the grade and handle the review request
    $grade = Grade::find($gradeId);

    if ($grade) {
        // Process the review request (e.g., mark it as requested, notify the teacher, etc.)
        $grade->update(['review_requested' => true]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Review request submitted successfully.');
    }

    // Redirect back with an error message if the grade is not found
    return redirect()->back()->with('error', 'Grade not found.');
}

    
    
    public function getGradesBySemester($semesterId)
    {
        $user = Auth::user();
    
        if ($user->type === 'student') {
            $grades = Grade::where('student_id', $user->student->id)
                ->where('semester_id', $semesterId)
                ->where('release_date', '<=', now()) // Only show grades after release_date
                ->with('subject')
                ->get();
    
            return response()->json($grades);
        }
    
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    

    public function sendGradesNotification()
    {
        // Fetch the students and their grades
        $students = Student::all(); // Adjust this query based on your requirements
    
        foreach ($students as $student) {
            // Send the email
            Mail::to($student->email)->send(new GradesNotification($student));
        }
    
        return redirect()->back()->with('status', 'Notifications sent successfully!');
    }
}
