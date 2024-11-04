<?php

namespace App\Http\Controllers;

use App\Models\{Subject, Employee, YearLevel, DepartmentSubject};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectsPheadController extends Controller
{
    public function index()
    {
        $userProgramId = Auth::user()->employee->department_id;
        
        // Fetch subjects based on department_subjects and program head's department_id
        $subjects = Subject::select('subjects.*', 'department_subjects.is_major')
            ->join('department_subjects', 'subjects.id', '=', 'department_subjects.subject_id')
            ->where('department_subjects.program_id', $userProgramId)
            ->where('subjects.archive_status', 0)
            ->paginate(20);

        $yearLevels = YearLevel::all();
        $teachers = Employee::where('department_id', $userProgramId)->get();

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

        // Insert the subject
        $subject = Subject::create($validatedData);

        // Insert into department_subjects using program head's department_id as program_id
        $userProgramId = Auth::user()->employee->department_id;
        DepartmentSubject::create([
            'program_id' => $userProgramId,
            'subject_id' => $subject->id,
            'is_major' => $validatedData['is_major'],
        ]);

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
            'is_major' => 'required|boolean'
        ]);
    
        // Update the subject details
        $subject = Subject::findOrFail($id);
        $subject->update($validatedData);
    
        // Update department_subjects table for is_major
        $userProgramId = Auth::user()->employee->department_id;
        $departmentSubject = DepartmentSubject::where('subject_id', $subject->id)
            ->where('program_id', $userProgramId)
            ->first();
        
        if ($departmentSubject) {
            $departmentSubject->update(['is_major' => $validatedData['is_major']]);
        }
    
        return redirect()->route('phead.subjects.index')->with('success', 'Subject updated successfully.');
    }
    

    public function archive(Subject $subject)
    {
        $subject->update(['archive_status' => 1]);
        return redirect()->route('phead.subjects.index')->with('success', 'Subject archived successfully.');
    }

    public function archivedSubjects()
{
    $userProgramId = Auth::user()->employee->department_id;

    $archivedSubjects = Subject::select('subjects.*', 'department_subjects.is_major')
        ->join('department_subjects', 'subjects.id', '=', 'department_subjects.subject_id')
        ->where('department_subjects.program_id', $userProgramId)
        ->where('subjects.archive_status', 1)
        ->paginate(20);

    return view('phead.archived-subjects', compact('archivedSubjects'));
}

public function restore(Subject $subject)
{
    $subject->update(['archive_status' => 0]);
    return redirect()->route('phead.archived-subjects')->with('success', 'Subject restored successfully.');
}

public function delete(Subject $subject)
{
    // Delete the associated department_subject record
    DepartmentSubject::where('subject_id', $subject->id)->delete();

    // Delete the subject
    $subject->delete();

    return redirect()->route('phead.archived-subjects')->with('success', 'Subject deleted successfully.');
}
}