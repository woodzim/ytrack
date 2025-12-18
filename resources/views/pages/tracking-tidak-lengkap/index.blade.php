@extends('layouts.master')

@section('styles')
    <style>
        .page-header-section {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
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

        .table-wrapper {
            border-radius: 8px;
            overflow: visible;
        }

        .table-action-btns .btn-sm {
            padding: 5px 8px;
            font-size: 12px;
        }

        .pagination {
            margin: 0;
            gap: 4px;
        }

        .pagination .page-link {
            border: 1px solid #e0e0e0;
            color: #d97706;
            border-radius: 4px;
            padding: 8px 12px;
            font-size: 13px;
        }

        .pagination .page-link:hover {
            background-color: #f5f5f5;
            color: #b45309;
        }

        .pagination .page-item.active .page-link {
            background: #d97706;
            border-color: #d97706;
            color: white;
        }

        .pagination .page-item.disabled .page-link {
            color: #ccc;
            cursor: not-allowed;
        }

        .status-badge {
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 4px;
        }

        .incomplete-badge {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }

        /* Horizontal Scrollable Table */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            width: 100%;
        }

        .table-responsive .table {
            min-width: 100%;
            white-space: nowrap;
        }

        .table-responsive .table thead th,
        .table-responsive .table tbody td {
            white-space: nowrap;
            padding: 12px 24px;
        }

        .table-responsive::-webkit-scrollbar {
            height: 8px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Ensure card-body doesn't hide overflow */
        .card-body {
            overflow: visible;
        }

        /* Mobile Responsive */
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

            .d-flex.gap-2.flex-wrap.align-items-end {
                flex-direction: column;
                gap: 8px !important;
            }

            .d-flex.gap-2.flex-wrap.align-items-end>* {
                width: 100%;
            }

            .d-flex.gap-2.flex-wrap.align-items-end button,
            .d-flex.gap-2.flex-wrap.align-items-end a {
                width: 100%;
                font-size: 13px;
            }

            .card {
                border-radius: 8px;
                margin-bottom: 12px;
            }

            .card-header {
                padding: 12px !important;
            }

            .card-body {
                padding: 12px !important;
            }

            .card-title {
                font-size: 14px !important;
                margin-bottom: 8px !important;
            }

            .table {
                font-size: 12px;
            }

            .table thead {
                display: none;
            }

            .table tbody tr {
                display: block;
                margin-bottom: 12px;
                border: 1px solid #e0e0e0;
                border-radius: 6px;
                overflow: hidden;
            }

            .table tbody td {
                display: block;
                padding: 8px 12px;
                border: none;
                text-align: right;
                position: relative;
                padding-left: 50%;
            }

            .table tbody td:before {
                content: attr(data-label);
                position: absolute;
                left: 12px;
                font-weight: 600;
                color: #d97706;
                text-align: left;
                width: 40%;
            }

            .table tbody td:first-child {
                font-weight: 600;
                background: #f8f9fa;
            }

            .table-action-btns {
                display: flex;
                gap: 6px;
                justify-content: flex-end;
                padding-top: 8px;
                padding-right: 0;
                border-top: 1px solid #f0f0f0;
            }

            .table-action-btns .btn-sm {
                padding: 6px 8px;
                font-size: 11px;
                flex: 1;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Start::page-header -->
    <div class="page-header-section d-flex align-items-center justify-content-between">
        <div>
            <h1>Tracking Tidak Lengkap</h1>
            <p>Data riwayat perjalanan dengan data yang tidak lengkap.</p>
        </div>
    </div>
    <!-- End::page-header -->

    <!-- Start::app-content -->
    <div class="row">
        <div class="col-12">
            <div class="card table-wrapper">
                <div class="card-header border-bottom-0 bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Daftar Tracking Tidak Lengkap ({{ $incompleteTrackings->total() }})</h5>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if ($incompleteTrackings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 8%;">ID</th>
                                        <th style="width: 10%;">Sesi Alat</th>
                                        <th style="width: 10%;">Alat</th>
                                        <th style="width: 12%;">Kendaraan</th>
                                        <th style="width: 12%;">Perusahaan</th>
                                        <th style="width: 10%;">Latitude</th>
                                        <th style="width: 10%;">Longitude</th>
                                        <th style="width: 8%;">Ketinggian</th>
                                        <th style="width: 8%;">Satelit</th>
                                        <th style="width: 8%;">Kecepatan</th>
                                        <th style="width: 12%;">Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($incompleteTrackings as $item)
                                        <tr>
                                            <td data-label="ID">
                                                <span class="badge bg-light text-dark">{{ $item->id }}</span>
                                            </td>
                                            <td data-label="Sesi Alat">
                                                @if ($item->id_sesi_alat)
                                                    <span class="badge bg-light text-dark">{{ $item->id_sesi_alat }}</span>
                                                @else
                                                    <span class="incomplete-badge">
                                                        <i class="ri-close-circle-line me-1"></i>Kosong
                                                    </span>
                                                @endif
                                            </td>
                                            <td data-label="Alat">
                                                @if ($item->id_alat)
                                                    <span class="badge bg-light text-dark">{{ $item->id_alat }}</span>
                                                @else
                                                    <span class="incomplete-badge">
                                                        <i class="ri-close-circle-line me-1"></i>Kosong
                                                    </span>
                                                @endif
                                            </td>
                                            <td data-label="Kendaraan">
                                                @if ($item->kendaraan)
                                                    <small class="text-muted">{{ $item->kendaraan->nama }}</small>
                                                @else
                                                    <span class="incomplete-badge">
                                                        <i class="ri-close-circle-line me-1"></i>Kosong
                                                    </span>
                                                @endif
                                            </td>
                                            <td data-label="Perusahaan">
                                                @if ($item->perusahaan)
                                                    <small class="text-muted">{{ $item->perusahaan->nama }}</small>
                                                @else
                                                    <span class="incomplete-badge">
                                                        <i class="ri-close-circle-line me-1"></i>Kosong
                                                    </span>
                                                @endif
                                            </td>
                                            <td data-label="Latitude">
                                                <small class="text-muted">{{ $item->lat ?? '-' }}</small>
                                            </td>
                                            <td data-label="Longitude">
                                                <small class="text-muted">{{ $item->long ?? '-' }}</small>
                                            </td>
                                            <td data-label="Ketinggian">
                                                <small class="text-muted">{{ $item->alt ?? '-' }}</small>
                                            </td>
                                            <td data-label="Satelit">
                                                <small class="text-muted">{{ $item->sat ?? '-' }}</small>
                                            </td>
                                            <td data-label="Kecepatan">
                                                <small class="text-muted">{{ $item->speed ?? '-' }} km/h</small>
                                            </td>
                                            <td data-label="Waktu">
                                                <small class="text-muted">{{ $item->created_at->format('Y-m-d H:i:s') }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-4 text-center">
                            <div class="mb-3">
                                <i class="ri-check-double-line" style="font-size: 48px; color: #10b981;"></i>
                            </div>
                            <h5 class="text-muted mb-2">Semua data lengkap</h5>
                            <p class="text-muted mb-3">Tidak ada data riwayat perjalanan dengan data yang tidak lengkap</p>
                        </div>
                    @endif
                </div>

                @if ($incompleteTrackings->hasPages())
                    <div class="card-footer bg-white border-top">
                        <nav aria-label="Page navigation">
                            {{ $incompleteTrackings->links() }}
                        </nav>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@endsection

@section('scripts')
@endsection
