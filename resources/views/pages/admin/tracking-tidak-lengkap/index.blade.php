@extends('layouts.master')

@section('content')
    <!-- Start::page-header -->
    <div class="page-header-section d-flex align-items-center justify-content-between">
        <div>
            <h1>Error Tracking</h1>
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
                        <h5 class="card-title mb-0">Daftar Error Tracking ({{ $incompleteTrackings->total() }})</h5>
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
