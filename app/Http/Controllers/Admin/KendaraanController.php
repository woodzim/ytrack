<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perusahaan;
use App\Models\Kendaraan;
use App\Models\JenisKendaraan;
use App\Models\SesiAlat;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */

    public function index(Request $request)
    {
        $search = $request->input('search');

        // 1. Eager Load Relasi
        // 2. Cek Status 'sedang_digunakan' secara efisien via query (bukan looping manual)
        $query = Kendaraan::with(['perusahaan', 'jenisKendaraan'])
            ->withExists(['sesiAlat as sedang_digunakan' => function ($q) {
                $q->where('aktif', true);
            }]);

        if ($search) {
            $query->where(function($q) use ($search) {
                // PENTING: Gunakan 'kendaraan.nama' bukan hanya 'nama'
                // untuk menghindari error "Ambiguous column" jika tabel relasi juga punya kolom 'nama'
                $q->where('kendaraan.nama', 'like', '%' . $search . '%')
                    ->orWhere('kendaraan.plat', 'like', '%' . $search . '%')
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
        $sedangDigunakan = SesiAlat::where('id_kendaraan', $kendaraan->id)
                            ->where('aktif', true)
                            ->exists(); // Mengembalikan true jika data ditemukan

        if ($sedangDigunakan) {
            return redirect()->route('kendaraan.index')
                ->with('error', "Gagal menghapus! Kendaraan '{$kendaraan->nama}' sedang digunakan dalam sesi aktif. Harap nonaktifkan sesi terlebih dahulu.");
        }

        try {
            $info = $kendaraan->nama . ' (' . $kendaraan->plat . ')';
            
            $kendaraan->delete();

            return redirect()->route('kendaraan.index')
                ->with('success', "Kendaraan $info berhasil dihapus.");
                
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == "23000") {
                return redirect()->route('kendaraan.index')
                    ->with('error', "Data tidak bisa dihapus karena memiliki riwayat perjalanan (terikat data lain).");
            }

            return redirect()->route('kendaraan.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data.');
        } catch (\Exception $e) {
            return redirect()->route('kendaraan.index')
                ->with('error', 'Gagal menghapus data kendaraan.');
        }
    }
}
