@extends('layouts.master')

@section('styles')
  <style>
    /* --- Layout Refinements --- */
    #map-dashboard {
      height: 55vh;
      /* Ditetapkan 55vh sesuai permintaan */
      width: 100%;
      border-radius: 8px;
      position: relative;
      z-index: 1;
    }

    /* Reset margin untuk elemen pertama agar sejajar sempurna */
    .col-lg-9> :first-child,
    .aside-stats-container> :first-child {
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

    /* Fixed height for chart containers to match admin dashboard */
    .chart-container-fixed {
      min-height: 380px;
      position: relative;
      width: 100%;
    }

    @media (max-width: 991.98px) {
      .aside-stats-container {
        margin-top: 2rem !important;
      }

      #map-dashboard {
        height: 45vh;
      }
    }
  </style>
@endsection

@section('content')
  <!-- Start::page-header -->
  <div class="d-flex align-items-center justify-content-between mb-4 page-header-breadcrumb flex-wrap gap-2">
    <div>
      <h1 class="page-title fw-bold fs-24 mb-1">Beranda YA-Track</h1>
      <p class="text-muted mb-0">Pantau kendaraan Anda secara langsung dengan data akurat.</p>
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
          <span class="badge bg-white-transparent text-white border-0 py-2">Klik untuk Lihat <i
              class="ri-arrow-right-line ms-1"></i></span>
        </div>
      </div>
    @endif
  </div>

  <!-- Start::app-content -->
  <div class="row gx-4 dashboard-main-row">
    <!-- Main Content (Left) -->
    <div class="col-lg-9">
      <!-- Map Section -->
      <div class="card mb-4 custom-card-enhanced overflow-hidden">
        <div class="card-body p-0">
          <div id="map-dashboard"></div>
        </div>
      </div>

      <!-- Data Alat Table -->
      <div class="card mb-4 custom-card-enhanced" id="daftar-alat-table">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">Daftar Alat & Status Terbaru</h5>
          <span class="badge bg-primary-transparent text-primary px-3 rounded-pill">{{ count($lokasiAlat) }} Alat
            Terdeteksi</span>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="bg-light text-uppercase fs-11 fw-bold text-muted">
                <tr>
                  <th class="ps-3 border-0">Nama Alat / No. Seri</th>
                  <th class="border-0">Kendaraan</th>
                  <th class="border-0">Baterai</th>
                  <th class="border-0">Status</th>
                  <th class="border-0">Pembaruan</th>
                  <th class="text-center border-0">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($lokasiAlat as $alat)
                  <tr class="{{ $alat['is_low_bat'] ? 'table-danger' : '' }}" id="row-alat-{{ $alat['id_sesi'] }}"
                    @if ($alat['id_sesi']) onclick="focusMarker('{{ $alat['id_sesi'] }}')" @endif>
                    <td class="ps-3">
                      <div class="fw-bold text-dark name-cell">
                        @if ($alat['is_low_bat'])
                          <i class="ri-error-warning-fill text-danger me-1"></i>
                        @endif
                        {{ $alat['nama_alat'] }}
                      </div>
                      <code class="text-muted small">{{ $alat['serial_number'] }}</code>
                    </td>
                    <td>
                      <div class="d-flex align-items-center fw-medium text-dark">
                        <i class="ri-truck-line me-2 text-primary fs-16"></i>
                        {{ $alat['kendaraan'] }}
                      </div>
                    </td>
                    <td class="battery-cell">
                      <x-battery-circle :level="$alat['battery']" size="40px" />
                    </td>
                    <td>
                      <span
                        class="badge rounded-pill {{ $alat['status'] == 'Aktif' ? 'bg-success-transparent text-success' : 'bg-danger-transparent text-danger' }}">
                        <i class="ri-checkbox-blank-circle-fill fs-8 me-1"></i> {{ $alat['status'] }}
                      </span>
                    </td>
                    <td class="update-cell">
                      <span class="text-muted small"><i class="ri-time-line me-1"></i>{{ $alat['last_update'] }}</span>
                    </td>
                    <td class="text-center">
                      @if ($alat['id_sesi'])
                        <a href="{{ route('perusahaan.sesi-alat.show', $alat['id_sesi']) }}"
                          class="btn btn-sm btn-icon btn-primary-transparent rounded-circle">
                          <i class="ri-map-2-line"></i>
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
          <a href="{{ route('perusahaan.sesi-alat.index') }}"
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
      @if ($lowBatCount > 0)
        <!-- BATERAI LEMAH -->
        <div class="card stats-card-enhanced border-0 border-start border-danger border-4" id="card-low-bat"
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

      <div class="card border-0 shadow-sm mt-4 custom-card-enhanced bg-light">
        <div class="card-body p-4 text-center">
          <div class="mb-3">
            <i class="ri-information-fill text-primary fs-32"></i>
          </div>
          <h6 class="page-title fw-medium fs-20 mb-0">Tips Pemantauan</h6>
          <p class="text-muted small mb-0 fst-italic">Klik baris pada tabel "Daftar Alat & Status Terbaru" untuk
            memfokuskan peta ke lokasi unit tersebut secara otomatis.</p>
        </div>
      </div>
    </div>
  </div>
  <!-- End::app-content -->
@endsection

@section('scripts')
  <script>
    let markers = {};
    let map;
    let markerCluster;
    let isUserFocusing = false;
    let trackedSesiId = null;
    let fitTimeout;

    // Helper: Fit all markers into view
    const fitAllMarkers = () => {
      // Jika sedang fokus (isUserFocusing true) atau ada alat yang dilacak, JANGAN panggil fitBounds
      if (isUserFocusing || trackedSesiId) return;

      var markerArray = Object.values(markers);
      if (markerArray.length > 0) {
        var group = new L.featureGroup(markerArray);
        map.fitBounds(group.getBounds().pad(0.1), {
          animate: true,
          maxZoom: 18
        });
      }
    };

    const focusMarker = (id_sesi, skipScroll = false) => {
      if (markers[id_sesi]) {
        // Batalkan semua antrian fitAllMarkers yang mungkin masih berjalan
        clearTimeout(fitTimeout);
        fitTimeout = null;

        trackedSesiId = String(id_sesi); // Simpan sebagai string untuk perbandingan presisi
        isUserFocusing = true;

        const marker = markers[id_sesi];
        map.stop(); // Hentikan animasi peta yang sedang berlangsung
        map.setView(marker.getLatLng(), 22);

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
        zoom: 5,
        layers: [streets],
        gestureHandling: true,
        fullscreenControl: true,
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

      const getMarkerIcon = (isFilled = false) => {
        const type = isFilled ? 'ri-map-pin-fill' : 'ri-map-pin-line';
        const color = '#be1e2d';
        const html = `<div style="background: white; border-radius: 50%; padding: 2px; box-shadow: 0 2px 5px rgba(0,0,0,0.3); border: 2px solid ${color}; display: flex; align-items: center; justify-content: center; width: 32px; height: 32px;">
                        <i class="${type}" style="color: ${color}; font-size: 20px;"></i>
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

      // Listener: Saat popup ditutup, kembalikan tampilan ke seluruh alat jika benar-benar tidak ada yang difokuskan
      map.on('popupclose', function() {
        // Berikan delay sedikit untuk mengecek apakah ini perpindahan fokus atau benar-benar ditutup
        setTimeout(() => {
          let anyPopupOpen = false;
          // Cek apakah ada marker yang masih memiliki popup terbuka
          Object.values(markers).forEach(m => {
            if (m.getPopup() && m.getPopup().isOpen()) {
              anyPopupOpen = true;
            }
          });

          if (!anyPopupOpen) {
            trackedSesiId = null;
            isUserFocusing = false;
            clearTimeout(fitTimeout);
            fitTimeout = setTimeout(fitAllMarkers, 1000);
          }
        }, 200);
      });

      // Inisialisasi marker
      locations.forEach(function(loc) {
        if (loc.is_low_bat && loc.id_sesi) lowBatSessions.add(loc.id_sesi);

        if (loc.lat && loc.long) {
          var marker = L.marker([loc.lat, loc.long], {
              icon: getMarkerIcon(false)
            })
            .bindPopup(`<div class="p-1" id="popup-${loc.id_sesi}">
                          <div class="fw-bold mb-1">${loc.nama_alat}</div>
                          <div class="text-muted small mb-2">${loc.kendaraan}</div>
                          <div class="d-flex justify-content-between align-items-center border-top pt-2">
                            <span class="small">Baterai:</span>
                            <div class="battery-popup-container">${getBatteryHtml(loc.battery, '32px', '8px', '3px')}</div>
                          </div>
                          <div class="text-muted text-end mt-1 text-xs last-update-text">
                            ${loc.last_update}
                          </div>
                        </div>`, {
              minWidth: 150,
              autoPan: false
            });

          // Unifikasi: Klik marker memanggil focusMarker yang sama dengan tabel
          marker.on('click', function() {
            focusMarker(loc.id_sesi, true);
          });

          markerCluster.addLayer(marker);
          if (loc.id_sesi) markers[loc.id_sesi] = marker;
        }

        // --- Realtime Setup per Active Session ---
        if (loc.id_sesi && loc.status === 'Aktif' && window.Echo) {
          window.Echo.channel(`tracking.sesi.${loc.id_sesi}`)
            .listen('.LokasiBaru', (e) => {
              const latLng = [parseFloat(e.lat), parseFloat(e.lng)];
              const battery = parseInt(e.battery || 0);
              const isLowBat = battery <= 20;

              // 1. Update Map Marker
              if (markers[loc.id_sesi]) {
                const marker = markers[loc.id_sesi];
                // Cek apakah alat yang baru gerak ini adalah alat yang sedang kita kunci fokusnya
                const isThisToolTracked = (trackedSesiId && String(loc.id_sesi) === trackedSesiId);

                marker.setLatLng(latLng);
                if (typeof markerCluster.refreshClusters === 'function') {
                  markerCluster.refreshClusters(marker);
                }

                // Update popup content if open
                const popup = marker.getPopup();
                if (popup && popup.isOpen()) {
                  const popupElement = popup.getElement();
                  if (popupElement) {
                    const batContainer = popupElement.querySelector('.battery-popup-container');
                    const updateText = popupElement.querySelector('.last-update-text');
                    if (batContainer) {
                      batContainer.innerHTML = getBatteryHtml(battery, '32px', '8px', '3px');
                    }
                    if (updateText) updateText.innerText = 'Sesaat lalu';
                  }
                }

                // LOGIKA KRUSIAL: Ikuti hanya jika alat ini yang dilacak
                if (isThisToolTracked) {
                  map.panTo(latLng, {
                    animate: true
                  });
                } else if (!isUserFocusing && !trackedSesiId) {
                  // Jika TIDAK ADA alat yang dilacak, barulah sesuaikan viewport agar semua masuk layar (debounced)
                  clearTimeout(fitTimeout);
                  fitTimeout = setTimeout(() => {
                    if (!isUserFocusing && !trackedSesiId) fitAllMarkers();
                  }, 2000);
                }
              }

              // 2. Update Table Row
              const row = document.getElementById(`row-alat-${loc.id_sesi}`);
              if (row) {
                const batCell = row.querySelector('.battery-cell');
                if (batCell) {
                  batCell.innerHTML = getBatteryHtml(battery, '40px', '10px', '4px');
                }

                const updateCell = row.querySelector('.update-cell');
                if (updateCell) {
                  const icon = updateCell.querySelector('i') ? updateCell.querySelector('i').outerHTML + ' ' :
                    '';
                  updateCell.innerHTML = `<span class="text-muted small">${icon}Sesaat lalu</span>`;
                }

                const nameCell = row.querySelector('.name-cell');
                if (isLowBat) {
                  row.classList.add('table-danger');
                  if (nameCell && !nameCell.querySelector('.ri-error-warning-fill')) {
                    nameCell.insertAdjacentHTML('afterbegin',
                      '<i class="ri-error-warning-fill text-danger me-1"></i>');
                  }
                } else {
                  row.classList.remove('table-danger');
                  if (nameCell) {
                    const icon = nameCell.querySelector('.ri-error-warning-fill');
                    if (icon) icon.remove();
                  }
                }
              }

              // 3. Update Global Low Battery Stats
              const wasLowBat = lowBatSessions.has(loc.id_sesi);
              if (isLowBat && !wasLowBat) {
                lowBatSessions.add(loc.id_sesi);
              } else if (!isLowBat && wasLowBat) {
                lowBatSessions.delete(loc.id_sesi);
              }

              const currentCount = lowBatSessions.size;
              const countDisplay = document.getElementById('count-low-bat');
              const cardLowBat = document.getElementById('card-low-bat');
              const alertContainer = document.getElementById('low-bat-alert-container');

              if (countDisplay) {
                countDisplay.innerText = currentCount;
                countDisplay.className = 'fw-black mb-0 ' + (currentCount > 0 ? 'text-danger' : 'text-dark');
              }

              if (cardLowBat) {
                if (currentCount > 0) {
                  cardLowBat.style.display = 'block';
                  cardLowBat.classList.add('border-start', 'border-danger', 'border-4');
                } else {
                  cardLowBat.style.display = 'none';
                }
              }

              if (currentCount > 0) {
                if (!document.getElementById('alert-low-bat-count')) {
                  alertContainer.innerHTML = `<div class="alert alert-danger d-flex align-items-center mb-4 border-0" role="alert" style="cursor: pointer; border-left: 5px solid #dc3545 !important;"
                    onclick="document.getElementById('daftar-alat-table').scrollIntoView({behavior: 'smooth'})">
                    <i class="ri-error-warning-fill fs-24 me-3"></i>
                    <div class="flex-grow-1">
                      <strong class="d-block text-uppercase fs-12">Peringatan Baterai Lemah!</strong> 
                      Terdapat <b id="alert-low-bat-count">${currentCount}</b> perangkat yang memerlukan pengisian daya segera.
                    </div>
                    <div class="ms-auto text-end d-none d-sm-block">
                      <span class="badge bg-primary-transparent text-primary border-0 py-2">Klik untuk Lihat <i class="ri-arrow-right-line ms-1"></i></span>
                    </div>
                  </div>`;
                } else {
                  document.getElementById('alert-low-bat-count').innerText = currentCount;
                }
              } else {
                alertContainer.innerHTML = '';
              }
            });
        }
      });

      if (Object.keys(markers).length > 0) {
        const firstSesiId = Object.keys(markers)[0];
        focusMarker(firstSesiId, true);
      }

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
          },
          fontFamily: 'inherit',
          animations: {
            enabled: true
          },
          sparkline: {
            enabled: false
          },
          parentHeightOffset: 0
        },
        dataLabels: {
          enabled: false
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
            stops: [0, 90, 100],
            colorStops: [{
              offset: 0,
              color: '#be1e2d',
              opacity: 0.4
            }]
          }
        },
        xaxis: {
          type: 'category',
          categories: @json(collect($last7Days)->map(fn($d) => \Carbon\Carbon::parse($d['date'])->translatedFormat('d M'))),
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false
          },
          labels: {
            style: {
              colors: '#64748b',
              fontWeight: 500
            }
          },
          tooltip: {
            enabled: false
          }
        },
        yaxis: {
          show: false
        },
        grid: {
          borderColor: '#f1f1f1',
          strokeDashArray: 4,
          padding: {
            left: 20,
            right: 20,
            top: 0,
            bottom: 0
          }
        },
        tooltip: {
          theme: 'light',
          x: {
            show: true
          }
        }
      };

      const activityChart = new ApexCharts(document.querySelector("#activity-chart"), activityOptions);

      setTimeout(() => {
        activityChart.render();
        window.dispatchEvent(new Event('resize'));
      }, 300);

      const batteryOptions = {
        series: [@json($batteryStats['good']), @json($batteryStats['moderate']), @json($batteryStats['low'])],
        chart: {
          type: 'donut',
          height: 280,
          width: '100%'
        },
        labels: ['Bagus (>70%)', 'Cukup (30-70%)', 'Lemah (<30%)'],
        colors: ['#10b981', '#f59e0b', '#ef4444'],
        legend: {
          position: 'bottom',
          fontWeight: 600
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
                  fontWeight: 700,
                  color: '#1e293b',
                  formatter: () => @json(count($lokasiAlat))
                }
              }
            }
          }
        },
        dataLabels: {
          enabled: false
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
          fontWeight: 600
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
        },
        dataLabels: {
          enabled: false
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
          fontWeight: 600
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
        },
        dataLabels: {
          enabled: false
        }
      };
      new ApexCharts(document.querySelector("#tool-status-chart"), toolStatusOptions).render();
    });
  </script>
@endsection
