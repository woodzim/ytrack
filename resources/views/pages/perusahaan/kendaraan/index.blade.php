@extends('layouts.master')

@section('content')
  <div class="page-header-section d-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="page-title fw-medium fs-20 mb-0">Manajemen Kendaraan</h1>
      <p class="">Kelola semua kendaraan perusahaan Anda</p>
    </div>
    <div class="ms-auto">
      <a href="{{ route('perusahaan.kendaraan.create') }}" class="btn btn-light btn-wave">
        <i class="ri-add-line me-2"></i>Tambah Kendaraan
      </a>
    </div>
  </div>
  <div class="row">
    <div class="col-12">

      <div class="card custom-card mb-3">
        <div class="card-body">
          <form action="{{ route('perusahaan.kendaraan.index') }}" method="GET"
            class="d-flex gap-2 flex-wrap align-items-end">
            <div class="grow" style="min-width: 250px;">
              <label for="searchInput" class="form-label mb-1">Cari Kendaraan</label>
              <input type="text" id="searchInput" name="search" class="form-control"
                placeholder="Cari No Polisi atau Nama..." value="{{ $search }}">
            </div>
            <button type="submit" class="btn btn-primary btn-wave">
              <i class="ri-search-line me-2"></i>Cari
            </button>
            @if ($search)
              <a href="{{ route('perusahaan.kendaraan.index') }}" class="btn btn-light btn-wave">
                <i class="ri-close-line me-2"></i>Reset
              </a>
            @endif
          </form>
        </div>
      </div>

      <div class="card custom-card table-wrapper">
        <div class="card-header border-bottom-0 bg-white py-3">
          <div class="d-flex justify-content-between align-items-center w-100">
            <h5 class="card-title mb-0">Daftar Kendaraan ({{ $kendaraan->total() }})</h5>
            @if ($search)
              <span class="badge bg-info-transparent">Hasil pencarian: "{{ $search }}"</span>
            @endif
          </div>
        </div>

        <div class="card-body p-0">
          {{-- MENAMPILKAN ALERT ERROR JIKA GAGAL HAPUS --}}
          @if (session('success'))
            <div class="alert alert-success m-3 alert-dismissible fade show" role="alert">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif
          @if (session('error'))
            <div class="alert alert-danger m-3 alert-dismissible fade show" role="alert">
              <i class="ri-error-warning-line me-2"></i> {{ session('error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          @if ($kendaraan->count() > 0)
            <div class="table-responsive">
              <table class="table table-hover text-nowrap mb-0">
                <thead class="table-light">
                  <tr>
                    <th width="5%">No</th>
                    <th width="25%">Nama Kendaraan</th>
                    <th width="15%">Plat Nomor</th>
                    <th width="20%">Jenis</th>
                    <th width="15%">Status</th>
                    <th width="10%" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($kendaraan as $index => $item)
                    <tr>
                      <td>
                        {{ $kendaraan->firstItem() + $index }}
                      </td>
                      <td>
                        <div class="fw-bold">{{ $item->nama }} {{ $item->tipe }}</div>
                      </td>
                      <td>
                        <span class="badge bg-light text-dark border">
                          {{ $item->plat }}
                        </span>
                      </td>
                      <td>
                        {{ $item->jenisKendaraan->nama ?? '-' }}
                      </td>

                      {{-- STATUS: Menggunakan variable 'sedang_digunakan' dari Controller --}}
                      <td>
                        @if ($item->sedang_digunakan)
                          <span class="badge bg-success-transparent">
                            <i class="ri-steering-2-line me-1"></i> Jalan
                          </span>
                        @else
                          <span class="badge bg-secondary-transparent">
                            <i class="ri-parking-box-line me-1"></i> Parkir
                          </span>
                        @endif
                      </td>

                      <td class="text-center">
                        <div class="btn-group btn-group-sm">
                          <a href="{{ route('perusahaan.kendaraan.edit', $item->id) }}" class="btn btn-warning-light"
                            data-bs-toggle="tooltip" title="Edit">
                            <i class="ri-edit-line"></i>
                          </a>

                          {{-- LOGIKA TOMBOL HAPUS --}}
                          <form action="{{ route('perusahaan.kendaraan.destroy', $item->id) }}" method="POST"
                            class="d-inline"
                            onsubmit="return confirm('Yakin ingin menghapus kendaraan {{ $item->plat }}?');">
                            @csrf
                            @method('DELETE')

                            @if ($item->sedang_digunakan)
                              {{-- Jika sedang jalan, tombol didisable --}}
                              <button type="button" class="btn btn-light text-muted" disabled data-bs-toggle="tooltip"
                                title="Sedang digunakan (Tidak bisa dihapus)">
                                <i class="ri-delete-bin-line"></i>
                              </button>
                            @else
                              {{-- Jika parkir, tombol aktif --}}
                              <button type="submit" class="btn btn-danger-light" data-bs-toggle="tooltip" title="Hapus">
                                <i class="ri-delete-bin-line"></i>
                              </button>
                            @endif
                          </form>

                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="6" class="text-center py-5">
                        <i class="ri-car-line fs-1 mb-3 text-muted opacity-25"></i>
                        <h6 class="text-muted">Data kendaraan tidak ditemukan.</h6>
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          @else
            <div class="p-5 text-center">
              <div class="mb-3">
                <i class="ri-car-line" style="font-size: 48px; color: #ddd;"></i>
                <h5 class="text-muted mt-3">Belum ada kendaraan</h5>
                <a href="{{ route('perusahaan.kendaraan.create') }}" class="btn mt-2">
                  <i class="ri-add-line me-1"></i>Tambah Kendaraan
                </a>
              </div>
            </div>
          @endif
        </div>

        @if ($kendaraan->hasPages())
          <div class="card-footer bg-white border-top-0 d-flex justify-content-end py-3">
            {{ $kendaraan->links() }}
          </div>
        @endif

      </div>
    </div>
  </div>
@endsection
