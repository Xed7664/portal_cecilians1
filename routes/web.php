<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Providers\RouteServiceProvider;

use App\Http\Controllers\{ 
    SearchController, ScheduleController, EventsController, CalendarController, 
    AccountSettingsController, AjaxController, AuthController, PostController, 
    ProfileController, UserController, StudentController, TeacherController, 
    ProgramHeadController, ProgramHeadPreEnrollmentController, AdminController
};

use App\Http\Controllers\Admin\{
    UserController as AdminUserController, 
    StudentController as AdminStudentController, 
    EmployeeController as AdminEmployeeController, 
    Analytics\LoginController as AdminLoginController
};

use App\Http\Controllers\Subjects\{ MiniController, SubjectController };
use App\Http\Controllers\{
    GradeController, ProspectusController, EnrollmentController, 
    WelcomeController, AdmissionController, PreEnrollmentController, 
    StudentPheadController, SubjectsPheadController, SchedulesPheadController, 
    NotificationSendController, Socialite\GoogleController, 
    Auth\VerificationController
};

use App\Livewire\Posts\SingleFull;
use App\Http\Controllers\{
    ChatBotController, SimpleChatBotController, ChatGPTController
};


// First Page Route
Route::get('/welcome', [WelcomeController::class, 'index'])->name('welcome');

// Default route to redirect to the welcome page
Route::get('/', function () {
    return redirect()->route('welcome');
});

// Authentication routes
Route::get('/auth/login', function () {
    // Just show the login view
    return view('auths.login');
})->name('login');

Route::middleware('auth')->group(function() {
    Route::get('/pre-enrollment', [PreEnrollmentController::class, 'showForm'])->name('pre-enrollment.form');
    // routes/web.php
Route::get('/pre-enrollment/preview', [PreEnrollmentController::class, 'preview'])->name('pre-enrollment.preview');
Route::post('/pre-enrollment/submit', [PreEnrollmentController::class, 'submitPreEnrollment'])->name('pre-enrollment.submit');


    Route::get('/pre-enrollment/dashboard', [PreEnrollmentController::class, 'previewForm'])->name('pre-enrollment.dashboard');
});
// Route in web.php
Route::patch('/sections/{section}/toggle-lock', [PreEnrollmentController::class, 'toggleLock'])->name('sections.toggleLock');

Route::get('/get-schedules', [PreEnrollmentController::class, 'getSchedules'])->name('get-schedules');
Route::get('/enrollment-status', [PreEnrollmentController::class, 'showStatus'])->name('enrollment.enrollment-status');
// web.php
Route::get('admission', [AdmissionController::class, 'showAdmissionForm'])->name('admission.form');
Route::post('admission', [AdmissionController::class, 'submitAdmission'])->name('admission.submit');
Route::get('admission-status', [AdmissionController::class, 'showStatusForm'])->name('admission.status.form');
Route::post('admission-status', [AdmissionController::class, 'checkStatus'])->name('admission.status.check');
Route::get('admission/tracker', [AdmissionController::class, 'showTracker'])->name('admission.tracker');
Route::post('admission/tracker', [AdmissionController::class, 'trackAdmission']);

Route::get('/chat', [ChatBotController::class, 'index'])->name('chat.index'); // For displaying the chatbot interface
Route::post('/chat', [ChatBotController::class, 'handle'])->name('chat.handle'); // For handling the POST requests
Route::post('/chat', [SimpleChatBotController::class, 'handle'])->name('chat.handle');
Route::post('/chatgpt', [ChatGPTController::class, 'handle'])->name('chatgpt.handle');
Route::post('/chatbot/message', [ChatbotController::class, 'message']);
// Role-specific home routes
Route::middleware(['auth'])->group(function () {
   
    // Program Head Prospectus 
    Route::prefix('phead')->name('phead.')->group(function () {

        // Pre-Enrollment Routes
        Route::get('/preenrollment', [PreEnrollmentController::class, 'preenrollmentphead'])->name('preenrollment');
        Route::post('/lock-section/{sectionId}', [PreEnrollmentController::class, 'lockSection'])->name('lockSection');
        Route::post('/unlock-section/{sectionId}', [PreEnrollmentController::class, 'unlockSection'])->name('unlockSection');
 


            // List student applications for pre-enrollment
            Route::get('/pre-enrollment/applications', [ProgramHeadPreEnrollmentController::class, 'listApplications'])->name('pre-enrollment.applications');
            
            // Review a specific student’s application
            Route::get('/pre-enrollment/application/{studentId}', [ProgramHeadPreEnrollmentController::class, 'reviewApplication'])->name('pre-enrollment.review');

             // Dashboard routes for each role
    Route::get('/student/dashboard', [StudentController::class, 'index'])->name('student.dashboard');
   
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

        
        // Program Head Dashboard route
    Route::get('/dashboard', [ProgramHeadController::class, 'dashboard'])->name('dashboard');




    // Prospectus Management routes
    Route::get('/program-head/prospectus', [ProgramHeadController::class, 'index'])->name('prospectus');
    Route::post('/prospectus', [ProgramHeadController::class, 'store'])->name('prospectus.store');
    Route::patch('/prospectus/{id}', [ProgramHeadController::class, 'update'])->name('prospectus.update');
    Route::patch('/prospectus/{id}/archive', [ProgramHeadController::class, 'archive'])->name('prospectus.archive');
    Route::get('/prospectus/archived', [ProgramHeadController::class, 'archivedIndex'])->name('prospectus.archived');
    Route::patch('/prospectus/{id}/restore', [ProgramHeadController::class, 'restore'])->name('prospectus.restore');
   
    

    // Subjects management routes
    Route::get('/subjects', [SubjectsPheadController::class, 'index'])->name('subjects.index');
    Route::post('/subjects', [SubjectsPheadController::class, 'store'])->name('subjects.store');
    Route::put('/subjects/{subject}', [SubjectsPheadController::class, 'update'])->name('subjects.update');
    Route::patch('/subjects/{subject}/archive', [SubjectsPheadController::class, 'archive'])->name('subjects.archive');
    Route::get('/subjects/archived', [SubjectsPheadController::class, 'archivedSubjects'])->name('archived-subjects');
    Route::patch('/subjects/{subject}/restore', [SubjectsPheadController::class, 'restore'])->name('restore');
    Route::delete('/subjects/{subject}', [SubjectsPheadController::class, 'delete'])->name('delete');
    
    // Student management routes
    Route::get('/students', [StudentPheadController::class, 'index'])->name('students.index');
    Route::get('/students/{id}', [StudentPheadController::class, 'view'])->name('students.view');
    Route::get('/students/{id}/grades', [StudentPheadController::class, 'grades'])->name('students.grades');
    Route::post('/students/check', [StudentPheadController::class, 'check'])->name('students.check');
    Route::post('/students/import', [StudentPheadController::class, 'import'])->name('students.import');

    // Prospectus-Grade management
    Route::get('/students/{id}/prospectus', [StudentPheadController::class, 'viewProspectus'])->name('students.prospectus');

    // Year and Section route
    Route::get('/yearandsection', [StudentPheadController::class, 'yearAndSection'])->name('yearandsection');

    // Route for listing students in a section
    Route::get('/sections/{section}/{yearLevel}/students', [StudentPheadController::class, 'studentsBySection'])->name('section.students');


    // route for schedules
    Route::get('/schedules', [SchedulesPheadController::class, 'index'])->name('schedules.index');
    Route::post('/schedules', [SchedulesPheadController::class, 'store'])->name('schedules.store');


    Route::post('/schedules/update/{schedule}', [SchedulesPheadController::class, 'update'])->name('schedules.update');



   

    Route::delete('/schedules/{id}', [SchedulesPheadController::class, 'destroy'])->name('schedules.destroy');
        

    // Filter Year and Section for Schedules
    Route::get('/get-sections/{yearLevelId}', [SchedulesPheadController::class, 'getSections'])->name('getSections');
    
    


});


});
Route::get('/auth/registration', [AuthController::class, 'registration'])->name('registration');

Route::get('/auth/verify', [AuthController::class, 'verify'])->name('verify');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $user = $request->user();
    
    if ($user->isVerified()) {
        return redirect()->route('newsfeed'); // Redirect to the newsfeed route
    }

    // Check if the user is not already banned
    if ($user->status !== 'banned' && $user->type !== 'member') {
        // Update the user's type to "member"
        $user->update(['status' => 'member']);

        // Attach permissions to the user upon becoming a member
        $permissions = ['create_post', 'create_comment', 'react_to_post', 'delete_post'];
        $user->givePermission($permissions); // Assuming you have a method to attach permissions.
    }

    $request->fulfill();
 
    return view('auths.verified')->with('success', 'Your email has been verified. You are now a member.');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Login with Google
Route::get('/auth/login/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/auth/login/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Menu
    Route::get('/menu', function () {
        return view('mobile.menu');
    })->name('menu');

    // Subject
    Route::get('/course/mini/{subject_id}', [MiniController::class, 'show'])->name('subject.mini.show');

    Route::get('/course/{subject_id}', [SubjectController::class, 'showDetails'])->name('subject.details');
    Route::get('/course/{subject_id}/people', [SubjectController::class, 'showPeople'])->name('subject.people');

    // Newsfeed and homepage
    Route::get('/', [PostController::class, 'index'])->name('newsfeed');

    // Schedule
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');

    // Events
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/get-events', [CalendarController::class, 'getEvents']);

    Route::get('/events/{id}', [EventsController::class, 'show'])->name('events.show');

            //Grades
           // Program Head Routes
            Route::get('/program-head/grades', [GradeController::class, 'programHeadIndex'])->name('program-head.grades.index');
            Route::get('/program-head/grades/{section}', [GradeController::class, 'programHeadShow'])->name('program-head.grades.show');

            // Teacher Routes
            Route::get('/teacher/grades', [GradeController::class, 'teacherIndex'])->name('teacher.grades.index');
            Route::get('/teacher/subject/grades/{subjectEnrolledId}', [GradeController::class, 'teacherShow'])->name('teacher.subject.grades.show');

            
            // Route::post('/teacher/grades/{section}', [GradeController::class, 'update'])->name('teacher.grades.update');
            Route::get('/teacher/grades/filter', [GradeController::class, 'filter'])->name('teacher.grades.filter');
            Route::get('/teacher/subject/{subjectEnrolledId}/grades', [GradeController::class, 'teacherShow'])->name('teacher.subject.grades');
            Route::post('/teacher/grades/{subjectEnrolled}/store-or-update', [GradeController::class, 'storeOrUpdateGrades'])->name('teacher.grades.storeOrUpdate');


            Route::get('grades/template/{subjectEnrolled}', [GradeController::class, 'downloadTemplate'])->name('teacher.grades.template');
         
     
Route::post('/teacher/grades/mapping', [GradeController::class, 'showMappingForm'])->name('teacher.grades.mapping');
Route::post('/teacher/grades/mapHeaders', [GradeController::class, 'mapHeaders'])->name('teacher.grades.mapHeaders');
Route::post('/teacher/grades/import', [GradeController::class, 'importGrades'])->name('teacher.grades.import');
// routes/web.php

Route::post('/send-grades-notification', [GradeController::class, 'sendGradesNotification']);

     // Route to display the file upload form
     Route::get('teacher/grades/upload', [GradesController::class, 'showUploadForm'])->name('grades.upload.form');
     Route::post('teacher/grades/upload', [GradesController::class, 'uploadFile'])->name('grades.upload');
     Route::post('teacher/grades/map', [GradesController::class, 'mapColumns'])->name('grades.map');
     Route::put('teacher/grades/{id}', [GradeController::class, 'update'])->name('grades.update');
   
     Route::get('/api/semesters/{schoolYearId}', [SemesterController::class, 'getSemestersBySchoolYear'])->name('api.semesters.bySchoolYear');


// Route to get subjects for the selected school year and semester
Route::get('/fetch-subjects', [GradeController::class, 'fetchSubjects'])->name('fetch.subjects');

// Route::get('/teacher/departments', [GradeController::class, 'fetchDepartments'])->name('fetch.teacher.departments');
Route::get('/fetch-teacher-departments', [GradeControllerName::class, 'fetchTeacherDepartments'])
     ->name('fetch.teacher.departments');

     Route::post('/teacher/grades/submit-student/{subjectEnrolled}', [GradeController::class, 'submitStudent'])->name('teacher.grades.submitStudent');

 Route::post('/grades/{subjectEnrolledId}/autosave', [GradeController::class, 'autoSaveGrade'])->name('grades.autoSave');


Route::post('/teacher/grades/submit-all-grades/{subjectId}', [GradeController::class, 'submitAllGrades'])->name('teacher.grades.submitAllGrades');

Route::post('/teacher/grades/{subjectEnrolled}/mark-ready', [GradeController::class, 'markAsReady'])->name('teacher.grades.markReady');
Route::get('/teacher/schedule', [ScheduleController::class, 'teacherSchedule'])->name('teacher.schedule');

// Teacher Dashboard Route
Route::get('/teacher/dashboard', [TeacherController::class, 'dashboard'])->name('teacher.dashboard');
// fetch students
Route::get('/teacher/fetch-enrolled-students', [TeacherController::class, 'fetchEnrolledStudents'])->name('teacher.fetchEnrolledStudents');

Route::get('/teacher/students/{student}/grades', [TeacherController::class, 'viewGrades'])
     ->name('teacher.students.grades');
     Route::get('/students/data', [TeacherController::class, 'fetchEnrolledStudents'])->name('students.data');



             // Route for Grades for students
    Route::get('/student/grades', [GradeController::class, 'studentIndex'])->name('student.grades.index');
    Route::get('/student/grades/{studentId}', [GradeController::class, 'showAllGradesForStudent'])->name('student.grades.all');
    Route::get('/student/grades/request-review/{studentId}', [GradeController::class, 'requestReview'])->name('student.grades.requestReview');
    Route::post('/student/grades/request-review/{gradeId}', [GradeController::class, 'submitReviewRequest'])->name('student.grades.submitReviewRequest');
    Route::get('/student/grades/section/{section}', [GradeController::class, 'studentShow'])->name('student.grades.show');

    //Filter Table
    Route::get('/student/grades/{studentId}/filter', [GradeController::class, 'filterGrades'])->name('student.grades.filter');

       //Prospectus
       Route::get('/prospectus', [ProspectusController::class, 'index'])->name('prospectus.index');
       // Debug page
       Route::get('/debug', function () {
           return view('debug');
       })->name('debug');
   
     //enrollment
     Route::middleware(['auth', 'student'])->group(function () {
        Route::get('/enrollment', [EnrollmentController::class, 'create'])->name('enrollment.create');
        Route::post('/enrollment', [EnrollmentController::class, 'store'])->name('enrollment.store');
        
        // For fetching section schedules
        Route::get('/get-section-schedule', [EnrollmentController::class, 'getSectionSchedule'])->name('get-section-schedule');
        
        // For fetching student details (add route name)
        Route::get('/get-student-details', [EnrollmentController::class, 'getStudentDetails'])->name('student.details'); 
    });
    
    // Profile routes
    Route::get('/profile/{username}', [ProfileController::class, 'showProfile'])->name('profile.show');
    Route::get('/profile/{username}/trophies', [ProfileController::class, 'showTrophy'])->name('profile.trophy');
    Route::get('/profile/{username}/lessons', [ProfileController::class, 'showProfile'])->name('profile.lesson');
    Route::get('/profile/{username}/organizations', [ProfileController::class, 'showProfile'])->name('profile.organization');

    // Follow/Unfollow user
    Route::post('/user/follow/{user}', [UserController::class, 'followOrUnfollow'])->name('user.follow');

    // Individual post page
    Route::get('/profile/{username}/posts/{id}', [PostController::class, 'show'])->name('posts.show');

    // Settings
    Route::get('/settings/{page}', [AccountSettingsController::class, 'show'])->name('account.show');

    // AJAX handling
    Route::post('/ajax/event', [AjaxController::class, 'handle'])->name('ajax.handle');

    // Scanning
    Route::view('/scan', 'scan')->name('scan.page');

    // Searching
    Route::get('/search', [SearchController::class, 'search']);



    // Test route (for testing purposes)
    Route::get('/test', function () {
        return view('test');
    })->name('test');

    // Notification routes
    Route::post('/store-token', [NotificationSendController::class, 'updateDeviceToken'])->name('store.token');
    Route::post('/send-web-notification', [NotificationSendController::class, 'sendNotification'])->name('send.web-notification');

    // Logout route
    Route::get('/auth/logout', [AuthController::class, 'logout'])->name('logout');

    // Hashtag route (incomplete)
    Route::get('/hashtag/{tag}', function () {
        return 'Not yet finished';
    })->name('hashtag');

    // Livewire test route
    Route::get('/livewire', function () {
        return view('livewire');
    });


   
    // Admin routes
    Route::prefix('admin/user')->group(function () {
        Route::get('/registered', [AdminUserController::class, 'index'])->name('admin.users.registered');
        Route::get('/student', [AdminStudentController::class, 'index'])->name('admin.users.student');
        Route::get('/employee', [AdminEmployeeController::class, 'index'])->name('admin.users.employee');

        // Admin student upload and check routes
        Route::post('/student/check', [AdminStudentController::class, 'checkFile'])->name('admin.users.student.check');
        Route::post('/student/upload', [AdminStudentController::class, 'upload'])->name('admin.users.student.upload');

        //Edit Student Info and Grades- Admin Routes
        Route::prefix('admin/users/student')->name('admin.users.student.')->group(function () {
            Route::get('/', [StudentController::class, 'index'])->name('index');
            Route::get('/{id}/edit', [StudentController::class, 'edit'])->name('edit');
            Route::put('/{id}', [StudentController::class, 'update'])->name('update');
            Route::get('/{id}/grades', [StudentController::class, 'showGrades'])->name('grades');
            Route::get('/{id}/grades-data', [StudentController::class, 'getGradesData'])->name('grades-data');
            
        });
        

        // Admin analytics

        Route::get('/analytics/logins', [AdminLoginController::class, 'index'])->name('admin.analytics.login');
        Route::get('admin/admission/{id}/review', [AdmissionController::class, 'reviewAdmission'])->name('admin.admission.review');
Route::post('admin/admission/{id}/approve', [AdmissionController::class, 'approveAdmission'])->name('admin.admission.approve');
Route::post('admin/admissions/{id}/reject', [AdmissionController::class, 'rejectAdmission'])->name('admin.admissions.reject');
// In web.php (new route)
Route::get('admin/admissions', [AdmissionController::class, 'index'])->name('admin.admissions.index');
Route::post('/admin/admissions/store', [AdmissionController::class, 'storeAdmissionSettings'])->name('admin.admissions.store'); // Store settings
Route::post('/admin/admissions/toggle/{semesterId}/{schoolYearId}', [AdmissionController::class, 'toggleAdmissionStatus'])->name('admin.admissions.toggle'); // Toggle status
Route::view('admission/closed', 'admission.closed')->name('admission.closed');
// Show the pre-enrollment settings page
Route::get('/pre-enrollment/settings', [PreEnrollmentController::class, 'showSettings'])->name('admin.pre-enrollment.settings');
            
// Update pre-enrollment settings
Route::post('/pre-enrollment/store', [PreEnrollmentController::class, 'storeSettings'])->name('admin.pre-enrollment.storeSettings');
// Add this inside the routes group for admin or pre-enrollment settings

    });
// Admin routes
Route::prefix('admin/pre-enrollment')->group(function () {
  // Admin routes
Route::post('/toggle/{semesterId}/{schoolYearId}', [PreEnrollmentController::class, 'togglePreEnrollmentStatus'])
->name('admin.pre-enrollment.toggle');

});

    //not yet applied
    // Route::get('admin',function(){
    //     return view('admin');
    // })->name('admin')->middleware('admin');
    
    // Route::get('proghead',function(){
    //     return view('proghead');
    // })->name('proghead')->middleware('proghead');
    
    // Route::get('teacher',function(){
    //     return view('teacher');
    // })->name('teacher')->middleware('teacher');
    
 

});