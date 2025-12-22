@extends('layouts.master')

@section('content')
    <!-- Start::page-header -->
    <div class="detail-header">
        <h1>{{ $alat->id }}</h1>
        <p>Detail Perangkat GPS</p>
    </div>
    <!-- End::page-header -->

    <!-- Start::app-content -->
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card card-detail">
                <div class="card-header border-bottom bg-white">
                    <h5 class="card-title mb-0"><i class="ri-information-line me-2"></i>Informasi Alat</h5>
                </div>
                <div class="card-body">
                    <div class="detail-row">
                        <span class="detail-label">ID Alat</span>
                        <span class="detail-value">
                            <strong>{{ $alat->id }}</strong>
                        </span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Dibuat Pada</span>
                        <span class="detail-value">
                            {{ $alat->created_at->translatedFormat('d M Y') }}
                            <div class="detail-value-meta">Pukul {{ $alat->created_at->translatedFormat('H:i') }}</div>
                        </span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Diperbarui Pada</span>
                        <span class="detail-value">
                            {{ $alat->updated_at->translatedFormat('d M Y') }}
                            <div class="detail-value-meta">Pukul {{ $alat->updated_at->translatedFormat('H:i') }}</div>
                        </span>
                    </div>
                </div>
                <div style="padding: 16px; background: #f9f9f9; border-top: 1px solid #f0f0f0; border-radius: 0 0 8px 8px;">
                    <div class="btn-action-group">
                        {{-- <a href="{{ route('alat.edit', $alat->id) }}" class="btn btn-primary btn-wave">
                            <i class="ri-edit-line me-2"></i>Edit
                        </a> --}}
                        <a href="{{ route('sesi-alat.index', ['search' => $alat->id]) }}" class="btn btn-primary btn-wave">
                            <i class="ri-eye-line me-2"></i>Lihat Sesi
                        </a>
                        <a href="{{ route('alat.index') }}" class="btn btn-outline-secondary btn-wave">
                            <i class="ri-arrow-left-line me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card card-detail">
                <div class="card-header border-bottom bg-white">
                    <h5 class="card-title mb-0"><i class="ri-bar-chart-line me-2"></i>Status</h5>
                </div>
                <div class="card-body">
                    <div style="text-align: center;">
                        <div style="margin-bottom: 12px;">
                            @if ($alat->sesiAlat()->where('aktif', true)->exists())
                                <span class="badge" style="background-color: #10b981; color: white;">Aktif</span>
                            @else
                                <span class="badge" style="background-color: #6b7280; color: white;">Tidak Aktif</span>
                            @endif
                        </div>
                        @if ($alat->sesiAlat()->where('aktif', true)->exists())
                            <p style="font-size: 13px; color: #666; margin: 0;">Perangkat sedang digunakan</p>
                        @else
                            <p style="font-size: 13px; color: #666; margin: 0;">Perangkat sedang tidak digunakan</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@endsection

@section('scripts')
@endsection
