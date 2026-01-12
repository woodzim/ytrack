@extends('layouts.master')

@section('content')
  <div class="page-header-section d-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="fs-4 fw-bold">Daftar Alat GPS</h1>
      <p class="mb-0">Monitor status penggunaan alat perusahaan Anda</p>
    </div>
  </div>

  <div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
      <form action="{{ route('perusahaan.alat.index') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-4">
          <label class="form-label text-muted small">Cari Serial Number</label>
          <div class="input-group">
            <span class="input-group-text bg-white"><i class="ri-search-line"></i></span>
            <input type="text" name="search" class="form-control" placeholder="Contoh: GPS-001..."
              value="{{ $search }}">
          </div>
        </div>

        <div class="col-md-3">
          <label class="form-label text-muted small">Status Penggunaan</label>
          <select name="status" class="form-select">
            <option value="">Semua Status</option>
            <option value="1" {{ $filter_status == '1' ? 'selected' : '' }}>Sedang Digunakan</option>
            <option value="0" {{ $filter_status == '0' ? 'selected' : '' }}>Tersedia (Idle)</option>
          </select>
        </div>

        <div class="col-md-2">
          <button type="submit" class="btn btn-primary w-100">
            Filter
          </button>
        </div>
      </form>
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="bg-light">
            <tr>
              <th class="ps-4 py-3" width="5%">No</th>
              <th width="25%">Serial Number (ID Alat)</th>
              <th width="20%">Status</th>
              <th width="35%">Posisi / Kendaraan</th>
              <th width="15%" class="text-end pe-4">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($alatList as $key => $item)
              <tr>
                <td class="ps-4">{{ $alatList->firstItem() + $key }}</td>

                <td class="fw-semibold text-primary">
                  <i class="ri-rfid-line me-1 text-muted"></i>
                  {{ $item->id }}
                </td>

                <td>
                  @if ($item->status_pemakaian == 'digunakan')
                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                      <i class="ri-map-pin-user-line me-1"></i> Sedang Jalan
                    </span>
                  @else
                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">
                      <i class="ri-pause-circle-line me-1"></i> Tersedia
                    </span>
                  @endif
                </td>

                <td>
                  @if ($item->kendaraan_saat_ini)
                    <div>
                      <strong>{{ $item->kendaraan_saat_ini->nama }}</strong>
                      <div class="small text-muted">
                        <span class="badge border border-secondary text-secondary me-1">
                          {{ $item->kendaraan_saat_ini->plat }}
                        </span>
                      </div>
                    </div>
                  @else
                    <span class="text-muted fst-italic small">Belum terpasang di sesi aktif</span>
                  @endif
                </td>

                <td class="text-end pe-4">
                  @if ($item->sesi_id)
                    <a href="{{ route('perusahaan.sesi-alat.show', $item->sesi_id) }}"
                      class="btn btn-sm btn-outline-primary" title="Lihat Lokasi">
                      <i class="ri-map-2-line"></i> Track
                    </a>
                  @else
                    <button class="btn btn-sm btn-light text-muted" disabled>
                      Idle
                    </button>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center py-5">
                  <img src="{{ asset('assets/images/empty.svg') }}" alt="Empty" style="width: 100px; opacity: 0.5"
                    class="mb-3">
                  <p class="text-muted mb-0">Tidak ada data alat yang ditemukan.</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="card-footer bg-white border-top-0 py-3">
      {{ $alatList->links() }} </div>
  </div>
@endsection
