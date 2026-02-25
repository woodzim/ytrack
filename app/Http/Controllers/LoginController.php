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
        // Cek jika user sudah login
        if (Auth::check()) {
            // Jika role perusahaan -> ke dashboard.index
            if (Auth::user()->hasRole('perusahaan')) {
                return redirect()->route('perusahaan.dashboard.index');
            }

            // Jika admin -> ke dashboard (admin)
            return redirect()->route('dashboard');
        }

        return view('pages.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        // Validate credentials
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password harus diisi',
        ]);

        $remember = $request->has('remember');

        // Attempt login
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Cek verifikasi email
            if (!Auth::user()->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            // LOGIKA REDIRECT
            // Prioritaskan Admin
            if (Auth::user()->hasRole('admin')) {
                return redirect()
                    ->intended(route('dashboard'))
                    ->with('success', 'Login berhasil!');
            }

            if (Auth::user()->hasRole('perusahaan')) {
                if (!Auth::user()->isValidated()) {
                    Auth::logout();
                    return back()
                        ->withInput($request->only('email'))
                        ->withErrors([
                            'email' => 'Akun Anda belum diverifikasi oleh admin. Silakan tunggu atau hubungi admin.',
                        ]);
                }

                // Redirect khusus Perusahaan
                return redirect()
                    ->intended(route('perusahaan.dashboard.index')) // Sesuai request Anda
                    ->with('success', 'Login berhasil!');
            }

            // Fallback (jika ada role lain atau default)
            return redirect()
                ->intended(route('dashboard'))
                ->with('success', 'Login berhasil!');
        }

        // Login gagal
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
            return redirect()->route('login');
        }
}
