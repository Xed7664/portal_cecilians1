<?php

namespace App\Http\Controllers;

use App\Models\{Subject, Employee, YearLevel};
use Illuminate\Http\Request;

class SubjectsPheadController extends Controller
{
    public function index()
    {
        // Fetch all subjects directly from the subjects table
        $subjects = Subject::where('archive_status', 0)->get();


        $yearLevels = YearLevel::all();
        $teachers = Employee::all();

        return view('phead.subjects.index', compact('subjects', 'yearLevels', 'teachers'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'subject_code' => 'required|unique:subjects',
            'description' => 'required',
            'lec_units' => 'required|numeric',
            'lab_units' => 'required|numeric',
            'total_units' => 'required|numeric',
            'total_hours' => 'required|numeric',
            'pre_requisite' => 'nullable',
            'is_major' => 'required|boolean',
        ]);

        // Insert the subject into the subjects table
        Subject::create($validatedData);

        return redirect()->route('phead.subjects.index')->with('success', 'Subject added successfully.');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'subject_code' => 'required|unique:subjects,subject_code,' . $id,
            'description' => 'required',
            'lec_units' => 'required|numeric',
            'lab_units' => 'required|numeric',
            'total_units' => 'required|numeric',
            'total_hours' => 'required|numeric',
            'pre_requisite' => 'nullable',
            'is_major' => 'required|boolean',
        ]);

        // Update the subject details in the subjects table
        $subject = Subject::findOrFail($id);
        $subject->update($validatedData);

        return redirect()->route('phead.subjects.index')->with('success', 'Subject updated successfully.');
    }

    public function archive(Subject $subject)
    {
        // Update the archive status in the subjects table
        $subject->update(['archive_status' => 1]);

        return redirect()->route('phead.subjects.index')->with('success', 'Subject archived successfully.');
    }

    public function archivedSubjects()
    {
        // Fetch all archived subjects directly from the subjects table
        $archivedSubjects = Subject::where('archive_status', 1)->paginate(20);

        return view('phead.archived-subjects', compact('archivedSubjects'));
    }

    public function restore(Subject $subject)
    {
        // Restore the subject by updating its archive status in the subjects table
        $subject->update(['archive_status' => 0]);

        return redirect()->route('phead.archived-subjects')->with('success', 'Subject restored successfully.');
    }

    public function delete(Subject $subject)
    {
        // Delete the subject directly from the subjects table
        $subject->delete();

        return redirect()->route('phead.archived-subjects')->with('success', 'Subject deleted successfully.');
    }
}
