<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\SesiAlat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlatController extends Controller
{
    /**
     * Menampilkan daftar alat milik perusahaan yang sedang login.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Pastikan user memiliki relasi ke perusahaan
        // Asumsi: tabel users punya kolom 'id_perusahaan'
        if (!$user->id_perusahaan) {
            abort(403, 'User tidak terasosiasi dengan perusahaan manapun.');
        }

        $search = $request->input('search');
        $status = $request->input('status'); // 'aktif' atau 'idle'

        // Query Dasar: Hanya ambil alat milik perusahaan ini
        $query = Alat::where('id_perusahaan', $user->id_perusahaan);

        // Filter Pencarian (Serial Number)
        if ($request->filled('search')) {
            $query->where('id', 'like', "%{$search}%");
        }

        // Ambil data dengan status sesi aktifnya
        // Kita gunakan 'with' untuk eager loading sesi yang sedang aktif
        $alatList = $query->get()->map(function ($alat) {
            // Cek apakah ada sesi aktif untuk alat ini
            $sesiAktif = SesiAlat::where('id_alat', $alat->id)
                ->where('aktif', true)
                ->with('kendaraan') // Ambil data kendaraan
                ->first();

            $alat->status_pemakaian = $sesiAktif ? 'digunakan' : 'tersedia';
            $alat->kendaraan_saat_ini = $sesiAktif ? $sesiAktif->kendaraan : null;
            $alat->sesi_id = $sesiAktif ? $sesiAktif->id : null;
            
            return $alat;
        });

        // Filter Status (jika user memilih filter di dropdown)
        if ($request->filled('status')) {
            $alatList = $alatList->where('status_pemakaian', $status == '1' ? 'digunakan' : 'tersedia');
        }

        // Manual Pagination karena kita memanipulasi collection di atas
        // (Opsional, jika data ribuan sebaiknya logic filter status dipindah ke Eloquent query langsung)
        $perPage = 10;
        $page = request()->get('page', 1);
        $paginatedData = new \Illuminate\Pagination\LengthAwarePaginator(
            $alatList->forPage($page, $perPage),
            $alatList->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        if ($request->ajax()) {
            return view('pages.perusahaan.alat.table', [
                'alatList' => $paginatedData,
            ])->render();
        }

        return view('pages.perusahaan.alat.index', [
            'alatList' => $paginatedData,
            'search' => $search,
            'filter_status' => $status
        ]);
    }
}