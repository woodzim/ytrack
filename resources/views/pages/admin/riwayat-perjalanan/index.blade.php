@extends('layouts.master')

@section('content')
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
