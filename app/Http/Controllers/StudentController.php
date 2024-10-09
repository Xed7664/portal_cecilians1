<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        // Logic specific to the student's dashboard
        return view('student.dashboard');
    }
}
