<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use App\Models\{
    User,
    Student,
    Subject,
    SubjectEnrolled,
    SchoolYear,
    Semester,
    Schedule,
    Department,
    YearLevel,
    Section
};
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;


class StudentController extends Controller
{
    public function index()
    {
        $data = Student::with('program')->get(); // Load the program relationship
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
                                // Mapping arrays for data normalization
                                $semesterMap = [
                                    '1st Semester' => '1st',
                                    '2nd Semester' => '2nd',
                                    'Summer Semester' => 'Summer',
                                    '1st' => '1st',
                                    '2nd' => '2nd',
                                    'Summer' => 'Summer',
                                ];
                                $sectionMap = [
                                    '2A' => 'A',
                                    '2B' => 'B',
                                    // Add other mappings as needed
                                ];

                                $programMap = [
                                    'BSIT' => 'BSIT',
                                    'BSBA' => 'BSBA',
                                    // Continue mapping for other programs if necessary
                                ];

                                // Normalize values from the file for Semester and Section
                                $semesterName = $semesterMap[$rowData[41]] ?? $rowData[41];
                                $sectionName = $sectionMap[$rowData[43]] ?? $rowData[43];
                                $programCode = $programMap[$rowData[45]] ?? $rowData[45];

                                 // Look up or create the School Year record
                                $schoolYearModel = SchoolYear::firstOrCreate(['name' => $rowData[46]]);

                                // Look up or create the Semester record
                                $semesterModel = Semester::firstOrCreate(['name' => $semesterName]);

                                // Attach the semester to the school year via the pivot table
                                $schoolYearModel->semesters()->syncWithoutDetaching([$semesterModel->id]);

                                $yearLevelModel = YearLevel::firstOrCreate(['name' => $rowData[42]]);
                                $sectionModel = Section::firstOrCreate(['name' => $sectionName]);
                                $programModel = Department::firstOrCreate(['code'=>$programCode]);

                                if ($programModel === null) {
                                    // Handle missing program if necessary
                                    $programModel = new Department(['code' => $programCode]); // Example placeholder
                                }

                                // Check if the student already exists
                                if ($currentStudent) {
                                    // Update existing student
                                    $parts = explode(', ', $rowData[1]);
                                    $formattedName = ucfirst(strtolower($parts[0])) . ', ' . ucwords(strtolower($parts[1]));

                                    $currentStudent->FullName = $formattedName;
                                    $currentStudent->Birthday = $birthday;
                                    $currentStudent->Gender = $gender;
                                    $currentStudent->Address = $rowData[18];
                                    $currentStudent->Status = $rowData[39];
                                    $currentStudent->semester_id = $semesterModel->id;
                                    $currentStudent->year_level_id = $yearLevelModel->id;
                                    $currentStudent->section_id = $sectionModel->id;
                                    $currentStudent->program_id = $programModel->id;
                                    $currentStudent->Major = $rowData[44];
                                    $currentStudent->Scholarship = $rowData[49];
                                    $currentStudent->school_year_id = $schoolYearModel->id;
                                    $currentStudent->BirthPlace = $rowData[14];
                                    $currentStudent->Religion = $rowData[16];
                                    $currentStudent->Citizenship = $rowData[17];
                                    $currentStudent->Type = $rowData[47];
                                    $currentStudent->save();
                                } else {
                                    // Create new student
                                    $currentStudent = new Student();
                                    $currentStudent->StudentID = $rowData[0];

                                    $parts = explode(', ', $rowData[1]);
                                    $formattedName = ucfirst(strtolower($parts[0])) . ', ' . ucwords(strtolower($parts[1]));

                                    $currentStudent->FullName = $formattedName;
                                    $currentStudent->Birthday = $birthday;
                                    $currentStudent->Gender = $gender;
                                    $currentStudent->Address = $rowData[18];
                                    $currentStudent->Status = $rowData[39];
                                    $currentStudent->semester_id = $semesterModel->id;
                                    $currentStudent->year_level_id = $yearLevelModel->id;
                                    $currentStudent->section_id = $sectionModel->id;
                                    $currentStudent->program_id = $programModel->id;
                                    $currentStudent->Major = $rowData[44];
                                    $currentStudent->Scholarship = $rowData[49];
                                    $currentStudent->school_year_id = $schoolYearModel->id;
                                    $currentStudent->BirthPlace = $rowData[14];
                                    $currentStudent->Religion = $rowData[16];
                                    $currentStudent->Citizenship = $rowData[17];
                                    $currentStudent->Type = $rowData[47];
                                    $currentStudent->save();
                                }

                                // Debug output to verify mappings
                                $debug = [
                                    'school_year' => $schoolYearModel,
                                    'semester' => $semesterModel,
                                    'year_level' => $yearLevelModel,
                                    'section' => $sectionModel,
                                    'program' => $programModel
                                ];

                                // Uncomment for debugging
                                // dd($debug);


                            }elseif ($currentStudent) {
                              // Check if a subject with the same attributes already exists
                                    $existingSubject = Subject::where([
                                        'subject_code' => $rowData[3],
                                    ])->first();
                                    
                                if (!$existingSubject) {
                                    $subject = new Subject();
                                } else {
                                    $subject = $existingSubject;
                                }
                            
                              
                            
                                // Check if a schedule with the same attributes already exists
                                $dayIsTBA = $rowData[6] === 'TBA' || $rowData[6] === null;
                                $timeIsTBA = $rowData[7] === 'TBA' || $rowData[7] === null;
                                $existingSchedule = Schedule::where([
                                    'subject_id' => $subject->id,
                                    'program_id' => $programModel->id,
                                    'year_level_id' => $yearLevelModel->id,
                                    'section_id' => $sectionModel->id,
                                    'teacher_id' => $teacherModel->id ?? null,
                                    'semester_id' => $semesterModel->id,
                                    'school_year_id' => $schoolYearModel->id,
                                    'room' => $rowData[5],
                                    'days' => $dayIsTBA ? 'TBA' : $rowData[6],
                                    'time' => $timeIsTBA ? 'TBA' : $rowData[7],
                                ])->first();
                            
                                if (!$existingSchedule) {
                                    $schedule = new Schedule();
                                } else {
                                    $schedule = $existingSchedule;
                                }
                            
                                if ($dayIsTBA || $timeIsTBA) {
                                      // Update or create the subject record
                                    $subject->subject_code = $rowData[3]; // Assuming the subject code is in the 4th column
                                    $subject->description = !empty($rowData[4]) ? $rowData[4] : 'No Description Provided';
                                    $subject->units = $rowData[8]; // Assuming units is in the 9th column
                                    $subject->amount = empty($rowData[10]) ? 0 : $rowData[10]; // Assuming amount is in the 11th column
                                    $subject->save();
                                    // Update or create the schedule record
                                    $schedule->subject_id = $subject->id;
                                    $schedule->program_id = $programModel->id;
                                    $schedule->year_level_id = $yearLevelModel->id;
                                    $schedule->section_id = $sectionModel->id;
                                    $schedule->teacher_id = $teacherModel->id ?? null;
                                    $schedule->semester_id = $semesterModel->id;
                                    $schedule->school_year_id = $schoolYearModel->id;
                                    $schedule->room = $rowData[5];
                                    $schedule->days = $dayIsTBA ? 'TBA' : $rowData[6];
                                    $schedule->corrected_day = $dayIsTBA ? '' : $correctedDay;
                                    $schedule->time = $timeIsTBA ? 'TBA' : $rowData[7];
                                    $schedule->corrected_time = $timeIsTBA ? '' : $correctedTime;
                                    $schedule->save();
                            
                                    continue; // Skip the rest of the processing for this row
                                }
                            
                                // Correct days
                                $originalDays = explode(',', strtoupper(str_replace(' ', '', $rowData[6])));
                                $correctedDays = array_map(function ($originalDay) use ($dayCorrectionMap) {
                                    return $dayCorrectionMap[$originalDay] ?? $originalDay;
                                }, $originalDays);
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
                            
                                // Update or create the subject record
                                $subject->subject_code = $rowData[3]; // Assuming the subject code is in the 4th column
                                $subject->description = !empty($rowData[4]) ? $rowData[4] : 'No Description Provided';
                                $subject->units = $rowData[8]; // Assuming units is in the 9th column
                                $subject->amount = empty($rowData[10]) ? 0 : $rowData[10]; // Assuming amount is in the 11th column
                                $subject->save();
                            
                                // Update or create schedule
                                $schedule->subject_id = $subject->id;
                                $schedule->program_id = $programModel->id;
                                $schedule->year_level_id = $yearLevelModel->id;
                                $schedule->section_id = $sectionModel->id;
                                $schedule->teacher_id = $teacherModel->id ?? null;
                                $schedule->semester_id = $semesterModel->id;
                                $schedule->school_year_id = $schoolYearModel->id;
                                $schedule->room = $rowData[5];
                                $schedule->days = $dayIsTBA ? 'TBA' : $rowData[6];
                                $schedule->corrected_day = $dayIsTBA ? '' : $correctedDay;
                                $schedule->time = $timeIsTBA ? 'TBA' : $rowData[7];
                                $schedule->corrected_time = $timeIsTBA ? '' : $correctedTime;
                                $schedule->save();
                            
                                // Enroll student in subject if not already enrolled
                                if (!SubjectEnrolled::where([
                                    'student_id' => $currentStudent->id,
                                    'subject_id' => $subject->id,
                                    'schedule_id' => $schedule->id,
                                ])->exists()) {
                                    $enrollment = new SubjectEnrolled([
                                        'student_id' => $currentStudent->id,
                                        'subject_id' => $subject->id,
                                        'section_id' => $sectionModel->id ?? null,
                                        'schedule_id' => $schedule->id ?? null,
                                        'semester_id' => $semesterModel->id,
                                        'school_year_id' => $schoolYearModel->id,
                                        'year_level_id' => $yearLevelModel->id ?? null,
                                        'prospectus_id' => $prospectusModel->id ?? null,
                                    ]);
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
