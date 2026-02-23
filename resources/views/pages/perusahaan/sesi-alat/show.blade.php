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
      opacity: 0.15;
      /* Sangat transparan */
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
      /* Sangat transparan */
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
        /* Jangkauan sedikit lebih luas agar tetap terlihat meski sangat muda */
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
      <div>
        <h1>Detail Perjalanan</h1>
        <p>Informasi lengkap sesi GPS tracking</p>
      </div>
    </div>
    <div>
      <a href="{{ route('perusahaan.sesi-alat.index') }}" class="btn btn-light btn-wave">
        <i class="ri-arrow-left-line me-2"></i>Kembali
      </a>
    </div>
  </div>

  <div class="row mb-4">
    <div class="col-xl-8 col-lg-7">
      <div class="detail-card h-100 mb-0">
        <div class="card-header">
          <h5>Informasi Sesi</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="detail-group mb-3 d-flex flex-column">
                <span class="detail-label mb-1">Nama Sesi</span>
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
        <div class="card-header">
          <h5>Status & Kontrol Sesi</h5>
        </div>
        <div class="card-body d-flex flex-column align-items-center justify-content-center py-4 flex-grow-1">
          @php
            $lastRiwayat = $riwayatPerjalanan->sortByDesc('created_at')->first();
            $batLevel = (int) ($lastRiwayat?->battery ?? 0);
          @endphp

          <div class="d-flex w-100 align-items-stretch flex-grow-1">
            {{-- KOLOM BATERAI --}}
            <div class="flex-fill d-flex flex-column align-items-center justify-content-center border-end">
              <span class="detail-label mb-3 d-block text-center text-uppercase text-nowrap">Baterai Alat</span>
              <div class="my-auto">
                @if ($lastRiwayat && $lastRiwayat->battery !== null)
                  <x-battery-circle :level="$batLevel" size="85px" />
                @else
                  <div class="text-muted small">Tidak ada data</div>
                @endif
              </div>
            </div>

            {{-- KOLOM KONTROL --}}
            <div class="flex-fill d-flex flex-column align-items-center justify-content-center">
              <label class="detail-label mb-3 d-block text-uppercase text-center text-nowrap">Kontrol Daya</label>
              <div class="my-auto d-flex flex-column align-items-center">
                <form id="toggle-power-form" action="{{ route('perusahaan.sesi-alat.toggle', $sesiAlat->id) }}"
                  method="POST">
                  @csrf
                  @method('PUT')
                  <button type="submit" id="toggle-power-btn"
                    class="btn btn-icon btn-lg rounded-circle shadow-lg {{ $sesiAlat->aktif ? 'btn-success pulse-active' : 'btn-danger' }}"
                    style="width: 80px; height: 80px;" title="{{ $sesiAlat->aktif ? 'Matikan Sesi' : 'Aktifkan Sesi' }}">
                    <i id="toggle-power-icon" class="ri-shut-down-line fs-40"></i>
                  </button>
                </form>
                <span id="power-status-badge"
                  class="badge rounded-pill mt-3 {{ $sesiAlat->aktif ? 'bg-success-transparent text-success' : 'bg-danger-transparent text-danger' }} px-3">
                  {{ $sesiAlat->aktif ? 'SESI AKTIF' : 'SESI NONAKTIF' }}
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
          <div id="map-container" style="height: 400px; width: 100%; border-radius: 8px; margin-bottom: 20px;"></div>

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
    // --- 2. CHECK ECHO ---
    if (typeof window.Echo === 'undefined') {
      console.warn('Echo is not defined. Realtime updates might not work.');
    }

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
    let isAutoPan = true;

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
      if (isNaN(dateObj.getTime())) return '-';

      const day = String(dateObj.getDate()).padStart(2, '0');
      const month = String(dateObj.getMonth() + 1).padStart(2, '0');
      const year = dateObj.getFullYear();
      const hours = String(dateObj.getHours()).padStart(2, '0');
      const minutes = String(dateObj.getMinutes()).padStart(2, '0');
      const seconds = String(dateObj.getSeconds()).padStart(2, '0');

      const offset = -dateObj.getTimezoneOffset();
      let zona = '';
      if (offset === 420) zona = 'WIB';
      else if (offset === 480) zona = 'WITA';
      else if (offset === 540) zona = 'WIT';
      else {
        const sign = offset >= 0 ? '+' : '-';
        const absOffset = Math.abs(offset);
        const h = String(Math.floor(absOffset / 60)).padStart(2, '0');
        const m = String(absOffset % 60).padStart(2, '0');
        zona = `UTC${sign}${h}:${m}`;
      }

      return `${day}-${month}-${year} ${hours}:${minutes}:${seconds} ${zona}`;
    };

    const formatAllLocalDates = () => {
      document.querySelectorAll('.local-date').forEach(el => {
        const raw = el.textContent.trim();
        if (raw && raw !== '-') {
          el.textContent = formatDateJS(raw);
        }
      });
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
            color: '#be1e2d',
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
          isAutoPan = false;
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
      window.Echo.channel(`tracking.sesi.${window.SESI_ALAT_ID}`)
        .listen('.LokasiBaru', (e) => {
          const latLng = [parseFloat(e.lat), parseFloat(e.lng)];
          rawRiwayatData.unshift(e);
          currentIndex++;

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

          const tbody = document.getElementById('history-table-body');
          const emptyRow = document.getElementById('empty-row');
          if (emptyRow) emptyRow.remove();

          const rowHtml = getRowHtml(e, 0);
          tbody.insertAdjacentHTML('afterbegin', rowHtml);
        });

      setTimeout(() => {
        map.invalidateSize();
      }, 500);

      // E. AJAX POWER TOGGLE
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
                const isActive = data.aktif;

                // Update Button
                powerBtn.className =
                  `btn btn-icon btn-lg rounded-circle shadow-lg ${isActive ? 'btn-success pulse-active' : 'btn-danger'}`;
                powerBtn.title = isActive ? 'Matikan Sesi' : 'Aktifkan Sesi';

                // Update Icon
                powerIcon.className = 'ri-shut-down-line fs-40';

                // Update Badge
                statusBadge.className =
                  `badge rounded-pill mt-3 ${isActive ? 'bg-success-transparent text-success' : 'bg-danger-transparent text-danger'} px-3`;
                statusBadge.textContent = isActive ? 'SESI AKTIF' : 'SESI NONAKTIF';
              } else {
                // Rollback on failure
                powerIcon.className = originalIconClass;
                alert(data.message || 'Gagal mengubah status daya.');
              }
            })
            .catch(error => {
              console.error('Error toggling power:', error);
              powerIcon.className = originalIconClass;
              alert('Terjadi kesalahan sistem.');
            })
            .finally(() => {
              powerBtn.disabled = false;
            });
        });
      }

      formatAllLocalDates();
    });
  </script>
@endsection
