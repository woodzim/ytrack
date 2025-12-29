@extends('layouts.master')

@section('content')
  <div class="detail-header mb-4">
    <div class="d-flex align-items-center justify-content-between">
      <div>
        {{-- Judul Utama: Kode Sistem (YTR_XXX) --}}
        <h1 class="mb-0 fw-bold">{{ $alat->kode_alat }}</h1>

        {{-- Subjudul: Serial Number / ID Alat --}}
        <div class="d-flex align-items-center mt-1">
          <i class="ri-barcode-line me-2"></i>
          <span>Serial Number: {{ $alat->id }}</span>
        </div>
      </div>

      <div>
        {{-- Badge Status di Header --}}
        @if ($alat->is_aktif)
          <span class="badge bg-success-transparent fs-13">
            <i class="ri-wifi-line me-1"></i> Online
          </span>
        @else
          <span class="badge bg-light text-muted border fs-13">
            <i class="ri-zzz-line me-1"></i> Offline
          </span>
        @endif
      </div>
    </div>
  </div>
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

                {{-- Row 2: Serial Number / ID Alat (Fisik) --}}
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
                  <th class="ps-4 py-3 text-muted">Perusahaan / Lokasi

                    [Image of office building icon]
                  </th>
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

                {{-- Row 4: Tanggal --}}
                <tr>
                  <th class="ps-4 py-3 text-muted">Didaftarkan Pada</th>
                  <td class="py-3">
                    {{ $alat->created_at->translatedFormat('d F Y') }}
                    <small class="text-muted ms-1">({{ $alat->created_at->format('H:i') }})</small>
                  </td>
                </tr>
                <tr>
                  <th class="ps-4 py-3 text-muted">Terakhir Update</th>
                  <td class="py-3">
                    {{ $alat->updated_at->translatedFormat('d F Y') }}
                    <small class="text-muted ms-1">({{ $alat->updated_at->format('H:i') }})</small>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="card-footer bg-light border-top">
          <div class="d-flex gap-2">
            {{-- Tombol Lihat Sesi --}}
            <a href="{{ route('sesi-alat.index', ['search' => $alat->id]) }}" class="btn btn-primary btn-wave">
              <i class="ri-history-line me-2"></i>Riwayat Sesi
            </a>

            {{-- Tombol Edit --}}
            <a href="{{ route('alat.edit', $alat->id) }}" class="btn btn-warning btn-wave text-white">
              <i class="ri-edit-line me-2"></i>Edit Data
            </a>

            {{-- Tombol Kembali --}}
            <a href="{{ route('alat.index') }}" class="btn btn-outline-secondary btn-wave ms-auto">
              <i class="ri-arrow-left-line me-2"></i>Kembali
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-4">
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
              {{ $alat->sesiAlat()->latest()->first()?->created_at->diffForHumans() ?? '-' }}
            </span>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection
