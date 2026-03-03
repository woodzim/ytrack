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
      <h1 class="fw-bold">Buat Perjalanan Baru</h1>
      <p class="">Hubungkan Alat GPS dengan Kendaraan untuk memulai tracking</p>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-8 col-12">
      <div class="">
        <div class="card-body">
          <form action="{{ route('perusahaan.perjalanan.store') }}" method="POST">
            @csrf

            <div class="mb-4">
              <label for="nama" class="form-label">
                Nama Perjalanan <span class="text-danger">*</span>
              </label>
              <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
                placeholder="Contoh: Operasional Harian Truck 01" value="{{ old('nama') }}" required>
              <div class="form-text">Berikan nama yang mudah dikenali untuk perjalanan pemantauan ini.</div>
              @error('nama')
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>

            <div class="row mb-4">
              <div class="col-md-6">
                <label for="id_kendaraan" class="form-label">
                  Kendaraan <span class="text-danger">*</span>
                </label>
                <select class="form-select select-choices @error('id_kendaraan') is-invalid @enderror" id="id_kendaraan"
                  name="id_kendaraan" required>
                  <option value="">-- Pilih Kendaraan --</option>

                  @foreach ($kendaraan as $k)
                    @php
                      $isActive = $k->sesiAlat->isNotEmpty();
                      $info = $isActive ? 'Sedang aktif dalam perjalanan lain' : $k->tipe . ' - ' . $k->plat;
                    @endphp
                    <option value="{{ $k->id }}" {{ $isActive ? 'disabled' : '' }}
                      {{ old('id_kendaraan') == $k->id ? 'selected' : '' }}
                      data-active="{{ $isActive ? 'true' : 'false' }}" data-info="{{ $info }}">
                      {{ $k->nama }}
                    </option>
                  @endforeach
                </select>

                @if ($kendaraan->isEmpty())
                  <div class="form-text text-warning mt-1">
                    <i class="ri-alert-line"></i> Tidak ada kendaraan yang tersedia.
                  </div>
                @endif
                @error('id_kendaraan')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>

              <div class="col-md-6">
                <label for="id_alat" class="form-label">
                  Serial Number Alat (ID) <span class="text-danger">*</span>
                </label>
                <select class="form-select select-choices @error('id_alat') is-invalid @enderror" id="id_alat"
                  name="id_alat" required>
                  <option value="">-- Pilih Alat --</option>

                  @foreach ($alat as $a)
                    @php
                      $isActive = $a->sesiAlat->isNotEmpty();
                      $usedBy =
                          $isActive && $a->sesiAlat->first()->kendaraan
                              ? 'Dipakai di: ' . $a->sesiAlat->first()->kendaraan->nama
                              : 'Siap digunakan';
                    @endphp
                    <option value="{{ $a->id }}" {{ $isActive ? 'disabled' : '' }}
                      {{ old('id_alat') == $a->id ? 'selected' : '' }} data-active="{{ $isActive ? 'true' : 'false' }}"
                      data-info="{{ $usedBy }}">
                      {{ $a->id }}
                    </option>
                  @endforeach
                </select>

                @if ($alat->isEmpty())
                  <div class="form-text text-warning mt-1">
                    <i class="ri-alert-line"></i> Stok alat habis.
                  </div>
                @endif
                @error('id_alat')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>
            </div>

            <div class="mb-4 p-3 bg-light rounded border">
              <div class="form-check form-switch">
                <input type="hidden" name="aktif" value="0">
                <input class="form-check-input" type="checkbox" role="switch" id="aktif" name="aktif"
                  value="1" checked>
                <label class="form-check-label fw-medium" for="aktif">Aktifkan Perjalanan Sekarang?</label>
              </div>
              <small class="text-muted d-block mt-1">
                Jika diaktifkan, data lokasi dari alat akan mulai dicatat untuk kendaraan ini.
              </small>
            </div>

            <div class="d-flex gap-2 justify-content-end">
              <a href="{{ route('perusahaan.perjalanan.index') }}" class="btn btn-light">
                <i class="ri-close-line me-1"></i>Batal
              </a>
              <button type="submit" class="btn btn-primary"
                {{ $kendaraan->isEmpty() || $alat->isEmpty() ? 'disabled' : '' }}>
                <i class="ri-check-line me-1"></i>Simpan Perjalanan
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>

    {{-- Side Panel Info --}}
    <div class="col-lg-4 col-12">
      <div class="card bg-primary">
        <div class="card-body">
          <h6 class="fw-bold text-light mb-3">Informasi Penting</h6>
          <ul class="list-unstyled text-light mb-0 d-grid gap-3">
            <li class="d-flex align-items-start">
              <i class="ri-filter-3-line text-light me-3"></i>
              <div>
                <strong>Filter Otomatis</strong>
                <p class="small mb-0">Hanya kendaraan dan alat yang sedang <b>tidak aktif</b> yang dapat
                  dipilih.</p>
              </div>
            </li>
            <li class="d-flex align-items-start">
              <i class="ri-link-unlink-m text-light me-3"></i>
              <div>
                <strong>Pairing Data</strong>
                <p class="small mb-0">Perjalanan ini menghubungkan stream data koordinat alat ke database kendaraan.
                </p>
              </div>
            </li>
            <li class="d-flex align-items-start">
              <i class="ri-history-line text-light me-3"></i>
              <div>
                <strong>Selesaikan Dahulu</strong>
                <p class="small mb-0">Pastikan menyelesaikan perjalanan lama sebelum memindahkan alat.</p>
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
      const elements = document.querySelectorAll('.select-choices');

      elements.forEach(element => {
        new Choices(element, {
          allowHTML: true,
          placeholder: true,
          searchEnabled: true,
          itemSelectText: '',
          callbackOnCreateTemplates: function(strToEl) {
            return {
              item: ({
                classNames
              }, data) => {
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
              choice: ({
                classNames
              }, data) => {
                const isActive = data.customProperties && (data.customProperties.active === 'true' || data
                  .customProperties.active === true);
                const info = data.customProperties ? data.customProperties.info : '';

                return strToEl(`
                  <div class="${classNames.item} ${classNames.itemChoice} ${data.disabled ? classNames.itemDisabled : classNames.itemSelectable} ${isActive ? 'text-muted opacity-75' : ''}" 
                    data-select-text="${this.config.itemSelectText}" 
                    data-choice 
                    ${data.disabled ? 'data-choice-disabled aria-disabled="true"' : 'data-choice-selectable'} 
                    data-id="${data.id}" 
                    data-value="${data.value}" 
                    role="option">
                    <div class="d-flex justify-content-between align-items-center w-100">
                      <strong>${data.label}</strong>
                      ${data.value !== "" ? (isActive 
                        ? '<span class="badge bg-danger-transparent text-danger border border-danger border-opacity-25"><i class="ri-lock-line me-1"></i> Digunakan</span>' 
                        : '<span class="badge bg-success-transparent text-success border border-success border-opacity-25"><i class="ri-check-line me-1"></i> Tersedia</span>'
                      ) : ''}
                    </div>
                    ${info ? `<small class="d-block mt-1 ${isActive ? 'text-danger fst-italic' : 'text-muted'}" style="font-size:11px;"><i class="ri-information-line me-1"></i>${info}</small>` : ''}
                  </div>
                `);
              },
            };
          },
        });
      });
    });
  </script>
@endsection
