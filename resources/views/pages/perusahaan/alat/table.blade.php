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
              <span class="badge bg-success text-white px-3 py-2 rounded-pill">
                <i class="ri-map-pin-user-line me-1"></i> Sedang Jalan
              </span>
            @else
              <span class="badge bg-secondary text-white px-3 py-2 rounded-pill">
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
              <a href="{{ route('perusahaan.perjalanan.show', $item->sesi_id) }}" class="btn btn-sm btn-outline-primary"
                title="Lihat Lokasi">
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

<div class="card-footer border-top-0 py-3">
  {{ $alatList->links() }}
</div>
