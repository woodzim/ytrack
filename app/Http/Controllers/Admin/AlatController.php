<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AlatController extends Controller
{
    private function generateKodeAlat()
    {
        $lastAlat = Alat::where('kode_alat', 'like', 'YTR_%')
                        ->orderByRaw('LENGTH(kode_alat) DESC') 
                        ->orderBy('kode_alat', 'desc')
                        ->first();
        if (!$lastAlat) {
            return 'YTR_001';
        }
        $lastNumber = (int) substr($lastAlat->kode_alat, 4);
        $newNumber = $lastNumber + 1;
        return 'YTR_' . sprintf("%03d", $newNumber);
    }

    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $alat = Alat::with(['sesiAlat', 'perusahaan']);

        if ($search) {
            $alat->where(function($query) use ($search) {
                $query->where('kode_alat', 'like', '%' . $search . '%') 
                    ->orWhere('id', 'like', '%' . $search . '%')      
                    ->orWhereHas('perusahaan', function($q) use ($search) {
                        $q->where('nama', 'like', '%' . $search . '%');
                    });
            });
        }
        
        $alat = $alat->orderByRaw('LENGTH(kode_alat) DESC')->orderBy('kode_alat', 'desc')->paginate(10);

        return view('pages.admin.alat.index', compact('alat', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $listPerusahaan = Perusahaan::all();

        $lastAlat = Alat::where('kode_alat', 'like', 'YTR_%')
                        ->orderByRaw('LENGTH(kode_alat) DESC') 
                        ->orderBy('kode_alat', 'desc')
                        ->first();

        if (!$lastAlat) {
            $nextKode = 'YTR_001';
        } else {
            $lastNumber = (int) substr($lastAlat->kode_alat, 4);
            $nextKode = 'YTR_' . sprintf("%03d", $lastNumber + 1);
        }

        return view('pages.admin.alat.create', compact('listPerusahaan', 'nextKode'));
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

        $validated['kode_alat'] = $this->generateKodeAlat();

        Alat::create($validated);

        return redirect()->route('alat.index')->with('success', 'Alat berhasil ditambahkan dengan kode: ' . $validated['kode_alat']);    
    }

    /**
     * Display the specified resource.
     */
    public function show(Alat $alat)
    {
        // Pastikan relasi diload saat menampilkan detail
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

    public function update(Request $request, Alat $alat)
    {
        $validated = $request->validate([
            // PENGGUNAAN RULE CLASS (Lebih Rapi & Aman)
            // Artinya: Wajib unik di tabel 'alat' kolom 'id', KECUALI id milik alat ini sendiri.
            'id' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('alat', 'id')->ignore($alat->id, 'id') 
            ],
            'id_perusahaan' => 'nullable|exists:perusahaan,id',
        ], [
            'id.required' => 'Serial Number / ID Alat harus diisi',
            'id.unique' => 'Serial Number ini sudah digunakan oleh alat lain',
        ]);

        // Update data (kode_alat tidak ikut ter-update karena tidak ada di $validated, ini sudah benar)
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