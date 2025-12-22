<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use App\Models\SesiAlat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SesiAlatController extends Controller
{
    /**
     * Display a listing of the resource for current perusahaan.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $filter_status = $request->input('status', '');
        
        // Get current perusahaan ID from authenticated user
        $idPerusahaan = Auth::user()->id_perusahaan;

        $sesiAlat = SesiAlat::with('alat', 'kendaraan', 'perusahaan', 'riwayatPerjalanan')
            ->where('id_perusahaan', $idPerusahaan);

        if ($search) {
            $sesiAlat->whereHas('alat', function ($q) use ($search) {
                $q->where('id', 'like', '%' . $search . '%');
            })->orWhereHas('kendaraan', function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%');
            });
        }

        if ($filter_status !== '') {
            $sesiAlat->where('aktif', (bool)$filter_status);
        }

        $sesiAlat = $sesiAlat
            ->orderBy('id_alat', 'asc')
            ->orderBy('aktif', 'desc')
            ->paginate(10)
            ->appends(request()->query());

        return view('pages.perusahaan.sesi-alat.index', compact('sesiAlat', 'search', 'filter_status'));
    }

    /**
     * Display the specified resource.
     */
    public function show(SesiAlat $sesiAlat)
    {
        // Check if sesi alat belongs to current perusahaan
        if ($sesiAlat->id_perusahaan !== auth()->user()->id_perusahaan) {
            abort(403, 'Unauthorized access');
        }

        $sesiAlat->load('alat', 'kendaraan', 'perusahaan');

        // Load riwayat perjalanan with pagination
        $riwayatPerjalanan = $sesiAlat->riwayatPerjalanan()->orderBy('created_at')->get();

        return view('pages.perusahaan.sesi-alat.show', compact('sesiAlat', 'riwayatPerjalanan'));
    }
}
