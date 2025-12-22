@extends('layouts.master')

@section('content')
    <!-- Start::page-header -->
    <div class="d-flex align-items-center justify-content-between mb-4 page-header-breadcrumb flex-wrap gap-2">
        <div>
            <h1 class="page-title fw-medium fs-20 mb-0">Selamat datang, {{ auth()->user()->name }}.</h1>
            <p class="text-muted mb-0">Dashboard Perusahaan</p>
        </div>
    </div>
    <!-- End::page-header -->

    <!-- Start::app-content -->
    <div class="row g-3">
        <!-- Statistics Cards -->
        <div class="col-md-6 col-lg-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="card-title text-muted mb-2">Sesi Alat</h6>
                            <h3 class="mb-0">{{ $totalSesiAlat }}</h3>
                        </div>
                        <div class="stats-icon bg-info-transparent">
                            <i class="ri-bluetooth-connect-line fs-24 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
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

        <div class="col-md-6 col-lg-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="card-title text-muted mb-2">Kendaraan dalam Perjalanan</h6>
                            <h3 class="mb-0">{{ $kendaraanDalamPerjalanan }}</h3>
                        </div>
                        <div class="stats-icon bg-success-transparent">
                            <i class="ri-map-pin-line fs-24 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-2">
        <!-- Profile Edit -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-bottom">
                    <div class="card-title mb-0">Edit Profil Perusahaan</div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="ri-check-line me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('perusahaan.dashboard.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Perusahaan</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                   id="nama" name="nama" 
                                   value="{{ old('nama', $perusahaan->nama) }}" 
                                   required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat Perusahaan</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                      id="alamat" name="alamat" 
                                      rows="4" required>{{ old('alamat', $perusahaan->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-wave">
                                <i class="ri-save-line me-2"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('perusahaan.dashboard.index') }}" class="btn btn-outline-secondary btn-wave">
                                <i class="ri-close-line me-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-bottom">
                    <div class="card-title mb-0">Aktivitas Terbaru</div>
                </div>
                <div class="card-body p-0">
                    @if($recentRiwayat->count() > 0)
                        <div class="table-responsive mb-0">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Waktu</th>
                                        <th>Kendaraan</th>
                                        <th>Alat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentRiwayat as $item)
                                        <tr>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $item->created_at?->format('d-m-Y H:i') ?? '-' }}
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    {{ $item->kendaraan?->nama ?? '-' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $item->alat?->id ?? '-' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info m-3">
                            <i class="ri-information-line me-2"></i>
                            Belum ada aktivitas perjalanan
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@endsection

@section('scripts')
@endsection
