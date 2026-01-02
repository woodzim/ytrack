@extends('layouts.master')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
  <style>
    /* Styling Dasar Icon */
    .custom-pin-icon {
      background: transparent;
      border: none;
    }

    .pin-wrapper {
      position: relative;
      width: 40px;
      height: 40px;
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
              <div class="detail-group">
                <label class="detail-label">Alat</label>
                <span class="detail-value badge bg-primary text-light">{{ $sesiAlat->alat?->kode_alat }}</span>
              </div>
              <div class="detail-group">
                <label class="detail-label">ID Alat</label>
                <span class="detail-value text-muted">
                  #{{ $sesiAlat->alat?->id ?? '-' }}
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
          <div class="table-responsive">
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
                    <tr>
                      <td style="font-size: 12px; font-weight: 600; text-align: center;">
                        <span
                          style="background-color: #be1e2d; color: white; padding: 4px 8px; border: 2px solid white; box-shadow:rgba(255, 255, 255, 0.2); border-radius: 100%; display: inline-block; min-width: 24px;">{{ $loop->iteration }}</span>
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

  <script>
    window.SESI_ALAT_ID = {{ $sesiAlat->id }};

    let map = null;
    let polyline = null;
    let startMarker = null; // Marker Titik Awal (Statis)
    let endMarker = null; // Marker Titik Akhir (Bergerak/Live)
    let liveLatLngs = []; // Array Path

    // --- Helper: Format Tanggal ---
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

    // --- Helper: Create Icon (PERBAIKAN LOGIKA DISINI) ---
    function createCustomIcon(type) {
      let iconClass = '';
      let wrapperClass = 'pin-wrapper';
      let pulseHtml = '';

      if (type === 'start') {
        iconClass = 'ri-map-pin-line';
      } else {
        iconClass = 'ri-map-pin-user-line'; // Atau ri-car-line / ri-truck-line
        pulseHtml = '<div class="pin-pulse"></div>';
      }

      return L.divIcon({
        html: `
                <div class="${wrapperClass}">
                    ${pulseHtml}
                    <i class="${iconClass}"></i>
                </div>
            `,
        className: 'custom-pin-icon',
        iconSize: [40, 40],
        iconAnchor: [20, 36],
        popupAnchor: [0, -36]
      });
    }

    document.addEventListener('DOMContentLoaded', function() {
      const mapContainer = document.getElementById('map-container');
      if (!mapContainer) return;

      // 1. Init Map
      map = L.map('map-container', {
        maxZoom: 19,
        minZoom: 4
      });
      L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '© OpenStreetMap contributors, © CARTO',
        maxZoom: 19
      }).addTo(map);

      // 2. Load Data Awal dari Tabel
      const rows = document.querySelectorAll('.table tbody tr:not(#empty-row)');

      rows.forEach((row) => {
        const cells = row.querySelectorAll('td');
        if (cells.length >= 4) {
          const lat = parseFloat(cells[2].textContent.trim());
          const lng = parseFloat(cells[3].textContent.trim());
          if (!isNaN(lat) && !isNaN(lng)) {
            liveLatLngs.unshift([lat, lng]);
          }
        }
      });

      // Render Map Objects
      if (liveLatLngs.length > 0) {
        // A. Draw Polyline
        polyline = L.polyline(liveLatLngs, {
          color: '#be1e2d',
          weight: 2,
          opacity: 0.8,
          dashArray: '5, 5'
        }).addTo(map);

        // B. Set Start Marker (Titik Pertama - Index 0 - Data Terlama)
        const startLatLng = liveLatLngs[0];
        startMarker = L.marker(startLatLng, {
            icon: createCustomIcon('start')
          })
          .bindPopup('<strong>Titik Awal</strong>')
          .addTo(map);

        // C. Set End Marker (Titik Terakhir - Index Terakhir - Data Terbaru)
        const endLatLng = liveLatLngs[liveLatLngs.length - 1];
        endMarker = L.marker(endLatLng, {
            icon: createCustomIcon('end')
          })
          .bindPopup('<strong>Lokasi Terkini</strong>')
          .addTo(map);

        // Fit Bounds
        map.fitBounds(L.latLngBounds(liveLatLngs), {
          padding: [80, 80]
        });
      } else {
        map.setView([-0.7893, 113.9213], 5); // Default Indonesia
      }

      // 3. Realtime Listener
      if (typeof Echo !== 'undefined') {
        Echo.channel(`tracking.sesi.${window.SESI_ALAT_ID}`)
          .listen('RiwayatPerjalananCreated', (e) => {

            const latLng = [e.lat, e.lng];
            const formattedDate = formatDateJS(e.created_at || new Date());

            const emptyRow = document.getElementById('empty-row');
            if (emptyRow) emptyRow.remove();

            const tbody = document.querySelector('.table tbody');
            const newIndex = tbody.querySelectorAll('tr').length + 1;

            const altDisplay = e.alt ? `${e.alt} m` : '-';
            const satDisplay = e.sat ? `<span class="badge bg-primary">${e.sat}</span>` : '-';
            const speedDisplay = e.speed ?
              `<span style="color: #10b981; font-weight: 600;">${e.speed} km/h</span>` : '-';

            const newRowHTML = `
                    <tr>
                        <td style="font-size: 12px; font-weight: 600; text-align: center;">
                            <span style="background-color: #be1e2d; color: white; padding: 4px 8px; border: 2px solid white; border-radius: 100%; display: inline-block; min-width: 24px;">${newIndex}</span>
                        </td>
                        <td style="font-size: 12px;"><i class="ri-time-line me-1"></i>${formattedDate}</td>
                        <td style="font-size: 12px;">${e.lat}</td>
                        <td style="font-size: 12px;">${e.lng}</td>
                        <td style="font-size: 12px;">${altDisplay}</td>
                        <td style="font-size: 12px;">${satDisplay}</td>
                        <td style="font-size: 12px;">${speedDisplay}</td>
                    </tr>
                `;

            // Catatan: Jika tabel menampilkan data terbaru di ATAS, gunakan 'afterbegin'.
            // Jika data terbaru di BAWAH, gunakan 'beforeend'.
            // Asumsi kode anda sebelumnya 'beforeend'.
            tbody.insertAdjacentHTML('beforeend', newRowHTML);

            // --- 2. Update Map Markers & Polyline ---

            // Data realtime SELALU data terbaru, jadi masukkan ke paling belakang array
            liveLatLngs.push(latLng);

            // Update Garis
            if (polyline) {
              polyline.setLatLngs(liveLatLngs);
            } else {
              polyline = L.polyline(liveLatLngs, {
                color: '#be1e2d',
                weight: 3,
                opacity: 0.8,
                dashArray: '5, 5'
              }).addTo(map);
            }

            // --- LOGIKA UTAMA PERGERAKAN ---

            if (!startMarker) {
              startMarker = L.marker(latLng, {
                  icon: createCustomIcon('start')
                })
                .bindPopup('<strong>Titik Awal</strong>')
                .addTo(map);
            }

            if (endMarker) {
              endMarker.setLatLng(latLng);
            } else {
              endMarker = L.marker(latLng, {
                  icon: createCustomIcon('end')
                })
                .bindPopup('<strong>Lokasi Terkini</strong>')
                .addTo(map);
            }

            map.panTo(latLng, {
              animate: true,
              duration: 0.5
            });
          });
      }
    });
  </script>
@endsection
