<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class StudentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check if the user is authenticated and has the type 'student'
        if (Auth::check() && Auth::user()->type === 'student') {
            return $next($request);
        }

        // If not a student, redirect or abort
        return redirect()->route('newsfeed')->with('error', 'Unauthorized access.');
    }
}

