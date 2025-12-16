@extends('layouts.master')

@section('styles')
    <style>
        .form-header {
            background: #667eea;
            border-radius: 8px;
            padding: 24px;
            color: white;
            margin-bottom: 24px;
        }
        .form-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .form-header p {
            margin: 8px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        .form-card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .form-group-wrapper {
            margin-bottom: 20px;
        }
        .form-group-wrapper label {
            font-weight: 600;
            color: #667eea;
            margin-bottom: 8px;
            display: block;
        }
        .form-group-wrapper .form-control,
        .form-group-wrapper .form-select {
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 10px 12px;
            font-size: 14px;
        }
        .form-group-wrapper .form-control:focus,
        .form-group-wrapper .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .form-info-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 16px;
            border-radius: 6px;
            margin-top: 20px;
        }
        .form-info-box h6 {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .form-info-box p {
            margin: 0;
            font-size: 13px;
            color: #666;
            line-height: 1.6;
        }
        .form-actions {
            display: flex;
            gap: 8px;
            margin-top: 24px;
        }
        .form-actions .btn {
            flex: 1;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .form-header {
                padding: 16px;
                margin-bottom: 16px;
            }
            .form-header h1 {
                font-size: 18px;
            }
            .form-header p {
                font-size: 12px;
            }
            .form-actions {
                flex-direction: column;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Start::page-header -->
    <div class="form-header">
        <h1>Tambah Alat Baru</h1>
        <p>Daftarkan perangkat GPS baru ke sistem</p>
    </div>
    <!-- End::page-header -->

    <!-- Start::app-content -->
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card form-card">
                <div class="card-header border-bottom bg-white">
                    <h5 class="card-title mb-0"><i class="ri-information-line me-2"></i>Informasi Alat</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('alat.store') }}" method="POST">
                        @csrf

                        <div class="form-group-wrapper">
                            <label for="id">ID Alat</label>
                            <input type="text" id="id" name="id" class="form-control @error('id') is-invalid @enderror" 
                                   placeholder="Contoh: GPS-001, GPS-002" value="{{ old('id') }}" required>
                            @error('id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-wave">
                                <i class="ri-save-line me-2"></i>Simpan Alat
                            </button>
                            <a href="{{ route('alat.index') }}" class="btn btn-outline-secondary btn-wave">
                                <i class="ri-close-line me-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="form-info-box">
                <h6><i class="ri-lightbulb-line me-2"></i>Informasi Penting</h6>
                <p>
                    ID Alat adalah pengenal unik untuk setiap perangkat GPS. Gunakan format yang mudah dikenali dan jangan duplikasi dengan alat yang sudah terdaftar.
                </p>
            </div>

            <div class="form-info-box mt-3">
                <h6><i class="ri-shield-check-line me-2"></i>Format ID</h6>
                <p>
                    Contoh format ID alat:
                    <ul style="margin: 8px 0 0 0; padding-left: 16px;">
                        <li>0001</li>
                        <li>GPS-001</li>
                        <li>TRACKER-A01</li>
                        <li>DEV-2025-001</li>
                    </ul>
                </p>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@endsection

@section('scripts')

@endsection
