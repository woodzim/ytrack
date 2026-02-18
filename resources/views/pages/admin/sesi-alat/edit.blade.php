@extends('layouts.master')

@section('styles')
  @vite(['resources/assets/scss/select2.scss', 'resources/assets/scss/select2-bootstrap-theme.scss'])
  <style>
    /* Styling tambahan agar opsi disabled terlihat lebih jelas */
    .select2-results__option[aria-disabled="true"] {
      background-color: #f8f9fa;
      color: #d6d6d6;
      font-style: italic;
    }
  </style>
@endsection

@section('content')
  <div class="page-header-section d-flex align-items-center justify-content-between">
    <div>
      <h1>Edit Perjalanan</h1>
      <p>Ubah konfigurasi perjalanan kendaraan</p>
    </div>
  </div>
  <div class="row pb-3">
    <div class="col-12">
      <div class="form-card">
        <div class="card-header">
          <h5>Edit Sesi</h5>
        </div>
        <div class="card-body">
          <div class="meta-info mb-4 p-3 bg-light rounded border">
            <div class="row">
              <div class="col-md-4"><strong>ID Sesi:</strong> #{{ $sesiAlat->id }}</div>
              <div class="col-md-4"><strong>Status:</strong>
                @if ($sesiAlat->aktif)
                  <span class="badge bg-success">Aktif</span>
                @else
                  <span class="badge bg-secondary">Non-Aktif</span>
                @endif
              </div>
              <div class="col-md-4"><strong>Dibuat:</strong> {{ $sesiAlat->created_at->translatedFormat('d F Y H:i') }}
              </div>
            </div>
          </div>

          <form action="{{ route('sesi-alat.update', $sesiAlat->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
              <label for="id_kendaraan" class="form-label">
                Kendaraan <span class="text-danger">*</span>
              </label>
              <select name="id_kendaraan" id="id_kendaraan"
                class="form-select @error('id_kendaraan') is-invalid @enderror" required>
                @if ($sesiAlat->kendaraan)
                  <option value="{{ $sesiAlat->id_kendaraan }}" selected>{{ $sesiAlat->kendaraan->nama }}</option>
                @endif
              </select>
              @error('id_kendaraan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-4">
              <label for="id_alat" class="form-label">
                Alat GPS <span class="text-danger">*</span>
              </label>
              <select name="id_alat" id="id_alat" class="form-select @error('id_alat') is-invalid @enderror" required>
                @if ($sesiAlat->alat)
                  <option value="{{ $sesiAlat->id_alat }}" selected>{{ $sesiAlat->alat->id }}</option>
                @endif
              </select>
              @error('id_alat')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-4">
              <label for="nama" class="form-label">
                Nama Sesi <span class="text-muted">(Opsional)</span>
              </label>
              <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                placeholder="Contoh: Sesi Tracking Koransi" value="{{ old('nama', $sesiAlat->nama) }}">
              @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary btn-wave">
                <i class="ri-save-line me-2"></i>Simpan Perubahan
              </button>
              <a href="{{ route('sesi-alat.index') }}" class="btn btn-light btn-wave">
                <i class="ri-close-line me-2"></i>Batal
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  @vite(['resources/assets/js/jquery.js', 'resources/assets/js/select2.js'])

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Data ID saat ini (Data asli sesi ini)
      // Kita butuh ini agar data milik sesi ini TIDAK ter-disabled
      const currentKendaraanId = "{{ $sesiAlat->id_kendaraan }}";
      const currentAlatId = "{{ $sesiAlat->id_alat }}";

      // Inisialisasi Element
      const $kendaraanSelect = $('#id_kendaraan');
      const $alatSelect = $('#id_alat');

      // 1. Setup Select2 Dasar
      $kendaraanSelect.select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: 'Loading...'
      });
      $alatSelect.select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: 'Loading...'
      });

      // 2. Fungsi Load Data (Kendaraan & Alat)
      function loadDataByPerusahaan(idPerusahaan) {
        if (!idPerusahaan) return;

        // --- A. LOAD KENDARAAN ---
        // Ganti URL ini sesuai route Anda: 'sesi-alat-kendaraan/{idPerusahaan}'
        fetch(`{{ url('sesi-alat-kendaraan') }}/${idPerusahaan}`)
          .then(res => res.json())
          .then(data => {
            // ... (logika update dropdown kendaraan sama seperti sebelumnya) ...
            $kendaraanSelect.empty().append('<option value="">-- Pilih Kendaraan --</option>');

            data.forEach(item => {
              let isDisabled = item.is_active && (String(item.id) !== String(currentKendaraanId));
              let label = item.nama + " (" + item.plat + ")";

              if (isDisabled) label += " -- [Sedang Digunakan]";

              let newOption = new Option(label, item.id, false, false);

              if (isDisabled) $(newOption).attr('disabled', 'disabled');

              if (String(item.id) === String(currentKendaraanId)) {
                $(newOption).prop('selected', true);
              }

              $kendaraanSelect.append(newOption);
            });
            $kendaraanSelect.trigger('change');
          })
          .catch(err => console.error('Error fetching kendaraan:', err));


        // --- B. LOAD ALAT ---
        // Ganti URL ini sesuai route Anda: 'sesi-alat-alat/{idPerusahaan}'
        fetch(`{{ url('sesi-alat-alat') }}/${idPerusahaan}`)
          .then(res => res.json())
          .then(data => {
            // ... (logika update dropdown alat sama seperti sebelumnya) ...
            $alatSelect.empty().append('<option value="">-- Pilih Alat --</option>');

            data.forEach(item => {
              let isDisabled = item.is_active && (String(item.id) !== String(currentAlatId));
              let label = item.id;

              if (isDisabled) label += ` -- [Dipakai: ${item.current_vehicle || 'Lain'}]`;

              let newOption = new Option(label, item.id, false, false);

              if (isDisabled) $(newOption).attr('disabled', 'disabled');

              if (String(item.id) === String(currentAlatId)) {
                $(newOption).prop('selected', true);
              }

              $alatSelect.append(newOption);
            });
            $alatSelect.trigger('change');
          })
          .catch(err => console.error('Error fetching alat:', err));
      }

      // 3. Trigger saat halaman pertama dimuat
      // Agar dropdown langsung terisi dengan data yang benar & status disable yang tepat
      loadDataByPerusahaan("{{ $sesiAlat->id_perusahaan }}");
    });
  </script>
@endsection
