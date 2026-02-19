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
                class="form-select select-choices-ajax @error('id_kendaraan') is-invalid @enderror" required>
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
              <select name="id_alat" id="id_alat" class="form-select select-choices-ajax @error('id_alat') is-invalid @enderror" required>
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
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const currentKendaraanId = "{{ $sesiAlat->id_kendaraan }}";
      const currentAlatId = "{{ $sesiAlat->id_alat }}";
      const idPerusahaan = "{{ $sesiAlat->id_perusahaan }}";

      const kendaraanSelect = document.getElementById('id_kendaraan');
      const alatSelect = document.getElementById('id_alat');

      const choicesConfigWithStatus = {
        allowHTML: true,
        placeholder: true,
        itemSelectText: '',
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

      const choicesKendaraan = new Choices(kendaraanSelect, choicesConfigWithStatus);
      const choicesAlat = new Choices(alatSelect, choicesConfigWithStatus);

      function loadData() {
        if (!idPerusahaan) return;

        // --- A. LOAD KENDARAAN ---
        fetch(`{{ url('sesi-alat-kendaraan') }}/${idPerusahaan}`)
          .then(res => res.json())
          .then(data => {
            choicesKendaraan.clearChoices();
            const options = data.map(item => {
              const isDisabled = item.is_active && (String(item.id) !== String(currentKendaraanId));
              return {
                value: item.id,
                label: item.nama,
                selected: String(item.id) === String(currentKendaraanId),
                disabled: isDisabled,
                customProperties: {
                  active: item.is_active && (String(item.id) !== String(currentKendaraanId)),
                  info: item.is_active && (String(item.id) !== String(currentKendaraanId)) ? 'Kendaraan sedang dalam sesi aktif' : `${item.plat}`
                }
              };
            });
            choicesKendaraan.setChoices([{ value: '', label: '-- Pilih Kendaraan --', selected: false }, ...options], 'value', 'label', true);
          })
          .catch(err => console.error('Error fetching kendaraan:', err));

        // --- B. LOAD ALAT ---
        fetch(`{{ url('sesi-alat-alat') }}/${idPerusahaan}`)
          .then(res => res.json())
          .then(data => {
            choicesAlat.clearChoices();
            const options = data.map(item => {
              const isDisabled = item.is_active && (String(item.id) !== String(currentAlatId));
              return {
                value: item.id,
                label: item.id,
                selected: String(item.id) === String(currentAlatId),
                disabled: isDisabled,
                customProperties: {
                  active: item.is_active && (String(item.id) !== String(currentAlatId)),
                  info: item.is_active && (String(item.id) !== String(currentAlatId)) ? (item.current_vehicle ? `Sedang dipakai di: ${item.current_vehicle}` : 'Sedang Aktif') : 'Siap digunakan'
                }
              };
            });
            choicesAlat.setChoices([{ value: '', label: '-- Pilih Alat --', selected: false }, ...options], 'value', 'label', true);
          })
          .catch(err => console.error('Error fetching alat:', err));
      }

      loadData();
    });
  </script>
@endsection
