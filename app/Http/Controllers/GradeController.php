<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{YearLevel, Section, Grade, Employee, Semester, SchoolYear, Department, Subject,SubjectEnrolled,Student};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Imports\GradesImport;
use App\Events\GradeUpdated;
use Illuminate\Support\Facades\Event;
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
        // Get the teacher's employee ID
        $teacherId = $user->employee->id;

        // Retrieve dropdown data
        $schoolYears = SchoolYear::all();
        $semesters = Semester::all();
        $departments = Department::all();
        $sections = Section::all();

        // Retrieve filter values from request or session
        $selectedDepartmentId = $request->get('department_id', session('selectedDepartmentId'));
        $selectedSectionId = $request->get('section_id', session('selectedSectionId'));
        $selectedSchoolYearId = $request->get('school_year_id', session('selectedSchoolYearId', session('current_school_year_id')));
        $selectedSemesterId = $request->get('semester_id', session('selectedSemesterId', session('current_semester_id')));

        // Fetch departments handled by the teacher
        $departmentsHandled = Department::whereHas('subjects', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })->get();

        // Fetch unique subjects based on the selected filters
        $subjectsEnrolled = SubjectEnrolled::with(['subject.department', 'section', 'schoolYear', 'semester'])
            ->where('teacher_id', $teacherId)
            ->when($selectedDepartmentId, function ($query) use ($selectedDepartmentId) {
                $query->whereHas('subject', function ($subQuery) use ($selectedDepartmentId) {
                    $subQuery->where('department_id', $selectedDepartmentId);
                });
            })
            ->when($selectedSectionId, function ($query) use ($selectedSectionId) {
                $query->where('section_id', $selectedSectionId);
            })
            ->when($selectedSchoolYearId, function ($query) use ($selectedSchoolYearId) {
                $query->where('school_year_id', $selectedSchoolYearId);
            })
            ->when($selectedSemesterId, function ($query) use ($selectedSemesterId) {
                $query->where('semester_id', $selectedSemesterId);
            })
            ->get()
            ->unique(function ($subjectEnrolled) {
                return $subjectEnrolled->subject->id; // Use subject ID to ensure uniqueness
            });

        return view('teacher.grades.index', compact(
            'schoolYears', 'semesters', 'departments', 'sections', 'subjectsEnrolled', 
            'selectedDepartmentId', 'selectedSectionId', 'selectedSchoolYearId', 'selectedSemesterId', 'departmentsHandled'
        ));
    }

    return redirect()->route('newsfeed');
}


    
 
public function fetchSubjects(Request $request)
{
    // Store the selected filters in the session
    session([
        'selectedSchoolYearId' => $request->school_year_id,
        'selectedSemesterId' => $request->semester_id,
        'selectedDepartmentId' => $request->department_id,
        'selectedSectionId' => $request->section_id,
    ]);

    $query = SubjectEnrolled::with('subject.department', 'section')
        ->where('teacher_id', Auth::user()->employee->id)
        ->where('school_year_id', $request->school_year_id)
        ->where('semester_id', $request->semester_id);

    if ($request->department_id) {
        $query->whereHas('subject.department', function ($q) use ($request) {
            $q->where('id', $request->department_id);
        });
    }

    if ($request->section_id) {
        $query->where('section_id', $request->section_id);
    }

    $subjectsEnrolled = $query->get();

    return response()->json($subjectsEnrolled);
}

public function fetchTeacherDepartments()
{
    // Get the teacher's ID from the authenticated user
    $teacherId = Auth::user()->employee->id;

    // Fetch unique departments associated with the teacher via subjects_enrolled
    $departments = Department::whereHas('subjects.subjectEnrolled', function ($query) use ($teacherId) {
        $query->where('teacher_id', $teacherId);
    })->distinct()->get();

    return response()->json($departments);
}




    public function teacherShow($subjectEnrolledId)
    {
        $user = Auth::user();
    
        // Ensure the authenticated user is a teacher
        if ($user->type !== 'teacher') {
            abort(403, 'Unauthorized action.');
        }
    
        // Find the enrolled subject or fail with a 404
        $subjectEnrolled = SubjectEnrolled::with(['subject', 'section'])->findOrFail($subjectEnrolledId);
    
        // Ensure the teacher matches the teacher assigned to this enrolled subject
        if ($subjectEnrolled->teacher_id !== $user->employee->id) {
            abort(403, 'Unauthorized action.');
        }
    
        // Fetch the students enrolled in this subject and section
        $students = Student::join('subjects_enrolled', 'students.id', '=', 'subjects_enrolled.student_id')
            ->where('subjects_enrolled.subject_id', $subjectEnrolled->subject_id)
            ->where('subjects_enrolled.section_id', $subjectEnrolled->section_id)
            ->where('subjects_enrolled.semester_id', $subjectEnrolled->semester_id)
            ->where('subjects_enrolled.school_year_id', $subjectEnrolled->school_year_id)
            ->select('students.id', 'students.StudentID', 'students.FullName')
            ->get();
    
        // Return the view with the required data
        return view('teacher.grades.show', [
            'subjectEnrolled' => $subjectEnrolled,
            'subject' => $subjectEnrolled->subject,
            'section' => $subjectEnrolled->section,
            'students' => $students
        ]);
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
        // Validate the incoming request data
        $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'prelim' => 'required|numeric|min:0|max:5',
            'midterm' => 'required|numeric|min:0|max:5',
            'prefinal' => 'required|numeric|min:0|max:5',
            'final' => 'required|numeric|min:0|max:5',
        ]);

        $studentId = $request->student_id;

        // Get the initial SubjectEnrolled record (the teacher's own record)
        $initialSubjectEnrolled = SubjectEnrolled::where('id', $subjectEnrolledId)
            ->where('teacher_id', $user->employee->id)
            ->firstOrFail();

        // Find the SubjectEnrolled record for the specific student in the same subject, section, semester, and school year
        $subjectEnrolled = SubjectEnrolled::where('subject_id', $initialSubjectEnrolled->subject_id)
            ->where('section_id', $initialSubjectEnrolled->section_id)
            ->where('semester_id', $initialSubjectEnrolled->semester_id)
            ->where('school_year_id', $initialSubjectEnrolled->school_year_id)
            ->where('student_id', $studentId)
            ->firstOrFail();

        // Update all grades for this subject_enrolled_id to 'draft' status (if needed)
        Grade::where('subject_enrolled_id', $subjectEnrolled->id)
            ->update(['status' => 'draft']);

        $finalGrade = $request->final;
        $remarks = $finalGrade > 3 ? 'Failed' : 'Passed';

        // Update or create the grade for the individual student
        Grade::updateOrCreate(
            [
                'subject_enrolled_id' => $subjectEnrolled->id,
                'student_id' => $studentId,
            ],
            [
                'prelim' => $request->prelim,
                'midterm' => $request->midterm,
                'prefinal' => $request->prefinal,
                'final' => $finalGrade,
                'remarks' => $remarks,
                'status' => 'draft',
            ]
        );

        return response()->json(['message' => 'Grade saved successfully with status set to draft']);
    }

    return response()->json(['message' => 'Unauthorized'], 403);
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

public function autoSaveGrade(Request $request, $subjectEnrolledId)
{
    $user = Auth::user();

    if ($user->type === 'teacher') {
        $subjectEnrolled = SubjectEnrolled::where('id', $subjectEnrolledId)
            ->where('teacher_id', $user->employee->id)
            ->firstOrFail();

        $studentId = $request->input('student_id');
        $grades = $request->input('grades');
        $finalGrade = $grades['final'];

        // Calculate remarks based on the final grade
        $remarks = $finalGrade > 3 ? 'Failed' : 'Passed';

        // Save or update the grade
        Grade::updateOrCreate(
            [
                'subject_enrolled_id' => $subjectEnrolled->id,
                'student_id' => $studentId,
            ],
            [
                'prelim' => $grades['prelim'],
                'midterm' => $grades['midterm'],
                'prefinal' => $grades['prefinal'],
                'final' => $finalGrade,
                'remarks' => $remarks,
            ]
        );

        return response()->json(['success' => 'Grades updated successfully!', 'remarks' => $remarks], 200);
    }

    return response()->json(['error' => 'Unauthorized access'], 403);
}



public function submitStudent(Request $request, $subjectEnrolledId)
{
    // Validate the incoming request
    $request->validate([
        'student_id' => 'required|exists:students,id',
        'status' => 'required|in:ready',
    ]);

    // Fetch the grade record for the specific subject enrollment
    $grade = Grade::where('subject_enrolled_id', $subjectEnrolledId)
                  ->where('student_id', $request->student_id)
                  ->firstOrFail();

    // Update the status
    $grade->status = $request->status;
    $grade->save();

    // Respond with success
    return response()->json(['success' => 'Grade marked as ready for submission.']);
}

public function submitAllGrades(Request $request, $subjectEnrolledId)
{
    $user = Auth::user();

    if ($user->type === 'teacher') {
        // Get the initial SubjectEnrolled record (the teacher's own record)
        $initialSubjectEnrolled = SubjectEnrolled::where('id', $subjectEnrolledId)
            ->where('teacher_id', $user->employee->id)
            ->firstOrFail();

        if ($request->has('grades')) {
            $selectedStudentIds = array_keys($request->grades);

            // Update or create the grade for each selected student and subject_enrolled combination
            foreach ($request->grades as $studentId => $gradeData) {
                // Find the correct SubjectEnrolled record for each student
                $subjectEnrolled = SubjectEnrolled::where('subject_id', $initialSubjectEnrolled->subject_id)
                    ->where('section_id', $initialSubjectEnrolled->section_id)
                    ->where('semester_id', $initialSubjectEnrolled->semester_id)
                    ->where('school_year_id', $initialSubjectEnrolled->school_year_id)
                    ->where('student_id', $studentId)
                    ->firstOrFail();

                $finalGrade = $gradeData['final'];
                $remarks = $finalGrade > 3 ? 'Failed' : 'Passed';

                // Update or create the grade for each student and subject_enrolled combination
                Grade::updateOrCreate(
                    [
                        'subject_enrolled_id' => $subjectEnrolled->id,
                        'student_id' => $studentId, // Ensure this matches the correct student
                    ],
                    [
                        'prelim' => $gradeData['prelim'],
                        'midterm' => $gradeData['midterm'],
                        'prefinal' => $gradeData['prefinal'],
                        'final' => $finalGrade,
                        'remarks' => $remarks,
                        'status' => 'reviewing' // Ensure status is properly updated
                    ]
                );
            }

            // Update only the selected grades to 'reviewing' status
            Grade::whereIn('subject_enrolled_id', SubjectEnrolled::where('subject_id', $initialSubjectEnrolled->subject_id)
                ->where('section_id', $initialSubjectEnrolled->section_id)
                ->where('semester_id', $initialSubjectEnrolled->semester_id)
                ->where('school_year_id', $initialSubjectEnrolled->school_year_id)
                ->pluck('id')
            )
            ->whereIn('student_id', $selectedStudentIds)
            ->update(['status' => 'reviewing']);

            return response()->json(['message' => 'Selected grades submitted successfully with status set to reviewing']);
        }

        return response()->json(['message' => 'No grades found to submit'], 400);
    }

    return response()->json(['message' => 'Unauthorized'], 403);
}




public function submitSelectedGrades(Request $request)
{
    $selectedStudents = $request->input('selected_students', []);

    if (!empty($selectedStudents)) {
        Grade::whereIn('student_id', $selectedStudents)
            ->update(['status' => 'under_review']);

        return response()->json(['message' => 'Selected grades submitted successfully']);
    }

    return response()->json(['message' => 'No students selected'], 400);
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
