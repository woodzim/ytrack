@extends('layouts.master')

@section('styles')
    @vite(['resources/assets/scss/select2.scss', 'resources/assets/scss/select2-bootstrap-theme.scss'])
@endsection

@section('content')
    <!-- Start::page-header -->
    <div class="page-header-section d-flex align-items-center justify-content-between">
        <div>
            <h1>Buat Sesi Alat</h1>
            <p>Tetapkan alat GPS untuk kendaraan tertentu</p>
        </div>
    </div>
    <!-- End::page-header -->

    <!-- Start::app-content -->
    <div class="row">
        <div class="col-12">
            <div class="form-container">
                <!-- Main Form -->
                <div class="form-card">
                    <div class="card-header">
                        <h5>Informasi Sesi</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('sesi-alat.store') }}" method="POST">
                            @csrf

                            <!-- Perusahaan Select -->
                            <div class="mb-3">
                                <label for="id_perusahaan" class="form-label">
                                    Perusahaan <span class="text-required">*</span>
                                </label>
                                <select name="id_perusahaan" id="id_perusahaan" class="form-select @error('id_perusahaan') is-invalid @enderror" required>
                                    <option value="">-- Pilih Perusahaan --</option>
                                    @foreach($perusahaan as $item)
                                        <option value="{{ $item->id }}" {{ old('id_perusahaan') == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_perusahaan')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Kendaraan Select (akan diisi berdasarkan perusahaan) -->
                            <div class="mb-3">
                                <label for="id_kendaraan" class="form-label">
                                    Kendaraan <span class="text-required">*</span>
                                </label>
                                <select name="id_kendaraan" id="id_kendaraan" class="form-select @error('id_kendaraan') is-invalid @enderror" required disabled>
                                    <option value="">-- Pilih Perusahaan Terlebih Dahulu --</option>
                                </select>
                                @error('id_kendaraan')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Alat Select -->
                            <div class="mb-4">
                                <label for="id_alat" class="form-label">
                                    Alat <span class="text-required">*</span>
                                </label>
                                <select name="id_alat" id="id_alat" class="form-select @error('id_alat') is-invalid @enderror" required>
                                    <option value="">-- Pilih Alat --</option>
                                    @foreach($alat as $item)
                                        <option value="{{ $item->id }}" {{ old('id_alat') == $item->id ? 'selected' : '' }}>
                                            {{ $item->id }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_alat')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-check-line me-2"></i>Buat Sesi
                                </button>
                                <a href="{{ route('sesi-alat.index') }}" class="btn btn-outline-secondary">
                                    <i class="ri-close-line me-2"></i>Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Info Sidebar -->
                <div class="info-card">
                    <div class="card-header">
                        <h5>Informasi Penting</h5>
                    </div>
                    <div class="card-body">
                        <div class="info-item">
                            <i class="ri-alert-line"></i>
                            <p><strong>Satu Alat per Sesi:</strong> Setiap alat GPS hanya dapat aktif di satu kendaraan sekaligus.</p>
                        </div>
                        <div class="info-item">
                            <i class="ri-alert-line"></i>
                            <p><strong>Satu Kendaraan per Alat:</strong> Setiap kendaraan hanya dapat memiliki satu alat aktif sekaligus.</p>
                        </div>
                        <div class="info-item">
                            <i class="ri-time-line"></i>
                            <p><strong>Buat Sesi Baru:</strong> Nonaktifkan sesi sebelumnya jika ingin mengalihkan alat ke kendaraan lain.</p>
                        </div>
                        <hr class="my-3">
                        <div class="info-item">
                            <i class="ri-lightbulb-flash-line"></i>
                            <p><strong>Tips:</strong> Anda dapat mengubah status sesi kapan saja dari halaman daftar atau detail.</p>
                        </div>
                    </div>
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
            const perusahaanSelect2 = $(perusahaanSelect).select2({
                theme: 'bootstrap-5',
                width: '100%',
                allowClear: true,
                placeholder: '-- Pilih Perusahaan --'
            });

            const kendaraanSelect2 = $(kendaraanSelect).select2({
                theme: 'bootstrap-5',
                width: '100%',
                allowClear: true,
                placeholder: '-- Pilih Kendaraan --'
            });

            const alatSelect2 = $(alatSelect).select2({
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
                    kendaraanSelect.disabled = true;
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
                                
                                // Pre-select if there was an old value
                                if ('{{ old('id_kendaraan') }}' == kendaraan.id) {
                                    option.selected = true;
                                }
                                
                                kendaraanSelect.appendChild(option);
                            });
                        } else {
                            kendaraanSelect.innerHTML = '<option value="">Tidak ada kendaraan untuk perusahaan ini</option>';
                        }
                        
                        kendaraanSelect.disabled = false;
                        $(kendaraanSelect).val('{{ old('id_kendaraan') }}').trigger('change');
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
