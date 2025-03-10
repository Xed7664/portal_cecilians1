<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SubjectEnrolled;
use App\Models\SchoolYear;
use App\Models\Semester;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        // Eager load the related student, program, and year level data
        $data = User::with('student.program', 'student.yearLevel')->get();
    
        return view('admin.users.registered', [
            'userType' => 'registered',
            'data' => $data
        ]);
    }
    


}
