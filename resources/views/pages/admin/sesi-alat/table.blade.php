<div class="card table-wrapper">
  <div class="card-header py-3">
    <div class="d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Daftar Perjalanan ({{ $sesiAlat->total() }})</h5>
    </div>
  </div>
  <div class="card-body p-0">
    @if ($sesiAlat->count() > 0)
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="bg-light text-uppercase fs-11 fw-bold text-muted border-bottom">
            <tr>
              <th class="ps-3" style="min-width: 150px;">Perusahaan</th>
              <th style="min-width: 150px;">Kendaraan</th>
              <th style="min-width: 120px;">Alat</th>
              <th style="min-width: 100px;">Status</th>
              <th style="min-width: 150px;">Baterai</th>
              <th style="min-width: 180px;">Waktu Track Terakhir</th>
              <th style="min-width: 100px;" class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($sesiAlat as $item)
              @php
                $lastTrack = $item->riwayatPerjalanan->sortByDesc('created_at')->first();
                $battery = $lastTrack ? (int)$lastTrack->battery : 0;
              @endphp
              <tr>
                <td class="ps-3" data-label="Perusahaan">
                  <div class="fw-semibold text-dark">{{ $item->perusahaan ? $item->perusahaan?->nama : '-' }}</div>
                </td>
                <td data-label="Kendaraan">
                  <div class="d-flex align-items-center">
                    <i class="ri-truck-line me-2 text-primary"></i>
                    <span class="fw-medium text-muted small">{{ $item->kendaraan ? $item->kendaraan?->nama : '-' }}</span>
                  </div>
                </td>
                <td data-label="Alat">
                  <span class="badge bg-primary-transparent text-primary">{{ $item->alat ? $item->alat?->id : '-' }}</span>
                </td>
                <td data-label="Status">
                  <span class="badge rounded-pill {{ $item->aktif ? 'bg-success-transparent text-success' : 'bg-secondary-transparent text-muted' }}">
                    <i class="ri-checkbox-blank-circle-fill fs-8 me-1"></i> {{ $item->aktif ? 'Aktif' : 'Nonaktif' }}
                  </span>
                </td>

                <td data-label="Baterai">
                  <x-battery-circle :level="$battery" size="40px" />
                </td>

                <td data-label="Tanggal Track">
                  @if (!$lastTrack)
                    <span class="text-muted small italic">Belum ada track</span>
                  @else
                    <div class="d-flex align-items-center text-muted small">
                      <i class="ri-time-line me-1"></i>
                      <span class="local-date">{{ $lastTrack->created_at->toIso8601String() }}</span>
                    </div>
                  @endif
                </td>

                <td data-label="Aksi" class="text-center">
                  <div class="dropdown">
                    <button class="btn btn-sm btn-icon btn-light btn-wave dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="ri-more-2-fill"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                      <li>
                        <a class="dropdown-item" href="{{ route('sesi-alat.show', $item->id) }}">
                          <i class="ri-map-2-line me-2 align-middle text-info"></i>Lihat Tracking
                        </a>
                      </li>
                      <li>
                        <form action="{{ route('sesi-alat.toggle', $item->id) }}" method="POST">
                          @csrf
                          @method('PUT')
                          <button type="submit" class="dropdown-item {{ $item->aktif ? 'text-warning' : 'text-success' }}">
                            <i class="ri-{{ $item->aktif ? 'pause' : 'play' }}-line me-2 align-middle"></i>
                            {{ $item->aktif ? 'Nonaktifkan Sesi' : 'Aktifkan Sesi' }}
                          </button>
                        </form>
                      </li>
                      <li><hr class="dropdown-divider"></li>
                      <li>
                        <form action="{{ route('sesi-alat.destroy', $item->id) }}" method="POST"
                          data-confirm="Hapus sesi ini?">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="dropdown-item text-danger">
                            <i class="ri-delete-bin-line me-2 align-middle"></i>Hapus Sesi
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
      <div class="p-5 text-center">
        <div class="mb-3">
          <i class="ri-route-line text-muted opacity-25" style="font-size: 64px;"></i>
        </div>
        <h5 class="text-muted mb-2 fw-medium">Belum ada perjalanan tercatat</h5>
        <p class="text-muted small">Data perjalanan akan muncul di sini setelah alat GPS diaktifkan.</p>
      </div>
    @endif
  </div>

  @if ($sesiAlat->hasPages())
    <div class="card-footer border-top py-3">
      <div class="d-flex justify-content-end">
        {{ $sesiAlat->links('pagination::bootstrap-5') }}
      </div>
    </div>
  @endif
</div>
