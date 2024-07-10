<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\{User, Student, Employee, SystemSetting};
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\{Hash, Notification, Auth, Session};
use App\Notifications\WelcomeEmail; 
use Illuminate\Auth\Events\Registered;
use App\Services\UserSessionService;

class RegistrationForm extends Component
{

    #[Rule('required', message: 'School ID is required.')]
    public $school_id;

    #[Rule('required', message: 'Birthdate is required.')]
    public $birthdate;

    #[Rule('required', message: 'Username is required.')]
    #[Rule('unique:users', message: 'Username is already taken.')]
    #[Rule('regex:/^[A-Za-z0-9_.]+$/', message: 'Username format is invalid.')]
    #[Rule('between:3,30', message: 'Username length should be between 3 and 30 characters.')]
    public $username;

    #[Rule('required', message: 'Email is required.')]
    #[Rule('email', message: 'Invalid email format.')]
    #[Rule('unique:users', message: 'Email is already registered.')]
    public $email;

    #[Rule('required', message: 'Password is required.')]
    #[Rule('regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z\d]).+$/', message: 'Password format is invalid.')]
    #[Rule('min:8', message: 'Password should be at least 8 characters long.')]
    public $password;

    #[Rule('accepted', message: 'Please agree to the terms and privacy policy.')]
    public $terms_agreed;

    public function register()
    {
        $this->validate();

        // Check if registration is enabled
        if (!SystemSetting::isRegistrationEnabled()) {
            return back()->with('error', 'Registration is not currently enabled.');
        }

        $schoolId = 'SCC-' . $this->school_id;

        $isStudent = Student::where('StudentID', $schoolId)->exists();
        $isEmployee = Employee::where('EmployeeID', $schoolId)->exists();
    
        if (!$isStudent && !$isEmployee) {
            return back()->with('error', 'School ID not associated.');
        }

        $userType = $isStudent ? 'student' : 'employee';
        $userIdField = $isStudent ? 'student_id' : 'employee_id';

        $userExists = User::where($userIdField, $schoolId)->exists();

        if ($userExists) {
            return back()->with('error', 'An account with this ID already exists.');
        }

        // Check if the provided birthdate matches the system
        if (($isStudent || $isEmployee) && ($student = Student::where('StudentID', $schoolId)->first() ?? Employee::where('EmployeeID', $schoolId)->first())) {
            if ($student->Birthday != $this->birthdate) {
                return back()->with('error', 'The provided birthdate does not match our records.');
            }
        }


        $user = User::create([
            'username' => $this->username,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'type' => $userType, // Set the user type (student or employee)
            $userIdField => $schoolId, // Set either "student_id" or "employee_id" based on the type
        ]);

        // Send an email
        event(new Registered($user));

        Auth::login($user);
        
        UserSessionService::storeUserPreferences($user);

        //return redirect()->route('login')->with('success', 'Registration successful!');
        //dd('I am still debugging, it should stop here. But you can login anyway. ~James');
        return redirect()->route('verify')->with('success', 'Registration and login successful!');

        // Redirect or perform any other actions as needed
    }

    public function render()
    {
        return view('livewire.auth.registration-form');
    }
}
