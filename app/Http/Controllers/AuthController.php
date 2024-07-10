<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Employee, Student, SystemSetting, User};
use Illuminate\Support\Facades\{Auth, Hash, Session};
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    function login()
    {
        return view('auths.login');
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
        // if(Auth::check()){
        //     return view('auths.verify');
        // } else {
        //    // return view('auths.register');
        //    return 'view';
        // }

        return view('auths.verify');

        
    }

    function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect(route('login'));
    }
}