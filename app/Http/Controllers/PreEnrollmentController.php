<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Department;
use App\Models\Section;
use App\Models\Schedule;
use App\Models\Semester;
use App\Models\SchoolYear;
use App\Models\YearLevel;
use App\Models\SubjectEnrolled;
use App\Models\SubjectsProspectus;
use App\Notifications\PreEnrollmentSubmittedNotification; // Import the notification
use App\Models\User; // Assuming you are notifying a user
use Notification; // Import the Notification facade
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notifiable;
use App\Mail\PreEnrollmentConfirmation;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf; // Import the Pdf facade here
use App\Models\PreEnrollmentSetting;
use App\Models\SectionYearLevelLock;

class PreEnrollmentController extends Controller
{
    public function showForm()
    {
        // Retrieve the active academic period (the currently open semester and school year)
        $activeAcademicPeriod = PreEnrollmentSetting::with(['semester', 'schoolYear'])
            ->where('is_open', true)
            ->first();
    
        // If no active pre-enrollment period, show the closed view
        if (!$activeAcademicPeriod) {
            return view('pre-enrollment.closed'); // Redirect to a "closed" view if no active period
        }
    
        // Retrieve the authenticated user's student profile
        $student = auth()->user()->student;
    
        // Fetch the student type from admissions, if available
        $admissionDetails = DB::table('admissions')
            ->where('email', auth()->user()->email)
            ->select('student_type')
            ->first();
    
        if ($admissionDetails) {
            $student->student_type = $admissionDetails->student_type;
        }
    
        // Fetch previous year level and initialize completed subjects and average grade
        $previousYearLevel = optional($student->yearLevel)->name ?? 'Not Available';
        $completedSubjects = [];
        $averageGrade = null;
    
        // Check if the student has enrolled subjects
        if ($student->subjectsEnrolled()->exists()) {
            $enrolledSubjects = $student->subjectsEnrolled()
                ->with('grade', 'subject')
                ->get();
    
            // Filter for completed subjects with passing grades
            $completedSubjects = $enrolledSubjects->filter(function ($subject) {
                return $subject->grade && $subject->grade->remarks === 'passed';
            });
    
            // Calculate average grade of completed subjects
            $averageGrade = $completedSubjects->avg(function ($subject) {
                return $subject->grade->final;
            });
    
            // Calculate the new year level based on completed subjects
            $student->year_level = $this->determineYearLevel($student, $completedSubjects);
        } else {
            $student->year_level = '1st Year';
        }
    
        // Admin functionality: retrieve all pre-enrollment settings to manage opening/closing
        $preEnrollmentSettings = PreEnrollmentSetting::with(['semester', 'schoolYear'])->get();
    
        // Determine if the current user is an admin
        $isAdmin = auth()->user()->hasRole('admin');
    
        // Retrieve all necessary data for the form view
        $programs = Department::all();
        $semesters = Semester::all();
        $schoolYears = SchoolYear::all();
    
        // Pass the previous year level and other data to the view
        return view('pre-enrollment.form', compact(
            'student',
            'programs',
            'semesters',
            'schoolYears',
            'completedSubjects',
            'averageGrade',
            'previousYearLevel',
            'activeAcademicPeriod', // Pass the active academic period
            'preEnrollmentSettings', // Pass all settings for admin to manage open/close
            'isAdmin' // Pass flag to determine if the user is an admin
        ));
    }
    
    
    private function determineYearLevel($student, $completedSubjects)
    {
        $allSubjectsCompleted = $completedSubjects->count() === $student->subjectsEnrolled()->count();
    
        if ($allSubjectsCompleted) {
            // Attempt to find the next year level
            $nextYearLevel = YearLevel::find($student->year_level_id + 1);
            
            // Return the next year level's name if found, otherwise "Graduated"
            return $nextYearLevel ? $nextYearLevel->name : 'Graduated';
        }
    
        // Check for the current year level; return "Unknown Year Level" if not found
        $currentYearLevel = YearLevel::find($student->year_level_id);
        return $currentYearLevel ? $currentYearLevel->name : '1st Year';
    }
    
    public function showStudentDetails($studentId)
    {
        // Fetch the student's details, including the current year level.
        $student = Student::find($studentId);
    
        return view('student-details', compact('student'));
    }
    
    public function showSettings()
    {
        // Retrieve the active academic period (i.e., the currently open school year and semester)
        $activeAcademicPeriod = PreEnrollmentSetting::with(['semester', 'schoolYear'])
                                                     ->where('is_open', true)
                                                     ->first();
    
        // Retrieve all pre-enrollment settings
        $preEnrollmentSettings = PreEnrollmentSetting::with(['semester', 'schoolYear'])->get();
    
        // Retrieve all semesters and school years for selection
        $semesters = Semester::all();
        $schoolYears = SchoolYear::all();
    
        // Pass the data to the view
        return view('pre-enrollment.admin.pre-enrollment-settings', compact('activeAcademicPeriod', 'preEnrollmentSettings', 'semesters', 'schoolYears'));
    }
    
public function storeSettings(Request $request)
{
    $request->validate([
        'semester_id' => 'required|exists:semesters,id',
        'school_year_id' => 'required|exists:school_years,id',
        'open_date' => 'required|date',
        'close_date' => 'required|date|after_or_equal:open_date',
    ]);

    // Create or update setting
    PreEnrollmentSetting::updateOrCreate(
        [
            'semester_id' => $request->semester_id,
            'school_year_id' => $request->school_year_id,
        ],
        $request->only('open_date', 'close_date')
    );

    return redirect()->back()->with('success', 'Pre-enrollment settings updated successfully.');
}
public function togglePreEnrollmentStatus($semesterId, $schoolYearId)
{
    // Retrieve the selected pre-enrollment setting by semester and school year
    $preEnrollmentSetting = PreEnrollmentSetting::where('semester_id', $semesterId)
                                                 ->where('school_year_id', $schoolYearId)
                                                 ->firstOrFail();

    if ($preEnrollmentSetting->is_open) {
        // If the selected period is already open, simply close it
        $preEnrollmentSetting->is_open = false;
        $preEnrollmentSetting->save();
    } else {
        // Close any currently open academic period with different semester and school year
        PreEnrollmentSetting::where('is_open', true)
                            ->where(function ($query) use ($semesterId, $schoolYearId) {
                                $query->where('semester_id', '!=', $semesterId)
                                      ->orWhere('school_year_id', '!=', $schoolYearId);
                            })
                            ->update(['is_open' => false]);

        // Open the selected period
        $preEnrollmentSetting->is_open = true;
        $preEnrollmentSetting->save();
    }

    // Redirect back to the settings page with a success message
    return redirect()->route('admin.pre-enrollment.settings')->with('success', 'Pre-enrollment status updated successfully.');
}



protected function calculateNextYearLevel($student)
{
    // Get the current year level and convert it to an index
    $currentYearLevel = $student->year_level_id;
    $yearLevels = ['1st Year' => 1, '2nd Year' => 2, '3rd Year' => 3, '4th Year' => 4];

    // Check if student has failed any subjects in the current year level
    $failedSubjects = DB::table('grades')
        ->join('subjects_enrolled', 'grades.subject_enrolled_id', '=', 'subjects_enrolled.id')
        ->where('subjects_enrolled.student_id', $student->id)
        ->where('subjects_enrolled.year_level_id', $currentYearLevel)
        ->where('grades.final', '>', 3) // Failed if final grade > 3
        ->exists();

    if ($failedSubjects) {
        // If there are failed subjects, the student stays in the current year level as irregular
        return $currentYearLevel;
    } else {
        // Promote to the next year level if no subjects are failed
        return $currentYearLevel < 4 ? $currentYearLevel : $currentYearLevel; // Cap at 4th Year
    }
}
protected function getUncompletedSubjects($student)
{
    return DB::table('subjects_enrolled')
        ->join('grades', 'subjects_enrolled.id', '=', 'grades.subject_enrolled_id')
        ->join('subjects_prospectus', 'subjects_enrolled.prospectus_id', '=', 'subjects_prospectus.id')
        ->where('subjects_enrolled.student_id', $student->id)
        ->where('grades.final', '>', 3) // Select only failed subjects
        ->select('subjects_prospectus.*')
        ->get();
}
protected function getEligibleSubjects($student, $nextYearLevel)
{
    $failedSubjects = $this->getUncompletedSubjects($student);

    // Fetch all subjects the student can take in the next year level
    $eligibleSubjects = DB::table('subjects_prospectus')
        ->where('program_id', $student->program_id)
        ->where('year_level_id', $nextYearLevel)
        ->whereNotIn('id', $failedSubjects->pluck('id')) // Exclude failed subjects that are prerequisites
        ->get();

    return $eligibleSubjects;
}

public function submitPreEnrollment(Request $request)
{
    try {
        Log::info('Pre-enrollment submission initiated.', ['user_id' => auth()->id()]);

        // Validate incoming request data
        $request->validate([
            'birthDate' => 'required|date',
            'sex' => 'required|string',
            'religion' => 'required|string',
            'status' => 'required|string',
            'birthplace' => 'required|string',
            'address' => 'required|string',
            'father' => 'required|string',
            'fatheroccupation' => 'required|string',
            'mother' => 'required|string',
            'motheroccupation' => 'required|string',
            'prevschool' => 'required|string',
            'prevschooladdress' => 'required|string',
            'contactNumber' => 'nullable|string',
            'program_id' => 'required|exists:departments,id',
            'year_level' => 'required|string',
            'schedule' => 'required|exists:schedules,id',
        ]);
        Log::info('Validation passed.', ['request_data' => $request->all()]);

        // Get the authenticated student
        $student = auth()->user()->student;
        if (!$student) {
            Log::warning('Student record not found for the user.', ['user_id' => auth()->id()]);
            return redirect()->back()->with('error', 'Student record not found.');
        }

        // Update the student record
        $student->update([
            'Birthday' => $request->input('birthDate'),
            'Gender' => $request->input('sex'),
            'Religion' => $request->input('religion'),
            'Status' => $request->input('status'),
            'BirthPlace' => $request->input('birthplace'),
            'Address' => $request->input('address'),
            'father_name' => $request->input('father'),
            'father_occupation' => $request->input('fatheroccupation'),
            'mother_name' => $request->input('mother'),
            'mother_occupation' => $request->input('motheroccupation'),
            'previous_school' => $request->input('prevschool'),
            'previous_school_adress' => $request->input('prevschooladdress'),
            'contact' => $request->input('contactNumber'),
        ]);
        Log::info('Student record updated.', ['student_id' => $student->id]);

        // Fetch the selected program and year level
        $selectedProgram = Department::find($request->input('program_id'));
        $selectedYearLevel = $request->input('year_level');
        Log::info('Program and year level retrieved.', [
            'program_id' => $selectedProgram->id,
            'year_level' => $selectedYearLevel,
        ]);
        $activeAcademicPeriod = PreEnrollmentSetting::where('is_open', 1)
        ->whereDate('open_date', '<=', now())
        ->whereDate('close_date', '>=', now())
        ->first();
    
    if (!$activeAcademicPeriod) {
        return redirect()->back()->with('error', 'No active enrollment period.');
    }
    
    $selectedSemester = $activeAcademicPeriod->semester_id;
    $selectedSchoolYear = $activeAcademicPeriod->school_year_id;
    
        // Fetch the chosen schedule
        $schedule = Schedule::with(['program', 'section'])->find($request->input('schedule'));

        if (!$schedule) {
            Log::warning('Schedule not found.', ['schedule_id' => $request->input('schedule')]);
            return redirect()->back()->with('error', 'Schedule not found.');
        }
        
        $selectedProgram = $schedule->program;
        $selectedSection = $schedule->section_id;
        
        if (!$selectedProgram || !$selectedSection) {
            Log::warning('Program or section not found for the selected schedule.', [
                'schedule_id' => $schedule->id,
                'program_id' => $selectedProgram->id ?? null,
                 'program_code' => $selectedProgram->code,
                'section_id' => $selectedSection ?? null,
            ]);
            return redirect()->back()->with('error', 'Program or section not found for the selected schedule.');
        }

$schedules = Schedule::where('semester_id', $selectedSemester)
    ->where('school_year_id', $selectedSchoolYear)
    ->where('program_id', $selectedProgram)
    ->where('year_level_id', $selectedYearLevel)
    ->where('section_id', $selectedSection)
    ->whereNotIn('id', function ($query) use ($student) {
        $query->select('schedule_id')
              ->from('subjects_enrolled')
              ->where('student_id', $student);
    })
    ->with(['subject', 'teacher', 'section'])
    ->get();



$schedulesData = [];
foreach ($schedules as $sectionId => $sectionSchedules) {
    $section = $sectionSchedules->first()->section;
    $schedulesData[$sectionId] = [
        'section_name' => $section->name,
        'schedules' => $sectionSchedules->map(function($schedule) {
            return [
                'id' => $schedule->id,
                'subject_code' => $schedule->subject->subject_code ?? '',
                'subject_description' => $schedule->subject->description ?? '',
                'lec_units' => $schedule->subject->lec_units ?? 0,
                'lab_units' => $schedule->subject->lab_units ?? 0,
                'room' => $schedule->room ?? '',
                'teacher_name' => $schedule->teacher->FullName ?? '',
                'days' => $schedule->days ?? '',
                'time' => $schedule->time ?? '',
            ];
        }),
    ];
}

// Pass the schedules to the PDF view

        // Retrieve the semester and school year
        $semester = Semester::find($schedule->semester_id);
        $schoolYear = SchoolYear::find($schedule->school_year_id);
        Log::info('Semester and school year retrieved.', [
            'semester_id' => $semester->id,
            'school_year_id' => $schoolYear->id,
        ]);

        // Find the corresponding prospectus
        $prospectus = SubjectsProspectus::where([
            ['program_id', $schedule->program_id],
            ['year_level_id', $schedule->year_level_id],
            ['subject_id', $schedule->subject_id],
        ])->first();

        if (!$prospectus) {
            Log::error('No prospectus entry found.', [
                'program_id' => $schedule->program_id,
                'year_level_id' => $schedule->year_level_id,
                'subject_id' => $schedule->subject_id,
            ]);
            return redirect()->back()->with('error', 'No prospectus entry found for the selected schedule.');
        }
        Log::info('Prospectus found.', ['prospectus_id' => $prospectus->id]);

        // Insert into subjects_enrolled
        DB::table('subjects_enrolled')->insert([
            'student_id' => $student->id,
            'subject_id' => $schedule->subject_id,
            'section_id' => $schedule->section_id,
            'schedule_id' => $schedule->id,
            'semester_id' => $schedule->semester_id,
            'school_year_id' => $schedule->school_year_id,
            'year_level_id' => $schedule->year_level_id,
            'prospectus_id' => $prospectus->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Log::info('Subject enrolled successfully.', ['student_id' => $student->id]);

        // Generate a unique reference code
        $enrollmentReferenceCode = 'REF' . strtoupper(uniqid());

        $semester = $activeAcademicPeriod->semester->name ?? 'N/A';
        $schoolYear = $activeAcademicPeriod->schoolYear->name ?? 'N/A';
        
        $pdf = Pdf::loadView('pdf.pre_enrollment', [
            'student' => $student,
            'referenceCode' => $enrollmentReferenceCode,
            'program' => $selectedProgram->name,
            'yearLevel' => $selectedYearLevel,
            'semester' => $semester,
            'schoolYear' => $schoolYear,
            'schedules' => $schedulesData,
        ])
        ->setPaper([0, 0, 612, 1008], 'portrait')
        ->setOption('margin-top', 5)
        ->setOption('margin-right', 5)
        ->setOption('margin-bottom', 5)
        ->setOption('margin-left', 5);
        
        
        Log::info('PDF generated.', ['student_id' => $student->id]);

        // Send email with PDF attachment
        Mail::to($student->user->email)->send(new PreEnrollmentConfirmation($student, $enrollmentReferenceCode, $pdf));
        Log::info('Email sent.', ['email' => $student->user->email]);

        // Notify the student
        $student->user->notify(new PreEnrollmentSubmittedNotification($enrollmentReferenceCode));
        Log::info('Notification sent.', ['student_id' => $student->id]);

        return redirect()->route('pre-enrollment.form')->with('success', 'Pre-enrollment successfully completed.');
    } catch (\Exception $e) {
        Log::error('Error during pre-enrollment submission.', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        return redirect()->back()->with('error', 'An unexpected error occurred. Please try again.');
    }
}


public function preview(Request $request)  
{
    // Get the authenticated student's information
    $student = auth()->user()->student;

    // Fetch the selected data from the request
    $selectedProgram = Department::find($request->query('program'));  // Fetch program by ID
    $selectedYearLevel = $request->query('year_level');  // Get year level directly from request
    $selectedSemester = Semester::find($request->query('semester_id'));  // Fetch semester by ID
    $selectedSchoolYear = SchoolYear::find($request->query('school_year_id'));  // Fetch school year by ID
    $selectedSchedule = $request->query('schedule');  // Get selected schedule from query
    // Personal information fetched from form
    $selectedFullName = $request->input('fullName');
    $selectedBirthDate = $request->input('birthDate');
    $selectedGender = $request->input('sex');
    $selectedReligion = $request->input('religion');
    $selectedStatus = $request->input('status');
    $selectedBirthPlace = $request->input('birthplace');
    $selectedAddress = $request->input('address');
    $selectedGender = $request->input('sex');
    $fatherName = $request->input('father');
    $fatherOccupation = $request->input('fatheroccupation');
    $motherName = $request->input('mother');
    $motherOccupation = $request->input('motheroccupation');
    $previousSchool = $request->input('prevschool');
    $previousSchoolAddress = $request->input('prevschooladdress');
    $contactNumber = $request->input('contact');
    // Fetch the selected schedule details, including relationships
    
    $scheduleDetails = Schedule::with(['subject', 'teacher', 'section', 'semester', 'schoolYear', 'program'])
        ->find($selectedSchedule);

    // Check if any of the required entities are null
    if (!$selectedProgram || !$selectedSemester || !$selectedSchoolYear || !$scheduleDetails) {
        return redirect()->back()->with('error', 'Invalid selection. Please make sure to select a valid program, semester, school year, and schedule.');
    }

    // Pass the data to the preview view
    return view('pre-enrollment.preview', compact(
        'student', 
        'selectedFullName',
        'selectedBirthDate',
        'selectedGender',
        'selectedReligion',
        'selectedStatus',
        'selectedBirthPlace',
        'selectedAddress',
        
        'selectedSemester',
        'selectedSchoolYear',
        'selectedGender',
        'fatherName', 
        'fatherOccupation', 
        'motherName', 
        'motherOccupation', 
        'previousSchool', 
        'previousSchoolAddress', 
        'contactNumber',
        'scheduleDetails' 
    ));
}
public function preenrollmentphead()
{
    // Get the active academic school year and semester
    $activeEnrollmentSetting = PreEnrollmentSetting::where('is_open', true)->first();

    if (!$activeEnrollmentSetting) {
        return redirect()->back()->with('error', 'No active enrollment period found.');
    }

    // Get the department ID of the authenticated program head
    $departmentId = auth()->user()->employee->department_id;

    // Fetch sections with year levels and schedules for the active school year and semester
    $sections = Section::whereHas('schedules', function ($query) use ($activeEnrollmentSetting, $departmentId) {
        $query->where('school_year_id', $activeEnrollmentSetting->school_year_id)
              ->where('semester_id', $activeEnrollmentSetting->semester_id)
              ->where('program_id', $departmentId);
    })
    ->with(['schedules' => function ($query) use ($activeEnrollmentSetting) {
        $query->with(['subject', 'teacher', 'program', 'semester', 'schoolYear'])
              ->orderBy('year_level_id', 'asc');
    }])
    ->get()
    ->map(function ($section) {
        // Group schedules by year level
        $section->schedules_by_year_level = $section->schedules->groupBy('year_level_id');
        return $section;
    });

    return view('pre-enrollment.phead.preenrollment', compact('sections', 'activeEnrollmentSetting'));
}



public function viewSchedules(Section $section, $year_level_id)
{
    // Fetch the active enrollment setting
    $activeEnrollmentSetting = PreEnrollmentSetting::where('is_open', true)->first();

    if (!$activeEnrollmentSetting) {
        return redirect()->route('phead.preenrollment')->with('error', 'No active enrollment period found.');
    }

    // Get schedules for the specified section and year level, matching the active school year and semester
    $schedules = $section->schedules()
        ->where('school_year_id', $activeEnrollmentSetting->school_year_id)
        ->where('semester_id', $activeEnrollmentSetting->semester_id)
        ->where('year_level_id', $year_level_id)
        ->with(['subject', 'teacher', 'program', 'semester', 'schoolYear'])
        ->get();

    return view('pre-enrollment.phead.view-schedules', compact('section', 'schedules', 'activeEnrollmentSetting', 'year_level_id'));
}



public function toggleLock(Section $section, $year_level_id)
{
    // Find or create the specific year-level lock status for the section
    $lockStatus = SectionYearLevelLock::firstOrCreate(
        ['section_id' => $section->id, 'year_level_id' => $year_level_id]
    );

    // Toggle the lock status
    $lockStatus->is_locked = !$lockStatus->is_locked;
    $lockStatus->save();

    return response()->json([
        'success' => true,
        'message' => 'Section ' . ($lockStatus->is_locked ? 'locked' : 'unlocked') . ' successfully.',
        'is_locked' => $lockStatus->is_locked,
    ]);
}


public function getSchedules(Request $request)
{
    $programId = $request->query('program_id');
    $yearLevel = $request->query('year_level');
    $yearLevelId = $this->mapYearLevelToId($yearLevel);

    // Fetch schedules grouped by section
    $schedules = Schedule::with(['subject', 'section', 'teacher'])
                         ->where('program_id', $programId)
                         ->where('year_level_id', $yearLevelId)
                         ->get()
                         ->groupBy('section.id'); // Group by section ID

    $schedulesData = [];
    foreach ($schedules as $sectionId => $sectionSchedules) {
        $section = $sectionSchedules->first()->section;
        $enrolledCount = $section->subjectsEnrolled->count(); // Count of enrolled students

        // Get the specific lock status for the section and year level
        $lockStatus = SectionYearLevelLock::where('section_id', $sectionId)
                                          ->where('year_level_id', $yearLevelId)
                                          ->first();
        $isLocked = $lockStatus ? $lockStatus->is_locked : false;

        $schedulesData[$sectionId] = [
            'section_name' => $section->name,
            'max_enrollment' => $section->max_enrollment,
            'enrolled_count' => $enrolledCount,
            'is_locked' => $isLocked,
            'schedules' => $sectionSchedules->map(function($schedule) {
                return [
                    'id' => $schedule->id ?? '',
                    'subject_code' => $schedule->subject->subject_code ?? '',
                    'subject_description' => $schedule->subject->description ?? '',
                    'subject_lecture' => $schedule->subject->lec_units ?? '',
                    'subject_lab' => $schedule->subject->lab_units ?? '',
                    'subject_units' => $schedule->subject->units ?? '',
                    'room' => $schedule->room ?? '',
                    'teacher_name' => $schedule->teacher->FullName ?? '',
                    'time' => $schedule->time ?? '',
                    'days' => $schedule->days ?? '',
                ];
            }),
        ];
    }

    return response()->json(['schedules' => $schedulesData]);
}


 // Retrieve schedules grouped by sections, with capacity and availability checks
 public function getSchedulescopy(Request $request)
 {
     $programId = $request->query('program_id');
     $yearLevel = $request->query('year_level');
     $yearLevelId = $this->mapYearLevelToId($yearLevel);

     $schedules = Schedule::with(['subject', 'section', 'teacher'])
                          ->where('program_id', $programId)
                          ->where('year_level_id', $yearLevelId)
                          ->get()
                          ->groupBy('section.name');

     $schedulesData = [];
     foreach ($schedules as $sectionName => $sectionSchedules) {
         $section = $sectionSchedules->first()->section;
         $enrollmentCount = SubjectEnrolled::where('section_id', $section->id)->count();
         $isAvailable = $section->capacity > $enrollmentCount && $section->is_unlocked;

         $schedulesData[$sectionName] = [
             'section_id' => $section->id,
             'section_name' => $sectionName,
             'isAvailable' => $isAvailable,
             'capacity' => $section->capacity,
             'enrolled_count' => $enrollmentCount,
             'schedules' => $sectionSchedules->map(function($schedule) {
                 return [
                     'id' => $schedule->id,
                     'subject_code' => $schedule->subject->subject_code,
                     'teacher_name' => $schedule->teacher->FullName,
                     'start_time' => $schedule->start_time,
                     'end_time' => $schedule->end_time,
                     'days' => $schedule->days,
                 ];
             }),
         ];
     }

     return response()->json([
         'schedules' => $schedulesData
     ]);
 }

 // Enroll a student in a specific section schedule, with capacity and availability checks
 public function enrollStudentInSection(Request $request)
 {
     $studentId = $request->input('student_id');
     $sectionId = $request->input('section_id');
     $scheduleId = $request->input('schedule_id');

     // Fetch section details
     $section = Section::find($sectionId);

     // Check if section is unlocked and has capacity
     if (!$section->is_unlocked) {
         return response()->json(['error' => 'This section is currently locked for enrollment.'], 403);
     }

     $enrollmentCount = SubjectEnrolled::where('section_id', $sectionId)->count();

     if ($enrollmentCount >= $section->capacity) {
         return response()->json(['error' => 'This section is already at full capacity.'], 403);
     }

     // Proceed with enrollment
     SubjectEnrolled::create([
         'student_id' => $studentId,
         'section_id' => $sectionId,
         'schedule_id' => $scheduleId,
         // Include other required fields like subject_id, semester_id, etc.
     ]);

     return response()->json(['success' => 'Enrollment successful!']);
 }

 // Helper function to map year level names to IDs
 private function mapYearLevelToId($yearLevel)
 {
     $yearLevelMapping = [
         '1st Year' => 1,
         '2nd Year' => 2,
         '3rd Year' => 3,
         '4th Year' => 4,
     ];

     return $yearLevelMapping[$yearLevel] ?? null;
 }



}

