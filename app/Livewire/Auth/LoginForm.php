<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Rule;
use App\Models\{Employee, Student, User, SystemSetting};
use App\Services\UserSessionService;
use Illuminate\Support\Facades\{Auth, Session};
use Illuminate\Http\Request;

class LoginForm extends Component
{
    #[Rule('required', message: 'Please enter your email or username.')]
    public $username;

    #[Rule('required', message: 'Please enter your password.')]
    public $password;

    #[Rule('boolean', message: 'Invalid input for the Remember Me checkbox.')]
    public $remember = false;

    public function login(Request $request)
{
    $this->validate();

    $loginField = filter_var($this->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    $credentials = [
        $loginField => $this->username,
        'password' => $this->password,
    ];

    $rememberMe = $this->remember;

    if (Auth::attempt($credentials, $rememberMe)) {
        $user = Auth::user();

        if ($user->status === 'banned') {
            Auth::logout();
            return back()->with('error', 'Your account has been banned.');
        }

        if (!SystemSetting::isLoginEnabled()) {
            if (!$user->hasPermission('access_admin')) {
                return back()->with('error', 'Login has been temporarily disabled.');
            }
        }

        UserSessionService::storeUserPreferences($user);

        // Log the login details
        $ipAddress = $request->ip();
        $userAgent = $request->header('User-Agent');

        // Save to login_logs table
        $user->loginLogs()->create([
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);

        // Check if the user is a student
        if ($user->type === 'student') {
            $student = $user->student;

            // Check if the student has enrolled subjects
            $hasSubjectsEnrolled = \DB::table('subjects_enrolled')
                ->where('student_id', $student->id)
                ->exists();

            // Redirect to pre-enrollment form if the student has not completed pre-enrollment or is a freshman
            if (!$student->pre_enrollment_completed) {
                return redirect()->intended(route('pre-enrollment.form'))->with('success', 'Please complete pre-enrollment.');
            }

            // Redirect to newsfeed if the student has already enrolled in subjects
            if ($student->pre_enrollment_completed && $hasSubjectsEnrolled) {
                return redirect()->intended(route('newsfeed'))->with('success', 'Login successful.');
            }
        }    // Check if the user is a teacher
        elseif ($user->type === 'teacher') {
            return redirect()->intended(route('teacher.dashboard'))->with('success', 'Login successful.');
        }

        // Redirect to newsfeed for all other users or students who have completed pre-enrollment
        return redirect()->intended(route('newsfeed'))->with('success', 'Login successful.');
    } else {
        return back()->with('error', 'Invalid login credentials.');
    }
}

    


    public function render()
    {
        return view('livewire.auth.login-form');
    }
}
