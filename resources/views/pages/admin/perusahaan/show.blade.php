@extends('layouts.master')

@section('content')
  <div class="detail-header">
    <div>
      <h1 class="mb-0">{{ $perusahaan->nama }}</h1>
    </div>
  </div>
  <div class="row">
    {{-- Kolom Kiri: Informasi Perusahaan --}}
    <div class="col-12 col-lg-8">
      <div class="card card-detail">
        <div class="card-header border-bottom bg-white">
          <h5 class="card-title mb-0"><i class="ri-information-line me-2"></i>Informasi Lengkap</h5>
        </div>
        <div class="card-body">
          <div class="detail-row">
            <span class="detail-label">ID Perusahaan</span>
            <span class="detail-value">
              <strong>{{ $perusahaan->id }}</strong>
            </span>
          </div>

          <div class="detail-row">
            <span class="detail-label">Nama Perusahaan</span>
            <span class="detail-value">
              <strong>{{ $perusahaan->nama }}</strong>
            </span>
          </div>

          <div class="detail-row">
            <span class="detail-label">Alamat</span>
            <span class="detail-value">
              {{ $perusahaan->alamat }}
            </span>
          </div>

          <div class="detail-row">
            <span class="detail-label">Dibuat Pada</span>
            <span class="detail-value">
              {{ $perusahaan->created_at->translatedFormat('d M Y') }}
              <div class="detail-value-meta">Pukul {{ $perusahaan->created_at->translatedFormat('H:i') }}
              </div>
            </span>
          </div>

          <div class="detail-row">
            <span class="detail-label">Diperbarui Pada</span>
            <span class="detail-value">
              {{ $perusahaan->updated_at->translatedFormat('d M Y') }}
              <div class="detail-value-meta">Pukul {{ $perusahaan->updated_at->translatedFormat('H:i') }}
              </div>
            </span>
          </div>
        </div>

        {{-- Tombol Aksi Perusahaan --}}
        <div style="padding: 16px; background: #f9f9f9; border-top: 1px solid #f0f0f0; border-radius: 0 0 8px 8px;">
          <div class="btn-action-group">
            <a href="{{ route('perusahaan.edit', $perusahaan->id) }}" class="btn btn-primary btn-wave">
              <i class="ri-edit-line me-2"></i>Edit Perusahaan
            </a>
            <a href="{{ route('perusahaan.index') }}" class="btn btn-outline-secondary btn-wave">
              <i class="ri-arrow-left-line me-2"></i>Kembali
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- Kolom Kanan: Statistik & Daftar Aset --}}
    <div class="col-12 col-lg-4">

      <div class="card card-detail mb-4">
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <div class="text-center">
                <h3 class="mb-1 text-primary">{{ $perusahaan->kendaraan->count() }}</h3>
                <p class="text-muted small mb-0">Total Kendaraan</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card card-detail">
        <div class="card-header border-bottom bg-white">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="ri-truck-line me-2"></i>Daftar Kendaraan</h5>
          </div>
        </div>
        <div class="card-body p-0">
          @if ($perusahaan->kendaraan->count() > 0)
            <ul class="list-group list-group-flush">
              @foreach ($perusahaan->kendaraan as $kendaraan)
                <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                  <div>
                    <h6 class="mb-1 fw-bold">{{ $kendaraan->nama }}</h6>
                    <span class="badge bg-light text-dark border">{{ $kendaraan->plat }}</span>
                  </div>
                  {{-- Jika ingin link ke detail kendaraan, bisa dibuka komentar di bawah ini --}}
                  {{-- <a href="{{ route('kendaraan.show', $kendaraan->id) }}" class="btn btn-sm btn-light"><i class="ri-arrow-right-s-line"></i></a> --}}
                </li>
              @endforeach
            </ul>
          @else
            <div class="p-4 text-center">
              <p class="text-muted mb-0">Belum ada kendaraan yang terdaftar.</p>
            </div>
          @endif
        </div>
      </div>

    </div>
  </div>
@endsection

@section('scripts')
@endsection
