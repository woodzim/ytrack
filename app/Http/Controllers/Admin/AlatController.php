<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        
        $alat = Alat::with('sesiAlat');
        
        if ($search) {
            $alat->where('id', 'like', '%' . $search . '%');
        }
        
        $alat = $alat->paginate(10)->appends(request()->query());
        
        return view('pages.admin.alat.index', compact('alat', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.alat.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|string|max:255|unique:alat,id',
        ], [
            'id.required' => 'ID harus diisi',
            'id.string' => 'ID harus berupa teks',
            'id.max' => 'ID maksimal 255 karakter',
            'id.unique' => 'ID sudah terdaftar',
        ]);

        Alat::create($validated);

        return redirect()->route('alat.index')->with('success', 'Alat berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Alat $alat)
    {
        return view('pages.admin.alat.show', compact('alat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alat $alat)
    {
        return view('pages.admin.alat.edit', compact('alat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alat $alat)
    {
        $validated = $request->validate([
            'id' => 'required|string|max:255|unique:alat,id,' . $alat->id . ',id',
        ], [
            'id.required' => 'ID harus diisi',
            'id.string' => 'ID harus berupa teks',
            'id.max' => 'ID maksimal 255 karakter',
            'id.unique' => 'ID sudah terdaftar',
        ]);

        $alat->update($validated);

        return redirect()->route('alat.index')->with('success', 'Alat berhasil diperbarui');
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
            return redirect()->route('alat.index')->with('error', 'Gagal menghapus alat. Pastikan tidak ada sesi yang menggunakan alat ini.');
        }
    }
}
