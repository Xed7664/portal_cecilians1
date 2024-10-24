<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPreEnrollment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
    
        if ($user && $user->type == 'student') {
            $student = $user->student;
    
            // Redirect if pre-enrollment is incomplete
            if ($student && $student->pre_enrollment_completed == 0) {
                return redirect()->route('pre-enrollment.form');
            }
        }
    
        return $next($request);
    }
    
}
