<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureVerified
{
    /**
     * Handle an incoming request.
     * 
     * This middleware ensures user has completed either email OR phone verification
     * - If user chose email verification, they must have email_verified_at
     * - If user chose WhatsApp verification, they must have phone_verified_at
     * - User is considered verified if they have either one
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Check if user has completed any form of verification
        $hasPhoneVerification = !is_null($user->phone_verified_at);
        $hasEmailVerification = !is_null($user->email_verified_at);
        
        // User must have at least one verification method completed
        if (!$hasPhoneVerification && !$hasEmailVerification) {
            // Redirect to appropriate verification page based on user's choice
            // Since we don't track which method they originally chose, 
            // we'll default to email verification notice
            return redirect()->route('verification.notice')
                ->with('error', 'Please complete your account verification to continue.');
        }

        return $next($request);
    }
}
