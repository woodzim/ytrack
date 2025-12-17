@extends('layouts.master')

@section('styles')
    <style>
        .detail-header {
            background: rgb(152, 95, 253);
            border-radius: 8px;
            padding: 24px;
            color: white;
            margin-bottom: 24px;
        }
        .detail-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .detail-header p {
            margin: 8px 0 0 0;
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
            color: rgb(152, 95, 253);
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
            margin-top: 4px;
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
            .detail-row {
                padding: 12px 0;
                flex-direction: column;
            }
            .detail-label {
                width: 100%;
                margin-bottom: 6px;
                color: rgb(152, 95, 253);
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
        }
    </style>
@endsection

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
                        <a href="{{ route('alat.edit', $alat->id) }}" class="btn btn-warning btn-wave">
                            <i class="ri-edit-line me-2"></i>Edit
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
                            <span class="badge bg-success" style="font-size: 12px; padding: 6px 12px;">Aktif</span>
                        </div>
                        <p style="font-size: 13px; color: #666; margin: 0;">Perangkat siap digunakan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@endsection

@section('scripts')

@endsection
