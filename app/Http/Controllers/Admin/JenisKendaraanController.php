<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisKendaraan;
use Illuminate\Http\Request;

class JenisKendaraanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $jenisKendaraan = JenisKendaraan::query();
        if ($search) {
            $jenisKendaraan->where('nama', 'like', '%' . $search . '%');
        }
        $jenisKendaraan->orderBy('nama', 'asc');
        $jenisKendaraan = $jenisKendaraan->paginate(10)->appends(request()->query());
        
        return view('pages.admin.jenis-kendaraan.index', compact('jenisKendaraan', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.jenis-kendaraan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:jenis_kendaraan,nama',
            'kategori' => 'required|in:Kendaraan,Container', 
        ], [
            'nama.required' => 'Nama jenis kendaraan harus diisi',
            'nama.unique' => 'Nama jenis kendaraan ini sudah ada di database', 
            'nama.string' => 'Nama jenis kendaraan harus berupa teks',
            'nama.max' => 'Nama jenis kendaraan maksimal 255 karakter',
            'kategori.required' => 'Kategori harus dipilih',
            'kategori.in' => 'Pilihan kategori tidak valid (Harus Kendaraan atau Container)',
        ]);

        JenisKendaraan::create($validated);

        return redirect()->route('jenis-kendaraan.index')
            ->with('success', 'Jenis kendaraan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisKendaraan $jenisKendaraan)
    {
        // Pastikan Anda punya file view show jika diperlukan, atau hapus method ini jika tidak dipakai
        return view('pages.admin.jenis-kendaraan.show', compact('jenisKendaraan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JenisKendaraan $jenisKendaraan)
    {
        return view('pages.admin.jenis-kendaraan.edit', compact('jenisKendaraan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JenisKendaraan $jenisKendaraan)
    {
        // Ubah validasi dari 'keterangan' menjadi 'kategori'
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|in:Kendaraan,Container', 
        ], [
            'nama.required' => 'Nama jenis kendaraan harus diisi',
            'nama.string' => 'Nama jenis kendaraan harus berupa teks',
            'nama.max' => 'Nama jenis kendaraan maksimal 255 karakter',
            'kategori.required' => 'Kategori harus dipilih',
            'kategori.in' => 'Pilihan kategori tidak valid',
        ]);

        // Update data
        $jenisKendaraan->update($validated);

        return redirect()->route('jenis-kendaraan.index')->with('success', 'Jenis kendaraan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JenisKendaraan $jenisKendaraan)
    {
        try {
            $namaJenis = $jenisKendaraan->nama;
            $jenisKendaraan->delete();
            
            return redirect()->route('jenis-kendaraan.index')->with('success', "Jenis '$namaJenis' berhasil dihapus.");
        } catch (\Exception $e) {
            return redirect()->route('jenis-kendaraan.index')->with('error', 'Gagal menghapus data. Pastikan data tidak sedang digunakan.');
        }
    }
}