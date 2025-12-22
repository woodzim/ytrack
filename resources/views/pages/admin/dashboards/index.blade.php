@extends('layouts.master')

@section('content')
    <!-- Start::page-header -->
    <div class="d-flex align-items-center justify-content-between mb-4 page-header-breadcrumb flex-wrap gap-2">
        <div>
            <h1 class="page-title fw-medium fs-20 mb-0">Selamat datang, {{  auth()->user()->name }}.</h1>
            <p class="text-muted mb-0">Dashboard GPS Tracking System</p>
        </div>
    </div>
    <!-- End::page-header -->

    <!-- Start::app-content -->
    <div class="row g-3">
        <!-- Statistics Cards -->
        <div class="col-md-6 col-lg-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="card-title text-muted mb-2">Sesi Aktif</h6>
                            <h3 class="mb-0">{{ $totalSesiAlatAktif }}</h3>
                        </div>
                        <div class="stats-icon bg-success-transparent">
                            <i class="ri-check-double-line fs-24 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="card-title text-muted mb-2">Total Alat GPS</h6>
                            <h3 class="mb-0">{{ $totalAlat }}</h3>
                        </div>
                        <div class="stats-icon bg-info-transparent">
                            <i class="ri-radar-line fs-24 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="card-title text-muted mb-2">Total Kendaraan</h6>
                            <h3 class="mb-0">{{ $totalKendaraan }}</h3>
                        </div>
                        <div class="stats-icon bg-warning-transparent">
                            <i class="ri-taxi-line fs-24 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="card-title text-muted mb-2">Total Perusahaan</h6>
                            <h3 class="mb-0">{{ $totalPerusahaan }}</h3>
                        </div>
                        <div class="stats-icon bg-primary-transparent">
                            <i class="ri-building-4-line fs-24 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-2">
        <!-- Active Sessions -->
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <div class="card-title mb-0">Sesi Aktif</div>
                    <p class="text-muted mb-0 small">Perangkat yang sedang aktif melacak</p>
                </div>
                <div class="card-body p-0">
                    @if($activeSessions->count() > 0)
                        <div class="table-responsive mb-0">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Alat ID</th>
                                        <th>Kendaraan</th>
                                        <th>Perusahaan</th>
                                        <th class="pe-4">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activeSessions->take(5) as $session)
                                        <tr>
                                            <td class="ps-4">
                                                <span class="badge bg-primary">{{ $session->alat?->id ?? '-' }}</span>
                                            </td>
                                            <td>{{ $session->kendaraan?->nama ?? '-' }}</td>
                                            <td>{{ $session->perusahaan?->nama ?? '-' }}</td>
                                            <td class="pe-4">
                                                <span class="badge bg-success">
                                                    <i class="ri-checkbox-circle-line"></i> Aktif
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0 m-3">
                            <i class="ri-information-line me-2"></i>
                            Tidak ada sesi alat yang aktif saat ini
                        </div>
                    @endif
                </div>
                <div class="card-footer border-top">
                    <a href="{{ route('sesi-alat.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua <i class="ri-arrow-right-line ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@endsection

@section('scripts')
@endsection
