@extends('layouts.master')

@section('styles')
  {{-- 1. Gunakan CDN agar Select2 & Style termuat sempurna --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

  <style>
    /* Agar tinggi input Select2 pas dengan konten custom (badge) */
    .select2-container .select2-selection--single {
      height: 48px !important;
      display: flex;
      align-items: center;
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
      display: block;
      width: 100%;
      padding-top: 4px;
    }
  </style>
@endsection

@section('content')
  <div class="page-header-section d-flex align-items-center justify-content-between">
    <div>
      <h1 class="fw-bold">Buat Sesi Alat</h1>
      <p class="">Tetapkan alat GPS untuk kendaraan berdasarkan perusahaan</p>
    </div>
  </div>

  <div class="row">
    {{-- KOLOM KIRI: FORM --}}
    <div class="col-lg-8 col-12">
      <div class="card custom-card">
        <div class="card-header">
          <div class="card-title">Informasi Sesi</div>
        </div>
        <div class="card-body">
          <form action="{{ route('sesi-alat.store') }}" method="POST">
            @csrf

            {{-- 1. Pilih Perusahaan --}}
            <div class="mb-4">
              <label for="id_perusahaan" class="form-label">
                Perusahaan <span class="text-danger">*</span>
              </label>
              <select name="id_perusahaan" id="id_perusahaan"
                class="form-select select2-custom @error('id_perusahaan') is-invalid @enderror" required>
                <option value="">-- Pilih Perusahaan --</option>
                @foreach ($perusahaan as $item)
                  <option value="{{ $item->id }}" {{ old('id_perusahaan') == $item->id ? 'selected' : '' }}>
                    {{ $item->nama }}
                  </option>
                @endforeach
              </select>
              @error('id_perusahaan')
                <small class="text-danger d-block mt-1">{{ $message }}</small>
              @enderror
            </div>

            <div class="row mb-4">
              {{-- 2. Pilih Kendaraan (Load via AJAX) --}}
              <div class="col-md-6">
                <label for="id_kendaraan" class="form-label">
                  Kendaraan <span class="text-danger">*</span>
                </label>
                <select name="id_kendaraan" id="id_kendaraan"
                  class="form-select select2-custom @error('id_kendaraan') is-invalid @enderror" required disabled>
                  <option value="">-- Pilih Kendaraan --</option>
                </select>
                @error('id_kendaraan')
                  <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror
              </div>

              {{-- 3. Pilih Alat (Load via AJAX) --}}
              <div class="col-md-6">
                <label for="id_alat" class="form-label">
                  Serial Number Alat <span class="text-danger">*</span>
                </label>
                <select name="id_alat" id="id_alat"
                  class="form-select select2-custom @error('id_alat') is-invalid @enderror" required disabled>
                  <option value="">-- Pilih Alat --</option>
                </select>
                @error('id_alat')
                  <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror
              </div>
            </div>

            <div class="mb-4">
              <label for="nama" class="form-label">
                Nama Sesi <span class="text-muted fw-normal">(Opsional)</span>
              </label>
              <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                placeholder="Contoh: Sesi Tracking Koransi" value="{{ old('nama') }}">
              <small class="text-muted d-block mt-1">Berikan nama unik untuk membedakan sesi jika diperlukan</small>
              @error('nama')
                <small class="text-danger d-block mt-1">{{ $message }}</small>
              @enderror
            </div>

            <div class="d-flex gap-2 justify-content-end border-top pt-3">
              <a href="{{ route('sesi-alat.index') }}" class="btn btn-light">
                <i class="ri-close-line me-1"></i>Batal
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="ri-check-line me-1"></i>Buat Sesi
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    {{-- KOLOM KANAN: INFO --}}
    <div class="col-lg-4 col-12">
      <div class="card custom-card bg-primary-transparent">
        <div class="card-body">
          <h6 class="fw-bold text-primary mb-3">Informasi Penting</h6>

          <ul class="list-unstyled mb-0 d-grid gap-3">
            <li class="d-flex align-items-start">
              <i class="ri-filter-3-line text-primary me-3 mt-1"></i>
              <div>
                <strong>Filter Aset Otomatis</strong>
                <p class="small text-muted mb-0">Hanya kendaraan dan alat milik perusahaan terpilih yang muncul.</p>
              </div>
            </li>
            <li class="d-flex align-items-start">
              <i class="ri-lock-line text-primary me-3 mt-1"></i>
              <div>
                <strong>Status Ketersediaan</strong>
                <p class="small text-muted mb-0">Item yang sedang aktif di sesi lain akan ditandai "Digunakan" dan tidak
                  bisa dipilih.</p>
              </div>
            </li>
            <li class="d-flex align-items-start">
              <i class="ri-car-line text-primary me-3 mt-1"></i>
              <div>
                <strong>Satu Kendaraan per Alat</strong>
                <p class="small text-muted mb-0">Setiap kendaraan hanya dapat memiliki satu alat aktif sekaligus.</p>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <script>
    $(document).ready(function() {
      const oldKendaraan = "{{ old('id_kendaraan') }}";
      const oldAlat = "{{ old('id_alat') }}";

      // ============================================
      // 1. FORMATTER FUNCTION (Hanya untuk Kendaraan & Alat)
      // ============================================
      function formatOption(state) {
        if (!state.id) return state.text;

        const element = state.element;
        const isActive = $(element).data('active') === true || $(element).data('active') === "true";
        const info = $(element).data('info');

        let $container = $('<div></div>');

        if (isActive) {
          // ITEM DIGUNAKAN
          $container.addClass('text-muted opacity-75');
          $container.append(
            `<div class="d-flex justify-content-between align-items-center">
                <strong>${state.text}</strong>
                <span class="badge bg-danger-transparent text-danger border border-danger border-opacity-25">
                    <i class="ri-lock-line me-1"></i>Digunakan
                </span>
            </div>`
          );
          if (info) {
            $container.append(
              `<small class="d-block mt-1 text-danger fst-italic" style="font-size:11px;"><i class="ri-information-line me-1"></i>${info}</small>`
            );
          }
        } else {
          // ITEM TERSEDIA
          $container.append(
            `<div class="d-flex justify-content-between align-items-center">
                <strong>${state.text}</strong>
                <span class="badge bg-success-transparent text-success border border-success border-opacity-25">
                    <i class="ri-check-line me-1"></i>Tersedia
                </span>
            </div>`
          );
          if (info) {
            $container.append(`<small class="d-block mt-1 text-muted" style="font-size:11px;">${info}</small>`);
          }
        }
        return $container;
      }

      // ============================================
      // 2. INIT SELECT2 (DIPISAH)
      // ============================================

      // A. Config Standar untuk PERUSAHAAN (Tanpa Badge Status)
      $('#id_perusahaan').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: '-- Pilih Perusahaan --'
      });

      // B. Config Custom untuk KENDARAAN & ALAT (Dengan Badge Status)
      const configWithStatus = {
        theme: 'bootstrap-5',
        width: '100%',
        templateResult: formatOption, // Pakai formatter custom
        templateSelection: formatOption, // Pakai formatter custom
        escapeMarkup: function(m) {
          return m;
        }
      };

      $('#id_kendaraan').select2({
        ...configWithStatus,
        placeholder: ' Pilih Kendaraan '
      });
      $('#id_alat').select2({
        ...configWithStatus,
        placeholder: ' Pilih Alat (Serial Number) '
      });


      // ============================================
      // 3. LOGIC AJAX
      // ============================================
      $('#id_perusahaan').on('change', function() {
        const idPerusahaan = $(this).val();
        const $kendaraanSelect = $('#id_kendaraan');
        const $alatSelect = $('#id_alat');

        // Reset Dropdowns
        $kendaraanSelect.empty().append('<option value="">-- Loading... --</option>').prop('disabled', true)
          .trigger('change');
        $alatSelect.empty().append('<option value="">-- Loading... --</option>').prop('disabled', true).trigger(
          'change');

        if (!idPerusahaan) {
          $kendaraanSelect.empty().append('<option value="">-- Pilih Perusahaan Terlebih Dahulu --</option>');
          $alatSelect.empty().append('<option value="">-- Pilih Perusahaan Terlebih Dahulu --</option>');
          return;
        }

        // --- AJAX KENDARAAN ---
        $.ajax({
          url: `{{ url('/sesi-alat-kendaraan') }}/${idPerusahaan}`,
          type: 'GET',
          dataType: 'json',
          success: function(data) {
            $kendaraanSelect.empty().append('<option value="">-- Pilih Kendaraan --</option>');

            if (data.length > 0) {
              $.each(data, function(key, item) {
                let label = item.nama;
                // if (item.plat) label += ` (${item.plat})`;

                let info = item.is_active ? 'Kendaraan sedang dalam sesi aktif' : (
                  `<i class="ri-info-card-line"></i> ${item.plat}` ||
                  'Siap digunakan');

                let newOption = new Option(label, item.id, false, false);

                // Inject data attributes
                $(newOption).attr('data-active', item.is_active);
                $(newOption).attr('data-info', info);

                if (item.is_active) {
                  $(newOption).prop('disabled', true);
                }

                if (oldKendaraan == item.id && !item.is_active) {
                  $(newOption).prop('selected', true);
                }

                $kendaraanSelect.append(newOption);
              });
              $kendaraanSelect.prop('disabled', false);
            } else {
              $kendaraanSelect.append('<option value="">Tidak ada kendaraan tersedia</option>');
            }
            $kendaraanSelect.trigger('change');
          }
        });

        // --- AJAX ALAT ---
        $.ajax({
          url: `{{ url('/sesi-alat-alat') }}/${idPerusahaan}`,
          type: 'GET',
          dataType: 'json',
          success: function(data) {
            $alatSelect.empty().append('<option value="">-- Pilih Alat --</option>');

            if (data.length > 0) {
              $.each(data, function(key, item) {
                let info = item.is_active ?
                  (item.current_vehicle ? `Sedang dipakai di: ${item.current_vehicle}` :
                    'Sedang Aktif') :
                  'Siap digunakan';

                // UPDATE: Menggunakan item.id (Serial Number) sebagai teks tampilan
                let newOption = new Option(item.id, item.id, false, false);

                $(newOption).attr('data-active', item.is_active);
                $(newOption).attr('data-info', info);

                if (item.is_active) {
                  $(newOption).prop('disabled', true);
                }

                if (oldAlat == item.id && !item.is_active) {
                  $(newOption).prop('selected', true);
                }

                $alatSelect.append(newOption);
              });
              $alatSelect.prop('disabled', false);
            } else {
              $alatSelect.append('<option value="">Tidak ada alat tersedia</option>');
            }
            $alatSelect.trigger('change');
          }
        });
      });

      if ($('#id_perusahaan').val()) {
        $('#id_perusahaan').trigger('change');
      }

    });
  </script>
@endsection
