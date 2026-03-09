<div class="card-header py-3">
  <div class="d-flex justify-content-between align-items-center w-100">
    <h5 class="card-title mb-0">Daftar Kendaraan ({{ $kendaraan->total() }})</h5>
    @if ($search)
      <span class="badge bg-info-transparent">Hasil pencarian: "{{ $search }}"</span>
    @endif
  </div>
</div>

<div class="card-body p-0">
  {{-- MENAMPILKAN ALERT ERROR JIKA GAGAL HAPUS --}}
  @if (session('success'))
    <div class="alert alert-success m-3 alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif
  @if (session('error'))
    <div class="alert alert-danger m-3 alert-dismissible fade show" role="alert">
      <i class="ri-error-warning-line me-2"></i> {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if ($kendaraan->count() > 0)
    <div class="table-responsive">
      <table class="table table-hover text-nowrap mb-0">
        <thead class="bg-light">
          <tr>
            <th width="5%">No</th>
            <th width="25%">Nama Kendaraan</th>
            <th width="15%">Plat Nomor</th>
            <th width="20%">Jenis</th>
            <th width="15%">Status</th>
            <th width="10%" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($kendaraan as $index => $item)
            <tr>
              <td>
                {{ $kendaraan->firstItem() + $index }}
              </td>
              <td>
                <div class="fw-bold">{{ $item->nama }} {{ $item->tipe }}</div>
              </td>
              <td>
                <span class="badge bg-light text-dark border">
                  {{ $item->plat }}
                </span>
              </td>
              <td>
                {{ $item->jenisKendaraan->nama ?? '-' }}
              </td>

              {{-- STATUS: Menggunakan variable 'sedang_digunakan' dari Controller --}}
              <td>
                @if ($item->sedang_digunakan)
                  <span class="badge bg-success-transparent">
                    <i class="ri-steering-2-line me-1"></i> Jalan
                  </span>
                @else
                  <span class="badge bg-secondary-transparent">
                    <i class="ri-parking-box-line me-1"></i> Parkir
                  </span>
                @endif
              </td>

              <td class="text-center">
                <div class="dropdown">
                  <button class="btn btn-sm btn-light btn-wave dropdown-toggle hide-arrow" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="window">
                    <i class="ri-more-2-fill"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="{{ route('perusahaan.kendaraan.edit', $item->id) }}">
                        <i class="ri-edit-line me-2 align-middle text-warning"></i>Edit Data
                      </a>
                    </li>
                    <li>
                      <hr class="dropdown-divider">
                    </li>
                    <li>
                      {{-- LOGIKA TOMBOL HAPUS --}}
                      @if ($item->sedang_digunakan)
                        {{-- Jika sedang jalan, tombol didisable --}}
                        <button type="button" class="dropdown-item text-muted" disabled
                          title="Sedang digunakan (Tidak bisa dihapus)">
                          <i class="ri-delete-bin-line me-2 align-middle"></i>Hapus (Sedang Digunakan)
                        </button>
                      @else
                        {{-- Jika parkir, tombol aktif --}}
                        <form action="{{ route('perusahaan.kendaraan.destroy', $item->id) }}" method="POST"
                          data-confirm="Yakin ingin menghapus kendaraan {{ $item->plat }}?"
                          data-confirm-title="Hapus Kendaraan">
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
          @empty
            <tr>
              <td colspan="6" class="text-center py-5">
                <i class="ri-car-line fs-1 mb-3 text-muted opacity-25"></i>
                <h6 class="text-muted">Data kendaraan tidak ditemukan.</h6>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  @else
    <div class="p-5 text-center">
      <div class="mb-3">
        <i class="ri-car-line" style="font-size: 48px; color: #ddd;"></i>
        <h5 class="text-muted mt-3">Belum ada kendaraan</h5>
        <a href="{{ route('perusahaan.kendaraan.create') }}" class="btn mt-2">
          <i class="ri-add-line me-1"></i>Tambah Kendaraan
        </a>
      </div>
    </div>
  @endif
</div>

@if ($kendaraan->hasPages())
  <div class="card-footer border-top-0 d-flex justify-content-end py-3">
    {{ $kendaraan->links() }}
  </div>
@endif
