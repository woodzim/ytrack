@extends('layouts.master')

@section('content')
    <!-- Start::page-header -->
    <div class="page-header-section d-flex align-items-center justify-content-between">
        <div>
            <h1>Manajemen Kendaraan</h1>
            <p>Kelola semua kendaraan perusahaan Anda</p>
        </div>
        <div class="ms-auto">
            <a href="{{ route('kendaraan.create') }}" class="btn btn-light btn-wave">
                <i class="ri-add-line me-2"></i>Tambah Kendaraan
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
                    <form action="{{ route('kendaraan.index') }}" method="GET" class="d-flex gap-2 flex-wrap align-items-end">
                        <div class="flex-grow-1" style="min-width: 250px;">
                            <label for="searchInput" class="form-label mb-2">Cari Kendaraan</label>
                            <input type="text" id="searchInput" name="search" class="form-control" 
                                   placeholder="Cari berdasarkan nama..." 
                                   value="{{ $search }}">
                        </div>
                        <button type="submit" class="btn btn-primary btn-wave">
                            <i class="ri-search-line me-2"></i>Cari
                        </button>
                        @if($search)
                            <a href="{{ route('kendaraan.index') }}" class="btn btn-outline-secondary btn-wave">
                                <i class="ri-close-line me-2"></i>Reset
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <div class="card table-wrapper">
                <div class="card-header border-bottom-0 bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Daftar Kendaraan ({{ $kendaraan->total() }})</h5>
                        @if($search)
                            <span class="badge bg-info">Hasil pencarian: "{{ $search }}"</span>
                        @endif
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($kendaraan->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 5%;">No</th>
                                        <th style="width: 40%;">Nama Kendaraan</th>
                                        <th style="width: 20%;">Status</th>
                                        <th style="width: 20%;">Dibuat</th>
                                        <th style="width: 15%;" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kendaraan as $item)
                                        <tr>
                                            <td data-label="No">
                                                <span class="badge bg-light text-dark">{{ $item->id }}</span>
                                            </td>
                                            <td data-label="Nama Kendaraan">
                                                <strong>{{ $item->nama }}</strong>
                                            </td>
                                            <td data-label="Status">
                                                <span class="badge bg-success">Aktif</span>
                                            </td>
                                            <td data-label="Dibuat">
                                                <small class="text-muted">{{ $item->created_at->format('d M Y') }}</small>
                                            </td>
                                            <td data-label="Aksi" class="table-action-btns text-center">
                                                <a href="{{ route('kendaraan.show', $item->id) }}" class="btn btn-sm btn-info btn-wave" title="Lihat">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a href="{{ route('kendaraan.edit', $item->id) }}" class="btn btn-sm btn-warning btn-wave" title="Edit">
                                                    <i class="ri-edit-line"></i>
                                                </a>
                                                <form action="{{ route('kendaraan.destroy', $item->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Anda yakin ingin menghapus kendaraan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-primary btn-wave" title="Hapus">
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
                                    <p class="text-muted mt-3">Tidak ada kendaraan yang cocok dengan pencarian "{{ $search }}"</p>
                                @else
                                    <i class="ri-car-line" style="font-size: 48px; color: #ddd;"></i>
                                    <p class="text-muted mt-3">Belum ada kendaraan</p>
                                    <a href="{{ route('kendaraan.create') }}" class="btn btn-sm btn-primary">
                                        <i class="ri-add-line me-1"></i>Tambah Kendaraan
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pagination -->
            @if($kendaraan->count() > 0)
                <div class="d-flex justify-content-center mt-4">
                    {{ $kendaraan->links() }}
                </div>
            @endif
        </div>
    </div>
    <!-- End::app-content -->
@endsection

@section('scripts')

@endsection
