@extends('layouts.master')

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
