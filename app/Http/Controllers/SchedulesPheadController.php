<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Subject;
use App\Models\Employee;
use App\Models\Schedule;
use App\Models\Semester;
use App\Models\YearLevel;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use App\Models\DepartmentSubject;
use App\Models\SubjectsProspectus;
use Illuminate\Support\Facades\DB;

class SchedulesPheadController extends Controller
{
    public function index(Request $request)
    {
        $programId = auth()->user()->employee->department_id;

        // Fetch filter inputs from the request
        $selectedYearLevel = $request->input('year_level_id');
        $selectedSection = $request->input('section_id');

        // Fetch schedules with related models and apply filters if provided
        $schedulesQuery = Schedule::with([
            'subject',
            'program',
            'yearLevel',
            'section',
            'teacher',
            'semester',
            'schoolYear'
        ])->whereHas('program', function ($query) use ($programId) {
            $query->where('id', $programId);
        });

        // Apply Year Level filter if selected
        if ($selectedYearLevel) {
            $schedulesQuery->where('year_level_id', $selectedYearLevel);
        }

        // Apply Section filter if selected
        if ($selectedSection) {
            $schedulesQuery->where('section_id', $selectedSection);
        }

        // Get the filtered schedules
        $schedules = $schedulesQuery->orderBy('id', 'desc')->get();

        // Fetch subjects, teachers, year levels, and sections for dropdowns
        $subjects = SubjectsProspectus::with('subject')->where('program_id', $programId)->get();
        $teachers = Employee::where('department_id', $programId)->get();
        $yearLevels = YearLevel::all();
        $sections = Section::all();
        $currentSemester = Semester::latest()->first();
        $currentSchoolYear = SchoolYear::latest()->first();

        return view('phead.schedules', compact(
            'schedules',
            'subjects',
            'teachers',
            'yearLevels',
            'sections',
            'currentSemester',
            'currentSchoolYear',
            'selectedYearLevel',
            'selectedSection'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:employees,id',
            'year_level_id' => 'required|exists:year_levels,id',
            'section_id' => 'required|exists:sections,id',
            'room' => 'required|string',
            'days' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);
    
        $programId = auth()->user()->employee->department_id;
        $day = $validated['days'];
        $startTime = $validated['start_time'];
        $endTime = $validated['end_time'];
        $room = $validated['room'];
        $yearLevelId = $validated['year_level_id'];
        $sectionId = $validated['section_id'];
    
        // Check for conflicting schedules within the same program
        $conflictingSchedule = Schedule::where('program_id', $programId)
            ->where('year_level_id', $yearLevelId)
            ->where('section_id', $sectionId)
            ->where('days', $day)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                      ->orWhereBetween('end_time', [$startTime, $endTime])
                      ->orWhere(function ($query) use ($startTime, $endTime) {
                          $query->where('start_time', '<', $startTime)
                                ->where('end_time', '>', $endTime);
                      });
            })
            ->exists();
    
        // Check for conflicting schedules in the same room across departments
        $crossDepartmentConflict = Schedule::where('room', $room)
            ->where('days', $day)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                      ->orWhereBetween('end_time', [$startTime, $endTime])
                      ->orWhere(function ($query) use ($startTime, $endTime) {
                          $query->where('start_time', '<', $startTime)
                                ->where('end_time', '>', $endTime);
                      });
            })
            ->exists();
    
        if ($conflictingSchedule || $crossDepartmentConflict) {
            return response()->json(['error' => 'The selected time conflicts with an existing schedule.'], 409);
        }
    
        // Proceed to create a new schedule
        $schedule = new Schedule($validated);
        $schedule->program_id = $programId;
        $schedule->semester_id = Semester::latest()->first()->id;
        $schedule->school_year_id = SchoolYear::latest()->first()->id;
    
        $schedule->time = date('h:i A', strtotime($startTime)) . ' - ' . date('h:i A', strtotime($endTime));
        $schedule->corrected_time = $schedule->time;
        $schedule->corrected_day = $day;
        $schedule->save();
    
        $schedule->load(['subject', 'teacher']);
    
        return response()->json(['schedule' => $schedule, 'success' => 'Schedule successfully created.']);
    }
    


    public function update(Request $request, $scheduleId)
    {
        $request->merge([
            'start_time' => date("H:i", strtotime($request->input('start_time'))),
            'end_time' => date("H:i", strtotime($request->input('end_time'))),
        ]);
    
        $validatedData = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:employees,id',
            'year_level_id' => 'required|exists:year_levels,id',
            'section_id' => 'required|exists:sections,id',
            'room' => 'required|string|max:50',
            'days' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);
    
        $schedule = Schedule::findOrFail($scheduleId);
        $programId = auth()->user()->employee->department_id;
        $day = $validatedData['days'];
        $startTime = $validatedData['start_time'];
        $endTime = $validatedData['end_time'];
        $room = $validatedData['room'];
    
        // Check for conflicting schedules within the same program
        $conflictingSchedule = Schedule::where('id', '!=', $scheduleId)
            ->where('program_id', $programId)
            ->where('year_level_id', $validatedData['year_level_id'])
            ->where('section_id', $validatedData['section_id'])
            ->where('days', $day)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                      ->orWhereBetween('end_time', [$startTime, $endTime])
                      ->orWhere(function ($query) use ($startTime, $endTime) {
                          $query->where('start_time', '<', $startTime)
                                ->where('end_time', '>', $endTime);
                      });
            })
            ->exists();
    
        // Check for cross-departmental room conflicts
        $crossDepartmentConflict = Schedule::where('id', '!=', $scheduleId)
            ->where('room', $room)
            ->where('days', $day)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                      ->orWhereBetween('end_time', [$startTime, $endTime])
                      ->orWhere(function ($query) use ($startTime, $endTime) {
                          $query->where('start_time', '<', $startTime)
                                ->where('end_time', '>', $endTime);
                      });
            })
            ->exists();
    
        if ($conflictingSchedule || $crossDepartmentConflict) {
            return response()->json(['error' => 'The selected time conflicts with an existing schedule.'], 409);
        }
    
        $schedule->update($validatedData);
    
        $correctedTime = date('h:i A', strtotime($validatedData['start_time'])) . ' - ' . date('h:i A', strtotime($validatedData['end_time']));
        $schedule->time = $correctedTime;
        $schedule->corrected_day = $validatedData['days'];
        $schedule->corrected_time = $correctedTime;
        $schedule->save();
    
        return response()->json(['schedule' => $schedule], 200);
    }
    



   

    public function getSections($yearLevelId)
{
    $sections = Section::all(['id', 'name']);
    return response()->json(['sections' => $sections]);
}


    
    public function destroy($id)
{
    $schedule = Schedule::findOrFail($id);
    $schedule->delete();
    return response()->json(['success' => 'Schedule deleted successfully']);
}



}
