<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Employee, Student, SystemSetting, User};
use Illuminate\Support\Facades\{Auth, Hash, Session};
use Laravel\Socialite\Facades\Socialite;
use App\Services\UserSessionService; // Import the UserSessionService

class AuthController extends Controller
{
    public function login(Request $request)
{
    // Validate the login credentials
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        // Store user preferences in the session
        UserSessionService::storeUserPreferences($user);

        // Check if the user is a student and if pre-enrollment is not completed
        if ($user->type === 'student' && !session('pre_enrollment_completed')) {
            return redirect()->route('pre-enrollment.form'); // Redirect to the pre-enrollment page
        }

        // Check if the user is a program head and redirect to the dashboard
        if ($user->type === 'program_head') {
            return redirect()->route('phead.dashboard'); // Adjust the route to the actual route for the dashboard
        }

        // Otherwise, proceed with the normal login flow
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
    
}

