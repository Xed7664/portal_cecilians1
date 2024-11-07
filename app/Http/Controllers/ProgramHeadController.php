<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\YearLevel;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\DepartmentSubject;
use App\Models\SubjectsProspectus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ProgramHeadController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $dashboardData = session('dashboard_data');

        return view('phead.dashboard', [
            'user' => $user,
            'studentsCount' => $dashboardData['studentsCount'],
            'sectionsCount' => $dashboardData['sectionsCount'],
            'subjectsCount' => $dashboardData['subjectsCount'],
            'prospectusCount' => $dashboardData['prospectusCount'],
        ]);
    }

    public function index()
    {
        $programId = session('program_id');
        
        if (!$programId) {
            $employee = Auth::user()->employee;
            $programId = $employee->department_id;
            session(['program_id' => $programId]);
        }

        $subjects = SubjectsProspectus::with(['subject', 'yearLevel', 'program'])
            ->where('program_id', $programId)
            ->where('archive_status', 0)
            ->get()
            ->groupBy(['year_level_id', 'semester_id']);

        $yearLevels = YearLevel::orderBy('id')->get();
        $semesters = Semester::orderBy('id')->take(2)->get();

        $departmentSubjects = DepartmentSubject::where('program_id', $programId)
            ->whereHas('subject', function ($query) {
                $query->where('archive_status', 0);
            })
            ->with('subject')
            ->get();

        return view('phead.prospectus', compact('subjects', 'yearLevels', 'semesters', 'departmentSubjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'year_level_id' => 'required|exists:year_levels,id',
            'semester_id' => 'required|exists:semesters,id',
        ]);

        $programId = session('program_id');

        $existingSubject = SubjectsProspectus::where('program_id', $programId)
            ->where('subject_id', $request->subject_id)
            ->where('year_level_id', $request->year_level_id)
            ->where('semester_id', $request->semester_id)
            ->first();

        if ($existingSubject) {
            return redirect()->route('phead.prospectus')->with('error', 'This subject already exists in the prospectus for the selected year level and semester.');
        }

        SubjectsProspectus::create([
            'program_id' => $programId,
            'subject_id' => $request->subject_id,
            'year_level_id' => $request->year_level_id,
            'semester_id' => $request->semester_id,
        ]);

        return redirect()->route('phead.prospectus')->with('success', 'Subject added to prospectus successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'subject_code' => 'required',
            'description' => 'required',
            'lec_units' => 'required|numeric',
            'lab_units' => 'required|numeric',
            'total_hours' => 'required|numeric',
            'pre_requisite' => 'nullable|string',
        ]);
    
        $prospectus = SubjectsProspectus::findOrFail($id);
        $subject = $prospectus->subject;
    
        $subject->update([
            'subject_code' => $request->subject_code,
            'description' => $request->description,
            'lec_units' => $request->lec_units,
            'lab_units' => $request->lab_units,
            'total_hours' => $request->total_hours,
            'pre_requisite' => $request->pre_requisite,
        ]);
    
        return redirect()->route('phead.prospectus')->with('success', 'Prospectus updated successfully.');
    }
    
    public function archive($id)
    {
        $prospectus = SubjectsProspectus::findOrFail($id);
        $prospectus->update(['archive_status' => 1]);

        return redirect()->route('phead.prospectus')->with('success', 'Subject archived from prospectus successfully.');
    }

    public function archivedIndex()
    {
        // Retrieve the authenticated program head's program_id from session
        $programId = session('program_id');
    
        // Fetch archived subjects prospectus specific to this program head's program
        $archivedProspectus = SubjectsProspectus::with('subject')
            ->where('program_id', $programId)
            ->where('archive_status', 1)
            ->get();
    
        return view('phead.archived-prospectus', compact('archivedProspectus'));
    }
    

    public function restore($id)
    {
        $prospectus = SubjectsProspectus::findOrFail($id);
        $prospectus->update(['archive_status' => 0]);

        return redirect()->route('phead.prospectus.archived')->with('success', 'Subject restored to prospectus successfully.');
    }


    public function schedules()
    {
        // Fetch schedules from the database if needed
        return view('phead.schedules');
    }
}