<div class="py-3">
  <div class="d-flex justify-content-between align-items-center">
    <h5 class="card-title mb-0">Daftar Jenis ({{ $jenisKendaraan->total() }})</h5>
    @if ($search)
      <span class="badge bg-info">Hasil pencarian: "{{ $search }}"</span>
    @endif
  </div>
</div>
<div class="card-body p-0">
  @if ($jenisKendaraan->count() > 0)
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="bg-light">
          <tr>
            <th style="width: 5%;">No</th>
            <th>Nama Jenis</th>
            <th>Kategori</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($jenisKendaraan as $index => $item)
            <tr>
              <td data-label="No">
                <span class="badge bg-light text-dark">{{ $jenisKendaraan->firstItem() + $index }}</span>
              </td>

              <td data-label="Nama Jenis">
                <strong>{{ $item->nama }}</strong>
              </td>

              <td data-label="Kategori">
                @if ($item->kategori || $item->deskripsi)
                  {{ Str::limit($item->kategori ?? $item->deskripsi, 60) }}
                @else
                  <span class="text-muted text-italic">- Tidak ada kategori -</span>
                @endif
              </td>

              <td data-label="Aksi" class="table-action-btns text-center">
                {{-- Tombol Edit --}}
                <a href="{{ route('jenis-kendaraan.edit', $item->id) }}" class="btn btn-sm btn-warning btn-wave"
                  title="Edit">
                  <i class="ri-edit-line"></i>
                </a>

                {{-- Tombol Hapus --}}
                <form action="{{ route('jenis-kendaraan.destroy', $item->id) }}" method="POST" style="display: inline;"
                  data-confirm="Hapus jenis kendaraan {{ $item->nama }}? Data kendaraan yang menggunakan jenis ini mungkin akan terdampak.">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger btn-wave" title="Hapus">
                    <i class="ri-delete-bin-line"></i>
                  </button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @else
    {{-- Empty State --}}
    <div class="p-5 text-center">
      <div class="mb-3">
        @if ($search)
          <i class="ri-search-line" style="font-size: 48px; color: #ddd;"></i>
        @else
          {{-- Icon Setir (Steering) untuk Jenis Kendaraan --}}
          <i class="ri-steering-2-line" style="font-size: 56px; color: #ddd;"></i>
        @endif
      </div>
      @if ($search)
        <h5 class="text-muted mb-2">Tidak ada hasil pencarian</h5>
        <p class="text-muted mb-3">Tidak ditemukan jenis kendaraan dengan kata kunci "{{ $search }}"</p>
        <a href="{{ route('jenis-kendaraan.index') }}" class="btn btn-outline-primary btn-wave">
          <i class="ri-arrow-left-line me-2"></i>Kembali ke Daftar
        </a>
      @else
        <h5 class="text-muted mb-2">Belum ada jenis kendaraan</h5>
        <p class="text-muted mb-3">Tambahkan kategori kendaraan (misal: Truk, Sedan, Pickup)</p>
        <a href="{{ route('jenis-kendaraan.create') }}" class="btn btn-primary btn-wave">
          <i class="ri-add-line me-2"></i>Tambah Jenis
        </a>
      @endif
    </div>
  @endif
</div>

@if ($jenisKendaraan->hasPages())
  <div class="card-footer border-top py-3">
    <div class="d-flex justify-content-end">
      {{ $jenisKendaraan->links('pagination::bootstrap-5') }}
    </div>
  </div>
@endif
