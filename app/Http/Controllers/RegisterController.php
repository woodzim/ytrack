<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Display the registration form for perusahaan.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('pages.register');
    }

    /**
     * Handle registration request for perusahaan.
     */
    public function register(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'nama_perusahaan' => ['required', 'string', 'max:255'],
            'alamat_perusahaan' => ['required', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama pengguna wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sesuai',
            'nama_perusahaan.required' => 'Nama perusahaan wajib diisi',
            'alamat_perusahaan.required' => 'Alamat perusahaan wajib diisi',
        ]);

        // Create perusahaan
        $perusahaan = Perusahaan::create([
            'nama' => $validated['nama_perusahaan'],
            'alamat' => $validated['alamat_perusahaan'],
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'id_perusahaan' => $perusahaan->id,
        ]);

        // Assign perusahaan role to user
        $user->assignRole('perusahaan');

        // Authenticate the user
        Auth::login($user);

        return redirect()
            ->route('perusahaan.dashboard.index')
            ->with('success', 'Pendaftaran berhasil! Selamat datang di aplikasi GPS Tracking.');
    }
}
