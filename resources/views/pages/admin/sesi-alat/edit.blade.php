@extends('layouts.master')

@section('styles')
    @vite(['resources/assets/scss/select2.scss', 'resources/assets/scss/select2-bootstrap-theme.scss'])
@endsection

@section('content')
    <!-- Start::page-header -->
    <div class="page-header-section d-flex align-items-center justify-content-between">
        <div>
            <h1>Edit Sesi Alat</h1>
            <p>Ubah konfigurasi sesi alat GPS</p>
        </div>
    </div>
    <!-- End::page-header -->

    <!-- Start::app-content -->
    <div class="row pb-3">
        <div class="col-12">
            <div class="form-card">
                <div class="card-header">
                    <h5>Edit Sesi</h5>
                </div>
                <div class="card-body">
                    <!-- Session Metadata -->
                    <div class="meta-info">
                        <p><strong>ID Sesi:</strong> {{ $sesiAlat->id }}</p>
                        <p><strong>Dibuat:</strong> {{ $sesiAlat->created_at->translatedFormat('d F Y H:i') }}</p>
                        <p><strong>Diubah:</strong> {{ $sesiAlat->updated_at->translatedFormat('d F Y H:i') }}</p>
                    </div>

                    <form action="{{ route('sesi-alat.update', $sesiAlat->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Perusahaan Select -->
                        <div class="mb-3">
                            <label for="id_perusahaan" class="form-label">
                                Perusahaan <span class="text-required">*</span>
                            </label>
                            <select name="id_perusahaan" id="id_perusahaan" class="form-select @error('id_perusahaan') is-invalid @enderror" required>
                                <option value="">-- Pilih Perusahaan --</option>
                                @foreach($perusahaan as $item)
                                    <option value="{{ $item->id }}" {{ old('id_perusahaan', $sesiAlat->id_perusahaan) == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_perusahaan')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                            <small class="text-muted d-block mt-1">
                                <i class="ri-information-line"></i> Perusahaan saat ini: <strong>{{ $sesiAlat->perusahaan ? $sesiAlat->perusahaan->nama : 'Dari Kendaraan' }}</strong>
                            </small>
                        </div>

                        <!-- Kendaraan Select (akan diisi berdasarkan perusahaan) -->
                        <div class="mb-3">
                            <label for="id_kendaraan" class="form-label">
                                Kendaraan <span class="text-required">*</span>
                            </label>
                            <select name="id_kendaraan" id="id_kendaraan" class="form-select @error('id_kendaraan') is-invalid @enderror" required>
                                <option value="">-- Pilih Kendaraan --</option>
                                @foreach($kendaraan as $item)
                                    <option value="{{ $item->id }}" {{ old('id_kendaraan', $sesiAlat->id_kendaraan) == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_kendaraan')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                            <small class="text-muted d-block mt-1">
                                <i class="ri-information-line"></i> Kendaraan saat ini: <strong>{{ $sesiAlat->kendaraan ? $sesiAlat->kendaraan->nama : '-' }}</strong>
                            </small>
                        </div>

                        <!-- Alat Select -->
                        <div class="mb-4">
                            <label for="id_alat" class="form-label">
                                Alat <span class="text-required">*</span>
                            </label>
                            <select name="id_alat" id="id_alat" class="form-select @error('id_alat') is-invalid @enderror" required>
                                <option value="">-- Pilih Alat --</option>
                                @foreach($alat as $item)
                                    <option value="{{ $item->id }}" {{ old('id_alat', $sesiAlat->id_alat) == $item->id ? 'selected' : '' }}>
                                        {{ $item->id }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_alat')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                            <small class="text-muted d-block mt-1">
                                <i class="ri-information-line"></i> Alat saat ini: <strong>{{ $sesiAlat->alat ? $sesiAlat->alat->id : '-' }}</strong>
                            </small>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-2"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('sesi-alat.show', $sesiAlat->id) }}" class="btn btn-outline-secondary">
                                <i class="ri-close-line me-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@endsection

@section('scripts')
    @vite(['resources/assets/js/jquery.js', 'resources/assets/js/select2.js'])
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const perusahaanSelect = document.getElementById('id_perusahaan');
            const kendaraanSelect = document.getElementById('id_kendaraan');
            const alatSelect = document.getElementById('id_alat');

            // Initialize Select2 on all select elements
            $(perusahaanSelect).select2({
                theme: 'bootstrap-5',
                width: '100%',
                allowClear: true,
                placeholder: '-- Pilih Perusahaan --'
            });

            $(kendaraanSelect).select2({
                theme: 'bootstrap-5',
                width: '100%',
                allowClear: true,
                placeholder: '-- Pilih Kendaraan --'
            });

            $(alatSelect).select2({
                theme: 'bootstrap-5',
                width: '100%',
                allowClear: true,
                placeholder: '-- Pilih Alat --'
            });

            // Load kendaraan when perusahaan is selected using Select2 change event
            $(perusahaanSelect).on('change', function() {
                const idPerusahaan = this.value;
                console.log('Perusahaan selected:', idPerusahaan);

                if (!idPerusahaan) {
                    kendaraanSelect.innerHTML = '<option value="">-- Pilih Perusahaan Terlebih Dahulu --</option>';
                    $(kendaraanSelect).val(null).trigger('change');
                    return;
                }

                // Fetch kendaraan dari API
                fetch(`{{ url('/sesi-alat-kendaraan') }}/${idPerusahaan}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Kendaraan data received:', data);
                        kendaraanSelect.innerHTML = '<option value="">-- Pilih Kendaraan --</option>';
                        
                        if (data.length > 0) {
                            data.forEach(kendaraan => {
                                const option = document.createElement('option');
                                option.value = kendaraan.id;
                                option.textContent = kendaraan.nama;
                                
                                // Pre-select if there was an old value or current value
                                if ('{{ old('id_kendaraan', $sesiAlat->id_kendaraan) }}' == kendaraan.id) {
                                    option.selected = true;
                                }
                                
                                kendaraanSelect.appendChild(option);
                            });
                        } else {
                            kendaraanSelect.innerHTML = '<option value="">Tidak ada kendaraan untuk perusahaan ini</option>';
                        }
                        
                        $(kendaraanSelect).val('{{ old('id_kendaraan', $sesiAlat->id_kendaraan) }}').trigger('change');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        kendaraanSelect.innerHTML = '<option value="">Error loading kendaraan</option>';
                    });
            });

            // Trigger change event on page load if perusahaan was already selected
            if (perusahaanSelect.value) {
                $(perusahaanSelect).trigger('change');
            }
        });
    </script>
@endsection
