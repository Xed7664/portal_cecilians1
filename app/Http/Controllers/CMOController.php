<?php

namespace App\Http\Controllers;

use App\Models\CMO;
use Illuminate\Http\Request;

class CMOController extends Controller
{
    // Display all CMOs
    public function index()
    {
        $cmos = CMO::all();
        return view('phead.cmos.index', compact('cmos'));
    }

    // Show the form to create a new CMO
    public function create()
    {
        return view('phead.cmos.create');
    }

    // Store a new CMO
    public function store(Request $request)
    {
        $request->validate([
            'cmo_number' => 'required|unique:cmos,cmo_number',
            'description' => 'nullable',
            'year_issued' => 'required|digits:4',
        ]);

        CMO::create($request->all());

        return redirect()->route('phead.cmos.index')->with('success', 'CMO created successfully.');
    }

    // Show the form to edit an existing CMO
    public function edit(CMO $cmo)
    {
        return view('phead.cmos.edit', compact('cmo'));
    }

    // Update an existing CMO
    public function update(Request $request, CMO $cmo)
    {
        $request->validate([
            'cmo_number' => 'required|unique:cmos,cmo_number,' . $cmo->id,
            'description' => 'nullable',
            'year_issued' => 'required|digits:4',
        ]);

        $cmo->update($request->all());

        return redirect()->route('phead.cmos.index')->with('success', 'CMO updated successfully.');
    }

    // Delete a CMO
    public function destroy(CMO $cmo)
    {
        $cmo->delete();
        return redirect()->route('phead.cmos.index')->with('success', 'CMO deleted successfully.');
    }
}
