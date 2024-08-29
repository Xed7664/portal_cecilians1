<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Employee, Student, SystemSetting, User};
use Illuminate\Support\Facades\{Auth, Hash, Session};
use Laravel\Socialite\Facades\Socialite;
use App\Services\UserSessionService; // Import the UserSessionService

class AuthController extends Controller
{
    function login(Request $request)
    {
        // Validate the login credentials
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Store user preferences in the session
            UserSessionService::storeUserPreferences($user);

            // Redirect to the dashboard or an appropriate page based on user role
            return redirect()->intended($this->redirectTo($user));
        } else {
            return redirect()->back()->withErrors(['email' => 'Invalid credentials']);
        }
    }

    function registration()
    {
        // Check if registration is enabled
        $registrationEnabled = SystemSetting::isRegistrationEnabled();

        if ($registrationEnabled) {
            return view('auths.register');
        } else {
            return view('auths.registration_disabled');
        }
    }

    function verify()
    {
        return view('auths.verify');
    }

    function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect(route('login'));
    }

    // Method to determine where to redirect users based on their role
    public function redirectToDashboard()
    {
        $user = Auth::user();
    
        // Check the user's role and redirect accordingly
        switch ($user->type) {
            case 'program_head':
                return redirect()->route('program-head.dashboard');
            case 'student':
                return redirect()->route('student.dashboard');
            case 'teacher':
                return redirect()->route('teacher.dashboard');
            case 'admin':
                return redirect()->route('admin.dashboard');
            default:
                return redirect()->route('login'); // Default redirection if no match
        }
    }
    
}

