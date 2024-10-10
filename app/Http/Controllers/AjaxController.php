<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Reply;
use App\Models\Hashtag;
use App\Models\Semester;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\ReactionController;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class AjaxController extends Controller
{
    public function handle(Request $request)
    {
        if ($request->has('op')) {
            $operation = $request->input('op');
            $requestData = $request->input('data'); 

            switch ($operation) {
                case 'subject':
                    $userId = $requestData['id']; 
                    $user = User::find($userId);
                    return app(UserController::class)->followOrUnfollow($user);

                case 'follow':
                    $userId = $requestData['id']; 
                    $user = User::find($userId);
                    return app(UserController::class)->followOrUnfollow($user);

                case 'switch_year':
                    $schoolYearId = $requestData['sy_id']; // Extract the selected school year ID
                    $schoolYear = SchoolYear::find($schoolYearId);

                    if ($schoolYear) {
                        // Update the session with the selected school year ID
                        Session::put('current_school_year_id', $schoolYearId);

                        // Set the current semester selection to 0 (or null if preferred)
                        Session::put('current_semester_id', 0);
                        
                        return response()->json(['result' => true, 'message' => 'Switched school year successfully']);
                    } else {
                        return response()->json(['result' => false, 'message' => 'School year not found']);
                    }

                case 'switch_semester':
                    $semesterId = $requestData['sem_id']; // Extract the selected semester ID
                    $semester = Semester::find($semesterId);

                    if ($semester) {
                        // Update the session with the selected semester ID
                        Session::put('current_semester_id', $semesterId);
                        
                        return response()->json(['result' => true, 'message' => 'Switched semester successully']);
                    } else {
                        return response()->json(['result' => false, 'message' => 'Semester not found']);
                    }

                case 'switch_theme':
                    // Toggle the theme and store the current theme in a session variable
                    $currentTheme = session('theme', 'light');
                    $newTheme = $currentTheme === 'light' ? 'dark' : 'light';
                    session(['theme' => $newTheme]);

                    return response()->json([
                        'result' => true,
                        'message' => 'Theme switched successfully',
                        'code' => $newTheme === 'dark' ? 1 : 0,
                    ]);

                case 'switch_panel':
                    // Toggle the theme and store the current theme in a session variable
                    $currentPanel = session('panel', 'user');
                    $newPanel = $currentPanel === 'user' ? 'admin' : 'user';
                    session(['panel' => $newPanel]);

                    return response()->json([
                        'result' => true,
                        'message' => 'Switched to ',
                        'code' => $newPanel === 'user' ? 1 : 0,
                    ]);

                    case 'admin_student_import_check':
                        // Check if files were uploaded
                        if (isset($_FILES['data']['file'])) {
                            $file = $_FILES['data']['file'];
                        
                            // Validate the file type
                            if ($file['type'] === 'application/vnd.ms-excel' || $file['type'] === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
                                // Process the uploaded file
                                $filePath = $file['tmp_name'];
                        
                                // Load the file using PhpSpreadsheet
                                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
                                $sheet = $spreadsheet->getActiveSheet();
                        
                                // Check if the required headers exist
                                $requiredHeaders = [
                                    'Student ID',
                                    'FullName',
                                    // Add other required headers here
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
                        
                                    foreach ($dataSheet->getRowIterator(2) as $row) {
                                        $rowData = [];
                                        $error = false; // Flag to track errors in the current row
                                        $studentName = ''; // Variable to store the name of the student causing the error
                        
                                        foreach ($row->getCellIterator() as $cell) {
                                            $columnIndex = $cell->getColumn();
                                            $cellValue = $cell->getValue();
                        
                                            // Convert birthdate column to the desired format if needed (use your existing code for this)
                        
                                            $rowData[] = $cellValue;
                                        }
                        
                                        // Process the $rowData, you can save it to the database or perform any other operations
                                        $data[] = $rowData;
                                    }
                        
                                    // Now you have the data from the XLS file, you can proceed to use it as needed
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

                        case 'upload':
                            if ($request->has('data.profile_picture')) {
                                $base64Image = $request->input('data.profile_picture');
                                
                                // Extract the base64 data and remove the data URI prefix
                                $base64Data = substr($base64Image, strpos($base64Image, ',') + 1);
                                $data = base64_decode($base64Data);
                        
                                // Define the destination directory and file name
                                $destinationPath = storage_path('app/public/img/profile'); // Using storage path
                                $profilePictureName = time() . '_' . uniqid() . '.jpg';
                        
                                // Ensure the directory exists
                                if (!file_exists($destinationPath)) {
                                    mkdir($destinationPath, 0755, true); // Create the directory if it doesn't exist
                                }
                        
                                // Save the decoded data as a file
                                if (file_put_contents($destinationPath . '/' . $profilePictureName, $data)) {
                                    // Update the user's profile picture path in your database
                                    $user = Auth::user();
                                    $user->avatar = $profilePictureName;
                                    $user->save();
                        
                                    // Construct the URL to the new profile picture
                                $profilePictureUrl = url('img/profile/' . $profilePictureName);

                                    return response()->json([
                                        'result' => true,
                                        'message' => 'Image uploaded successfully',
                                        'profile_picture_url' => $profilePictureUrl,
                                    ]);
                                } else {
                                    return response()->json(['result' => false, 'message' => 'Failed to save the image']);
                                }
                            } else {
                                return response()->json(['result' => false, 'message' => 'No image data provided']);
                            }
                        

                    case 'scan':
                        $qrData = $request->input('qrcode'); // Extract the QR code data
                    
                        // Check if the QR code data matches the SchoolID of the authenticated user
                        $user = auth()->user(); // Assuming the user is authenticated
                    
                        if ($user && $user->getSchoolID() === $qrData) {
                            // QR code data matches the SchoolID, return the user_id
                            return response()->json(['result' => true, 'user_id' => $user->username]);
                        }
                    
                        // QR code data doesn't match, return an error message
                        return response()->json(['result' => false, 'message' => 'Invalid QR code data']);
                        

                default:
                    return response()->json(['result' => false, 'message' => 'Invalid operation']);
            }
        } else {
            return response()->json(['result' => false, 'message' => 'Operation not specified']);
        }
    }
}
