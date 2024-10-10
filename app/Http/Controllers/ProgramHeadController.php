<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\YearLevel;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\SubjectsProspectus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ProgramHeadController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employee = session('employee');

        if (!$employee || !$employee->department_id) {
            Log::error("Program head user {$user->id} has no associated employee record or department.");
            return redirect()->back()->with('error', 'Unable to determine your department. Please contact the administrator.');
        }

        $departmentId = $employee->department_id;

        // Fetch subjects that are not in the subjects_prospectus table
        $subjects = Subject::whereDoesntHave('prospectus', function($query) {
            $query->where('archive_status', 0);
        })->where('department_id', $departmentId)->get();

        $prospectus = SubjectsProspectus::with(['subject' => function($query) use ($departmentId) {
            $query->where('department_id', $departmentId);
        }])->where('archive_status', 0)->get();

        // Log any prospectus items without a subject for debugging
        $prospectus->each(function ($item) {
            if (!$item->subject) {
                Log::warning("Prospectus item {$item->id} has no associated subject.");
            }
        });

        $department = Department::find($departmentId);
        $yearLevels = YearLevel::all();

        return view('phead.prospectus', compact('subjects', 'prospectus', 'department', 'yearLevels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
        ]);

        SubjectsProspectus::create($request->all());

        return redirect()->route('phead.prospectus.index')->with('success', 'Subject added to prospectus successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'subject_code' => 'required',
            'description' => 'required',
            'lec_units' => 'required|numeric',
            'lab_units' => 'required|numeric',
        ]);

        $prospectus = SubjectsProspectus::findOrFail($id);
        $subject = $prospectus->subject;

        $subject->update($request->all());

        return redirect()->route('phead.prospectus.index')->with('success', 'Prospectus updated successfully.');
    }

    public function archive($id)
    {
        $prospectus = SubjectsProspectus::findOrFail($id);
        $prospectus->update(['archive_status' => 1]);

        return redirect()->route('phead.prospectus.index')->with('success', 'Subject archived from prospectus successfully.');
    }

    public function archivedIndex()
    {
        $archivedProspectus = SubjectsProspectus::with('subject')->where('archive_status', 1)->get();
        return view('phead.archived-prospectus', compact('archivedProspectus'));
    }

    public function restore($id)
    {
        $prospectus = SubjectsProspectus::findOrFail($id);
        $prospectus->update(['archive_status' => 0]);

        return redirect()->route('phead.prospectus.archived')->with('success', 'Subject restored to prospectus successfully.');
    }
}