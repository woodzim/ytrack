<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Perusahaan;
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
        $alat = Alat::with(['sesiAlat', 'perusahaan']);

        if ($search) {
            $alat->where(function($query) use ($search) {
                // Cari berdasarkan ID Alat atau Nama Perusahaan
                $query->where('id', 'like', '%' . $search . '%')      
                    ->orWhereHas('perusahaan', function($q) use ($search) {
                        $q->where('nama', 'like', '%' . $search . '%');
                    });
            });
        }
        
        // Urutkan berdasarkan data terbaru (created_at desc)
        $alat = $alat->latest()->paginate(10);

        return view('pages.admin.alat.index', compact('alat', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $listPerusahaan = Perusahaan::all();
        
        // Tidak perlu generate kode lagi, langsung return view
        return view('pages.admin.alat.create', compact('listPerusahaan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|string|max:255|unique:alat,id',
            'id_perusahaan' => 'nullable|exists:perusahaan,id',        
        ], [
            'id.required' => 'ID Perangkat harus diisi',
            'id.string' => 'ID harus berupa teks',
            'id.unique' => 'ID Perangkat ini sudah terdaftar',
            'perusahaan_id.exists' => 'Data perusahaan tidak valid',
        ]);

        // Hapus baris generate kode, langsung create
        Alat::create($validated);

        return redirect()->route('alat.index')->with('success', 'Alat berhasil ditambahkan: ' . $validated['id']);    
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
                'unique:alat,id,' . $alat->id // Cara penulisan short syntax untuk ignore ID saat ini
            ],
            'id_perusahaan' => 'nullable|exists:perusahaan,id',
        ], [
            'id.required' => 'Serial Number / ID Alat harus diisi',
            'id.unique' => 'Serial Number ini sudah digunakan oleh alat lain',
        ]);

        $alat->update($validated);

        return redirect()->route('alat.index')->with('success', 'Data alat berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alat $alat)
    {
        try {
            $alatId = $alat->id;
            $alat->delete();
            return redirect()->route('alat.index')->with('success', "Alat '$alatId' berhasil dihapus");
        } catch (\Exception $e) {
            return redirect()->route('alat.index')->with('error', 'Gagal menghapus alat. Kemungkinan alat ini masih terhubung dengan Sesi Aktif atau riwayat perjalanan.');
        }
    }
}