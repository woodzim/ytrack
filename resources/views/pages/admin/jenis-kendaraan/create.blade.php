@extends('layouts.master')

@section('content')
  <div class="page-title-section">
    <h1 class="page-title fw-medium fs-20 mb-0">Tambah Jenis Kendaraan Baru</h1>
    <p class="text-muted">Isikan informasi untuk mengkategorikan tipe kendaraan</p>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card form-card">
        <div class="card-body">
          <form action="{{ route('jenis-kendaraan.store') }}" method="POST">
            @csrf

            {{-- Input Nama Jenis (WAJIB) --}}
            <div class="form-group-wrapper mb-3">
              <label for="nama" class="form-label fw-medium">
                Nama Jenis <span class="text-danger">*</span>
              </label>
              <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
                placeholder="Contoh: Truck Engkel, Pick Up, Minibus" value="{{ old('nama') }}" required>
              @error('nama')
                <div class="invalid-feedback d-block mt-2">
                  <i class="ri-error-warning-line me-1"></i>{{ $message }}
                </div>
              @enderror
            </div>

            {{-- Input Kategori (WAJIB) --}}
            {{-- PERBAIKAN: name="keterangan" diubah menjadi name="kategori" --}}
            <div class="form-group-wrapper mb-3">
              <label for="kategori" class="form-label fw-medium">
                Kategori <span class="text-danger">*</span>
              </label>

              <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="Kendaraan" {{ old('kategori') == 'Kendaraan' ? 'selected' : '' }}>Kendaraan</option>
                <option value="Container" {{ old('kategori') == 'Container' ? 'selected' : '' }}>Container</option>
              </select>

              @error('kategori')
                <div class="invalid-feedback d-block mt-2">
                  <i class="ri-error-warning-line me-1"></i>{{ $message }}
                </div>
              @enderror
            </div>

            <div class="d-flex flex-column flex-sm-row gap-2 pt-3 border-top">
              <button type="submit" class="btn btn-primary btn-wave flex-sm-grow-1">
                <i class="ri-save-line me-2"></i>Simpan Jenis
              </button>
              <a href="{{ route('jenis-kendaraan.index') }}" class="btn btn-outline-secondary btn-wave flex-sm-grow-1">
                <i class="ri-arrow-left-line me-2"></i>Kembali
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
@endsection
