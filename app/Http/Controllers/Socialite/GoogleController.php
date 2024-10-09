<?php

namespace App\Http\Controllers\Socialite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Employee, Student, SystemSetting, User};
use Illuminate\Support\Facades\{Auth, Hash, Session};
use Laravel\Socialite\Facades\Socialite;
use App\Services\UserSessionService;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        $user = Socialite::driver('google')->user();

        // Check if the 'link' parameter is present in the request
        $isLinking = $request->has('link');

        if (Auth::check()) {
            // Account linking logic
            $authUser = Auth::user();

            // Check if the user already has a Google ID linked
            if (!$authUser->google_id) {
                // Get the Google user data after they grant access
                $googleUser = Socialite::driver('google')->user();
                
                // Update the user's google_id field with the Google ID
                $authUser->update(['google_id' => $googleUser->id]);
                
                return redirect()->intended(route('account.show', ['page' => 'connection']))->with('success', 'Google account linked successfully.');
            } else {
                return redirect()->intended(route('account.show', ['page' => 'connection']))->with('danger', 'Your account is already linked to a Google account.');
            }
        } else {
            // Check if the user with the Google ID already exists in the database
            $authUser = User::where('google_id', $user->id)->first();

            if ($authUser) {
                Auth::login($authUser);

                UserSessionService::storeUserPreferences($authUser);

                $intendedUrl = session('url.intended');

                if ($intendedUrl) {
                    return redirect()->to($intendedUrl);
                } else {
                    return redirect()->intended(route('newsfeed'))->with('success', 'Login successful.');
                }
            } else {
                return redirect()->route('login')->with('error', 'No user with this Google account exists.');
            }
        }
    }


}
