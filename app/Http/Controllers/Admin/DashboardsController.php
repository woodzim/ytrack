<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SesiAlat;
use App\Models\RiwayatPerjalanan;
use App\Models\Alat;
use App\Models\Kendaraan;
use App\Models\Perusahaan;
use App\Models\ActivityLog;

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
        
        // 10 data log aktivitas
        $activityLogs = ActivityLog::with('user')->orderBy('created_at', 'desc')->limit(10)->get();
        
        // 10 data error tracking (tracking tidak lengkap)
        $errorTrackings = RiwayatPerjalanan::uncompleted()
            ->with(['alat', 'kendaraan', 'perusahaan'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Data for charts
        // 1. Perjalanan per hari (7 hari terakhir)
        $riwayatChart = RiwayatPerjalanan::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 2. Distribusi Kendaraan per Perusahaan
        $perusahaanChart = Perusahaan::withCount('kendaraan')->limit(5)->get();

        return view('pages.admin.dashboards.index', [
            'totalSesiAlatAktif' => $totalSesiAlatAktif,
            'totalAlat' => $totalAlat,
            'totalKendaraan' => $totalKendaraan,
            'totalPerusahaan' => $totalPerusahaan,
            'recentRiwayat' => $recentRiwayat,
            'activeSessions' => $activeSessions,
            'activityLogs' => $activityLogs,
            'errorTrackings' => $errorTrackings,
            'riwayatChart' => $riwayatChart,
            'perusahaanChart' => $perusahaanChart,
        ]);
    }

}
