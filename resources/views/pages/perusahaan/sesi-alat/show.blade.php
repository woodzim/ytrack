@extends('layouts.master')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
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
      color: #be1e2d;
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

    /* Warna highlight saat baris dipilih */
    .table-active-row {
      background-color: #e0f2fe !important;
      /* Biru muda */
      border-left: 4px solid #0ea5e9;
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
            </div>
            <div class="col-md-6">
              {{-- BAGIAN INI DIUPDATE: Menggunakan ID (Serial Number) --}}
              <div class="detail-group">
                <label class="detail-label">Serial Number (SN)</label>
                <span class="detail-value badge bg-primary text-light">
                  {{ $sesiAlat->alat?->id ?? '-' }}
                </span>
              </div>

              {{-- Field tambahan untuk Tipe (Opsional, jika ada kolom tipe di tabel alat) --}}
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
                </tr>
              </thead>
              <tbody>
                @if ($riwayatPerjalanan->count() > 0)
                  @foreach ($riwayatPerjalanan as $riwayat)
                    <tr class="clickable-row" data-lat="{{ $riwayat->lat }}" data-lng="{{ $riwayat->long }}"
                      data-time="{{ $riwayat->created_at?->translatedFormat('H:i:s') }}"
                      data-speed="{{ $riwayat->speed ?? 0 }}">

                      <td style="font-size: 12px; font-weight: 600;">
                        <span class="rounded-circle py-1 px-2 bg-primary text-white">{{ $loop->iteration }}</span>
                      </td>
                      <td style="font-size: 12px;">
                        <i
                          class="ri-time-line me-1"></i>{{ $riwayat->created_at?->translatedFormat('d-m-Y H:i:s') ?? '-' }}
                      </td>
                      <td style="font-size: 12px;">{{ $riwayat->lat }}</td>
                      <td style="font-size: 12px;">{{ $riwayat->long }}</td>
                      <td style="font-size: 12px;">{{ $riwayat->alt ? $riwayat->alt . ' m' : '-' }}</td>
                      <td style="font-size: 12px;">
                        @if ($riwayat->sat)
                          <span class="badge bg-primary">{{ $riwayat->sat }}</span>
                        @else
                          -
                        @endif
                      </td>
                      <td style="font-size: 12px;">
                        @if ($riwayat->speed)
                          <span style="color: #10b981; font-weight: 600;">{{ $riwayat->speed }} km/h</span>
                        @else
                          -
                        @endif
                      </td>
                    </tr>
                  @endforeach
                @else
                  <tr id="empty-row">
                    <td colspan="7">
                      <div style="text-align: center; padding: 40px 20px; color: #999;">
                        <i class="ri-map-pin-2-line"
                          style="font-size: 48px; margin-bottom: 16px; display: block; opacity: 0.5;"></i>
                        <p style="margin: 0;">Belum ada data riwayat perjalanan</p>
                        <small>Data GPS tracking akan muncul di sini secara realtime</small>
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
  {{-- 1. LOAD LIBRARY (URUTAN PENTING: Leaflet -> Pusher -> Echo) --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
  <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>

  <script>
    // --- 2. KONFIGURASI ECHO & PUSHER (Manual Config) ---
    window.Pusher = Pusher;
    window.Echo = new Echo({
      broadcaster: 'pusher',
      key: '{{ config('broadcasting.connections.pusher.key') }}',
      cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
      forceTLS: true
    });
  </script>

  <script>
    window.SESI_ALAT_ID = {{ $sesiAlat->id }};

    // --- VARIABEL GLOBAL ---
    let map = null;
    let polyline = null;
    let startMarker = null; // Marker Titik Awal
    let endMarker = null; // Marker Truk (Bergerak)
    let selectedMarker = null; // Marker History (Biru)
    let liveLatLngs = []; // Array Jalur

    // LOGIKA AUTO FOLLOW
    let isAutoPan = true; // Default: Ikuti truk

    // --- Helper Format ---
    function formatDateJS(dateString) {
      if (!dateString) return '-';
      const date = new Date(dateString);
      return date.toLocaleString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
      }).replace(/\./g, ':');
    }

    function formatTimeJS(dateString) {
      if (!dateString) return '-';
      const date = new Date(dateString);
      return `${String(date.getHours()).padStart(2,'0')}:${String(date.getMinutes()).padStart(2,'0')}:${String(date.getSeconds()).padStart(2,'0')}`;
    }

    // --- Custom Icons (Sesuai Style Anda) ---
    function createCustomIcon(type) {
      let iconClass = '';
      let wrapperClass = 'pin-wrapper';
      let pulseHtml = '';

      if (type === 'start') {
        iconClass = 'ri-map-pin-line';
      } else {
        iconClass = 'ri-map-pin-user-line';
        pulseHtml = '<div class="pin-pulse"></div>';
      }

      return L.divIcon({
        html: `<div class="${wrapperClass}">${pulseHtml}<i class="${iconClass}"></i></div>`,
        className: 'custom-pin-icon',
        iconSize: [40, 40],
        iconAnchor: [25, 36]
      });
    }

    document.addEventListener('DOMContentLoaded', function() {
      const mapContainer = document.getElementById('map-container');
      if (!mapContainer) return;

      // 1. Init Map
      map = L.map('map-container', {
        maxZoom: 20,
        minZoom: 1
      });
      L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '© OpenStreetMap contributors, © CARTO',
        maxZoom: 20
      }).addTo(map);

      // --- LOGIKA RESUME TRACKING ---
      // Jika klik area kosong di peta, kembali ikuti truk
      map.on('click', function() {
        if (!isAutoPan) {
          isAutoPan = true;
          // Hapus seleksi history
          if (selectedMarker) {
            map.removeLayer(selectedMarker);
            selectedMarker = null;
          }
          document.querySelectorAll('#history-table tbody tr').forEach(r => r.classList.remove(
            'table-active-row'));

          // Kembali ke truk
          if (endMarker) {
            map.panTo(endMarker.getLatLng());
            endMarker.openPopup();
          }
          console.log("🔄 Resume Auto Tracking");
        }
      });

      // 2. Load Data Existing dari Tabel
      const rows = document.querySelectorAll('#history-table tbody tr:not(#empty-row)');
      // Karena tabel biasanya urut DESC (terbaru diatas), kita loop dan masukkan ke depan array
      // Agar array urut dari Awal -> Akhir
      let tempCoords = [];
      rows.forEach((row) => {
        const lat = parseFloat(row.getAttribute('data-lat'));
        const lng = parseFloat(row.getAttribute('data-lng'));
        if (!isNaN(lat) && !isNaN(lng)) {
          tempCoords.unshift([lat, lng]); // Masukkan ke awal array
        }
      });
      liveLatLngs = tempCoords;

      // Render Map Awal
      if (liveLatLngs.length > 0) {
        polyline = L.polyline(liveLatLngs, {
          color: '#be1e2d',
          weight: 4,
          opacity: 0.8,
          dashArray: '5, 5'
        }).addTo(map);

        startMarker = L.marker(liveLatLngs[0], {
            icon: createCustomIcon('start')
          })
          .bindPopup('<strong>Titik Awal</strong>').addTo(map);

        endMarker = L.marker(liveLatLngs[liveLatLngs.length - 1], {
            icon: createCustomIcon('end')
          })
          .bindPopup('<strong>Lokasi Terkini</strong><br>Tracking Aktif').addTo(map);

        map.fitBounds(L.latLngBounds(liveLatLngs), {
          padding: [50, 50]
        });
      } else {
        map.setView([-2.548926, 118.0148634], 5); // Default Indonesia
      }

      // ==========================================
      // EVENT: KLIK BARIS TABEL (STOP AUTO FOLLOW)
      // ==========================================
      const tableWrapper = document.getElementById('table-wrapper');
      tableWrapper.addEventListener('click', function(e) {
        const row = e.target.closest('tr');

        if (row && row.dataset.lat && row.dataset.lng) {
          // STOP FOLLOW
          isAutoPan = false;

          const lat = parseFloat(row.dataset.lat);
          const lng = parseFloat(row.dataset.lng);
          const time = row.dataset.time || '-';
          const speed = row.dataset.speed || '0';

          // Highlight Tabel
          document.querySelectorAll('#history-table tbody tr').forEach(r => r.classList.remove(
            'table-active-row'));
          row.classList.add('table-active-row');

          // Marker Seleksi (Biru)
          if (selectedMarker) map.removeLayer(selectedMarker);

          selectedMarker = L.circleMarker([lat, lng], {
            radius: 8,
            fillColor: "#0ea5e9",
            color: "#fff",
            weight: 2,
            opacity: 1,
            fillOpacity: 0.8
          }).addTo(map);

          selectedMarker.bindPopup(`
                <div style="text-align:center;">
                    <strong>Riwayat</strong><br><small>${time}</small><br>
                    <span style="color:#10b981; font-weight:bold">${speed} km/h</span><br>
                    <em style="font-size:10px; color:#999;">Klik peta untuk kembali ke live</em>
                </div>
            `).openPopup();

          map.panTo([lat, lng], {
            animate: true,
            duration: 1.0
          });
        }
      });

      // ==========================================
      // REALTIME LISTENER
      // ==========================================
      const channelName = `tracking.sesi.${window.SESI_ALAT_ID}`;

      window.Echo.channel(channelName)
        .listen('.LokasiBaru', (e) => { // Pastikan event name pakai titik (.)
          console.log("Data Masuk:", e);

          // Data Mapping (Sesuaikan dengan payload event Anda)
          const lat = parseFloat(e.lat);
          const lng = parseFloat(e.lng);
          const latLng = [lat, lng];

          const formattedDate = formatDateJS(e.created_at || new Date());
          const timeStr = formatTimeJS(e.created_at || new Date());

          // 1. UPDATE PETA (Background Process)
          liveLatLngs.push(latLng);

          if (polyline) polyline.setLatLngs(liveLatLngs);
          else polyline = L.polyline(liveLatLngs, {
            color: '#be1e2d',
            weight: 4,
            opacity: 0.8,
            dashArray: '5, 5'
          }).addTo(map);

          if (!startMarker) startMarker = L.marker(latLng, {
            icon: createCustomIcon('start')
          }).bindPopup('<strong>Titik Awal</strong>').addTo(map);

          if (endMarker) endMarker.setLatLng(latLng);
          else endMarker = L.marker(latLng, {
            icon: createCustomIcon('end')
          }).bindPopup('<strong>Lokasi Terkini</strong>').addTo(map);

          // 2. AUTO PAN LOGIC (Kunci Perubahan Disini)
          if (isAutoPan) {
            map.panTo(latLng);
          }

          // 3. UPDATE TABEL
          const emptyRow = document.getElementById('empty-row');
          if (emptyRow) emptyRow.remove();

          const tbody = document.querySelector('#history-table tbody');
          // Hitung nomor baru (karena append di bawah, nomor lanjut terus)
          const newIndex = tbody.querySelectorAll('tr').length + 1;

          const altDisplay = e.alt ? `${e.alt} m` : '-';
          const satDisplay = e.sat ? `<span class="badge bg-primary">${e.sat}</span>` : '-';
          const speedDisplay = e.speed ? `<span style="color: #10b981; font-weight: 600;">${e.speed} km/h</span>` :
            '-';

          const newRowHTML = `
              <tr class="clickable-row"
                  data-lat="${e.lat}"
                  data-lng="${e.lng}"
                  data-time="${timeStr}"
                  data-speed="${e.speed || 0}">
                <td style="font-size: 12px; font-weight: 600; text-align: center;">
                  <span class="rounded-circle py-1 px-2 bg-primary text-white">${newIndex}</span>
                </td>
                <td style="font-size: 12px;"><i class="ri-time-line me-1"></i>${formattedDate}</td>
                <td style="font-size: 12px;">${e.lat}</td>
                <td style="font-size: 12px;">${e.lng}</td>
                <td style="font-size: 12px;">${altDisplay}</td>
                <td style="font-size: 12px;">${satDisplay}</td>
                <td style="font-size: 12px;">${speedDisplay}</td>
              </tr>
            `;

          // Tambahkan ke bawah
          tbody.insertAdjacentHTML('beforeend', newRowHTML);
        });
    });
  </script>
@endsection
