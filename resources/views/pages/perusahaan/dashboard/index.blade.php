@extends('layouts.master')

@section('styles')
  <style>
    /* --- Layout Refinements --- */
    #map-dashboard {
      height: 55vh;
      width: 100%;
      border-radius: 8px;
      position: relative;
      z-index: 1;
    }

    /* Reset margin untuk elemen pertama agar sejajar sempurna */
    .col-lg-9> :first-child,
    .aside-stats-container> :first-child,
    .aside-stats-shell> :first-child {
      margin-top: 0 !important;
    }

    .table-hover tbody tr {
      cursor: pointer;
    }

    .leaflet-top,
    .leaflet-bottom {
      z-index: 400 !important;
    }

    /* --- Enhanced Card Styles --- */
    .custom-card-enhanced {
      border: 1px solid var(--default-border) !important;
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05) !important;
      background: var(--custom-white) !important;
      border-radius: 12px !important;
    }

    .stats-card-enhanced {
      background: var(--custom-white) !important;
      border: 1px solid var(--default-border) !important;
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05) !important;
      margin-bottom: 1.25rem;
      border-radius: 12px;
      transition: all 0.3s ease;
    }

    .stats-card-enhanced:hover {
      transform: translateY(-3px);
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08) !important;
    }

    .icon-box {
      width: 48px;
      height: 48px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 10px;
    }

    .chart-container {
      background: var(--custom-white) !important;
      border-radius: 12px;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
      border: 1px solid var(--default-border) !important;
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05) !important;
      position: relative;
    }

    .chart-title {
      font-size: 1rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
      color: #1e293b;
      display: flex;
      align-items: center;
      border-bottom: 1px solid #f1f5f9;
      padding-bottom: 0.75rem;
    }

    .chart-title i {
      margin-right: 0.75rem;
      font-size: 1.25rem;
      color: #09637E;
    }

    .monitoring-card {
      overflow: hidden;
    }

    .monitoring-card-header {
      background: linear-gradient(180deg, rgba(9, 99, 126, 0.05), rgba(9, 99, 126, 0.015));
      border-bottom: 1px solid #e9eef5;
      padding: 1rem 1.25rem;
    }

    .monitoring-card-title {
      font-size: 1rem;
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 0.2rem;
    }

    .monitoring-card-subtitle {
      margin: 0;
      font-size: 0.8rem;
      color: #64748b;
    }

    .monitoring-map-wrap {
      padding: 1rem;
      background: linear-gradient(180deg, rgba(248, 250, 252, 0.9), rgba(255, 255, 255, 1));
      border-bottom: 1px solid #edf2f7;
    }

    .monitoring-list-intro {
      padding: 0.95rem 1.25rem 0.85rem;
      border-bottom: 1px solid #edf2f7;
      background: #fff;
    }

    .monitoring-list-title {
      margin: 0;
      font-size: 0.86rem;
      font-weight: 700;
      color: #334155;
    }

    .monitoring-list-subtitle {
      margin: 0.18rem 0 0;
      font-size: 0.76rem;
      color: #64748b;
    }

    .device-table-card {
      overflow: hidden;
    }

    .device-table-card .card-header {
      background: linear-gradient(180deg, rgba(9, 99, 126, 0.04), rgba(9, 99, 126, 0.01));
      border-bottom: 1px solid #e9eef5;
      padding: 1rem 1.25rem;
    }

    .device-table-title {
      font-size: 1rem;
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 0.2rem;
    }

    .device-table-subtitle {
      margin: 0;
      font-size: 0.8rem;
      color: #64748b;
    }

    .device-count-badge {
      border: 1px solid rgba(9, 99, 126, 0.08);
      box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.35);
      font-weight: 600;
    }

    .device-table-wrap {
      background: #fff;
    }

    .device-table {
      margin-bottom: 0;
    }

    .device-table thead th {
      background: #f8fafc;
      color: #64748b;
      font-size: 0.68rem;
      font-weight: 800;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      padding-top: 0.85rem;
      padding-bottom: 0.85rem;
      border-bottom: 1px solid #e9eef5;
      white-space: nowrap;
    }

    .device-table tbody td {
      padding-top: 0.95rem;
      padding-bottom: 0.95rem;
      border-top: 1px solid #edf2f7;
      vertical-align: middle;
      background: #fff;
    }

    .device-table tbody tr {
      transition: background-color 0.2s ease, transform 0.2s ease;
    }

    .device-table tbody tr:hover {
      background: #f8fbfd;
    }

    .device-table tbody tr:hover td {
      background: #f8fbfd;
    }

    .device-row.table-danger td {
      background: #fff6f6;
    }

    .device-name {
      font-size: 0.88rem;
      font-weight: 700;
      color: #1e293b;
      line-height: 1.3;
      margin-bottom: 0.2rem;
    }

    .device-serial {
      display: inline-block;
      font-size: 0.72rem;
      color: #64748b;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 999px;
      padding: 0.15rem 0.5rem;
      font-family: inherit;
    }

    .device-vehicle {
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      font-weight: 600;
      color: #334155;
    }

    .device-battery-col,
    .device-action-col {
      white-space: nowrap;
    }

    .device-battery-col {
      text-align: center;
    }

    .device-status-pill {
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      border-radius: 999px;
      padding: 0.38rem 0.7rem;
      font-size: 0.72rem;
      font-weight: 700;
      letter-spacing: 0.01em;
      border: 1px solid transparent;
      white-space: nowrap;
    }

    .device-status-pill--active {
      color: #15803d;
      background: #f0fdf4;
      border-color: #bbf7d0;
    }

    .device-status-pill--inactive {
      color: #b91c1c;
      background: #fef2f2;
      border-color: #fecaca;
    }

    .device-update {
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      font-size: 0.78rem;
      color: #64748b;
    }

    .device-action-btn {
      width: 34px;
      height: 34px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      border: 1px solid rgba(9, 99, 126, 0.12);
      background: #f8fafc;
      color: #09637E;
      transition: all 0.2s ease;
    }

    .device-action-btn:hover {
      background: #09637E;
      color: #fff;
      border-color: #09637E;
      transform: translateY(-1px);
    }

    .mobile-device-list {
      background: #f8fafc;
      padding: 0.75rem;
      gap: 0.75rem;
    }

    .mobile-device-card {
      border: 1px solid #e6edf5;
      border-radius: 14px;
      background: #fff;
      box-shadow: 0 0.125rem 0.25rem rgba(15, 23, 42, 0.04);
      padding: 1rem;
    }

    .mobile-device-card.bg-danger-transparent {
      background: #fff7f7 !important;
      border-color: #fecaca;
    }

    .mobile-device-top {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 0.75rem;
      margin-bottom: 1rem;
    }

    .mobile-device-heading {
      min-width: 0;
      flex: 1;
    }

    .mobile-device-status {
      flex: 0 0 auto;
    }

    .mobile-device-card .device-name {
      margin-bottom: 0.35rem;
      padding-right: 0.25rem;
    }

    .mobile-device-card .device-status-pill {
      font-size: 0.68rem;
      padding: 0.35rem 0.62rem;
    }

    .mobile-device-meta {
      display: grid;
      gap: 0.7rem;
    }

    .mobile-device-meta-item {
      display: flex;
      align-items: flex-start;
      gap: 0.65rem;
      color: #475569;
      font-size: 0.8rem;
    }

    .mobile-device-meta-icon {
      width: 28px;
      height: 28px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 8px;
      background: #f8fafc;
      color: #09637E;
      flex: 0 0 28px;
    }

    .mobile-device-meta-copy {
      min-width: 0;
      display: grid;
      gap: 0.12rem;
    }

    .mobile-device-meta-label {
      font-size: 0.68rem;
      font-weight: 700;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      color: #94a3b8;
    }

    .mobile-device-meta-value {
      font-size: 0.84rem;
      font-weight: 600;
      color: #334155;
    }

    .mobile-device-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 0.9rem;
      padding-top: 0.95rem;
      margin-top: 1rem;
      border-top: 1px solid #eef2f7;
    }

    .mobile-device-actions {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 0.7rem;
      width: 100%;
    }

    .mobile-device-battery {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .mobile-device-battery-copy {
      display: grid;
      gap: 0.12rem;
    }

    .mobile-device-battery-label {
      font-size: 0.68rem;
      font-weight: 700;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      color: #94a3b8;
    }

    .mobile-device-battery-value {
      font-size: 0.82rem;
      font-weight: 600;
      color: #334155;
    }

    .aside-stats-container {
      position: relative;
    }

    .aside-stats-shell {
      position: sticky;
      top: 5.5rem;
      z-index: 10;
    }

    .aside-stats-scroll {
      max-height: calc(100vh - 6.5rem);
      overflow-y: auto;
      padding: 1rem;
      scrollbar-width: thin;
      scrollbar-color: #cbd5e1 #f8fafc;
    }

    .aside-stats-scroll::-webkit-scrollbar {
      width: 7px;
    }

    .aside-stats-scroll::-webkit-scrollbar-track {
      background: #f8fafc;
      border-radius: 999px;
    }

    .aside-stats-scroll::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 999px;
    }

    .aside-stats-scroll .stats-card-enhanced:last-of-type {
      margin-bottom: 0;
    }

    /* Pengaturan khusus Aktivitas Chart agar tidak overflow */
    .activity-chart-container {
      padding-left: 0.5rem !important;
      padding-right: 0.5rem !important;
      overflow: hidden;
    }

    .activity-chart-container .chart-title {
      margin-left: 1rem;
      margin-right: 1rem;
    }

    @media (max-width: 991.98px) {
      .aside-stats-container {
        margin-top: 2rem !important;
      }

      .aside-stats-shell {
        position: static;
      }

      .aside-stats-scroll {
        max-height: none;
        overflow: visible;
        padding: 0;
      }

      .device-table-card .card-header {
        padding: 0.9rem 1rem;
      }

      .monitoring-card-header,
      .monitoring-list-intro,
      .monitoring-map-wrap {
        padding-left: 1rem;
        padding-right: 1rem;
      }

      .mobile-device-list {
        padding: 0.65rem;
      }

      .mobile-device-top {
        gap: 0.6rem;
      }

      .mobile-device-card {
        padding: 0.95rem;
      }

      #map-dashboard {
        height: 45vh;
      }
    }
  </style>
@endsection

@section('content')
  <!-- Start::page-header -->
  <div class="page-header-section d-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="page-title fw-medium fs-20 mb-0">Beranda YA-Track</h1>
      <p class="">Pantau kendaraan Anda secara langsung dengan data akurat.</p>
    </div>
  </div>
  <!-- End::page-header -->

  <!-- Alert Section -->
  <div id="low-bat-alert-container">
    @if ($lowBatCount > 0)
      <div class="alert alert-danger d-flex align-items-center mb-4 shadow-sm border-0" role="alert"
        style="cursor: pointer; border-left: 5px solid #dc3545 !important;"
        onclick="document.getElementById('daftar-alat-table').scrollIntoView({behavior: 'smooth'})">
        <i class="ri-error-warning-fill fs-24 me-3"></i>
        <div class="flex-grow-1">
          <strong class="d-block text-uppercase fs-12">Peringatan Baterai Lemah!</strong>
          Terdapat <b id="alert-low-bat-count">{{ $lowBatCount }}</b> perangkat yang memerlukan pengisian daya segera.
        </div>
        <div class="ms-auto text-end d-none d-sm-block">
          <span class="badge bg-danger text-white border-0 py-2">Klik untuk Lihat <i
              class="ri-arrow-right-line ms-1"></i></span>
        </div>
      </div>
    @endif
  </div>

  <!-- Start::app-content -->
  <div class="row gx-4 dashboard-main-row">
    <!-- Main Content (Left) -->
    <div class="col-lg-9">
      <!-- Monitoring Section -->
      <div class="card mb-4 custom-card-enhanced monitoring-card device-table-card" id="daftar-alat-table">
        <div class="card-header monitoring-card-header">
          <div class="d-flex justify-content-between align-items-center w-100 flex-column flex-md-row gap-2">
            <div>
              <h5 class="card-title monitoring-card-title">Monitoring Posisi Terakhir Setiap Perjalanan</h5>
              <p class="monitoring-card-subtitle">Pantau posisi terakhir setiap perjalanan alat Anda secara <i>real-time</i>.</p>
            </div>
            <span
              class="badge bg-primary-transparent text-primary px-3 py-2 rounded-pill align-self-start align-self-md-center device-count-badge">
              {{ count($lokasiAlat) }} perjalanan
            </span>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="monitoring-map-wrap">
            <div id="map-dashboard"></div>
          </div>

          <div class="monitoring-list-intro">
            <p class="monitoring-list-subtitle">Klik baris atau kartu alat untuk memfokuskan peta ke unit yang dipilih.</p>
          </div>

          {{-- Desktop View: Table --}}
          <div class="table-responsive d-none d-md-block device-table-wrap">
            <table class="table table-hover table-sm align-middle device-table" style="font-size: 0.85rem;">
              <thead>
                <tr>
                  <th class="ps-3 py-2 border-0">Nama Alat / No. Seri</th>
                  <th class="py-2 border-0">Kendaraan</th>
                  <th class="py-2 border-0 text-center">Baterai</th>
                  <th class="py-2 border-0">Status</th>
                  <th class="py-2 border-0">Pembaruan</th>
                  <th class="text-center py-2 border-0">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($lokasiAlat as $alat)
                  <tr class="device-row {{ $alat['is_low_bat'] ? 'table-danger' : '' }}" id="row-alat-{{ $alat['id_sesi'] }}"
                    @if ($alat['id_sesi']) onclick="focusMarker('{{ $alat['id_sesi'] }}')" @endif>
                    <td class="ps-3 py-2">
                      <div class="device-name name-cell">
                        @if ($alat['is_low_bat'])
                          <i class="ri-error-warning-fill text-danger me-1"></i>
                        @endif
                        {{ $alat['nama_alat'] }}
                      </div>
                      <span class="device-serial">{{ $alat['serial_number'] }}</span>
                    </td>
                    <td class="py-2">
                      <div class="device-vehicle">
                        <i class="ri-truck-line text-primary fs-14"></i>
                        {{ $alat['kendaraan'] }}
                      </div>
                    </td>
                    <td class="battery-cell py-2 device-battery-col">
                      <x-battery-circle :level="$alat['battery']" size="30px" stroke="3" />
                    </td>
                    <td class="py-2">
                      <span
                        class="device-status-pill {{ $alat['status'] == 'Aktif' ? 'device-status-pill--active' : 'device-status-pill--inactive' }}">
                        <i class="ri-checkbox-blank-circle-fill fs-9 me-1"></i> {{ $alat['status'] }}
                      </span>
                    </td>
                    <td class="update-cell py-2">
                      <span class="device-update"><i
                          class="ri-time-line me-1"></i>{{ $alat['last_update'] }}</span>
                    </td>
                    <td class="text-center py-2 device-action-col">
                      @if ($alat['id_sesi'])
                        <a href="{{ route('perusahaan.perjalanan.show', $alat['id_sesi']) }}"
                          class="device-action-btn"
                          aria-label="Lihat detail alat {{ $alat['nama_alat'] }}">
                          <i class="ri-map-2-line fs-14"></i>
                        </a>
                      @else
                        <span class="text-muted">-</span>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                      <i class="ri-inbox-line fs-40 d-block mb-2"></i>
                      Tidak ada data alat aktif saat ini.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          {{-- Mobile View: Cards --}}
          <div class="d-block d-md-none">
            <div class="list-group list-group-flush rounded-bottom mobile-device-list">
              @forelse($lokasiAlat as $alat)
                <div class="list-group-item mobile-device-card {{ $alat['is_low_bat'] ? 'bg-danger-transparent' : '' }}"
                  @if ($alat['id_sesi']) onclick="focusMarker('{{ $alat['id_sesi'] }}')" style="cursor: pointer;" @endif>
                  <div class="mobile-device-top">
                    <div class="mobile-device-heading">
                      <div class="device-name mb-1">
                        @if ($alat['is_low_bat'])
                          <i class="ri-error-warning-fill text-danger me-1"></i>
                        @endif
                        {{ $alat['nama_alat'] }}
                      </div>
                      <span class="device-serial">{{ $alat['serial_number'] }}</span>
                    </div>
                    <div class="mobile-device-status">
                      <span
                        class="device-status-pill {{ $alat['status'] == 'Aktif' ? 'device-status-pill--active' : 'device-status-pill--inactive' }}">
                        <i class="ri-checkbox-blank-circle-fill fs-9 me-1"></i> {{ $alat['status'] }}
                      </span>
                    </div>
                  </div>

                  <div class="mobile-device-meta">
                    <div class="mobile-device-meta-item">
                      <span class="mobile-device-meta-icon">
                        <i class="ri-truck-line"></i>
                      </span>
                      <div class="mobile-device-meta-copy">
                        <span class="mobile-device-meta-label">Kendaraan</span>
                        <span class="mobile-device-meta-value">{{ $alat['kendaraan'] }}</span>
                      </div>
                    </div>
                    <div class="mobile-device-meta-item text-muted">
                      <span class="mobile-device-meta-icon">
                        <i class="ri-time-line"></i>
                      </span>
                      <div class="mobile-device-meta-copy">
                        <span class="mobile-device-meta-label">Pembaruan</span>
                        <span class="mobile-device-meta-value">{{ $alat['last_update'] }}</span>
                      </div>
                    </div>
                  </div>

                  <div class="mobile-device-footer">
                    <div class="mobile-device-actions">
                      <div class="mobile-device-battery">
                        <div class="device-battery-col">
                          <x-battery-circle :level="$alat['battery']" size="38px" stroke="3" />
                        </div>
                        <div class="mobile-device-battery-copy">
                          <span class="mobile-device-battery-label">Baterai</span>
                          <span class="mobile-device-battery-value">{{ $alat['battery'] }}%</span>
                        </div>
                      </div>
                      @if ($alat['id_sesi'])
                        <a href="{{ route('perusahaan.perjalanan.show', $alat['id_sesi']) }}"
                          class="device-action-btn"
                          aria-label="Lihat detail alat {{ $alat['nama_alat'] }}">
                          <i class="ri-map-2-line fs-14"></i>
                        </a>
                      @endif
                    </div>
                  </div>
                </div>
              @empty
                <div class="p-4 text-center text-muted">
                  <i class="ri-inbox-line fs-40 d-block mb-2"></i>
                  Tidak ada data alat aktif saat ini.
                </div>
              @endforelse
            </div>
          </div>
          {{-- End Mobile View --}}

        </div>
      </div>

      <!-- Charts Row 1 -->
      <div class="row">
        <div class="col-12 mb-4">
          <h5 class="fw-semibold mb-3">Grafik Aktivitas Pelacakan (7 Hari Terakhir)</h5>
          <div class="activity-chart-container">
            <div id="activity-chart"></div>
          </div>
        </div>
      </div>

      <!-- Charts Row 2 -->
      <div class="row">
        <div class="col-12 mb-4">
          <div class="chart-container custom-card-enhanced h-100">
            <div class="chart-title">
              <i class="ri-battery-2-charge-line"></i> Status Baterai Alat
            </div>
            <div id="battery-chart" class="d-flex justify-content-center"></div>
          </div>
        </div>
        <div class="col-xl-6 mb-4">
          <div class="chart-container custom-card-enhanced text-center h-100">
            <div class="chart-title d-flex justify-content-between align-items-center text-start">
              <span><i class="ri-donut-chart-line"></i> Penggunaan Kendaraan</span>
              <span class="badge bg-light text-muted fw-normal">Bergerak dan Berhenti</span>
            </div>
            <div id="status-chart" class="d-flex justify-content-center"></div>
          </div>
        </div>
        <div class="col-xl-6 mb-4">
          <div class="chart-container custom-card-enhanced text-center h-100">
            <div class="chart-title d-flex justify-content-between align-items-center text-start">
              <span><i class="ri-cpu-line"></i> Penggunaan Alat</span>
              <span class="badge bg-light text-muted fw-normal">Aktif dan Nonaktif</span>
            </div>
            <div id="tool-status-chart" class="d-flex justify-content-center"></div>
          </div>
        </div>
      </div>

      <!-- Aktivitas Terakhir -->
      <div class="card mb-4 custom-card-enhanced">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">Riwayat Perjalanan Terakhir</h5>
          <a href="{{ route('perusahaan.perjalanan.index') }}"
            class="btn btn-sm btn-outline-primary px-3 rounded-pill fw-bold">
            LIHAT SEMUA
          </a>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <tbody>
                @forelse($recentRiwayat as $item)
                  <tr>
                    <td class="ps-4" style="width: 60px;">
                      <div class="avatar avatar-sm bg-primary-transparent text-primary rounded-circle">
                        <i class="ri-history-line"></i>
                      </div>
                    </td>
                    <td>
                      <div class="fw-bold text-dark">{{ $item->kendaraan?->nama ?? 'Unit Armada' }}</div>
                      <div class="text-muted small">Alat: <span
                          class="text-dark fw-medium">{{ $item->alat?->id ?? '-' }}</span></div>
                    </td>
                    <td>
                      <span class="badge bg-light text-muted border fw-normal">
                        <i class="ri-map-pin-line me-1"></i> {{ $item->lat }}, {{ $item->long }}
                      </span>
                    </td>
                    <td class="text-end pe-4">
                      <div class="text-dark fw-bold small">{{ $item->created_at?->translatedFormat('d M Y, H:i') }}
                      </div>
                      <div class="text-muted tiny">{{ $item->created_at?->diffForHumans() }}</div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center py-5 text-muted fst-italic">Belum ada aktivitas perjalanan
                      yang
                      tercatat.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Sidebar Stats (Aside) -->
    <div class="col-lg-3 aside-stats-container">
      <div class="card custom-card-enhanced aside-stats-shell overflow-hidden">
        <div class="card-body aside-stats-scroll">
          @if ($lowBatCount > 0)
            <!-- BATERAI LEMAH -->
            <div class="card stats-card-enhanced border-start border-danger border-4" id="card-low-bat"
              style="cursor: pointer;"
              onclick="document.getElementById('daftar-alat-table').scrollIntoView({behavior: 'smooth'})">
              <div class="card-body p-4">
                <div class="d-flex align-items-center">
                  <div class="icon-box bg-danger-transparent text-danger">
                    <i class="ri-battery-low-line fs-24"></i>
                  </div>
                  <div class="ms-3">
                    <h6 class="text-muted mb-1 fs-11 text-uppercase fw-bold ls-1">Baterai Lemah</h6>
                    <h3 class="fw-black mb-0 text-danger" id="count-low-bat">
                      {{ $lowBatCount }}</h3>
                  </div>
                </div>
              </div>
            </div>
          @endif

          <div class="card stats-card-enhanced border-0">
            <div class="card-body p-4">
              <div class="d-flex align-items-center">
                <div class="icon-box bg-success-transparent text-success">
                  <i class="ri-map-pin-line fs-24"></i>
                </div>
                <div class="ms-3">
                  <h6 class="text-muted mb-1 fs-11 text-uppercase fw-bold ls-1">Sedang Bergerak</h6>
                  <h3 class="fw-black mb-0 text-success" id="count-moving-vehicles">{{ $kendaraanDalamPerjalanan }}</h3>
                </div>
              </div>
            </div>
          </div>

          <div class="card stats-card-enhanced border-0">
            <div class="card-body p-4">
              <div class="d-flex align-items-center">
                <div class="icon-box bg-primary-transparent text-primary">
                  <i class="ri-route-line fs-24"></i>
                </div>
                <div class="ms-3">
                  <h6 class="text-muted mb-1 fs-11 text-uppercase fw-bold ls-1">Total Perjalanan</h6>
                  <h3 class="fw-black mb-0 text-dark">{{ $totalSesiAlat }}</h3>
                </div>
              </div>
            </div>
          </div>

          <div class="card stats-card-enhanced border-0">
            <div class="card-body p-4">
              <div class="d-flex align-items-center">
                <div class="icon-box bg-warning-transparent text-warning">
                  <i class="ri-cpu-line fs-24"></i>
                </div>
                <div class="ms-3">
                  <h6 class="text-muted mb-1 fs-11 text-uppercase fw-bold ls-1">Total Alat</h6>
                  <h3 class="fw-black mb-0 text-dark">{{ $totalAlat }}</h3>
                </div>
              </div>
            </div>
          </div>

          <div class="card stats-card-enhanced border-0">
            <div class="card-body p-4">
              <div class="d-flex align-items-center">
                <div class="icon-box bg-info-transparent text-info">
                  <i class="ri-car-fill fs-24"></i>
                </div>
                <div class="ms-3">
                  <h6 class="text-muted mb-1 fs-11 text-uppercase fw-bold ls-1">Total Kendaraan</h6>
                  <h3 class="fw-black mb-0 text-dark">{{ $totalKendaraan }}</h3>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    let markers = {};
    let map;
    let markerCluster;
    let isUserFocusing = false;
    let trackedSesiId = null;
    let fitTimeout;

    const fitAllMarkers = () => {
      if (isUserFocusing || trackedSesiId) return;
      var markerArray = Object.values(markers);
      if (markerArray.length > 0) {
        var group = new L.featureGroup(markerArray);
        map.fitBounds(group.getBounds().pad(0.1), {
          animate: true,
          maxZoom: 13
        });
      }
    };

    const focusMarker = (id_sesi, skipScroll = false) => {
      if (markers[id_sesi]) {
        clearTimeout(fitTimeout);
        fitTimeout = null;
        trackedSesiId = String(id_sesi);
        isUserFocusing = true;
        const marker = markers[id_sesi];
        map.stop();
        map.setView(marker.getLatLng(), 18);
        if (markerCluster.hasLayer(marker)) {
          markerCluster.zoomToShowLayer(marker, () => {
            marker.openPopup();
          });
        } else {
          marker.openPopup();
        }
        if (!skipScroll) {
          const element = document.getElementById('map-dashboard');
          window.scrollTo({
            top: element.getBoundingClientRect().top + window.scrollY - 100,
            behavior: 'smooth'
          });
        }
      }
    };

    document.addEventListener('DOMContentLoaded', function() {
      // --- MAP SETUP ---
      const streets = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; Ya-Track Dumeg',
        maxZoom: 18
      });

      map = L.map('map-dashboard', {
        center: [-2.5489, 118.0149],
        zoom: 3, // Dikurangi dari 5 menjadi 3 (agar view lebih luas)
        layers: [streets],
        gestureHandling: true,
        fullscreenControl: {
          position: 'bottomright'
        },
        preferCanvas: true,
        attributionControl: false
      });

      markerCluster = L.markerClusterGroup({
        showCoverageOnHover: false,
        spiderfyOnMaxZoom: true,
        disableClusteringAtZoom: 19
      });
      map.addLayer(markerCluster);

      var locations = @json($lokasiAlat);
      var lowBatSessions = new Set();

      const getMarkerIcon = () => {
        const color = 'var(--primary-color)'; // Menggunakan warna palet utama
        const html = `<div style="background: white; border-radius: 50%; padding: 2px; box-shadow: 0 2px 5px rgba(0,0,0,0.3); border: 2px solid ${color}; display: flex; align-items: center; justify-content: center; width: 32px; height: 32px;">
                        <i class="ri-map-pin-fill" style="color: ${color}; font-size: 20px;"></i>
                      </div>`;
        return L.divIcon({
          html: html,
          className: '',
          iconSize: [32, 32],
          iconAnchor: [16, 32],
          popupAnchor: [0, -32]
        });
      };

      const getBatteryHtml = (level, size = '32px', fontSize = '8px', maskSize = '3px') => {
        const levelInt = parseInt(level || 0);
        let color = '#be1e2d';
        if (levelInt > 70) color = '#10b981';
        else if (levelInt > 30) color = '#f59e0b';
        const borderThickness = parseInt(maskSize);
        return `<div class="battery-circle-container d-flex align-items-center justify-content-center">
                  <div style="position: relative; width: ${size}; height: ${size}; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: radial-gradient(closest-side, white calc(100% - ${borderThickness}px), transparent calc(100% - ${borderThickness}px + 0.5px)), conic-gradient(${color} ${levelInt}%, #e9ecef 0);">
                    <span style="font-size: ${fontSize}; font-weight: 700; color: ${color}; z-index: 1;">${levelInt}%</span>
                  </div>
                </div>`;
      };

      map.on('popupclose', function() {
        setTimeout(() => {
          let anyPopupOpen = false;
          Object.values(markers).forEach(m => {
            if (m.getPopup() && m.getPopup().isOpen()) anyPopupOpen = true;
          });
          if (!anyPopupOpen) {
            trackedSesiId = null;
            isUserFocusing = false;
            clearTimeout(fitTimeout);
            fitTimeout = setTimeout(fitAllMarkers, 1000);
          }
        }, 200);
      });

      locations.forEach(function(loc) {
        if (loc.is_low_bat && loc.id_sesi) lowBatSessions.add(loc.id_sesi);
        if (loc.lat && loc.long) {
          var marker = L.marker([loc.lat, loc.long], {
              icon: getMarkerIcon()
            })
            .bindPopup(`<div class="p-1" id="popup-${loc.id_sesi}">
                          <div class="fw-bold mb-1">${loc.nama_alat}</div>
                          <div class="text-muted small mb-2">${loc.kendaraan}</div>
                          <div class="d-flex justify-content-between align-items-center border-top pt-2">
                            <span class="small">Baterai:</span>
                            <div class="battery-popup-container">${getBatteryHtml(loc.battery)}</div>
                          </div>
                          <div class="text-muted text-end mt-1 text-xs last-update-text">${loc.last_update}</div>
                        </div>`, {
              minWidth: 150,
              autoPan: false
            });

          marker.on('click', () => focusMarker(loc.id_sesi, true));
          markerCluster.addLayer(marker);
          if (loc.id_sesi) markers[loc.id_sesi] = marker;
        }

        if (loc.id_sesi && loc.status === 'Aktif' && window.Echo) {
          window.Echo.channel(`tracking.sesi.${loc.id_sesi}`)
            .listen('.LokasiBaru', (e) => {
              const latLng = [parseFloat(e.lat), parseFloat(e.lng)];
              const battery = parseInt(e.battery || 0);
              const isLowBat = battery <= 30;

              if (markers[loc.id_sesi]) {
                const marker = markers[loc.id_sesi];
                marker.setLatLng(latLng);
                if (typeof markerCluster.refreshClusters === 'function') markerCluster.refreshClusters(marker);

                const popup = marker.getPopup();
                if (popup && popup.isOpen()) {
                  const popupElement = popup.getElement();
                  if (popupElement) {
                    const batContainer = popupElement.querySelector('.battery-popup-container');
                    const updateText = popupElement.querySelector('.last-update-text');
                    if (batContainer) batContainer.innerHTML = getBatteryHtml(battery);
                    if (updateText) updateText.innerText = 'Sesaat lalu';
                  }
                }

                if (trackedSesiId && String(loc.id_sesi) === trackedSesiId) {
                  map.panTo(latLng, {
                    animate: true
                  });
                } else if (!isUserFocusing && !trackedSesiId) {
                  clearTimeout(fitTimeout);
                  fitTimeout = setTimeout(() => {
                    if (!isUserFocusing && !trackedSesiId) fitAllMarkers();
                  }, 2000);
                }
              }

              const row = document.getElementById(`row-alat-${loc.id_sesi}`);
              if (row) {
                const batCell = row.querySelector('.battery-cell');
                if (batCell) batCell.innerHTML = getBatteryHtml(battery, '40px', '10px', '4px');
                const updateCell = row.querySelector('.update-cell');
                if (updateCell) updateCell.innerHTML =
                  `<span class="text-muted small"><i class="ri-time-line me-1"></i>Sesaat lalu</span>`;
                if (isLowBat) row.classList.add('table-danger');
                else row.classList.remove('table-danger');
              }
            });
        }
      });

      if (Object.keys(markers).length > 0) fitAllMarkers();

      setTimeout(() => {
        map.invalidateSize();
      }, 500);

      // --- CHARTS SETUP ---
      const activityOptions = {
        series: [{
          name: 'Rekaman Lokasi',
          data: @json(collect($last7Days)->pluck('count'))
        }],
        chart: {
          height: 380,
          type: 'area',
          width: '100%',
          toolbar: {
            show: false
          },
          zoom: {
            enabled: false
          }
        },
        stroke: {
          curve: 'smooth',
          width: 3,
          colors: ['#09637E']
        },
        fill: {
          type: 'gradient',
          gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.4,
            opacityTo: 0.1,
            stops: [0, 90, 100]
          }
        },
        xaxis: {
          categories: @json(collect($last7Days)->map(fn($d) => \Carbon\Carbon::parse($d['date'])->translatedFormat('d M')))
        },
        yaxis: {
          show: false
        },
        grid: {
          borderColor: '#f1f1f1',
          strokeDashArray: 4
        }
      };
      new ApexCharts(document.querySelector("#activity-chart"), activityOptions).render();

      const batteryOptions = {
        series: [@json($batteryStats['good']), @json($batteryStats['moderate']), @json($batteryStats['low'])],
        chart: {
          type: 'donut',
          height: 300,
          width: '100%'
        },
        labels: ['Bagus (>70%)', 'Cukup (30-70%)', 'Lemah (<30%)'],
        colors: ['#10b981', '#f59e0b', '#ef4444'],
        legend: {
          position: 'bottom',
          height: 60
        },
        plotOptions: {
          pie: {
            donut: {
              size: '75%',
              labels: {
                show: true,
                total: {
                  show: true,
                  label: 'Total Alat',
                  formatter: () => @json(count($lokasiAlat))
                }
              }
            }
          }
        }
      };
      new ApexCharts(document.querySelector("#battery-chart"), batteryOptions).render();

      const statusOptions = {
        series: [@json($vehicleStatusStats['moving']), @json($vehicleStatusStats['idle'])],
        chart: {
          type: 'donut',
          height: 300,
          width: '100%'
        },
        labels: ['Bergerak', 'Berhenti'],
        colors: ['#3b82f6', '#94a3b8'],
        legend: {
          position: 'bottom',
          height: 60
        },
        plotOptions: {
          pie: {
            donut: {
              size: '70%',
              labels: {
                show: true,
                total: {
                  show: true,
                  label: 'Total Armada',
                  formatter: () => @json($totalKendaraan)
                }
              }
            }
          }
        }
      };
      new ApexCharts(document.querySelector("#status-chart"), statusOptions).render();

      const toolStatusOptions = {
        series: [@json($toolStatusStats['active']), @json($toolStatusStats['idle'])],
        chart: {
          type: 'donut',
          height: 300,
          width: '100%'
        },
        labels: ['Aktif', 'Nonaktif'],
        colors: ['#f59e0b', '#cbd5e1'],
        legend: {
          position: 'bottom',
          height: 60
        },
        plotOptions: {
          pie: {
            donut: {
              size: '70%',
              labels: {
                show: true,
                total: {
                  show: true,
                  label: 'Total Alat',
                  formatter: () => @json($totalAlat)
                }
              }
            }
          }
        }
      };
      new ApexCharts(document.querySelector("#tool-status-chart"), toolStatusOptions).render();
    });
  </script>
@endsection
