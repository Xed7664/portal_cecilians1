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
use Illuminate\Notifications\Notifiable;
use App\Mail\PreEnrollmentConfirmation;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf; // Import the Pdf facade here
use App\Models\PreEnrollmentSetting;

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
            $nextYearLevel = YearLevel::find($student->year_level_id + 1);
            return $nextYearLevel ? $nextYearLevel->name : 'Graduated';
        }
        return YearLevel::find($student->year_level_id)->name;
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
        'contact' => 'required|string',
        'program' => 'required|exists:departments,id',
        'year_level' => 'required|string',
        'schedule' => 'required|exists:schedules,id',
    ]);

    // Get the authenticated student
    $student = auth()->user()->student;

    // Update the student record with the new values
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
        'contact' => $request->input('contact'),
    ]);
      // Fetch the selected program and year level
      $selectedProgram = Department::find($request->input('program'));  // Fetch program by ID
      $selectedYearLevel = $request->input('year_level');  // Get year level directly from request
  
      // Fetch the chosen schedule based on the selected schedule ID
      $schedule = Schedule::find($request->input('schedule'));
     // Retrieve the semester and school year based on the selected schedule
     $semester = Semester::find($schedule->semester_id); // Fetch semester model
     $schoolYear = SchoolYear::find($schedule->school_year_id); // Fetch school year model
      // Find the corresponding prospectus using the schedule's subject, program, and year level
      $prospectus = SubjectsProspectus::where('program_id', $schedule->program_id)
                      ->where('year_level_id', $schedule->year_level_id)
                      ->where('subject_id', $schedule->subject_id)
                      ->first();
  
      if (!$prospectus) {
          // Handle the case where no prospectus entry exists for the selected schedule
          return redirect()->back()->with('error', 'No prospectus entry found for the selected schedule.');
      }
  
      // Insert into subjects_enrolled with the prospectus_id based on the chosen schedule
      DB::table('subjects_enrolled')->insert([
          'student_id' => $student->id,
          'subject_id' => $schedule->subject_id,
          'section_id' => $schedule->section_id,
          'schedule_id' => $schedule->id,
          'semester_id' => $schedule->semester_id,
          'school_year_id' => $schedule->school_year_id,
          'year_level_id' => $schedule->year_level_id,
          'prospectus_id' => $prospectus->id, // Include prospectus_id based on schedule
          'created_at' => now(),
          'updated_at' => now(),
      ]);
  
      // Generate a unique reference code for the pre-enrollment
      $enrollmentReferenceCode = 'REF' . strtoupper(uniqid());
      $pdf = Pdf::loadView('pdf.pre_enrollment', [
        'student' => $student,
        'referenceCode' => $enrollmentReferenceCode,
        'program' => $selectedProgram->name,
        'yearLevel' => $selectedYearLevel,
        'semester' => $semester->name,
        'schoolYear' => $schoolYear->name,
    ])
    ->setPaper([0, 0, 612, 1008], 'portrait') // Approx 8.5 x 13 inches
    ->setOption('margin-top', 5)
    ->setOption('margin-right', 5)
    ->setOption('margin-bottom', 5)
    ->setOption('margin-left', 5);

  
      // Send email with PDF attachment
      Mail::to($student->user->email)->send(new PreEnrollmentConfirmation($student, $enrollmentReferenceCode, $pdf));
  
      // Notify the student (user) about the pre-enrollment
      $notification = new PreEnrollmentSubmittedNotification($enrollmentReferenceCode);
      $student->user->notify($notification); // Notify the related user
  
      // Store the notification manually in portal_notifications and user_portal_notifications
      $notification->storeInPortalNotifications($student);
  
      // Redirect with a success message
      return redirect()->route('pre-enrollment.form')->with('success', 'Pre-enrollment details have been successfully updated.');
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
    // Fetch sections with their current lock status and related schedules
    $sections = Section::with(['schedules' => function($query) {
        $query->with(['subject', 'teacher', 'program', 'semester', 'schoolYear']);
    }])->get();

    return view('pre-enrollment.phead.preenrollment', compact('sections'));
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
        $isLocked = $section->is_locked;

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
public function lockSection($sectionId)
{
    // Lock the section by setting is_locked to true
    $section = Section::findOrFail($sectionId);
    $section->is_locked = true;
    $section->save();

    return response()->json(['message' => 'Section locked successfully.']);
}

public function unlockSection($sectionId)
{
    // Unlock the section by setting is_locked to false
    $section = Section::findOrFail($sectionId);
    $section->is_locked = false;
    $section->save();

    return response()->json(['message' => 'Section unlocked successfully.']);
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

 // Method for program heads to toggle the lock/unlock status of a section
 public function toggleSectionLock($sectionId)
 {
     $section = Section::findOrFail($sectionId);
     $section->is_unlocked = !$section->is_unlocked;
     $section->save();

     return response()->json(['success' => 'Section lock status updated.']);
 }

}

