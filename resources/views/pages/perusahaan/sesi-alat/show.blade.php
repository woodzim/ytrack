@extends('layouts.master')

@section('styles')
  <style>
    /* --- Styling Map Icon --- */
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
      /* My Blue */
      filter: drop-shadow(0 3px 2px rgba(0, 0, 0, 0.3));
      z-index: 2;
    }

    .pin-pulse {
      position: absolute;
      width: 100%;
      height: 100%;
      background: rgba(15, 40, 84, 0.4);
      /* My Blue Transparent */
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

    .clickable-row {
      cursor: pointer;
      transition: background-color 0.2s ease;
    }

    .table-active-row {
      background-color: #e0f2fe !important;
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

    /* Tracking Mode Button Customization */
    .leaflet-control-tracking {
      /* Inherits from .leaflet-bar a, but explicit overrides just in case */
      width: 34px !important;
      height: 34px !important;
      display: flex !important;
      align-items: center;
      justify-content: center;
      background: white;
      border: 1px solid #e2e8ee;
      border-radius: 6px !important;
      cursor: pointer;
      color: #5d6576;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      transition: all 0.2s ease;
      text-decoration: none !important;
      font-size: 18px;
      /* Icon size */
    }

    .leaflet-control-tracking:hover {
      background: #f9fafb;
      color: #0F2854;
      border-color: #0F2854;
    }

    .leaflet-control-tracking.is-tracking {
      background: #0F2854 !important;
      color: white !important;
      border-color: #0F2854 !important;
      box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06);
    }

    .leaflet-control-tracking.is-tracking i {
      color: white !important;
    }

    /* Fullscreen style */
    #map-container:fullscreen {
      width: 100vw !important;
      height: 100vh !important;
      border-radius: 0 !important;
    }

    /* Ensure icon inside is visible */
    .leaflet-control-tracking i,
    .leaflet-control-fullscreen i {
      display: block;
      line-height: 1;
    }

    .pulse-active {
      position: relative;
      z-index: 1;
    }

    .pulse-active::before,
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
    }

    .pulse-active::after {
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
  <div class="page-header-section d-flex align-items-center justify-content-between">
    <div>
      <h1>Detail Perjalanan</h1>
      <p>Informasi lengkap perjalanan GPS tracking</p>
    </div>
    <div>
      <a href="{{ route('perusahaan.perjalanan.index') }}" class="btn btn-light btn-wave">
        <i class="ri-arrow-left-line me-2"></i>Kembali
      </a>
    </div>
  </div>

  <div class="row mb-4">
    <div class="col-xl-8 col-lg-7">
      <div class="detail-card h-100 mb-0">
        <div class="card-header">
          <h5>Informasi Perjalanan</h5>
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
                <span id="last-update-time" class="detail-value text-muted">
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
        <div class="card-header">
          <h5>Status & Kontrol Perjalanan</h5>
        </div>
        <div class="card-body d-flex flex-column align-items-center justify-content-center py-4 flex-grow-1">
          @php
            $lastRiwayat = $riwayatPerjalanan->sortByDesc('created_at')->first();
            $batLevel = (int) ($lastRiwayat?->battery ?? 0);
          @endphp
          <div class="d-flex w-100 flex-column flex-md-row align-items-stretch flex-grow-1 gap-3 gap-md-0">
            <div
              class="flex-fill d-flex flex-column align-items-center justify-content-center border-bottom border-md-bottom-0 border-md-end pb-3 pb-md-0 w-100 w-md-50">
              <span class="detail-label mb-3 d-block text-center text-uppercase text-nowrap">Baterai Alat</span>
              <div class="my-auto d-flex flex-column align-items-center justify-content-start" style="height: 120px;">
                @if ($lastRiwayat && $lastRiwayat->battery !== null)
                  <x-battery-circle :level="$batLevel" size="85px" />
                @else
                  <div class="text-muted small">Tidak ada data</div>
                @endif
              </div>
            </div>
            <div
              class="flex-fill d-flex flex-column align-items-center justify-content-center pt-3 pt-md-0 w-100 w-md-50">
              <label class="detail-label mb-3 d-block text-uppercase text-center text-nowrap">Kontrol Daya</label>
              <div class="my-auto d-flex flex-column align-items-center justify-content-start" style="height: 120px;">
                <form id="toggle-power-form" action="{{ route('perusahaan.perjalanan.toggle', $sesiAlat->id) }}"
                  method="POST">
                  @csrf
                  @method('PUT')
                  <button type="submit" id="toggle-power-btn"
                    class="btn btn-icon btn-lg rounded-circle shadow-lg {{ $sesiAlat->aktif ? 'btn-success pulse-active' : 'btn-danger' }}"
                    style="width: 85px; height: 85px;"
                    title="{{ $sesiAlat->aktif ? 'Selesaikan Perjalanan' : 'Aktifkan Perjalanan' }}"
                    data-is-active="{{ $sesiAlat->aktif ? '1' : '0' }}">
                    <i id="toggle-power-icon" class="ri-shut-down-line fs-40"></i>
                  </button>
                </form>
                <span id="power-status-badge"
                  class="badge rounded-pill mt-3 {{ $sesiAlat->aktif ? 'bg-success-transparent text-success' : 'bg-danger-transparent text-danger' }} px-3">
                  {{ $sesiAlat->aktif ? 'AKTIF' : 'NONAKTIF' }}
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

          {{-- Desktop View: Table --}}
          <div class="table-responsive d-none d-md-block">
            <table class="table table-hover table-sm align-middle" id="history-table">
              <thead class="bg-light text-uppercase fs-10 fw-bold text-muted">
                <tr>
                  <th class="py-2">No</th>
                  <th class="py-2">Waktu</th>
                  <th class="py-2">Latitude</th>
                  <th class="py-2">Longitude</th>
                  <th class="py-2">Ketinggian</th>
                  <th class="py-2">Satelit</th>
                  <th class="py-2">Kecepatan</th>
                  <th class="py-2">Battery</th>
                </tr>
              </thead>
              <tbody id="history-table-body"></tbody>
            </table>
          </div>

          {{-- Mobile View: Cards --}}
          <div class="d-block d-md-none mt-3">
            <div class="list-group list-group-flush rounded-bottom" id="history-cards-container">
              <!-- Cards will be dynamically injected here by JS -->
            </div>
          </div>
          {{-- End Mobile View --}}

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
    const rawRiwayatData = @json($riwayatPerjalanan);
    let currentIndex = 0;
    const itemsPerPage = 100;
    let isLoading = false;

    let map = null,
      polyline = null,
      startMarker = null,
      truckMarker = null,
      selectedMarker = null;
    let liveLatLngs = [];

    // Tracking Mode
    let isTrackingMode = false;
    let isAutoPan = true;
    let perusahaanTrackingBtn = null; // Store button reference

    const parseDateSafe = (input) => {
      if (!input) return new Date();
      if (input instanceof Date) return input;
      let s = input.toString().replace(' ', 'T');
      if (!s.endsWith('Z') && !s.includes('+')) s += 'Z';
      let d = new Date(s);
      return isNaN(d.getTime()) ? new Date() : d;
    };

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
                <div style="position: relative; width: ${size}; height: ${size}; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: radial-gradient(closest-side, white calc(100% - ${maskSize}), transparent calc(100% - ${maskSize} + 0.5px)), conic-gradient(${color} ${levelInt}%, #e9ecef 0);">
                  <span style="font-size: ${fontSize}; font-weight: 700; color: ${color}; z-index: 1;">${levelInt}%</span>
                </div>
              </div>`;
    };

    const getRowHtml = (item, index) => {
      const dateStr = formatDateJS(item.created_at);
      const no = rawRiwayatData.length - index;
      const batteryHtml = getBatteryHtml(item.battery);
      const batteryHtmlSmall = getBatteryHtml(item.battery, '28px', '7px', '2.5px');

      // Return object with separate desktop and mobile HTML to avoid breaking table DOM
      return {
        desktop: `
              <tr class="clickable-row desktop-row" data-lat="${item.lat}" data-lng="${item.long}" data-time="${item.created_at}" data-speed="${item.speed || 0}">
                <td class="py-2"><span class="rounded-circle py-1 px-2 bg-primary text-white number-badge" style="font-size:0.75rem;">${no}</span></td>
                <td class="py-2"><i class="ri-time-line me-1"></i>${dateStr}</td>
                <td class="py-2">${item.lat}</td><td class="py-2">${item.long}</td><td class="py-2">${item.alt || '-'} m</td>
                <td class="py-2">${item.sat ? '<span class="badge bg-primary px-2 py-1">' + item.sat + '</span>' : '-'}</td>
                <td class="py-2"><span style="color: #10b981; font-weight: 600;">${item.speed || 0} km/h</span></td>
                <td class="py-2">${batteryHtml}</td>
              </tr>
            `,
        mobile: `
              <div class="list-group-item p-3 clickable-card mobile-card" data-lat="${item.lat}" data-lng="${item.long}" data-time="${item.created_at}" data-speed="${item.speed || 0}" style="cursor: pointer; border-bottom: 1px solid #eee;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <div class="d-flex align-items-center gap-2">
                    <span class="rounded-circle py-1 px-2 bg-primary text-white" style="font-size: 0.7rem; font-weight:bold;">${no}</span>
                    <div class="fw-bold text-dark" style="font-size: 0.85rem;"><i class="ri-time-line me-1 text-muted"></i>${dateStr}</div>
                  </div>
                  <div>
                    ${batteryHtmlSmall}
                  </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                  <div class="d-flex flex-column gap-1">
                    <div class="text-muted d-flex gap-2" style="font-size: 0.75rem;">
                      <span><i class="ri-map-pin-line me-1"></i>Lat: ${item.lat}</span>
                      <span>Lng: ${item.long}</span>
                    </div>
                    <div class="text-muted" style="font-size: 0.75rem;">
                      <i class="ri-base-station-line me-1"></i>Sat: ${item.sat || '-'} | Alt: ${item.alt || '-'}m
                    </div>
                  </div>
                  <div class="text-end">
                    <span class="badge bg-success-transparent text-success px-2 py-1" style="font-size: 0.75rem;">${item.speed || 0} km/h</span>
                  </div>
                </div>
              </div>
            `
      };
    };

    const loadMoreData = () => {
      if (isLoading || currentIndex >= rawRiwayatData.length) return;
      isLoading = true;

      const spinner = document.getElementById('loading-spinner');
      if (spinner) spinner.style.display = 'block';

      const tbody = document.getElementById('history-table-body');
      const mobileContainer = document.getElementById('history-cards-container');
      const limit = Math.min(currentIndex + itemsPerPage, rawRiwayatData.length);

      let desktopHtml = '';
      let mobileHtml = '';

      for (let i = currentIndex; i < limit; i++) {
        const htmlObj = getRowHtml(rawRiwayatData[i], i);
        desktopHtml += htmlObj.desktop;
        mobileHtml += htmlObj.mobile;
      }

      if (tbody) tbody.insertAdjacentHTML('beforeend', desktopHtml);
      if (mobileContainer) mobileContainer.insertAdjacentHTML('beforeend', mobileHtml);

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

    function createCustomIcon(type) {
      let iconClass = type === 'start' ? 'ri-map-pin-4-line' : 'ri-map-pin-user-fill';
      let iconColor = type === 'start' ? '#0F2854' : '#0F2854';
      let pulseHtml = type === 'end' ? '<div class="pin-pulse"></div>' : '';
      return L.divIcon({
        html: `<div class="pin-wrapper">${pulseHtml}<i class="${iconClass}" style="color: ${iconColor};"></i></div>`,
        className: 'custom-pin-icon',
        iconSize: [50, 50],
        iconAnchor: [25, 45]
      });
    }

    document.addEventListener('DOMContentLoaded', function() {
      // Format last update time to local
      const lastUpdateEl = document.getElementById('last-update-time');
      if (lastUpdateEl && lastUpdateEl.innerText !== '-') {
        lastUpdateEl.innerText = formatDateJS(lastUpdateEl.innerText);
      }

      map = L.map('map-container', {
        attributionControl: false,
        gestureHandling: true
      }).setView([-2.5489, 118.0149], 4);
      L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png').addTo(map);

      // --- CUSTOM CONTROL: TRACKING MODE ---
      const TrackingControl = L.Control.extend({
        options: {
          position: 'bottomright'
        },
        onAdd: function(map) {
          const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
          perusahaanTrackingBtn = L.DomUtil.create('a', 'leaflet-control-tracking', container);
          perusahaanTrackingBtn.href = '#';
          perusahaanTrackingBtn.innerHTML =
            '<i class="ri-focus-3-line" style="font-size: 20px;"></i>'; // Icon only
          perusahaanTrackingBtn.title = 'Tracking Mode';

          L.DomEvent.on(perusahaanTrackingBtn, 'click', function(e) {
            L.DomEvent.stop(e);
            toggleTrackingMode(perusahaanTrackingBtn);
          });
          return container;
        }
      });
      map.addControl(new TrackingControl());

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

      // --- CUSTOM CONTROL: LEGEND ---
      const LegendControl = L.Control.extend({
        options: {
          position: 'bottomleft'
        },
        onAdd: function(map) {
          const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control custom-map-legend');
          container.style =
            "background: white; padding: 12px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06); margin-bottom: 20px; margin-left: 10px;";
          container.innerHTML = `
            <div style="font-size: 13px; font-weight: 600; color: #1f2937; margin-bottom: 8px; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px;">Legenda</div>
            <div class="d-flex align-items-center mb-2">
              <i class="ri-map-pin-4-line" style="color: #0F2854; font-size: 20px; width: 24px; text-align: center; margin-right: 6px;"></i>
              <span style="font-size: 12px; color: #4b5563;">Titik Awal</span>
            </div>
            <div class="d-flex align-items-center mb-2">
              <div style="position: relative; width: 24px; height: 24px; text-align: center; margin-right: 6px; display: flex; align-items: center; justify-content: center;">
                <div style="position: absolute; width:100%; height:100%; background:rgba(15,40,84,0.3); border-radius:50%; animation: pulse-animation 2s infinite; transform: scaleX(1) scaleY(0.5); bottom: 1px; z-index: 1;"></div>
                <i class="ri-map-pin-user-fill" style="color: #0F2854; font-size: 20px; position: relative; z-index: 2;"></i>
              </div>
              <span style="font-size: 12px; color: #4b5563;">Lokasi Terkini</span>
            </div>
            <div class="d-flex align-items-center mt-3">
              <div style="width: 12px; height: 12px; border-radius: 50%; background: #0ea5e9; border: 2px solid white; box-shadow: 0 0 0 1px #0ea5e9; margin: 0 6px 0 6px; margin-right: 12px;"></div>
              <span style="font-size: 12px; color: #4b5563;">Posisi Riwayat (klik tabel)</span>
            </div>
          `;
          return container;
        }
      });
      map.addControl(new LegendControl());

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

      // Disable tracking mode on drag
      map.on('dragstart', () => {
        if (isTrackingMode) {
          isTrackingMode = false;
          if (perusahaanTrackingBtn) perusahaanTrackingBtn.classList.remove('is-tracking');
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
          }).addTo(map);
          truckMarker = L.marker(pathLatLngs[pathLatLngs.length - 1], {
            icon: createCustomIcon('end'),
            zIndexOffset: 1000
          }).addTo(map);
          polyline = L.polyline(pathLatLngs, {
            color: '#0F2854', // My Blue
            weight: 4,
            dashArray: '5, 5'
          }).addTo(map);
          map.fitBounds(polyline.getBounds(), {
            padding: [50, 50]
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

      // Klik Tabel Interaction
      document.querySelector('#history-table').addEventListener('click', (e) => {
        const row = e.target.closest('tr.clickable-row');
        if (row) {
          isAutoPan = false;
          if (isTrackingMode) {
            isTrackingMode = false;
            if (perusahaanTrackingBtn) perusahaanTrackingBtn.classList.remove('is-tracking');
          }

          const lat = parseFloat(target.dataset.lat);
          const lng = parseFloat(target.dataset.lng);
          const timeRaw = target.dataset.time;
          const timeDisplay = formatDateJS(timeRaw);
          const speed = target.dataset.speed;

          document.querySelectorAll('.table-active-row, .bg-light').forEach(r => r.classList.remove(
            'table-active-row', 'bg-light'));
          target.classList.add(target.classList.contains('clickable-row') ? 'table-active-row' : 'bg-light');

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

          const mapEl = document.getElementById('map-container');
          const navbarHeight = 80;
          const elementPosition = mapEl.getBoundingClientRect().top;
          const offsetPosition = elementPosition + window.pageYOffset - navbarHeight;
          window.scrollTo({
            top: offsetPosition,
            behavior: "smooth"
          });
        }
      });

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
            rawRiwayatData.unshift(mappedItem);
            currentIndex++;

            if (!polyline) {
              truckMarker = L.marker(latLng, {
                icon: createCustomIcon('end')
              }).addTo(map);
              polyline = L.polyline([latLng], {
                color: '#0F2854', // My Blue
                weight: 4
              }).addTo(map);
            } else {
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

            document.getElementById('history-table-body').insertAdjacentHTML('afterbegin', getRowHtml(mappedItem,
              0));
          });
      }

      // AJAX Power Toggle dengan Konfirmasi Modal
      const powerForm = document.getElementById('toggle-power-form');
      if (powerForm) {
        powerForm.addEventListener('submit', function(e) {
          e.preventDefault();
          const btn = document.getElementById('toggle-power-btn');
          const icon = document.getElementById('toggle-power-icon');
          const badge = document.getElementById('power-status-badge');
          const originalClass = icon.className;

          const isActive = btn.getAttribute('data-is-active') === '1';
          const confirmTitle = isActive ? 'Selesaikan Perjalanan?' : 'Aktifkan Perjalanan?';
          const confirmText = isActive ?
            'Perjalanan ini akan diselesaikan dan alat akan dinonaktifkan.' :
            'Perjalanan ini akan diaktifkan dan alat akan mulai melacak.';
          const confirmBtnText = "Ya";
          const confirmBtnClass = "btn-primary";

          window.showConfirmModal(confirmText, () => {
            icon.className = 'ri-loader-4-line fs-40 spin';
            btn.disabled = true;

            fetch(powerForm.action, {
                method: 'POST',
                headers: {
                  'X-CSRF-TOKEN': '{{ csrf_token() }}',
                  'Content-Type': 'application/json',
                  'Accept': 'application/json'
                },
                body: JSON.stringify({
                  _method: 'PUT'
                })
              })
              .then(r => r.json())
              .then(data => {
                if (data.success) {
                  // Update UI without reload
                  btn.setAttribute('data-is-active', data.aktif ? '1' : '0');

                  if (data.aktif) {
                    btn.className = 'btn btn-icon btn-lg rounded-circle shadow-lg btn-success pulse-active';
                    badge.className = 'badge rounded-pill mt-3 bg-success-transparent text-success px-3';
                    badge.innerText = 'AKTIF';
                    btn.title = 'Selesaikan Perjalanan';
                  } else {
                    btn.className = 'btn btn-icon btn-lg rounded-circle shadow-lg btn-danger';
                    badge.className = 'badge rounded-pill mt-3 bg-danger-transparent text-danger px-3';
                    badge.innerText = 'NONAKTIFKAN';
                    btn.title = 'Aktifkan Perjalanan';
                  }
                  icon.className = 'ri-shut-down-line fs-40';
                  btn.disabled = false;

                  // Removed success popup
                } else {
                  icon.className = originalClass;
                  btn.disabled = false;
                  window.showAlertModal(data.message || 'Gagal mengubah status', 'error');
                }
              })
              .catch(error => {
                console.error('Error toggle power:', error);
                icon.className = originalClass;
                btn.disabled = false;
                window.showAlertModal('Terjadi kesalahan jaringan.', 'error');
              });
          }, confirmTitle, confirmBtnText, confirmBtnClass);
        });
      }

      setTimeout(() => map.invalidateSize(), 500);
    });
  </script>
@endsection
