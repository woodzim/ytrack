@extends('layouts.master')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
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
      color: #be1e2d;
      /* Merah */
      filter: drop-shadow(0 3px 2px rgba(0, 0, 0, 0.3));
      z-index: 2;
    }

    /* Animasi Pulse */
    .pin-pulse {
      position: absolute;
      width: 100%;
      height: 100%;
      background: rgba(190, 30, 45, 0.4);
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

      /* --- KUNCI PERBAIKAN --- */
      position: relative;
      /* Pastikan posisi relative */
      z-index: 1;
      /* Angka kecil (0 atau 1) agar kalah tinggi dari Navbar (biasanya 1000+) */
    }

    /* OPSI TAMBAHAN: Jika tombol zoom (+ -) leaflet masih menembus navbar */
    .leaflet-top,
    .leaflet-bottom {
      z-index: 400 !important;
      /* Default leaflet kadang 1000, kita turunkan */
    }
  </style>
@endsection

@section('content')
  <div class="page-header-section d-flex align-items-center justify-content-between">
    <div>
      <div>
        <h1>Detail Sesi Alat</h1>
        <p>Informasi lengkap sesi GPS tracking</p>
      </div>
      <div>
        <span class="status-badge-large mt-3"
          style="background-color: {{ $sesiAlat->aktif ? '#10b981' : '#6b7280' }}; color: white;">
          <i class="ri-{{ $sesiAlat->aktif ? 'check-double' : 'close' }}-line me-1"></i>
          {{ $sesiAlat->aktif ? 'AKTIF' : 'NONAKTIF' }}
        </span>
      </div>
    </div>
    <div>
      <a href="{{ route('perusahaan.sesi-alat.index') }}" class="btn btn-light btn-wave">
        <i class="ri-arrow-left-line me-2"></i>Kembali
      </a>
    </div>
  </div>

  <div class="row mb-12">
    <div class="col-12">
      <div class="detail-card">
        <div class="card-header">
          <h5>Informasi Sesi</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="detail-group">
                <label class="detail-label">ID Sesi</label>
                <span class="detail-value">{{ $sesiAlat->id }}</span>
              </div>
              <div class="detail-group">
                <label class="detail-label">Nama Sesi</label>
                <span class="detail-value">{{ $sesiAlat->nama ?? '-' }}</span>
              </div>
              <div class="detail-group">
                <label class="detail-label">Kendaraan</label>
                <span class="detail-value">
                  {{ $sesiAlat->kendaraan?->nama ?? '-' }} ({{ $sesiAlat->kendaraan?->plat ?? '-' }})
                </span>
              </div>
              <div class="detail-group">
                <label class="detail-label">Status Baterai Alat</label>
                @php
                  $lastRiwayat = $riwayatPerjalanan->sortByDesc('created_at')->first();
                  $batLevel = (int) ($lastRiwayat?->battery ?? 0);
                  $batColor = 'bg-danger';
                  if ($batLevel > 70) {
                      $batColor = 'bg-success';
                  } elseif ($batLevel >= 30) {
                      $batColor = 'bg-warning';
                  }
                @endphp
                <div class="detail-value">
                  @if($lastRiwayat && $lastRiwayat->battery !== null)
                    <div class="d-flex align-items-center" style="max-width: 200px;">
                      <div class="progress flex-grow-1" style="height: 8px; background-color: #e9ecef;">
                        <div class="progress-bar {{ $batColor }}" role="progressbar"
                          style="width: {{ $batLevel }}%" aria-valuenow="{{ $batLevel }}"
                          aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <span class="ms-2" style="font-size: 13px; font-weight: 700;">{{ $batLevel }}%</span>
                    </div>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="detail-group">
                <label class="detail-label">Serial Number (SN)</label>
                <span class="detail-value badge bg-primary text-light">
                  {{ $sesiAlat->alat?->id ?? '-' }}
                </span>
              </div>
              <div class="detail-group">
                <label class="detail-label">Status Alat</label>
                <span class="detail-value text-muted">
                  {{ $sesiAlat->aktif ? 'Sedang Transmisi Data' : 'Idle / Offline' }}
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
          <h5><i class="ri-map-pin-line me-2"></i>Riwayat Perjalanan</h5>
        </div>
        <div class="card-body">
          <div id="map-container" style="height: 400px; width: 100%; border-radius: 8px; margin-bottom: 20px;"></div>

          <div class="table-responsive" id="table-wrapper">
            <table class="table table-sm table-hover" id="history-table">
              <thead>
                <tr style="background-color: #f8f9fa;">
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
              <tbody>
                @php $totalData = $riwayatPerjalanan->count(); @endphp

                @if ($totalData > 0)
                  @foreach ($riwayatPerjalanan->sortByDesc('created_at') as $riwayat)
                    <tr class="clickable-row" data-lat="{{ $riwayat->lat }}" data-lng="{{ $riwayat->long }}"
                      data-time="{{ $riwayat->created_at }}" {{-- Simpan Raw Date --}}
                      data-speed="{{ $riwayat->speed ?? 0 }}">

                      <td style="font-size: 12px; font-weight: 600;">
                        <span class="rounded-circle py-1 px-2 bg-primary text-white number-badge">
                          {{ $totalData - $loop->index }}
                        </span>
                      </td>

                      <td style="font-size: 12px;">
                        <i class="ri-time-line me-1"></i>
                        {{ $riwayat->created_at?->translatedFormat('d-m-Y H:i:s') ?? '-' }}
                      </td>
                      <td style="font-size: 12px;">{{ $riwayat->lat }}</td>
                      <td style="font-size: 12px;">{{ $riwayat->long }}</td>
                      <td style="font-size: 12px;">{{ $riwayat->alt ? $riwayat->alt . ' m' : '-' }}</td>
                      <td style="font-size: 12px;">
                        {!! $riwayat->sat ? '<span class="badge bg-primary">' . $riwayat->sat . '</span>' : '-' !!}
                      </td>
                      <td style="font-size: 12px;">
                        {!! $riwayat->speed
                            ? '<span style="color: #10b981; font-weight: 600;">' . $riwayat->speed . ' km/h</span>'
                            : '-' !!}
                      </td>
                      <td style="font-size: 12px;">
                        @php
                          $batLevel = (int) ($riwayat->battery ?? 0);
                          $batColor = 'bg-danger';
                          if ($batLevel > 70) {
                              $batColor = 'bg-success';
                          } elseif ($batLevel >= 30) {
                              $batColor = 'bg-warning';
                          }
                        @endphp
                        <div class="d-flex align-items-center" style="min-width: 100px;">
                          <div class="progress flex-grow-1" style="height: 6px; background-color: #e9ecef;">
                            <div class="progress-bar {{ $batColor }}" role="progressbar" style="width: {{ $batLevel }}%"
                              aria-valuenow="{{ $batLevel }}" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                          <span class="ms-2" style="font-size: 11px; font-weight: 600;">{{ $batLevel }}%</span>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                @else
                  <tr id="empty-row">
                    <td colspan="8">
                      <div style="text-align: center; padding: 40px 20px; color: #999;">
                        <i class="ri-map-pin-2-line"
                          style="font-size: 48px; margin-bottom: 16px; display: block; opacity: 0.5;"></i>
                        <p style="margin: 0;">Belum ada data riwayat perjalanan</p>
                      </div>
                    </td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  {{-- 1. LOAD LIBRARY --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

  <script>
    // --- 2. CHECK ECHO ---
    if (typeof window.Echo === 'undefined') {
      console.warn('Echo is not defined. Realtime updates might not work.');
    }

    window.SESI_ALAT_ID = {{ $sesiAlat->id }};

    // --- VARIABEL GLOBAL ---
    let map = null;
    let polyline = null;
    let startMarker = null;
    let truckMarker = null;
    let selectedMarker = null;
    let liveLatLngs = [];
    let isAutoPan = true;

    // --- 3. HELPER: PARSE TANGGAL AMAN ---
    const parseDateSafe = (input) => {
      if (!input) return new Date();
      if (input instanceof Date) return input;

      // Bersihkan string dan pastikan format ISO
      let safeString = input.toString().replace(' ', 'T');

      // Tambahkan 'Z' (UTC) jika belum ada, supaya dikonversi ke lokal
      if (!safeString.endsWith('Z') && !safeString.includes('+')) {
        safeString += 'Z';
      }

      let d = new Date(safeString);
      return isNaN(d.getTime()) ? new Date() : d;
    };

    // --- 4. FORMATTER DATE (Deteksi Zona Waktu Otomatis) ---
    // Output: 12-01-2026 14:45:06 WIB
    const formatDateJS = (d) => {
      const dateObj = parseDateSafe(d);

      // A. Ambil Komponen Waktu
      const day = String(dateObj.getDate()).padStart(2, '0');
      const month = String(dateObj.getMonth() + 1).padStart(2, '0');
      const year = dateObj.getFullYear();
      const hours = String(dateObj.getHours()).padStart(2, '0');
      const minutes = String(dateObj.getMinutes()).padStart(2, '0');
      const seconds = String(dateObj.getSeconds()).padStart(2, '0');

      // B. Deteksi Zona Waktu User (Browser)
      // getTimezoneOffset() mengembalikan selisih menit dari UTC.
      // Jakarta (UTC+7) = -420 menit. Bali (UTC+8) = -480 menit.
      const offset = -dateObj.getTimezoneOffset();
      let zona = '';

      if (offset === 420) {
        zona = 'WIB';
      } else if (offset === 480) {
        zona = 'WITA';
      } else if (offset === 540) {
        zona = 'WIT';
      } else {
        // Jika dibuka di luar negeri (opsional)
        const jamOffset = offset / 60;
        const tanda = jamOffset >= 0 ? '+' : '';
        zona = `(UTC${tanda}${jamOffset})`;
      }

      return `${day}-${month}-${year} ${hours}:${minutes}:${seconds} ${zona}`;
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
      map = L.map('map-container').setView([-2.5489, 118.0149], 5);
      L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '© OpenStreetMap, © CARTO',
        maxZoom: 20
      }).addTo(map);

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

      // B. LOAD DATA HISTORY & FORMAT ULANG TABEL
      const rows = document.querySelectorAll('#history-table tbody tr.clickable-row');

      rows.forEach(row => {
        // Ambil lat/lng
        const lat = parseFloat(row.dataset.lat);
        const lng = parseFloat(row.dataset.lng);
        if (!isNaN(lat) && !isNaN(lng)) liveLatLngs.push([lat, lng]);

        // FORMAT ULANG WAKTU HISTORY
        const rawTime = row.dataset.time;
        if (rawTime) {
          const formattedTime = formatDateJS(rawTime);
          // Update kolom ke-2 (Index 1)
          if (row.cells[1]) {
            row.cells[1].innerHTML = `<i class="ri-time-line me-1"></i>${formattedTime}`;
          }
        }
      });

      // Render Jalur
      liveLatLngs.reverse();
      if (liveLatLngs.length > 0) {
        startMarker = L.marker(liveLatLngs[0], {
          icon: createCustomIcon('start')
        }).addTo(map).bindPopup("Titik Awal");
        truckMarker = L.marker(liveLatLngs[liveLatLngs.length - 1], {
          icon: createCustomIcon('end'),
          zIndexOffset: 1000
        }).addTo(map).bindPopup("Lokasi Terkini");
        polyline = L.polyline(liveLatLngs, {
          color: '#be1e2d',
          weight: 4,
          dashArray: '5, 5'
        }).addTo(map);
        map.fitBounds(polyline.getBounds(), {
          padding: [50, 50]
        });
      }

      // C. KLIK TABEL
      document.querySelector('#history-table').addEventListener('click', (e) => {
        const row = e.target.closest('tr.clickable-row');
        if (row) {
          isAutoPan = false;
          const lat = parseFloat(row.dataset.lat);
          const lng = parseFloat(row.dataset.lng);
          const timeRaw = row.dataset.time;
          const timeDisplay = formatDateJS(timeRaw); // Format ulang di popup juga
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

          selectedMarker.bindPopup(`
                <div class="text-center">
                    <strong>Riwayat</strong><br>${timeDisplay}<br>
                    <span style="color: #10b981;">${speed} km/h</span>
                </div>
            `).openPopup();
          map.panTo([lat, lng]);

          selectedMarker.on('popupclose', function() {
            if (selectedMarker) {
              map.removeLayer(selectedMarker);
              selectedMarker = null;
            }
            document.querySelectorAll('.table-active-row').forEach(r => r.classList.remove(
              'table-active-row'));
          });
        }
      });

      // D. REALTIME UPDATE
      window.Echo.channel(`tracking.sesi.${window.SESI_ALAT_ID}`)
        .listen('.LokasiBaru', (e) => {
          console.log("Realtime:", e);
          const latLng = [parseFloat(e.lat), parseFloat(e.lng)];

          if (liveLatLngs.length === 0) {
            startMarker = L.marker(latLng, {
              icon: createCustomIcon('start')
            }).addTo(map);
            truckMarker = L.marker(latLng, {
              icon: createCustomIcon('end')
            }).addTo(map);
            polyline = L.polyline([latLng], {
              color: '#be1e2d',
              weight: 4
            }).addTo(map);
          } else {
            if (truckMarker) truckMarker.setLatLng(latLng);
            if (polyline) polyline.addLatLng(latLng);
          }
          liveLatLngs.push(latLng);

          if (isAutoPan) map.panTo(latLng);

          // Update Tabel
          const tbody = document.querySelector('#history-table tbody');
          const emptyRow = document.getElementById('empty-row');
          if (emptyRow) emptyRow.remove();

          const newIndex = tbody.querySelectorAll('tr.clickable-row').length + 1;

          const rawDate = e.created_at ? e.created_at : new Date();
          const dateStr = formatDateJS(rawDate); // SUDAH ADA WIB/WITA

          const rowHtml = `
            <tr class="clickable-row" data-lat="${e.lat}" data-lng="${e.lng}" data-time="${rawDate}" data-speed="${e.speed}">
              <td style="font-size: 12px; font-weight: 600;">
                  <span class="rounded-circle py-1 px-2 bg-primary text-white number-badge">${newIndex}</span>
              </td>
              <td style="font-size: 12px;"><i class="ri-time-line me-1"></i>${dateStr}</td>
              <td style="font-size: 12px;">${e.lat}</td>
              <td style="font-size: 12px;">${e.lng}</td>
              <td style="font-size: 12px;">${e.alt ? e.alt+' m' : '-'}</td>
              <td style="font-size: 12px;">${e.sat ? '<span class="badge bg-primary">'+e.sat+'</span>' : '-'}</td>
              <td style="font-size: 12px;">${e.speed ? '<span style="color: #10b981; font-weight: 600;">'+e.speed+' km/h</span>' : '-'}</td>
              <td style="font-size: 12px;">
                ${(() => {
                    let bat = parseInt(e.battery || 0);
                    let color = 'bg-danger';
                    if(bat > 70) color = 'bg-success';
                    else if(bat >= 30) color = 'bg-warning';
                    return `
                    <div class="d-flex align-items-center" style="min-width: 100px;">
                        <div class="progress flex-grow-1" style="height: 6px; background-color: #e9ecef;">
                          <div class="progress-bar ${color}" role="progressbar" style="width: ${bat}%"></div>
                        </div>
                        <span class="ms-2" style="font-size: 11px; font-weight: 600;">${bat}%</span>
                    </div>`;
                })()}
              </td>
            </tr>
          `;

          tbody.insertAdjacentHTML('afterbegin', rowHtml);
        });

      // Fix map rendering issues (cut off)
      setTimeout(function() {
        map.invalidateSize();
      }, 500);
    });
  </script>
@endsection
