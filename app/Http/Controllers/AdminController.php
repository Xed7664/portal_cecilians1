<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Subject;
use App\Models\Employee;
use App\Models\Admission;
use Illuminate\Http\Request;
use App\Imports\SubjectImport;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    

    public function import(Request $request)
    {
        try {
            // Validate the uploaded file
            $request->validate([
                'file' => 'required|file|mimes:xlsx,csv',
            ]);
    
            // Initialize the import class
            $import = new SubjectImport;
    
            // Import the file
            Excel::import($import, $request->file('file'));
    
            // Check if there are any duplicates
            if (!empty($import->duplicates)) {
                $duplicateCodes = implode(', ', $import->duplicates);
                return response()->json([
                    'success' => false,
                    'message' => "Import completed with duplicate subject codes: $duplicateCodes.",
                ], 422);
            }
    
            return response()->json(['success' => true, 'message' => 'Subjects imported successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error importing subjects: ' . $e->getMessage()], 500);
        }
    }



        public function index()
    {
        $dashboardData = session('dashboard_data', []);
        return view('admin.dashboard', $dashboardData);
    }

    public function recentActivity()
{
    $recentActivities = $this->getRecentActivities();
    return view('admin.partials.recent-activity', compact('recentActivities'));
}

private function getRecentActivities()
{
    $activities = collect();

    // Check for recent students
    $recentStudent = Student::latest()->first();
    if ($recentStudent) {
        $activities->push([
            'type' => 'student',
            'message' => "New student {$recentStudent->FullName} registered",
            'time' => $recentStudent->created_at->diffForHumans()
        ]);
    }

    // Check for recent subjects
    $recentSubject = Subject::latest()->first();
    if ($recentSubject) {
        $activities->push([
            'type' => 'subject',
            'message' => "New subject {$recentSubject->description} added",
            'time' => $recentSubject->created_at->diffForHumans()
        ]);
    }

    // Check for recent employees
    $recentEmployee = Employee::latest()->first();
    if ($recentEmployee) {
        $activities->push([
            'type' => 'employee',
            'message' => "New employee {$recentEmployee->FullName} added",
            'time' => $recentEmployee->created_at->diffForHumans()
        ]);
    }

    // Check for recent admissions
    $recentAdmission = Admission::latest()->first();
    if ($recentAdmission) {
        $activities->push([
            'type' => 'admission',
            'message' => "New admission for {$recentAdmission->full_name}",
            'time' => $recentAdmission->created_at->diffForHumans()
        ]);
    }

    return $activities->sortByDesc('time')->take(5);
}
}
