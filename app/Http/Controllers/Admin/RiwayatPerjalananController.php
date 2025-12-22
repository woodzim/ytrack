<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiwayatPerjalanan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RiwayatPerjalananController extends Controller
{
    /**
     * Display list of riwayat perjalanan grouped by alat ID
     * (Only show records with id_sesi_alat null and id_alat not null)
     */
    public function index(Request $request)
    {
        $query = RiwayatPerjalanan::whereNull('id_sesi_alat')
            ->whereNotNull('id_alat');

        // Search functionality
        $search = $request->get('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id_alat', 'like', "%$search%")
                  ->orWhere('lat', 'like', "%$search%")
                  ->orWhere('long', 'like', "%$search%");
            });
        }

        // Get data and group by id_alat
        $riwayatRaw = $query->latest('created_at')->get();

        // Group by id_alat
        $riwayatGrouped = $riwayatRaw->groupBy('id_alat');

        // Get summary for each alat
        $alatSummary = $riwayatRaw->groupBy('id_alat')->map(function ($group) {
            return [
                'id_alat' => $group->first()->id_alat,
                'count' => $group->count(),
                'first_time' => $group->max('created_at')->translatedFormat('d F Y H:i:s'),
                'last_time' => $group->min('created_at')->translatedFormat('d F Y H:i:s'),
            ];
        })->sortByDesc('count');

        return view('pages.admin.riwayat-perjalanan.index', [
            'riwayatGrouped' => $riwayatGrouped,
            'alatSummary' => $alatSummary,
            'search' => $search,
            'totalRecords' => $riwayatRaw->count(),
        ]);
    }

    /**
     * Show detail riwayat perjalanan for a specific alat ID
     */
    public function show($idAlat)
    {
        // Get all tracking data for this alat
        $riwayat = RiwayatPerjalanan::whereNull('id_sesi_alat')
            ->where('id_alat', $idAlat)
            ->latest('created_at')
            ->paginate(20);

        if ($riwayat->isEmpty() && $riwayat->currentPage() == 1) {
            abort(404, 'Tidak ada data tracking untuk alat ini');
        }

        $totalCount = RiwayatPerjalanan::whereNull('id_sesi_alat')
            ->where('id_alat', $idAlat)
            ->count();

        return view('pages.admin.riwayat-perjalanan.show', [
            'riwayat' => $riwayat,
            'idAlat' => $idAlat,
            'totalCount' => $totalCount,
        ]);
    }

    /**
     * Show edit form for riwayat perjalanan
     */
    public function edit($id)
    {
        $riwayat = RiwayatPerjalanan::findOrFail($id);
        $perusahaan = \App\Models\Perusahaan::all();
        $kendaraan = \App\Models\Kendaraan::all();

        return view('pages.admin.riwayat-perjalanan.edit', [
            'riwayat' => $riwayat,
            'perusahaan' => $perusahaan,
            'kendaraan' => $kendaraan,
        ]);
    }

    /**
     * Update riwayat perjalanan
     */
    public function update(Request $request, $id)
    {
        $riwayat = RiwayatPerjalanan::findOrFail($id);

        $validated = $request->validate([
            'id_perusahaan' => 'nullable|exists:perusahaan,id',
            'id_kendaraan' => 'nullable|exists:kendaraan,id',
            'lat' => 'required|string',
            'long' => 'required|string',
            'alt' => 'nullable|string',
            'sat' => 'nullable|integer',
            'speed' => 'nullable|integer',
        ]);

        try {
            $riwayat->update($validated);

            return redirect()
                ->route('riwayat-perjalanan.show', $riwayat->id_alat)
                ->with('success', 'Data tracking berhasil diperbarui');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Delete riwayat perjalanan
     */
    public function destroy($id)
    {
        $riwayat = RiwayatPerjalanan::findOrFail($id);
        $idAlat = $riwayat->id_alat;

        try {
            $riwayat->delete();

            return redirect()
                ->route('riwayat-perjalanan.show', $idAlat)
                ->with('success', 'Data tracking berhasil dihapus');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
