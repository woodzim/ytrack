@extends('layouts.master')

@section('content')
  <div class="page-title-section">
    <h1 class="fw-bold mb-2">Edit Data Kendaraan</h1>
    <p class="text-muted">Perbarui informasi kendaraan yang terdaftar</p>
  </div>
  <div class="row">
    <div class="col-12 col-lg-8">
      <div class="card form-card">
        <div class="card-body">
          <form action="{{ route('kendaraan.update', $kendaraan->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- 1. Pilih Perusahaan --}}
            <div class="form-group-wrapper mb-3">
              <label for="id_perusahaan" class="form-label fw-medium">
                Perusahaan Pemilik <span class="text-danger">*</span>
              </label>
              <select class="form-select @error('id_perusahaan') is-invalid @enderror" id="id_perusahaan"
                name="id_perusahaan" required>
                <option value="">-- Pilih Perusahaan --</option>
                @foreach ($perusahaan as $p)
                  <option value="{{ $p->id }}"
                    {{ old('id_perusahaan', $kendaraan->id_perusahaan) == $p->id ? 'selected' : '' }}>
                    {{ $p->nama }}
                  </option>
                @endforeach
              </select>
              @error('id_perusahaan')
                <div class="invalid-feedback d-block mt-2">
                  <i class="ri-error-warning-line me-1"></i>{{ $message }}
                </div>
              @enderror
            </div>

            {{-- 2. Pilih Jenis Kendaraan --}}
            <div class="form-group-wrapper mb-3">
              <label for="id_jenis_kendaraan" class="form-label fw-medium">
                Jenis Kendaraan <span class="text-danger">*</span>
              </label>
              <select class="form-select @error('id_jenis_kendaraan') is-invalid @enderror" id="id_jenis_kendaraan"
                name="id_jenis_kendaraan" required>
                <option value="">-- Pilih Jenis --</option>
                @foreach ($jenisKendaraan as $jk)
                  <option value="{{ $jk->id }}"
                    {{ old('id_jenis_kendaraan', $kendaraan->id_jenis_kendaraan) == $jk->id ? 'selected' : '' }}>
                    {{ $jk->nama }} ({{ $jk->kategori }})
                  </option>
                @endforeach
              </select>
              @error('id_jenis_kendaraan')
                <div class="invalid-feedback d-block mt-2">
                  <i class="ri-error-warning-line me-1"></i>{{ $message }}
                </div>
              @enderror
            </div>

            {{-- 3. Input Nama Kendaraan --}}
            <div class="form-group-wrapper mb-3">
              <label for="nama" class="form-label fw-medium">
                Nama/Kode Unit <span class="text-danger">*</span>
              </label>
              <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
                placeholder="Contoh: Truck 01" value="{{ old('nama', $kendaraan->nama) }}" required>
              @error('nama')
                <div class="invalid-feedback d-block mt-2">
                  <i class="ri-error-warning-line me-1"></i>{{ $message }}
                </div>
              @enderror
            </div>

            {{-- 4. Input Nomor Plat --}}
            <div class="form-group-wrapper mb-3">
              <label for="plat" class="form-label fw-medium">
                Nomor Plat <span class="text-danger">*</span>
              </label>
              <input type="text" class="form-control @error('plat') is-invalid @enderror" id="plat" name="plat"
                placeholder="Contoh: B 1234 XYZ" value="{{ old('plat', $kendaraan->plat) }}" required
                style="text-transform: uppercase;">
              @error('plat')
                <div class="invalid-feedback d-block mt-2">
                  <i class="ri-error-warning-line me-1"></i>{{ $message }}
                </div>
              @enderror
            </div>

            <div class="d-flex flex-column flex-sm-row gap-2 pt-3 border-top">
              <button type="submit" class="btn btn-primary btn-wave flex-sm-grow-1">
                <i class="ri-save-line me-2"></i>Simpan Perubahan
              </button>
              <a href="{{ route('kendaraan.index') }}" class="btn btn-outline-secondary btn-wave flex-sm-grow-1">
                <i class="ri-arrow-left-line me-2"></i>Kembali
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>

    {{-- Kolom Riwayat (Read Only) --}}
    <div class="col-12 col-lg-4">
      <div class="card">
        <div class="card-body">
          <h6 class="card-title mb-3"><i class="ri-history-line me-2"></i>Info Data</h6>
          <div class="small">
            <p class="text-muted mb-2">
              <strong>ID Sistem:</strong> {{ $kendaraan->id }}
            </p>
            <p class="text-muted mb-2">
              <strong>Dibuat:</strong><br>
              {{ $kendaraan->created_at->translatedFormat('d M Y H:i') }}
            </p>
            <p class="text-muted mb-0">
              <strong>Terakhir Update:</strong><br>
              {{ $kendaraan->updated_at->translatedFormat('d M Y H:i') }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
