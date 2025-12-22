<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use App\Models\Perusahaan;
use App\Models\SesiAlat;
use App\Models\Kendaraan;
use App\Models\RiwayatPerjalanan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard for perusahaan.
     */
    public function index()
    {
        $idPerusahaan = auth()->user()->id_perusahaan;
        
        // Get perusahaan data
        $perusahaan = Perusahaan::find($idPerusahaan);
        
        // Get total sesi alat for this perusahaan
        $totalSesiAlat = SesiAlat::where('id_perusahaan', $idPerusahaan)->count();
        
        // Get total kendaraan for this perusahaan
        $totalKendaraan = Kendaraan::where('id_perusahaan', $idPerusahaan)->count();
        
        // Get kendaraan dalam perjalanan (has active sesi alat)
        $kendaraanDalamPerjalanan = Kendaraan::where('id_perusahaan', $idPerusahaan)
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
        
        return view('pages.perusahaan.dashboard.index', [
            'perusahaan' => $perusahaan,
            'totalSesiAlat' => $totalSesiAlat,
            'totalKendaraan' => $totalKendaraan,
            'kendaraanDalamPerjalanan' => $kendaraanDalamPerjalanan,
            'recentRiwayat' => $recentRiwayat,
        ]);
    }

    /**
     * Update perusahaan profile.
     */
    public function update(Request $request)
    {
        $idPerusahaan = auth()->user()->id_perusahaan;
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
        ], [
            'nama.required' => 'Nama perusahaan wajib diisi',
            'alamat.required' => 'Alamat perusahaan wajib diisi',
        ]);
        
        $perusahaan = Perusahaan::find($idPerusahaan);
        $perusahaan->update($validated);
        
        return back()->with('success', 'Data perusahaan berhasil diperbarui');
    }
}
