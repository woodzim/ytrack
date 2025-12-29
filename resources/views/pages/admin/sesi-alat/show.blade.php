@extends('layouts.master')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
  <style>
    .leaflet-marker-icon {
      transition: transform 0.6s linear;
      will-change: transform;
    }

    .pin-marker {
      position: relative;
      width: 36px;
      height: 36px;
      background: #be1e2d;
      border-radius: 50% 50% 50% 0;
      transform: rotate(-45deg);
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
      border: 3px solid white;
    }

    .pin-marker span {
      position: absolute;
      width: 100%;
      height: 100%;
      transform: rotate(45deg);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: bold;
      font-size: 20px;
    }

    .start-pin {
      background: #be1e2d;
    }
  </style>
@endsection

@section('content')
  <!-- Start::page-header -->
  <div class="page-header-section">
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
  <!-- End::page-header -->

  <!-- Start::app-content -->
  <div class="row mb-12">
    <div class="col-12">
      <div class="detail-container">
        <!-- Main Detail Card -->
        <div class="detail-card">
          <div class="card-header">
            <h5>Informasi Sesi</h5>
          </div>
          <div class="card-body">
            <!-- ID Sesi -->
            <div class="detail-group">
              <div class="detail-label" style="margin-bottom: 10px;">ID Sesi</div>
              <div class="detail-value">{{ $sesiAlat->id }}</div>
            </div>

            <!-- Alat -->
            <div class="detail-group">
              <div class="detail-label" style="margin-bottom: 10px;">Alat GPS</div>
              <div class="detail-value">
                <span class="badge-alat">{{ $sesiAlat->alat ? $sesiAlat->alat->id : '-' }}</span>
              </div>
            </div>

            <!-- Kendaraan -->
            <div class="detail-group">
              <div class="detail-label" style="margin-bottom: 10px;">Kendaraan</div>
              <div class="detail-value">{{ $sesiAlat->kendaraan ? $sesiAlat->kendaraan->nama : '-' }}</div>
            </div>

            <!-- Perusahaan -->
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

            <!-- Timeline -->
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

        <!-- Side Actions -->
        <div class="side-card">
          <div class="card-header">
            <h5>Aksi</h5>
          </div>
          <div class="card-body">
            <!-- Toggle Status Button -->
            <form action="{{ route('sesi-alat.toggle', $sesiAlat->id) }}" method="POST" style="margin-bottom: 12px;">
              @csrf
              @method('PUT')
              <button type="submit" class="btn-toggle-large {{ $sesiAlat->aktif ? 'active' : 'inactive' }}">
                <i class="ri-{{ $sesiAlat->aktif ? 'pause' : 'play' }}-line me-2"></i>
                {{ $sesiAlat->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
              </button>
            </form>

            <!-- Edit Button -->
            <a href="{{ route('sesi-alat.edit', $sesiAlat->id) }}" class="btn btn-primary btn-sm w-100 mb-2">
              <i class="ri-edit-line me-2"></i>Edit
            </a>

            <!-- Delete Button -->
            <form action="{{ route('sesi-alat.destroy', $sesiAlat->id) }}" method="POST"
              onsubmit="return confirm('Hapus sesi ini secara permanen? Tindakan ini tidak dapat dibatalkan.');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-primary btn-sm w-100 mb-2">
                <i class="ri-delete-bin-line me-2"></i>Hapus Sesi
              </button>
            </form>

            <!-- Back Button -->
            <a href="{{ route('sesi-alat.index') }}" class="btn btn-outline-secondary btn-sm w-100">
              <i class="ri-arrow-left-line me-2"></i>Kembali
            </a>

            <hr class="my-3">

            <!-- Status Info -->
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
                  <strong>Sesi nonaktif.</strong> Alat GPS tidak sedang tracking. Klik tombol
                  <strong>Aktifkan</strong> untuk memulai.
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Riwayat Perjalanan Section -->
  <div class="row my-4">
    <div class="col-12">
      <div class="detail-card">
        <div class="card-header">
          <h5><i class="ri-map-pin-line me-2"></i>Riwayat Perjalanan</h5>
        </div>
        <div class="card-body">
          @if ($riwayatPerjalanan->count() > 0)
            <!-- Map Container -->
            <div id="map-container"></div>

            <div class="table-responsive">
              <table class="table table-sm table-hover">
                <thead>
                  <tr style="background-color: #f8f9fa;">
                    <th style="font-size: 12px; font-weight: 600; color: #666; width: 40px;">No</th>
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
                    <tr>
                      <td style="font-size: 12px; font-weight: 600; text-align: center;">
                        <span
                          style="
                            background-color: #be1e2d;
                            color: white;
                            padding: 4px 8px;
                            border: 2px solid white;
                            box-shadow:rgba(255, 255, 255, 0.2);
                            border-radius: 100%;
                            display: inline-block;
                            min-width: 24px;
                            text-align: center;
                        ">{{ $loop->iteration }}</span>
                      </td>
                      <td style="font-size: 12px;">
                        <i
                          class="ri-time-line me-1"></i>{{ $riwayat->created_at?->translatedFormat('d-m-Y H:i:s') ?? '-' }}
                      </td>
                      <td style="font-size: 12px;">{{ $riwayat->lat }}</td>
                      <td style="font-size: 12px;">{{ $riwayat->long }}</td>
                      <td style="font-size: 12px;">
                        @if ($riwayat->alt)
                          {{ $riwayat->alt }} m
                        @else
                          <span class="text-muted">-</span>
                        @endif
                      </td>
                      <td style="font-size: 12px;">
                        @if ($riwayat->sat)
                          <span class="badge bg-primary">{{ $riwayat->sat }}</span>
                        @else
                          <span class="text-muted">-</span>
                        @endif
                      </td>
                      <td style="font-size: 12px;">
                        @if ($riwayat->speed)
                          <span style="color: #10b981; font-weight: 600;">{{ $riwayat->speed }}
                            km/h</span>
                        @else
                          <span class="text-muted">-</span>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div style="text-align: center; padding: 40px 20px; color: #999;">
              <i class="ri-map-pin-2-line"
                style="font-size: 48px; margin-bottom: 16px; display: block; opacity: 0.5;"></i>
              <p style="margin: 0;">Belum ada data riwayat perjalanan</p>
              <small>Data GPS tracking akan muncul di sini</small>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  <!-- End Riwayat Perjalanan Section -->
@endsection

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

  <script>
    window.SESI_ALAT_ID = {{ $sesiAlat->id }};

    let map = null;
    let startMarker = null;
    let truckMarker = null;
    let polyline = null;
    let liveLatLngs = [];

    // --- Helper: Format Tanggal JS agar sama dengan PHP d-m-Y H:i:s ---
    function formatDateJS(dateString) {
      if (!dateString) return '-';
      const date = new Date(dateString);
      const d = String(date.getDate()).padStart(2, '0');
      const m = String(date.getMonth() + 1).padStart(2, '0');
      const y = date.getFullYear();
      const h = String(date.getHours()).padStart(2, '0');
      const min = String(date.getMinutes()).padStart(2, '0');
      const s = String(date.getSeconds()).padStart(2, '0');
      return `${d}-${m}-${y} ${h}:${min}:${s}`;
    }

    function createStartIcon() {
      return L.divIcon({
        html: `<div class="pin-marker start-pin" >
            <span></span>
            </div>`,
        className: '',
        iconAnchor: [18, 50]
      });
    }

    function createTruckIcon() {
      return L.divIcon({
        html: `<div class="pin-marker start-pin" >
            <span>🚚</span>
            </div>`,
        className: 'truck-marker',
        iconAnchor: [18, 50]
      });
    }

    document.addEventListener('DOMContentLoaded', function() {
      const mapContainer = document.getElementById('map-container');
      if (!mapContainer) return;

      map = L.map('map-container', {
        maxZoom: 20,
        minZoom: 12
      });

      L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '© OpenStreetMap © CARTO',
        maxZoom: 19
      }).addTo(map);

      const coordinates = [];
      const rows = document.querySelectorAll('.table tbody tr');

      rows.forEach((row) => {
        const cells = row.querySelectorAll('td');
        if (cells.length >= 4) {
          const lat = parseFloat(cells[2].textContent.trim());
          const lng = parseFloat(cells[3].textContent.trim());
          if (!isNaN(lat) && !isNaN(lng)) {
            coordinates.push({
              lat,
              lng
            });
          }
        }
      });

      if (coordinates.length > 0) {
        liveLatLngs = coordinates.map(c => [c.lat, c.lng]);

        const first = liveLatLngs[0];
        const last = liveLatLngs[liveLatLngs.length - 1];

        startMarker = L.marker(first, {
          icon: createStartIcon()
        }).addTo(map);
        truckMarker = L.marker(last, {
          icon: createTruckIcon()
        }).addTo(map);

        polyline = L.polyline(liveLatLngs, {
          color: '#be1e2d',
          weight: 2,
          opacity: 0.8,
          dashArray: '5, 5'
        }).addTo(map);

        map.fitBounds(L.latLngBounds(liveLatLngs), {
          padding: [150, 150]
        });
      } else {
        // Set view default jika belum ada data
        map.setView([-0.502106, 117.153709], 13);
      }

      // ==========================================
      // REALTIME LISTENER (MAP + TABEL)
      // ==========================================
      Echo.channel(`tracking.sesi.${window.SESI_ALAT_ID}`)
        .listen('RiwayatPerjalananCreated', (e) => {

          // 1. UPDATE MAP (Logika Asli Tidak Diubah)
          const latLng = [e.lat, e.lng];

          // Handle jika ini titik pertama kali masuk
          if (liveLatLngs.length === 0) {
            startMarker = L.marker(latLng, {
              icon: createStartIcon()
            }).addTo(map);
            truckMarker = L.marker(latLng, {
              icon: createTruckIcon()
            }).addTo(map);
            polyline = L.polyline([latLng], {
              color: '#be1e2d',
              weight: 2,
              opacity: 0.8,
              dashArray: '5, 5'
            }).addTo(map);
            map.setView(latLng, 16);
          }

          liveLatLngs.push(latLng);

          if (polyline) polyline.setLatLngs(liveLatLngs);
          if (truckMarker) truckMarker.setLatLng(latLng);

          map.panTo(latLng, {
            animate: true,
            duration: 0.5
          });

          // 2. UPDATE TABEL SECARA REALTIME
          const tbody = document.querySelector('.table tbody');

          if (tbody) {
            // Hitung nomor urut baru berdasarkan jumlah baris yang ada
            const newIndex = tbody.rows.length + 1;
            const formattedDate = formatDateJS(e.created_at || new Date());

            // Siapkan HTML untuk Alt, Sat, Speed (dengan styling yang sama)
            const altDisplay = e.alt ? `${e.alt} m` : '<span class="text-muted">-</span>';
            const satDisplay = e.sat ? `<span class="badge bg-primary">${e.sat}</span>` :
              '<span class="text-muted">-</span>';
            const speedDisplay = e.speed ?
              `<span style="color: #10b981; font-weight: 600;">${e.speed} km/h</span>` :
              '<span class="text-muted">-</span>';

            // Template Row (Copy-paste style dari Blade agar konsisten)
            const newRowHTML = `
                <tr>
                  <td style="font-size: 12px; font-weight: 600; text-align: center;">
                    <span style="background-color: #be1e2d; color: white; padding: 4px 8px; border: 2px solid white; box-shadow:rgba(255, 255, 255, 0.2); border-radius: 100%; display: inline-block; min-width: 24px; text-align: center;">
                        ${newIndex}
                    </span>
                  </td>
                  <td style="font-size: 12px;">
                    <i class="ri-time-line me-1"></i>${formattedDate}
                  </td>
                  <td style="font-size: 12px;">${e.lat}</td>
                  <td style="font-size: 12px;">${e.lng}</td>
                  <td style="font-size: 12px;">${altDisplay}</td>
                  <td style="font-size: 12px;">${satDisplay}</td>
                  <td style="font-size: 12px;">${speedDisplay}</td>
                </tr>
              `;

            // Masukkan row baru ke bagian bawah tabel
            tbody.insertAdjacentHTML('beforeend', newRowHTML);

          } else {
            // Edge Case: Jika halaman awalnya kosong (0 data), tabel belum dirender oleh Blade.
            // Cara termudah adalah reload halaman agar tabel dirender ulang oleh Server.
            location.reload();
          }

        });
    });
  </script>
@endsection
