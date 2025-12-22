<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KendaraanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get search parameter
        $search = request('search', '');
        
        // Get kendaraan for the logged-in perusahaan
        $kendaraan = Kendaraan::where('id_perusahaan', $user->id_perusahaan)
            ->when($search, function ($query) use ($search) {
                return $query->where('nama', 'like', "%{$search}%");
            })
            ->paginate(10);
        
        return view('pages.perusahaan.kendaraan.index', compact('kendaraan', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.perusahaan.kendaraan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
        ], [
            'nama.required' => 'Nama kendaraan harus diisi',
            'nama.string' => 'Nama kendaraan harus berupa teks',
            'nama.max' => 'Nama kendaraan maksimal 255 karakter',
        ]);

        $user = Auth::user();

        Kendaraan::create([
            'id_perusahaan' => $user->id_perusahaan,
            'nama' => $validated['nama'],
        ]);

        return redirect()->route('kendaraan.index')
                       ->with('success', 'Kendaraan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kendaraan $kendaraan)
    {
        $user = Auth::user();
        
        // Verify that kendaraan belongs to this perusahaan
        if ($kendaraan->id_perusahaan !== $user->id_perusahaan) {
            return abort(403, 'Unauthorized');
        }

        return view('pages.perusahaan.kendaraan.show', compact('kendaraan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kendaraan $kendaraan)
    {
        $user = Auth::user();
        
        // Verify that kendaraan belongs to this perusahaan
        if ($kendaraan->id_perusahaan !== $user->id_perusahaan) {
            return abort(403, 'Unauthorized');
        }

        return view('pages.perusahaan.kendaraan.edit', compact('kendaraan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kendaraan $kendaraan)
    {
        $user = Auth::user();
        
        // Verify that kendaraan belongs to this perusahaan
        if ($kendaraan->id_perusahaan !== $user->id_perusahaan) {
            return abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
        ], [
            'nama.required' => 'Nama kendaraan harus diisi',
            'nama.string' => 'Nama kendaraan harus berupa teks',
            'nama.max' => 'Nama kendaraan maksimal 255 karakter',
        ]);

        $kendaraan->update($validated);

        return redirect()->route('kendaraan.show', $kendaraan->id)
                       ->with('success', 'Kendaraan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kendaraan $kendaraan)
    {
        $user = Auth::user();
        
        // Verify that kendaraan belongs to this perusahaan
        if ($kendaraan->id_perusahaan !== $user->id_perusahaan) {
            return abort(403, 'Unauthorized');
        }

        $kendaraan->delete();

        return redirect()->route('kendaraan.index')
                       ->with('success', 'Kendaraan berhasil dihapus');
    }
}
