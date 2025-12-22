@extends('layouts.master')

@section('content')
    <!-- Start::page-header -->
    <div class="page-title-section">
        <h1 class="fw-bold mb-2">Edit Perusahaan</h1>
        <p class="text-muted">Perbarui informasi perusahaan sesuai kebutuhan</p>
    </div>
    <!-- End::page-header -->

    <!-- Start::app-content -->
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card form-card">
                <div class="card-body">
                    <form action="{{ route('perusahaan.update', $perusahaan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group-wrapper">
                            <label for="nama" class="form-label fw-medium">Nama Perusahaan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" placeholder="Masukkan nama perusahaan" value="{{ old('nama', $perusahaan->nama) }}" required>
                            @error('nama')
                                <div class="invalid-feedback d-block mt-2">
                                    <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group-wrapper">
                            <label for="alamat" class="form-label fw-medium">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="5" placeholder="Masukkan alamat perusahaan" required>{{ old('alamat', $perusahaan->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback d-block mt-2">
                                    <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex flex-column flex-sm-row gap-2 pt-3 border-top">
                            <button type="submit" class="btn btn-primary btn-wave flex-sm-grow-1">
                                <i class="ri-save-line me-2"></i>Perbarui Data
                            </button>
                            <a href="{{ route('perusahaan.index') }}" class="btn btn-outline-secondary btn-wave flex-sm-grow-1">
                                <i class="ri-arrow-left-line me-2"></i>Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title mb-3"><i class="ri-history-line me-2"></i>Riwayat</h6>
                    <div class="small">
                        <p class="text-muted mb-2">
                            <strong>ID:</strong>
                            {{ $perusahaan->id }}
                        </p>
                        <p class="text-muted mb-2">
                            <strong>Dibuat:</strong><br>
                            {{ $perusahaan->created_at->translatedFormat('d M Y H:i') }}
                        </p>
                        <p class="text-muted mb-0">
                            <strong>Diperbarui:</strong><br>
                            {{ $perusahaan->updated_at->translatedFormat('d M Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@endsection

@section('scripts')

@endsection
