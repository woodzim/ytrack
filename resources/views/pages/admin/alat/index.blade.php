@extends('layouts.master')

@section('content')
  <div class="page-header-section d-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="page-title fw-medium fs-20 mb-0">Daftar Alat (GPS)</h1>
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
      {{-- 1. SEARCH FORM --}}
      <div class="card mb-3">
        <div class="card-body">
          <form action="{{ route('alat.index') }}" method="GET" class="d-flex gap-2 flex-wrap align-items-end">
            <div class="grow" style="min-width: 250px;">
              <label for="searchInput" class="form-label mb-2">Cari Alat</label>
              <input type="text" id="searchInput" name="search" class="form-control"
                placeholder="Cari Serial Number (ID) atau Nama Perusahaan..." value="{{ $search }}">
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

          {{-- 2. ALERT MESSAGES --}}
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

          @if ($alat->count() > 0)
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead class="table-light">
                  <tr>
                    <th style="width: 5%;">No</th>
                    {{-- UBAH HEADER: Jadi Serial Number --}}
                    <th style="width: 25%;">Serial Number (ID)</th>
                    <th style="width: 25%;">Perusahaan (Lokasi)</th>
                    <th style="width: 20%;">Status</th>
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

                      {{-- TAMPILAN BARU: Langsung ID --}}
                      <td data-label="Serial Number">
                        <div class="d-flex align-items-center">
                          <span class="avatar avatar-xs me-2 avatar-rounded bg-light text-muted">
                            <i class="ri-barcode-line"></i>
                          </span>
                          <span class="fw-bold text-dark font-monospace">{{ $item->id }}</span>
                        </div>
                      </td>

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

                      <td data-label="Status">
                        @if ($item->is_aktif)
                          <span class="badge bg-success-transparent text-success">
                            <i class="ri-broadcast-line me-1"></i> Sedang Digunakan
                          </span>
                        @else
                          <span class="badge bg-light text-muted border">
                            <i class="ri-zzz-line me-1"></i> Tersedia
                          </span>
                        @endif
                      </td>

                      <td data-label="Dibuat">
                        <small class="text-muted local-date">{{ $item->created_at->toIso8601String() }}</small>
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
                          onsubmit="return confirm('Anda yakin ingin menghapus alat ID: {{ $item->id }}?');">
                          @csrf
                          @method('DELETE')

                          @if ($item->sedang_digunakan)
                            <button type="button" class="btn btn-sm btn-light text-muted" disabled
                              title="Alat sedang digunakan (Tidak bisa dihapus)">
                              <i class="ri-delete-bin-line"></i>
                            </button>
                          @else
                            <button type="submit" class="btn btn-sm btn-danger btn-wave" title="Hapus">
                              <i class="ri-delete-bin-line"></i>
                            </button>
                          @endif
                        </form>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="p-5 text-center">
              <div class="mb-3">
                @if ($search)
                  <i class="ri-search-line" style="font-size: 48px; color: #ddd;"></i>
                @else
                  <i class="ri-router-line" style="font-size: 56px; color: #ddd;"></i>
                @endif
              </div>
              @if ($search)
                <h5 class="text-muted mb-2">Tidak ada hasil pencarian</h5>
                <p class="text-muted mb-3">Tidak ditemukan alat dengan Serial Number atau Perusahaan
                  "{{ $search }}"</p>
                <a href="{{ route('alat.index') }}" class="btn btn-outline-primary btn-wave">
                  <i class="ri-arrow-left-line me-2"></i>Kembali ke Daftar
                </a>
              @else
                <h5 class="text-muted mb-2">Belum ada data alat</h5>
                <p class="text-muted mb-3">Tambahkan perangkat GPS baru menggunakan ID Serial Number</p>
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

@section('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const dateElements = document.querySelectorAll('.local-date');

      dateElements.forEach(el => {
        const rawDate = el.textContent;
        if (rawDate) {
          const date = new Date(rawDate);
          const formatted = new Intl.DateTimeFormat('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
          }).format(date);

          el.textContent = formatted;
        }
      });
    });
  </script>
@endsection
