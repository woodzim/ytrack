@extends('layouts.master')

@section('content')
  <div class="form-header">
    <h1>Edit Data Alat</h1>
    <p>Perbarui informasi perangkat GPS</p>
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

            {{-- 1. Serial Number / ID Alat --}}
            <div class="form-group-wrapper mb-4">
              <label for="id" class="form-label">Serial Number / ID Alat <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                  <i class="ri-barcode-line text-muted"></i>
                </span>
                <input type="text" id="id" name="id" class="form-control @error('id') is-invalid @enderror"
                  placeholder="Contoh: A0B1C2D3E4F5" value="{{ old('id', $alat->id) }}" required>
              </div>

              @error('id')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror

              <div class="form-text text-muted">
                Pastikan ID ini sesuai dengan stiker fisik atau MAC Address pada modul ESP.
              </div>
            </div>

            {{-- 2. Pilihan Perusahaan (Opsional) --}}
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

              <div class="form-text text-muted">
                Pilih perusahaan tempat alat ini dipasang.
              </div>

              @error('id_perusahaan')
                <div class="invalid-feedback d-block">{{ $message }}</div>
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

    {{-- SIDEBAR KANAN: LOG SISTEM --}}
    <div class="col-12 col-lg-4">
      <div class="card form-card">
        <div class="card-header border-bottom bg-white py-3">
          <h5 class="card-title mb-0"><i class="ri-history-line me-2"></i>Log Sistem</h5>
        </div>
        <div class="card-body p-4">
          <div class="mb-3">
            <small class="text-muted d-block mb-1">Dibuat pada:</small>
            <div class="fw-medium">
              <span class="local-date">{{ $alat->created_at->toIso8601String() }}</span>
              <span class="text-muted text-xs">
                (<span class="local-time">{{ $alat->created_at->toIso8601String() }}</span>)
              </span>
            </div>
          </div>
          <div class="mb-0">
            <small class="text-muted d-block mb-1">Terakhir diperbarui:</small>
            <div class="fw-medium">
              <span class="local-date">{{ $alat->updated_at->toIso8601String() }}</span>
              <span class="text-muted text-xs">
                (<span class="local-time">{{ $alat->updated_at->toIso8601String() }}</span>)
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // 1. Format Tanggal
      function convertDate(dateString) {
        if (!dateString) return '-';
        return new Intl.DateTimeFormat('id-ID', {
          day: 'numeric',
          month: 'long',
          year: 'numeric'
        }).format(new Date(dateString));
      }

      // 2. Format Jam
      function convertTime(dateString) {
        if (!dateString) return '-';
        return new Intl.DateTimeFormat('id-ID', {
          hour: '2-digit',
          minute: '2-digit',
          hour12: false
        }).format(new Date(dateString)).replace(/\./g, ':');
      }

      // Eksekusi perubahan teks di HTML
      document.querySelectorAll('.local-date').forEach(el => el.textContent = convertDate(el.textContent));
      document.querySelectorAll('.local-time').forEach(el => el.textContent = convertTime(el.textContent));
    });
  </script>
@endsection
