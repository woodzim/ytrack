@extends('layouts.master')

@section('content')
  <div class="form-header mb-4">
    <div class="d-flex align-items-center justify-content-between">
      <div>
        <h1>Edit Alat</h1>
        <p>Perbarui data perangkat {{ $alat->kode_alat }}</p>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12 col-lg-8">
      <div class="card form-card">
        <div class="card-header border-bottom bg-white py-3">
          <h5 class="card-title mb-0"><i class="ri-edit-2-line me-2"></i>Form Edit Data</h5>
        </div>
        <div class="card-body p-4">
          <form action="{{ route('alat.update', $alat->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- 1. Kode Alat (Read Only / Tidak Bisa Diedit) --}}
            <div class="form-group-wrapper mb-4">
              <label class="form-label">Kode Alat (System)</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                  <i class="ri-barcode-box-line text-muted"></i>
                </span>
                <input type="text" class="form-control bg-light fw-bold text-dark" value="{{ $alat->kode_alat }}"
                  disabled style="cursor: not-allowed;">
              </div>
              <div class="form-text text-muted">
                Kode sistem bersifat permanen dan tidak dapat diubah.
              </div>
            </div>

            {{-- 2. Serial Number / ID Alat --}}
            <div class="form-group-wrapper mb-4">
              <label for="id" class="form-label">Serial Number / ID Alat <span class="text-danger">*</span></label>
              <input type="text" id="id" name="id" class="form-control @error('id') is-invalid @enderror"
                placeholder="Contoh: A0B1C2D3E4F5" value="{{ old('id', $alat->id) }}" required>

              @error('id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror

              <div class="form-text text-muted">
                Pastikan ID ini sesuai dengan stiker fisik atau MAC Address pada modul ESP.
              </div>
            </div>

            {{-- 3. Pilihan Perusahaan (Opsional) --}}
            <div class="form-group-wrapper mb-4">
              <label for="id_perusahaan" class="form-label">Perusahaan / Lokasi</label>
              <select name="id_perusahaan" id="id_perusahaan"
                class="form-select @error('id_perusahaan') is-invalid @enderror">
                <option value="">-- Belum Ditempatkan --</option>
                @foreach ($listPerusahaan as $p)
                  <option value="{{ $p->id }}"
                    {{ old('id_perusahaan', $alat->id_perusahaan) == $p->id ? 'selected' : '' }}>
                    {{ $p->nama }}
                  </option>
                @endforeach
              </select>
              @error('id_perusahaan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-actions d-flex gap-2 mt-4">
              <button type="submit" class="btn btn-primary btn-wave">
                <i class="ri-save-line me-2"></i>Simpan Perubahan
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
      <div class="card form-card">
        <div class="card-header border-bottom bg-white py-3">
          <h5 class="card-title mb-0"><i class="ri-history-line me-2"></i>Log Sistem</h5>
        </div>
        <div class="card-body p-4">
          <div class="mb-3">
            <small class="text-muted d-block mb-1">Dibuat pada:</small>
            <div class="fw-medium">
              {{ $alat->created_at->translatedFormat('d F Y') }}
              <span class="text-muted text-xs dynamic-date">({{ $alat->created_at->format('H:i') }})</span>
            </div>
          </div>
          <div class="mb-0">
            <small class="text-muted d-block mb-1">Terakhir diperbarui:</small>
            <div class="fw-medium">
              {{ $alat->updated_at->translatedFormat('d F Y') }}
              <span class="text-muted text-xs">({{ $alat->updated_at->format('H:i') }})</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
