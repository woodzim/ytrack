@extends('layouts.master')

@section('styles')
  <style>
    /* --- Styling Map Icon (Sesuai Desain Anda) --- */
    .custom-pin-icon {
      background: transparent;
      border: none;
    }

    .pin-wrapper {
      position: relative;
      width: 50px;
      height: 50px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .pin-wrapper i {
      font-size: 40px;
      color: #0F2854;
      /* Blue D-LSP / Primary */
      filter: drop-shadow(0 3px 2px rgba(0, 0, 0, 0.3));
      z-index: 2;
    }

    /* Animasi Pulse */
    .pin-pulse {
      position: absolute;
      width: 100%;
      height: 100%;
      background: rgba(15, 40, 84, 0.4);
      /* Blue D-LSP / Primary Transparent */
      border-radius: 50%;
      z-index: 1;
      animation: pulse-animation 2s infinite;
      bottom: 3px;
      transform: scaleX(1) scaleY(0.5);
    }

    @keyframes pulse-animation {
      0% {
        transform: scale(0.5);
        opacity: 1;
      }

      100% {
        transform: scale(1.5);
        opacity: 0;
      }
    }

    /* --- Style Interaksi Tabel --- */
    .clickable-row {
      cursor: pointer;
      transition: background-color 0.2s ease;
    }

    .table-active-row {
      background-color: #e0f2fe !important;
      /* Biru muda */
      border-left: 4px solid #0ea5e9;
    }

    #map-container {
      height: 400px;
      width: 100%;
      border-radius: 8px;
      margin-bottom: 20px;
      position: relative;
      z-index: 1;
    }

    .leaflet-top,
    .leaflet-bottom {
      z-index: 400 !important;
    }

    /* --- CUSTOM LEAFLET CONTROLS --- */
    .leaflet-bar,
    .leaflet-touch .leaflet-bar {
      border: none !important;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
    }

    .leaflet-bar a,
    .leaflet-touch .leaflet-bar a {
      width: 34px !important;
      height: 34px !important;
      line-height: 34px !important;
      border-radius: 6px !important;
      margin-bottom: 5px !important;
      background-color: #fff !important;
      color: #5d6576 !important;
      transition: all 0.2s ease;
      border: 1px solid #e2e8ee !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
    }

    .leaflet-bar a:last-child,
    .leaflet-touch .leaflet-bar a:last-child {
      margin-bottom: 0 !important;
      border-bottom: 1px solid #e2e8ee !important;
    }

    .leaflet-bar a:hover,
    .leaflet-touch .leaflet-bar a:hover {
      background-color: #f9fafb !important;
      color: #0F2854 !important;
      border-color: #0F2854 !important;
    }

    /* Fix Zoom Icons centering */
    .leaflet-control-zoom-in span,
    .leaflet-control-zoom-out span {
      line-height: 34px;
    }

    /* Tracking & Fullscreen Button Customization */
    .leaflet-control-tracking,
    .leaflet-control-fullscreen {
      width: 34px !important;
      height: 34px !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      background: white !important;
      border: 1px solid #e2e8ee !important;
      border-radius: 6px !important;
      cursor: pointer;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      transition: all 0.2s ease;
      text-decoration: none !important;
    }

    .leaflet-control-tracking i,
    .leaflet-control-fullscreen i {
      color: #5d6576;
      font-size: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .leaflet-control-tracking:hover,
    .leaflet-control-fullscreen:hover {
      background: #f9fafb !important;
      border-color: #0F2854 !important;
    }

    .leaflet-control-tracking:hover i,
    .leaflet-control-fullscreen:hover i {
      color: #0F2854;
    }

    .leaflet-control-tracking.is-tracking {
      background: #0F2854 !important;
      border-color: #0F2854 !important;
      box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06);
    }

    .leaflet-control-tracking.is-tracking i {
      color: white !important;
    }

    /* Fullscreen Support */
    #map-container:fullscreen {
      width: 100vw !important;
      height: 100vh !important;
      border-radius: 0 !important;
      margin: 0 !important;
    }

    /* Ensure icon inside is visible */
    .leaflet-control-tracking i,
    .leaflet-control-fullscreen i {
      display: block;
      line-height: 1;
    }

    /* Animasi Radar Wave untuk Tombol Power Aktif */
    .pulse-active {
      position: relative;
      z-index: 1;
    }

    .pulse-active::before {
      content: "";
      position: absolute;
      width: 100%;
      height: 100%;
      border-radius: 50%;
      background: #10b981;
      /* Warna hijau success */
      opacity: 0.15;
      z-index: -1;
      animation: radar-wave 2s linear infinite;
    }

    .pulse-active::after {
      content: "";
      position: absolute;
      width: 100%;
      height: 100%;
      border-radius: 50%;
      background: #10b981;
      opacity: 0.15;
      z-index: -1;
      animation: radar-wave 2s linear infinite;
      animation-delay: 1s;
    }

    @keyframes radar-wave {
      0% {
        transform: scale(1);
        opacity: 0.15;
      }

      100% {
        transform: scale(2);
        opacity: 0;
      }
    }

    .spin {
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      from {
        transform: rotate(0deg);
      }

      to {
        transform: rotate(360deg);
      }
    }
  </style>
@endsection

@section('content')
  <div class="page-header-section d-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="page-title fw-medium fs-20 mb-0">Perjalanan</h1>
      <p class="">Kelola perjalanan penggunaan alat GPS di kendaraan</p>
    </div>
    <div class="ms-auto">
      <a href="{{ route('perjalanan.index') }}" class="btn btn-light btn-wave">
        <i class="ri-arrow-left-line me-2"></i>Kembali
      </a>
    </div>
  </div>

  <div class="row mb-4">
    <div class="col-xl-8 col-lg-7">
      <div class="detail-card h-100 mb-0">
        <div class="card-header py-3">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Informasi Perjalanan</h5>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="detail-group mb-3 d-flex flex-column">
                <span class="detail-label mb-1">Nama Perjalanan</span>
                <span class="detail-value fw-bold text-dark">{{ $sesiAlat->nama ?? '-' }}</span>
              </div>
              <div class="detail-group mb-3 d-flex flex-column">
                <span class="detail-label mb-1">Kendaraan</span>
                <span class="detail-value">
                  <i class="ri-truck-line me-1 text-primary"></i>
                  {{ $sesiAlat->kendaraan?->nama ?? '-' }} ({{ $sesiAlat->kendaraan?->plat ?? '-' }})
                </span>
              </div>
              <div class="detail-group mb-3 d-flex flex-column">
                <span class="detail-label mb-1">Perusahaan Pemilik</span>
                <span class="detail-value">
                  <i class="ri-building-line me-1 text-info"></i>
                  {{ $sesiAlat->perusahaan?->nama ?? '-' }}
                </span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="detail-group mb-3 d-flex flex-column">
                <span class="detail-label mb-1">Serial Number (SN)</span>
                <span class="detail-value">
                  <span class="badge bg-primary-transparent text-primary">
                    <i class="ri-cpu-line me-1"></i>{{ $sesiAlat->alat?->id ?? '-' }}
                  </span>
                </span>
              </div>
              <div class="detail-group mb-3 d-flex flex-column">
                <label class="detail-label mb-1">Waktu Pembaruan Terakhir</label>
                <span class="detail-value text-muted local-date">
                  {{ $sesiAlat->updated_at ? $sesiAlat->updated_at->toIso8601String() : '-' }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-4 col-lg-5">
      <div class="detail-card h-100 mb-0 d-flex flex-column">
        <div class="card-header py-3">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Status & Kontrol Perjalanan</h5>
          </div>
        </div>
        <div class="d-flex align-items-center justify-content-center py-4 flex-grow-1">
          @php
            $lastRiwayat = $riwayatPerjalanan->sortByDesc('created_at')->first();
            $batLevel = (int) ($lastRiwayat?->battery ?? 0);
          @endphp

          <div class="d-flex w-100 align-items-stretch">
            {{-- KOLOM BATERAI --}}
            <div class="flex-fill d-flex flex-column align-items-center justify-content-center border-end w-50">
              <span class="detail-label mb-3 d-block text-center text-uppercase text-nowrap">Baterai Alat</span>
              <div class="my-auto d-flex flex-column align-items-center justify-content-start" style="height: 120px;">
                @if ($lastRiwayat && $lastRiwayat->battery !== null)
                  <x-battery-circle :level="$batLevel" size="85px" />
                @else
                  <div class="text-muted small">Tidak ada data</div>
                @endif
              </div>
            </div>

            {{-- KOLOM KONTROL --}}
            <div class="flex-fill d-flex flex-column align-items-center justify-content-center w-50">
              <label class="detail-label mb-3 d-block text-uppercase text-center text-nowrap">Kontrol Daya</label>
              <div class="my-auto d-flex flex-column align-items-center justify-content-start" style="height: 120px;">
                <form id="toggle-power-form" action="{{ route('perjalanan.toggle', $sesiAlat->id) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <button type="submit" id="toggle-power-btn"
                    class="btn btn-icon btn-lg rounded-circle shadow-lg {{ $sesiAlat->aktif ? 'btn-success pulse-active' : 'btn-danger' }}"
                    style="width: 80px; height: 80px;"
                    title="{{ $sesiAlat->aktif ? 'Selesaikan Perjalanan' : 'Aktifkan Perjalanan' }}">
                    <i id="toggle-power-icon" class="ri-shut-down-line fs-40"></i>
                  </button>
                </form>
                <span id="power-status-badge"
                  class="badge rounded-pill mt-3 {{ $sesiAlat->aktif ? 'bg-success-transparent text-success' : 'bg-danger-transparent text-danger' }} px-3">
                  {{ $sesiAlat->aktif ? 'AKTIF' : 'NONAKTIFKAN' }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row my-4">
    <div class="col-12">
      <div class="detail-card">
        <div class="card-header">
          <h5><i class="ri-map-pin-line me-2"></i>Perjalanan</h5>
        </div>
        <div class="card-body">
          <div id="map-container"></div>

          <div class="table-responsive" id="table-wrapper">
            <table class="table table-hover" id="history-table">
              <thead class="bg-light">
                <tr>
                  <th style="font-size: 12px; font-weight: 600; color: #666;">No</th>
                  <th style="font-size: 12px; font-weight: 600; color: #666;">Waktu</th>
                  <th style="font-size: 12px; font-weight: 600; color: #666;">Latitude</th>
                  <th style="font-size: 12px; font-weight: 600; color: #666;">Longitude</th>
                  <th style="font-size: 12px; font-weight: 600; color: #666;">Ketinggian</th>
                  <th style="font-size: 12px; font-weight: 600; color: #666;">Satelit</th>
                  <th style="font-size: 12px; font-weight: 600; color: #666;">Kecepatan</th>
                  <th style="font-size: 12px; font-weight: 600; color: #666;">Battery</th>
                </tr>
              </thead>
              <tbody id="history-table-body">
                <!-- Data akan dimuat via JavaScript -->
              </tbody>
            </table>
          </div>

          <!-- Sentinel untuk Infinite Scroll -->
          <div id="scroll-sentinel" style="height: 50px; margin-top: 10px;"
            class="d-flex align-items-center justify-content-center">
            <div class="spinner-border spinner-border-sm text-primary" role="status" id="loading-spinner"
              style="display: none;">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    window.SESI_ALAT_ID = "{{ $sesiAlat->id }}";

    // Konversi Data PHP ke JS
    const rawRiwayatData = @json($riwayatPerjalanan);
    let currentIndex = 0;
    const itemsPerPage = 100;
    let isLoading = false;

    // --- VARIABEL GLOBAL ---
    let map = null;
    let polyline = null;
    let startMarker = null;
    let truckMarker = null;
    let selectedMarker = null;
    let liveLatLngs = [];

    // Tracking Mode
    let isTrackingMode = false;
    let isAutoPan = true;
    let adminTrackingBtn = null; // Store button reference

    // --- 3. HELPER: PARSE TANGGAL AMAN ---
    const parseDateSafe = (input) => {
      if (!input) return new Date();
      if (input instanceof Date) return input;
      let safeString = input.toString().replace(' ', 'T');
      if (!safeString.endsWith('Z') && !safeString.includes('+')) safeString += 'Z';
      let d = new Date(safeString);
      return isNaN(d.getTime()) ? new Date() : d;
    };

    // --- 4. FORMATTER DATE ---
    const formatDateJS = (d) => {
      const dateObj = parseDateSafe(d);
      const day = String(dateObj.getDate()).padStart(2, '0');
      const month = String(dateObj.getMonth() + 1).padStart(2, '0');
      const year = dateObj.getFullYear();
      const hours = String(dateObj.getHours()).padStart(2, '0');
      const minutes = String(dateObj.getMinutes()).padStart(2, '0');
      const seconds = String(dateObj.getSeconds()).padStart(2, '0');
      const offset = -dateObj.getTimezoneOffset();
      let zona = offset === 420 ? 'WIB' : (offset === 480 ? 'WITA' : (offset === 540 ? 'WIT' : ''));
      return `${day}-${month}-${year} ${hours}:${minutes}:${seconds} ${zona}`;
    };

    const getBatteryHtml = (level, size = '32px', fontSize = '8px', maskSize = '3px') => {
      const levelInt = parseInt(level || 0);
      let color = '#be1e2d';
      if (levelInt > 70) color = '#10b981';
      else if (levelInt > 30) color = '#f59e0b';

      return `<div class="battery-circle-container d-flex align-items-center justify-content-center">
                <div style="position: relative; width: ${size}; height: ${size}; border-radius: 50%; background: #f0f0f0; display: flex; align-items: center; justify-content: center; background: radial-gradient(closest-side, white calc(100% - ${maskSize}), transparent calc(100% - ${maskSize} + 0.5px)), conic-gradient(${color} ${levelInt}%, #e9ecef 0);">
                  <span style="font-size: ${fontSize}; font-weight: 700; color: ${color}; z-index: 1;">${levelInt}%</span>
                </div>
              </div>`;
    };

    const getRowHtml = (item, index) => {
      const dateStr = formatDateJS(item.created_at);

      return `
            <tr class="clickable-row" data-lat="${item.lat}" data-lng="${item.long}" 
                data-time="${item.created_at}" data-speed="${item.speed || 0}">
                <td style="font-size: 12px; font-weight: 600;">
                    <span class="rounded-circle py-1 px-2 bg-primary text-white number-badge">
                        ${rawRiwayatData.length - index}
                    </span>
                </td>
                <td style="font-size: 12px;"><i class="ri-time-line me-1"></i>${dateStr}</td>
                <td style="font-size: 12px;">${item.lat}</td>
                <td style="font-size: 12px;">${item.long}</td>
                <td style="font-size: 12px;">${item.alt ? item.alt + ' m' : '-'}</td>
                <td style="font-size: 12px;">${item.sat ? '<span class="badge bg-primary">' + item.sat + '</span>' : '-'}</td>
                <td style="font-size: 12px;">${item.speed ? '<span style="color: #10b981; font-weight: 600;">' + item.speed + ' km/h</span>' : '-'}</td>
                <td style="font-size: 12px;">
                    ${getBatteryHtml(item.battery, '32px', '8px', '3px')}
                </td>
            </tr>
        `;
    };

    const loadMoreData = () => {
      if (isLoading || currentIndex >= rawRiwayatData.length) return;
      isLoading = true;

      const spinner = document.getElementById('loading-spinner');
      if (spinner) spinner.style.display = 'block';

      const tbody = document.getElementById('history-table-body');
      const limit = Math.min(currentIndex + itemsPerPage, rawRiwayatData.length);

      let chunkHtml = '';
      for (let i = currentIndex; i < limit; i++) {
        chunkHtml += getRowHtml(rawRiwayatData[i], i);
      }

      tbody.insertAdjacentHTML('beforeend', chunkHtml);
      currentIndex = limit;

      if (spinner) spinner.style.display = 'none';
      isLoading = false;

      const sentinel = document.getElementById('scroll-sentinel');
      if (sentinel) {
        const rect = sentinel.getBoundingClientRect();
        if (currentIndex < rawRiwayatData.length && rect.top < window.innerHeight + 400) {
          setTimeout(loadMoreData, 50);
        } else if (currentIndex >= rawRiwayatData.length) {
          sentinel.style.display = 'none';
        }
      }
    };

    // --- 5. CUSTOM ICON MAP ---
    function createCustomIcon(type) {
      let iconClass = type === 'start' ? 'ri-map-pin-line' : 'ri-map-pin-user-line';
      let pulseHtml = type === 'end' ? '<div class="pin-pulse"></div>' : '';
      return L.divIcon({
        html: `<div class="pin-wrapper">${pulseHtml}<i class="${iconClass}"></i></div>`,
        className: 'custom-pin-icon',
        iconSize: [50, 50],
        iconAnchor: [25, 45]
      });
    }

    document.addEventListener('DOMContentLoaded', function() {
      // A. INIT MAP
      map = L.map('map-container', {
        attributionControl: false,
        gestureHandling: true
      }).setView([-2.5489, 118.0149], 4);
      L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '© OpenStreetMap, © CARTO',
        maxZoom: 18
      }).addTo(map);

      // --- CUSTOM CONTROL: TRACKING MODE ---
      const TrackingControl = L.Control.extend({
        options: {
          position: 'bottomright'
        },
        onAdd: function(map) {
          const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
          // Important: Use 'a' tag for consistency with Leaflet controls
          adminTrackingBtn = L.DomUtil.create('a', 'leaflet-control-tracking', container);
          adminTrackingBtn.href = '#';
          adminTrackingBtn.innerHTML = '<i class="ri-focus-3-line" style="font-size: 20px;"></i>'; // Icon only
          adminTrackingBtn.title = 'Tracking Mode';

          L.DomEvent.on(adminTrackingBtn, 'click', function(e) {
            L.DomEvent.stop(e);
            toggleTrackingMode(adminTrackingBtn);
          });
          return container;
        }
      });
      map.addControl(new TrackingControl());

      // --- CUSTOM CONTROL: FULLSCREEN ---
      const FullscreenControl = L.Control.extend({
        options: {
          position: 'bottomright'
        },
        onAdd: function(map) {
          const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
          const btn = L.DomUtil.create('a', 'leaflet-control-fullscreen', container);
          btn.href = '#';
          btn.innerHTML = '<i class="ri-fullscreen-line" style="font-size: 20px;"></i>';
          btn.title = 'Fullscreen';

          L.DomEvent.on(btn, 'click', function(e) {
            L.DomEvent.stop(e);
            const mapEl = document.getElementById('map-container');
            if (!document.fullscreenElement) {
              mapEl.requestFullscreen().catch(err => {
                console.error(`Error attempting to enable full-screen mode: ${err.message}`);
              });
            } else {
              document.exitFullscreen();
            }
          });
          return container;
        }
      });
      map.addControl(new FullscreenControl());

      // Update button icon on fullscreen change
      document.addEventListener('fullscreenchange', () => {
        const btn = document.querySelector('.leaflet-control-fullscreen');
        if (document.fullscreenElement) {
          btn.innerHTML = '<i class="ri-fullscreen-exit-line" style="font-size: 20px;"></i>';
          setTimeout(() => map.invalidateSize(), 100);
        } else {
          btn.innerHTML = '<i class="ri-fullscreen-line" style="font-size: 20px;"></i>';
          setTimeout(() => map.invalidateSize(), 100);
        }
      });

      function toggleTrackingMode(btn) {
        isTrackingMode = !isTrackingMode;
        if (isTrackingMode) {
          btn.classList.add('is-tracking');
          isAutoPan = true;
          if (truckMarker) {
            map.setView(truckMarker.getLatLng(), 18); // Zoom dekat
          }
        } else {
          btn.classList.remove('is-tracking');
        }
      }

      map.on('click', () => {
        if (!isAutoPan) {
          isAutoPan = true;
          if (selectedMarker) {
            map.removeLayer(selectedMarker);
            selectedMarker = null;
          }
          document.querySelectorAll('.table-active-row').forEach(r => r.classList.remove('table-active-row'));
          if (truckMarker) {
            map.panTo(truckMarker.getLatLng());
            truckMarker.openPopup();
          }
        }
      });

      // Disable tracking mode on drag if desired? No, user says "selalu mengikuti".
      // But if user drags, they fight the auto-pan.
      map.on('dragstart', () => {
        if (isTrackingMode) {
          isTrackingMode = false;
          if (adminTrackingBtn) adminTrackingBtn.classList.remove('is-tracking');
        }
      });

      // B. INIT DATA TABEL & MAP
      if (rawRiwayatData.length > 0) {
        loadMoreData();

        const observer = new IntersectionObserver((entries) => {
          if (entries[0].isIntersecting && !isLoading) {
            loadMoreData();
          }
        }, {
          rootMargin: '400px',
          threshold: 0.01
        });

        const sentinel = document.getElementById('scroll-sentinel');
        if (sentinel) observer.observe(sentinel);

        rawRiwayatData.forEach(item => {
          const lat = parseFloat(item.lat);
          const lng = parseFloat(item.long);
          if (!isNaN(lat) && !isNaN(lng)) liveLatLngs.push([lat, lng]);
        });

        const pathLatLngs = [...liveLatLngs].reverse();
        if (pathLatLngs.length > 0) {
          startMarker = L.marker(pathLatLngs[0], {
            icon: createCustomIcon('start')
          }).addTo(map).bindPopup("Titik Awal");
          truckMarker = L.marker(pathLatLngs[pathLatLngs.length - 1], {
            icon: createCustomIcon('end'),
            zIndexOffset: 1000
          }).addTo(map).bindPopup("Lokasi Terkini");
          polyline = L.polyline(pathLatLngs, {
            color: '#0F2854', // My Blue
            weight: 4,
            dashArray: '5, 5'
          }).addTo(map);
          map.fitBounds(polyline.getBounds(), {
            padding: [50, 50],
            maxZoom: 18
          });
        }
      } else {
        const tbody = document.getElementById('history-table-body');
        if (tbody) {
          tbody.innerHTML = `
                <tr id="empty-row">
                    <td colspan="8">
                        <div style="text-align: center; padding: 40px 20px; color: #999;">
                            <i class="ri-map-pin-2-line" style="font-size: 48px; margin-bottom: 16px; display: block; opacity: 0.5;"></i>
                            <p style="margin: 0;">Belum ada data riwayat perjalanan</p>
                        </div>
                    </td>
                </tr>`;
        }
        const sentinel = document.getElementById('scroll-sentinel');
        if (sentinel) sentinel.style.display = 'none';
      }

      // C. KLIK TABEL
      document.querySelector('#history-table').addEventListener('click', (e) => {
        const row = e.target.closest('tr.clickable-row');
        if (row) {
          isAutoPan = false; // Disable auto pan when reviewing history
          // Turn off Tracking Mode if active to allow review
          if (isTrackingMode) {
            isTrackingMode = false;
            if (adminTrackingBtn) adminTrackingBtn.classList.remove('is-tracking');
          }

          const lat = parseFloat(row.dataset.lat);
          const lng = parseFloat(row.dataset.lng);
          const timeRaw = row.dataset.time;
          const timeDisplay = formatDateJS(timeRaw);
          const speed = row.dataset.speed;

          document.querySelectorAll('.table-active-row').forEach(r => r.classList.remove('table-active-row'));
          row.classList.add('table-active-row');

          if (selectedMarker) map.removeLayer(selectedMarker);
          selectedMarker = L.circleMarker([lat, lng], {
            radius: 8,
            fillColor: "#0ea5e9",
            color: "#fff",
            weight: 2,
            fillOpacity: 1
          }).addTo(map);
          selectedMarker.bindPopup(
            `<div class="text-center"><strong>Riwayat</strong><br>${timeDisplay}<br><span style="color: #10b981;">${speed} km/h</span></div>`
          ).openPopup();
          map.panTo([lat, lng]);

          selectedMarker.on('popupclose', function() {
            if (selectedMarker) {
              map.removeLayer(selectedMarker);
              selectedMarker = null;
            }
            document.querySelectorAll('.table-active-row').forEach(r => r.classList.remove(
              'table-active-row'));
          });

          // SCROLL KE PETA (Dengan offset navbar)
          const mapEl = document.getElementById('map-container');
          const navbarHeight = 80; // Estimasi tinggi navbar + margin
          const elementPosition = mapEl.getBoundingClientRect().top;
          const offsetPosition = elementPosition + window.pageYOffset - navbarHeight;

          window.scrollTo({
            top: offsetPosition,
            behavior: "smooth"
          });
        }
      });

      // D. REALTIME UPDATE
      if (window.Echo) {
        window.Echo.channel(`tracking.sesi.${window.SESI_ALAT_ID}`)
          .listen('.LokasiBaru', (e) => {
            // Map event data structure to match DB structure expected by getRowHtml
            const mappedItem = {
              lat: e.lat,
              long: e.lng,
              created_at: e.waktu,
              speed: e.speed,
              alt: e.alt,
              sat: e.sat,
              battery: e.battery
            };

            const latLng = [parseFloat(e.lat), parseFloat(e.lng)];

            // Add to raw data (unshift to keep order consistent with table logic)
            rawRiwayatData.unshift(mappedItem);
            currentIndex++;

            // Update Map
            if (!polyline) {
              // First point
              startMarker = L.marker(latLng, {
                icon: createCustomIcon('start')
              }).addTo(map);
              truckMarker = L.marker(latLng, {
                icon: createCustomIcon('end')
              }).addTo(map);
              polyline = L.polyline([latLng], {
                color: '#0F2854', // My Blue
                weight: 4
              }).addTo(map);
            } else {
              // Subsequent points
              if (truckMarker) truckMarker.setLatLng(latLng);
              if (polyline) polyline.addLatLng(latLng);
            }
            liveLatLngs.push(latLng);

            // Handle Panning
            if (isTrackingMode) {
              map.setView(latLng, 18); // Force Zoom 18 and Center
            } else if (isAutoPan) {
              map.panTo(latLng); // Normal auto pan
            }

            // Update Table
            const tbody = document.getElementById('history-table-body');
            const emptyRow = document.getElementById('empty-row');
            if (emptyRow) emptyRow.remove();

            // Use mappedItem here
            const rowHtml = getRowHtml(mappedItem, 0);
            tbody.insertAdjacentHTML('afterbegin', rowHtml);
          });
      }

      setTimeout(() => {
        map.invalidateSize();
      }, 500);

      // E. AJAX POWER TOGGLE DENGAN KONFIRMASI MODAL
      const powerForm = document.getElementById('toggle-power-form');
      const powerBtn = document.getElementById('toggle-power-btn');
      const powerIcon = document.getElementById('toggle-power-icon');
      const statusBadge = document.getElementById('power-status-badge');

      if (powerForm) {
        powerForm.addEventListener('submit', function(e) {
          e.preventDefault();

          const originalIconClass = powerIcon.className;
          const url = powerForm.action;
          const token = document.querySelector('input[name="_token"]').value;

          const isActive = powerBtn.getAttribute('data-is-active') === '1';
          const confirmTitle = isActive ? 'Selesaikan Perjalanan?' : 'Aktifkan Perjalanan?';
          const confirmText = isActive ?
            'Perjalanan ini akan diselesaikan dan alat akan dinonaktifkan.' :
            'Perjalanan ini akan diaktifkan dan alat akan mulai melacak.';

          window.showConfirmModal(confirmText, () => {
            // Start Loading: Change icon to spinner (no text)
            powerIcon.className = 'ri-loader-4-line fs-40 spin';
            powerBtn.disabled = true;

            fetch(url, {
                method: 'POST',
                headers: {
                  'X-CSRF-TOKEN': token,
                  'Content-Type': 'application/json',
                  'Accept': 'application/json'
                },
                body: JSON.stringify({
                  _method: 'PUT'
                })
              })
              .then(response => response.json())
              .then(data => {
                if (data.success) {
                  const isNowActive = data.aktif;
                  powerBtn.setAttribute('data-is-active', isNowActive ? '1' : '0');

                  // Update Button
                  powerBtn.className =
                    `btn btn-icon btn-lg rounded-circle shadow-lg ${isNowActive ? 'btn-success pulse-active' : 'btn-danger'}`;
                  powerBtn.title = isNowActive ? 'Selesaikan Perjalanan' : 'Aktifkan Perjalanan';

                  // Update Icon
                  powerIcon.className = 'ri-shut-down-line fs-40';

                  // Update Badge
                  statusBadge.className =
                    `badge rounded-pill mt-3 ${isNowActive ? 'bg-success-transparent text-success' : 'bg-danger-transparent text-danger'} px-3`;
                  statusBadge.textContent = isNowActive ? 'AKTIF' : 'NONAKTIFKAN';

                  if (window.showAlertModal) {
                    window.showAlertModal(data.message || 'Status berhasil diubah', 'success');
                  } else if (window.Swal) {
                    Swal.fire('Berhasil', data.message || 'Status berhasil diubah', 'success');
                  }
                } else {
                  // Rollback on failure
                  powerIcon.className = originalIconClass;
                  if (window.showAlertModal) {
                    window.showAlertModal(data.message || 'Gagal mengubah status', 'error');
                  } else if (window.Swal) {
                    Swal.fire('Gagal', data.message || 'Gagal mengubah status', 'error');
                  }
                }
              })
              .catch(error => {
                console.error('Error toggling power:', error);
                powerIcon.className = originalIconClass;
                if (window.showAlertModal) {
                  window.showAlertModal('Terjadi kesalahan sistem', 'error');
                } else if (window.Swal) {
                  Swal.fire('Error', 'Kesalahan sistem', 'error');
                }
              })
              .finally(() => {
                powerBtn.disabled = false;
              });
          });
        });
      }

      formatAllLocalDates();
    });

    const formatAllLocalDates = () => {
      document.querySelectorAll('.local-date').forEach(el => {
        const raw = el.textContent.trim();
        if (raw && raw !== '-') {
          el.textContent = formatDateJS(raw);
        }
      });
    };
  </script>
@endsection
