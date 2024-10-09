<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index()
    {
        $data = Employee::all();
        return view('admin.users.employee', ['userType' => 'employee', 'data' => $data]);
    }
}
