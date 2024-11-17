<?php

namespace App\Http\Controllers\Admin;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmployeeImport;

class EmployeeController extends Controller
{
    public function index()
    {
        $data = Employee::all();
        return view('admin.users.employee', ['userType' => 'employee', 'data' => $data]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv,xls|max:2048',
        ]);
    
        try {
            Excel::import(new EmployeeImport, $request->file('file'));
    
            return response()->json([
                'success' => true,
                'message' => 'Employees imported successfully!',
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already imported the employee details. Try another one.',
                ]);
            }
    
            return response()->json([
                'success' => false,
                'message' => 'Error importing file: ' . $e->getMessage(),
            ]);
        }
    }
    
    
}
