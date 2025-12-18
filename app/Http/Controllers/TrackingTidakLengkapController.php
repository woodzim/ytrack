<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPerjalanan;
use Illuminate\Http\Request;

class TrackingTidakLengkapController extends Controller
{
    /**
     * Display a listing of incomplete tracking data.
     */
    public function index()
    {
        $incompleteTrackings = RiwayatPerjalanan::where(function ($query) {
            $query->whereNull('id_sesi_alat')
                ->orWhereNull('id_alat')
                ->orWhereNull('id_kendaraan')
                ->orWhereNull('id_perusahaan');
        })
        ->with(['sesiAlat', 'alat', 'kendaraan', 'perusahaan'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        return view('pages.tracking-tidak-lengkap.index', [
            'incompleteTrackings' => $incompleteTrackings
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RiwayatPerjalanan $trackingTidakLengkap)
    {
        $riwayat = $trackingTidakLengkap;
        
        return view('pages.tracking-tidak-lengkap.edit', [
            'riwayat' => $riwayat
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RiwayatPerjalanan $trackingTidakLengkap)
    {
        $validated = $request->validate([
            'id_sesi_alat' => 'nullable|integer|exists:sesi_alat,id',
            'id_alat' => 'nullable|string|exists:alat,id',
            'id_kendaraan' => 'nullable|integer|exists:kendaraan,id',
            'id_perusahaan' => 'nullable|integer|exists:perusahaan,id',
        ]);

        $trackingTidakLengkap->update($validated);

        return redirect()
            ->route('tracking-tidak-lengkap.index')
            ->with('success', 'Data tracking berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RiwayatPerjalanan $trackingTidakLengkap)
    {
        $trackingTidakLengkap->delete();

        return redirect()
            ->route('tracking-tidak-lengkap.index')
            ->with('success', 'Data tracking berhasil dihapus');
    }
}
