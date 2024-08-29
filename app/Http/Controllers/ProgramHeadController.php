<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProgramHeadController extends Controller
{
    public function index()
    {
        // Logic for the program head dashboard
        return view('program_head.dashboard');    }
}

