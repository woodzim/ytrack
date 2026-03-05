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
     * Handle multi-file berkas upload for perusahaan validation per document type.
     */
    public function uploadBerkas(Request $request)
    {
        $request->validate([
            'berkas' => 'required|file|mimes:pdf|max:4096', // Max 4MB
            'type' => 'required|string|in:akta,npwp_badan,nib,domisili,ktp_pj,npwp_pj,izin_usaha,siup',
        ], [
            'berkas.required' => 'File berkas wajib diunggah',
            'berkas.mimes' => 'Format file harus berupa PDF',
            'berkas.max' => 'Ukuran file maksimal 4MB',
            'type.required' => 'Tipe dokumen wajib disertakan',
            'type.in' => 'Tipe dokumen tidak valid',
        ]);

        $user = Auth::user();
        
        if ($user && $user->hasRole('perusahaan') && $user->id_perusahaan) {
            $perusahaan = Perusahaan::find($user->id_perusahaan);
            $type = $request->type;
            $column = 'berkas_' . $type;
            
            if ($request->hasFile('berkas')) {
                $file = $request->file('berkas');
                $fileName = time() . '_' . $type . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('perusahaan/berkas/' . $perusahaan->id, $fileName, 'public');
                
                // Hapus file lama di disk jika ada
                if ($perusahaan->{$column}) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($perusahaan->{$column});
                }
                
                $perusahaan->update([$column => $filePath]);
                
                return response()->json([
                    'success' => true, 
                    'message' => 'Berkas berhasil diunggah.',
                    'path' => $filePath
                ]);
            }
        }
        
        return response()->json(['success' => false, 'message' => 'Gagal mengunggah berkas.'], 400);
    }

    /**
     * Delete an uploaded berkas.
     */
    public function deleteBerkas(Request $request, $type)
    {
        $user = Auth::user();
        \Illuminate\Support\Facades\Log::info("Attempting to delete berkas type: $type for user: " . ($user ? $user->id : 'Guest'));
        
        if ($user && $user->hasRole('perusahaan') && $user->id_perusahaan) {
            $perusahaan = \App\Models\Perusahaan::find($user->id_perusahaan);
            $column = 'berkas_' . $type;
            
            \Illuminate\Support\Facades\Log::info("Perusahaan ID: " . ($perusahaan ? $perusahaan->id : 'Not Found') . ", Column: $column, Value: " . ($perusahaan ? $perusahaan->{$column} : 'N/A'));

            if ($perusahaan && $perusahaan->{$column}) {
                $deleted = \Illuminate\Support\Facades\Storage::disk('public')->delete($perusahaan->{$column});
                \Illuminate\Support\Facades\Log::info("File deletion status: " . ($deleted ? 'Success' : 'Failed'));
                
                $perusahaan->update([$column => null]);
                return response()->json(['success' => true]);
            } else {
                \Illuminate\Support\Facades\Log::warning("File not found or column empty for type: $type");
            }
        } else {
            \Illuminate\Support\Facades\Log::error("User not authorized or missing perusahaan ID");
        }
        
        return response()->json(['success' => false, 'message' => 'Berkas tidak ditemukan atau Anda tidak memiliki akses.'], 400);
    }

    /**
     * Finalize submission of verification documents
     */
    public function submitBerkas(Request $request)
    {
        $user = Auth::user();
        if ($user && $user->hasRole('perusahaan') && $user->id_perusahaan) {
            $perusahaan = Perusahaan::find($user->id_perusahaan);
            
            // Validasi bidang wajib minimum
            $mandatory = ['berkas_akta', 'berkas_npwp_badan', 'berkas_nib', 'berkas_domisili', 'berkas_ktp_pj', 'berkas_npwp_pj'];
            foreach ($mandatory as $field) {
                if (empty($perusahaan->{$field})) {
                    return back()->with('error', 'Semua dokumen dengan status WAJIB harus diunggah terlebih dahulu sebelum mengirim permohonan validasi.');
                }
            }
            
            // Flag submission as complete (untuk memicu status cek di sisi admin/UI)
            if (empty($perusahaan->berkas)) {
                $perusahaan->update(['berkas' => 'submitted_multi_flow']);
            }
            
            return back()->with('success', 'Semua berkas wajib telah dikirim. Permohonan Anda sekarang dalam antrean validasi Administrator.');
        }
        return back()->with('error', 'Akses ditolak.');
    }
}
