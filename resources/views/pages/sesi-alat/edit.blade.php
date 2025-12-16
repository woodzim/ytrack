@extends('layouts.master')

@section('styles')
    <style>
        .page-header-section {
            background: #667eea;
            border-radius: 8px;
            padding: 24px;
            color: white;
            margin-bottom: 24px;
        }
        .page-header-section h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
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
            color: #667eea;
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
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .form-control:disabled, .form-select:disabled {
            background-color: #f0f0f0;
            color: #999;
        }
        .btn-primary {
            background: #667eea;
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
        .meta-info {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 6px;
            font-size: 12px;
            color: #666;
            margin-bottom: 16px;
        }
        .meta-info p {
            margin: 0;
            line-height: 1.6;
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
            <h1>Edit Sesi Alat</h1>
            <p>Ubah konfigurasi sesi alat GPS</p>
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
                        <h5>Edit Sesi</h5>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger error-alert">
                                <strong>Terjadi kesalahan:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-warning error-alert">
                                {{ session('error') }}
                            </div>
                        @endif

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
                                    <i class="ri-information-line"></i> Kendaraan saat ini: <strong>{{ $sesiAlat->kendaraan->nama }}</strong>
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
                                    <i class="ri-information-line"></i> Alat saat ini: <strong>{{ $sesiAlat->alat->id }}</strong>
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

                <!-- Info Sidebar -->
                <div class="info-card">
                    <div class="card-header">
                        <h5>Catatan Penting</h5>
                    </div>
                    <div class="card-body">
                        <div class="info-item">
                            <i class="ri-alert-line"></i>
                            <p><strong>Perubahan Alat:</strong> Mengalihkan alat ke yang lain akan memindahkan perangkat tracking ke kendaraan ini.</p>
                        </div>
                        <div class="info-item">
                            <i class="ri-alert-line"></i>
                            <p><strong>Perubahan Kendaraan:</strong> Memindahkan sesi ke kendaraan lain akan mengalihkan alat ke kendaraan tersebut.</p>
                        </div>
                        <div class="info-item">
                            <i class="ri-shield-check-line"></i>
                            <p><strong>Validasi Otomatis:</strong> Sistem akan memastikan tidak ada konflik penggunaan alat atau kendaraan setelah perubahan.</p>
                        </div>
                        <hr class="my-3">
                        <div class="info-item">
                            <i class="ri-time-line"></i>
                            <p><strong>Status Sesi:</strong> Ubah status (Aktif/Nonaktif) dari halaman detail atau daftar sesi.</p>
                        </div>
                        <div class="info-item">
                            <i class="ri-lightbulb-flash-line"></i>
                            <p><strong>Tips:</strong> Jika perubahan mengakibatkan konflik, sistem akan menampilkan pesan error dengan penjelasan.</p>
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
                                
                                // Pre-select if there was an old value or current value
                                if ('{{ old('id_kendaraan', $sesiAlat->id_kendaraan) }}' == kendaraan.id) {
                                    option.selected = true;
                                }
                                
                                kendaraanSelect.appendChild(option);
                            });
                        } else {
                            kendaraanSelect.innerHTML = '<option value="">Tidak ada kendaraan untuk perusahaan ini</option>';
                        }
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
