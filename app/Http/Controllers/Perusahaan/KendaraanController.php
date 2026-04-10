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
    /**
     * Daftar kolom foto kendaraan per-angle.
     */
    private const FOTO_FIELDS = ['foto_depan', 'foto_samping_kanan', 'foto_samping_kiri', 'foto_belakang'];

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
            'stnk'               => 'required|file|mimes:pdf,jpeg,png,jpg,webp|max:4096',
            'foto_depan'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'foto_samping_kanan' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'foto_samping_kiri'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'foto_belakang'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ],[
            'nama.required'              => 'Nama kendaraan harus diisi',
            'plat.required'              => 'Nomor polisi harus diisi',
            'plat.unique'                => 'Nomor polisi ini sudah terdaftar',
            'stnk.required'              => 'STNK kendaraan wajib diunggah',
            'stnk.mimes'                 => 'Format STNK harus berupa PDF, JPEG, PNG, JPG, atau WEBP',
            'stnk.max'                   => 'Ukuran STNK maksimal 4MB',
            'foto_depan.image'           => 'File harus berupa gambar',
            'foto_depan.max'             => 'Ukuran gambar maksimal 2MB',
            'foto_samping_kanan.image'   => 'File harus berupa gambar',
            'foto_samping_kanan.max'     => 'Ukuran gambar maksimal 2MB',
            'foto_samping_kiri.image'    => 'File harus berupa gambar',
            'foto_samping_kiri.max'      => 'Ukuran gambar maksimal 2MB',
            'foto_belakang.image'        => 'File harus berupa gambar',
            'foto_belakang.max'          => 'Ukuran gambar maksimal 2MB',
        ]);

        return DB::transaction(function () use ($request, $validated) {
            $kendaraan = Kendaraan::create([
                'id_perusahaan'      => Auth::user()->id_perusahaan,
                'id_jenis_kendaraan' => $validated['id_jenis_kendaraan'],
                'nama'               => $validated['nama'],
                'plat'               => $validated['plat'],
            ]);

            $storagePath = 'kendaraan/' . $kendaraan->id;

            // Handle STNK upload
            if ($request->hasFile('stnk')) {
                $stnkPath = $request->file('stnk')->store($storagePath, 'public');
                $kendaraan->update(['stnk' => $stnkPath]);
            }

            // Handle per-angle photo uploads
            foreach (self::FOTO_FIELDS as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $path = $file->store($storagePath, 'public');
                    if ($this->compressionService->isCompressibleImage($file)) {
                        $this->compressionService->compressImage($file, $path);
                    }
                    $kendaraan->update([$field => $path]);
                }
            }

            $fotoCount = collect(self::FOTO_FIELDS)->filter(fn($f) => $request->hasFile($f))->count();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'CREATE',
                'subject_type' => Kendaraan::class,
                'subject_id' => $kendaraan->id,
                'description' => "Perusahaan menambahkan kendaraan: {$kendaraan->nama} ({$kendaraan->plat}) dengan {$fotoCount} foto",
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
            'stnk'               => 'nullable|file|mimes:pdf,jpeg,png,jpg,webp|max:4096',
            'foto_depan'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'foto_samping_kanan' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'foto_samping_kiri'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'foto_belakang'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'hapus_foto'         => 'nullable|array',
            'hapus_foto.*'       => 'in:foto_depan,foto_samping_kanan,foto_samping_kiri,foto_belakang,stnk',
        ],[
            'nama.required'              => 'Nama kendaraan harus diisi',
            'plat.required'              => 'Nomor polisi harus diisi',
            'plat.unique'                => 'Nomor polisi ini sudah terdaftar',
            'stnk.mimes'                 => 'Format STNK harus berupa PDF, JPEG, PNG, JPG, atau WEBP',
            'stnk.max'                   => 'Ukuran STNK maksimal 4MB',
            'foto_depan.image'           => 'File harus berupa gambar',
            'foto_depan.max'             => 'Ukuran gambar maksimal 2MB',
            'foto_samping_kanan.image'   => 'File harus berupa gambar',
            'foto_samping_kanan.max'     => 'Ukuran gambar maksimal 2MB',
            'foto_samping_kiri.image'    => 'File harus berupa gambar',
            'foto_samping_kiri.max'      => 'Ukuran gambar maksimal 2MB',
            'foto_belakang.image'        => 'File harus berupa gambar',
            'foto_belakang.max'          => 'Ukuran gambar maksimal 2MB',
        ]);

        return DB::transaction(function () use ($request, $kendaraan, $validated) {
            $oldInfo = "{$kendaraan->nama} ({$kendaraan->plat})";
            $storagePath = 'kendaraan/' . $kendaraan->id;

            $kendaraan->update([
                'id_jenis_kendaraan' => $validated['id_jenis_kendaraan'],
                'nama'               => $validated['nama'],
                'plat'               => $validated['plat'],
            ]);

            // Handle photo deletions
            if ($request->filled('hapus_foto')) {
                foreach ($request->hapus_foto as $field) {
                    if ($kendaraan->{$field}) {
                        Storage::disk('public')->delete($kendaraan->{$field});
                        $kendaraan->update([$field => null]);
                    }
                }
            }

            // Handle STNK upload/replacement
            if ($request->hasFile('stnk')) {
                if ($kendaraan->stnk) {
                    Storage::disk('public')->delete($kendaraan->stnk);
                }
                $stnkPath = $request->file('stnk')->store($storagePath, 'public');
                $kendaraan->update(['stnk' => $stnkPath]);
            }

            // Handle per-angle photo uploads/replacements
            foreach (self::FOTO_FIELDS as $field) {
                if ($request->hasFile($field)) {
                    // Delete old file
                    if ($kendaraan->{$field}) {
                        Storage::disk('public')->delete($kendaraan->{$field});
                    }
                    $file = $request->file($field);
                    $path = $file->store($storagePath, 'public');
                    if ($this->compressionService->isCompressibleImage($file)) {
                        $this->compressionService->compressImage($file, $path);
                    }
                    $kendaraan->update([$field => $path]);
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

                // Delete images from storage (legacy kendaraan_images)
                foreach ($kendaraan->images as $img) {
                    Storage::disk('public')->delete($img->image_path);
                }

                // Delete per-angle photos
                foreach (self::FOTO_FIELDS as $field) {
                    if ($kendaraan->{$field}) {
                        Storage::disk('public')->delete($kendaraan->{$field});
                    }
                }

                // Delete STNK
                if ($kendaraan->stnk) {
                    Storage::disk('public')->delete($kendaraan->stnk);
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
     * Update foto kendaraan tertentu (legacy).
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
     * Hapus foto kendaraan tertentu (legacy).
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
