<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Department;
use App\Models\Schedule;
use App\Models\Semester;
use App\Models\SchoolYear;
use App\Models\YearLevel;
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
        $student = auth()->user()->student;
    
        // Fetch student_type from the admissions table if needed
        $admissionDetails = DB::table('admissions')
            ->where('email', auth()->user()->email) // Assuming user email matches admissions table
            ->select('student_type')
            ->first();
    
        // Add student_type to the student object if it's not already present
        if ($admissionDetails) {
            $student->student_type = $admissionDetails->student_type;
        }
    
        // Fetch other data as in your original code
        $programs = Department::all();
        $semesters = Semester::all();
        $schoolYears = SchoolYear::all();
        $currentSemester = Semester::latest()->first();
        $currentSchoolYear = SchoolYear::latest()->first();
    
        if (!$currentSemester || !$currentSchoolYear) {
            return redirect()->back()->with('error', 'No semesters or school years found in the system.');
        }
    
        $yearLevels = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
        $uncompletedSubjects = null;
        $subjects = null;
    
        if ($student->student_type == 'new') {
            $student->year_level = '1st Year';
            $subjects = SubjectsProspectus::getSubjectsForYearLevel($student->program_id, '1st Year', $currentSemester->id);
        } else {
            $enrolledSubjects = DB::table('subjects_enrolled')
                ->where('student_id', $student->id)
                ->where('semester_id', $currentSemester->id)
                ->where('school_year_id', $currentSchoolYear->id)
                ->get();
    
            if ($enrolledSubjects->isEmpty()) {
                $student->year_level = '1st Year';
            } else {
                $student->year_level = $this->calculateNextYearLevel($student);
            }
    
            $uncompletedSubjects = $this->getUncompletedSubjects($student);
        }
    
        return view('pre-enrollment.form', compact('student', 'currentSemester', 'currentSchoolYear', 'programs', 'yearLevels', 'uncompletedSubjects', 'subjects', 'semesters', 'schoolYears'));
    }
    

public function showSettings()
{
    $semesters = Semester::all();
    $schoolYears = SchoolYear::all();
    $preEnrollmentSettings = PreEnrollmentSetting::with(['semester', 'schoolYear'])->latest()->get();

    return view('pre-enrollment.phead.pre-enrollment-settings', compact('semesters', 'schoolYears', 'preEnrollmentSettings'));
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
public function togglePreEnrollmentStatus($semesterId)
{
    // Retrieve the pre-enrollment setting by semester
    $preEnrollmentSetting = PreEnrollmentSetting::where('semester_id', $semesterId)->firstOrFail();

    // Toggle the status
    $preEnrollmentSetting->is_open = !$preEnrollmentSetting->is_open;
    $preEnrollmentSetting->save();

    return response()->json([
        'success' => true,
        'is_open' => $preEnrollmentSetting->is_open,
    ]);
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
        return $currentYearLevel < 4 ? $currentYearLevel + 1 : $currentYearLevel; // Cap at 4th Year
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
public function getSchedules(Request $request)
{
    $programId = $request->query('program_id');
    $yearLevel = $request->query('year_level');

    // Map the year level (example function)
    $yearLevelId = $this->mapYearLevelToId($yearLevel);

    // Fetch schedules including related subject and section
    $schedules = Schedule::with(['subject', 'section'])
                         ->where('program_id', $programId)
                         ->where('year_level_id', $yearLevelId)
                         ->get();

    // Prepare response data
    $schedulesData = $schedules->map(function($schedule) {
        return [
            'id' => $schedule->id,
            'subject_code' => $schedule->subject->subject_code, // Assuming 'code' is the subject code field
            'section_name' => $schedule->section->name, // Assuming 'name' is the section field
            'start_time' => $schedule->start_time,
            'end_time' => $schedule->end_time,
            'days' => $schedule->days, // Example of how days might be stored
        ];
    });

    return response()->json([
        'schedules' => $schedulesData
    ]);
}

// Example helper function to map year level name to ID
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

