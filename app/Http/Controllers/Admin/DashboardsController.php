<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SesiAlat;
use App\Models\RiwayatPerjalanan;
use App\Models\Alat;
use App\Models\Kendaraan;
use App\Models\Perusahaan;

class DashboardsController extends Controller
{
      
    public function index()
    {
        $totalSesiAlatAktif = SesiAlat::where('aktif', true)->count();
        $totalAlat = Alat::count();
        $totalKendaraan = Kendaraan::count();
        $totalPerusahaan = Perusahaan::count();
        $recentRiwayat = RiwayatPerjalanan::with('alat', 'kendaraan', 'perusahaan')->orderBy('created_at', 'desc')->limit(10)->get();
        $activeSessions = SesiAlat::with('alat', 'kendaraan', 'perusahaan')->where('aktif', true)->get();
        $latestRiwayatByAlat = RiwayatPerjalanan::with('alat', 'kendaraan', 'perusahaan')
            ->whereIn('id', function($query) {
                $query->selectRaw('MAX(id)')
                    ->from('riwayat_perjalanan')
                    ->groupBy('id_alat');
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('pages.admin.dashboards.index', [
            'totalSesiAlatAktif' => $totalSesiAlatAktif,
            'totalAlat' => $totalAlat,
            'totalKendaraan' => $totalKendaraan,
            'totalPerusahaan' => $totalPerusahaan,
            'recentRiwayat' => $recentRiwayat,
            'activeSessions' => $activeSessions,
            'latestRiwayatByAlat' => $latestRiwayatByAlat,
        ]);
    }

}
