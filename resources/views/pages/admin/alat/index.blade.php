@extends('layouts.master')

@section('content')
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
  <div class="row">
    <div class="col-12">
      <div class="card mb-3">
        <div class="card-body">
          <form action="{{ route('alat.index') }}" method="GET" class="d-flex gap-2 flex-wrap align-items-end">
            <div class="grow" style="min-width: 250px;">
              <label for="searchInput" class="form-label mb-2">Cari Alat</label>
              <input type="text" id="searchInput" name="search" class="form-control"
                placeholder="Cari ID alat atau nama perusahaan..." value="{{ $search }}">
            </div>
            <button type="submit" class="btn btn-primary btn-wave">
              <i class="ri-search-line me-2"></i>Cari
            </button>
            @if ($search)
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
            @if ($search)
              <span class="badge bg-info">Hasil pencarian: "{{ $search }}"</span>
            @endif
          </div>
        </div>
        <div class="card-body p-0">
          @if ($alat->count() > 0)
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead class="table-light">
                  <tr>
                    {{-- Sesuaikan lebar kolom agar rapi --}}
                    <th style="width: 5%;">No</th>
                    <th style="width: 20%;">Kode Alat</th>
                    <th style="width: 25%;">Perusahaan (Lokasi)</th>
                    <th style="width: 20%;">Status Aktif</th>
                    <th style="width: 15%;">Dibuat</th>
                    <th class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($alat as $index => $item)
                    <tr>
                      <td data-label="No">
                        <span class="badge bg-light text-dark">{{ $alat->firstItem() + $index }}</span>
                      </td>

                      <td data-label="Kode Alat">
                        <div class="d-flex align-items-center gap-2">
                          <span class="fw-bold text-dark">{{ $item->kode_alat }}</span>
                          {{-- Tombol pintas cari sesi --}}
                          <a href="{{ route('sesi-alat.index', ['search' => $item->kode_alat]) }}" class="text-muted"
                            title="Lihat History Sesi">
                            <i class="ri-external-link-line"></i>
                          </a>
                        </div>
                      </td>

                      {{-- KOLOM BARU: Perusahaan --}}
                      <td data-label="Perusahaan">
                        @if ($item->perusahaan)
                          <div class="d-flex align-items-center">
                            <span class="avatar avatar-xs me-2 avatar-rounded bg-primary-transparent text-primary">
                              <i class="ri-building-line"></i>
                            </span>
                            <span class="fw-medium text-primary">{{ $item->perusahaan->nama }}</span>
                          </div>
                        @else
                          <span class="text-muted fst-italic">
                            <i class="ri-error-warning-line me-1"></i>Belum ditempatkan
                          </span>
                        @endif
                      </td>

                      <td data-label="Aktif">
                        @if ($item->is_aktif)
                          <span class="badge bg-success-transparent">
                            <i class="ri-wifi-line me-1"></i> Sedang Digunakan
                          </span>
                        @else
                          <span class="badge bg-light text-muted border">
                            <i class="ri-zzz-line me-1"></i> Tidak Digunakan
                          </span>
                        @endif
                      </td>

                      <td data-label="Dibuat">
                        <small class="text-muted">{{ $item->created_at->format('d M Y') }}</small>
                      </td>

                      <td data-label="Aksi" class="table-action-btns text-center">
                        <a href="{{ route('alat.show', $item->id) }}" class="btn btn-sm btn-info btn-wave"
                          title="Lihat Detail">
                          <i class="ri-eye-line"></i>
                        </a>

                        <a href="{{ route('alat.edit', $item->id) }}" class="btn btn-sm btn-warning btn-wave"
                          title="Edit">
                          <i class="ri-edit-line"></i>
                        </a>

                        <form action="{{ route('alat.destroy', $item->id) }}" method="POST" style="display: inline;"
                          onsubmit="return confirm('Anda yakin ingin menghapus alat {{ $item->id }}?');">
                          @csrf
                          @method('DELETE')
                          {{-- Ubah jadi merah (danger) agar sesuai standar --}}
                          <button type="submit" class="btn btn-sm btn-danger btn-wave" title="Hapus">
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
                  <i class="ri-gps-line" style="font-size: 48px; color: #ddd;"></i>
                @endif
              </div>
              @if ($search)
                <h5 class="text-muted mb-2">Tidak ada hasil pencarian</h5>
                <p class="text-muted mb-3">Tidak ditemukan alat dengan kata kunci "{{ $search }}"</p>
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

        @if ($alat->hasPages())
          <div class="card-footer bg-white border-top py-3">
            <div class="d-flex justify-content-end">
              {{ $alat->links('pagination::bootstrap-5') }}
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection
