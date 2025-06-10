<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');  // If not authenticated, redirect to login
        }

        // Log the user's role for debugging
        \Log::info('User role in middleware: ' . $request->user()->role);

        // Check if the user has the required role
        if (!in_array($request->user()->role, $roles)) {
            return redirect()->route('login');  // Redirect if user does not have the correct role
        }

        return $next($request);  // Proceed to the next request if role matches
    }
}
