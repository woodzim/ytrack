<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perusahaan;
use App\Models\Kendaraan;
use App\Models\JenisKendaraan;
use App\Models\SesiAlat;
use App\Models\ActivityLog; // <-- TAMBAHAN 1: Import Model Log
use Illuminate\Support\Facades\Auth; // <-- TAMBAHAN 2: Import Auth
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $id_perusahaan = $request->input('id_perusahaan');
        $status = $request->input('status'); // 'digunakan' or 'tersedia'

        $query = Kendaraan::with(['perusahaan', 'jenisKendaraan'])
            ->withExists(['sesiAlat as sedang_digunakan' => function ($q) {
                $q->where('aktif', true);
            }]);

        if ($request->filled('search')) {
            $query->where(function($q) use ($search) {
                $q->where('kendaraan.nama', 'like', '%' . $search . '%')
                    ->orWhere('kendaraan.plat', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('id_perusahaan')) {
            $query->where('id_perusahaan', $id_perusahaan);
        }

        if ($request->filled('status')) {
            if ($status === 'digunakan') {
                $query->whereHas('sesiAlat', function($q) {
                    $q->where('aktif', true);
                });
            } elseif ($status === 'tersedia') {
                $query->whereDoesntHave('sesiAlat', function($q) {
                    $q->where('aktif', true);
                });
            }
        }

        $kendaraan = $query->latest()
                            ->paginate(10)
                            ->appends($request->query());

        $perusahaan = Perusahaan::all();

        if ($request->ajax()) {
            return view('pages.admin.kendaraan.table', compact('kendaraan', 'search', 'perusahaan', 'id_perusahaan', 'status'))->render();
        }

        return view('pages.admin.kendaraan.index', compact('kendaraan', 'search', 'perusahaan', 'id_perusahaan', 'status'));
    }

    public function create()
    {
        $perusahaan = Perusahaan::all();
        $jenisKendaraan = JenisKendaraan::all();

        return view('pages.admin.kendaraan.create', compact('perusahaan', 'jenisKendaraan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_perusahaan'      => 'required|exists:perusahaan,id',
            'id_jenis_kendaraan' => 'required|exists:jenis_kendaraan,id',
            'nama'               => 'required|string|max:255',
            'plat'               => 'required|string|max:20|unique:kendaraan,plat',
        ], [
            'id_perusahaan.required'      => 'Perusahaan harus dipilih',
            'id_jenis_kendaraan.required' => 'Jenis kendaraan harus dipilih',
            'nama.required'               => 'Nama kendaraan harus diisi',
            'plat.required'               => 'Nomor plat harus diisi',
            'plat.unique'                 => 'Nomor plat ini sudah terdaftar',
        ]);

        // UBAH DISINI: Tampung hasil create ke variabel $kendaraan
        $kendaraan = Kendaraan::create($validated);

        // --- TAMBAHAN LOG: CREATE ---
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'CREATE',
            'subject_type' => Kendaraan::class,
            'subject_id' => $kendaraan->id,
            'description' => "Menambahkan kendaraan baru: {$kendaraan->nama} ({$kendaraan->plat})",
            'ip_address' => $request->ip(),
        ]);
        // -----------------------------

        return redirect()->route('kendaraan.index')
            ->with('success', 'Kendaraan berhasil ditambahkan');
    }

    public function edit(Kendaraan $kendaraan)
    {
        $perusahaan = Perusahaan::all();
        $jenisKendaraan = JenisKendaraan::all();

        return view('pages.admin.kendaraan.edit', compact('kendaraan', 'perusahaan', 'jenisKendaraan'));
    }

    public function update(Request $request, Kendaraan $kendaraan)
    {
        $validated = $request->validate([
            'id_perusahaan'      => 'required|exists:perusahaan,id',
            'id_jenis_kendaraan' => 'required|exists:jenis_kendaraan,id',
            'nama'               => 'required|string|max:255',
            'plat'               => 'required|string|max:20|unique:kendaraan,plat,' . $kendaraan->id,
        ], [
            'id_perusahaan.required'      => 'Perusahaan harus dipilih',
            'id_jenis_kendaraan.required' => 'Jenis kendaraan harus dipilih',
            'plat.unique'                 => 'Nomor plat ini sudah digunakan kendaraan lain',
        ]);

        // Simpan nama lama untuk log
        $oldInfo = "{$kendaraan->nama} ({$kendaraan->plat})";

        $kendaraan->update($validated);

        // --- TAMBAHAN LOG: UPDATE ---
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE',
            'subject_type' => Kendaraan::class,
            'subject_id' => $kendaraan->id,
            'description' => "Memperbarui data kendaraan. Dari [$oldInfo] menjadi [{$kendaraan->nama} ({$kendaraan->plat})]",
            'ip_address' => $request->ip(),
        ]);
        // -----------------------------

        return redirect()->route('kendaraan.index')
            ->with('success', 'Data kendaraan berhasil diperbarui');
    }

    public function destroy(Kendaraan $kendaraan)
    {
        $sedangDigunakan = SesiAlat::where('id_kendaraan', $kendaraan->id)
                                    ->where('aktif', true)
                                    ->exists();

        if ($sedangDigunakan) {
            return redirect()->route('kendaraan.index')
                ->with('error', "Gagal menghapus! Kendaraan '{$kendaraan->nama}' sedang digunakan dalam sesi aktif.");
        }

        try {
            $info = $kendaraan->nama . ' (' . $kendaraan->plat . ')';
            $id = $kendaraan->id; // Simpan ID sebelum dihapus
            
            $kendaraan->delete();

            // --- TAMBAHAN LOG: DELETE ---
            // Kita log setelah delete berhasil (di dalam try)
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'DELETE',
                'subject_type' => Kendaraan::class,
                'subject_id' => $id,
                'description' => "Menghapus kendaraan: {$info}",
                'ip_address' => request()->ip(),
            ]);
            // -----------------------------

            return redirect()->route('kendaraan.index')
                ->with('success', "Kendaraan $info berhasil dihapus.");
                
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == "23000") {
                return redirect()->route('kendaraan.index')
                    ->with('error', "Data tidak bisa dihapus karena memiliki riwayat perjalanan.");
            }

            return redirect()->route('kendaraan.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data.');
        } catch (\Exception $e) {
            return redirect()->route('kendaraan.index')
                ->with('error', 'Gagal menghapus data kendaraan.');
        }
    }
}