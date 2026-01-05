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

            {{-- Input ID / Serial Number --}}
            <div class="form-group-wrapper mb-4">
              <label for="id" class="form-label">Serial Number / ID Alat <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                  <i class="ri-barcode-line text-muted"></i>
                </span>
                <input type="text" id="id" name="id" class="form-control @error('id') is-invalid @enderror"
                  placeholder="Contoh: A0B1C2D3E4F5 atau ESP-123456" value="{{ old('id') }}" required>
              </div>

              @error('id')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror

              <div class="form-text text-muted">
                Lihat pada label fisik perangkat untuk Serial Number atau ID unik.
              </div>
            </div>

            {{-- Input Perusahaan --}}
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
          ID Alat adalah pengenal unik untuk setiap perangkat GPS. Pastikan ID yang dimasukkan sesuai dengan yang
          tertera pada perangkat keras (Hardware) agar koneksi data berfungsi dengan benar.
        </p>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
@endsection
