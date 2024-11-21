<?php

namespace App\Livewire\Auth;

use App\Mail\VerificationCodeMail; 
use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\Attributes\Rule;
use App\Mail\AccountLockedMail;
use App\Services\UserSessionService;
use Illuminate\Support\Facades\Hash;
use App\Models\{User, SystemSetting};
use Illuminate\Support\Facades\{Auth, Mail, DB};

class LoginForm extends Component
{
    #[Rule('required', message: 'Please enter your email or username.')]
    public $username;

    #[Rule('required', message: 'Please enter your password.')]
    public $password;

    #[Rule('boolean', message: 'Invalid input for the Remember Me checkbox.')]
    public $remember = false;

    
    public $email, $verificationCode, $newPassword, $confirmPassword, $otp_code;
    public $currentForm = 'login';
    public $generatedCode;
    public $error = null; // Track errors for reset
    protected $rules = [
        'username' => 'required',
        'password' => 'required',
    ];

    protected $messages = [
        'username.required' => 'The email or username is required.',
        'password.required' => 'The password is required.',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    public function showForgotPasswordForm()
    {
        $this->reset(['email', 'verificationCode', 'newPassword', 'confirmPassword']);
        $this->currentForm = 'forgot-password';
    }

    public function sendVerificationCode()
    {
        $this->validate(['email' => 'required|email']);
    
        $this->generatedCode = rand(100000, 999999);
    
        // Save the OTP code in the database
        DB::table('otp_codes')->insert([
            'email' => $this->email,
            'code' => $this->generatedCode,
            'expires_at' => now()->addMinutes(10), // OTP expires in 10 minutes
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
        // Send the code to the user's email
        Mail::to($this->email)->send(new VerificationCodeMail($this->generatedCode));
    
        $this->currentForm = 'verify-code';
        session()->flash('success', 'Verification code sent to your email.');
    
        // Log for debugging
        \Log::info('OTP sent and stored:', [
            'email' => $this->email,
            'code' => $this->generatedCode,
            'expires_at' => now()->addMinutes(10),
        ]);
    }
    
    public function verifyCode()
{
    $this->validate([
        'verificationCode' => 'required',
    ]);

    // Check if the provided code is valid
    $isValid = DB::table('otp_codes')
        ->where('code', $this->verificationCode)
        ->where('email', $this->email)
        ->where('expires_at', '>', now()) // Ensure the code isn't expired
        ->exists();

    if (!$isValid) {
        session()->flash('error', 'Invalid or expired verification code.');

        // Log for debugging
        \Log::error('OTP verification failed:', [
            'email' => $this->email,
            'verificationCode' => $this->verificationCode,
            'currentTime' => now(),
        ]);

        return;
    }

    // Transition to the reset password form
    $this->currentForm = 'reset-password';
    session()->flash('success', 'Verification code verified. You may reset your password.');

    // Log success for debugging
    \Log::info('OTP verification successful:', [
        'email' => $this->email,
        'verificationCode' => $this->verificationCode,
    ]);
}

    
public function resetPassword()
{
    $this->validate([
        'newPassword' => [
            'required',
            'min:8',
            'regex:/[A-Z]/',         // At least one uppercase letter
            'regex:/[a-z]/',         // At least one lowercase letter
            'regex:/[0-9]/',         // At least one digit
            'regex:/[@$!%*?&]/',     // At least one special character
        ],
        'confirmPassword' => 'required|same:newPassword',
        'verificationCode' => 'required', // Ensure it's present
    ], [
        'newPassword.required' => 'The password field is required.',
        'newPassword.min' => 'The password must be at least 8 characters.',
        'newPassword.regex' => 'The password must include at least one uppercase letter, one lowercase letter, one number, and one special character.',
        'confirmPassword.required' => 'The confirmation password field is required.',
        'confirmPassword.same' => 'The confirmation password must match the new password.',
        'verificationCode.required' => 'The verification code is required.',
    ]);

    // Validate OTP again before resetting password
    $isValidOtp = DB::table('otp_codes')
        ->where('code', $this->verificationCode)
        ->where('email', $this->email)
        ->where('expires_at', '>', now())
        ->exists();

    if (!$isValidOtp) {
        session()->flash('error', 'Invalid or expired verification code.');

        // Log for debugging
        \Log::error('Password reset failed due to invalid OTP:', [
            'email' => $this->email,
            'verificationCode' => $this->verificationCode,
            'currentTime' => now(),
        ]);

        return;
    }

    // Update the user's password
    $user = User::where('email', $this->email)->first();
    $user->password = bcrypt($this->newPassword);
    $user->save();

    session()->flash('success', 'Password has been successfully reset!');
    \Log::info('Password reset successful:', [
        'email' => $this->email,
    ]);

    // Call restoreAccount to unlock the account after successful password reset
    $this->restoreAccount($user->id);

    return redirect()->route('login');
}

/**
 * Restore the account status after password reset
 */
private function restoreAccount($userId)
{
    // Reset the lock status in login_logs for the user
    DB::table('login_logs')
        ->where('user_id', $userId)
        ->where('is_failed_attempt', 0) // Check for account lock flag
        ->update([
            'lock_until' => null, // Reset lock_until to null to unlock the account
            'updated_at' => now(),
        ]);

    \Log::info('Account restored after password reset:', [
        'userId' => $userId,
    ]);
}
public function resetError()
{
    $this->error = null; // Reset error state
}


public function showLoginForm()
{
    $this->reset(['email', 'verificationCode', 'newPassword', 'confirmPassword']);
    $this->currentForm = 'login';
}


    
    public function login(Request $request)
    {
        $this->resetError(); // Reset errors on login attempt
        $this->validate();

        $loginField = filter_var($this->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [$loginField => $this->username, 'password' => $this->password];
        $deviceId = md5($request->header('User-Agent')); // Generate a unique device ID
        $ipAddress = $request->ip();

        $user = User::where('email', $this->username)
            ->orWhere('username', $this->username)
            ->first();

            
            if (!$user) {
                return back()->with('error', 'Invalid login credentials.');
            }
            // Check if the account is locked
        if ($this->isAccountLocked($user->id, $deviceId)) {
            return back()->with('error', 'Too many login attempts. Your account is locked. Check your email to reset your credentials.');
        }

       
       
        // Check if the device is under temporary lock
        if ($this->isDeviceTemporarilyLocked($user->id, $deviceId)) {
            return back()->with('error', 'Too many login attempts. Wait for 1 minute to try again.');
        }

        if (Auth::attempt($credentials, $this->remember)) {
            // Reset failed attempts and log successful login
            $this->resetFailedAttempts($user->id, $deviceId);

            if ($user->status === 'banned') {
                Auth::logout();
                return back()->with('error', 'Your account has been banned.');
            }

            if (!SystemSetting::isLoginEnabled() && !$user->hasPermission('access_admin')) {
                Auth::logout();
                return back()->with('error', 'Login has been temporarily disabled.');
            }
    
            UserSessionService::storeUserPreferences($user);
    
            $user->loginLogs()->create([
                'ip_address' => $ipAddress,
                'user_agent' => $request->header('User-Agent'),
                'device_id' => $deviceId,
            ]);

            // Check if the user is a student
            if ($user->type === 'student') {
                $student = $user->student;
    
                // Check if the student has enrolled subjects
                $hasSubjectsEnrolled = DB::table('subjects_enrolled')
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
            }
            // Check if the user is a teacher
            elseif ($user->type === 'teacher') {
                return redirect()->intended(route('teacher.dashboard'))->with('success', 'Login successful.');
            }
    
            return redirect()->intended(route('newsfeed'))->with('success', 'Login successful.');
        } else {
            // Log failed attempt and check for account/device lock
            $this->logFailedAttempt($user->id, $deviceId, $ipAddress);

            return back()->with('error', 'Invalid login credentials.');
        }
    }
    
    private function isAccountLocked($userId, $deviceId)
    {
        return DB::table('login_logs')
            ->where('user_id', $userId)
            ->where('device_id', $deviceId)
            ->where('lock_until', '>', now())
            ->where('is_failed_attempt', 0) // Full account lock flag
            ->exists();
    }

    private function isDeviceTemporarilyLocked($userId, $deviceId)
    {
        return DB::table('login_logs')
            ->where('user_id', $userId)
            ->where('device_id', $deviceId)
            ->where('lock_until', '>', now())
            ->where('is_failed_attempt', 1) // Temporary lock flag
            ->exists();
    }

    private function logFailedAttempt($userId, $deviceId, $ipAddress)
    {
        // Log the failed attempt
        DB::table('login_logs')->insert([
            'user_id' => $userId,
            'device_id' => $deviceId,
            'user_agent' => request()->header('User-Agent'),
            'ip_address' => $ipAddress,
            'is_failed_attempt' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Count total failed attempts
        $totalFailedAttempts = DB::table('login_logs')
            ->where('user_id', $userId)
            ->where('device_id', $deviceId)
            ->where('is_failed_attempt', 1)
            ->count();

        if ($totalFailedAttempts === 3) {
            // If it's the 3rd failed attempt, temporarily lock the device for 1 minute
            DB::table('login_logs')
                ->where('user_id', $userId)
                ->where('device_id', $deviceId)
                ->update(['lock_until' => now()->addMinutes(1)]);
        }

        if ($totalFailedAttempts === 4) {
            // Lock the account after the 4th failed attempt
            $this->lockAccount($userId, $deviceId, $ipAddress);
        }
    }

    private function resetFailedAttempts($userId, $deviceId)
    {
        // Only reset failed attempts, not the lock
        DB::table('login_logs')
            ->where('user_id', $userId)
            ->where('device_id', $deviceId)
            ->where('is_failed_attempt', 1)
            ->delete();
    }

    private function lockAccount($userId, $deviceId, $ipAddress)
    {
        DB::table('login_logs')->insert([
            'user_id' => $userId,
            'device_id' => $deviceId,
            'ip_address' => $ipAddress,
            'user_agent' => request()->header('User-Agent'),
            'is_failed_attempt' => 0, // Full account lock flag
            'lock_until' => now()->addHours(1), // Lock account for 1 hour
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user = User::find($userId);

        if ($user && $user->email) {
            Mail::to($user->email)->send(new AccountLockedMail());
        }
    }
    
    public function render()
    {
        return view('livewire.auth.login-form');
    }
}