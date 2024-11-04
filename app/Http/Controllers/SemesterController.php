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
    // Retrieve the current school year ID from the session
    $currentSchoolYearId = Session::get('current_school_year_id');

    // Fetch semesters based on the current school year
    $schoolYear = SchoolYear::with('semesters')->find($currentSchoolYearId);

    if ($schoolYear) {
        $semesters = $schoolYear->semesters->map(function ($semester) {
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

        return $semesters;
    } else {
        return collect(); // Return an empty collection if no semesters found
    }
}

public function getSemestersBySchoolYear($schoolYearId)
{
    $schoolYear = SchoolYear::with('semesters')->find($schoolYearId);

    // Ensure that we return only the semesters in JSON format.
    return response()->json($schoolYear ? $schoolYear->semesters : []);
}



}
