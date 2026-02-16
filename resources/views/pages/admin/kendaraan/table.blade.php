<div class="card table-wrapper">
  <div class="card-header border-bottom-0 bg-white py-3">
    <div class="d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Daftar Kendaraan ({{ $kendaraan->total() }})</h5>
      @if ($search)
        <span class="badge bg-info">Hasil pencarian: "{{ $search }}"</span>
      @endif
    </div>
  </div>
  <div class="card-body p-0">
    @if ($kendaraan->count() > 0)
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th style="width: 5%;">No</th>
              <th>Nama Kendaraan</th>
              <th>Plat Nomor</th>
              <th>Jenis</th>
              <th>Perusahaan</th>
              {{-- 1. HEADER KOLOM STATUS --}}
              <th style="width: 15%;">Status</th>
              <th class="text-center" style="width: 15%;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($kendaraan as $index => $item)
              <tr>
                <td data-label="No">
                  <span class="badge bg-light text-dark">{{ $kendaraan->firstItem() + $index }}</span>
                </td>

                <td data-label="Nama Kendaraan">
                  <strong>{{ $item->nama }}</strong>
                </td>

                <td data-label="Plat">
                  <span class="badge bg-outline-dark border bg-white text-dark font-monospace">
                    {{ $item->plat }}
                  </span>
                </td>

                <td data-label="Jenis Kendaraan">
                  {{ $item->jenisKendaraan->nama ?? '-' }}
                </td>

                <td data-label="Perusahaan">
                  <small class="text-primary">{{ $item->perusahaan->nama ?? '-' }}</small>
                </td>

                {{-- 2. ISI KOLOM STATUS --}}
                <td data-label="Status">
                  @if ($item->sedang_digunakan)
                    {{-- Tampilan jika Aktif (Dipakai) --}}
                    <span class="badge bg-danger-transparent text-danger">
                      <i class="ri-lock-line me-1"></i>Digunakan
                    </span>
                  @else
                    {{-- Tampilan jika Tidak Aktif (Tersedia) --}}
                    <span class="badge bg-success-transparent text-success">
                      <i class="ri-checkbox-circle-line me-1"></i>Tersedia
                    </span>
                  @endif
                </td>

                <td data-label="Aksi" class="text-center">
                  <div class="dropdown">
                    <button class="btn btn-sm btn-light btn-wave dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="viewport">
                      <i class="ri-more-2-fill"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                      <li>
                        <a class="dropdown-item" href="{{ route('kendaraan.edit', $item->id) }}">
                          <i class="ri-edit-line me-2 align-middle text-warning"></i>Edit Data
                        </a>
                      </li>
                      <li><hr class="dropdown-divider"></li>
                      <li>
                        @if ($item->sedang_digunakan)
                          <button type="button" class="dropdown-item text-muted" title="Sedang digunakan" disabled>
                            <i class="ri-delete-bin-line me-2 align-middle"></i>Hapus (Sedang Digunakan)
                          </button>
                        @else
                          <form action="{{ route('kendaraan.destroy', $item->id) }}" method="POST"
                            onsubmit="return confirm('Anda yakin ingin menghapus kendaraan {{ $item->nama }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger">
                              <i class="ri-delete-bin-line me-2 align-middle"></i>Hapus
                            </button>
                          </form>
                        @endif
                      </li>
                    </ul>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <div class="p-5 text-center">
        <div class="mb-3">
          @if ($search)
            <i class="ri-search-line" style="font-size: 48px; color: #ddd;"></i>
          @else
            <i class="ri-car-line" style="font-size: 56px; color: #ddd;"></i>
          @endif
        </div>
        @if ($search)
          <h5 class="text-muted mb-2">Tidak ada hasil pencarian</h5>
          <p class="text-muted mb-3">Tidak ditemukan kendaraan dengan kata kunci "{{ $search }}"</p>
          <a href="{{ route('kendaraan.index') }}" class="btn btn-outline-primary btn-wave">
            <i class="ri-arrow-left-line me-2"></i>Kembali ke Daftar
          </a>
        @else
          <h5 class="text-muted mb-2">Belum ada data kendaraan</h5>
          <p class="text-muted mb-3">Mulai dengan menambahkan kendaraan pertama Anda</p>
          <a href="{{ route('kendaraan.create') }}" class="btn btn-primary btn-wave">
            <i class="ri-add-line me-2"></i>Tambah Kendaraan
          </a>
        @endif
      </div>
    @endif
  </div>

  @if ($kendaraan->hasPages())
    <div class="card-footer bg-white border-top py-3">
      <div class="d-flex justify-content-end">
        {{ $kendaraan->links('pagination::bootstrap-5') }}
      </div>
    </div>
  @endif
</div>
