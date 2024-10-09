<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use App\Models\{User, Student, Subject, SubjectEnrolled, SchoolYear, Semester};
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index()
    {
        $data = Student::all();
        return view('admin.users.student', ['userType' => 'student', 'data' => $data]);
    }

    public function checkFile(Request $request)
    {
        // Check if files were uploaded
        if ($request->hasFile('file')) {
            $file = $request->file('file');
    
            // Validate the file type
            if (
                $file->getClientMimeType() === 'application/vnd.ms-excel' ||
                $file->getClientMimeType() === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ) {
                // Process the uploaded file
                $filePath = $file->getRealPath();
    
                // Load the file using PhpSpreadsheet
                $spreadsheet = IOFactory::load($filePath);
                $sheet = $spreadsheet->getActiveSheet();
    
                // Check if the required headers exist
                $requiredHeaders = [
                    'Student ID',
                    'FullName',
                    'Picture',
                    'Enrollement_select::Subject code',
                    'Enrollement_select::Description',
                    'Enrollement_select::Room name',
                    'Enrollement_select::Day',
                    'Enrollement_select::Time',
                    'Enrollement_select::Units',
                    'Enrollement_select::instructor_name',
                    'Enrollement_select::amount',
                    'B_date',
                    'Age',
                    'ttl_units',
                    'B_place',
                    'Sex',
                    'Religion',
                    'Citizenship',
                    'Home_Add',
                    'Home_No',
                    'F_name',
                    'F_Business_Add',
                    'F_Occupation',
                    'F_Tel_No',
                    'F_Mob_No',
                    'F_Email_Add',
                    'M_name',
                    'M_Business_Add',
                    'M_Occupation',
                    'M_Tel_No',
                    'M_Mob_No',
                    'M_Email_Add',
                    'G_name',
                    'G_Relationship',
                    'G_Business_add',
                    'G_Occupation',
                    'G_Tel_No',
                    'G_Mob_Mo',
                    'G_Email_add',
                    'Civil Status',
                    'Zipcode',
                    'Semester',
                    'Grade / Year Level',
                    'Section',
                    'Major',
                    'Course',
                    'SY',
                    'type',
                    'Type of Scholarship',
                    'Fees Status',
                    'Parentsname',
                    'SLastAttended',
                    'SLastAttended_addtel',
                    'withdraw',
                    'expelledMonth'
                ];                
    
                $headersRow = $sheet->getRowIterator()->current();
                $headers = [];
    
                foreach ($headersRow->getCellIterator() as $cell) {
                    $headers[] = $cell->getValue();
                }
    
                if (count(array_diff($requiredHeaders, $headers)) === 0) {
                    // Headers match, retrieve data from Sheet1
                    $dataSheet = $spreadsheet->getSheetByName('Sheet1');
                    $data = [];
    
                    foreach ($dataSheet->getRowIterator() as $row) {
                        $rowData = [];
                        $error = false; // Flag to track errors in the current row
                        $studentName = ''; // Variable to store the name of the student causing the error
    
                        foreach ($row->getCellIterator() as $cell) {
                            $columnIndex = $cell->getColumn();
                            $cellValue = $cell->getValue();
    
                            // Convert birthdate column to the desired format (you can use your existing code for this)
    
                            $rowData[] = $cellValue;
                        }
    
                        // Process the $rowData, you can save it to the database or perform any other operations
                        $data[] = $rowData;
                    }
    
                    // Now you have the data from the XLS file; you can proceed to use it as needed
                    return response()->json(['result' => true, 'message' => 'File is valid', 'data' => $data]);
                } else {
                    return response()->json(['result' => false, 'message' => 'File is missing one or more required headers']);
                }
            } else {
                return response()->json(['result' => false, 'message' => 'Invalid file format. Please upload an XLS file.']);
            }
        } else {
            return response()->json(['result' => false, 'message' => 'No file uploaded']);
        }
    }

    public function upload(Request $request)
    {
        // Check if files were uploaded
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            $data = [];
            $currentStudent = null;

            // Mapping array for correcting days
            $dayCorrectionMap = [
                'MONDAY' => 'MONDAY',
                'M' => 'MONDAY',
                'MON' => 'MONDAY',
                'MON,FRI' => 'MONDAY,FRIDAY',
                'TUESDAY' => 'TUESDAY',
                'T' => 'TUESDAY',
                'TUE' => 'TUESDAY',
                'TUE,THURSDAY' => 'TUESDAY,THURSDAY',
                'WEDNESDAY' => 'WEDNESDAY',
                'W' => 'WEDNESDAY',
                'WED' => 'WEDNESDAY',
                'THURSDAY' => 'THURSDAY',
                'THURSDAY,FRIDAY' => 'THURSDAY,FRIDAY',
                'TH' => 'THURSDAY',
                'THU' => 'THURSDAY',
                'FRIDAY' => 'FRIDAY',
                'F' => 'FRIDAY',
                'FRI' => 'FRIDAY',
                'SATURDAY' => 'SATURDAY',
                'SAT' => 'SATURDAY',
                'SUNDAY' => 'SUNDAY',
                'SUN' => 'SUNDAY',
                'MW' => 'MONDAY,WEDNESDAY',
                'MON,TUE' => 'MONDAY,TUESDAY',
                'MON,TUE,SAT' => 'MONDAY,TUESDAY,SATURDAY',
                'MON,WED' => 'MONDAY,WEDNESDAY',
                'TTH' => 'TUESDAY,THURSDAY',
                'WED,FRI' => 'WEDNESDAY,FRIDAY',
                'WED&THURS' => 'WEDNESDAY,THURSDAY',
                'WED,THURS,FRI' => 'WEDNESDAY,THURSDAY,FRIDAY',
                'WED,THURS,FRIDAY' => 'WEDNESDAY,THURSDAY,FRIDAY',
                'TTH,FRI' => 'TUESDAY,THURSDAY,FRIDAY',
                'FRI,SAT' => 'FRIDAY,SATURDAY',
                'FRI-SAT' => 'FRIDAY,SATURDAY',
                'FRI/SAT' => 'FRIDAY,SATURDAY',

                // Add more mappings as needed
            ];


            foreach ($files as $file) {
                // Validate the file type
                if (
                    $file->getClientMimeType() === 'application/vnd.ms-excel' ||
                    $file->getClientMimeType() === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                ) {
                    // Process the uploaded file
                    $filePath = $file->getRealPath();

                    // Load the file using PhpSpreadsheet
                    $spreadsheet = IOFactory::load($filePath);
                    $sheet = $spreadsheet->getActiveSheet();

                    // Check if the required headers exist
                    $requiredHeaders = [
                        'Student ID',
                        'FullName',
                        'Picture',
                        'Enrollement_select::Subject code',
                        'Enrollement_select::Description',
                        'Enrollement_select::Room name',
                        'Enrollement_select::Day',
                        'Enrollement_select::Time',
                        'Enrollement_select::Units',
                        'Enrollement_select::instructor_name',
                        'Enrollement_select::amount',
                        'B_date',
                        'Age',
                        'ttl_units',
                        'B_place',
                        'Sex',
                        'Religion',
                        'Citizenship',
                        'Home_Add',
                        'Home_No',
                        'F_name',
                        'F_Business_Add',
                        'F_Occupation',
                        'F_Tel_No',
                        'F_Mob_No',
                        'F_Email_Add',
                        'M_name',
                        'M_Business_Add',
                        'M_Occupation',
                        'M_Tel_No',
                        'M_Mob_No',
                        'M_Email_Add',
                        'G_name',
                        'G_Relationship',
                        'G_Business_add',
                        'G_Occupation',
                        'G_Tel_No',
                        'G_Mob_Mo',
                        'G_Email_add',
                        'Civil Status',
                        'Zipcode',
                        'Semester',
                        'Grade / Year Level',
                        'Section',
                        'Major',
                        'Course',
                        'SY',
                        'type',
                        'Type of Scholarship',
                        'Fees Status',
                        'Parentsname',
                        'SLastAttended',
                        'SLastAttended_addtel',
                        'withdraw',
                        'expelledMonth'
                    ];

                    $headersRow = $sheet->getRowIterator()->current();
                    $headers = [];

                    foreach ($headersRow->getCellIterator() as $cell) {
                        $headers[] = $cell->getValue();
                    }

                    if (count(array_diff($requiredHeaders, $headers)) === 0) {
                        // Headers match, retrieve data from Sheet1
                        $dataSheet = $spreadsheet->getSheetByName('Sheet1');
                        $dataFromFile = [];
                        $isHeaderRow = true;

                        // For the school year and semester
                        $schoolYearModel = null;
                        $semesterModel = null;

                        $rowNumber = 0; // Initialize row number

                        foreach ($dataSheet->getRowIterator() as $row) {
                            $rowData = [];

                            foreach ($row->getCellIterator() as $cell) {
                                $rowData[] = $cell->getValue();
                            }

                            if ($isHeaderRow) {
                                $isHeaderRow = false; // Skip the header row
                                continue;
                            }

                            $dataFromFile[] = $rowData;
                            
                            if (!empty($rowData[0])) {
                                // Parse the date using Carbon
                                $excelDate = $rowData[11]; // Assuming Birthday is in the 12th column
                                $excelDateValue = intval($excelDate); // Convert the value to an integer

                                // Define the reference date
                                $referenceDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($excelDateValue);

                                // Convert to a human-readable date format
                                $birthday = date("Y-m-d", $referenceDate);

                                // Check if the student with the given StudentID already exists
                                $currentStudent = Student::where('StudentID', $rowData[0])->first();

                                // Assuming course is in the 45th column
                                $course = strtoupper($rowData[45]); // Assuming column indexing starts from 0

                                // Define a map for course replacements
                                $courseReplacements = [
                                    'BSBA-MM' => 'BSBA',
                                    'BSED-ENG' => 'BSED',
                                    'BSED-MATH' => 'BSED',
                                ];

                                // Check if the course is in the map, if yes, replace it
                                if (array_key_exists($course, $courseReplacements)) {
                                    $course = $courseReplacements[$course];
                                }

                                // BUG: Gender mistake (FIXED)
                                $gender = $rowData[15];

                                // Create a map for gender values
                                $genderMap = [
                                    'fel' => 'female',
                                    // Add other mappings as needed
                                ];

                                // Check if the gender value exists in the map, if not, keep the original value
                                $gender = isset($genderMap[$gender]) ? $genderMap[$gender] : $gender;

                                if ($currentStudent) {
                                    // Update the existing Student record
                                    $parts = explode(', ', $rowData[1]); // Split the name into parts at the comma and space
                                    $formattedName = ucfirst(strtolower($parts[0])) . ', ' . ucwords(strtolower($parts[1]));

                                    $currentStudent->FullName = $formattedName;
                                    $currentStudent->Birthday = $birthday;
                                    $currentStudent->Gender = $gender;
                                    $currentStudent->Address = $rowData[18];
                                    $currentStudent->Status = $rowData[39];
                                    $currentStudent->Semester = $rowData[41];
                                    $currentStudent->YearLevel = $rowData[42];
                                    $currentStudent->Section = $rowData[43];
                                    $currentStudent->Major = $rowData[44];
                                    $currentStudent->Course = $course;
                                    $currentStudent->Scholarship = $rowData[49];
                                    $currentStudent->SchoolYear = $rowData[46];
                                    $currentStudent->BirthPlace = $rowData[14];
                                    $currentStudent->Religion = $rowData[16];
                                    $currentStudent->Citizenship = $rowData[17];
                                    $currentStudent->Type = $rowData[47];
                                    $currentStudent->save();
                                } else {
                                    // Create a new Student record
                                    $currentStudent = new Student();
                                    $currentStudent->StudentID = $rowData[0];

                                    $parts = explode(', ', $rowData[1]); // Split the name into parts at the comma and space
                                    $formattedName = ucfirst(strtolower($parts[0])) . ', ' . ucwords(strtolower($parts[1]));

                                    $currentStudent->FullName = $formattedName;
                                    $currentStudent->Birthday = $birthday;
                                    $currentStudent->Gender = $gender;
                                    $currentStudent->Address = $rowData[18];
                                    $currentStudent->Status = $rowData[39];
                                    $currentStudent->Semester = $rowData[41];
                                    $currentStudent->YearLevel = $rowData[42];
                                    $currentStudent->Section = $rowData[43];
                                    $currentStudent->Major = $rowData[44];
                                    $currentStudent->Course = $course;
                                    $currentStudent->Scholarship = $rowData[49];
                                    $currentStudent->SchoolYear = $rowData[46];
                                    $currentStudent->BirthPlace = $rowData[14];
                                    $currentStudent->Religion = $rowData[16];
                                    $currentStudent->Citizenship = $rowData[17];
                                    $currentStudent->Type = $rowData[47];
                                    $currentStudent->save();
                                }

                                // Check if school year and semester information is available
                                if (!empty($rowData[42]) && !empty($rowData[41])) {
                                    // Check if a school year with the given name already exists
                                    $schoolYearModel = SchoolYear::firstOrNew(['name' => $rowData[46]]);
                                    
                                    // Save the school year if it's new
                                    if ($schoolYearModel->isDirty()) {
                                        $schoolYearModel->save();
                                    }

                                    // Check if a semester with the given name already exists
                                    $semesterModel = Semester::firstOrNew(['name' => $rowData[41], 'school_year_id' => $schoolYearModel->id]);
                                    
                                    // Associate the semester with the school year
                                    $semesterModel->schoolYear()->associate($schoolYearModel);

                                    // Save the semester if it's new
                                    if ($semesterModel->isDirty()) {
                                        $semesterModel->save();
                                    }
                                }

                                $debug = array(
                                    'school_year:' => $schoolYearModel,
                                    'semester' => $semesterModel
                                );

                                //dd($debug);

                            } elseif ($currentStudent) {
                                
                                // Check if a subject with the same attributes already exists
                                $existingSubject = Subject::where([
                                    'subject_code' => $rowData[3],
                                    'day' => $rowData[6],
                                    'time' => $rowData[7],
                                    'instructor_name' => $rowData[9],
                                    'room_name' => $rowData[5],
                                    'school_year_id' => $schoolYearModel->id,
                                    'semester_id' => $semesterModel->id,
                                ])->first();

                                //dd($existingSubject);

                                if (!$existingSubject) {
                                    $subject = new Subject();
                                } else {
                                    $subject = $existingSubject;
                                }
                                   
                                    // Check if day or time is "TBA"
                                    $dayIsTBA = $rowData[6] === 'TBA' || $rowData[6] === null;
                                    $timeIsTBA = $rowData[7] === 'TBA' || $rowData[7] === null;

                                    if ($dayIsTBA || $timeIsTBA) {
                                        // If day or time is "TBA", record the subject and set corrected_day and corrected_time as empty
                                        //$subject = new Subject();
                                        $subject->subject_code = $rowData[3]; // Assuming the subject code is in the 4th column
                                        $subject->description = $rowData[4]; // Assuming the description is in the 5th column
                                        $subject->room_name = $rowData[5]; // Assuming room_name is in the 6th column
                                        $subject->day = $dayIsTBA ? 'TBA' : $rowData[6]; // Assuming day is in the 7th column 
                                        $subject->corrected_day = $dayIsTBA ? '' : $correctedDay;
                                        $subject->time = $timeIsTBA ? 'TBA' : $rowData[7]; // Assuming time is in the 8th column
                                        $subject->corrected_time = $timeIsTBA ? '' : $correctedTime;
                                        $subject->units = $rowData[8]; // Assuming units is in the 9th column
                                        $subject->instructor_name = $rowData[9]; // Assuming instructor_name is in the 10th column
                                        $subject->amount = empty($rowData[10]) ? 0 : $rowData[10]; // Assuming amount is in the 11th column
                                        $subject->school_year_id = $schoolYearModel->id; // Assuming amount is in the 11th column
                                        $subject->semester_id = $semesterModel->id; // Assuming amount is in the 11th column
                                
                                        $subject->schoolYear()->associate($schoolYearModel); // Set $schoolYearModel
                                        $subject->semester()->associate($semesterModel); // Set $semesterModel
                                        $subject->save(); // Save the new Subject record
                                
                                        continue; // Skip the rest of the processing for this row
                                    }
                                    
                                    // Split the originalDay based on commas
                                    $originalDays = explode(',', strtoupper(str_replace(' ', '', $rowData[6])));

                                    // Initialize an array to store corrected days
                                    $correctedDays = [];

                                    // Map and fix each part
                                    foreach ($originalDays as $originalDay) {
                                        // Check if the original day is in the correction map
                                        if (isset($dayCorrectionMap[$originalDay])) {
                                            // Map and fix the day
                                            $correctedDays[] = $dayCorrectionMap[$originalDay];
                                        } else {
                                            // If not found in the map, use the original day
                                            $correctedDays[] = $originalDay;
                                        }
                                    }

                                    // Join the corrected days back into a comma-separated string
                                    $correctedDay = implode(',', $correctedDays);

                                    // Correcting sa Time

                                    // Assuming time is in the 8th column
                                    $rawTimeRanges = $rowData[7];

                                    // Replace the colon-hyphen combination with colon only
                                    $rawTimeRanges = str_replace(':-', ':', $rawTimeRanges);

                                    // BUG: (CRIM) Errors of OO to 00
                                    $rawTimeRanges = str_replace('OO', '00', strtoupper($rawTimeRanges));
                                    
                                    // Remove spaces
                                    $rawTimeRanges = str_replace(' ', '', $rawTimeRanges);

                                    // Define the pattern
                                    $pattern = '/\d{2}(?:[A-Z]{2}|\d{1,2}(?=[A-Z]|\d|-))/';
                                    
                                    // Find matches in the string
                                    preg_match_all($pattern, strtoupper($rawTimeRanges), $matches);

                                    // Process the matches
                                    foreach ($matches[0] as $match) {
                                        // Check if the character after the two digits is a number
                                        if (is_numeric($match[2])) {
                                            // Log the match
                                            \Log::info("No hyphen found. Match: $match, Raw: $rawTimeRanges");

                                            // Get the position of the match in the raw string
                                            $position = strpos($rawTimeRanges, $match);

                                            // Check if rawTimeRanges contains a hyphen before replacing
                                            if (strpos($rawTimeRanges, '-') === false) {
                                                $rawTimeRanges = substr_replace($rawTimeRanges, substr($match, 0, 2) . '-' . substr($match, 2), $position, strlen($match));
                                                \Log::info("Hyphen fixed. Result: $rawTimeRanges");
                                            } else {
                                                \Log::info("Recheck: Hyphen already present. No changes needed. Result: $rawTimeRanges");
                                            }

                                            // Check the number of colons in the raw string
                                            $colonCount = substr_count($rawTimeRanges, ':');

                                            // Add colons if there's only one
                                            if ($colonCount === 1) {
                                                if (strlen($match) === 3) {
                                                    // Add colon before the last digit of the three-digit match
                                                    $rawTimeRanges = substr_replace($rawTimeRanges, ':', $position + 1, 0);
                                                    \Log::info("Colon added. Match count: ".strlen($match).", Result: $rawTimeRanges");
                                                } elseif (strlen($match) === 4) {
                                                    // Add colon before the last digit of the four-digit match
                                                    $rawTimeRanges = substr_replace($rawTimeRanges, ':', $position + 2, 0);
                                                    \Log::info("Colon added. Match count: ".strlen($match).", Result: $rawTimeRanges");
                                                } else {
                                                    \Log::info("No changes needed. Match count: ".strlen($match).", Result: $rawTimeRanges");
                                                }
                                            }
                                            
                                        }
                                    }

                                    $timeRanges = explode(',', $rawTimeRanges);

                                    // Remove extra hyphens from each time range
                                    // $timeRanges = array_map(function ($timeRange) {
                                    //     // Count the number of hyphens in the time range
                                    //     $hyphenCount = substr_count($timeRange, '-');

                                    //     // Check if there are multiple hyphens
                                    //     if ($hyphenCount > 1) {
                                    //         \Log::info("Multiple hyphen found. Count: $hyphenCount, Raw: $timeRange");

                                    //         // Replace multiple hyphens with a single hyphen
                                    //         $fixedTimeRange = preg_replace('/-+/', '-', $timeRange);

                                    //         // Log the fixed time range
                                    //         \Log::info("Fixed time range: $fixedTimeRange");

                                    //         return $fixedTimeRange;
                                    //     }

                                    //     // If there are not multiple hyphens, return the original time range
                                    //     return $timeRange;
                                    // }, $timeRanges);

                                    // Filter out invalid time ranges
                                    $timeRanges = array_filter($timeRanges, function ($timeRange) {
                                        // Count the number of hyphens in the time range
                                        $hyphenCount = substr_count($timeRange, '-');

                                        // Return true if there is exactly one hyphen, false otherwise
                                        return $hyphenCount === 1;
                                    });


                                    $formattedTimeRanges = []; // Initialize an array to store formatted time ranges

                                    foreach ($timeRanges as $rawTimeRange) {
                                        $rowNumber++; // Increment row number
                                        
                                        try {
                                            // Split the raw time range into start and end times
                                            $timeRangeParts = explode('-', $rawTimeRange);

                                            list($startTime, $endTime) = $timeRangeParts;

                                            // Trim extra spaces from start and end of time strings
                                            // $startTime = trim($startTime);
                                            // $endTime = trim($endTime);

                                            // Additional cleaning if needed, e.g., removing non-breaking spaces
                                            $startTime = str_replace("\xc2\xa0", ' ', $startTime);
                                            $endTime = str_replace("\xc2\xa0", ' ', $endTime);

                                            // Decode HTML entities
                                            $startTime = html_entity_decode($startTime);
                                            $endTime = html_entity_decode($endTime);

                                            $startHasMeridiem = stripos(strtoupper($startTime), 'AM') !== false || stripos(strtoupper($startTime), 'PM') !== false || stripos(strtoupper($startTime), 'NN') !== false;
                                            $endHasMeridiem = stripos(strtoupper($endTime), 'AM') !== false || stripos(strtoupper($endTime), 'PM') !== false || stripos(strtoupper($endTime), 'NN') !== false;
                                            
                                            $formattedStartTime = $startTime; // Initialize the variables
                                            $formattedEndTime = $endTime;
                                    
                                            // If endTime has NN on it, set the startTime to AM
                                            if (stripos(strtoupper($endTime), 'NN') !== false) {
                                                $endTime = str_replace('NN', 'PM', $endTime);
                                                if (!$startHasMeridiem && $endHasMeridiem) {
                                                    $guide = 'AM';
                                                    $startTime = \Carbon\Carbon::parse(trim($startTime . ' ' . $guide))->format('h:i A');
                                                }
                                            } else {
                                                // Replace 'NN' with 'PM' in both start and end times
                                                $startTime = str_replace('NN', 'PM', $startTime);
                                                $endTime = str_replace('NN', 'PM', $endTime);
                                            }                                            
                                    
                                            // Rerun test
                                            $startHasMeridiem = stripos(strtoupper($startTime), 'AM') !== false || stripos(strtoupper($startTime), 'PM') !== false || stripos(strtoupper($startTime), 'NN') !== false;
                                            $endHasMeridiem = stripos(strtoupper($endTime), 'AM') !== false || stripos(strtoupper($endTime), 'PM') !== false || stripos(strtoupper($endTime), 'NN') !== false;

                                            if ($startHasMeridiem && !$endHasMeridiem) {
                                                // If start time has meridiem and end time has none, assign end time with meridiem nearer to start time
                                                $guide = strtoupper(substr(trim($startTime), -2));
                                                $formattedEndTime = \Carbon\Carbon::parse(trim($endTime . ' ' . $guide))->format('h:i A');
                                            } elseif (!$startHasMeridiem && $endHasMeridiem) {
                                                // If end time has meridiem and start time has none, assign start time with meridiem nearer to end time
                                                $guide = strtoupper(substr(trim($endTime), -2));
                                                $formattedStartTime = \Carbon\Carbon::parse(trim($startTime . ' ' . $guide))->format('h:i A');
                                            } else {
                                                // If both start time and end time have meridiem, or both have none, no need to change
                                                $formattedStartTime = \Carbon\Carbon::parse(trim($startTime))->format('h:i A');
                                                $formattedEndTime = \Carbon\Carbon::parse(trim($endTime))->format('h:i A');
                                            }

                                            // Check if start time is beyond 9:00 PM, then adjust it
                                            if (\Carbon\Carbon::parse($formattedStartTime)->greaterThan(\Carbon\Carbon::parse('9:00 PM'))) {

                                                // Adjust the start time to AM
                                                $formattedStartTime = \Carbon\Carbon::parse(str_replace('PM', 'AM', $formattedStartTime))->format('h:i A');

                                                // Log the adjustment
                                                \Log::info("Start time adjusted to AM. Result: $formattedStartTime");
                                            }

                                            $formattedTimeRanges[] = "$formattedStartTime - $formattedEndTime";


                                            // Log a success message
                                            \Log::info("Success! Row: $rowNumber, Code: $rowData[3], Raw: $rawTimeRange, Result: $formattedStartTime - $formattedEndTime");

                                        } catch (\Exception $e) {
                                            // Log the exception along with row number and raw time range
                                            \Log::error("Error processing time range. Row: $rowNumber, Raw time range: $rawTimeRange");
                                            \Log::error("Error message: " . $e->getMessage());
                                        }
                                    
                                    }
                                
                                    // Join the formatted time ranges back into a comma-separated string
                                    $correctedTime = implode(', ', $formattedTimeRanges);                                

                                    // Create a new Subject record
                                    //$subject = new Subject();
                                    $subject->subject_code = $rowData[3]; // Assuming the subject code is in the 4th column
                                    $subject->description = $rowData[4]; // Assuming the description is in the 5th column
                                    $subject->room_name = $rowData[5]; // Assuming room_name is in the 6th column
                                    $subject->day = $rowData[6]; // Assuming day is in the 7th column 
                                    $subject->corrected_day = $correctedDay;
                                    $subject->time = $rowData[7]; // Assuming time is in the 8th column
                                    $subject->corrected_time = $correctedTime;
                                    $subject->units = $rowData[8]; // Assuming units is in the 9th column
                                    $subject->instructor_name = $rowData[9]; // Assuming instructor_name is in the 10th column
                                    $subject->amount = empty($rowData[10]) ? 0 : $rowData[10]; // Assuming amount is in the 11th column
                                    $subject->school_year_id = $schoolYearModel->id; // Assuming amount is in the 11th column
                                    $subject->semester_id = $semesterModel->id; // Assuming amount is in the 11th column

                                    $subject->schoolYear()->associate($schoolYearModel); // Set $schoolYearModel
                                    $subject->semester()->associate($semesterModel); // Set $semesterModel
                                    $subject->save(); // Save the new Subject record
                                //  } else {
                                //      $subject = $existingSubject;

                                //     $subject->subject_code = $rowData[3]; // Update with the new value
                                //     $subject->description = $rowData[4]; // Update with the new value
                                //     $subject->room_name = $rowData[5]; // Update with the new value
                                //     $subject->day = $rowData[6]; // Update with the new value
                                //     // ... update other fields ...

                                //     $subject->save(); // Save the updated Subject recor
                                // }

                                // Check if the student is already enrolled in this subject
                                $enrollmentExists = SubjectEnrolled::where('student_id', $currentStudent->id)
                                    ->where('subject_id', $subject->id)
                                    ->exists();

                                if (!$enrollmentExists) {
                                    // Enroll the student in this subject (create a new enrollment record)
                                    $enrollment = new SubjectEnrolled();
                                    $enrollment->student_id = $currentStudent->id;
                                    $enrollment->subject_id = $subject->id;

                                    $enrollment->semester()->associate($subject->semester);
                                    $enrollment->schoolYear()->associate($subject->schoolYear);

                                    $enrollment->save();
                                }

                            }
                        }

                        // Append the data from this file to the result array
                        $data[] = $dataFromFile;

                        
                    } else {
                        // Handle missing required headers
                        return response()->json(['result' => false, 'message' => 'File is missing one or more required headers']);
                    }
                } else {
                    // Handle invalid file format
                    return response()->json(['result' => false, 'message' => 'Invalid file format. Please upload an XLS file.']);
                }
            }

            // Return the response with the combined data (if needed)
            return response()->json(['result' => true, 'message' => 'Data are uploaded successfully.', 'data' => $data]);
        } else {
            return response()->json(['result' => false, 'message' => 'No files uploaded']);
        }
    }

}
