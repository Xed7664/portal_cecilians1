<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PreEnrollmentSetting;
use App\Models\Semester;
use App\Models\SchoolYear;
use App\Models\Student;
use Carbon\Carbon; // Import Carbon for date handling
use Illuminate\Support\Facades\Session;

class ProgramHeadPreEnrollmentController extends Controller
{

    
    public function showSettings(Request $request)
    {
        $semesters = Semester::select('id', 'name')->distinct()->get();
        $schoolYears = SchoolYear::all();
        $settings = PreEnrollmentSetting::with('semester', 'schoolYear')->get();
    
        // Get the selected semester ID from the request or session
        $selectedSemesterId = $request->get('semester_id', session('selectedSemesterId', session('current_semester_id')));
    
        // Pass $selectedSemesterId to the view
        return view('pre-enrollment.phead.pre-enrollment-settings', compact('settings', 'semesters', 'schoolYears', 'selectedSemesterId'));
    }

    // Update pre-enrollment settings (open/close pre-enrollment period)
    public function updateSettings(Request $request)
    {
        $request->validate([
            'semester_id' => 'required|exists:semesters,id',
            'school_year_id' => 'required|exists:school_years,id',
            'is_open' => 'required|boolean',
            'open_date' => 'required|date',
            'close_date' => 'required|date|after_or_equal:open_date',
        ]);

        // Convert the dates to Carbon instances for consistency
        $openDate = Carbon::parse($request->open_date);
        $closeDate = Carbon::parse($request->close_date);

       // Create or update pre-enrollment settings
       $setting = PreEnrollmentSetting::updateOrCreate(
        [
            'semester_id' => $request->semester_id,
            'school_year_id' => $request->school_year_id,
        ],
        [
            'is_open' => $request->is_open,
            'open_date' => $openDate,
            'close_date' => $closeDate,
        ]
    );

        return redirect()->back()->with('success', 'Pre-enrollment settings updated successfully.');
    }

    // List all pre-enrollment applications for review
    public function listApplications()
    {
        $applications = Student::where('pre_enrollment_completed', true)
                               ->with(['program', 'yearLevel'])
                               ->get();

        return view('phead.pre-enrollment.applications', compact('applications'));
    }

    // Review a specific pre-enrollment application
    public function reviewApplication($studentId)
    {
        $student = Student::findOrFail($studentId);
        $subjects = $student->subjects()->wherePivot('pre_enrollment', true)->get();

        return view('phead.pre-enrollment.review', compact('student', 'subjects'));
    }
}
