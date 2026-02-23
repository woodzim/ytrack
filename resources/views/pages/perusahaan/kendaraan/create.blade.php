@extends('layouts.master')

@section('content')
  <div class="page-title-section mb-4">
    <h1 class="page-title fw-medium fs-20 mb-0">Tambah Kendaraan Baru</h1>
    <p class="text-muted">Daftarkan aset kendaraan ke dalam sistem</p>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card form-card">
        <div class="card-body">

          {{-- Pastikan action mengarah ke perusahaan.kendaraan.store --}}
          <form action="{{ route('perusahaan.kendaraan.store') }}" method="POST">
            @csrf

            {{-- 1. Pilih Jenis Kendaraan --}}
            <div class="mb-3">
              <label for="id_jenis_kendaraan" class="form-label fw-medium">
                Jenis Kendaraan <span class="text-danger">*</span>
              </label>
              <select class="form-select @error('id_jenis_kendaraan') is-invalid @enderror" id="id_jenis_kendaraan"
                name="id_jenis_kendaraan" required>
                <option value="">-- Pilih Jenis --</option>
                @foreach ($jenisKendaraan as $jk)
                  <option value="{{ $jk->id }}" {{ old('id_jenis_kendaraan') == $jk->id ? 'selected' : '' }}>
                    {{ $jk->nama }}
                  </option>
                @endforeach
              </select>
              @error('id_jenis_kendaraan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- 2. Input Nama Kendaraan --}}
            <div class="mb-3">
              <label for="nama" class="form-label fw-medium">
                Nama Kendaraan <span class="text-danger">*</span>
              </label>
              <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
                placeholder="Contoh: Truck 01, Unit A-55" value="{{ old('nama') }}" required>
              @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- 3. Input Nomor Plat --}}
            <div class="mb-3">
              <label for="plat" class="form-label fw-medium">
                Nomor Plat <span class="text-danger">*</span>
              </label>
              <input type="text" class="form-control @error('plat') is-invalid @enderror" id="plat" name="plat"
                placeholder="Contoh: B 1234 XYZ" value="{{ old('plat') }}" required style="text-transform: uppercase;">
              <small class="text-muted">Gunakan huruf kapital tanpa spasi.</small>
              @error('plat')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Tombol Aksi --}}
            <div class="d-flex gap-2 pt-3 border-top">
              <button type="submit" class="btn btn-primary btn-wave">
                <i class="ri-save-line me-2"></i>Simpan Kendaraan
              </button>
              <a href="{{ route('perusahaan.kendaraan.index') }}" class="btn btn-light btn-wave">
                <i class="ri-arrow-left-line me-2"></i>Kembali
              </a>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
