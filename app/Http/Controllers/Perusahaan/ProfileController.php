<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display the profile for perusahaan.
     */
    public function index()
    {
        $idPerusahaan = Auth::user()->id_perusahaan;
        $perusahaan = Perusahaan::find($idPerusahaan);
        
        return view('pages.perusahaan.profile.index', compact('perusahaan'));
    }

    /**
     * Update perusahaan profile.
     */
    public function update(Request $request)
    {
        $idPerusahaan = Auth::user()->id_perusahaan;
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'nama' => 'required|string|max:255',
            'logo_base64' => 'nullable|string',
            'alamat' => 'required|string|max:500',
        ], [
            'name.required' => 'Nama pengguna wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'nama.required' => 'Nama perusahaan wajib diisi',
            'alamat.required' => 'Alamat perusahaan wajib diisi',
        ]);
        
        // Update user data
        Auth::user()->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);
        
        // Update perusahaan data
        $perusahaan = Perusahaan::find($idPerusahaan);
        $perusahaanData = [
            'nama' => $validated['nama'],
            'alamat' => $validated['alamat'],
        ];

        // Handle logo
        if (!empty($validated['logo_base64'])) {
            $base64Image = $validated['logo_base64'];
            $imageParts = explode(";base64,", $base64Image);
            if (count($imageParts) == 2) {
                // $imageTypeAux = explode("image/", $imageParts[0]);
                // $imageType = $imageTypeAux[1] ?? 'png';
                $imageBase64 = base64_decode($imageParts[1]);
                $fileName = 'logo_' . time() . '.webp';
                $filePath = 'perusahaan/logos/' . $fileName;
                
                if ($perusahaan->logo) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($perusahaan->logo);
                }
                
                \Illuminate\Support\Facades\Storage::disk('public')->put($filePath, $imageBase64);
                $perusahaanData['logo'] = $filePath;
            }
        }

        $perusahaan->update($perusahaanData);
        
        return back()->with('success', 'Data profil dan perusahaan berhasil diperbarui');
    }

    /**
     * Delete perusahaan logo.
     */
    public function deleteLogo()
    {
        $idPerusahaan = Auth::user()->id_perusahaan;
        $perusahaan = Perusahaan::find($idPerusahaan);

        if ($perusahaan && $perusahaan->logo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($perusahaan->logo);
            $perusahaan->update(['logo' => null]);
            return back()->with('success', 'Logo perusahaan berhasil dihapus');
        }

        return back()->with('error', 'Gagal menghapus logo');
    }
}
