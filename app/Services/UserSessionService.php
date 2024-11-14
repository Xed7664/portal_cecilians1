<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use App\Models\{Employee, Student, SubjectEnrolled, SystemSetting, User, Section, SubjectsProspectus};

class UserSessionService
{
    public static function storeUserPreferences(User $user)
    {
        if ($user->type === 'student') {
            $student = Student::where('StudentID', $user->student_id)->first();
            session()->put('student', $student);
            session()->put('panel', 'student');

            // Fetch the latest school year and semester based on student's latest enrollment
            $latestEnrollment = SubjectEnrolled::where('student_id', $student->id)
                ->orderBy('school_year_id', 'desc')
                ->first();

            // Fetch and store the current school year and semester
            $currentSchoolYearId = $latestEnrollment->school_year_id ?? SystemSetting::getSetting('current_school_year_id');
            $currentSemesterId = $latestEnrollment->semester_id ?? SystemSetting::getSetting('current_semester_id');

            Session::put('current_school_year_id', $currentSchoolYearId);
            Session::put('current_semester_id', $currentSemesterId);

        } elseif ($user->type === 'teacher') {
            $employee = Employee::where('EmployeeID', $user->employee_id)->first();
            session()->put('employee', $employee);
            session()->put('panel', 'teacher');

        } elseif ($user->type === 'program_head') {
            $employee = Employee::where('EmployeeID', $user->employee_id)->first();
            session()->put('employee', $employee);
            session()->put('panel', 'program_head');

            // Store the department ID for the program head to use for filtering as program_id
            session()->put('program_id', $employee->department_id);

            // Count and store the data for the program head's dashboard
            $studentsCount = Student::where('program_id', $employee->department_id)->count();
            $subjectsCount = SubjectsProspectus::where('program_id', $employee->department_id)->count();

            // Retrieve sections based on the current program's students and count distinct sections
            $sectionsCount = Section::whereIn('id', function ($query) use ($employee) {
                $query->select('section_id')
                      ->from('students')
                      ->where('program_id', $employee->department_id)
                      ->distinct();
            })->count();    

            $prospectusCount = SubjectsProspectus::where('program_id', $employee->department_id)->distinct('program_id')->count();

            session()->put('dashboard_data', [
                'studentsCount' => $studentsCount,
                'subjectsCount' => $subjectsCount,
                'sectionsCount' => $sectionsCount,
                'prospectusCount' => $prospectusCount,
            ]);

        } elseif ($user->type === 'admin') {
            $employee = Employee::where('EmployeeID', $user->employee_id)->first();
            session()->put('employee', $employee);
            session()->put('panel', 'admin');
        }

        // Set the initial theme for the user
        Session::put('theme', 'light'); // Adjust if needed
    }
}