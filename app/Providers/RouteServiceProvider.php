<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's home route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Get the path for the authenticated user's home based on their type.
     *
     * @param \App\Models\User $user
     * @return string
     */
    public static function redirectToHome(\App\Models\User $user)
    {
        switch ($user->role) {
            case 'student':
                return route('student.dashboard');
            case 'teacher':
                return route('teacher.dashboard');
            case 'program_head':
                return route('program_head.dashboard');
            case 'admin':
                return route('admin.dashboard');
            default:
                return route('newsfeed');
        }
    }
}
