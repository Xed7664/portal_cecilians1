<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;
use App\Services\UserSessionService;

class GoogleController extends Controller
{
    /**
     * Redirect the user to Google's OAuth page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Handle Google OAuth callback.
     */
    public function handleGoogleCallback()
    {
        try {
            // Retrieve user info from Google
            $googleUser = Socialite::driver('google')->stateless()->user();

            \Log::info('Google User Retrieved:', (array)$googleUser);

            // Check if the user exists by Google ID or email
            $user = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if (!$user) {
                // If no user is found, redirect with a message to register first
                return redirect()->route('register')->with('error', 'Your Google account is not registered in our system. Please sign up first.');
            }

            // If a user is found but lacks a Google ID, update their record
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->id]);
            }

            // Log in the user
            Auth::login($user);

            // Update session preferences
            UserSessionService::storeUserPreferences($user);

            // Redirect based on user type
            return $this->handleUserRedirect($user);

        } catch (\Exception $e) {
            \Log::error('Google Login Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Your Google account is not registered in our system. Please sign up first.');
        }
    }


    /**
     * Redirect the user based on their type.
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function handleUserRedirect(User $user)
    {
        if ($user->status === 'banned') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account has been banned.');
        }

        // Check if the user is a student
        if ($user->type === 'student') {
            $student = $user->student;

            // Check if the student has enrolled subjects
            $hasSubjectsEnrolled = DB::table('subjects_enrolled')
                ->where('student_id', $student->id)
                ->exists();

            // Redirect to pre-enrollment form if the student has not completed pre-enrollment
            if (!$student->pre_enrollment_completed) {
                return redirect()->route('pre-enrollment.form')->with('success', 'Please complete pre-enrollment.');
            }

            // Redirect to newsfeed if the student has already enrolled in subjects
            if ($student->pre_enrollment_completed && $hasSubjectsEnrolled) {
                return redirect()->route('newsfeed')->with('success', 'Login successful.');
            }
        }

        // Check if the user is a teacher
        if ($user->type === 'teacher') {
            return redirect()->route('teacher.dashboard')->with('success', 'Login successful.');
        }
        // Check if the user is a phead
        if ($user->type === 'program_head') {
            return redirect()->route('phead.dashboard')->with('success', 'Login successful.');
        }
        // Check if the user is a admin
        if ($user->type === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Login successful.');
        }

        // Default redirect for other user types
        return redirect()->route('newsfeed')->with('success', 'Successfully logged in with Google!');
    }
}
