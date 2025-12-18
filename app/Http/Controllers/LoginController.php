<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Display the login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('pages.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        // Validate the credentials
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password harus diisi',
        ]);

        // Add remember me functionality
        $remember = $request->has('remember') ? true : false;

        // Attempt to authenticate
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()
                ->intended(route('sesi-alat.index'))
                ->with('success', 'Login berhasil!');
        }

        // Authentication failed
        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'Email atau password tidak valid.',
            ]);
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Logout berhasil!');
    }
}
