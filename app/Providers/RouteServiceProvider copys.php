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
     * The default home path after login.
     */
    // public const HOME = '/home';

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
    public static function getHomeRoute($user)
    {
        // Check if user is a student and hasn't completed pre-enrollment
        if ($user->type === 'student' && !$user->pre_enrollment_completed) {
            return '/pre-enrollment/form';
        }

        // Role-based redirection
        switch ($user->type) {
            case 'admin':
                return '/admin/dashboard';
            case 'teacher':
                return '/teacher/dashboard';
            case 'program_head':
                return '/program-head/dashboard';
            case 'student':
                return '/student/dashboard';
            default:
                // return self::HOME;
        }
    }
}
