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
// Authentication routes
Route::get('/auth/login', function () {
    return view('auths.login');
})->name('login');

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
    Route::get('/grades', [GradeController::class, 'index'])->name('grade.index');

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
});
