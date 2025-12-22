@extends('layouts.master')

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
                                                    <span class="badge bg-light text-dark">{{ $item->id }}</span>
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
                                                    <span class="badge" style="background-color: #10b981; color: white;">Perangkat sedang digunakan</span>
                                                @else
                                                    <span class="badge" style="background-color: #6b7280; color: white;">Perangkat sedang tidak digunakan</span>
                                                @endif
                                            </td>
                                            <td data-label="Aksi" class="table-action-btns text-center">
                                                <a href="{{ route('alat.show', $item->id) }}" class="btn btn-sm btn-info btn-wave" title="Lihat">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                {{-- <a href="{{ route('alat.edit', $item->id) }}" class="btn btn-sm btn-warning btn-wave" title="Edit">
                                                    <i class="ri-edit-line"></i>
                                                </a> --}}
                                                <form action="{{ route('alat.destroy', $item->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Anda yakin ingin menghapus alat ini?');">
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
