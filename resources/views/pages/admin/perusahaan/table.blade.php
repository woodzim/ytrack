<div class="card table-wrapper">
  <div class="card-header border-bottom-0 bg-white py-3">
    <div class="d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Daftar Perusahaan ({{ $perusahaan->total() }})</h5>
      @if ($search)
        <span class="badge bg-info">Hasil pencarian: "{{ $search }}"</span>
      @endif
    </div>
  </div>
  <div class="card-body p-0">
    @if ($perusahaan->count() > 0)
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th style="width: 5%;">No</th>
              <th style="width: 25%;">Nama Perusahaan</th>
              <th style="width: 50%;">Alamat</th>
              <th style="width: 20%;" class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($perusahaan as $item)
              <tr>
                <td data-label="No">
                  <span class="badge bg-light text-dark">{{ $perusahaan->firstItem() + $loop->index }}</span>
                </td>
                <td data-label="Nama Perusahaan">
                  <strong>{{ $item->nama }}</strong>
                </td>
                <td data-label="Alamat">
                  <small class="text-muted">{{ Str::limit($item->alamat, 60) }}</small>
                </td>
                <td data-label="Aksi" class="text-center">
                  <div class="dropdown">
                    <button class="btn btn-sm btn-light btn-wave dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="ri-more-2-fill"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                      <li>
                        <a class="dropdown-item" href="{{ route('perusahaan.show', $item->id) }}">
                          <i class="ri-eye-line me-2 align-middle text-info"></i>Lihat Detail
                        </a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="{{ route('perusahaan.edit', $item->id) }}">
                          <i class="ri-edit-line me-2 align-middle text-warning"></i>Edit Data
                        </a>
                      </li>
                      <li><hr class="dropdown-divider"></li>
                      <li>
                        <form action="{{ route('perusahaan.destroy', $item->id) }}" method="POST"
                          onsubmit="return confirm('Anda yakin ingin menghapus perusahaan ini?');">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="dropdown-item text-danger">
                            <i class="ri-delete-bin-line me-2 align-middle"></i>Hapus
                          </button>
                        </form>
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
      <div class="p-4 text-center">
        <div class="mb-3">
          @if ($search)
            <i class="ri-search-line" style="font-size: 48px; color: #ddd;"></i>
          @else
            <i class="ri-building-line" style="font-size: 48px; color: #ddd;"></i>
          @endif
        </div>
        @if ($search)
          <h5 class="text-muted mb-2">Tidak ada hasil pencarian</h5>
          <p class="text-muted mb-3">Tidak ditemukan perusahaan dengan kata kunci "{{ $search }}"</p>
          <a href="{{ route('perusahaan.index') }}" class="btn btn-outline-primary btn-wave">
            <i class="ri-arrow-left-line me-2"></i>Kembali ke Daftar
          </a>
        @else
          <h5 class="text-muted mb-2">Tidak ada data perusahaan</h5>
          <p class="text-muted mb-3">Mulai dengan menambahkan perusahaan pertama Anda</p>
          <a href="{{ route('perusahaan.create') }}" class="btn btn-primary btn-wave">
            <i class="ri-add-line me-2"></i>Tambah Perusahaan
          </a>
        @endif
      </div>
    @endif
  </div>

  @if ($perusahaan->hasPages())
    <div class="card-footer bg-white border-top py-3">
      <div class="d-flex justify-content-end">
        {{ $perusahaan->links('pagination::bootstrap-5') }}
      </div>
    </div>
  @endif
</div>
