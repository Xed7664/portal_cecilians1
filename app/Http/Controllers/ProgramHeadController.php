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
        // Ensure we have the program_id from the session
        $programId = session('program_id');
        
        if (!$programId) {
            // If program_id is not in session, fetch it from the employee record
            $employee = Auth::user()->employee;
            $programId = $employee->department_id;
            
            // Store it in the session for future use
            session(['program_id' => $programId]);
        }

        $subjects = SubjectsProspectus::with(['subject', 'yearLevel', 'program'])
            ->where('program_id', $programId)
            ->where('archive_status', 0)
            ->get()
            ->groupBy(['year_level_id', 'semester_id']);

        $yearLevels = YearLevel::orderBy('id')->get();
        $semesters = Semester::orderBy('id')->take(2)->get();

        // Fetch department subjects for the logged-in program head
        $departmentSubjects = Subject::whereHas('departmentSubjects', function ($query) use ($programId) {
            $query->where('program_id', $programId);
        })->get();

        return view('phead.prospectus', compact('subjects', 'yearLevels', 'semesters', 'departmentSubjects'));
    }
    public function getSubjectDetails($id)
        {
            $subject = Subject::findOrFail($id);
            return response()->json($subject);
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
    
        // Updating only specific fields to avoid overwriting unintended fields
        $subject->update([
            'subject_code' => $request->subject_code,
            'description' => $request->description,
            'lec_units' => $request->lec_units,
            'lab_units' => $request->lab_units,
            'pre_requisite' => $request->pre_requisite,
        ]);
    
        return redirect()->route('phead.prospectus')->with('success', 'Prospectus updated successfully.');
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
