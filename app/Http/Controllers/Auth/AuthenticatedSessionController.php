<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Manually attempt to log in the user
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
        
            // Log the authenticated user for debugging
            \Log::info('Authenticated user: ' . auth()->user()->email);
            \Log::info('Authenticated user role: ' . auth()->user()->role);
    
            // Check role after successful authentication
            switch (auth()->user()->role) {
                case 'penjual':
                    return redirect()->intended('/penjual/dashboard');
                case 'pembeli':
                    return redirect()->intended('/customer/dashboard');
                default:
                    return redirect()->intended('/');
            }
        }
    
        // If authentication fails, return to login with an error message
        return redirect()->route('login')->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
    
    

    

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Log the user out and invalidate the session
        Auth::guard('web')->logout();

        // Invalidate the session and regenerate CSRF token for security
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to the home page
        return redirect('/');
    }
}
