<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\KendaraanImage;
use App\Models\JenisKendaraan;
use App\Models\SesiAlat;
use App\Models\ActivityLog;
use App\Services\ImageCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class KendaraanController extends Controller
{
    public function __construct(protected ImageCompressionService $compressionService) {}

    /**
     * Menampilkan daftar kendaraan milik perusahaan yang sedang login.
     */
    public function index(Request $request)
    {
        $idPerusahaan = Auth::user()->id_perusahaan;
        $search = $request->input('search');
        $filter_status = $request->input('status');

        $query = Kendaraan::where('id_perusahaan', $idPerusahaan)
            ->with(['jenisKendaraan', 'images'])
            ->withExists(['sesiAlat as sedang_digunakan' => function ($q) {
                $q->where('aktif', true);
            }]);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('plat', 'like', '%' . $search . '%')
                    ->orWhere('nama', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('status') && $request->input('status') !== null && $request->input('status') !== '') {
            $filter_status = $request->input('status');
            if ($filter_status == '1') {
                $query->whereHas('sesiAlat', function($q) {
                    $q->where('aktif', true);
                });
            } elseif ($filter_status == '0') {
                $query->whereDoesntHave('sesiAlat', function($q) {
                    $q->where('aktif', true);
                });
            }
        }

        $kendaraan = $query->latest()->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return view('pages.perusahaan.kendaraan.table', compact('kendaraan', 'search', 'filter_status'))->render();
        }

        return view('pages.perusahaan.kendaraan.index', compact('kendaraan', 'search', 'filter_status'));
    }

    /**
     * Form tambah kendaraan.
     */
    public function create()
    {
        $jenisKendaraan = JenisKendaraan::all();
        return view('pages.perusahaan.kendaraan.create', compact('jenisKendaraan'));
    }

    /**
     * Simpan kendaraan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_jenis_kendaraan' => 'required|exists:jenis_kendaraan,id',
            'nama'               => 'required|string|max:255',
            'plat'               => 'required|string|max:20|unique:kendaraan,plat',
            'images.*'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'captions.*'         => 'nullable|string|max:255',
        ],[
            'nama.required'      => 'Nama kendaraan harus diisi',
            'plat.required'      => 'Nomor polisi harus diisi',
            'plat.unique'        => 'Nomor polisi ini sudah terdaftar',
            'images.*.image'     => 'File harus berupa gambar',
            'images.*.max'       => 'Ukuran gambar maksimal 2MB',
        ]);

        return DB::transaction(function () use ($request, $validated) {
            $kendaraan = Kendaraan::create([
                'id_perusahaan'      => Auth::user()->id_perusahaan,
                'id_jenis_kendaraan' => $validated['id_jenis_kendaraan'],
                'nama'               => $validated['nama'],
                'plat'               => $validated['plat'],
            ]);

            // Handle multiple images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $key => $image) {
                    $path = $image->store('kendaraan/' . $kendaraan->id, 'public');
                    $this->compressionService->compressImage($image, $path);

                    KendaraanImage::create([
                        'kendaraan_id' => $kendaraan->id,
                        'image_path'   => $path,
                        'caption'      => $request->captions[$key] ?? null,
                    ]);
                }
            }

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'CREATE',
                'subject_type' => Kendaraan::class,
                'subject_id' => $kendaraan->id,
                'description' => "Perusahaan menambahkan kendaraan: {$kendaraan->nama} ({$kendaraan->plat}) dengan " . (count($request->file('images') ?? []) ) . " foto",
                'ip_address' => $request->ip(),
            ]);

            return redirect()->route('perusahaan.kendaraan.index')
                ->with('success', 'Kendaraan berhasil ditambahkan.');
        });
    }

    /**
     * Form edit kendaraan.
     */
    public function edit($id)
    {
        $kendaraan = Kendaraan::where('id', $id)
            ->where('id_perusahaan', Auth::user()->id_perusahaan)
            ->with('images')
            ->firstOrFail();

        $jenisKendaraan = JenisKendaraan::all();

        return view('pages.perusahaan.kendaraan.edit', compact('kendaraan', 'jenisKendaraan'));
    }

    /**
     * Update data kendaraan.
     */
    public function update(Request $request, $id)
    {
        $kendaraan = Kendaraan::where('id', $id)
            ->where('id_perusahaan', Auth::user()->id_perusahaan)
            ->firstOrFail();

        $validated = $request->validate([
            'id_jenis_kendaraan' => 'required|exists:jenis_kendaraan,id',
            'nama'               => 'required|string|max:255',
            'plat'               => 'required|string|max:20|unique:kendaraan,plat,' . $kendaraan->id,
            'images.*'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'captions.*'         => 'nullable|string|max:255',
            'delete_images.*'    => 'nullable|exists:kendaraan_images,id',
        ],[
            'nama.required'      => 'Nama kendaraan harus diisi',
            'plat.required'      => 'Nomor polisi harus diisi',
            'plat.unique'        => 'Nomor polisi ini sudah terdaftar',
        ]);

        return DB::transaction(function () use ($request, $kendaraan, $validated) {
            $oldInfo = "{$kendaraan->nama} ({$kendaraan->plat})";

            $kendaraan->update([
                'id_jenis_kendaraan' => $validated['id_jenis_kendaraan'],
                'nama'               => $validated['nama'],
                'plat'               => $validated['plat'],
            ]);

            // Handle image deletions
            if ($request->filled('delete_images')) {
                foreach ($request->delete_images as $imageId) {
                    $img = KendaraanImage::find($imageId);
                    if ($img) {
                        Storage::disk('public')->delete($img->image_path);
                        $img->delete();
                    }
                }
            }

            // Handle new images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $key => $image) {
                    if ($image->isValid()) {
                        $path = $image->store('kendaraan/' . $kendaraan->id, 'public');
                        $this->compressionService->compressImage($image, $path);

                        KendaraanImage::create([
                            'kendaraan_id' => $kendaraan->id,
                            'image_path'   => $path,
                            'caption'      => $request->captions[$key] ?? null,
                        ]);
                    }
                }
            }

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'UPDATE',
                'subject_type' => Kendaraan::class,
                'subject_id' => $kendaraan->id,
                'description' => "Perusahaan memperbarui kendaraan. Dari [$oldInfo] menjadi [{$validated['nama']} ({$validated['plat']})]",
                'ip_address' => $request->ip(),
            ]);

            return redirect()->route('perusahaan.kendaraan.index')
                ->with('success', 'Data kendaraan berhasil diperbarui.');
        });
    }

    /**
     * Hapus kendaraan.
     */
    public function destroy($id)
    {
        $idPerusahaan = Auth::user()->id_perusahaan;

        $sedangDigunakan = SesiAlat::where('id_kendaraan', $id)
            ->where('aktif', true)
            ->exists();

        if ($sedangDigunakan) {
            return redirect()->route('perusahaan.kendaraan.index')
                ->with('error', 'Gagal menghapus! Kendaraan sedang beroperasi (Status: Jalan). Harap selesaikan sesi terlebih dahulu.');
        }

        $kendaraan = Kendaraan::where('id', $id)
            ->where('id_perusahaan', $idPerusahaan)
            ->firstOrFail();

        try {
            return DB::transaction(function () use ($kendaraan) {
                $info = $kendaraan->nama . ' (' . $kendaraan->plat . ')';
                $deletedId = $kendaraan->id;

                // Delete images from storage
                foreach ($kendaraan->images as $img) {
                    Storage::disk('public')->delete($img->image_path);
                }
                
                // Folder cleanup
                Storage::disk('public')->deleteDirectory('kendaraan/' . $kendaraan->id);

                $kendaraan->delete();
                
                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'DELETE',
                    'subject_type' => Kendaraan::class,
                    'subject_id' => $deletedId,
                    'description' => "Perusahaan menghapus kendaraan: $info beserta seluruh datanya",
                    'ip_address' => request()->ip(),
                ]);

                return redirect()->route('perusahaan.kendaraan.index')
                    ->with('success', 'Kendaraan berhasil dihapus.');
            });
                
        } catch (\Exception $e) {
            return redirect()->route('perusahaan.kendaraan.index')
                ->with('error', 'Kendaraan tidak bisa dihapus karena memiliki riwayat penggunaan.');
        }
    }

    /**
     * Update foto kendaraan tertentu.
     */
    public function updateImage(Request $request, $id, $imageId)
    {
        $request->validate([
            'new_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'new_image.required' => 'File gambar baru harus diunggah',
            'new_image.image' => 'File harus berupa gambar',
            'new_image.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        $kendaraan = Kendaraan::where('id', $id)
            ->where('id_perusahaan', Auth::user()->id_perusahaan)
            ->firstOrFail();

        $image = KendaraanImage::where('id', $imageId)
            ->where('kendaraan_id', $kendaraan->id)
            ->firstOrFail();

        if ($request->hasFile('new_image')) {
            // Delete old image from storage
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }

            // Store new image
            $newFile = $request->file('new_image');
            $path = $newFile->store('kendaraan/' . $kendaraan->id, 'public');
            $this->compressionService->compressImage($newFile, $path);

            // Update database record
            $image->update([
                'image_path' => $path,
            ]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'UPDATE',
                'subject_type' => KendaraanImage::class,
                'subject_id' => $image->id,
                'description' => "Perusahaan mengubah foto kendaraan: {$kendaraan->nama} ({$kendaraan->plat})",
                'ip_address' => $request->ip(),
            ]);
        }

        return redirect()->back()->with('success', 'Foto kendaraan berhasil diubah.');
    }

    /**
     * Hapus foto kendaraan tertentu.
     */
    public function destroyImage($id, $imageId)
    {
        $kendaraan = Kendaraan::where('id', $id)
            ->where('id_perusahaan', Auth::user()->id_perusahaan)
            ->firstOrFail();

        $image = KendaraanImage::where('id', $imageId)
            ->where('kendaraan_id', $kendaraan->id)
            ->firstOrFail();

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'DELETE',
            'subject_type' => KendaraanImage::class,
            'subject_id' => $image->id,
            'description' => "Perusahaan menghapus foto kendaraan: {$kendaraan->nama} ({$kendaraan->plat})",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->back()->with('success', 'Foto kendaraan berhasil dihapus.');
    }

    /**
     * Menampilkan detail kendaraan (opsional untuk show page)
     */
    public function show($id)
    {
        $kendaraan = Kendaraan::where('id', $id)
            ->where('id_perusahaan', Auth::user()->id_perusahaan)
            ->with(['jenisKendaraan', 'images', 'sesiAlat' => function($q) {
                $q->latest()->limit(5);
            }])
            ->firstOrFail();

        return view('pages.perusahaan.kendaraan.show', compact('kendaraan'));
    }
}
