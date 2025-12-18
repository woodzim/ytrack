@extends('layouts.master')

@section('styles')
    <style>
        .page-header-section {
            background: rgb(152, 95, 253);
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
            color: rgb(152, 95, 253);
            border-radius: 4px;
            padding: 8px 12px;
            font-size: 13px;
        }
        .pagination .page-link:hover {
            background-color: #f5f5f5;
            color: #764ba2;
        }
        .pagination .page-item.active .page-link {
            background: rgb(152, 95, 253);
            border-color: rgb(152, 95, 253);
            color: white;
        }
        .pagination .page-item.disabled .page-link {
            color: #ccc;
            cursor: not-allowed;
        }
        .status-badge {
            font-size: 11px;
            padding: 4px 8px;
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
            .d-flex.gap-2.flex-wrap.align-items-end > * {
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
                color: rgb(152, 95, 253);
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
            <h1>Sesi Alat</h1>
            <p>Kelola sesi penggunaan alat GPS di kendaraan</p>
        </div>
        <div class="ms-auto">
            <a href="{{ route('sesi-alat.create') }}" class="btn btn-light btn-wave">
                <i class="ri-add-line me-2"></i>Buat Sesi
            </a>
        </div>
    </div>
    <!-- End::page-header -->

    <!-- Start::app-content -->
    <div class="row">
        <div class="col-12">
            <!-- Filter & Search Section -->
            <div class="card mb-3">
                <div class="card-body">
                    <form action="{{ route('sesi-alat.index') }}" method="GET" class="d-flex gap-2 flex-wrap align-items-end">
                        <div class="flex-grow-1" style="min-width: 200px;">
                            <label for="searchInput" class="form-label mb-2">Cari Sesi</label>
                            <input type="text" id="searchInput" name="search" class="form-control" 
                                   placeholder="Cari alat atau kendaraan..." 
                                   value="{{ $search }}">
                        </div>
                        <div>
                            <label for="statusFilter" class="form-label mb-2">Status</label>
                            <select id="statusFilter" name="status" class="form-select">
                                <option value="">Semua</option>
                                <option value="1" {{ $filter_status === '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ $filter_status === '0' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-wave">
                            <i class="ri-search-line me-2"></i>Cari
                        </button>
                        @if($search || $filter_status !== '')
                            <a href="{{ route('sesi-alat.index') }}" class="btn btn-outline-secondary btn-wave">
                                <i class="ri-close-line me-2"></i>Reset
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <div class="card table-wrapper">
                <div class="card-header border-bottom-0 bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Daftar Sesi Alat ({{ $sesiAlat->total() }})</h5>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($sesiAlat->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 15%;">Perusahaan</th>
                                        <th style="width: 20%;">Kendaraan</th>
                                        <th style="width: 15%;">Alat</th>
                                        <th style="width: 15%;">Status</th>
                                        <th style="width: 15%;">Jumlah Track</th>
                                        <th style="width: 20%;">Waktu Track Terakhir</th>
                                        <th style="width: 15%;" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sesiAlat as $item)
                                        <tr>
                                            <td data-label="Perusahaan">
                                                <small class="text-muted">{{ $item->perusahaan ? $item->perusahaan->nama : '-' }}</small>
                                            </td>
                                            <td data-label="Kendaraan">
                                                <small class="text-muted">{{ $item->kendaraan ? $item->kendaraan->nama : '-' }}</small>
                                            </td>
                                            <td data-label="Alat">
                                                <span class="badge bg-light text-dark">{{ $item->alat ? $item->alat->id : '-' }}</span>
                                            </td>
                                            <td data-label="Status">
                                                <span class="status-badge" style="background-color: {{ $item->aktif ? '#10b981' : '#6b7280' }}; color: white;">
                                                    {{ $item->aktif ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </td>
                                            <td data-label="Jumlah Track">
                                                <small class="text-muted">{{ $item->riwayatPerjalanan->count() }}</small>
                                            </td>
                                            <td data-label="Tanggal Track">
                                                @if ($item->riwayatPerjalanan->isEmpty())
                                                    <small class="text-muted">Belum ada track</small>
                                                @else
                                                    <small class="text-muted">{{ $item->riwayatPerjalanan()->max('created_at') }}</small>
                                                @endif
                                            </td>
                                            <td data-label="Aksi" class="table-action-btns text-end text-md-start">
                                                <div class="mb-2">
                                                    <a href="{{ route('sesi-alat.show', $item->id) }}" class="btn btn-sm btn-info btn-wave" title="Lihat">
                                                        <i class="ri-eye-line"></i>
                                                        Lihat Tracking
                                                    </a>
                                                </div>
                                                <div class="mb-2">
                                                    <form action="{{ route('sesi-alat.toggle', $item->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm {{ $item->aktif ? 'btn-warning' : 'btn-success' }} btn-wave" title="{{ $item->aktif ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                            <i class="ri-{{ $item->aktif ? 'pause' : 'play' }}-line"></i>
                                                            {{ $item->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                                        </button>
                                                    </form>
                                                </div>
                                                <div>
                                                    <form action="{{ route('sesi-alat.destroy', $item->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus sesi ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger btn-wave" title="Hapus">
                                                            <i class="ri-delete-bin-line"></i>
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-4 text-center">
                            <div class="mb-3">
                                <i class="ri-bluetooth-connect-line" style="font-size: 48px; color: #ddd;"></i>
                            </div>
                            <h5 class="text-muted mb-2">Tidak ada sesi alat</h5>
                            <p class="text-muted mb-3">Mulai dengan membuat sesi alat baru</p>
                            <a href="{{ route('sesi-alat.create') }}" class="btn btn-primary btn-wave">
                                <i class="ri-add-line me-2"></i>Buat Sesi
                            </a>
                        </div>
                    @endif
                </div>

                @if($sesiAlat->hasPages())
                    <div class="card-footer bg-white border-top">
                        <nav aria-label="Page navigation">
                            {{ $sesiAlat->links() }}
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
