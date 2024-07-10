<?php
// app/Http/Controllers/GradeController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        // Add logic to fetch grades here
        $grades = []; // Replace this with actual logic to fetch grades

        return view('grades.index', compact('grades'));
    }
}
?>
