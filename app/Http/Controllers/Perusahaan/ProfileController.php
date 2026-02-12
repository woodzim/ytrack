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
        $perusahaan->update([
            'nama' => $validated['nama'],
            'alamat' => $validated['alamat'],
        ]);
        
        return back()->with('success', 'Data profil dan perusahaan berhasil diperbarui');
    }
}
