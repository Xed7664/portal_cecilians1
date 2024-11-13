<?php

namespace App\Http\Controllers\Subjects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Subject, Schedule, Employee};

class MiniController extends Controller
{
    public function show($subject_id)
    {
        // Find the subject by its ID and load its schedules with the teacher relation
        $subject = Subject::with(['schedules.teacher'])->find($subject_id);

        if (!$subject) {
            abort(404); // Handle the case when the subject is not found
        }

        // Find the first related schedule for this subject, if any
        $schedule = $subject->schedules->first();

        // Prepare the data array with fallback values if schedule or teacher is missing
        $courseData = [
            'id' => $subject->id, // Add the subject ID here
            'description' => $subject->description,
            'instructor_name' => $schedule && $schedule->teacher ? $schedule->teacher->FullName : 'N/A',
            'room_name' => $schedule ? $schedule->room : 'N/A',
            'corrected_day' => $schedule ? $schedule->corrected_day : 'N/A',
            'corrected_time' => $schedule ? $schedule->corrected_time : 'N/A',
            'updatedDate' => $schedule->updated_at->format('F d, Y'),
            'CUSTOM_GoogleClassroom' => $subject->CUSTOM_GoogleClassroom ?? 'N/A', // Ensure this property exists
        ];

        // Retrieve the user's theme preference from session
        $userTheme = session('theme');

        return view('subjects.mini', [
            'courseData' => (object) $courseData, // Convert to object for Blade access
            'theme' => $userTheme,
        ]);
    }
}
