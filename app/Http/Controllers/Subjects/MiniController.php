<?php

namespace App\Http\Controllers\Subjects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;

class MiniController extends Controller
{
    public function show($subject_id)
    {
        $subject = Subject::find($subject_id);

        if (!$subject) {
            abort(404); // Or handle the case when the subject is not found
        }

        $userTheme = session('theme');

        return view('subjects.mini', [
            'courseData' => $subject, 
            'theme' => $userTheme,
        ]);
    }
}
