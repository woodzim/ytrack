@extends('layouts.master')

@section('content')
  <div class="page-title-section">
    <h1 class="fw-bold mb-2">Edit Jenis Kendaraan</h1>
    <p class="text-muted">Perbarui informasi kategori kendaraan</p>
  </div>
  <div class="row">
    <div class="col-12 col-lg-8">
      <div class="card form-card">
        <div class="card-body">
          {{-- Form Update --}}
          <form action="{{ route('jenis-kendaraan.update', $jenisKendaraan->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Input Nama Jenis --}}
            <div class="form-group-wrapper mb-3">
              <label for="nama" class="form-label fw-medium">
                Nama Jenis <span class="text-danger">*</span>
              </label>
              <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
                placeholder="Contoh: Truck Engkel" value="{{ old('nama', $jenisKendaraan->nama) }}" required>
              @error('nama')
                <div class="invalid-feedback d-block mt-2">
                  <i class="ri-error-warning-line me-1"></i>{{ $message }}
                </div>
              @enderror
            </div>

            {{-- Input Kategori (Sekarang name="kategori") --}}
            <div class="form-group-wrapper mb-3">
              <label for="kategori" class="form-label fw-medium">
                Kategori <span class="text-danger">*</span>
              </label>

              {{-- Perubahan di sini: name="kategori" dan id="kategori" --}}
              <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="Kendaraan"
                  {{ old('kategori', $jenisKendaraan->kategori) == 'Kendaraan' ? 'selected' : '' }}>
                  Kendaraan
                </option>
                <option value="Container"
                  {{ old('kategori', $jenisKendaraan->kategori) == 'Container' ? 'selected' : '' }}>
                  Container
                </option>
              </select>

              @error('kategori')
                <div class="invalid-feedback d-block mt-2">
                  <i class="ri-error-warning-line me-1"></i>{{ $message }}
                </div>
              @enderror
            </div>

            <div class="d-flex flex-column flex-sm-row gap-2 pt-3 border-top">
              <button type="submit" class="btn btn-primary btn-wave flex-sm-grow-1">
                <i class="ri-save-line me-2"></i>Perbarui Data
              </button>
              <a href="{{ route('jenis-kendaraan.index') }}" class="btn btn-outline-secondary btn-wave flex-sm-grow-1">
                <i class="ri-arrow-left-line me-2"></i>Kembali
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>

    {{-- Kolom Riwayat --}}
    <div class="col-12 col-lg-4">
      <div class="card">
        <div class="card-body">
          <h6 class="card-title mb-3"><i class="ri-history-line me-2"></i>Riwayat</h6>
          <div class="small">
            <p class="text-muted mb-2">
              <strong>ID:</strong> {{ $jenisKendaraan->id }}
            </p>
            <p class="text-muted mb-2">
              <strong>Dibuat:</strong><br>
              {{ $jenisKendaraan->created_at->translatedFormat('d M Y H:i') }}
            </p>
            <p class="text-muted mb-0">
              <strong>Diperbarui:</strong><br>
              {{ $jenisKendaraan->updated_at->translatedFormat('d M Y H:i') }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
