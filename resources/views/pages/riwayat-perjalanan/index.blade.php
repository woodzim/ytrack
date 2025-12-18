@extends('layouts.master')

@section('styles')
    <style>
        .page-header-section {
            background: linear-gradient(135deg, rgb(152, 95, 253) 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 32px 24px;
            color: white;
            margin-bottom: 32px;
        }
        .page-header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 24px;
        }
        .page-header-section h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: white;
        }
        .page-header-section p {
            margin: 8px 0 0 0;
            opacity: 0.95;
            font-size: 14px;
            font-weight: 500;
        }
        .badge-count {
            background: rgba(255, 255, 255, 0.25);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid rgba(255, 255, 255, 0.3);
            white-space: nowrap;
        }

        /* Search Card */
        .search-card {
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            margin-bottom: 24px;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        .search-card .card-body {
            padding: 20px;
        }
        .search-form {
            display: grid;
            grid-template-columns: 1fr auto auto auto;
            gap: 12px;
            align-items: flex-end;
        }
        .search-form .form-group {
            margin: 0;
        }
        .search-form .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }
        .search-form .form-control {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 14px;
            transition: all 0.2s;
        }
        .search-form .form-control:focus {
            border-color: rgb(152, 95, 253);
            box-shadow: 0 0 0 3px rgba(152, 95, 253, 0.1);
        }

        /* Table Styling */
        .table-wrapper {
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            background: white;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        .table-wrapper .card-header {
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            padding: 16px 20px;
        }
        .table {
            margin-bottom: 0;
        }
        .table thead th {
            background: #f9fafb;
            border-bottom: 2px solid #e5e7eb !important;
            padding: 12px !important;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: #6b7280;
            letter-spacing: 0.5px;
        }
        .table tbody tr {
            border-bottom: 1px solid #f3f4f6;
            transition: background-color 0.2s;
        }
        .table tbody tr:hover {
            background-color: #f9fafb;
        }
        .table tbody td {
            padding: 16px 12px !important;
            vertical-align: middle;
            font-size: 14px;
            color: #374151;
        }
        .alat-id-cell {
            font-weight: 600;
            color: rgb(152, 95, 253);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-view {
            background: rgb(152, 95, 253);
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            text-decoration: none;
        }
        .btn-view:hover {
            background: #764ba2;
            color: white;
            text-decoration: none;
        }

        /* Empty State */
        .empty-state-card {
            border-radius: 12px;
            border: 2px dashed #d1d5db;
            background: #f9fafb;
            padding: 60px 20px;
            text-align: center;
        }
        .empty-state-icon {
            font-size: 56px;
            margin-bottom: 16px;
            opacity: 0.4;
        }
        .empty-state-title {
            font-size: 18px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }
        .empty-state-desc {
            font-size: 14px;
            color: #6b7280;
            margin: 0;
        }

        /* Mobile Responsive */
        @media (max-width: 1024px) {
            .alat-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            }
        }
        @media (max-width: 768px) {
            .page-header-section {
                padding: 24px;
                margin-bottom: 24px;
            }
            .page-header-content {
                flex-direction: column;
                align-items: stretch;
            }
            .page-header-section h1 {
                font-size: 24px;
                color: white;
            }
            .badge-count {
                width: fit-content;
            }
            .search-form {
                grid-template-columns: 1fr;
            }
            .search-form button, .search-form a {
                width: 100%;
            }
            .alat-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .alat-stats {
                gap: 12px;
            }
            .alat-card-body {
                padding: 16px;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Start::page-header -->
    <div class="page-header-section">
        <div class="page-header-content">
            <div>
                <h1><i class="ri-map-pin-line me-2" style="font-size: 28px;"></i>Riwayat Perjalanan</h1>
                <p>Tracking data per alat (tanpa sesi aktif)</p>
            </div>
            <div>
                <span class="badge-count">
                    <i class="ri-database-line me-1"></i>{{ $totalRecords }} Data
                </span>
            </div>
        </div>
    </div>
    <!-- End::page-header -->

    <!-- Start::app-content -->
    <div class="row pb-3">
        <div class="col-12">
            <!-- Search Section -->
            <div class="search-card">
                <div class="card-body">
                    <form action="{{ route('riwayat-perjalanan.index') }}" method="GET" class="search-form">
                        <div class="form-group">
                            <label for="searchInput" class="form-label">Cari ID Alat</label>
                            <input type="text" id="searchInput" name="search" class="form-control" 
                                   placeholder="Contoh: GPS-001, ALAT-GPS-001..." 
                                   value="{{ $search }}">
                        </div>
                        <button type="submit" class="btn btn-primary btn-wave" style="border-radius: 8px;">
                            <i class="ri-search-line me-1"></i>Cari
                        </button>
                        @if($search)
                            <a href="{{ route('riwayat-perjalanan.index') }}" class="btn btn-outline-secondary btn-wave" style="border-radius: 8px;">
                                <i class="ri-close-line me-1"></i>Reset
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Alat Groups Grid -->
            @if($alatSummary->count() > 0)
                <div class="table-wrapper">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Daftar Alat</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 25%;">ID Alat</th>
                                    <th style="width: 15%;">Jumlah Tracking</th>
                                    <th style="width: 25%;">Terbaru</th>
                                    <th style="width: 25%;">Terakhir</th>
                                    <th style="width: 10%; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alatSummary as $summary)
                                    <tr>
                                        <td>
                                            <div class="alat-id-cell">
                                                <i class="ri-router-wifi-line"></i>
                                                {{ Str::limit($summary['id_alat'], 20) }}
                                            </div>
                                        </td>
                                        <td data-label="Data Tracking">
                                            {{ $summary['count'] }}
                                        </td>
                                        <td data-label="Terbaru">
                                            <small>{{ $summary['first_time'] }}</small>
                                        </td>
                                        <td data-label="Tertua">
                                            <small>{{ Str::limit($summary['last_time'], 25) }}</small>
                                        </td>
                                        <td data-label="Aksi" style="text-align: center;">
                                            <a href="{{ route('riwayat-perjalanan.show', $summary['id_alat']) }}" class="btn-view">
                                                <i class="ri-arrow-right-line"></i>Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="empty-state-card">
                    <div class="empty-state-icon">📍</div>
                    <h3 class="empty-state-title">Tidak ada data riwayat perjalanan</h3>
                    <p class="empty-state-desc">Semua tracking data sudah memiliki sesi alat atau tidak memiliki ID alat</p>
                </div>
            @endif
        </div>
    </div>
    <!-- End::app-content -->
@endsection

@section('scripts')

@endsection
