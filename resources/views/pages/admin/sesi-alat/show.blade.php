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
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($riwayatPerjalanan as $riwayat)
                      <tr class="clickable-row" data-lat="{{ $riwayat->lat }}" data-lng="{{ $riwayat->long }}"
                        data-time="{{ $riwayat->created_at?->translatedFormat('H:i:s') }}"
                        data-speed="{{ $riwayat->speed ?? 0 }}">
                        <td style="font-size: 12px; font-weight: 600;">
                          <span class="rounded-circle py-1 px-2 bg-primary text-white">
                            {{ $loop->iteration }}
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
    // Default true: Peta akan mengikuti pergerakan truk
    let isAutoPan = true;

    // Helper Format
    function formatDateJS(dateString) {
      /* ... sama seperti sebelumnya ... */
      if (!dateString) return '-';
      const date = new Date(dateString);
      return `${String(date.getDate()).padStart(2,'0')}-${String(date.getMonth()+1).padStart(2,'0')}-${date.getFullYear()} ${String(date.getHours()).padStart(2,'0')}:${String(date.getMinutes()).padStart(2,'0')}:${String(date.getSeconds()).padStart(2,'0')}`;
    }

    function formatTimeJS(dateString) {
      if (!dateString) return '-';
      const date = new Date(dateString);
      return `${String(date.getHours()).padStart(2,'0')}:${String(date.getMinutes()).padStart(2,'0')}:${String(date.getSeconds()).padStart(2,'0')}`;
    }

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
          // Hapus highlight seleksi jika ada
          if (selectedMarker) {
            map.removeLayer(selectedMarker);
            selectedMarker = null;
          }
          // Hapus highlight di tabel
          document.querySelectorAll('#gps-table tbody tr').forEach(r => r.classList.remove('table-active-row'));

          // Langsung fokus ke truk kembali
          if (truckMarker) {
            map.panTo(truckMarker.getLatLng());
            truckMarker.openPopup();
          }
          console.log("🔄 Resume Auto Tracking");
        }
      });

      // B. LOAD DATA LAMA
      const rows = document.querySelectorAll('#gps-table tbody tr');
      rows.forEach((row) => {
        const lat = parseFloat(row.getAttribute('data-lat'));
        const lng = parseFloat(row.getAttribute('data-lng'));
        if (!isNaN(lat) && !isNaN(lng)) liveLatLngs.push([lat, lng]);
      });

      if (liveLatLngs.length > 0) {
        startMarker = L.marker(liveLatLngs[0], {
          icon: createStartIcon()
        }).addTo(map);
        truckMarker = L.marker(liveLatLngs[liveLatLngs.length - 1], {
          icon: createTruckIcon(),
          zIndexOffset: 1000
        }).addTo(map);

        // Tambahkan Popup ke Truk agar user tau statusnya
        // truckMarker.bindPopup("<b>Posisi Terkini</b><br>Tracking Aktif").openPopup();

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

            // --- [UPDATE LOGIKA] STOP AUTO PAN SAAT KLIK TABEL ---
            isAutoPan = false;
            console.log("⏸️ User clicked table. Auto Tracking Paused.");

            const lat = parseFloat(row.dataset.lat);
            const lng = parseFloat(row.dataset.lng);
            const time = row.dataset.time || '-';
            const speed = row.dataset.speed || '0';

            // UX: Bersihkan seleksi lama
            document.querySelectorAll('#gps-table tbody tr').forEach(r => r.classList.remove('table-active-row'));
            row.classList.add('table-active-row');

            if (selectedMarker) map.removeLayer(selectedMarker);

            // Marker Biru (Titik Terpilih)
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

            // Fokus ke titik riwayat
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
          const timeStr = formatTimeJS(e.created_at || new Date());
          const fullDateStr = formatDateJS(e.created_at || new Date());

          // 1. Selalu Update Marker & Garis (Background Process)
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

          // --- [UPDATE LOGIKA] HANYA GESER PETA JIKA 'isAutoPan' TRUE ---
          if (isAutoPan) {
            map.panTo(latLng);
            // Optional: Buka popup truk jika sedang auto follow
            // truckMarker.getPopup().setContent(`<b>Live Tracking</b><br>${e.speed} km/h`);
          } else {
            // Optional: Beritahu user ada data baru tapi tidak digeser
            console.log("Data diterima (Background update)");
          }

          // 2. Update Tabel
          let tbody = document.querySelector('#gps-table tbody');
          if (tbody) {
            const newIndex = tbody.rows.length + 1;
            const rowHtml = `
                            <tr class="clickable-row" data-lat="${e.lat}" data-lng="${e.lng}" data-time="${timeStr}" data-speed="${e.speed || 0}">
                                <td style="font-size: 12px; font-weight: 600; text-align:center;">
                                    <span style="background-color: #be1e2d; color: white; padding: 2px 6px; border-radius: 50%; display: inline-block; min-width: 20px;">${newIndex}</span>
                                </td>
                                <td style="font-size: 12px;"><i class="ri-time-line me-1"></i>${fullDateStr}</td>
                                <td style="font-size: 12px;">${e.lat}</td>
                                <td style="font-size: 12px;">${e.lng}</td>
                                <td style="font-size: 12px;">${e.alt ? e.alt+' m' : '-'}</td>
                                <td style="font-size: 12px;">${e.sat ? '<span class="badge bg-primary">'+e.sat+'</span>' : '-'}</td>
                                <td style="font-size: 12px;">${e.speed ? '<span style="color:#10b981;font-weight:600">'+e.speed+' km/h</span>' : '-'}</td>
                            </tr>
                        `;
            tbody.insertAdjacentHTML('beforeend', rowHtml);
          }
        });
    });
  </script>
@endsection
