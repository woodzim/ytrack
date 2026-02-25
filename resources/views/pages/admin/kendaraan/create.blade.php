@extends('layouts.master')

@section('content')
  <div class="page-header-section d-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="page-title fw-medium fs-20 mb-0">Tambah Kendaraan Baru</h1>
      <p class="">Daftarkan aset kendaraan ke dalam sistem</p>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="">
        <div class="">
          <form action="{{ route('kendaraan.store') }}" method="POST">
            @csrf

            {{-- 1. Pilih Perusahaan (Foreign Key) --}}
            <div class="form-group-wrapper mb-3">
              <label for="id_perusahaan" class="form-label fw-medium">
                Perusahaan Pemilik <span class="text-danger">*</span>
              </label>
              <select class="form-select @error('id_perusahaan') is-invalid @enderror" id="id_perusahaan"
                name="id_perusahaan" required>
                <option value="">-- Pilih Perusahaan --</option>
                @foreach ($perusahaan as $p)
                  <option value="{{ $p->id }}" {{ old('id_perusahaan') == $p->id ? 'selected' : '' }}>
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

            {{-- 2. Pilih Jenis Kendaraan (Foreign Key) --}}
            <div class="form-group-wrapper mb-3">
              <label for="id_jenis_kendaraan" class="form-label fw-medium">
                Jenis Kendaraan <span class="text-danger">*</span>
              </label>
              <select class="form-select @error('id_jenis_kendaraan') is-invalid @enderror" id="id_jenis_kendaraan"
                name="id_jenis_kendaraan" required>
                <option value="">-- Pilih Jenis --</option>
                @foreach ($jenisKendaraan as $jk)
                  <option value="{{ $jk->id }}" {{ old('id_jenis_kendaraan') == $jk->id ? 'selected' : '' }}>
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
                placeholder="Contoh: Truck 01, Unit A-55" value="{{ old('nama') }}" required>
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
                placeholder="Contoh: B 1234 XYZ" value="{{ old('plat') }}" required style="text-transform: uppercase;">
              <small class="text-muted">Gunakan huruf kapital tanpa spasi berlebih.</small>
              @error('plat')
                <div class="invalid-feedback d-block mt-2">
                  <i class="ri-error-warning-line me-1"></i>{{ $message }}
                </div>
              @enderror
            </div>

            <div class="d-flex flex-column flex-sm-row gap-2 pt-3 border-top">
              <button type="submit" class="btn btn-primary btn-wave flex-sm-grow-1">
                <i class="ri-save-line me-2"></i>Simpan Kendaraan
              </button>
              <a href="{{ route('kendaraan.index') }}" class="btn btn-outline-secondary btn-wave flex-sm-grow-1">
                <i class="ri-arrow-left-line me-2"></i>Kembali
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
