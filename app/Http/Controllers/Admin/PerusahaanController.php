<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perusahaan;
use Illuminate\Http\Request;

class PerusahaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        
        $perusahaan = Perusahaan::query();
        
        if ($search) {
            $perusahaan->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('alamat', 'like', '%' . $search . '%');
        }
        
        $perusahaan = $perusahaan->paginate(10)->appends(request()->query());
        
        return view('pages.admin.perusahaan.index', compact('perusahaan', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.perusahaan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
        ], [
            'nama.required' => 'Nama perusahaan harus diisi',
            'nama.string' => 'Nama perusahaan harus berupa teks',
            'nama.max' => 'Nama perusahaan maksimal 255 karakter',
            'alamat.required' => 'Alamat harus diisi',
            'alamat.string' => 'Alamat harus berupa teks',
            'alamat.max' => 'Alamat maksimal 255 karakter',
        ]);

        Perusahaan::create($validated);

        return redirect()->route('perusahaan.index')->with('success', 'Perusahaan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Perusahaan $perusahaan)
    {
        return view('pages.admin.perusahaan.show', compact('perusahaan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Perusahaan $perusahaan)
    {
        return view('pages.admin.perusahaan.edit', compact('perusahaan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Perusahaan $perusahaan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
        ], [
            'nama.required' => 'Nama perusahaan harus diisi',
            'nama.string' => 'Nama perusahaan harus berupa teks',
            'nama.max' => 'Nama perusahaan maksimal 255 karakter',
            'alamat.required' => 'Alamat harus diisi',
            'alamat.string' => 'Alamat harus berupa teks',
            'alamat.max' => 'Alamat maksimal 255 karakter',
        ]);

        $perusahaan->update($validated);

        return redirect()->route('perusahaan.index')->with('success', 'Perusahaan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Perusahaan $perusahaan)
    {
        try {
            $perusahaanNama = $perusahaan->nama;
            $perusahaan->delete();
            return redirect()->route('perusahaan.index')->with('success', "Perusahaan '$perusahaanNama' berhasil dihapus beserta semua data terkait.");
        } catch (\Exception $e) {
            return redirect()->route('perusahaan.index')->with('error', 'Gagal menghapus perusahaan. Silakan coba lagi.');
        }
    }
}
