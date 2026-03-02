<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Perusahaan;
use App\Mail\NewCompanyRegistration;
use App\Mail\AdminNewRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;

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
            'is_validated' => false,
        ]);

        // Assign perusahaan role to user
        $user->assignRole('perusahaan');

        // Trigger email verification
        event(new Registered($user));

        // Send email to perusahaan
        try {
            Mail::to($user->email)->send(new NewCompanyRegistration($user));
        } catch (\Exception $e) {
            Log::error('Failed to send registration email to company: ' . $e->getMessage());
        }

        // Send email to admin
        try {
            $adminEmail = config('app.admin_email');
            Mail::to($adminEmail)->send(new AdminNewRegistration($user));
        } catch (\Exception $e) {
            Log::error('Failed to send registration notification to admin: ' . $e->getMessage());
        }

        Auth::login($user);

        return redirect()->route('verification.notice');
    }

    /**
     * Handle berkas upload for perusahaan validation.
     */
    public function uploadBerkas(Request $request)
    {
        $request->validate([
            'berkas' => 'required|file|mimes:pdf|max:4096', // Max 4MB
        ], [
            'berkas.required' => 'File berkas wajib diunggah',
            'berkas.mimes' => 'Format file harus berupa PDF',
            'berkas.max' => 'Ukuran file maksimal 4MB',
        ]);

        $user = Auth::user();
        
        if ($user && $user->hasRole('perusahaan') && $user->id_perusahaan) {
            $perusahaan = Perusahaan::find($user->id_perusahaan);
            
            if ($perusahaan->berkas) {
                return back()->with('error', 'Berkas sudah diunggah, silakan tunggu validasi admin.');
            }
            
            if ($request->hasFile('berkas')) {
                $file = $request->file('berkas');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('perusahaan/berkas', $fileName, 'public');
                
                $perusahaan->update(['berkas' => $filePath]);
                
                return back()->with('success', 'Berkas perusahaan berhasil diunggah. Silakan tunggu validasi admin.');
            }
        }
        
        return back()->with('error', 'Gagal mengunggah berkas.');
    }
}
