@extends('layouts.master')

@section('content')
    <!-- Start::page-header -->
    <div class="detail-header">
        <div>
            <h1 class="mb-0">{{ $kendaraan->nama }}</h1>
        </div>
    </div>
    <!-- End::page-header -->

    <!-- Start::app-content -->
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card card-detail">
                <div class="card-header border-bottom bg-white">
                    <h5 class="card-title mb-0"><i class="ri-information-line me-2"></i>Informasi Lengkap</h5>
                </div>
                <div class="card-body">
                    <div class="detail-row">
                        <div class="detail-label">
                            <strong>ID Kendaraan</strong>
                        </div>
                        <div class="detail-value">
                            <span class="badge bg-light text-dark">{{ $kendaraan->id }}</span>
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">
                            <strong>Nama Kendaraan</strong>
                        </div>
                        <div class="detail-value">
                            <strong>{{ $kendaraan->nama }}</strong>
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">
                            <strong>Status</strong>
                        </div>
                        <div class="detail-value">
                            @if ($kendaraan->sesiAlat()->where('aktif', true)->exists())
                                <span class="badge bg-success">Sedang dalam perjalanan</span>
                            @else
                                <span class="badge bg-danger">Tidak sedang dalam perjalanan</span>
                            @endif
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">
                            <strong>Dibuat Pada</strong>
                        </div>
                        <div class="detail-value">
                            {{ $kendaraan->created_at?->format('d M Y H:i') ?? '-' }}
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">
                            <strong>Terakhir Diperbarui</strong>
                        </div>
                        <div class="detail-value">
                            {{ $kendaraan->updated_at?->format('d M Y H:i') ?? '-' }}
                        </div>
                    </div>
                </div>
                <div style="padding: 16px; background: #f9f9f9; border-top: 1px solid #f0f0f0; border-radius: 0 0 8px 8px;">
                    <div class="btn-action-group">
                        <a href="{{ route('kendaraan.edit', $kendaraan->id) }}" class="btn btn-warning btn-wave">
                            <i class="ri-edit-line me-2"></i>Edit
                        </a>
                        <form action="{{ route('kendaraan.destroy', $kendaraan->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Anda yakin ingin menghapus kendaraan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-primary btn-wave">
                                <i class="ri-delete-bin-line me-2"></i>Hapus
                            </button>
                        </form>
                        <a href="{{ route('kendaraan.index') }}" class="btn btn-outline-secondary btn-wave">
                            <i class="ri-arrow-left-line me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <!-- Statistics Section -->
            <div class="card card-detail">
                <div class="card-header border-bottom bg-white">
                    <h5 class="card-title mb-0"><i class="ri-bar-chart-line me-2"></i>Statistik</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-label text-muted">Perjalanan</div>
                                <div class="stat-value">
                                    <strong class="fs-5">{{ $kendaraan->sesiAlat?->count() ?? '0' }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-label text-muted">Riwayat Perjalanan</div>
                                <div class="stat-value">
                                    <strong class="fs-5">{{ $kendaraan->riwayatPerjalanan?->count() ?? '0' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@endsection

@section('scripts')

@endsection
