<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use App\Models\Perusahaan;
use App\Models\SesiAlat;
use App\Models\Kendaraan;
use App\Models\RiwayatPerjalanan;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard for perusahaan.
     */
    public function index()
    {
        $idPerusahaan = Auth::user()->id_perusahaan;
        
        // Get perusahaan data
        $perusahaan = Perusahaan::find($idPerusahaan);
        
        // Get total perjalanan for this perusahaan
        $totalSesiAlat = SesiAlat::where('id_perusahaan', $idPerusahaan)->count();
        
        // Get total kendaraan for this perusahaan
        $totalKendaraan = Kendaraan::where('id_perusahaan', $idPerusahaan)->count();
        
        // Get total alat for this perusahaan
        $totalAlat = Alat::where('id_perusahaan', $idPerusahaan)->count();
        
        // Get kendaraan dalam perjalanan (has active perjalanan)
        $kendaraanDalamPerjalanan = Kendaraan::where('id_perusahaan', $idPerusahaan)
            ->whereHas('sesiAlat', function ($query) {
                $query->where('aktif', true);
            })
            ->count();

        // Get alat dalam perjalanan (active session)
        $alatAktifCount = Alat::where('id_perusahaan', $idPerusahaan)
            ->whereHas('sesiAlat', function ($query) {
                $query->where('aktif', true);
            })
            ->count();
        
        // Get recent riwayat perjalanan for this perusahaan
        $recentRiwayat = RiwayatPerjalanan::where('id_perusahaan', $idPerusahaan)
            ->with('alat', 'kendaraan')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        // Get all tools and their latest location
        $lokasiAlat = Alat::where('id_perusahaan', $idPerusahaan)
            ->with(['riwayatPerjalanan' => function ($query) {
                $query->latest()->limit(1);
            }, 'sesiAlat' => function ($query) {
                $query->with('kendaraan')->latest()->limit(1);
            }])
            ->get()
            ->map(function ($alat) {
                $latestLocation = $alat->riwayatPerjalanan->first();
                $lastSession = $alat->sesiAlat->first();
                
                if (!$latestLocation) return null;

                $isActive = $lastSession && $lastSession->aktif;
                $battery = (int) ($latestLocation->battery ?? 0);

                return [
                    'id_alat' => $alat->id,
                    'serial_number' => $alat->id,
                    'nama_alat' => $lastSession->nama ?? $alat->id,
                    'kendaraan' => $lastSession ? ($lastSession->kendaraan->plat ?? '-') : '-',
                    'lat' => $latestLocation->lat,
                    'long' => $latestLocation->long,
                    'battery' => $battery,
                    'is_low_bat' => $battery <= 20,
                    'last_update' => $latestLocation->created_at->diffForHumans(),
                    'status' => $isActive ? 'Aktif' : 'Tidak Aktif',
                    'id_sesi' => $lastSession ? $lastSession->id : null
                ];
            })
            ->filter()
            ->values();

        $lowBatCount = $lokasiAlat->where('is_low_bat', true)->count();

        // --- CHART DATA ---
        
        // 1. Battery Distribution
        $batteryStats = [
            'good' => 0, // > 70
            'moderate' => 0, // 30 - 70
            'low' => 0 // < 30
        ];
        
        foreach ($lokasiAlat as $alat) {
            $bat = $alat['battery'];
            if ($bat > 70) $batteryStats['good']++;
            elseif ($bat >= 30) $batteryStats['moderate']++;
            else $batteryStats['low']++;
        }

        // 2. Activity Last 7 Days (Records count)
        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $count = RiwayatPerjalanan::where('id_perusahaan', $idPerusahaan)
                ->whereDate('created_at', $date)
                ->count();
            
            $last7Days[] = [
                'date' => Carbon::now()->subDays($i)->format('d M'),
                'count' => $count
            ];
        }

        // 3. Vehicle Status
        $vehicleStatusStats = [
            'moving' => $kendaraanDalamPerjalanan,
            'idle' => max(0, $totalKendaraan - $kendaraanDalamPerjalanan)
        ];

        // 4. Tool Status
        $toolStatusStats = [
            'active' => $alatAktifCount,
            'idle' => max(0, $totalAlat - $alatAktifCount)
        ];
        
        return view('pages.perusahaan.dashboard.index', [
            'perusahaan' => $perusahaan,
            'totalSesiAlat' => $totalSesiAlat,
            'totalKendaraan' => $totalKendaraan,
            'totalAlat' => $totalAlat,
            'kendaraanDalamPerjalanan' => $kendaraanDalamPerjalanan,
            'recentRiwayat' => $recentRiwayat,
            'lokasiAlat' => $lokasiAlat,
            'lowBatCount' => $lowBatCount,
            'batteryStats' => $batteryStats,
            'last7Days' => $last7Days,
            'vehicleStatusStats' => $vehicleStatusStats,
            'toolStatusStats' => $toolStatusStats
        ]);
    }
}
