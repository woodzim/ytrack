<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use App\Models\Kendaraan;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Perusahaan $perusahaan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
        ], [
            'nama.required' => 'Nama kendaraan harus diisi',
            'nama.string' => 'Nama kendaraan harus berupa teks',
            'nama.max' => 'Nama kendaraan maksimal 255 karakter',
        ]);

        Kendaraan::create([
            'id_perusahaan' => $perusahaan->id,
            'nama' => $validated['nama'],
        ]);

        return redirect()->route('perusahaan.show', $perusahaan->id)
                       ->with('success', 'Kendaraan berhasil ditambahkan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Perusahaan $perusahaan, Kendaraan $kendaraan)
    {
        // Verify that kendaraan belongs to this perusahaan
        if ($kendaraan->id_perusahaan !== $perusahaan->id) {
            return abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
        ], [
            'nama.required' => 'Nama kendaraan harus diisi',
            'nama.string' => 'Nama kendaraan harus berupa teks',
            'nama.max' => 'Nama kendaraan maksimal 255 karakter',
        ]);

        $kendaraan->update($validated);

        return redirect()->route('perusahaan.show', $perusahaan->id)
                       ->with('success', 'Kendaraan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Perusahaan $perusahaan, Kendaraan $kendaraan)
    {
        // Verify that kendaraan belongs to this perusahaan
        if ($kendaraan->id_perusahaan !== $perusahaan->id) {
            return abort(403, 'Unauthorized');
        }

        $kendaraan->delete();

        return redirect()->route('perusahaan.show', $perusahaan->id)
                       ->with('success', 'Kendaraan berhasil dihapus');
    }
}
