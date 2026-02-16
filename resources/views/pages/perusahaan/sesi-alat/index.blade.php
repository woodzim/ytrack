@extends('layouts.master')

@section('styles')
  <style>
    /* Force parent containers to allow overflow so dropdowns aren't cut off */
    .custom-card,
    .custom-card .card-body,
    .table-responsive {
        overflow: visible !important;
    }
  </style>
@endsection

@section('content')
  <div class="page-header-section d-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="fw-bold mb-2">Perjalanan</h1>
      <p class="">Kelola sesi penggunaan alat GPS di kendaraan</p>
    </div>
    <div class="ms-auto">
      {{-- TOMBOL BUAT SESI --}}
      <a href="{{ route('perusahaan.sesi-alat.create') }}" class="btn btn-light btn-wave">
        <i class="ri-add-line me-2"></i>Buat Sesi Baru
      </a>
    </div>
  </div>
  <div class="row">
    <div class="col-12">

      <div class="card mb-4 custom-card">
        <div class="card-body">
          <form action="{{ route('perusahaan.sesi-alat.index') }}" method="GET"
            class="d-flex gap-3 flex-wrap align-items-end">

            {{-- SEARCH INPUT --}}
            <div class="grow" style="min-width: 200px;">
              <label for="searchInput" class="form-label fw-medium mb-1">Cari Sesi</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="ri-search-line text-muted"></i></span>
                {{-- Input pencarian --}}
                <input type="text" id="searchInput" name="search" class="form-control border-start-0 ps-0"
                  placeholder="Cari nama sesi, serial number alat, atau kendaraan..." value="{{ request('search') }}">
              </div>
            </div>

            {{-- FILTER STATUS --}}
            <div style="min-width: 150px;">
              <label for="statusFilter" class="form-label fw-medium mb-1">Status</label>
              <select id="statusFilter" name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
              </select>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary btn-wave">
                Cari
              </button>
              {{-- Reset hanya muncul jika ada query parameter --}}
              @if (request('search') || request('status') !== null)
                <a href="{{ route('perusahaan.sesi-alat.index') }}" class="btn btn-light btn-wave">
                  Reset
                </a>
              @endif
            </div>

          </form>
        </div>
      </div>

      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">
            Daftar Perjalanan ({{ $sesiAlat->total() }})
          </div>
        </div>

        <div class="card-body p-0">
          @if ($sesiAlat->count() > 0)
            <div class="table-responsive">
              <table class="table table-hover text-nowrap mb-0">
                <thead class="table-light">
                  <tr>
                    <th scope="col">Nama Sesi</th>
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
                            {{ $item->nama ?? '(Sesi Tanpa Nama)' }}
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
                          $batColor = 'bg-danger';
                          if ($batLevel > 70) {
                              $batColor = 'bg-success';
                          } elseif ($batLevel >= 30) {
                              $batColor = 'bg-warning';
                          }
                        @endphp
                        @if($lastRiwayat && $lastRiwayat->battery !== null)
                          <div class="d-flex align-items-center" style="min-width: 80px;">
                            <div class="progress flex-grow-1" style="height: 6px; background-color: #e9ecef;">
                              <div class="progress-bar {{ $batColor }}" role="progressbar"
                                style="width: {{ $batLevel }}%" aria-valuenow="{{ $batLevel }}"
                                aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="ms-2" style="font-size: 11px; font-weight: 600;">{{ $batLevel }}%</span>
                          </div>
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
                              <a class="dropdown-item" href="{{ route('perusahaan.sesi-alat.show', $item->id) }}">
                                <i class="ri-eye-line me-2 align-middle text-info"></i>Lihat Tracking
                              </a>
                            </li>
                            <li>
                              <form action="{{ route('perusahaan.sesi-alat.toggle', $item->id) }}" method="POST"
                                onsubmit="return confirm('Ubah status sesi ini?')">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="dropdown-item">
                                  <i
                                    class="ri-{{ $item->aktif ? 'stop-circle-line text-warning' : 'play-circle-line text-success' }} me-2 align-middle"></i>
                                  {{ $item->aktif ? 'Matikan Sesi' : 'Aktifkan Sesi' }}
                                </button>
                              </form>
                            </li>
                            <li>
                              <hr class="dropdown-divider">
                            </li>
                            <li>
                              <form action="{{ route('perusahaan.sesi-alat.destroy', $item->id) }}" method="POST"
                                onsubmit="return confirm('Hapus sesi ini? Data tracking mungkin akan hilang.')">
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

            <div class="d-flex justify-content-end border-top p-3">
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
              <p class="text-muted mb-4">Buat sesi baru untuk mulai menghubungkan Alat GPS dengan Kendaraan.</p>
              <a href="{{ route('perusahaan.sesi-alat.create') }}" class="btn btn-primary btn-wave">
                <i class="ri-add-line me-2"></i>Buat Sesi Sekarang
              </a>
            </div>
          @endif
        </div>
      </div>

    </div>
  </div>
@endsection

@section('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const dateElements = document.querySelectorAll('.local-date');

      dateElements.forEach(el => {
        const rawDate = el.textContent.trim();
        if (rawDate) {
          const date = new Date(rawDate);

          const formatted = new Intl.DateTimeFormat('id-ID', {
            day: 'numeric',
            month: 'short', // 'Des'
            year: 'numeric', // '2023'
            hour: '2-digit', // '14'
            minute: '2-digit', // '30'
            hour12: false // Format 24 Jam
          }).format(date);

          // Hasil contoh: "31 Des 2024 14:30"
          el.textContent = formatted;
        } else {
          el.textContent = '-';
        }
      });
    });
  </script>
@endsection
