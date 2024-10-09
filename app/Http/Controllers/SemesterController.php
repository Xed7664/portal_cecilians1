<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Semester;
use App\Models\SchoolYear;

class SemesterController extends Controller
{
    public static function getCurrentSemesterName()
    {
        // Retrieve the current semester ID from the session
        $currentSemesterId = Session::get('current_semester_id');

        // Fetch the name of the current semester from the database
        $currentSemester = Semester::find($currentSemesterId);

        if ($currentSemester) {
            return $currentSemester->name;
        } else {
            // Handle the case where the current semester is not found
            return 'Select'; // Return a default value or handle the error as needed
        }
    }

    public static function getSemesters()
    {
        // Retrieve the current school year ID from the session or wherever it's stored
        $currentSchoolYearId = Session::get('current_school_year_id');

        // Fetch the current school year
        $currentSchoolYear = SchoolYear::find($currentSchoolYearId);

        // Check if the current school year exists and fetch its associated semesters
        if ($currentSchoolYear) {
            $semesters = $currentSchoolYear->semesters;

            // Map semester names to their human-readable equivalents
            $semesters = $semesters->map(function ($semester) {
                switch ($semester->name) {
                    case '1st Sem':
                        $semester->name = 'First Semester';
                        break;
                    case '2nd Sem':
                        $semester->name = 'Second Semester';
                        break;
                    // Add more cases as needed for other semester names
                }
                return $semester;
            });

            return $semesters; // Returns a collection of Semester objects with modified names
        } else {
            return collect(); // Return an empty collection if the current school year is not found
        }
    }

    
   


}
