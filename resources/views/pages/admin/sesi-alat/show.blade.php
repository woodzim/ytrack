@extends('layouts.master')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
  <style>
    /* Wrapper Utama Marker */
    .pin-wrapper {
      position: relative;
      width: 50px;
      height: 50px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* Icon Utama (Mobil/Bendera) */
    .pin-wrapper i {
      position: relative;
      font-size: 32px;
      z-index: 10;
      filter: drop-shadow(0 5px 4px rgba(0, 0, 0, 0.3));
      transition: all 0.3s ease;
    }

    /* Warna Khusus */
    .truck-icon i {
      color: #be1e2d;
    }

    .start-icon i {
      color: #be1e2d;
    }

    /* Animasi Denyut (Pulse) di Bawah */
    .pin-pulse {
      position: absolute;
      width: 40px;
      height: 15px;
      background: rgba(0, 0, 0, 0.2);
      border-radius: 50%;
      bottom: 5px;
      z-index: 1;
      animation: pulse-ring 2s infinite;
    }

    .truck-icon .pin-pulse {
      background: rgba(190, 30, 45, 0.4);
    }

    .start-icon .pin-pulse {
      background: rgba(16, 185, 129, 0.4);
    }

    @keyframes pulse-ring {
      0% {
        transform: scale(0.5);
        opacity: 1;
      }

      100% {
        transform: scale(2.5);
        opacity: 0;
      }
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


    /* --- TAMBAHAN GAYA UNTUK INTERAKSI TABEL --- */
    /* Ubah kursor jadi telunjuk saat hover baris tabel */
    .table-hover tbody tr {
      cursor: pointer;
      transition: background-color 0.2s;
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
  <div class="page-header-section">
    <div>
      <h1>Detail Sesi Alat</h1>
      <p>Informasi lengkap sesi YA-Track</p>
    </div>
    <div>
      <span class="status-badge-large mt-3"
        style="background-color: {{ $sesiAlat->aktif ? '#10b981' : '#6b7280' }}; color: white;">
        <i class="ri-{{ $sesiAlat->aktif ? 'check-double' : 'close' }}-line me-1"></i>
        {{ $sesiAlat->aktif ? 'AKTIF' : 'NONAKTIF' }}
      </span>
    </div>
  </div>
  <div class="row mb-12">
    <div class="col-12">
      <div class="detail-container">
        <div class="detail-card">
          <div class="card-header">
            <h5>Informasi Sesi</h5>
          </div>
          <div class="card-body">
            <div class="detail-group">
              <div class="detail-label" style="margin-bottom: 10px;">ID Sesi</div>
              <div class="detail-value">{{ $sesiAlat->id }}</div>
            </div>

            <div class="detail-group">
              <div class="detail-label" style="margin-bottom: 10px;">ID Alat GPS</div>
              <div class="detail-value">
                <span class="badge-alat">{{ $sesiAlat->alat ? $sesiAlat->alat->id : '-' }}</span>
              </div>
            </div>

            <div class="detail-group">
              <div class="detail-label" style="margin-bottom: 10px;">Kendaraan</div>
              <div class="detail-value">{{ $sesiAlat->kendaraan ? $sesiAlat->kendaraan->nama : '-' }}</div>
            </div>

            <div class="detail-group">
              <div class="detail-label" style="margin-bottom: 10px;">Status Baterai Alat</div>
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

            <div class="detail-group">
              <div class="detail-label" style="margin-bottom: 10px;">Perusahaan</div>
              <div class="detail-value">
                {{ $sesiAlat->perusahaan ? $sesiAlat->perusahaan->nama : '-' }}
              </div>
              @if ($sesiAlat->perusahaan)
                <div class="detail-value secondary">
                  <small class="text-muted">
                    <i class="ri-map-pin-line me-1"></i>{{ $sesiAlat->perusahaan ? $sesiAlat->perusahaan->alamat : '-' }}
                  </small>
                </div>
              @endif
            </div>

            <div class="detail-group">
              <div class="detail-label" style="margin-bottom: 10px;">Timeline</div>
              <div class="timeline">
                <div class="timeline-item">
                  <strong>Dibuat</strong>
                  <span>{{ $sesiAlat->created_at->translatedFormat('d F Y') }}</span>
                  <small class="text-muted">{{ $sesiAlat->created_at->translatedFormat('H:i') }}</small>
                </div>
                <div class="timeline-item">
                  <strong>Diperbarui</strong>
                  <span>{{ $sesiAlat->updated_at->translatedFormat('d F Y') }}</span>
                  <small class="text-muted">{{ $sesiAlat->updated_at->translatedFormat('H:i') }}</small>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="side-card">
          <div class="card-header">
            <h5>Aksi</h5>
          </div>
          <div class="card-body">
            <form action="{{ route('sesi-alat.toggle', $sesiAlat->id) }}" method="POST" style="margin-bottom: 12px;">
              @csrf
              @method('PUT')
              <button type="submit" class="btn-toggle-large {{ $sesiAlat->aktif ? 'active' : 'inactive' }}">
                <i class="ri-{{ $sesiAlat->aktif ? 'pause' : 'play' }}-line me-2"></i>
                {{ $sesiAlat->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
              </button>
            </form>

            <a href="{{ route('sesi-alat.edit', $sesiAlat->id) }}" class="btn btn-primary btn-sm w-100 mb-2">
              <i class="ri-edit-line me-2"></i>Edit
            </a>

            <form action="{{ route('sesi-alat.destroy', $sesiAlat->id) }}" method="POST"
              onsubmit="return confirm('Hapus sesi ini secara permanen? Tindakan ini tidak dapat dibatalkan.');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-primary btn-sm w-100 mb-2">
                <i class="ri-delete-bin-line me-2"></i>Hapus Sesi
              </button>
            </form>

            <a href="{{ route('sesi-alat.index') }}" class="btn btn-outline-secondary btn-sm w-100">
              <i class="ri-arrow-left-line me-2"></i>Kembali
            </a>

            <hr class="my-3">

            <div class="p-3 rounded" style="background-color: #f8f9fa; border: 1px solid #e0e0e0;">
              <div class="mb-3">
                <strong style="font-size: 12px; color: #666;">Status Saat Ini</strong>
                <div style="margin-top: 8px;">
                  <span class="badge"
                    style="background-color: {{ $sesiAlat->aktif ? '#10b981' : '#6b7280' }}; color: white;">
                    {{ $sesiAlat->aktif ? 'Aktif' : 'Nonaktif' }}
                  </span>
                </div>
              </div>
              <div style="font-size: 12px; line-height: 1.6; color: #666;">
                @if ($sesiAlat->aktif)
                  <strong>Sesi aktif.</strong> Alat GPS sedang tracking kendaraan ini.
                @else
                  <strong>Sesi nonaktif.</strong> Alat GPS tidak sedang tracking. Klik tombol <strong>Aktifkan</strong>
                  untuk memulai.
                @endif
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

          <div id="table-wrapper">
            @if ($riwayatPerjalanan->count() > 0)
              <div class="table-responsive">
                <table class="table table-sm table-hover" id="gps-table">
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
                    {{-- 1. Hitung total data dulu sebelum looping --}}
                    @php $totalData = $riwayatPerjalanan->count(); @endphp

                    {{-- 2. Loop data yang sudah dibalik (Terbaru di atas) --}}
                    @foreach ($riwayatPerjalanan->sortByDesc('created_at') as $riwayat)
                      <tr class="clickable-row" data-lat="{{ $riwayat->lat }}" data-lng="{{ $riwayat->long }}"
                        data-time="{{ $riwayat->created_at?->translatedFormat('H:i:s') }}"
                        data-speed="{{ $riwayat->speed ?? 0 }}">

                        <td style="font-size: 12px; font-weight: 600;">
                          <span class="rounded-circle py-1 px-2 bg-primary text-white number-badge">
                            {{-- RUMUS MUNDUR: Total Data dikurangi Index saat ini --}}
                            {{ $totalData - $loop->index }}
                          </span>
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
                              <div class="progress-bar {{ $batColor }}" role="progressbar"
                                style="width: {{ $batLevel }}%" aria-valuenow="{{ $batLevel }}"
                                aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="ms-2" style="font-size: 11px; font-weight: 600;">{{ $batLevel }}%</span>
                          </div>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div id="empty-state" style="text-align: center; padding: 40px 20px; color: #999;">
                <i class="ri-map-pin-2-line"
                  style="font-size: 48px; margin-bottom: 16px; display: block; opacity: 0.5;"></i>
                <p style="margin: 0;">Belum ada data riwayat perjalanan</p>
                <small>Data YA-Track akan muncul di sini</small>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  {{-- 1. LOAD LIBRARY --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
  <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>

  <script>
    // --- 2. CONFIG ECHO ---
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
    let startMarker = null;
    let truckMarker = null;
    let polyline = null;
    let selectedMarker = null;
    let liveLatLngs = [];

    // --- [UPDATE LOGIKA] STATUS AUTO FOLLOW ---
    let isAutoPan = true;

    // --- 3. HELPER DATE (Updated) ---
    const parseDateSafe = (input) => {
      if (!input) return new Date();
      if (input instanceof Date) return input;

      let safeString = input.toString().replace(' ', 'T');
      // Tambahkan Z jika tidak ada, agar dianggap UTC lalu dikonversi ke Lokal
      if (!safeString.endsWith('Z') && !safeString.includes('+')) {
        safeString += 'Z';
      }

      let d = new Date(safeString);
      return isNaN(d.getTime()) ? new Date() : d;
    };

    // Format: 12-01-2026 14:45:06 WIB
    const formatDateJS = (d) => {
      const dateObj = parseDateSafe(d);

      const day = String(dateObj.getDate()).padStart(2, '0');
      const month = String(dateObj.getMonth() + 1).padStart(2, '0');
      const year = dateObj.getFullYear();
      const hours = String(dateObj.getHours()).padStart(2, '0');
      const minutes = String(dateObj.getMinutes()).padStart(2, '0');
      const seconds = String(dateObj.getSeconds()).padStart(2, '0');

      // Deteksi Timezone
      const offset = -dateObj.getTimezoneOffset();
      let zona = '';
      if (offset === 420) zona = 'WIB';
      else if (offset === 480) zona = 'WITA';
      else if (offset === 540) zona = 'WIT';
      else {
        const jamOffset = offset / 60;
        zona = `(UTC${jamOffset >= 0 ? '+' : ''}${jamOffset})`;
      }

      return `${day}-${month}-${year} ${hours}:${minutes}:${seconds} ${zona}`;
    };

    // Icons
    function createStartIcon() {
      return L.divIcon({
        html: `<div class="pin-wrapper start-icon"><i class="ri-map-pin-line"></i></div>`,
        className: '',
        iconSize: [50, 50],
        iconAnchor: [25, 45]
      });
    }

    function createTruckIcon() {
      return L.divIcon({
        html: `<div class="pin-wrapper truck-icon"><div class="pin-pulse"></div><i class="ri-map-pin-user-line"></i></div>`,
        className: '',
        iconSize: [50, 50],
        iconAnchor: [25, 45]
      });
    }

    document.addEventListener('DOMContentLoaded', function() {

      // A. INIT MAP
      const mapContainer = document.getElementById('map-container');
      if (!mapContainer) return;

      map = L.map('map-container').setView([-2.548926, 118.0148634], 5);
      L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '© OpenStreetMap © CARTO',
        maxZoom: 19
      }).addTo(map);

      // --- [UPDATE LOGIKA] RESUME TRACKING SAAT KLIK PETA KOSONG ---
      map.on('click', function() {
        if (!isAutoPan) {
          isAutoPan = true;
          if (selectedMarker) {
            map.removeLayer(selectedMarker);
            selectedMarker = null;
          }
          document.querySelectorAll('#gps-table tbody tr').forEach(r => r.classList.remove('table-active-row'));

          if (truckMarker) {
            map.panTo(truckMarker.getLatLng());
            truckMarker.openPopup();
          }
          console.log("🔄 Resume Auto Tracking");
        }
      });

      // B. LOAD DATA LAMA & FORMAT TABEL
      const rows = document.querySelectorAll('#gps-table tbody tr');

      rows.forEach((row) => {
        // 1. Ambil LatLong untuk Map
        const lat = parseFloat(row.getAttribute('data-lat'));
        const lng = parseFloat(row.getAttribute('data-lng'));
        if (!isNaN(lat) && !isNaN(lng)) {
          liveLatLngs.push([lat, lng]);
        }

        // 2. FORMAT ULANG WAKTU DI TABEL (Agar Data Lama Sesuai Format Baru)
        // Ambil raw string dari atribut (misal: 2024-01-01 10:00:00)
        const rawTime = row.getAttribute('data-time');
        if (rawTime) {
          const prettyTime = formatDateJS(rawTime);
          // Update kolom ke-2 (index 1) di tabel HTML
          if (row.cells[1]) {
            row.cells[1].innerHTML = `<i class="ri-time-line me-1"></i>${prettyTime}`;
          }
        }
      });

      // Reverse array agar [0] = Titik Awal (Lama), [Akhir] = Titik Truk (Baru)
      liveLatLngs.reverse();

      if (liveLatLngs.length > 0) {
        startMarker = L.marker(liveLatLngs[0], {
          icon: createStartIcon()
        }).addTo(map);

        truckMarker = L.marker(liveLatLngs[liveLatLngs.length - 1], {
          icon: createTruckIcon(),
          zIndexOffset: 1000
        }).addTo(map);

        polyline = L.polyline(liveLatLngs, {
          color: '#be1e2d',
          weight: 4,
          dashArray: '10, 10'
        }).addTo(map);

        map.fitBounds(L.latLngBounds(liveLatLngs), {
          padding: [50, 50]
        });
      }

      // C. INTERAKSI TABEL
      const tableWrapper = document.getElementById('table-wrapper');
      if (tableWrapper) {
        tableWrapper.addEventListener('click', function(e) {
          const row = e.target.closest('tr');
          if (row && row.dataset.lat && row.dataset.lng) {

            isAutoPan = false;
            console.log("⏸️ User clicked table. Auto Tracking Paused.");

            const lat = parseFloat(row.dataset.lat);
            const lng = parseFloat(row.dataset.lng);

            // Gunakan formatJS untuk popup juga agar konsisten
            const rawTime = row.dataset.time;
            const timeDisplay = formatDateJS(rawTime);
            const speed = row.dataset.speed || '0';

            // 1. Highlight Baris Tabel
            document.querySelectorAll('#gps-table tbody tr').forEach(r => r.classList.remove('table-active-row'));
            row.classList.add('table-active-row');

            // 2. Hapus Marker Seleksi Lama (Jika Ada)
            if (selectedMarker) {
              map.removeLayer(selectedMarker);
              selectedMarker = null; // Reset variable
            }

            // 3. Buat Marker Baru (Biru)
            selectedMarker = L.circleMarker([lat, lng], {
              radius: 8,
              fillColor: "#0ea5e9",
              color: "#fff",
              weight: 2,
              opacity: 1,
              fillOpacity: 0.8
            }).addTo(map);

            // 4. Bind Popup & Buka
            selectedMarker.bindPopup(`
                <div style="text-align:center;">
                  <strong>Riwayat</strong><br><small>${timeDisplay}</small><br>
                  <span style="color:#10b981; font-weight:bold">${speed} km/h</span><br>
                  <em style="font-size:10px; color:#999;">Klik peta untuk kembali ke live</em>
                </div>
            `).openPopup();

            // --- [TAMBAHAN BARU] HAPUS MARKER SAAT POPUP DITUTUP ---
            selectedMarker.on('popupclose', function() {
              // Hapus marker dari peta
              if (selectedMarker) {
                map.removeLayer(selectedMarker);
                selectedMarker = null;
              }
              // Hapus highlight warna biru di tabel
              document.querySelectorAll('#gps-table tbody tr').forEach(r => r.classList.remove(
                'table-active-row'));

              console.log("❌ Popup closed. Selection cleared.");
            });
            // -------------------------------------------------------

            // 5. Geser Peta ke Titik Tersebut
            map.panTo([lat, lng], {
              animate: true
            });
          }
        });
      }

      // D. REALTIME LISTENER
      const channelName = `tracking.sesi.${window.SESI_ALAT_ID}`;
      window.Echo.channel(channelName)
        .listen('.LokasiBaru', (e) => {
          const latLng = [parseFloat(e.lat), parseFloat(e.lng)];

          // Gunakan formatter baru untuk data realtime
          const rawDate = e.created_at || new Date();
          const fullDateStr = formatDateJS(rawDate);

          // 1. Update Map Elements
          if (liveLatLngs.length === 0) {
            startMarker = L.marker(latLng, {
              icon: createStartIcon()
            }).addTo(map);
            truckMarker = L.marker(latLng, {
              icon: createTruckIcon(),
              zIndexOffset: 1000
            }).addTo(map);
            polyline = L.polyline([latLng], {
              color: '#be1e2d',
              weight: 4
            }).addTo(map);
            map.setView(latLng, 18);
          } else {
            if (truckMarker) truckMarker.setLatLng(latLng);
            if (polyline) polyline.addLatLng(latLng);
          }
          liveLatLngs.push(latLng);

          if (isAutoPan) {
            map.panTo(latLng);
          }

          // 2. Update Tabel
          let tbody = document.querySelector('#gps-table tbody');
          if (tbody) {
            const newIndex = tbody.rows.length + 1;

            const rowHtml = `
              <tr class="clickable-row" data-lat="${e.lat}" data-lng="${e.lng}" data-time="${rawDate}" data-speed="${e.speed || 0}">
                  <td style="font-size: 12px; font-weight: 600; text-align:center;">
                      <span class="number-badge" style="background-color: #be1e2d; color: white; padding: 2px 6px; border-radius: 50%; display: inline-block; min-width: 20px;">
                          ${newIndex}
                      </span>
                  </td>
                  <td style="font-size: 12px;"><i class="ri-time-line me-1"></i>${fullDateStr}</td>
                  <td style="font-size: 12px;">${e.lat}</td>
                  <td style="font-size: 12px;">${e.lng}</td>
                  <td style="font-size: 12px;">${e.alt ? e.alt+' m' : '-'}</td>
                  <td style="font-size: 12px;">${e.sat ? '<span class="badge bg-primary">'+e.sat+'</span>' : '-'}</td>
                  <td style="font-size: 12px;">${e.speed ? '<span style="color:#10b981;font-weight:600">'+e.speed+' km/h</span>' : '-'}</td>
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
          }
        });
    });
  </script>
@endsection
