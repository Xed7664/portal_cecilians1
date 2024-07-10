<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use App\Models\{Employee, Student, User, SystemSetting};

class UserSessionService
{
    public static function storeUserPreferences(User $user)
    {
        if ($user->role === 'Student') {
            $student = Student::where('StudentID', $user->student_id)->first();
            session()->put('student', $student);
        } elseif ($user->role === 'Employee') {
            $employee = Employee::where('EmployeeID', $user->employee_id)->first();
            session()->put('employee', $employee);
        }

        // Fetch and store the current school year and semester settings in the session
        $currentSchoolYearId = SystemSetting::getSetting('current_school_year_id');
        $currentSemesterId = SystemSetting::getSetting('current_semester_id');
        Session::put('current_school_year_id', $currentSchoolYearId);
        Session::put('current_semester_id', $currentSemesterId);

        // Set the initial theme for the user
        Session::put('theme', 'light'); // You can set the default theme to 'light' or 'dark' as needed
        Session::put('panel', 'user');
    }
}
