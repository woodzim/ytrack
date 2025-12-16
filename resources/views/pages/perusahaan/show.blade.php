@extends('layouts.master')

@section('styles')
    <style>
        .detail-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            padding: 24px;
            color: white;
            margin-bottom: 24px;
        }
        .detail-header h1 {
            margin: 0 0 8px 0;
            font-size: 28px;
            font-weight: 600;
        }
        .detail-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 14px;
        }
        .detail-row {
            display: flex;
            padding: 16px 0;
            border-bottom: 1px solid #f0f0f0;
            align-items: flex-start;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #667eea;
            width: 150px;
            flex-shrink: 0;
        }
        .detail-value {
            color: #333;
            flex: 1;
        }
        .detail-value-meta {
            color: #999;
            font-size: 13px;
        }
        .card-detail {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            margin-bottom: 16px;
        }
        .btn-action-group {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        /* Tablet & Desktop */
        @media (min-width: 992px) {
            .detail-header {
                padding: 24px;
            }
            .detail-header h1 {
                font-size: 28px;
            }
            .detail-row {
                padding: 16px 0;
            }
            .detail-label {
                width: 150px;
            }
        }

        /* Mobile Responsiveness */
        @media (max-width: 991px) {
            .detail-header {
                padding: 16px;
                margin-bottom: 16px;
            }
            .detail-header h1 {
                font-size: 22px;
                margin-bottom: 4px;
            }
            .detail-header p {
                font-size: 12px;
            }
            .detail-header .text-end {
                margin-top: 12px;
            }
            .detail-row {
                padding: 12px 0;
                flex-direction: column;
            }
            .detail-label {
                width: 100%;
                margin-bottom: 6px;
                color: #667eea;
                font-size: 14px;
            }
            .detail-value {
                font-size: 14px;
            }
            .card-detail {
                margin-bottom: 12px;
            }
            .card-body {
                padding: 16px !important;
            }
            .btn-action-group {
                gap: 6px;
            }
            .btn-action-group .btn {
                flex: 1;
                font-size: 13px;
                padding: 8px 12px;
                white-space: nowrap;
            }
            .btn-action-group .btn i {
                margin-right: 4px;
            }
            h6.card-title {
                font-size: 14px;
            }
            .row > [class*='col-'] {
                margin-bottom: 12px;
            }
        }

        /* Extra Small Devices */
        @media (max-width: 576px) {
            .detail-header {
                padding: 12px;
                margin-bottom: 12px;
            }
            .detail-header h1 {
                font-size: 18px;
                margin-bottom: 2px;
            }
            .detail-header p {
                font-size: 11px;
            }
            .detail-header .badge {
                font-size: 10px;
                padding: 4px 8px !important;
            }
            .card-header {
                padding: 12px !important;
            }
            .card-body {
                padding: 12px !important;
            }
            .detail-row {
                padding: 10px 0;
            }
            .detail-label {
                font-size: 12px;
                margin-bottom: 4px;
            }
            .detail-value {
                font-size: 13px;
            }
            .btn-action-group {
                flex-direction: column;
                gap: 6px;
            }
            .btn-action-group .btn {
                width: 100%;
                padding: 10px 12px;
                font-size: 12px;
            }
            h5.card-title {
                font-size: 14px;
                margin-bottom: 8px !important;
            }
            h6.card-title {
                font-size: 12px;
                margin-bottom: 8px !important;
            }
            .row {
                margin-left: -4px;
                margin-right: -4px;
            }
            .row > [class*='col-'] {
                padding-left: 4px;
                padding-right: 4px;
                margin-bottom: 8px;
            }
            .text-center h3 {
                font-size: 20px !important;
            }
            .text-center p {
                font-size: 11px !important;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Start::page-header -->
    <div class="detail-header">
        <div>
            <h1 class="mb-0">{{ $perusahaan->nama }}</h1>
        </div>
    </div>
    <!-- End::page-header -->

    <!-- Start::app-content -->
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card card-detail">
            <div class="card-header border-bottom bg-white">
                <h5 class="card-title mb-0"><i class="ri-information-line me-2"></i>Informasi Lengkap</h5>
            </div>
            <div class="card-body">
                <div class="detail-row">
                <span class="detail-label">ID Perusahaan</span>
                <span class="detail-value">
                    <strong>#{{ $perusahaan->id }}</strong>
                </span>
                </div>

                <div class="detail-row">
                <span class="detail-label">Nama Perusahaan</span>
                <span class="detail-value">
                    <strong>{{ $perusahaan->nama }}</strong>
                </span>
                </div>

                <div class="detail-row">
                <span class="detail-label">Alamat</span>
                <span class="detail-value">
                    {{ $perusahaan->alamat }}
                </span>
                </div>

                <div class="detail-row">
                <span class="detail-label">Dibuat Pada</span>
                <span class="detail-value">
                    {{ $perusahaan->created_at->translatedFormat('d M Y') }}
                    <div class="detail-value-meta">Pukul {{ $perusahaan->created_at->translatedFormat('H:i') }}</div>
                </span>
                </div>

                <div class="detail-row">
                <span class="detail-label">Diperbarui Pada</span>
                <span class="detail-value">
                    {{ $perusahaan->updated_at->translatedFormat('d M Y') }}
                    <div class="detail-value-meta">Pukul {{ $perusahaan->updated_at->translatedFormat('H:i') }}</div>
                </span>
                </div>
            </div>
            <div style="padding: 16px; background: #f9f9f9; border-top: 1px solid #f0f0f0; border-radius: 0 0 8px 8px;">
                <div class="btn-action-group">
                <a href="{{ route('perusahaan.edit', $perusahaan->id) }}" class="btn btn-warning btn-wave">
                    <i class="ri-edit-line me-2"></i>Edit
                </a>
                <a href="{{ route('perusahaan.index') }}" class="btn btn-outline-secondary btn-wave">
                    <i class="ri-arrow-left-line me-2"></i>Kembali
                </a>
                </div>
            </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card card-detail">
                <div class="card-body">
                    {{-- <h6 class="card-title mb-3"><i class="ri-bar-chart-line me-2"></i>Statistik</h6> --}}
                    <div class="row">
                        <div class="col-12">
                            <div class="text-center">
                                <h3 class="mb-1" style="color: #667eea;">0</h3>
                                <p class="text-muted small mb-0">Kendaraan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@endsection

@section('scripts')

@endsection
