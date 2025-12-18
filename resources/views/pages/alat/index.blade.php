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
        
        /* Tablet (768px - 991px) */
        @media (max-width: 991px) {
            .page-header-section {
                padding: 16px;
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            .page-header-section h1 {
                font-size: 22px;
            }
            .page-header-section > div:last-child {
                width: 100%;
            }
            .page-header-section .btn {
                width: 100%;
            }
            .table-action-btns {
                display: flex;
                gap: 4px;
                justify-content: center;
                flex-wrap: wrap;
            }
            .table-action-btns .btn-sm {
                padding: 6px 10px;
                font-size: 11px;
            }
        }
        
        /* Mobile (576px - 767px) */
        @media (max-width: 767px) {
            .page-header-section {
                padding: 12px;
                margin-bottom: 16px;
            }
            .page-header-section h1 {
                font-size: 18px;
            }
            .page-header-section p {
                font-size: 12px;
            }
            
            /* Search form */
            .d-flex.gap-2.flex-wrap.align-items-end {
                flex-direction: column;
                gap: 8px !important;
            }
            .d-flex.gap-2.flex-wrap.align-items-end > * {
                width: 100%;
            }
            .d-flex.gap-2.flex-wrap.align-items-end > div {
                min-width: 100% !important;
            }
            .d-flex.gap-2.flex-wrap.align-items-end button,
            .d-flex.gap-2.flex-wrap.align-items-end a {
                width: 100%;
                font-size: 13px;
            }
            
            /* Card styling */
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
            
            /* Table responsive */
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
            .table tbody td:first-child:before {
                content: "ID";
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
            
            /* Pagination responsive */
            .pagination {
                flex-wrap: wrap;
                gap: 4px;
            }
            .pagination .page-link {
                padding: 6px 10px;
                font-size: 12px;
            }
            
            /* Header badge */
            .d-flex.justify-content-between.align-items-center {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 8px;
            }
            .badge {
                font-size: 11px !important;
            }
            
            /* Empty state */
            .p-4.text-center {
                padding: 16px !important;
            }
            .p-4.text-center i {
                font-size: 36px !important;
            }
        }
        
        /* Extra small (< 576px) */
        @media (max-width: 575px) {
            .page-header-section {
                padding: 12px;
                margin-bottom: 12px;
            }
            .page-header-section h1 {
                font-size: 16px;
            }
            .page-header-section p {
                font-size: 11px;
            }
            
            /* Search form */
            .form-label {
                font-size: 12px !important;
                margin-bottom: 6px !important;
            }
            .form-control {
                font-size: 13px;
                padding: 8px 10px;
            }
            
            .card {
                margin-bottom: 10px;
            }
            .card-header {
                padding: 10px !important;
            }
            .card-body {
                padding: 10px !important;
            }
            
            /* Table card view */
            .table tbody td {
                padding: 6px 10px;
                padding-left: 45%;
                font-size: 11px;
            }
            .table tbody td:before {
                left: 10px;
                font-size: 11px;
                width: 35%;
            }
            
            .table-action-btns {
                gap: 4px;
                padding-top: 6px;
                padding: 6px 10px;
            }
            .table-action-btns .btn-sm {
                padding: 5px 6px;
                font-size: 10px;
            }
            .table-action-btns i {
                margin-right: 0 !important;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Start::page-header -->
    <div class="page-header-section d-flex align-items-center justify-content-between">
        <div>
            <h1>Daftar Alat</h1>
            <p>Kelola semua perangkat GPS di sistem</p>
        </div>
        <div class="ms-auto">
            <a href="{{ route('alat.create') }}" class="btn btn-light btn-wave">
                <i class="ri-add-line me-2"></i>Tambah Alat
            </a>
        </div>
    </div>
    <!-- End::page-header -->

    <!-- Start::app-content -->
    <div class="row">
        <div class="col-12">
            <!-- Search Section -->
            <div class="card mb-3">
                <div class="card-body">
                    <form action="{{ route('alat.index') }}" method="GET" class="d-flex gap-2 flex-wrap align-items-end">
                        <div class="flex-grow-1" style="min-width: 250px;">
                            <label for="searchInput" class="form-label mb-2">Cari Alat</label>
                            <input type="text" id="searchInput" name="search" class="form-control" 
                                   placeholder="Cari berdasarkan ID alat..." 
                                   value="{{ $search }}">
                        </div>
                        <button type="submit" class="btn btn-primary btn-wave">
                            <i class="ri-search-line me-2"></i>Cari
                        </button>
                        @if($search)
                            <a href="{{ route('alat.index') }}" class="btn btn-outline-secondary btn-wave">
                                <i class="ri-close-line me-2"></i>Reset
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <div class="card table-wrapper">
                <div class="card-header border-bottom-0 bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Daftar Alat ({{ $alat->total() }})</h5>
                        @if($search)
                            <span class="badge bg-info">Hasil pencarian: "{{ $search }}"</span>
                        @endif
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($alat->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 30%;">ID Alat</th>
                                        <th style="width: 50%;">Dibuat</th>
                                        <th style="width: 50%;">Aktif</th>
                                        <th style="width: 20%;" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($alat as $item)
                                        <tr>
                                            <td data-label="ID Alat">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge bg-light text-dark">#{{ $item->id }}</span>
                                                    <a href="{{ route('sesi-alat.index', ['search' => $item->id]) }}" class="btn btn-sm btn-outline-primary btn-wave" title="Cari Sesi">
                                                        <i class="ri-search-line"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <td data-label="Dibuat">
                                                <small class="text-muted">{{ $item->created_at->translatedFormat('d M Y H:i') }}</small>
                                            </td>
                                            <td data-label="Aktif">
                                                @if($item->sesiAlat()->where('aktif', true)->exists())
                                                    <span class="badge" style="background-color: #10b981; color: white;">Aktif</span>
                                                @else
                                                    <span class="badge" style="background-color: #6b7280; color: white;">Tidak Aktif</span>
                                                @endif
                                            </td>
                                            <td data-label="Aksi" class="table-action-btns text-center">
                                                <a href="{{ route('alat.show', $item->id) }}" class="btn btn-sm btn-info btn-wave" title="Lihat">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a href="{{ route('alat.edit', $item->id) }}" class="btn btn-sm btn-warning btn-wave" title="Edit">
                                                    <i class="ri-edit-line"></i>
                                                </a>
                                                <form action="{{ route('alat.destroy', $item->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Anda yakin ingin menghapus alat ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger btn-wave" title="Hapus">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-4 text-center">
                            <div class="mb-3">
                                @if($search)
                                    <i class="ri-search-line" style="font-size: 48px; color: #ddd;"></i>
                                @else
                                    <i class="ri-gps-line" style="font-size: 48px; color: #ddd;"></i>
                                @endif
                            </div>
                            @if($search)
                                <h5 class="text-muted mb-2">Tidak ada hasil pencarian</h5>
                                <p class="text-muted mb-3">Tidak ditemukan alat dengan ID "{{ $search }}"</p>
                                <a href="{{ route('alat.index') }}" class="btn btn-outline-primary btn-wave">
                                    <i class="ri-arrow-left-line me-2"></i>Kembali ke Daftar
                                </a>
                            @else
                                <h5 class="text-muted mb-2">Tidak ada data alat</h5>
                                <p class="text-muted mb-3">Mulai dengan menambahkan perangkat GPS pertama Anda</p>
                                <a href="{{ route('alat.create') }}" class="btn btn-primary btn-wave">
                                    <i class="ri-add-line me-2"></i>Tambah Alat
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                @if($alat->hasPages())
                    <div class="card-footer bg-white border-top">
                        <nav aria-label="Page navigation">
                            {{ $alat->links() }}
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
