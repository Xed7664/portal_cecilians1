<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Logic specific to the student's dashboard
        return view('Admin.dashboard');
    }
}
