@if ($sesiAlat->count() > 0)
  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead class="bg-light">
        <tr>
          <th scope="col">Nama Perjalanan</th>
          <th scope="col">Kendaraan</th>
          <th scope="col">Alat GPS (SN)</th>
          <th scope="col">Status</th>
          <th scope="col">Battery</th>
          <th scope="col">Update Terakhir</th>
          <th scope="col" class="text-center" style="width: 150px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($sesiAlat as $item)
          <tr>
            <td>
              <div class="d-flex align-items-center">
                <div class="avatar avatar-sm me-2 text-primary">
                  <i class="ri-route-line fs-5"></i>
                </div>
                <span class="fw-medium {{ empty($item->nama) ? 'fst-italic text-muted' : '' }}">
                  {{ $item->nama ?? '(Perjalanan Tanpa Nama)' }}
                </span>
              </div>
            </td>

            <td>
              @if ($item->kendaraan)
                <div>{{ $item->kendaraan->nama }}</div>
                <small class="text-muted" style="font-size: 0.75rem;">{{ $item->kendaraan->plat }}</small>
              @else
                <span class="text-muted fst-italic">-</span>
              @endif
            </td>

            <td>
              @if ($item->alat)
                <span class="badge bg-light text-dark border">
                  <i class="ri-cpu-line me-1"></i>
                  {{-- PERUBAHAN DI SINI: Menggunakan ID (Serial Number) --}}
                  {{ $item->alat->id }}
                </span>
              @else
                <span class="text-muted">-</span>
              @endif
            </td>

            <td>
              @if ($item->aktif)
                <span class="badge bg-success-transparent">Aktif</span>
              @else
                <span class="badge bg-secondary-transparent">Nonaktif</span>
              @endif
            </td>

            <td>
              @php
                $lastRiwayat = $item->riwayatPerjalanan?->sortByDesc('created_at')->first();
                $batLevel = (int) ($lastRiwayat?->battery ?? 0);
              @endphp
              @if ($lastRiwayat && $lastRiwayat->battery !== null)
                <x-battery-circle :level="$batLevel" size="40px" />
              @else
                <small class="text-muted">-</small>
              @endif
            </td>

            <td>
              {{-- Output Tanggal ISO --}}
              <small class="text-muted local-date">
                {{ $item->updated_at ? $item->updated_at->toIso8601String() : '' }}
              </small>
            </td>

            <td class="text-center">
              <div class="dropdown">
                <button class="btn btn-sm btn-light btn-wave dropdown-toggle hide-arrow" type="button"
                  data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="viewport">
                  <i class="ri-more-2-fill"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="{{ route('perusahaan.perjalanan.show', $item->id) }}">
                      <i class="ri-eye-line me-2 align-middle text-info"></i>Lihat Tracking
                    </a>
                  </li>
                  <li>
                    <button type="button" class="dropdown-item toggle-status-btn" data-id="{{ $item->id }}"
                      data-active="{{ $item->aktif ? '1' : '0' }}"
                      data-url="{{ route('perusahaan.perjalanan.toggle', $item->id) }}"
                      data-token="{{ csrf_token() }}">
                      <i
                        class="ri-{{ $item->aktif ? 'stop-circle-line text-warning' : 'play-circle-line text-success' }} me-2 align-middle"></i>
                      <span
                        class="toggle-text">{{ $item->aktif ? 'Selesaikan Perjalanan' : 'Aktifkan Perjalanan' }}</span>
                    </button>
                  </li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li>
                    <form action="{{ route('perusahaan.perjalanan.destroy', $item->id) }}" method="POST"
                      data-confirm="Hapus perjalanan ini? Data tracking mungkin akan hilang."
                      data-confirm-title="Hapus Perjalanan">
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

  <div class="d-flex justify-content-end border-top" id="pagination-container">
    {{ $sesiAlat->links() }}
  </div>
@else
  <div class="p-5 text-center">
    <div class="mb-3">
      <div class="avatar avatar-xxl bg-light text-muted">
        <i class="ri-route-line display-5"></i>
      </div>
    </div>
    <h5 class="fw-bold">Belum Ada Perjalanan</h5>
    <p class="text-muted mb-4">Buat perjalanan baru untuk mulai menghubungkan Alat GPS dengan Kendaraan.</p>
    <a href="{{ route('perusahaan.perjalanan.create') }}" class="btn btn-primary btn-wave">
      <i class="ri-add-line me-2"></i>Buat Perjalanan Sekarang
    </a>
  </div>
@endif
