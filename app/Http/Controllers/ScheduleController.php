<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\{Subject, SubjectEnrolled};
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class ScheduleController extends Controller
{
    public $timelineData = [
        "07:00", "07:30", "08:00", "08:30", "09:00", "09:30", "10:00", "10:30", "11:00", "11:30",
        "12:00", "12:30", "01:00", "01:30", "02:00", "02:30", "03:00", "03:30", "04:00", "04:30",
        "05:00", "05:30", "06:00", "06:30", "07:00", "07:30", "08:00", "08:30"
    ];

    public function index()
    {
        // Get the authenticated user (assuming it's a student)
        $user = Auth::user();

        // Fetch data only for the current user from the subjects_enrolled and subjects tables
        $scheduleData = SubjectEnrolled::with(['subject', 'subject.semester', 'subject.schoolYear'])
        ->join('subjects', 'subjects_enrolled.subject_id', '=', 'subjects.id')
        ->select('subjects_enrolled.student_id', 'subjects_enrolled.subject_id', 'subjects.*')
        ->where('subjects_enrolled.student_id', $user->student->id) 
        ->where('subjects.school_year_id', Session::get('current_school_year_id'))
        ->where('subjects.semester_id', Session::get('current_semester_id'))
        ->get();

        // Organize the data by corrected_day for easy rendering in the view
        $organizedScheduleData = $this->organizeScheduleData($scheduleData);

        $timelineData = $this->timelineData;

        // Select the latest date from the schedule data
        $latestDate = $this->getLatestDate($scheduleData);

        // Format the latest date in the desired format
        $formattedLatestDate = Carbon::parse($latestDate)->format('F d, Y (h:i A)');

        // Check if $scheduleData is empty
        if ($scheduleData->isEmpty()) {
            return view('schedule_empty');
        }
        
        // Pass the organized schedule data to the view
        return view('schedule', compact('timelineData', 'organizedScheduleData', 'formattedLatestDate'));
    }

    private function getLatestDate($scheduleData)
    {
        // Extract all the created_at and updated_at values
        $timestamps = $scheduleData->pluck('created_at')->merge($scheduleData->pluck('updated_at'));

        // Use max() to find the latest date among all events
        return $timestamps->max();
    }

    private function organizeScheduleData($scheduleData)
    {
        // Define the order of days in a week
        $daysOrder = ['MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY'];

        // Organize the data by corrected_day for easy rendering in the view
        $organizedData = [];

        // Counter to track the event number
        $eventCounter = 1;

        // Mapping to track the relationship between subject_id and event_id
        $subjectEventMapping = [];

        foreach ($scheduleData as $event) {
            if (stripos(strtolower($event->subject_code), '(lab)') !== false) {
                // Skip subjects with "(lec)" regardless of case
                continue;
            }            

            $event->subject_code = str_ireplace('-(LEC)', '',  strtoupper($event->subject_code));

            $correctedDays = explode(',', $event->corrected_day);
            $correctedTimes = explode(',', $event->corrected_time);

            foreach ($correctedDays as $correctedDay) {
                // Check if the corrected day is valid
                if (in_array($correctedDay, $daysOrder)) {
                    foreach ($correctedTimes as $correctedTime) {
                         //echo '<b>'.$correctedDay.'</b><br>';
                         //echo $correctedTime.'<br><br>';

                        // // Adjust the time format if there are multiple times
                        // $correctedTimeParts = explode('-', $correctedTime);
                        // $startTime = trim($correctedTimeParts[0]);
                        // $endTime = count($correctedTimeParts) > 1 ? trim($correctedTimeParts[1]) : '';
    
                        // echo 'Start Time: '.$startTime.'<br>';
                        // echo 'End Time: '.$endTime.'<br><br>';

                        // // Add the event to the organized data using the day name as key
                        // $event->start_military_time = Carbon::parse($startTime)->format('H:i');
                        // $event->end_military_time = Carbon::parse($endTime)->format('H:i');

                        // $event->start_civilian_time = $startTime;
                        // $event->end_civilian_time = $endTime;

                        // $organizedData[$correctedDay][] = $event;

                        // Adjust the time format if there are multiple times
                        $correctedTimeParts = explode('-', $correctedTime);
                        $startTime = trim($correctedTimeParts[0]);
                        $endTime = count($correctedTimeParts) > 1 ? trim($correctedTimeParts[1]) : '';

                        // Check if subject_id already has an event_id assigned
                        $subjectId = $event->subject_id;
                        if (!isset($subjectEventMapping[$subjectId])) {
                            // If not, assign a new event_id and update the mapping
                            $eventId = 'event-' . $eventCounter;
                            $subjectEventMapping[$subjectId] = $eventId;
            
                            // Increment the event counter and reset when it reaches 20
                            $eventCounter++;
                            if ($eventCounter > 20) {
                                $eventCounter = 1;
                            }
                        } else {
                            // Use the existing event_id for the subject_id
                            $eventId = $subjectEventMapping[$subjectId];
                        }            

                        // Add the event details to the organized data using the day name as key
                        $eventDetails = [
                            'event_id' => $eventId,
                            'subject_id' => $event->id,
                            'subject_code' => $event->subject_code,
                            'description' => $event->description,
                            'room_name' => $event->room_name,
                            'start_military_time' => Carbon::parse($startTime)->format('H:i'),
                            'end_military_time' => Carbon::parse($endTime)->format('H:i'),
                            'start_civilian_time' => $startTime,
                            'end_civilian_time' => $endTime,
                            'corrected_time' => $correctedTime,
                        ];

                        $organizedData[$correctedDay][] = $eventDetails;
                    }
                } else {
                    // Debugging code for invalid days
                    //echo 'Invalid day: ' . $correctedDay . '<br>';
                }
            }
        }

        // Sort the organized data by day order and maintain the associative array
        $sortedData = [];
        foreach ($daysOrder as $day) {
            if (isset($organizedData[$day])) {
                $sortedData[$day] = $organizedData[$day];
            }
        }

        return $sortedData;
    }


}
