<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SesiAlat;
use App\Models\Alat;
use App\Models\Kendaraan;
use App\Models\Perusahaan;
use Illuminate\Http\Request;

class SesiAlatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $filter_status = $request->input('status', '');

        $sesiAlat = SesiAlat::with('alat', 'kendaraan', 'perusahaan', 'riwayatPerjalanan');

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

        return view('pages.admin.sesi-alat.index', compact('sesiAlat', 'search', 'filter_status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $alat = Alat::all();
        $kendaraan = Kendaraan::all();
        $perusahaan = Perusahaan::all();

        return view('pages.admin.sesi-alat.create', compact('alat', 'kendaraan', 'perusahaan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'nullable|string|max:255',
            'id_alat' => 'required|exists:alat,id',
            'id_kendaraan' => 'required|exists:kendaraan,id',
            'id_perusahaan' => 'required|exists:perusahaan,id',
            'aktif' => 'boolean',
        ]);

        // Check if alat is already in use with an active session
        $activeAlatSession = SesiAlat::where('id_alat', $validated['id_alat'])
            ->where('aktif', true)
            ->first();

        if ($activeAlatSession) {
            return back()->withInput()->with('error', 'Alat ini sudah digunakan oleh sesi lain yang aktif. Hentikan sesi sebelumnya terlebih dahulu.');
        }

        // Check if kendaraan is already in use with an active session
        $activeKendaraanSession = SesiAlat::where('id_kendaraan', $validated['id_kendaraan'])
            ->where('aktif', true)
            ->first();

        if ($activeKendaraanSession) {
            return back()->withInput()->with('error', 'Kendaraan ini sudah digunakan oleh sesi lain yang aktif. Hentikan sesi sebelumnya terlebih dahulu.');
        }

        SesiAlat::create($validated);

        return redirect()->route('sesi-alat.index')->with('success', 'Sesi Alat berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(SesiAlat $sesiAlat)
    {
        $sesiAlat->load('alat', 'kendaraan', 'perusahaan');

        // Load riwayat perjalanan with pagination
        $riwayatPerjalanan = $sesiAlat->riwayatPerjalanan()->orderBy('created_at')->get();

        return view('pages.admin.sesi-alat.show', compact('sesiAlat', 'riwayatPerjalanan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SesiAlat $sesiAlat)
    {
        $alat = Alat::all();
        $kendaraan = Kendaraan::all();
        $perusahaan = Perusahaan::all();

        return view('pages.admin.sesi-alat.edit', compact('sesiAlat', 'alat', 'kendaraan', 'perusahaan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SesiAlat $sesiAlat)
    {
        $validated = $request->validate([
            'nama' => 'nullable|string|max:255',
            'id_alat' => 'required|exists:alat,id',
            'id_kendaraan' => 'required|exists:kendaraan,id',
            'id_perusahaan' => 'required|exists:perusahaan,id',
            'aktif' => 'boolean',
        ]);

        // Check if alat is already in use by another active session
        if ($validated['id_alat'] !== $sesiAlat->id_alat) {
            $activeAlatSession = SesiAlat::where('id_alat', $validated['id_alat'])
                ->where('aktif', true)
                ->where('id', '!=', $sesiAlat->id)
                ->first();

            if ($activeAlatSession) {
                return back()->withInput()->with('error', 'Alat ini sudah digunakan oleh sesi lain yang aktif.');
            }
        }

        // Check if kendaraan is already in use by another active session
        if ($validated['id_kendaraan'] !== $sesiAlat->id_kendaraan) {
            $activeKendaraanSession = SesiAlat::where('id_kendaraan', $validated['id_kendaraan'])
                ->where('aktif', true)
                ->where('id', '!=', $sesiAlat->id)
                ->first();

            if ($activeKendaraanSession) {
                return back()->withInput()->with('error', 'Kendaraan ini sudah digunakan oleh sesi lain yang aktif.');
            }
        }

        $sesiAlat->update($validated);

        return redirect()->route('sesi-alat.index')->with('success', 'Sesi Alat berhasil diperbarui');
    }

    /**
     * Toggle aktif status
     */
    public function toggle(SesiAlat $sesiAlat)
    {
        // If trying to activate, check conflicts
        if (!$sesiAlat->aktif) {
            $activeAlatSession = SesiAlat::where('id_alat', $sesiAlat->id_alat)
                ->where('aktif', true)
                ->first();

            if ($activeAlatSession) {
                return back()->with('error', 'Alat ini sudah digunakan oleh sesi lain yang aktif.');
            }

            $activeKendaraanSession = SesiAlat::where('id_kendaraan', $sesiAlat->id_kendaraan)
                ->where('aktif', true)
                ->first();

            if ($activeKendaraanSession) {
                return back()->with('error', 'Kendaraan ini sudah digunakan oleh sesi lain yang aktif.');
            }
        }

        $sesiAlat->update(['aktif' => !$sesiAlat->aktif]);

        $status = $sesiAlat->aktif ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', 'Sesi Alat berhasil ' . $status);
    }

    /**
     * Get kendaraan by perusahaan (for AJAX)
     */
    public function getKendaraanByPerusahaan($idPerusahaan)
    {
        $kendaraan = Kendaraan::where('id_perusahaan', $idPerusahaan)->get();
        return response()->json($kendaraan);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SesiAlat $sesiAlat)
    {
        $sesiAlat->delete();

        return redirect()->route('sesi-alat.index')->with('success', 'Sesi Alat berhasil dihapus');
    }
}
