<?php
namespace App\Http\Controllers;
use App\Models\Subject;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\SubjectsProspectus;

class SubjectsProspectusController extends Controller
{
    public function index()
    {
        // Show all subjects based on department and status
        $prospectus = SubjectsProspectus::where('status', 'active')->get();
        return view('prospectus.index', compact('prospectus'));
    }

    public function create()
    {
        $subjects = Subject::all();
        $departments = Department::all();
        return view('prospectus.create', compact('subjects', 'departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject_id' => 'required',
            'department_id' => 'required',
            'program_head_id' => 'required',
        ]);

        SubjectsProspectus::create($data);

        return redirect()->route('prospectus.index')->with('success', 'Subject added to prospectus!');
    }

    public function edit(SubjectsProspectus $prospectus)
    {
        $subjects = Subject::all();
        $departments = Department::all();
        return view('prospectus.edit', compact('prospectus', 'subjects', 'departments'));
    }

    public function update(Request $request, SubjectsProspectus $prospectus)
    {
        $data = $request->validate([
            'subject_id' => 'required',
            'department_id' => 'required',
            'program_head_id' => 'required',
        ]);

        $prospectus->update($data);

        return redirect()->route('prospectus.index')->with('success', 'Prospectus updated!');
    }

    public function archive(SubjectsProspectus $prospectus)
    {
        $prospectus->update(['status' => 'archived']);
        return redirect()->route('prospectus.index')->with('success', 'Subject archived!');
    }
}

