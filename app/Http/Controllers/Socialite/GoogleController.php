<?php

namespace App\Http\Controllers\Socialite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Employee, Student, SystemSetting, User};
use Illuminate\Support\Facades\{Auth, Hash, Session, DB};
use Laravel\Socialite\Facades\Socialite;
use App\Services\UserSessionService;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        // Fetch the user data from Google
        $googleUser = Socialite::driver('google')->user();
    
        // Check if the 'link' parameter is present in the request
        $isLinking = $request->has('link');
    
        // If the user is already authenticated (logged in)
        if (Auth::check()) {
            $authUser = Auth::user();
            
            // Check if the user already has a Google ID linked
            if (!$authUser->google_id) {
                try {
                    // Update the user's google_id field with the Google ID
                    $authUser->google_id = $googleUser->id;
                    $authUser->save(); // Use save() instead of update()
                    
                    return redirect()->intended(route('account.show', ['page' => 'connection']))
                                     ->with('success', 'Google account linked successfully.');
                } catch (\Exception $e) {
                    return redirect()->intended(route('account.show', ['page' => 'connection']))
                                     ->with('error', 'Failed to link Google account.');
                }
            } else {
                return redirect()->intended(route('account.show', ['page' => 'connection']))
                                 ->with('danger', 'Your account is already linked to a Google account.');
            }
        } else {
            // Find a user with the Google ID
            $authUser = User::where('google_id', $googleUser->id)->first();
    
            if ($authUser) {
                // Log the user in
                Auth::login($authUser);
                UserSessionService::storeUserPreferences($authUser);
                
                // Check if there's an intended URL to redirect the user to
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
