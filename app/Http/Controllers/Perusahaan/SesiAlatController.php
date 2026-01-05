<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Alat;
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
        
        $idPerusahaan = Auth::user()->id_perusahaan;

        $sesiAlat = SesiAlat::with('alat', 'kendaraan', 'perusahaan', 'riwayatPerjalanan')
            ->where('id_perusahaan', $idPerusahaan);

        if ($search) {
            $sesiAlat->whereHas('alat', function ($q) use ($search) {
                $q->where('id', 'like', '%' . $search . '%');
            })->orWhereHas('kendaraan', function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%');
            })->orWhereHas('perusahaan', function ($q) use ($search) {
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
                    $exists = SesiAlat::where('id_alat', $value) // Disini id_alat adalah string ID/SN
                        ->where('aktif', true)
                        ->exists();
                    if ($exists) {
                        $fail('Alat GPS ini sedang digunakan di sesi lain.');
                    }
                },
            ],
            'aktif' => 'boolean'
        ]);

        if ($request->has('aktif') && $request->aktif == 1) {
            SesiAlat::where('id_alat', $request->id_alat)
                    ->where('aktif', 1)
                    ->update(['aktif' => 0]);
        }

        SesiAlat::create([
            'id_perusahaan' => Auth::user()->id_perusahaan,
            'id_kendaraan'  => $request->id_kendaraan,
            'id_alat'       => $request->id_alat,
            'nama'          => $request->nama,
            'aktif'         => $request->has('aktif') ? 1 : 0,
        ]);

        return redirect()->route('perusahaan.sesi-alat.index')
                        ->with('success', 'Sesi alat berhasil dibuat.');
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

        $riwayatPerjalanan = $sesiAlat->riwayatPerjalanan()->orderBy('created_at', 'desc')->get();

        return view('pages.perusahaan.sesi-alat.show', compact('sesiAlat', 'riwayatPerjalanan'));
    }

    /**
     * Toggle Status (Aktif/Nonaktif).
     * Method ini dipanggil tombol di Index
     */
    public function toggle($id)
    {
        $sesi = SesiAlat::where('id', $id)
            ->where('id_perusahaan', Auth::user()->id_perusahaan)
            ->firstOrFail();

        if (!$sesi->aktif) { 
            
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

        $sesi->delete();

        return redirect()->route('perusahaan.sesi-alat.index')
                            ->with('success', 'Sesi berhasil dihapus.');
    }
}