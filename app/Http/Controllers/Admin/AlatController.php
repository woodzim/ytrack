<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Perusahaan;
use App\Models\ActivityLog; // <-- TAMBAHAN 1: Import Model Log
use Illuminate\Support\Facades\Auth; // <-- TAMBAHAN 2: Import Auth
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AlatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $id_perusahaan = $request->input('id_perusahaan', '');
        $status = $request->input('status', ''); // 1: digunakan, 0: tersedia

        $query = Alat::with(['sesiAlat', 'perusahaan'])
            ->withExists(['sesiAlat as sedang_digunakan' => function ($q) {
                $q->where('aktif', true);
            }]);

        if ($request->filled('search')) {
            $query->where(function($q) use ($search) {
                $q->where('alat.id', 'like', '%' . $search . '%')      
                    ->orWhereHas('perusahaan', function($sq) use ($search) {
                        $sq->where('nama', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('id_perusahaan')) {
            $query->where('id_perusahaan', $id_perusahaan);
        }

        if ($request->filled('status')) {
            if ($status == '1') {
                $query->whereHas('sesiAlat', function($q) {
                    $q->where('aktif', true);
                });
            } else {
                $query->whereDoesntHave('sesiAlat', function($q) {
                    $q->where('aktif', true);
                });
            }
        }
        
        $alat = $query->latest()->paginate(10)->appends($request->query());
        $perusahaan = Perusahaan::all();

        if ($request->ajax()) {
            return view('pages.admin.alat.table', compact('alat', 'search', 'perusahaan', 'id_perusahaan', 'status'))->render();
        }

        return view('pages.admin.alat.index', compact('alat', 'search', 'perusahaan', 'id_perusahaan', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $listPerusahaan = Perusahaan::all();
        return view('pages.admin.alat.create', compact('listPerusahaan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id'            => 'required|string|max:255|unique:alat,id',
            'id_perusahaan' => 'nullable|exists:perusahaan,id',        
        ], [
            'id.required'          => 'ID Perangkat harus diisi',
            'id.unique'            => 'ID Perangkat ini sudah terdaftar',
            'id_perusahaan.exists' => 'Data perusahaan tidak valid',
        ]);

        // Simpan Data Alat
        $alat = Alat::create($validated);

        // --- LOGIC PENYUSUNAN DESKRIPSI ---
        $deskripsiLog = "Admin menambahkan alat baru (SN: " . $validated['id'] . ")";

        // Cek apakah user memilih perusahaan
        if (!empty($validated['id_perusahaan'])) {
            // Ambil nama perusahaan untuk log agar mudah dibaca
            $namaPerusahaan = \App\Models\Perusahaan::find($validated['id_perusahaan'])->nama;
            $deskripsiLog .= " dan ditempatkan di " . $namaPerusahaan;
        } else {
            $deskripsiLog .= " (Belum ditempatkan di perusahaan)";
        }

        // --- SIMPAN LOG ---
        ActivityLog::create([
            'user_id'      => \Illuminate\Support\Facades\Auth::id(),
            'action'       => 'CREATE',
            'subject_type' => Alat::class,
            'subject_id'   => null, // Biarkan null karena ID Alat adalah String (kecuali Anda ubah struktur tabel log)
            'description'  => $deskripsiLog,
            'ip_address'   => $request->ip(),
        ]);
        // -----------------------------

        return redirect()->route('alat.index')->with('success', 'Alat berhasil ditambahkan.');    
    }

    /**
     * Display the specified resource.
     */
    public function show(Alat $alat)
    {
        $alat->load(['perusahaan', 'sesiAlat']);
        return view('pages.admin.alat.show', compact('alat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alat $alat)
    {
        $listPerusahaan = Perusahaan::orderBy('nama', 'asc')->get();
        return view('pages.admin.alat.edit', compact('alat', 'listPerusahaan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alat $alat)
    {
        $validated = $request->validate([
            'id' => [
                'required', 
                'string', 
                'max:255', 
                'unique:alat,id,' . $alat->id
            ],
            'id_perusahaan' => 'nullable|exists:perusahaan,id',
        ], [
            'id.required' => 'Serial Number / ID Alat harus diisi',
            'id.unique'   => 'Serial Number ini sudah digunakan oleh alat lain',
        ]);

        // --- 1. SIMPAN DATA LAMA (BEFORE) ---
        $oldId = $alat->id;
        $oldPerusahaanId = $alat->id_perusahaan;
        // Ambil nama perusahaan lama (jika ada)
        $oldPerusahaanNama = $alat->perusahaan ? $alat->perusahaan->nama : 'Belum ditempatkan';

        // --- 2. LAKUKAN UPDATE ---
        $alat->update($validated);

        // --- 3. LOGIC PENYUSUNAN DESKRIPSI LOG ---
        // Refresh relasi untuk mendapatkan data perusahaan baru setelah update
        $alat->load('perusahaan'); 
        
        $changes = [];

        // Cek apakah SN berubah
        if ($oldId !== $validated['id']) {
            $changes[] = "SN berubah dari [$oldId] menjadi [" . $validated['id'] . "]";
        }

        // Cek apakah Perusahaan berubah
        if ($oldPerusahaanId != $validated['id_perusahaan']) {
            $newPerusahaanNama = $alat->perusahaan ? $alat->perusahaan->nama : 'Belum ditempatkan';
            $changes[] = "Lokasi dipindahkan dari [$oldPerusahaanNama] ke [$newPerusahaanNama]";
        }

        // Jika tidak ada perubahan sama sekali (jarang terjadi karena tombol submit biasanya disable jika dirty, tapi buat jaga-jaga)
        if (empty($changes)) {
            $desc = "Admin memperbarui data alat (Tidak ada perubahan signifikan). ID: $oldId";
        } else {
            // Gabungkan array perubahan menjadi kalimat
            $desc = "Admin memperbarui alat ($oldId). Detail: " . implode(', ', $changes) . ".";
        }

        // --- 4. SIMPAN LOG ---
        ActivityLog::create([
            'user_id'      => Auth::id(),
            'action'       => 'UPDATE',
            'subject_type' => Alat::class,
            'subject_id'   => null, // Null karena ID Alat adalah String
            'description'  => $desc,
            'ip_address'   => $request->ip(),
        ]);
        // -----------------------------

        return redirect()->route('alat.index')->with('success', 'Data alat berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alat $alat)
    {
        try {
            $alatId = $alat->id; // Simpan ID sebelum dihapus
            
            $alat->delete();

            // --- TAMBAHAN LOG: DELETE ---
            ActivityLog::create([
                'user_id'      => Auth::id(),
                'action'       => 'DELETE',
                'subject_type' => Alat::class,
                'subject_id'   => null,
                'description'  => "Admin menghapus alat dengan ID: $alatId",
                'ip_address'   => request()->ip(),
            ]);
            // -----------------------------

            return redirect()->route('alat.index')->with('success', "Alat '$alatId' berhasil dihapus");
            
        } catch (\Exception $e) {
            return redirect()->route('alat.index')->with('error', 'Gagal menghapus alat. Kemungkinan alat ini masih terhubung dengan Sesi Aktif atau riwayat perjalanan.');
        }
    }
}