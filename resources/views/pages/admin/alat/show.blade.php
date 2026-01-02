@extends('layouts.master')

@section('content')
  {{-- ... (Header & Badge Status Biarkan Saja) ... --}}

  <div class="row">
    <div class="col-12 col-lg-8">
      <div class="card card-detail">
        <div class="card-header border-bottom bg-white py-3">
          <h5 class="card-title mb-0"><i class="ri-file-list-3-line me-2"></i>Informasi Perangkat</h5>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-borderless mb-0">
              <tbody>
                {{-- Row 1: Kode Sistem --}}
                <tr>
                  <th class="ps-4 py-3 text-muted" style="width: 35%;">Kode Sistem</th>
                  <td class="py-3">
                    <span class="badge bg-primary-transparent fs-14">{{ $alat->kode_alat }}</span>
                  </td>
                </tr>

                {{-- Row 2: Serial Number --}}
                <tr>
                  <th class="ps-4 py-3 text-muted">Serial Number / ID Alat</th>
                  <td class="py-3">
                    <strong class="text-dark fs-15">{{ $alat->id }}</strong>
                    <div class="small text-muted mt-1">
                      <i class="ri-qr-code-line me-1"></i>ID Unik Fisik
                    </div>
                  </td>
                </tr>

                {{-- Row 3: Perusahaan --}}
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

                {{-- UPDATE ROW 4: TANGGAL DIDAFTARKAN --}}
                <tr>
                  <th class="ps-4 py-3 text-muted">Didaftarkan Pada</th>
                  <td class="py-3">
                    {{-- Tanggal --}}
                    <span class="local-date">{{ $alat->created_at->toIso8601String() }}</span>
                    {{-- Jam (dalam kurung) --}}
                    <small class="text-muted ms-1">
                      (<span class="local-time">{{ $alat->created_at->toIso8601String() }}</span>)
                    </small>
                  </td>
                </tr>

                {{-- UPDATE ROW 5: TERAKHIR UPDATE --}}
                <tr>
                  <th class="ps-4 py-3 text-muted">Terakhir Update</th>
                  <td class="py-3">
                    {{-- Tanggal --}}
                    <span class="local-date">{{ $alat->updated_at->toIso8601String() }}</span>
                    {{-- Jam (dalam kurung) --}}
                    <small class="text-muted ms-1">
                      (<span class="local-time">{{ $alat->updated_at->toIso8601String() }}</span>)
                    </small>
                  </td>
                </tr>

              </tbody>
            </table>
          </div>
        </div>

        {{-- Footer Buttons Biarkan Saja --}}
        <div class="card-footer bg-light border-top">
          {{-- ... --}}
          <div class="d-flex gap-2">
            <a href="{{ route('sesi-alat.index', ['search' => $alat->id]) }}" class="btn btn-primary btn-wave">
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

    {{-- Kolom Kanan (Status & Statistik) Biarkan Saja --}}
    <div class="col-12 col-lg-4">
      {{-- ... content kanan ... --}}
      <div class="card card-detail mb-3">
        <div class="card-header border-bottom bg-white py-3">
          <h5 class="card-title mb-0"><i class="ri-dashboard-3-line me-2"></i>Status Terkini</h5>
        </div>
        <div class="card-body text-center p-4">
          <div class="mb-3">
            @if ($alat->is_aktif)
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
              {{-- DiffForHumans biasanya sudah cukup oke (misal: 2 jam yang lalu) --}}
              {{-- Tapi jika ingin 100% akurat browser, biarkan saja sbg teks server side untuk durasi --}}
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

      // 1. Format Tanggal (25 Desember 2023)
      function convertDate(dateString) {
        if (!dateString) return '-';
        return new Intl.DateTimeFormat('id-ID', {
          day: 'numeric',
          month: 'long',
          year: 'numeric'
        }).format(new Date(dateString));
      }

      // 2. Format Jam (14:30)
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
