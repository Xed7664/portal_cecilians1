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

class PreEnrollmentController extends Controller
{
// PreEnrollmentController.php
public function showForm()
{
    $student = auth()->user()->student;

    // Fetch available programs
    $programs = Department::all();
    
    // Fetch all semesters
    $semesters = Semester::all();

    // Fetch all school years
    $schoolYears = SchoolYear::all();  // Add this line

    // Fetch the latest semester and school year
    $currentSemester = Semester::latest()->first();
    $currentSchoolYear = SchoolYear::latest()->first();

    if (!$currentSemester || !$currentSchoolYear) {
        return redirect()->back()->with('error', 'No semesters or school years found in the system.');
    }

    // Define available year levels
    $yearLevels = ['1st Year', '2nd Year', '3rd Year', '4th Year'];

    $uncompletedSubjects = null; // Initialize variable to avoid undefined error
    $subjects = null; // New variable to hold subjects for new students

    // Handle new students: automatically set year level to 'First Year'
    if ($student->student_type == 'new') {
        $student->year_level = '1st Year';
        // Fetch subjects for 1st-year new students
        $subjects = SubjectsProspectus::getSubjectsForYearLevel($student->program_id, '1st Year', $currentSemester->id);
    } else {
        // For old students, fetch enrolled subjects for the latest semester
        $enrolledSubjects = DB::table('subjects_enrolled')
            ->where('student_id', $student->id)
            ->where('semester_id', $currentSemester->id)
            ->where('school_year_id', $currentSchoolYear->id)
            ->get();
        
        // Determine the student's next year level based on completed subjects
        if ($enrolledSubjects->isEmpty()) {
            $student->year_level = '1st Year'; // Assume first year if no enrollments
        } else {
            $student->year_level = $this->calculateNextYearLevel($student);
        }

        // Fetch schedules for uncompleted subjects only for old students
        $uncompletedSubjects = $this->getUncompletedSubjects($student);
    }

    // Pass the fetched semesters and school years to the view
    return view('pre-enrollment.form', compact('student', 'currentSemester', 'currentSchoolYear', 'programs', 'yearLevels', 'uncompletedSubjects', 'subjects', 'semesters', 'schoolYears'));  // Add 'schoolYears'
}



private function calculateNextYearLevel($student)
{
    // Fetch all completed subjects from the subjects_enrolled table
    $completedSubjectsCount = SubjectEnrolled::where('student_id', $student->id)
        ->whereHas('grades', function($query) {
            $query->where('final', '<=', 3.0); // Assuming grade <= 3.0 is a passing grade
        })
        ->count();

    // Adjust year level thresholds based on your university's rules
    if ($completedSubjectsCount >= 40) {
        return '3rd Year';
    } elseif ($completedSubjectsCount >= 20) {
        return '2nd Year';
    } else {
        return '1st Year';
    }
}

private function getUncompletedSubjects($student)
{
    $currentSemester = Semester::latest()->first(); // Fetch current semester

    return SubjectsProspectus::with('subject') // Load related subjects
        ->where('program_id', $student->program_id)
        ->where('year_level_id', $student->year_level) // Ensure correct year_level_id
        ->where('semester_id', $currentSemester->id)
        ->whereDoesntHave('subject.schedules.enrollments', function ($query) use ($student) {
            $query->where('student_id', $student->id)
                  ->whereHas('grades', function ($subQuery) {
                      $subQuery->whereNotNull('final'); // Ensure final grade is set
                  });
        })
        ->get();
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

