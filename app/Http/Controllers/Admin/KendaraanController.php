<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perusahaan;
use App\Models\Kendaraan;
use App\Models\JenisKendaraan;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */

    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Kendaraan::with(['perusahaan', 'jenisKendaraan']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('plat', 'like', '%' . $search . '%')
                    ->orWhereHas('perusahaan', function($subQuery) use ($search) {
                        $subQuery->where('nama', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('jenisKendaraan', function($subQuery) use ($search) {
                        $subQuery->where('nama', 'like', '%' . $search . '%');
                    });
            });
        }

        $kendaraan = $query->latest() 
                            ->paginate(10)
                            ->appends(request()->query());

        return view('pages.admin.kendaraan.index', compact('kendaraan', 'search'));
    }

    public function create()
    {
        $perusahaan = Perusahaan::all();
        $jenisKendaraan = JenisKendaraan::all();

        return view('pages.admin.kendaraan.create', compact('perusahaan', 'jenisKendaraan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_perusahaan'      => 'required|exists:perusahaan,id',
            'id_jenis_kendaraan' => 'required|exists:jenis_kendaraan,id',
            'nama'               => 'required|string|max:255',
            'plat'               => 'required|string|max:20|unique:kendaraan,plat',
        ], [
            'id_perusahaan.required'      => 'Perusahaan harus dipilih',
            'id_jenis_kendaraan.required' => 'Jenis kendaraan harus dipilih',
            'nama.required'               => 'Nama kendaraan harus diisi',
            'plat.required'               => 'Nomor plat harus diisi',
            'plat.unique'                 => 'Nomor plat ini sudah terdaftar',
        ]);

        Kendaraan::create($validated);

        // 3. Redirect
        return redirect()->route('kendaraan.index')
            ->with('success', 'Kendaraan berhasil ditambahkan');
    }

    public function edit(Kendaraan $kendaraan)
    {
        $perusahaan = Perusahaan::all();
        $jenisKendaraan = JenisKendaraan::all();

        return view('pages.admin.kendaraan.edit', compact('kendaraan', 'perusahaan', 'jenisKendaraan'));
    }

    public function update(Request $request, \App\Models\Kendaraan $kendaraan)
    {
        $validated = $request->validate([
            'id_perusahaan'      => 'required|exists:perusahaan,id',
            'id_jenis_kendaraan' => 'required|exists:jenis_kendaraan,id',
            'nama'               => 'required|string|max:255',
            'plat'               => 'required|string|max:20|unique:kendaraan,plat,' . $kendaraan->id,
        ], [
            'id_perusahaan.required'        => 'Perusahaan harus dipilih',
            'id_jenis_kendaraan.required'   => 'Jenis kendaraan harus dipilih',
            'plat.unique'                   => 'Nomor plat ini sudah digunakan kendaraan lain',
        ]);

        // 2. Update (Mutator akan otomatis mengubah plat jadi HURUF BESAR)
        $kendaraan->update($validated);

        // 3. Redirect
        return redirect()->route('kendaraan.index')
            ->with('success', 'Data kendaraan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kendaraan $kendaraan)
    {
        try {
            // Ambil nama/plat untuk pesan notifikasi (opsional)
            $info = $kendaraan->nama . ' (' . $kendaraan->plat . ')';
            
            $kendaraan->delete();

            // Redirect ke halaman Index Kendaraan (Daftar semua kendaraan)
            return redirect()->route('kendaraan.index')
                ->with('success', "Kendaraan $info berhasil dihapus.");
                
        } catch (\Exception $e) {
            // Error handling jika ada masalah (misal constraint database)
            return redirect()->route('kendaraan.index')
                ->with('error', 'Gagal menghapus data kendaraan.');
        }
    }
}
