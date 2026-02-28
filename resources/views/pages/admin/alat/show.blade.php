@extends('layouts.master')

@section('content')
  <div class="page-header-section d-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="page-title fw-medium fs-20 mb-0">Detail Alat</h1>
      <p>Daftar Alat</p>
    </div>
  </div>

  <div class="row">
    <div class="col-12 col-lg-8">
      <div class="card card-detail">
        <div class="card-header py-3">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="ri-file-list-3-line me-2"></i>Informasi Perangkat</h5>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-borderless mb-0">
              <tbody>
                {{-- Row 1: Serial Number --}}
                <tr>
                  <th class="ps-4 py-3 text-muted" style="width: 35%;">Serial Number / ID Alat</th>
                  <td class="py-3">
                    <strong class="text-dark fs-16 font-monospace">{{ $alat->id }}</strong>
                    <div class="small text-muted mt-1">
                      <i class="ri-qr-code-line me-1"></i>ID Unik Fisik
                    </div>
                  </td>
                </tr>

                {{-- Row 2: Perusahaan --}}
                <tr>
                  <th class="ps-4 py-3 text-muted">Perusahaan / Lokasi</th>
                  <td class="py-3">
                    @if ($alat->perusahaan)
                      <div class="d-flex align-items-center">
                        <span class="avatar avatar-xs avatar-rounded bg-primary-transparent text-primary me-2">
                          <i class="ri-building-line"></i>
                        </span>
                        <a href="#" class="fw-medium text-primary text-decoration-none">
                          {{ $alat->perusahaan->nama }}
                        </a>
                      </div>
                    @else
                      <span class="text-muted fst-italic">
                        <i class="ri-error-warning-line me-1"></i>Belum ditempatkan
                      </span>
                    @endif
                  </td>
                </tr>

                {{-- Row 3: Tanggal Didaftarkan --}}
                <tr>
                  <th class="ps-4 py-3 text-muted">Didaftarkan Pada</th>
                  <td class="py-3">
                    <span class="local-date">{{ $alat->created_at->toIso8601String() }}</span>
                    <small class="text-muted ms-1">
                      (<span class="local-time">{{ $alat->created_at->toIso8601String() }}</span>)
                    </small>
                  </td>
                </tr>

                {{-- Row 4: Terakhir Update --}}
                <tr>
                  <th class="ps-4 py-3 text-muted">Terakhir Update</th>
                  <td class="py-3">
                    <span class="local-date">{{ $alat->updated_at->toIso8601String() }}</span>
                    <small class="text-muted ms-1">
                      (<span class="local-time">{{ $alat->updated_at->toIso8601String() }}</span>)
                    </small>
                  </td>
                </tr>

              </tbody>
            </table>
          </div>
        </div>

        <div class="card-footer bg-light border-top">
          <div class="d-flex gap-2">
            {{-- Tombol Lihat Riwayat Sesi (Filter by ID) --}}
            <a href="{{ route('perjalanan.index', ['search' => $alat->id]) }}" class="btn btn-primary btn-wave">
              <i class="ri-history-line me-2"></i>Riwayat Sesi
            </a>
            <a href="{{ route('alat.edit', $alat->id) }}" class="btn btn-warning btn-wave text-white">
              <i class="ri-edit-line me-2"></i>Edit Data
            </a>
            <a href="{{ route('alat.index') }}" class="btn btn-outline-secondary btn-wave ms-auto">
              <i class="ri-arrow-left-line me-2"></i>Kembali
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- Kolom Kanan (Status & Statistik) --}}
    <div class="col-12 col-lg-4">
      <div class="card card-detail mb-3">
        <div class="card-header py-3">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="ri-dashboard-3-line me-2"></i>Status Terkini</h5>
          </div>
        </div>
        <div class="card-body text-center p-4">
          <div class="mb-3">
            {{-- Menggunakan properti sedang_digunakan atau is_aktif sesuai model --}}
            @if ($alat->sedang_digunakan ?? $alat->is_aktif)
              <div class="avatar avatar-xl bg-success-transparent text-success mb-3 rounded-circle">
                <i class="ri-broadcast-line fs-24"></i>
              </div>
              <h5 class="fw-bold text-success mb-1">Sedang Aktif</h5>
              <p class="text-muted small mb-0">Perangkat sedang mengirimkan data lokasi.</p>
            @else
              <div class="avatar avatar-xl bg-light text-muted mb-3 rounded-circle border">
                <i class="ri-shut-down-line fs-24"></i>
              </div>
              <h5 class="fw-bold text-muted mb-1">Tidak Aktif</h5>
              <p class="text-muted small mb-0">Perangkat sedang tidak digunakan.</p>
            @endif
          </div>
        </div>
      </div>

      <div class="card card-detail">
        <div class="card-body">
          <h6 class="fw-semibold mb-3">Statistik Singkat</h6>
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-muted">Total Sesi</span>
            <span class="fw-bold">{{ $alat->sesiAlat()->count() }} x</span>
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <span class="text-muted">Perjalanan Terakhir</span>
            <span class="fw-bold text-end">
              {{ $alat->sesiAlat()->latest()->first()?->created_at->diffForHumans() ?? '-' }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', () => {

      // 1. Format Tanggal
      function convertDate(dateString) {
        if (!dateString) return '-';
        return new Intl.DateTimeFormat('id-ID', {
          day: 'numeric',
          month: 'long',
          year: 'numeric'
        }).format(new Date(dateString));
      }

      // 2. Format Jam
      function convertTime(dateString) {
        if (!dateString) return '-';
        return new Intl.DateTimeFormat('id-ID', {
          hour: '2-digit',
          minute: '2-digit',
          hour12: false
        }).format(new Date(dateString)).replace(/\./g, ':');
      }

      // Jalankan Konversi
      document.querySelectorAll('.local-date').forEach(el => el.textContent = convertDate(el.textContent));
      document.querySelectorAll('.local-time').forEach(el => el.textContent = convertTime(el.textContent));

    });
  </script>
@endsection
