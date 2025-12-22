@extends('layouts.master')

@section('content')
    <!-- Start::page-header -->
    <div class="form-header">
        <h1>Edit Alat</h1>
        <p>Perbarui informasi perangkat GPS</p>
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
                    <form action="{{ route('alat.update', $alat->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group-wrapper">
                            <label for="id">ID Alat</label>
                            <input type="text" id="id" name="id" class="form-control @error('id') is-invalid @enderror" 
                                   placeholder="Contoh: GPS-001, GPS-002" value="{{ old('id', $alat->id) }}" required>
                            @error('id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-wave">
                                <i class="ri-save-line me-2"></i>Simpan Perubahan
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
            <div class="card form-card">
                <div class="card-header border-bottom bg-white">
                    <h5 class="card-title mb-0"><i class="ri-time-line me-2"></i>Riwayat</h5>
                </div>
                <div class="card-body p-4">
                    <div class="info-row">
                        <div class="info-label">Dibuat pada</div>
                        <div class="info-value">{{ $alat->created_at->translatedFormat('d F Y H:i') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Diperbarui pada</div>
                        <div class="info-value">{{ $alat->updated_at->translatedFormat('d F Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@endsection

@section('scripts')

@endsection
