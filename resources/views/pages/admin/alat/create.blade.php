@extends('layouts.master')

@section('content')
  <div class="form-header">
    <h1>Tambah Alat Baru</h1>
    <p>Daftarkan perangkat GPS baru ke sistem</p>
  </div>
  <div class="row">
    <div class="col-12 col-lg-8">
      <div class="card form-card">
        <div class="card-header border-bottom bg-white">
          <h5 class="card-title mb-0"><i class="ri-information-line me-2"></i>Informasi Alat</h5>
        </div>
        <div class="card-body p-4">
          <form action="{{ route('alat.store') }}" method="POST">
            @csrf
            {{-- 1. Input Kode Alat (Disabled / Read-Only) --}}
            <div class="form-group-wrapper mb-4">
              <label class="form-label">Kode Alat</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                  <i class="ri-barcode-box-line text-muted"></i>
                </span>
                {{-- TAMPILKAN NEXT KODE DISINI --}}
                <input type="text" class="form-control bg-light fw-bold text-muted" value="{{ $nextKode }}"
                  disabled style="cursor: not-allowed;">
              </div>
              <div class="form-text text-muted">
                <i class="ri-information-line me-1"></i>
                Sistem akan otomatis menggunakan kode <strong>{{ $nextKode }}</strong> ini saat disimpan.
              </div>
            </div>

            {{-- 2. Input Serial Number (Fisik) --}}
            {{-- 2. Input Serial Number (Fisik) --}}
            <div class="form-group-wrapper mb-4">
              <label for="id" class="form-label">Serial Number / ID Alat <span
                  class="text-danger">*</span></label>
              <input type="text" id="id" name="id" class="form-control @error('id') is-invalid @enderror"
                placeholder="Contoh: A0B1C2D3E4F5 atau ESP-123456" value="{{ old('id') }}" required>

              @error('id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror

              <div class="form-text text-muted">
                Lihat pada alat untuk serial number / ID alat.
              </div>
            </div>

            <div class="form-group-wrapper mb-4">
              <label for="id_perusahaan" class="form-label">Perusahaan (Opsional)</label>
              <select id="id_perusahaan" name="id_perusahaan"
                class="form-select @error('id_perusahaan') is-invalid @enderror">
                <option value="">-- Tidak Ada / Belum Ditentukan --</option>
                @foreach ($listPerusahaan as $p)
                  <option value="{{ $p->id }}" {{ old('id_perusahaan') == $p->id ? 'selected' : '' }}>
                    {{ $p->nama }}
                  </option>
                @endforeach
              </select>

              <div class="form-text text-muted">
                Anda bisa memilih perusahaan nanti jika belum ada datanya.
              </div>

              @error('id_perusahaan')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-actions mt-4">
              <button type="submit" class="btn btn-primary btn-wave">
                <i class="ri-save-line me-2"></i>Simpan Alat
              </button>
              <a href="{{ route('alat.index') }}" class="btn btn-outline-secondary btn-wave">
                <i class="ri-close-line me-2"></i>Batal
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="form-info-box">
        <h6><i class="ri-lightbulb-line me-2"></i>Informasi Penting</h6>
        <p>
          ID Alat adalah pengenal unik untuk setiap perangkat GPS. Masukkan ID unik atau Serial Number (SN) sesuai yang
          tertera pada label fisik perangkat GPS.
        </p>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
@endsection
