@extends('layouts.master')

@section('content')
  <!-- Start::page-header -->
  <div class="page-header-section d-flex align-items-center justify-content-between">
    <div>
      <h1>Daftar Perusahaan</h1>
      <p>Kelola semua perusahaan di sistem</p>
    </div>
    <div class="ms-auto">
      <a href="{{ route('perusahaan.create') }}" class="btn btn-light btn-wave">
        <i class="ri-add-line me-2"></i>Tambah Perusahaan
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
          <form action="{{ route('perusahaan.index') }}" method="GET" class="d-flex gap-2 flex-wrap align-items-end">
            <div class="grow" style="min-width: 250px;">
              <label for="searchInput" class="form-label mb-2">Cari Perusahaan</label>
              <input type="text" id="searchInput" name="search" class="form-control"
                placeholder="Cari berdasarkan nama atau alamat..." value="{{ $search }}">
            </div>
            <button type="submit" class="btn btn-primary btn-wave">
              <i class="ri-search-line me-2"></i>Cari
            </button>
            @if ($search)
              <a href="{{ route('perusahaan.index') }}" class="btn btn-outline-secondary btn-wave">
                <i class="ri-close-line me-2"></i>Reset
              </a>
            @endif
          </form>
        </div>
      </div>

      <div class="card table-wrapper">
        <div class="card-header border-bottom-0 bg-white py-3">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Perusahaan ({{ $perusahaan->total() }})</h5>
            @if ($search)
              <span class="badge bg-info">Hasil pencarian: "{{ $search }}"</span>
            @endif
          </div>
        </div>
        <div class="card-body p-0">
          @if ($perusahaan->count() > 0)
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead class="table-light">
                  <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 25%;">Nama Perusahaan</th>
                    <th style="width: 50%;">Alamat</th>
                    <th style="width: 20%;" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($perusahaan as $item)
                    <tr>
                      <td data-label="No">
                        <span class="badge bg-light text-dark">{{ $item->id }}</span>
                      </td>
                      <td data-label="Nama Perusahaan">
                        <strong>{{ $item->nama }}</strong>
                      </td>
                      <td data-label="Alamat">
                        <small class="text-muted">{{ Str::limit($item->alamat, 60) }}</small>
                      </td>
                      <td data-label="Aksi" class="table-action-btns text-center">
                        <a href="{{ route('perusahaan.show', $item->id) }}" class="btn btn-sm btn-info btn-wave"
                          title="Lihat">
                          <i class="ri-eye-line"></i>
                        </a>
                        <a href="{{ route('perusahaan.edit', $item->id) }}" class="btn btn-sm btn-warning btn-wave"
                          title="Edit">
                          <i class="ri-edit-line"></i>
                        </a>
                        <form action="{{ route('perusahaan.destroy', $item->id) }}" method="POST"
                          style="display: inline;"
                          onsubmit="return confirm('Anda yakin ingin menghapus perusahaan ini?');">
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
                @if ($search)
                  <i class="ri-search-line" style="font-size: 48px; color: #ddd;"></i>
                @else
                  <i class="ri-building-line" style="font-size: 48px; color: #ddd;"></i>
                @endif
              </div>
              @if ($search)
                <h5 class="text-muted mb-2">Tidak ada hasil pencarian</h5>
                <p class="text-muted mb-3">Tidak ditemukan perusahaan dengan kata kunci "{{ $search }}"</p>
                <a href="{{ route('perusahaan.index') }}" class="btn btn-outline-primary btn-wave">
                  <i class="ri-arrow-left-line me-2"></i>Kembali ke Daftar
                </a>
              @else
                <h5 class="text-muted mb-2">Tidak ada data perusahaan</h5>
                <p class="text-muted mb-3">Mulai dengan menambahkan perusahaan pertama Anda</p>
                <a href="{{ route('perusahaan.create') }}" class="btn btn-primary btn-wave">
                  <i class="ri-add-line me-2"></i>Tambah Perusahaan
                </a>
              @endif
            </div>
          @endif
        </div>

        @if ($perusahaan->hasPages())
          <div class="card-footer bg-white border-top py-3">
            <div class="d-flex justify-content-end">
              {{ $perusahaan->links('pagination::bootstrap-5') }}
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
  <!-- End::app-content -->
@endsection

@section('scripts')
@endsection
