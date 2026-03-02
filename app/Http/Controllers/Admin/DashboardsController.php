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
use Carbon\Carbon;

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
        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = RiwayatPerjalanan::whereDate('created_at', $date->format('Y-m-d'))->count();
            
            $last7Days[] = [
                'date' => $date->format('Y-m-d'),
                'count' => $count
            ];
        }

        // 2. Distribusi Kendaraan per Perusahaan
        $perusahaanChart = Perusahaan::withCount('kendaraan')->limit(5)->get();

        // Get all tools and their latest location across all companies
        $lokasiAlat = Alat::with(['riwayatPerjalanan' => function ($query) {
                $query->latest()->limit(1);
            }, 'sesiAlat' => function ($query) {
                $query->with(['kendaraan', 'perusahaan'])->latest()->limit(1);
            }])
            ->get()
            ->map(function ($alat) {
                $latestLocation = $alat->riwayatPerjalanan->first();
                $lastSession = $alat->sesiAlat->first();
                
                if (!$latestLocation || !$lastSession) return null;

                $isActive = $lastSession->aktif;
                $battery = (int) ($latestLocation->battery ?? 0);

                return [
                    'id_alat' => $alat->id,
                    'serial_number' => $alat->id,
                    'nama_alat' => $lastSession->nama ?? $alat->id,
                    'perusahaan' => $lastSession->perusahaan->nama ?? 'N/A',
                    'kendaraan' => $lastSession->kendaraan->plat ?? '-',
                    'lat' => $latestLocation->lat,
                    'long' => $latestLocation->long,
                    'battery' => $battery,
                    'is_low_bat' => $battery <= 30,
                    'last_update' => $latestLocation->created_at->diffForHumans(),
                    'status' => $isActive ? 'Aktif' : 'Tidak Aktif',
                    'id_sesi' => $lastSession->id
                ];
            })
            ->filter()
            ->values();

        return view('pages.admin.dashboards.index', [
            'totalSesiAlatAktif' => $totalSesiAlatAktif,
            'totalAlat' => $totalAlat,
            'totalKendaraan' => $totalKendaraan,
            'totalPerusahaan' => $totalPerusahaan,
            'recentRiwayat' => $recentRiwayat,
            'activeSessions' => $activeSessions,
            'activityLogs' => $activityLogs,
            'errorTrackings' => $errorTrackings,
            'last7Days' => $last7Days,
            'perusahaanChart' => $perusahaanChart,
            'lokasiAlat' => $lokasiAlat,
        ]);
    }

}
