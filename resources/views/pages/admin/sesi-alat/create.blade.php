@extends('layouts.master')

@section('styles')
  <style>
    /* Choices.js custom styling for better list visibility */
    .choices__list--dropdown .choices__item--selectable {
      padding-right: 10px;
    }
    .choices__list--dropdown .choices__item--disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }
  </style>
@endsection

@section('content')
  <div class="page-header-section d-flex align-items-center justify-content-between">
    <div>
      <h1 class="fw-bold">Buat Perjalanan</h1>
      <p class="">Tetapkan alat GPS untuk kendaraan berdasarkan perusahaan</p>
    </div>
  </div>

  <div class="row">
    {{-- KOLOM KIRI: FORM --}}
    <div class="col-lg-8 col-12">
      <div class="card custom-card">
        <div class="card-header py-3">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Informasi Sesi</h5>
          </div>
        </div>
        <div class="card-body">
          <form action="{{ route('perjalanan.store') }}" method="POST">
            @csrf

            {{-- 1. Pilih Perusahaan --}}
            <div class="mb-4">
              <label for="id_perusahaan" class="form-label">
                Perusahaan <span class="text-danger">*</span>
              </label>
              <select name="id_perusahaan" id="id_perusahaan"
                class="form-select select-choices @error('id_perusahaan') is-invalid @enderror" required>
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
                  class="form-select select-choices-ajax @error('id_kendaraan') is-invalid @enderror" required disabled>
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
                  class="form-select select-choices-ajax @error('id_alat') is-invalid @enderror" required disabled>
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
              <a href="{{ route('perjalanan.index') }}" class="btn btn-light">
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
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const oldKendaraan = "{{ old('id_kendaraan') }}";
      const oldAlat = "{{ old('id_alat') }}";

      const perusahaanSelect = document.getElementById('id_perusahaan');
      const kendaraanSelect = document.getElementById('id_kendaraan');
      const alatSelect = document.getElementById('id_alat');

      const commonConfig = {
        allowHTML: true,
        placeholder: true,
        itemSelectText: '',
      };

      const choicesConfigWithStatus = {
        ...commonConfig,
        callbackOnCreateTemplates: function(strToEl) {
          return {
            item: ({ classNames }, data) => {
              return strToEl(`
                <div class="${classNames.item} ${data.highlighted ? classNames.highlightedState : classNames.itemSelectable}" 
                  data-item 
                  data-id="${data.id}" 
                  data-value="${data.value}" 
                  ${data.active ? 'aria-selected="true"' : ''} 
                  ${data.disabled ? 'aria-disabled="true"' : ''}>
                  <span class="fw-bold">${data.label}</span>
                </div>
              `);
            },
            choice: ({ classNames }, data) => {
              const isActive = data.customProperties && (data.customProperties.active === 'true' || data.customProperties.active === true);
              const info = data.customProperties ? data.customProperties.info : '';
              
              return strToEl(`
                <div class="${classNames.item} ${classNames.itemChoice} ${data.disabled ? classNames.itemDisabled : classNames.itemSelectable} ${isActive ? 'text-muted opacity-75' : ''}" 
                  data-select-text="" 
                  data-choice 
                  ${data.disabled ? 'data-choice-disabled aria-disabled="true"' : 'data-choice-selectable'} 
                  data-id="${data.id}" 
                  data-value="${data.value}" 
                  role="option">
                  <div class="d-flex justify-content-between align-items-center w-100">
                    <strong>${data.label}</strong>
                    ${isActive 
                      ? '<span class="badge bg-danger-transparent text-danger border border-danger border-opacity-25"><i class="ri-lock-line me-1"></i> Digunakan</span>' 
                      : '<span class="badge bg-success-transparent text-success border border-success border-opacity-25"><i class="ri-check-line me-1"></i> Tersedia</span>'
                    }
                  </div>
                  ${info ? `<small class="d-block mt-1 ${isActive ? 'text-danger fst-italic' : 'text-muted'}" style="font-size:11px;">${info}</small>` : ''}
                </div>
              `);
            },
          };
        },
      };

      const choicesPerusahaan = new Choices(perusahaanSelect, commonConfig);
      const choicesKendaraan = new Choices(kendaraanSelect, choicesConfigWithStatus);
      const choicesAlat = new Choices(alatSelect, choicesConfigWithStatus);

      perusahaanSelect.addEventListener('change', function() {
        const idPerusahaan = this.value;

        // Reset and disable
        choicesKendaraan.clearChoices();
        choicesKendaraan.disable();
        choicesKendaraan.setChoices([{ value: '', label: '-- Loading... --', selected: true, disabled: true }]);

        choicesAlat.clearChoices();
        choicesAlat.disable();
        choicesAlat.setChoices([{ value: '', label: '-- Loading... --', selected: true, disabled: true }]);

        if (!idPerusahaan) {
          const placeholder = [{ value: '', label: '-- Pilih Perusahaan Terlebih Dahulu --', selected: true, disabled: true }];
          choicesKendaraan.setChoices(placeholder);
          choicesAlat.setChoices(placeholder);
          return;
        }

        // --- AJAX KENDARAAN ---
        fetch(`{{ url('/perjalanan-kendaraan') }}/${idPerusahaan}`)
          .then(res => res.json())
          .then(data => {
            choicesKendaraan.clearChoices();
            if (data.length > 0) {
              const options = data.map(item => ({
                value: item.id,
                label: item.nama,
                selected: oldKendaraan == item.id && !item.is_active,
                disabled: item.is_active,
                customProperties: {
                  active: item.is_active,
                  info: item.is_active ? 'Kendaraan sedang dalam sesi aktif' : `<i class="ri-info-card-line"></i> ${item.plat}`
                }
              }));
              choicesKendaraan.setChoices([{ value: '', label: '-- Pilih Kendaraan --', selected: true, disabled: false }, ...options], 'value', 'label', true);
              choicesKendaraan.enable();
            } else {
              choicesKendaraan.setChoices([{ value: '', label: 'Tidak ada kendaraan tersedia', selected: true, disabled: true }]);
            }
          });

        // --- AJAX ALAT ---
        fetch(`{{ url('/perjalanan-alat') }}/${idPerusahaan}`)
          .then(res => res.json())
          .then(data => {
            choicesAlat.clearChoices();
            if (data.length > 0) {
              const options = data.map(item => ({
                value: item.id,
                label: item.id,
                selected: oldAlat == item.id && !item.is_active,
                disabled: item.is_active,
                customProperties: {
                  active: item.is_active,
                  info: item.is_active ? (item.current_vehicle ? `Sedang dipakai di: ${item.current_vehicle}` : 'Sedang Aktif') : 'Siap digunakan'
                }
              }));
              choicesAlat.setChoices([{ value: '', label: '-- Pilih Alat --', selected: true, disabled: false }, ...options], 'value', 'label', true);
              choicesAlat.enable();
            } else {
              choicesAlat.setChoices([{ value: '', label: 'Tidak ada alat tersedia', selected: true, disabled: true }]);
            }
          });
      });

      if (perusahaanSelect.value) {
        perusahaanSelect.dispatchEvent(new Event('change'));
      }
    });
  </script>
@endsection
