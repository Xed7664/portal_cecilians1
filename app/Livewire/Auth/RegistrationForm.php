<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\{User, Student, Employee, SystemSetting};
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\{Hash, Notification, Auth, Session};
use Illuminate\Support\Str;
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
        $isProgramHead = Employee::where('EmployeeID', $schoolId)->exists();
        $isTeacher = Employee::where('EmployeeID', $schoolId)->exists();
        $isAdmin = Employee::where('EmployeeID', $schoolId)->exists();

        if (!$isStudent && !$isProgramHead) {
            return back()->with('error', 'School ID not associated.');
        }

        if ($isStudent) {
            $userType = 'student';
        } elseif ($isTeacher) {
            $userType = 'teacher';
        } elseif ($isProgramHead) {
            $userType = 'program_head';
        } elseif ($isAdmin) {
            $userType = 'admin';
        } else {
            $userType = 'employee'; // Default or fallback user type if needed
        }

        $userIdField = $isStudent ? 'student_id' : 'employee_id';

        $userExists = User::where($userIdField, $schoolId)->exists();

        if ($userExists) {
            return back()->with('error', 'An account with this ID already exists.');
        }

        // Check if the provided birthdate matches the system
        if (($isStudent || $isProgramHead) && ($student = Student::where('StudentID', $schoolId)->first() ?? Employee::where('EmployeeID', $schoolId)->first())) {
            if ($student->Birthday != $this->birthdate) {
                return back()->with('error', 'The provided birthdate does not match our records.');
            }
        }

        // Generate a unique google_id
        $googleId = $this->generateUniqueGoogleId();

        $user = User::create([
            'username' => $this->username,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'type' => $userType,
            $userIdField => $schoolId, // Set either "student_id" or "employee_id" based on the type
            'google_id' => $googleId, // Save the generated Google ID
        ]);

        // Send an email
        event(new Registered($user));

        Auth::login($user);

        UserSessionService::storeUserPreferences($user);

        return redirect()->route('verify')->with('success', 'Registration and login successful!');
    }

    /**
     * Generate a unique Google ID.
     *
     * @return string
     */
    protected function generateUniqueGoogleId()
    {
        do {
            $googleId = Str::uuid()->toString(); // Generate a unique UUID
        } while (User::where('google_id', $googleId)->exists());

        return $googleId;
    }

    public function render()
    {
        return view('livewire.auth.registration-form');
    }
}
