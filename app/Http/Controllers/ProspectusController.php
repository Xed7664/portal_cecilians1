<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProspectusController extends Controller
{
    public function index()
    {
        // Add logic to fetch grades here
        $prospectus = []; // Replace this with actual logic to fetch grades

        return view('prospectus.index', compact('prospectus'));
    }
}
