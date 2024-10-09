<?php

namespace App\Http\Controllers\Subjects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Subject, SubjectEnrolled};
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    public function showDetails($subject_id)
    {

        // Get the authenticated user
        $user = Auth::user();

        // Check if the user is enrolled in the specified subject
        $enrollment = SubjectEnrolled::where('student_id', $user->student->id)
            ->where('subject_id', $subject_id)
            ->first();
    
        // If the user is not enrolled, return a 403 Forbidden response
        if (!$enrollment) {
            return response()->json(['error' => 'Unauthorized. User is not enrolled in the subject.'], 403);
        }

        // Assuming you want to fetch a subject based on the provided subject_id
        $subject = Subject::find($subject_id);

        // Check if the subject exists
        if (!$subject) {
            // Handle the case where the subject is not found, for example, redirect or show an error message.
            // You can customize this based on your application's requirements.
            //return redirect()->route('some.route.to.handle.not.found.subject');
        }

        // Get the count of enrollees for the subject
        $enrolleesCount = SubjectEnrolled::where('subject_id', $subject->id)->count();

        // Pass the subject data to the view
        return view('subjects.details', ['subject' => $subject, 'enrolleesCount' => $enrolleesCount, 'page' => 'detail']);
    }

    public function showPeople($subject_id)
    {

        // Get the authenticated user
        $user = Auth::user();

        // Check if the user is enrolled in the specified subject
        $enrollment = SubjectEnrolled::where('student_id', $user->student->id)
            ->where('subject_id', $subject_id)
            ->first();
    
        // If the user is not enrolled, return a 403 Forbidden response
        if (!$enrollment) {
            return response()->json(['error' => 'Unauthorized. User is not enrolled in the subject.'], 403);
        }

        // Assuming you want to fetch a subject based on the provided subject_id
        $subject = Subject::find($subject_id);

        // Check if the subject exists
        if (!$subject) {
            // Handle the case where the subject is not found, for example, redirect or show an error message.
            // You can customize this based on your application's requirements.
            //return redirect()->route('some.route.to.handle.not.found.subject');
        }

        // Get the count of enrollees for the subject
        $enrolleesCount = SubjectEnrolled::where('subject_id', $subject->id)->count();

        // Get all students enrolled in the subject
        $students = SubjectEnrolled::where('subject_id', $subject->id)
            ->with('student') // Assuming you have a relationship defined between SubjectEnrolled and Student models
            ->get()
            ->pluck('student');

        // Pass the subject data to the view
        return view('subjects.people', ['subject' => $subject, 'enrolleesCount' => $enrolleesCount, 'students' => $students, 'page' => 'people']);
    }
}
