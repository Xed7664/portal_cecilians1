<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Semester;
use App\Models\SchoolYear;

class SchoolYearController extends Controller
{
    public static function getCurrentSchoolYearName()
    {
        // Retrieve the current school year ID from the session
        $currentSchoolYearId = Session::get('current_school_year_id');

        // Fetch the name of the current school year from the database
        $currentSchoolYear = SchoolYear::find($currentSchoolYearId);

        if ($currentSchoolYear) {
            return $currentSchoolYear->name;
        } else {
            // Handle the case where the current school year is not found
            return 'Select'; // Return a default value or handle the error as needed
        }
    }

    public static function getSchoolYears()
    {
        // Retrieve the ID of the currently selected school year from the session
        $currentSchoolYearId = Session::get('current_school_year_id');

        // Retrieve a list of school years from the database, excluding the current one
        $schoolYears = SchoolYear::where('id', '!=', $currentSchoolYearId)->get();

        return $schoolYears;
    }
    
}
