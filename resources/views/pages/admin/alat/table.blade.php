<div class="card table-wrapper">
  <div class="card-header border-bottom-0 bg-white py-3">
    <div class="d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Daftar Alat ({{ $alat->total() }})</h5>
      @if ($search)
        <span class="badge bg-info">Hasil pencarian: "{{ $search }}"</span>
      @endif
    </div>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive" style="min-height: 250px;">
      <table class="table table-hover align-middle mb-0">
        <thead class="bg-light">
          <tr>
            <th class="ps-4" style="width: 50px;">No</th>
            <th>Serial Number (ID)</th>
            <th>Perusahaan Pemilik</th>
            <th>Status</th>
            <th>Kendaraan Saat Ini</th>
            <th class="text-end pe-4" style="width: 100px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($alat as $index => $item)
            <tr>
              <td class="ps-4 text-muted">{{ $alat->firstItem() + $index }}</td>
              <td>
                <span class="fw-medium text-primary">#{{ $item->id }}</span>
              </td>
              <td>
                @if ($item->perusahaan)
                  <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm bg-primary-transparent me-2">
                      <i class="ri-building-line text-primary"></i>
                    </div>
                    <span>{{ $item->perusahaan->nama }}</span>
                  </div>
                @else
                  <span class="text-muted italic">Belum ditentukan</span>
                @endif
              </td>
              <td>
                @php
                  // Alat dianggap 'Digunakan' jika memiliki SesiAlat yang status 'aktif' = true
                  $sedangDigunakan = $item->sedang_digunakan; 
                @endphp

                @if ($sedangDigunakan)
                  <span class="badge bg-warning-transparent text-warning px-2 py-1">
                    <i class="ri-record-circle-line me-1"></i>Digunakan
                  </span>
                @else
                  <span class="badge bg-success-transparent text-success px-2 py-1">
                    <i class="ri-checkbox-circle-line me-1"></i>Tersedia
                  </span>
                @endif
              </td>
              <td>
                @if ($sedangDigunakan)
                  @php
                    $sesiAktif = $item->sesiAlat->firstWhere('aktif', true);
                  @endphp
                  @if($sesiAktif && $sesiAktif->kendaraan)
                    <div class="d-flex flex-column">
                      <span class="fw-medium">{{ $sesiAktif->kendaraan->nama }}</span>
                      <small class="text-muted">{{ $sesiAktif->kendaraan->plat }}</small>
                    </div>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                @else
                  <span class="text-muted">-</span>
                @endif
              </td>
              <td class="text-end pe-4">
                <div class="dropdown">
                  <button class="btn btn-sm btn-light btn-wave dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="viewport">
                    <i class="ri-more-2-fill"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="{{ route('alat.show', $item->id) }}">
                        <i class="ri-eye-line me-2 align-middle text-info"></i>Detail
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="{{ route('alat.edit', $item->id) }}">
                        <i class="ri-edit-line me-2 align-middle text-warning"></i>Edit
                      </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                      <form action="{{ route('alat.destroy', $item->id) }}" method="POST"
                        onsubmit="return confirm('Hapus alat ini? Semua data terkait (sesi & riwayat) mungkin terdampak.');">
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
          @empty
            <tr>
              <td colspan="6" class="text-center py-5">
                <div class="empty-state">
                  <i class="ri-gps-line text-muted mb-3" style="font-size: 48px; opacity: 0.3;"></i>
                  <h6 class="text-muted">Tidak ada alat ditemukan</h6>
                  <p class="text-muted small">Coba sesuaikan filter atau tambahkan alat baru.</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if ($alat->hasPages())
    <div class="card-footer bg-white border-top-0 py-3">
      <div class="d-flex justify-content-between align-items-center">
        <div class="text-muted small">
          Menampilkan {{ $alat->firstItem() }} sampai {{ $alat->lastItem() }} dari {{ $alat->total() }} alat
        </div>
        <div class="pagination-sm">
          {{ $alat->links() }}
        </div>
      </div>
    </div>
  @endif
</div>
