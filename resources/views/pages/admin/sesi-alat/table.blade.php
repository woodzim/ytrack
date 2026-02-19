<div class="card table-wrapper">
  <div class="card-header border-bottom-0 bg-white py-3">
    <div class="d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Daftar Perjalanan ({{ $sesiAlat->total() }})</h5>
    </div>
  </div>
  <div class="card-body p-0">
    @if ($sesiAlat->count() > 0)
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th style="min-width: 150px;">Perusahaan</th>
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
                $batteryColor = 'bg-danger';
                if ($battery > 70) $batteryColor = 'bg-success';
                elseif ($battery > 30) $batteryColor = 'bg-warning';
              @endphp
              <tr>
                <td data-label="Perusahaan">
                  <small class="text-muted">{{ $item->perusahaan ? $item->perusahaan?->nama : '-' }}</small>
                </td>
                <td data-label="Kendaraan">
                  <small class="text-muted">{{ $item->kendaraan ? $item->kendaraan?->nama : '-' }}</small>
                </td>
                <td data-label="Alat">
                  <span class="badge bg-light text-dark">{{ $item->alat ? $item->alat?->id : '-' }}</span>
                </td>
                <td data-label="Status">
                  <span class="status-badge"
                    style="background-color: {{ $item->aktif ? '#10b981' : '#6b7280' }}; color: white;">
                    {{ $item->aktif ? 'Aktif' : 'Nonaktif' }}
                  </span>
                </td>

                <td data-label="Baterai">
                  <x-battery-circle :level="$battery" size="48px" />
                </td>

                <td data-label="Tanggal Track">
                  @if (!$lastTrack)
                    <small class="text-muted">Belum ada track</small>
                  @else
                    <small class="text-muted local-date">
                      {{ $lastTrack->created_at->toIso8601String() }}
                    </small>
                  @endif
                </td>

                <td data-label="Aksi" class="text-center">
                  <div class="dropdown">
                    <button class="btn btn-sm btn-light btn-wave dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="ri-more-2-fill"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                      <li>
                        <a class="dropdown-item" href="{{ route('sesi-alat.show', $item->id) }}">
                          <i class="ri-eye-line me-2 align-middle text-info"></i>Lihat Tracking
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
                          onsubmit="return confirm('Hapus sesi ini?');">
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
      <div class="p-4 text-center">
        <div class="mb-3">
          <i class="ri-bluetooth-connect-line" style="font-size: 48px; color: #ddd;"></i>
        </div>
        <h5 class="text-muted mb-2">Belum ada perjalanan</h5>
      </div>
    @endif
  </div>

  @if ($sesiAlat->hasPages())
    <div class="card-footer bg-white border-top">
      <nav aria-label="Page navigation">
        {{ $sesiAlat->links('pagination::bootstrap-5') }}
      </nav>
    </div>
  @endif
</div>
