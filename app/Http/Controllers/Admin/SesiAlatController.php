<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SesiAlat;
use App\Models\Alat;
use App\Models\Kendaraan;
use App\Models\Perusahaan;
use App\Models\ActivityLog; // <-- TAMBAHAN 1: Import Model Log
use Illuminate\Support\Facades\Auth; // <-- TAMBAHAN 2: Import Auth
use Illuminate\Http\Request;

class SesiAlatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $filter_status = $request->input('status', '');

        $sesiAlat = SesiAlat::with('alat', 'kendaraan', 'perusahaan', 'riwayatPerjalanan');

        if ($search) {
            $sesiAlat->where(function ($query) use ($search) {
                // Cari berdasarkan ID Alat (Serial Number)
                $query->whereHas('alat', function ($q) use ($search) {
                    $q->where('id', 'like', '%' . $search . '%');
                })
                // Cari berdasarkan Nama Kendaraan
                ->orWhereHas('kendaraan', function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%');
                })
                // Cari berdasarkan Nama Perusahaan
                ->orWhereHas('perusahaan', function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%');
                });
            });
        }

        if ($filter_status !== '') {
            $sesiAlat->where('aktif', (bool)$filter_status);
        }

        $sesiAlat = $sesiAlat
            ->orderBy('aktif', 'desc') // Prioritaskan sesi aktif di atas
            ->orderBy('created_at', 'desc') // Urutkan sesi terbaru
            ->paginate(10)
            ->appends(request()->query());

        return view('pages.admin.sesi-alat.index', compact('sesiAlat', 'search', 'filter_status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $perusahaan = Perusahaan::all();
        return view('pages.admin.sesi-alat.create', compact('perusahaan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'          => 'nullable|string|max:255',
            'id_alat'       => 'required|exists:alat,id',
            'id_kendaraan'  => 'required|exists:kendaraan,id',
            'id_perusahaan' => 'required|exists:perusahaan,id',
            'aktif'         => 'boolean',
        ]);

        // Cek apakah alat sedang aktif di sesi lain
        $activeAlatSession = SesiAlat::where('id_alat', $validated['id_alat'])
            ->where('aktif', true)
            ->first();

        if ($activeAlatSession) {
            return back()->withInput()->with('error', 'Alat ini sudah digunakan oleh sesi lain yang aktif. Hentikan sesi sebelumnya terlebih dahulu.');
        }

        // Cek apakah kendaraan sedang aktif di sesi lain
        $activeKendaraanSession = SesiAlat::where('id_kendaraan', $validated['id_kendaraan'])
            ->where('aktif', true)
            ->first();

        if ($activeKendaraanSession) {
            return back()->withInput()->with('error', 'Kendaraan ini sudah digunakan oleh sesi lain yang aktif. Hentikan sesi sebelumnya terlebih dahulu.');
        }

        // Simpan
        $sesiAlat = SesiAlat::create($validated);

        // --- TAMBAHAN LOG: CREATE ---
        // Kita load relasi sebentar untuk mendapatkan nama Kendaraan agar log lebih informatif
        $sesiAlat->load(['kendaraan', 'alat']);
        
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'CREATE',
            'subject_type' => SesiAlat::class,
            'subject_id' => $sesiAlat->id,
            'description' => "Admin membuat sesi alat baru. Alat: {$sesiAlat->alat->id}, Kendaraan: {$sesiAlat->kendaraan->nama} ({$sesiAlat->kendaraan->plat})",
            'ip_address' => $request->ip(),
        ]);
        // -----------------------------

        return redirect()->route('sesi-alat.index')->with('success', 'Sesi Alat berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(SesiAlat $sesiAlat)
    {
        $sesiAlat->load('alat', 'kendaraan', 'perusahaan');
        $riwayatPerjalanan = $sesiAlat->riwayatPerjalanan()->orderBy('created_at')->get();
        return view('pages.admin.sesi-alat.show', compact('sesiAlat', 'riwayatPerjalanan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SesiAlat $sesiAlat)
    {
        $alat = Alat::all();
        $kendaraan = Kendaraan::all();
        $perusahaan = Perusahaan::all();

        return view('pages.admin.sesi-alat.edit', compact('sesiAlat', 'alat', 'kendaraan', 'perusahaan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SesiAlat $sesiAlat)
    {
        $validated = $request->validate([
            'nama'          => 'nullable|string|max:255',
            'id_alat'       => 'required|exists:alat,id',
            'id_kendaraan'  => 'required|exists:kendaraan,id',
            'id_perusahaan' => 'required|exists:perusahaan,id',
            'aktif'         => 'boolean',
        ]);

        // Validasi jika alat berubah dan alat baru sedang aktif
        if ($validated['id_alat'] !== $sesiAlat->id_alat) {
            $activeAlatSession = SesiAlat::where('id_alat', $validated['id_alat'])
                ->where('aktif', true)
                ->where('id', '!=', $sesiAlat->id)
                ->first();

            if ($activeAlatSession) {
                return back()->withInput()->with('error', 'Alat ini sudah digunakan oleh sesi lain yang aktif.');
            }
        }

        // Validasi jika kendaraan berubah dan kendaraan baru sedang aktif
        if ($validated['id_kendaraan'] !== $sesiAlat->id_kendaraan) {
            $activeKendaraanSession = SesiAlat::where('id_kendaraan', $validated['id_kendaraan'])
                ->where('aktif', true)
                ->where('id', '!=', $sesiAlat->id)
                ->first();

            if ($activeKendaraanSession) {
                return back()->withInput()->with('error', 'Kendaraan ini sudah digunakan oleh sesi lain yang aktif.');
            }
        }

        // --- 1. SIMPAN INFO LAMA (Termasuk Nama) ---
        $oldAlat      = $sesiAlat->id_alat;
        $oldKendaraan = $sesiAlat->id_kendaraan;
        $oldNama      = $sesiAlat->nama; // <--- Tambahan: Simpan nama lama

        // Lakukan Update
        $sesiAlat->update($validated);

        // --- 2. LOGGING ---
        $desc = "Admin memperbarui Sesi Alat ID #{$sesiAlat->id}.";
        
        // Cek perubahan Alat
        if ($oldAlat !== $validated['id_alat']) {
            $desc .= " Alat berubah dari [$oldAlat] ke [{$validated['id_alat']}].";
        }

        // Cek perubahan Kendaraan
        if ($oldKendaraan != $validated['id_kendaraan']) {
            $newKendaraan = Kendaraan::find($validated['id_kendaraan']);
            $desc .= " Kendaraan berubah ke [{$newKendaraan->nama}].";
        }

        // --- TAMBAHAN: Cek perubahan Nama ---
        if ($oldNama !== $validated['nama']) {
            // Kita pakai operator ?? '-' untuk berjaga-jaga jika datanya null (kosong)
            $valOld = $oldNama ?? '-'; 
            $valNew = $validated['nama'] ?? '-';
            
            $desc .= " Nama Sesi berubah dari [$valOld] ke [$valNew].";
        }

        ActivityLog::create([
            'user_id'      => Auth::id(),
            'action'       => 'UPDATE',
            'subject_type' => SesiAlat::class,
            'subject_id'   => $sesiAlat->id,
            'description'  => $desc,
            'ip_address'   => $request->ip(),
        ]);
        // ------------------------------------

        return redirect()->route('sesi-alat.index')->with('success', 'Sesi Alat berhasil diperbarui');
    }

    /**
     * Toggle aktif status
     */
    public function toggle(SesiAlat $sesiAlat)
    {
        // Jika mau mengaktifkan sesi yang mati, cek dulu bentrok atau tidak
        if (!$sesiAlat->aktif) {
            $activeAlatSession = SesiAlat::where('id_alat', $sesiAlat->id_alat)
                ->where('aktif', true)
                ->first();

            if ($activeAlatSession) {
                return back()->with('error', 'Alat ini sudah digunakan oleh sesi lain yang aktif.');
            }

            $activeKendaraanSession = SesiAlat::where('id_kendaraan', $sesiAlat->id_kendaraan)
                ->where('aktif', true)
                ->first();

            if ($activeKendaraanSession) {
                return back()->with('error', 'Kendaraan ini sudah digunakan oleh sesi lain yang aktif.');
            }
        }

        $newState = !$sesiAlat->aktif;
        $sesiAlat->update(['aktif' => $newState]);

        // --- TAMBAHAN LOG: TOGGLE ---
        $statusText = $newState ? 'MENGAKTIFKAN' : 'MENONAKTIFKAN';
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE', // Bisa dianggap update status
            'subject_type' => SesiAlat::class,
            'subject_id' => $sesiAlat->id,
            'description' => "Admin $statusText sesi alat (ID: {$sesiAlat->id_alat}) pada kendaraan (ID: {$sesiAlat->id_kendaraan})",
            'ip_address' => request()->ip(),
        ]);
        // -----------------------------

        $status = $sesiAlat->aktif ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', 'Sesi Alat berhasil ' . $status);
    }

    /**
     * Get kendaraan by perusahaan (for AJAX)
     */
    public function getKendaraanByPerusahaan($idPerusahaan)
    {
        $kendaraanList = Kendaraan::where('id_perusahaan', $idPerusahaan)
            ->orderBy('nama', 'asc')
            ->get();

        $data = $kendaraanList->map(function ($item) {
            $isUsed = SesiAlat::where('id_kendaraan', $item->id)
                ->where('aktif', true)
                ->exists();

            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'plat' => $item->plat,
                'is_active' => $isUsed
            ];
        });

        $sortedData = $data->sortBy('is_active')->values();

        return response()->json($sortedData);
    }

    /**
     * Get alat by perusahaan (for AJAX)
     */
    public function getAlatByPerusahaan($idPerusahaan)
    {
        // Ambil data alat berdasarkan perusahaan
        $alatList = Alat::where('id_perusahaan', $idPerusahaan)
            ->orderBy('id', 'asc') // Urutkan berdasarkan Serial Number (ID)
            ->get();

        $data = $alatList->map(function ($item) {
            // Cek apakah alat sedang dipakai di sesi aktif
            $sesiAktif = SesiAlat::where('id_alat', $item->id)
                ->where('aktif', true)
                ->with('kendaraan')
                ->first();

            $vehicleInfo = null;
            if ($sesiAktif && $sesiAktif->kendaraan) {
                $vehicleInfo = $sesiAktif->kendaraan->nama . ' (' . $sesiAktif->kendaraan->plat . ')';
            }

            return [
                'id' => $item->id, // Ini adalah Serial Number
                'is_active' => $sesiAktif ? true : false,
                'current_vehicle' => $vehicleInfo
            ];
        });

        // Sorting: Pindahkan yang sedang aktif (unavailable) ke bawah
        $sortedData = $data->sortBy('is_active')->values();

        return response()->json($sortedData);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SesiAlat $sesiAlat)
    {
        $id = $sesiAlat->id;
        $info = "Alat: {$sesiAlat->id_alat} | Kendaraan ID: {$sesiAlat->id_kendaraan}";

        $sesiAlat->delete();

        // --- TAMBAHAN LOG: DELETE ---
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'DELETE',
            'subject_type' => SesiAlat::class,
            'subject_id' => $id,
            'description' => "Admin menghapus sesi alat. Detail: [$info]",
            'ip_address' => request()->ip(),
        ]);
        // -----------------------------

        return redirect()->route('sesi-alat.index')->with('success', 'Sesi Alat berhasil dihapus');
    }
}