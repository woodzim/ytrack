@extends('layouts.master')

@section('styles')
  @vite(['resources/assets/scss/select2.scss', 'resources/assets/scss/select2-bootstrap-theme.scss'])
@endsection

@section('content')
  <div class="page-header-section d-flex align-items-center justify-content-between">
    <div>
      <h1>Buat Sesi Alat</h1>
      <p>Tetapkan alat GPS untuk kendaraan tertentu</p>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="form-container">
        <div class="form-card card custom-card">
          <div class="card-header">
            <div class="card-title">Informasi Sesi</div>
          </div>
          <div class="card-body">
            <form action="{{ route('sesi-alat.store') }}" method="POST">
              @csrf

              <div class="mb-3">
                <label for="id_perusahaan" class="form-label">
                  Perusahaan <span class="text-danger">*</span>
                </label>
                <select name="id_perusahaan" id="id_perusahaan"
                  class="form-select @error('id_perusahaan') is-invalid @enderror" required>
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

              <div class="mb-3">
                <label for="id_kendaraan" class="form-label">
                  Kendaraan <span class="text-danger">*</span>
                </label>
                <select name="id_kendaraan" id="id_kendaraan"
                  class="form-select @error('id_kendaraan') is-invalid @enderror" required disabled>
                  <option value="">-- Pilih Perusahaan Terlebih Dahulu --</option>
                </select>
                @error('id_kendaraan')
                  <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror
              </div>

              <div class="mb-3">
                <label for="id_alat" class="form-label">
                  Alat GPS <span class="text-danger">*</span>
                </label>
                {{-- Hapus @foreach loop PHP disini, kita pakai JS --}}
                <select name="id_alat" id="id_alat" class="form-select @error('id_alat') is-invalid @enderror" required
                  disabled>
                  <option value="">-- Pilih Perusahaan Terlebih Dahulu --</option>
                </select>

                @error('id_alat')
                  <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror

                {{-- Informasi Tambahan --}}
                <div class="form-text text-muted">
                  <i class="ri-information-line me-1"></i>Hanya alat yang dimiliki perusahaan yang akan muncul.
                </div>
              </div>

              <div class="mb-4">
                <label for="nama" class="form-label">
                  Nama Sesi <span class="text-muted fw-normal">(Opsional)</span>
                </label>
                <input type="text" name="nama" id="nama"
                  class="form-control @error('nama') is-invalid @enderror" placeholder="Contoh: Sesi Tracking Koransi"
                  value="{{ old('nama') }}">
                @error('nama')
                  <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror
                <small class="text-muted d-block mt-1">Berikan nama unik untuk membedakan sesi jika diperlukan</small>
              </div>

              <div class="d-flex gap-2 justify-content-end">
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
        <div class="">
          <div class="card custom-card bg-primary-transparent">
            <div class="card-body">
              <h6 class="fw-bold text-primary mb-3">Informasi Penting</h6>

              <ul class="list-unstyled mb-0 d-grid gap-3">
                <li class="d-flex align-items-start">
                  <i class="ri-filter-3-line text-primary me-3"></i>
                  <div>
                    <strong>Filter Aset Otomatis</strong>
                    <p class="small text-muted mb-0">Hanya kendaraan dan alat milik perusahaan terpilih yang muncul.</p>
                  </div>
                </li>
                <li class="d-flex align-items-start">
                  <i class="ri-link-unlink-m text-primary me-3"></i>
                  <div>
                    <strong>Satu Alat per Sesi</strong>
                    <p class="small text-muted mb-0">Setiap alat GPS hanya dapat aktif di satu kendaraan sekaligus.</p>
                  </div>
                </li>
                <li class="d-flex align-items-start">
                  <i class="ri-car-line text-primary me-3"></i>
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
    </div>
  </div>
@endsection

@section('scripts')
  @vite(['resources/assets/js/jquery.js', 'resources/assets/js/select2.js'])

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Definisikan Element
      const perusahaanSelect = document.getElementById('id_perusahaan');
      const kendaraanSelect = document.getElementById('id_kendaraan');
      const alatSelect = document.getElementById('id_alat');

      // 1. Initialize Select2
      const configSelect2 = {
        theme: 'bootstrap-5',
        width: '100%',
        allowClear: true
      };

      $(perusahaanSelect).select2({
        ...configSelect2,
        placeholder: '-- Pilih Perusahaan --'
      });
      $(kendaraanSelect).select2({
        ...configSelect2,
        placeholder: '-- Pilih Kendaraan --'
      });
      $(alatSelect).select2({
        ...configSelect2,
        placeholder: '-- Pilih Alat --'
      });

      // 2. Logic AJAX: Saat Perusahaan Berubah
      $(perusahaanSelect).on('change', function() {
        const idPerusahaan = this.value;
        console.log('Perusahaan selected:', idPerusahaan);

        // --- RESET KONDISI AWAL ---
        // Kosongkan dan matikan dropdown kendaraan
        kendaraanSelect.innerHTML = '<option value="">-- Pilih Kendaraan --</option>';
        kendaraanSelect.disabled = true;
        $(kendaraanSelect).val(null).trigger('change');

        // Kosongkan dan matikan dropdown alat
        alatSelect.innerHTML = '<option value="">-- Pilih Alat --</option>';
        alatSelect.disabled = true;
        $(alatSelect).val(null).trigger('change');

        // Jika user memilih "kosong/pilih perusahaan", berhenti disini
        if (!idPerusahaan) {
          kendaraanSelect.innerHTML = '<option value="">-- Pilih Perusahaan Terlebih Dahulu --</option>';
          alatSelect.innerHTML = '<option value="">-- Pilih Perusahaan Terlebih Dahulu --</option>';
          return;
        }

        // =========================================
        // FETCH 1: KENDARAAN
        // =========================================
        fetch(`{{ url('/sesi-alat-kendaraan') }}/${idPerusahaan}`)
          .then(response => response.json())
          .then(data => {
            console.log('Kendaraan data:', data);
            kendaraanSelect.innerHTML = '<option value="">-- Pilih Kendaraan --</option>';

            if (data.length > 0) {
              data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                // Menampilkan Nama + Plat (jika ada)
                let text = item.nama;
                if (item.plat) text += ` (${item.plat})`;
                else if (item.plat) text += ` (${item.plat})`;
                option.textContent = text;

                // Re-select old input (saat validasi gagal)
                if ('{{ old('id_kendaraan') }}' == item.id) {
                  option.selected = true;
                }
                kendaraanSelect.appendChild(option);
              });
              kendaraanSelect.disabled = false;
            } else {
              kendaraanSelect.innerHTML = '<option value="">Tidak ada kendaraan tersedia</option>';
            }
            // Refresh UI Select2
            $(kendaraanSelect).trigger('change');
          })
          .catch(error => {
            console.error('Error Kendaraan:', error);
            kendaraanSelect.innerHTML = '<option value="">Gagal memuat data</option>';
          });

        // =========================================
        // FETCH 2: ALAT (Sesuai Permintaan)
        // =========================================
        fetch(`{{ url('/sesi-alat-alat') }}/${idPerusahaan}`)
          .then(response => response.json())
          .then(data => {
            console.log('Alat data:', data);
            alatSelect.innerHTML = '<option value="">-- Pilih Alat --</option>';

            if (data.length > 0) {
              data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.kode_alat; // Sesuaikan jika kolomnya bukan 'nama'

                // Re-select old input
                if ('{{ old('id_alat') }}' == item.id) {
                  option.selected = true;
                }
                alatSelect.appendChild(option);
              });
              alatSelect.disabled = false;
            } else {
              alatSelect.innerHTML = '<option value="">Tidak ada alat tersedia</option>';
            }
            // Refresh UI Select2
            $(alatSelect).trigger('change');
          })
          .catch(error => {
            console.error('Error Alat:', error);
            alatSelect.innerHTML = '<option value="">Gagal memuat data</option>';
          });
      });

      // 3. Trigger saat halaman dimuat (jika ada error validasi sebelumnya)
      if (perusahaanSelect.value) {
        $(perusahaanSelect).trigger('change');
      }
    });
  </script>
@endsection
