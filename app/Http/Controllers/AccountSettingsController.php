<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountSettingsController extends Controller
{
    public function show($page)
    {
        // Check the $page parameter and return the corresponding view
        if (in_array($page, ['account', 'security', 'notification', 'connection', 'qrcode'])) {
            return view('settings', ['page' => $page]);
        } else {
            // Handle other cases or return an error view
            return view('error', ['page' => $page]);
        }
    }
}
