<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        // Check if the user is authenticated
        if (!Auth::check()) {

            session(['url.intended' => $request->fullUrl()]);
            
            // Redirect to the login page with a message (optional)
            return redirect()->route('login')->with('error', 'Please log in to access the page.');
        }

        // User is authenticated, continue to the next middleware or route
        return $next($request);
    }
}
