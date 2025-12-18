@extends('layouts.master')

@section('styles')
    <style>
        .page-header-section {
            background: rgb(152, 95, 253);
            border-radius: 8px;
            padding: 24px;
            color: white;
            margin-bottom: 24px;
        }
        .page-header-section h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
            color: white;
        }
        .page-header-section p {
            margin: 8px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        .form-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }
        .form-card {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }
        .form-card .card-header {
            background: #f0f3ff;
            border-bottom: 1px solid #e0e0e0;
            padding: 16px 20px;
        }
        .form-card .card-header h5 {
            margin: 0;
            color: #333;
            font-weight: 600;
        }
        .form-card .card-body {
            padding: 24px;
        }
        .info-card {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            background: #f8f9ff;
        }
        .info-card .card-header {
            background: #f0f3ff;
            border-bottom: 1px solid #e0e0e0;
            padding: 16px 20px;
        }
        .info-card .card-header h5 {
            margin: 0;
            color: #333;
            font-weight: 600;
        }
        .info-card .card-body {
            padding: 20px;
        }
        .info-item {
            display: flex;
            gap: 12px;
            margin-bottom: 12px;
        }
        .info-item:last-child {
            margin-bottom: 0;
        }
        .info-item i {
            color: rgb(152, 95, 253);
            min-width: 20px;
        }
        .info-item p {
            margin: 0;
            font-size: 13px;
            color: #666;
            line-height: 1.4;
        }
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 13px;
        }
        .form-control, .form-select {
            border-radius: 6px;
            border: 1px solid #d0d0d0;
            padding: 10px 12px;
            font-size: 13px;
        }
        .form-control:focus, .form-select:focus {
            border-color: rgb(152, 95, 253);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .btn-primary {
            background: rgb(152, 95, 253);
            border: none;
            padding: 10px 24px;
            font-weight: 600;
            border-radius: 6px;
        }
        .btn-primary:hover {
            opacity: 0.9;
            color: white;
        }
        .btn-outline-secondary {
            padding: 10px 24px;
            border-radius: 6px;
            font-weight: 600;
        }
        .error-alert {
            border-radius: 6px;
            padding: 12px 16px;
            margin-bottom: 16px;
        }
        .text-required {
            color: #dc3545;
        }

        /* Mobile Responsive */
        @media (max-width: 991px) {
            .form-container {
                grid-template-columns: 1fr;
            }
        }
        @media (max-width: 767px) {
            .page-header-section {
                padding: 12px;
                margin-bottom: 16px;
            }
            .page-header-section h1 {
                font-size: 18px;
                color: white;
            }
            .page-header-section p {
                font-size: 12px;
            }
            
            .form-card .card-body {
                padding: 16px;
            }
            .info-card .card-body {
                padding: 16px;
            }
            
            .form-label {
                font-size: 12px;
            }
            .form-control, .form-select {
                font-size: 12px;
                padding: 8px 10px;
            }
            
            .d-flex.gap-2 {
                gap: 8px !important;
                flex-direction: column;
            }
            .d-flex.gap-2 > * {
                width: 100%;
            }
        }
    </style>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const perusahaanSelect = document.getElementById('id_perusahaan');
            const kendaraanSelect = document.getElementById('id_kendaraan');

            // Load kendaraan when perusahaan is selected
            perusahaanSelect.addEventListener('change', function() {
                const idPerusahaan = this.value;

                if (!idPerusahaan) {
                    kendaraanSelect.innerHTML = '<option value="">-- Pilih Perusahaan Terlebih Dahulu --</option>';
                    kendaraanSelect.disabled = true;
                    return;
                }

                // Fetch kendaraan dari API
                fetch(`{{ url('/sesi-alat-kendaraan') }}/${idPerusahaan}`)
                    .then(response => response.json())
                    .then(data => {
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
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        kendaraanSelect.innerHTML = '<option value="">Error loading kendaraan</option>';
                    });
            });

            // Trigger change event on page load if perusahaan was already selected
            if (perusahaanSelect.value) {
                perusahaanSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection
