<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Alat;
use App\Models\SesiAlat;
use App\Models\ActivityLog; // <-- TAMBAHAN 1: Import Model Log
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SesiAlatController extends Controller
{
    /**
     * Display a listing of the resource for current perusahaan.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $filter_status = $request->input('status', '');
        
        $idPerusahaan = Auth::user()->id_perusahaan;

        $sesiAlat = SesiAlat::with('alat', 'kendaraan', 'perusahaan', 'riwayatPerjalanan')
            ->where('id_perusahaan', $idPerusahaan);

        if ($request->filled('search')) {
            $sesiAlat->where(function ($query) use ($search) {
                $query->where('id', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhereHas('alat', function ($q) use ($search) {
                        $q->where('id', 'like', '%' . $search . '%');
                    })->orWhereHas('kendaraan', function ($q) use ($search) {
                        $q->where('nama', 'like', '%' . $search . '%')
                            ->orWhere('plat', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('status')) {
            $sesiAlat->where('aktif', (bool)$filter_status);
        }

        $sesiAlat = $sesiAlat
            ->orderBy('id_alat', 'asc')
            ->orderBy('aktif', 'desc')
            ->paginate(10)
            ->appends(request()->query());

        if ($request->ajax()) {
            return view('pages.perusahaan.sesi-alat.table', compact('sesiAlat'))->render();
        }

        return view('pages.perusahaan.sesi-alat.index', compact('sesiAlat', 'search', 'filter_status'));
    }

    public function create()
    {
        $idPerusahaan = Auth::user()->id_perusahaan;

        $kendaraan = Kendaraan::where('id_perusahaan', $idPerusahaan)
            ->with(['sesiAlat' => function($q) {
                $q->where('aktif', true);
            }])
            ->get()
            ->sortBy(function($item) {
                return $item->sesiAlat->isNotEmpty() ? 1 : 0;
            });

        $alat = Alat::where('id_perusahaan', $idPerusahaan)
            ->with(['sesiAlat' => function($q) {
                $q->where('aktif', true)->with('kendaraan');
            }])
            ->get()
            ->sortBy(function($item) {
                return $item->sesiAlat->isNotEmpty() ? 1 : 0;
            });

        return view('pages.perusahaan.sesi-alat.create', compact('kendaraan', 'alat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'id_kendaraan' => [
                'required',
                // Custom rule: Pastikan kendaraan ini belum punya sesi aktif
                function ($attribute, $value, $fail) {
                    $exists = SesiAlat::where('id_kendaraan', $value)
                        ->where('aktif', true)
                        ->exists();
                    if ($exists) {
                        $fail('Kendaraan ini sedang dalam sesi aktif lain.');
                    }
                },
            ],
            'id_alat' => [
                'required',
                // Custom rule: Pastikan alat ini belum punya sesi aktif
                function ($attribute, $value, $fail) {
                    $exists = SesiAlat::where('id_alat', $value) 
                        ->where('aktif', true)
                        ->exists();
                    if ($exists) {
                        $fail('Alat GPS ini sedang digunakan di sesi lain.');
                    }
                },
            ],
            'aktif' => 'boolean'
        ]);

        // Logika otomatis mematikan sesi lain jika user memaksa aktif (walaupun validasi di atas sudah mencegah,
        // kode ini tetap aman jika validasi diubah di masa depan)
        if ($request->input('aktif') == 1) {
            SesiAlat::where('id_alat', $request->id_alat)
                    ->where('aktif', 1)
                    ->update(['aktif' => 0]);
        }

        $sesiAlat = SesiAlat::create([
            'id_perusahaan' => Auth::user()->id_perusahaan,
            'id_kendaraan'  => $request->id_kendaraan,
            'id_alat'       => $request->id_alat,
            'nama'          => $request->nama,
            'aktif'         => $request->input('aktif', 0),
        ]);

        // --- TAMBAHAN LOG: CREATE ---
        // Load kendaraan untuk mendapatkan Plat Nomor agar log informatif
        $kendaraanInfo = Kendaraan::find($request->id_kendaraan);
        $plat = $kendaraanInfo ? $kendaraanInfo->plat : 'Unknown';

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'CREATE',
            'subject_type' => SesiAlat::class,
            'subject_id' => $sesiAlat->id,
            'description' => "Perusahaan memulai perjalanan baru. Alat: {$request->id_alat}, Kendaraan: {$plat}",
            'ip_address' => $request->ip(),
        ]);
        // -----------------------------

        return redirect()->route('perusahaan.perjalanan.index')
                        ->with('success', 'Perjalanan berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SesiAlat $sesiAlat)
    {
        if ($sesiAlat->id_perusahaan !== auth()->user()->id_perusahaan) {
            abort(403, 'Unauthorized access');
        }

        $sesiAlat->load('alat', 'kendaraan', 'perusahaan');

        $cacheKey = "sesi_alat_tracking_{$sesiAlat->id}_perusahaan";

        // Cache history for 10 seconds and get ALL points
        $riwayatPerjalanan = Cache::remember($cacheKey, 10, function () use ($sesiAlat) {
            return $sesiAlat->riwayatPerjalanan()
                ->orderBy('created_at', 'desc')
                ->get()
                ->values();
        });

        return view('pages.perusahaan.sesi-alat.show', compact('sesiAlat', 'riwayatPerjalanan'));
    }

    /**
     * Toggle Status (Aktif/Nonaktif).
     */
    public function toggle($id)
    {
        $sesi = SesiAlat::where('id', $id)
            ->where('id_perusahaan', Auth::user()->id_perusahaan)
            ->firstOrFail();

        if (!$sesi->aktif) { 
            // Jika mau diaktifkan, matikan sesi lain yang bentrok
            SesiAlat::where('id_alat', $sesi->id_alat)
                ->where('id', '!=', $id)
                ->where('aktif', 1)
                ->update(['aktif' => 0]);

            SesiAlat::where('id_kendaraan', $sesi->id_kendaraan)
                ->where('id', '!=', $id)
                ->where('aktif', 1)
                ->update(['aktif' => 0]);
        }

        $sesi->aktif = !$sesi->aktif;
        $sesi->save();

        // --- TAMBAHAN LOG: TOGGLE ---
        $statusText = $sesi->aktif ? 'MENGAKTIFKAN' : 'MENONAKTIFKAN';
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE',
            'subject_type' => SesiAlat::class,
            'subject_id' => $sesi->id,
            'description' => "Perusahaan $statusText perjalanan (ID Alat: {$sesi->id_alat})",
            'ip_address' => request()->ip(),
        ]);
        // -----------------------------

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'aktif' => $sesi->aktif,
                'message' => 'Sesi berhasil ' . ($sesi->aktif ? 'diaktifkan' : 'dinonaktifkan') . '.'
            ]);
        }

        $statusMsg = $sesi->aktif ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', 'Sesi berhasil ' . $statusMsg . '.');
    }

    /**
     * Hapus Sesi.
     */
    public function destroy($id)
    {
        $sesi = SesiAlat::where('id', $id)
            ->where('id_perusahaan', Auth::user()->id_perusahaan)
            ->firstOrFail();

        // Simpan info sebelum hapus
        $info = "Alat: {$sesi->id_alat} | Kendaraan ID: {$sesi->id_kendaraan}";
        $deletedId = $sesi->id;

        $sesi->delete();

        // --- TAMBAHAN LOG: DELETE ---
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'DELETE',
            'subject_type' => SesiAlat::class,
            'subject_id' => $deletedId,
            'description' => "Perusahaan menghapus perjalanan. Detail: [$info]",
            'ip_address' => request()->ip(),
        ]);
        // -----------------------------

        return redirect()->route('perusahaan.perjalanan.index')
                            ->with('success', 'Sesi berhasil dihapus.');
    }
}