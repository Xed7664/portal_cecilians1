<?php

use App\Http\Controllers\{SearchController, ScheduleController, EventsController, CalendarController, AccountSettingsController, AjaxController, AuthController, PostController, ProfileController, UserController};
use App\Livewire\Posts\{SingleFull};
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\EmployeeController as AdminEmployeeController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\Analytics\LoginController as AdminLoginController;
use App\Http\Controllers\Socialite\GoogleController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Subjects\MiniController;
use App\Http\Controllers\Subjects\SubjectController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ProspectusController;
use App\Http\Controllers\{StudentController, TeacherController, ProgramHeadController, AdminController};
use App\Http\Controllers\WelcomeController;
use App\Providers\RouteServiceProvider;

// First Page Route
Route::get('/welcome', [WelcomeController::class, 'index'])->name('welcome');

// Default route to redirect to the welcome page
Route::get('/', function () {
    return redirect()->route('welcome');
});

// Authentication routes
Route::get('/auth/login', function () {
    // Check if the user is already authenticated
    if (Auth::check()) {
        return redirect()->route('newsfeed'); // or any other authenticated route
    }
    return view('auths.login');
})->name('login');



// Route::get('/program-head/dashboard', function () {
//     return view('program-head.dashboard');
// })->name('program-head.dashboard');

// Route::get('/student/dashboard', function () {
//     return view('student.dashboard');
// })->name('student.dashboard');

// Route::get('/teacher/dashboard', function () {
//     return view('teacher.dashboard');
// })->name('teacher.dashboard');

// Route::get('/admin/dashboard', function () {
//     return view('admin.dashboard');
// })->name('admin.dashboard');



// Route::get('/admin/dashboard', function () {
//     return view('dashboard');
// })->name('admin')->middleware('admin');

// Route::get('/program-head/dashboard', function () {
//     return view('dashboard');
// })->name('program_head')->middleware('program_head');

// Route::get('/teacher/dashboard', function () {
//     return view('dashboard');
// })->name('teacher')->middleware('teacher');




// Role-specific home routes
Route::middleware(['auth'])->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'index'])->name('student.dashboard');
    Route::get('/teacher/dashboard', [TeacherController::class, 'index'])->name('teacher.dashboard');
    Route::get('/program-head/dashboard', [ProgramHeadController::class, 'index'])->name('program_head.dashboard');
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
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
            Route::get('/teacher/grades/{section}', [GradeController::class, 'teacherShow'])->name('teacher.grades.show');
            // Route::post('/teacher/grades/{section}', [GradeController::class, 'update'])->name('teacher.grades.update');
            Route::get('/teacher/grades/filter', [GradeController::class, 'filter'])->name('teacher.grades.filter');
            Route::get('/teacher/subject/{subjectEnrolledId}/grades', [GradeController::class, 'teacherShow'])->name('teacher.subject.grades');
            Route::post('/teacher/grades/{subjectEnrolled}/store-or-update', [GradeController::class, 'storeOrUpdateGrades'])->name('teacher.grades.storeOrUpdate');
            Route::get('grades/template/{subjectEnrolled}', [GradeController::class, 'downloadTemplate'])->name('teacher.grades.template');
         
     
Route::post('/teacher/grades/mapping', [GradeController::class, 'showMappingForm'])->name('teacher.grades.mapping');
Route::post('/teacher/grades/mapHeaders', [GradeController::class, 'mapHeaders'])->name('teacher.grades.mapHeaders');
Route::post('/teacher/grades/import', [GradeController::class, 'importGrades'])->name('teacher.grades.import');
// routes/web.php

Route::post('/send-grades-notification', [GradesController::class, 'sendGradesNotification']);

     // Route to display the file upload form
     Route::get('teacher/grades/upload', [GradesController::class, 'showUploadForm'])->name('grades.upload.form');
     Route::post('teacher/grades/upload', [GradesController::class, 'uploadFile'])->name('grades.upload');
     Route::post('teacher/grades/map', [GradesController::class, 'mapColumns'])->name('grades.map');
     Route::put('teacher/grades/{id}', [GradeController::class, 'update'])->name('grades.update');
            // Student Routes
            // Route::get('/student/grades', [GradeController::class, 'studentIndex'])->name('student.grades.index');
            // Route::get('/student/grades/{semester}', [GradeController::class, 'studentShow'])->name('student.grades.show');
            // Route::get('/student/grades/ajax/{semester}', [GradeController::class, 'getGradesBySemester'])->name('student.grades.ajax');
            // Route for Grades for students
    Route::get('/student/grades', [GradeController::class, 'studentIndex'])->name('student.grades.index');
    Route::get('/student/grades/{studentId}', [GradeController::class, 'showAllGradesForStudent'])->name('student.grades.all');
    Route::get('/student/grades/request-review/{studentId}', [GradeController::class, 'requestReview'])->name('student.grades.requestReview');
    Route::post('/student/grades/request-review/{gradeId}', [GradeController::class, 'submitReviewRequest'])->name('student.grades.submitReviewRequest');
    Route::get('/student/grades/section/{section}', [GradeController::class, 'studentShow'])->name('student.grades.show');

     //Prospectus
     Route::get('/prospectus', [ProspectusController::class, 'index'])->name('prospectus.index');
    // Debug page
    Route::get('/debug', function () {
        return view('debug');
    })->name('debug');

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

        // Admin analytics

        Route::get('/analytics/logins', [AdminLoginController::class, 'index'])->name('admin.analytics.login');
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
