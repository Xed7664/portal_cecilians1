<?php

namespace App\Http\Controllers;
use App\Events\AdmissionApproved;
use App\Models\Admission;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Mail\AdmissionApprovedEmail;
use App\Mail\AdmissionSubmittedEmail;
use Illuminate\Support\Facades\Mail;

class AdmissionController extends Controller
{
    public function showAdmissionForm()
    {
        return view('admission.form'); 
    }
    public function submitAdmission(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admissions',
            'birthday' => 'required|date',
            'gender' => 'required|string|max:10',
            'address' => 'required|string|max:255',
            'student_type' => 'required|string',
            'picture' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'formcard' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'certifications' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
    
        $full_name = trim($request->first_name . ' ' . $request->middle_name . ' ' . $request->last_name);
    
        $admission = new Admission();
        $admission->full_name = $full_name;
        $admission->email = $request->email;
        $admission->birthday = $request->birthday;
        $admission->gender = $request->gender;
        $admission->address = $request->address;
        $admission->student_type = $request->student_type;
    
        // Handle picture file upload
        if ($request->hasFile('picture')) {
            $picturePath = $request->file('picture')->store('pictures');
            if ($picturePath === null) {
                return back()->with('error', 'File upload failed for picture.');
            }
            $admission->picture = $picturePath;
        }
    
        // Handle formcard file upload
        if ($request->hasFile('formcard')) {
            $formcardPath = $request->file('formcard')->store('formcards');
            if ($formcardPath === null) {
                return back()->with('error', 'File upload failed for formcard.');
            }
            $admission->formcard = $formcardPath;
        }
    
        // Handle certifications file upload
        if ($request->hasFile('certifications')) {
            $certificationsPath = $request->file('certifications')->store('certifications');
            if ($certificationsPath === null) {
                return back()->with('error', 'File upload failed for certifications.');
            }
            $admission->certifications = $certificationsPath;
        }
    
          // Generate a tracker code (a unique random string)
  $admission->tracker_code = uniqid('SCC-');  

    // Set initial status to 'pending'
    $admission->status = 'pending';

    // Save the admission record
    $admission->save();

    // Send an email with the tracking code
    Mail::to($admission->email)->send(new AdmissionSubmittedEmail($admission));

    // Send a response that includes the tracker code
    return redirect()->route('admission.form')->with('success', 'Your admission application has been submitted. Your tracker code is ' . $admission->tracker_code);
}
public function showStatusForm()
{
    return view('admission.status');
}

public function checkStatus(Request $request)
{
    $request->validate([
        'tracker_code' => 'required|string',
    ]);

    $admission = Admission::where('tracker_code', $request->tracker_code)->first();

    if (!$admission) {
        return back()->with('error', 'Invalid tracker code.');
    }

    return view('admission.status-result', ['admission' => $admission]);
}
public function showTracker()
{
    return view('admission.tracker'); // Make sure this view exists
}

public function trackAdmission(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'tracker_code' => 'required|string',
    ]);

    $admission = Admission::where('email', $request->email)
        ->where('tracker_code', $request->tracker_code)
        ->first();

    if ($admission) {
        return view('admission.status-result', compact('admission'));

    }

    return back()->with('error', 'Invalid email or tracker code.');
}

    public function index()
    {
        $admissions = Admission::where('status', 'pending')->get();
        return view('admin.admission.index', compact('admissions'));
    }
    

    // Admin: Review the admission
    public function reviewAdmission($id)
    {
        $admission = Admission::findOrFail($id);
        return view('admin.admission.review', compact('admission'));
    }

    public function approveAdmission(Request $request, $id)
    {
        $admission = Admission::findOrFail($id);
    
        // Generate Student ID
        $studentID = 'SCC-' . date('y') . '-' . str_pad($admission->id, 7, '0', STR_PAD_LEFT);
    
        // Create a new student record
        $student = Student::create([
            'StudentID' => $studentID,
            'FullName' => $admission->full_name,
            'Birthday' => $admission->birthday,
            'Gender' => $admission->gender,
            'Address' => $admission->address,
            'admission_status' => 'approved',
            'admission_date' => now(),
        ]);
    
        // Update admission status
        $admission->update(['status' => 'approved']);
    
// Send email to the applicant
Mail::to($admission->email)->send(new AdmissionApprovedEmail($admission));
    
        return redirect()->route('admin.admission.review', $id)->with('success', 'Admission approved and email sent, Student ID: ' . $studentID);
    }
    
    public function rejectAdmission(Request $request, $id)
{
    $admission = Admission::findOrFail($id);

    // Update admission status to rejected
    $admission->update(['status' => 'rejected']);

    // Optionally, notify the student via email about the rejection
    // Mail::to($admission->email)->send(new AdmissionRejected($admission));

    return redirect()->route('admin.admission.review', $id)->with('error', 'Admission rejected.');
}

}
