@extends('layouts.master')

@section('content')
    <!-- Start::page-header -->
    <div class="page-title-section">
        <h1 class="fw-bold mb-2">Tambah Perusahaan Baru</h1>
        <p class="text-muted">Isikan informasi lengkap untuk menambahkan perusahaan baru</p>
    </div>
    <!-- End::page-header -->

    <!-- Start::app-content -->
    <div class="row">
        <div class="col-12">
            <div class="card form-card">
                <div class="card-body">
                    <form action="{{ route('perusahaan.store') }}" method="POST">
                        @csrf

                        <div class="form-group-wrapper">
                            <label for="nama" class="form-label fw-medium">Nama Perusahaan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" placeholder="Contoh: PT. Maju Jaya" value="{{ old('nama') }}" required>
                            @error('nama')
                                <div class="invalid-feedback d-block mt-2">
                                    <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group-wrapper">
                            <label for="alamat" class="form-label fw-medium">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="5" placeholder="Contoh: Jl. Merdeka No. 123, Jakarta 12345" required>{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback d-block mt-2">
                                    <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex flex-column flex-sm-row gap-2 pt-3 border-top">
                            <button type="submit" class="btn btn-primary btn-wave flex-sm-grow-1">
                                <i class="ri-save-line me-2"></i>Simpan Perusahaan
                            </button>
                            <a href="{{ route('perusahaan.index') }}" class="btn btn-outline-secondary btn-wave flex-sm-grow-1">
                                <i class="ri-arrow-left-line me-2"></i>Kembali
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

@endsection
