<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\JenisKendaraan;
use App\Models\SesiAlat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KendaraanController extends Controller
{
    /**
     * Menampilkan daftar kendaraan milik perusahaan yang sedang login.
     */
    public function index(Request $request)
    {
        $idPerusahaan = Auth::user()->id_perusahaan;
        $search = $request->input('search');

        // Update: Tambahkan withExists agar pengecekan status di view jauh lebih cepat
        $query = Kendaraan::where('id_perusahaan', $idPerusahaan)
            ->with('jenisKendaraan')
            ->withExists(['sesiAlat as sedang_digunakan' => function ($q) {
                $q->where('aktif', true);
            }]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('plat', 'like', '%' . $search . '%')
                    ->orWhere('nama', 'like', '%' . $search . '%');
            });
        }

        $kendaraan = $query->latest()->paginate(10)->withQueryString();

        return view('pages.perusahaan.kendaraan.index', compact('kendaraan', 'search'));
    }

    /**
     * Form tambah kendaraan.
     */
    public function create()
    {
        $jenisKendaraan = JenisKendaraan::all();
        return view('pages.perusahaan.kendaraan.create', compact('jenisKendaraan'));
    }

    /**
     * Simpan kendaraan baru.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'id_jenis_kendaraan' => 'required|exists:jenis_kendaraan,id',
            'nama'               => 'required|string|max:255',
            'plat'               => 'required|string|max:20|unique:kendaraan,plat',
        ],[
            'nama.required'               => 'Nama kendaraan harus diisi',
            'plat.required'               => 'Nomor plat harus diisi',
            'plat.unique'                 => 'Nomor plat ini sudah terdaftar',

        ]);

        // 2. Simpan ke Database
        Kendaraan::create([
            'id_perusahaan'      => Auth::user()->id_perusahaan,
            'id_jenis_kendaraan' => $validated['id_jenis_kendaraan'],
            'nama'               => $validated['nama'],
            'plat'               => $validated['plat'],
        ]);

        return redirect()->route('perusahaan.kendaraan.index')
                            ->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    /**
     * Form edit kendaraan.
     */
    public function edit($id)
    {
        // Pastikan hanya bisa edit kendaraan milik perusahaan sendiri
        $kendaraan = Kendaraan::where('id', $id)
            ->where('id_perusahaan', Auth::user()->id_perusahaan)
            ->firstOrFail();

        $jenisKendaraan = JenisKendaraan::all();

        return view('pages.perusahaan.kendaraan.edit', compact('kendaraan', 'jenisKendaraan'));
    }

    /**
     * Update data kendaraan.
     */
    public function update(Request $request, $id)
    {
        $kendaraan = Kendaraan::where('id', $id)
            ->where('id_perusahaan', Auth::user()->id_perusahaan)
            ->firstOrFail();

        $validated = $request->validate([
            'id_jenis_kendaraan' => 'required|exists:jenis_kendaraan,id',
            'nama'               => 'required|string|max:255',
            // VALIDASI UNIQUE UPDATE: Abaikan ID kendaraan ini saat pengecekan
            'plat'               => 'required|string|max:20|unique:kendaraan,plat,' . $kendaraan->id,
        ],[
            'nama.required'               => 'Nama kendaraan harus diisi',
            'plat.required'               => 'Nomor plat harus diisi',
            'plat.unique'                 => 'Nomor plat ini sudah terdaftar',

        ]);

        $kendaraan->update([
            'id_jenis_kendaraan' => $validated['id_jenis_kendaraan'],
            'nama'               => $validated['nama'],
            'plat'               => $validated['plat'],
        ]);

        return redirect()->route('perusahaan.kendaraan.index')
                            ->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    /**
     * Hapus kendaraan.
     */
    public function destroy($id)
    {
        $idPerusahaan = Auth::user()->id_perusahaan;

        // 1. Cek apakah kendaraan ini sedang dipakai (Aktif)
        $sedangDigunakan = SesiAlat::where('id_kendaraan', $id)
            ->where('aktif', true)
            ->exists();

        if ($sedangDigunakan) {
            // Jika aktif, kembalikan dengan pesan error
            return redirect()->route('perusahaan.kendaraan.index')
                ->with('error', 'Gagal menghapus! Kendaraan sedang beroperasi (Status: Jalan). Harap selesaikan sesi terlebih dahulu.');
        }

        // 2. Cari dan Hapus
        $kendaraan = Kendaraan::where('id', $id)
            ->where('id_perusahaan', $idPerusahaan)
            ->firstOrFail();

        try {
            $kendaraan->delete();
            
            return redirect()->route('perusahaan.kendaraan.index')
                ->with('success', 'Kendaraan berhasil dihapus.');
                
        } catch (\Exception $e) {
            // Antisipasi jika database menolak (constraint)
            return redirect()->route('perusahaan.kendaraan.index')
                ->with('error', 'Kendaraan tidak bisa dihapus karena memiliki riwayat penggunaan.');
        }
    }
}