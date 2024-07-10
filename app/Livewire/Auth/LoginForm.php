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
                // Check if the user has the 'access_admin' permission
                if (!$user->hasPermission('access_admin')) {
                    return back()->with('error', 'Login has been temporarily disabled.');
                }
            }

            UserSessionService::storeUserPreferences($user);

            $intendedUrl = session('url.intended');

            // Log the login details
            $user = Auth::user();
            $ipAddress =$request->ip();
            $userAgent = $request->header('User-Agent');

            // Save to login_logs table
            $user->loginLogs()->create([
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
            ]);

            if ($intendedUrl) {
                return redirect()->to($intendedUrl);
            } else {
                return redirect()->intended(route('newsfeed'))->with('success', 'Login successful.');
            }
        } else {
            return back()->with('error', 'Invalid login credentials.');
        }
    }


    public function render()
    {
        return view('livewire.auth.login-form');
    }
}
