<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\JenisKendaraan;
use App\Models\SesiAlat;
use App\Models\ActivityLog; // <-- TAMBAHAN 1: Import Model Log
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
        $filter_status = $request->input('status');

        $query = Kendaraan::where('id_perusahaan', $idPerusahaan)
            ->with('jenisKendaraan')
            ->withExists(['sesiAlat as sedang_digunakan' => function ($q) {
                $q->where('aktif', true);
            }]);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('plat', 'like', '%' . $search . '%')
                    ->orWhere('nama', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('status') && $request->input('status') !== null && $request->input('status') !== '') {
            $filter_status = $request->input('status');
            if ($filter_status == '1') {
                // Sedang Digunakan (punya sesi aktif)
                $query->whereHas('sesiAlat', function($q) {
                    $q->where('aktif', true);
                });
            } elseif ($filter_status == '0') {
                // Standby (tidak punya sesi aktif)
                $query->whereDoesntHave('sesiAlat', function($q) {
                    $q->where('aktif', true);
                });
            }
        }

        $kendaraan = $query->latest()->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return view('pages.perusahaan.kendaraan.table', compact('kendaraan', 'search', 'filter_status'))->render();
        }

        return view('pages.perusahaan.kendaraan.index', compact('kendaraan', 'search', 'filter_status'));
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
        $validated = $request->validate([
            'id_jenis_kendaraan' => 'required|exists:jenis_kendaraan,id',
            'nama'               => 'required|string|max:255',
            'plat'               => 'required|string|max:20|unique:kendaraan,plat',
        ],[
            'nama.required'      => 'Nama kendaraan harus diisi',
            'plat.required'      => 'Nomor plat harus diisi',
            'plat.unique'        => 'Nomor plat ini sudah terdaftar',

        ]);

        // UBAH DISINI: Tampung hasil create ke variabel $kendaraan agar bisa ambil ID-nya
        $kendaraan = Kendaraan::create([
            'id_perusahaan'      => Auth::user()->id_perusahaan,
            'id_jenis_kendaraan' => $validated['id_jenis_kendaraan'],
            'nama'               => $validated['nama'],
            'plat'               => $validated['plat'],
        ]);

        // --- TAMBAHAN LOG: CREATE ---
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'CREATE',
            'subject_type' => Kendaraan::class,
            'subject_id' => $kendaraan->id,
            'description' => "Perusahaan menambahkan kendaraan: {$kendaraan->nama} ({$kendaraan->plat})",
            'ip_address' => $request->ip(),
        ]);
        // -----------------------------

        return redirect()->route('perusahaan.kendaraan.index')
            ->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    /**
     * Form edit kendaraan.
     */
    public function edit($id)
    {
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
            'plat'               => 'required|string|max:20|unique:kendaraan,plat,' . $kendaraan->id,
        ],[
            'nama.required'      => 'Nama kendaraan harus diisi',
            'plat.required'      => 'Nomor plat harus diisi',
            'plat.unique'        => 'Nomor plat ini sudah terdaftar',
        ]);

        // Simpan info lama untuk log
        $oldInfo = "{$kendaraan->nama} ({$kendaraan->plat})";

        $kendaraan->update([
            'id_jenis_kendaraan' => $validated['id_jenis_kendaraan'],
            'nama'               => $validated['nama'],
            'plat'               => $validated['plat'],
        ]);

        // --- TAMBAHAN LOG: UPDATE ---
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE',
            'subject_type' => Kendaraan::class,
            'subject_id' => $kendaraan->id,
            'description' => "Perusahaan memperbarui kendaraan. Dari [$oldInfo] menjadi [{$validated['nama']} ({$validated['plat']})]",
            'ip_address' => $request->ip(),
        ]);
        // -----------------------------

        return redirect()->route('perusahaan.kendaraan.index')
            ->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    /**
     * Hapus kendaraan.
     */
    public function destroy($id)
    {
        $idPerusahaan = Auth::user()->id_perusahaan;

        $sedangDigunakan = SesiAlat::where('id_kendaraan', $id)
            ->where('aktif', true)
            ->exists();

        if ($sedangDigunakan) {
            return redirect()->route('perusahaan.kendaraan.index')
                ->with('error', 'Gagal menghapus! Kendaraan sedang beroperasi (Status: Jalan). Harap selesaikan sesi terlebih dahulu.');
        }

        $kendaraan = Kendaraan::where('id', $id)
            ->where('id_perusahaan', $idPerusahaan)
            ->firstOrFail();

        try {
            // Simpan info sebelum hapus
            $info = $kendaraan->nama . ' (' . $kendaraan->plat . ')';
            $deletedId = $kendaraan->id;

            $kendaraan->delete();
            
            // --- TAMBAHAN LOG: DELETE ---
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'DELETE',
                'subject_type' => Kendaraan::class,
                'subject_id' => $deletedId,
                'description' => "Perusahaan menghapus kendaraan: $info",
                'ip_address' => request()->ip(),
            ]);
            // -----------------------------

            return redirect()->route('perusahaan.kendaraan.index')
                ->with('success', 'Kendaraan berhasil dihapus.');
                
        } catch (\Exception $e) {
            return redirect()->route('perusahaan.kendaraan.index')
                ->with('error', 'Kendaraan tidak bisa dihapus karena memiliki riwayat penggunaan.');
        }
    }
}