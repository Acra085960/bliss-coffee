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
    $request->authenticate();

    $request->session()->regenerate();

    // Debug: Periksa role pengguna
    dd(auth()->user()->role);  // Pastikan role sesuai (penjual, manajer, owner, dll.)

    switch (auth()->user()->role) {
        case 'pembeli':
            return redirect()->intended('/pembeli/dashboard');
        case 'penjual':
            return redirect()->intended('/penjual/dashboard');
        case 'manajer':
            return redirect()->intended('/manajer/dashboard');
        case 'owner':
            return redirect()->intended('/owner/dashboard');
    }

    return redirect()->intended('/');
}



    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
