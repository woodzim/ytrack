@extends('layouts.master')

@section('content')
  <div class="page-header-section d-flex align-items-center justify-content-between">
    <div>
      <h1>Sesi Alat</h1>
      <p>Kelola sesi penggunaan alat GPS di kendaraan</p>
    </div>
    <div class="ms-auto">
      <a href="{{ route('sesi-alat.create') }}" class="btn btn-light btn-wave">
        <i class="ri-add-line me-2"></i>Buat Sesi
      </a>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card mb-3">
        <div class="card-body">
          <form action="{{ route('sesi-alat.index') }}" method="GET" class="d-flex gap-2 flex-wrap align-items-end">
            <div class="grow" style="min-width: 200px;">
              <label for="searchInput" class="form-label mb-2">Cari Sesi</label>
              <input type="text" id="searchInput" name="search" class="form-control"
                placeholder="Cari alat atau kendaraan..." value="{{ $search }}">
            </div>
            <div>
              <label for="statusFilter" class="form-label mb-2">Status</label>
              <select id="statusFilter" name="status" class="form-select">
                <option value="">Semua</option>
                <option value="1" {{ $filter_status === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ $filter_status === '0' ? 'selected' : '' }}>Nonaktif</option>
              </select>
            </div>
            <button type="submit" class="btn btn-primary btn-wave">
              <i class="ri-search-line me-2"></i>Cari
            </button>
            @if ($search || $filter_status !== '')
              <a href="{{ route('sesi-alat.index') }}" class="btn btn-outline-secondary btn-wave">
                <i class="ri-close-line me-2"></i>Reset
              </a>
            @endif
          </form>
        </div>
      </div>

      <div class="card table-wrapper">
        <div class="card-header border-bottom-0 bg-white py-3">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Sesi Alat ({{ $sesiAlat->total() }})</h5>
          </div>
        </div>
        <div class="card-body p-0">
          @if ($sesiAlat->count() > 0)
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead class="table-light">
                  <tr>
                    <th style="width: 10%;">Perusahaan</th>
                    <th style="width: 15%;">Kendaraan</th>
                    <th style="width: 12%;">Alat</th>
                    <th style="width: 15%;">Nama Sesi</th>
                    <th style="width: 12%;">Status</th>
                    <th style="width: 12%;">Status Battery</th>
                    <th style="width: 16%;">Waktu Track Terakhir</th>
                    <th style="width: 15%;" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($sesiAlat as $item)
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
                      <td data-label="Nama Sesi">
                        <span class="fw-medium {{ empty($item->nama) ? 'fst-italic text-muted' : '' }}">
                          {{ $item->nama ?? '(Sesi Tanpa Nama)' }}
                        </span>
                      </td>
                      <td data-label="Status">
                        <span class="status-badge"
                          style="background-color: {{ $item->aktif ? '#10b981' : '#6b7280' }}; color: white;">
                          {{ $item->aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                      </td>
                      <td data-label="Status Battery">
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
                        @if ($lastRiwayat && $lastRiwayat->battery !== null)
                          <div class="d-flex align-items-center" style="min-width: 80px;">
                            <div class="progress grow" style="height: 6px; background-color: #e9ecef;">
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

                      {{-- Bagian Tanggal Diubah Disini --}}
                      <td data-label="Tanggal Track">
                        @if ($item->riwayatPerjalanan?->isEmpty())
                          <small class="text-muted">Belum ada track</small>
                        @else
                          {{-- Mengambil max date dan mengubah ke ISO String agar dibaca JS --}}
                          <small class="text-muted local-date">
                            {{ \Carbon\Carbon::parse($item->riwayatPerjalanan()->max('created_at'))->toIso8601String() }}
                          </small>
                        @endif
                      </td>

                      <td data-label="Aksi" class="table-action-btns text-end text-md-start">
                        <div class="mb-2">
                          <a href="{{ route('sesi-alat.show', $item->id) }}" class="w-100 btn btn-sm btn-info btn-wave"
                            title="Lihat">
                            <i class="ri-eye-line"></i>
                            Lihat Tracking
                          </a>
                        </div>
                        <div class="mb-2">
                          <form action="{{ route('sesi-alat.toggle', $item->id) }}" method="POST"
                            style="display: inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit"
                              class="w-100 btn btn-sm {{ $item->aktif ? 'btn-warning' : 'btn-success' }} btn-wave"
                              title="{{ $item->aktif ? 'Nonaktifkan' : 'Aktifkan' }}">
                              <i class="ri-{{ $item->aktif ? 'pause' : 'play' }}-line"></i>
                              {{ $item->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                          </form>
                        </div>
                        <div>
                          <form action="{{ route('sesi-alat.destroy', $item->id) }}" method="POST"
                            style="display: inline;" onsubmit="return confirm('Hapus sesi ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-100 btn btn-sm btn-primary btn-wave" title="Hapus">
                              <i class="ri-delete-bin-line"></i>
                              Hapus
                            </button>
                          </form>
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
              <h5 class="text-muted mb-2">Tidak ada sesi alat</h5>
              <p class="text-muted mb-3">Mulai dengan membuat sesi alat baru</p>
              <a href="{{ route('sesi-alat.create') }}" class="btn btn-primary btn-wave">
                <i class="ri-add-line me-2"></i>Buat Sesi
              </a>
            </div>
          @endif
        </div>

        @if ($sesiAlat->hasPages())
          <div class="card-footer bg-white border-top">
            <nav aria-label="Page navigation">
              {{ $sesiAlat->links() }}
            </nav>
          </div>
        @endif
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

          // Format: 31 Des 2024, 14:30 (Termasuk Jam karena ini tracking)
          const formatted = new Intl.DateTimeFormat('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
          }).format(date);

          el.textContent = formatted;
        }
      });
    });
  </script>
@endsection
