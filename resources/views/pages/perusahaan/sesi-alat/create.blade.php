@extends('layouts.master')

@section('styles')
  {{-- Gunakan CDN untuk memastikan Style Select2 termuat --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

  <style>
    /* CSS Tambahan agar tinggi input pas dengan konten custom */
    .select2-container .select2-selection--single {
      height: 48px !important;
      /* Dipertinggi */
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
      <h1 class="fw-bold">Buat Perjalanan Baru</h1>
      <p class="">Hubungkan Alat GPS dengan Kendaraan untuk memulai tracking</p>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-8 col-12">
      <div class="card custom-card">
        <div class="card-header">
          <div class="card-title">Formulir Sesi</div>
        </div>
        <div class="card-body">
          <form action="{{ route('perusahaan.sesi-alat.store') }}" method="POST">
            @csrf

            <div class="mb-4">
              <label for="nama" class="form-label">
                Nama Sesi <span class="text-danger">*</span>
              </label>
              <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
                placeholder="Contoh: Operasional Harian Truck 01" value="{{ old('nama') }}" required>
              <div class="form-text">Berikan nama yang mudah dikenali untuk sesi pemantauan ini.</div>
              @error('nama')
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>

            <div class="row mb-4">
              <div class="col-md-6">
                <label for="id_kendaraan" class="form-label">
                  Kendaraan <span class="text-danger">*</span>
                </label>
                <select class="form-select select2-custom @error('id_kendaraan') is-invalid @enderror" id="id_kendaraan"
                  name="id_kendaraan" required>
                  <option value="">-- Pilih Kendaraan --</option>

                  @foreach ($kendaraan as $k)
                    @php
                      $isActive = $k->sesiAlat->isNotEmpty();
                      $info = $isActive ? 'Sedang aktif dalam sesi lain' : $k->tipe . ' - ' . $k->plat;
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
                <select class="form-select select2-custom @error('id_alat') is-invalid @enderror" id="id_alat"
                  name="id_alat" required>
                  <option value="">-- Pilih Alat --</option>

                  @foreach ($alat as $a)
                    @php
                      $isActive = $a->sesiAlat->isNotEmpty();
                      // Info logic: Jika aktif tampilkan dipakai dimana, jika tidak tampilkan status siap
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
                <label class="form-check-label fw-medium" for="aktif">Aktifkan Sesi Sekarang?</label>
              </div>
              <small class="text-muted d-block mt-1">
                Jika diaktifkan, data lokasi dari alat akan mulai dicatat untuk kendaraan ini.
              </small>
            </div>

            <div class="d-flex gap-2 justify-content-end border-top pt-3">
              <a href="{{ route('perusahaan.sesi-alat.index') }}" class="btn btn-light">
                <i class="ri-close-line me-1"></i>Batal
              </a>
              <button type="submit" class="btn btn-primary"
                {{ $kendaraan->isEmpty() || $alat->isEmpty() ? 'disabled' : '' }}>
                <i class="ri-check-line me-1"></i>Simpan Sesi
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>

    {{-- Side Panel Info --}}
    <div class="col-lg-4 col-12">
      <div class="card custom-card bg-primary-transparent">
        <div class="card-body">
          <h6 class="fw-bold text-primary mb-3">Informasi Penting</h6>
          <ul class="list-unstyled mb-0 d-grid gap-3">
            <li class="d-flex align-items-start">
              <i class="ri-filter-3-line text-primary me-3"></i>
              <div>
                <strong>Filter Otomatis</strong>
                <p class="small text-muted mb-0">Hanya kendaraan dan alat yang sedang <b>tidak aktif</b> yang dapat
                  dipilih.</p>
              </div>
            </li>
            <li class="d-flex align-items-start">
              <i class="ri-link-unlink-m text-primary me-3"></i>
              <div>
                <strong>Pairing Data</strong>
                <p class="small text-muted mb-0">Sesi ini menghubungkan stream data koordinat alat ke database kendaraan.
                </p>
              </div>
            </li>
            <li class="d-flex align-items-start">
              <i class="ri-history-line text-primary me-3"></i>
              <div>
                <strong>Nonaktifkan Dahulu</strong>
                <p class="small text-muted mb-0">Pastikan menonaktifkan sesi lama sebelum memindahkan alat.</p>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  {{-- PENTING: Gunakan CDN urutan: JQuery -> Select2 --}}
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <script>
    $(document).ready(function() {

      // Fungsi Formatter (Untuk isi Dropdown & Box Terpilih)
      function formatOption(state) {
        if (!state.id) {
          return state.text; // Placeholder
        }

        // Ambil data atribut dari element <option> asli
        const element = state.element;
        const isActive = $(element).data('active') === true || $(element).data('active') === "true";
        const info = $(element).data('info');

        let $container = $('<div></div>');

        if (isActive) {
          // Style untuk ITEM YANG DISABLED
          $container.addClass('text-muted opacity-75');
          $container.append(
            `<div class="d-flex justify-content-between align-items-center">
                <strong>${state.text}</strong>
                <span class="badge bg-danger-transparent text-danger border border-danger border-opacity-25">
                    <i class="ri-lock-line me-1"></i> Digunakan
                </span>
            </div>`
          );
          if (info) {
            $container.append(
              `<small class="d-block mt-1 text-danger fst-italic" style="font-size:11px;">
                 <i class="ri-information-line me-1"></i>${info}
              </small>`
            );
          }
        } else {
          // Style untuk ITEM YANG AVAILABLE
          $container.append(
            `<div class="d-flex justify-content-between align-items-center">
                <strong>${state.text}</strong>
                <span class="badge bg-success-transparent text-success border border-success border-opacity-25">
                    <i class="ri-check-line me-1"></i> Tersedia
                </span>
            </div>`
          );
          if (info) {
            $container.append(`<small class="d-block mt-1 text-muted" style="font-size:11px;">${info}</small>`);
          }
        }

        return $container;
      }

      // Inisialisasi Select2
      $('.select2-custom').select2({
        theme: 'bootstrap-5',
        width: '100%',
        templateResult: formatOption, // Mengatur tampilan LIST dropdown
        templateSelection: formatOption, // Mengatur tampilan KOTAK INPUT setelah dipilih
        escapeMarkup: function(m) {
          return m; // Izinkan render HTML
        }
      });

    });
  </script>
@endsection
