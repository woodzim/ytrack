@extends('layouts.master')

@section('content')
    <!-- Start::page-header -->
    <div class="page-header-section d-flex align-items-center justify-content-between">
        <div>
            <h1>Sesi Alat</h1>
            <p>Kelola sesi penggunaan alat GPS di kendaraan Anda</p>
        </div>
    </div>
    <!-- End::page-header -->

    <!-- Start::app-content -->
    <div class="row">
        <div class="col-12">
            <!-- Filter & Search Section -->
            <div class="card mb-3">
                <div class="card-body">
                    <form action="{{ route('perusahaan.sesi-alat.index') }}" method="GET"
                        class="d-flex gap-2 flex-wrap align-items-end">
                        <div class="flex-grow-1" style="min-width: 200px;">
                            <label for="searchInput" class="form-label mb-2">Cari Sesi</label>
                            <input type="text" id="searchInput" name="search" class="form-control"
                                placeholder="Cari alat atau kendaraan..." value="{{ $search }}">
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
                        @if ($search || $filter_status !== '')
                            <a href="{{ route('perusahaan.sesi-alat.index') }}" class="btn btn-outline-secondary btn-wave">
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
                    @if ($sesiAlat->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 20%;">Kendaraan</th>
                                        <th style="width: 20%;">Alat</th>
                                        <th style="width: 20%;">Status</th>
                                        <th style="width: 20%;">Jumlah Track</th>
                                        <th style="width: 20%;">Waktu Track Terakhir</th>
                                        <th style="width: 15%;" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sesiAlat as $item)
                                        <tr>
                                            <td data-label="Kendaraan">
                                                <strong>{{ $item->kendaraan->nama ?? '-' }}</strong>
                                            </td>
                                            <td data-label="Alat">
                                                <span class="badge bg-light text-dark">{{ $item->alat->id ?? '-' }}</span>
                                            </td>
                                            <td data-label="Status">
                                                @if ($item->aktif)
                                                    <span class="badge bg-success">
                                                        <i class="ri-check-double-line me-1"></i>Aktif
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="ri-close-line me-1"></i>Nonaktif
                                                    </span>
                                                @endif
                                            </td>
                                            <td data-label="Jumlah Track">
                                                {{ $item->riwayatPerjalanan?->count() ?? '0' }} tracks
                                            </td>
                                            <td data-label="Tanggal Track">
                                                @php
                                                    $lastTrack = $item
                                                        ->riwayatPerjalanan()
                                                        ->latest('created_at')
                                                        ->first();
                                                @endphp
                                                @if ($lastTrack)
                                                    <small class="text-muted">
                                                        {{ $lastTrack->created_at?->format('d-m-Y H:i') ?? '-' }}
                                                    </small>
                                                @else
                                                    <small class="text-muted">Belum ada track</small>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('perusahaan.sesi-alat.show', $item->id) }}"
                                                        class="btn btn-outline-primary" title="Lihat Detail">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
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
                            <p class="text-muted mb-3">Hubungi administrator untuk membuat sesi alat baru</p>
                        </div>
                    @endif
                </div>

                @if ($sesiAlat->hasPages())
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
