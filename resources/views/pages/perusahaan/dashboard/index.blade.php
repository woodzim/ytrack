@extends('layouts.master')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/MarkerCluster.css" />
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/MarkerCluster.Default.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet-gesture-handling@1.2.2/dist/leaflet-gesture-handling.min.css"
    type="text/css">
  <link rel="stylesheet"
    href="https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css" />
  <style>
    #map-dashboard {
      height: 70vh;
      width: 100%;
      border-radius: 0 0 8px 8px;
      position: relative;
      z-index: 1;
    }

    .table-hover tbody tr {
      cursor: pointer;
    }

    /* Prevent leaflet controls from overlapping navbar */
    .leaflet-top,
    .leaflet-bottom {
      z-index: 400 !important;
    }

    /* Custom style for marker cluster to match project theme */
    .marker-cluster-small {
      background-color: rgba(190, 30, 45, 0.6);
    }

    .marker-cluster-small div {
      background-color: rgba(190, 30, 45, 0.6);
      color: white;
    }

    .marker-cluster-medium {
      background-color: rgba(190, 30, 45, 0.8);
    }

    .marker-cluster-medium div {
      background-color: rgba(190, 30, 45, 0.8);
      color: white;
    }

    .marker-cluster-large {
      background-color: rgba(190, 30, 45, 1);
    }

    .marker-cluster-large div {
      background-color: rgba(190, 30, 45, 1);
      color: white;
    }

    @media (max-width: 575.98px) {
      .card, 
      .stats-card,
      .card-header,
      .card-body,
      .card-footer {
        background-color: transparent !important;
        background: transparent !important;
        box-shadow: none !important;
        border: none !important;
      }

      .stats-card .card-body {
        padding: 0.8rem;
      }

      .stats-card .card-title {
        font-size: 0.7rem !important;
        margin-bottom: 0.25rem !important;
      }

      .stats-card h3 {
        font-size: 1.1rem !important;
      }

      .stats-icon i {
        font-size: 1.25rem !important;
      }

      .page-header-breadcrumb {
        margin-bottom: 0.75rem !important;
      }

      .alert {
        padding: 0.75rem;
        margin-bottom: 0.75rem !important;
      }

      .alert i {
        font-size: 1.25rem !important;
        margin-right: 0.5rem !important;
      }

                  #daftar-alat-table tr {
                    display: flex;
                    flex-wrap: wrap;
                    border: 1px solid #e9e9e9 !important;
                    margin-bottom: 0.75rem;
                    border-radius: 0.75rem;
                    padding: 0.85rem;
                    background: transparent !important;
                    box-shadow: none !important;
                    position: relative;
                    transition: all 0.2s ease;
                  }
      
                  /* Aware Style for Low Battery - Removed as requested, using standard card style */
                  #daftar-alat-table tr.table-danger,
                  #daftar-alat-table tr.table-danger td {
                    background-color: transparent !important;
                    border-color: #e9e9e9 !important;
                  }      
                  #daftar-alat-table tr.table-danger {
                    border-left: 1px solid #e9e9e9 !important;
                  }
      
                  #daftar-alat-table tr.table-danger .name-cell {
                    color: inherit !important;
                  }
      #daftar-alat-table td {
        display: block;
        border: none !important;
        padding: 0.25rem 0 !important;
        font-size: 0.85rem;
        white-space: normal !important;
      }

      #daftar-alat-table td::before {
        display: none;
        /* Remove labels as requested */
      }

      #daftar-alat-table td.name-cell {
        width: 100%;
        border-bottom: 1px solid #f3f3f3 !important;
        padding-bottom: 0.6rem !important;
        margin-bottom: 0.5rem;
        font-size: 1.05rem;
        font-weight: 600;
        text-align: left;
      }

      #row-alat-td-kendaraan {
        width: 65%;
        font-weight: 500;
      }

      #row-alat-td-status {
        width: 35%;
        text-align: right;
      }

      #daftar-alat-table td.battery-cell {
        width: 100%;
        margin-top: 0.5rem;
        padding: 0.6rem 0.75rem !important;
        border-radius: 0.5rem;
      }

      #daftar-alat-table td.update-cell {
        width: 100%;
        margin-top: 0.5rem;
        font-size: 0.75rem;
        color: #8c9097;
      }

      #daftar-alat-table td.action-cell {
        width: 100%;
        padding-top: 0.75rem !important;
      }

      #daftar-alat-table td.action-cell .btn {
        width: 100%;
        padding: 0.5rem;
        font-weight: 500;
      }
    }
  </style>
@endsection

@section('content')
  <!-- Start::page-header -->
  <div class="d-flex align-items-center justify-content-between mb-3 page-header-breadcrumb flex-wrap gap-2">
    <div>
      <h1 class="page-title fw-medium fs-20 mb-0">Selamat datang, {{ auth()->user()->name }}.</h1>
      <p class="text-muted mb-0">Dashboard Perusahaan</p>
    </div>
  </div>
  <!-- End::page-header -->

  <!-- Start::app-content -->
  @if ($lowBatCount > 0)
    <div class="alert alert-danger d-flex align-items-center mb-3 shadow-sm" role="alert" style="cursor: pointer;"
      onclick="document.getElementById('daftar-alat-table').scrollIntoView({behavior: 'smooth'})"
      title="Klik untuk melihat daftar alat">
      <i class="ri-battery-low-line fs-24 me-3"></i>
      <div>
        <strong>Perhatian!</strong> Terdapat <b>{{ $lowBatCount }}</b> alat dengan baterai lemah (≤ 20%). Segera lakukan
        pengisian daya agar pemantauan tidak terputus.
      </div>
    </div>
  @endif

  <!-- Map Section -->
  <div class="row g-2 mt-1">
    <div class="col-12">
      <div class="card">
        <div class="p-0">
          <div id="map-dashboard" class="rounded"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Data Alat Table -->
  <div class="row g-2 mt-1" id="daftar-alat-table">
    <div class="col-12">
      <div class="card">
        <div class="card-header border-bottom">
          <div class="card-title mb-0">Daftar Alat</div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered text-nowrap w-100 table-hover">
              <thead>
                <tr>
                  <th>Nama Alat / Serial</th>
                  <th>Kendaraan</th>
                  <th>Baterai</th>
                  <th>Status</th>
                  <th>Terakhir Update</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($lokasiAlat as $alat)
                  <tr class="{{ $alat['is_low_bat'] ? 'table-danger' : '' }}" id="row-alat-{{ $alat['id_sesi'] }}"
                    @if ($alat['id_sesi']) onclick="focusMarker('{{ $alat['id_sesi'] }}')" @endif>
                    <td class="{{ $alat['is_low_bat'] ? 'fw-bold text-danger' : '' }} name-cell" data-label="Nama Alat">
                      @if ($alat['is_low_bat'])
                        <i class="ri-error-warning-fill text-danger me-1"></i>
                      @endif
                      {{ $alat['nama_alat'] }}
                    </td>
                    <td data-label="Kendaraan" id="row-alat-td-kendaraan">
                      <i class="ri-taxi-line me-1 text-muted"></i>{{ $alat['kendaraan'] }}
                    </td>
                    <td data-label="Status" id="row-alat-td-status">
                      <span class="badge {{ $alat['status'] == 'Aktif' ? 'bg-success' : 'bg-danger' }}">
                        {{ $alat['status'] }}
                      </span>
                    </td>
                    <td class="battery-cell" data-label="Baterai">
                      @php
                        $bat = (int) $alat['battery'];
                        $batColor = 'bg-danger';
                        if ($bat > 70) {
                            $batColor = 'bg-success';
                        } elseif ($bat >= 30) {
                            $batColor = 'bg-warning';
                        }
                      @endphp
                      <div class="d-flex align-items-center">
                        <i class="ri-battery-fill me-2 {{ str_replace('bg-', 'text-', $batColor) }}"></i>
                        <div class="progress flex-grow-1" style="height: 8px;">
                          <div class="progress-bar {{ $batColor }}" role="progressbar"
                            style="width: {{ $bat }}%"></div>
                        </div>
                        <span class="ms-2 small fw-bold">{{ $bat }}%</span>
                      </div>
                    </td>
                    <td class="update-cell" data-label="Terakhir Update">
                      <i class="ri-time-line me-1 text-muted"></i>{{ $alat['last_update'] }}
                    </td>
                                                                        <td class="action-cell">
                                                                          @if ($alat['id_sesi'])
                                                                            <a href="{{ route('perusahaan.sesi-alat.show', $alat['id_sesi']) }}"
                                                                              class="btn btn-sm btn-primary">
                                                                              <i class="ri-eye-line me-1"></i>Detail
                                                                            </a>
                                                                          @else
                                                                            <span class="text-muted">-</span>
                                                                          @endif
                                                                        </td>                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center">Tidak ada data alat.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-2 mt-1">
    <!-- Statistics Cards -->
    <div class="col-6 col-xl-3 col-md-6">
      <div class="card stats-card">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <h6 class="card-title text-muted mb-2">Perjalanan</h6>
              <h3 class="mb-0">{{ $totalSesiAlat }}</h3>
            </div>
            <div class="stats-icon">
              <i class="ri-route-line fs-24 text-info"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-6 col-xl-3 col-md-6">
      <div class="card stats-card">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <h6 class="card-title text-muted mb-2">Total Kendaraan</h6>
              <h3 class="mb-0">{{ $totalKendaraan }}</h3>
            </div>
            <div class="stats-icon">
              <i class="ri-taxi-line fs-24 text-warning"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-6 col-xl-3 col-md-6">
      <div class="card stats-card">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <h6 class="card-title text-muted mb-2">Dalam Perjalanan</h6>
              <h3 class="mb-0">{{ $kendaraanDalamPerjalanan }}</h3>
            </div>
            <div class="stats-icon">
              <i class="ri-map-pin-line fs-24 text-success"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-6 col-xl-3 col-md-6">
      <div class="card stats-card {{ $lowBatCount > 0 ? 'border-danger' : '' }}" id="card-low-bat"
        @if ($lowBatCount > 0) style="cursor: pointer;" onclick="document.getElementById('daftar-alat-table').scrollIntoView({behavior: 'smooth'})" 
        title="Klik untuk melihat detail alat dengan baterai lemah" @endif>
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <h6 class="card-title text-muted mb-2">Baterai Lemah</h6>
              <h3 class="mb-0 {{ $lowBatCount > 0 ? 'text-danger' : '' }}" id="count-low-bat">{{ $lowBatCount }}</h3>
            </div>
            <div class="stats-icon text-danger">
              <i class="ri-battery-low-line fs-24"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-2 mt-1">
    <!-- Profile Edit -->
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header border-bottom">
          <div class="card-title mb-0">Edit Profil Perusahaan</div>
        </div>
        <div class="card-body">
          @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="ri-check-line me-2"></i>{{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          <form action="{{ route('perusahaan.dashboard.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
              <label for="name" class="form-label">Nama Pengguna</label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                name="name" value="{{ old('name', auth()->user()->name) }}" required>
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                name="email" value="{{ old('email', auth()->user()->email) }}" required>
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="nama" class="form-label">Nama Perusahaan</label>
              <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                name="nama" value="{{ old('nama', $perusahaan->nama) }}" required>
              @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="alamat" class="form-label">Alamat Perusahaan</label>
              <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="4"
                required>{{ old('alamat', $perusahaan->alamat) }}</textarea>
              @error('alamat')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary btn-wave">
                <i class="ri-save-line me-2"></i>Simpan Perubahan
              </button>
              <a href="{{ route('perusahaan.dashboard.index') }}" class="btn btn-outline-secondary btn-wave">
                <i class="ri-close-line me-2"></i>Batal
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Recent Activity -->
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header border-bottom">
          <div class="card-title mb-0">Aktivitas Terbaru</div>
        </div>
        <div class="card-body p-0">
          @if ($recentRiwayat->count() > 0)
            <div class="table-responsive mb-0">
              <table class="table table-sm table-hover mb-0">
                <thead class="table-light">
                  <tr>
                    <th>Waktu</th>
                    <th>Kendaraan</th>
                    <th>Alat</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($recentRiwayat as $item)
                    <tr>
                      <td>
                        <small class="text-muted">
                          {{ $item->created_at?->format('d-m-Y H:i') ?? '-' }}
                        </small>
                      </td>
                      <td>
                        <span class="badge bg-light text-dark">
                          {{ $item->kendaraan?->nama ?? '-' }}
                        </span>
                      </td>
                      <td>
                        <span class="badge bg-info">
                          {{ $item->alat?->id ?? '-' }}
                        </span>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="alert alert-info m-3">
              <i class="ri-information-line me-2"></i>
              Belum ada aktivitas perjalanan
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  <!-- End::app-content -->
@endsection

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/leaflet.markercluster.js"></script>
  <script src="https://unpkg.com/leaflet-gesture-handling@1.2.2/dist/leaflet-gesture-handling.min.js"></script>
  <script src="https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.js"></script>
  <script>
    // Helper: Parse date safely
    const parseDateSafe = (input) => {
      if (!input) return new Date();
      if (input instanceof Date) return input;
      let safeString = input.toString().replace(' ', 'T');
      if (!safeString.endsWith('Z') && !safeString.includes('+')) {
        safeString += 'Z';
      }
      let d = new Date(safeString);
      return isNaN(d.getTime()) ? new Date() : d;
    };

    // Formatter Date (Auto Timezone)
    const formatDateJS = (d) => {
      const dateObj = parseDateSafe(d);
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
        const jamOffset = offset / 60;
        const tanda = jamOffset >= 0 ? '+' : '';
        zona = `(UTC${tanda}${jamOffset})`;
      }
      return `${day}-${month}-${year} ${hours}:${minutes}:${seconds} ${zona}`;
    };

    let markers = {};
    let map;
    let markerCluster;
    let isUserFocusing = false;
    let trackedSesiId = null;
    let fitTimeout;

    const focusMarker = (id_sesi) => {
      if (markers[id_sesi]) {
        trackedSesiId = id_sesi;
        isUserFocusing = true;

        // Zoom in slightly less to keep popup in view
        map.setView(markers[id_sesi].getLatLng(), 16);

        // If marker is in a cluster, this will zoom to it
        if (markerCluster.hasLayer(markers[id_sesi])) {
          markerCluster.zoomToShowLayer(markers[id_sesi], () => {
            markers[id_sesi].openPopup();
          });
        } else {
          markers[id_sesi].openPopup();
        }

        // Scroll with offset to account for navbar
        const element = document.getElementById('map-dashboard');
        const offset = 100;
        const bodyRect = document.body.getBoundingClientRect().top;
        const elementRect = element.getBoundingClientRect().top;
        const elementPosition = elementRect - bodyRect;
        const offsetPosition = elementPosition - offset;

        window.scrollTo({
          top: offsetPosition,
          behavior: 'smooth'
        });
      }
    };

    document.addEventListener('DOMContentLoaded', function() {
      // Base Layers
      const streets = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        maxZoom: 19
      });

      const satellite = L.tileLayer(
        'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
          attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
        });

      map = L.map('map-dashboard', {
        center: [-2.5489, 118.0149],
        zoom: 5,
        layers: [streets],
        gestureHandling: true,
        fullscreenControl: true,
        preferCanvas: true
      });

      const baseMaps = {
        "Streets": streets,
        "Satellite": satellite
      };

      L.control.layers(baseMaps).addTo(map);

      // Initialize Marker Cluster
      markerCluster = L.markerClusterGroup({
        showCoverageOnHover: false,
        spiderfyOnMaxZoom: true,
        disableClusteringAtZoom: 17
      });
      map.addLayer(markerCluster);

      var locations = @json($lokasiAlat);
      var lowBatSessions = new Set(); // Track sessions currently in low battery state

      // Helper: Create Marker Icon
      const getMarkerIcon = (isFilled = false) => {
        const type = isFilled ? 'ti-map-pin-filled' : 'ti-map-pin';
        const color = '#be1e2d';
        const html =
          `<i class="ti ${type}" style="color: ${color}; font-size: 32px; text-shadow: 0 2px 4px rgba(0,0,0,0.3);"></i>`;
        return L.divIcon({
          html: html,
          className: '',
          iconSize: [32, 32],
          iconAnchor: [16, 32],
          popupAnchor: [0, -32]
        });
      };

      // Function to auto-fit map to all markers (debounced)
      function fitAllMarkers(immediate = false) {
        if (isUserFocusing || trackedSesiId) return;

        clearTimeout(fitTimeout);
        const performFit = () => {
          if (isUserFocusing || trackedSesiId) return;
          var markerArray = Object.values(markers);
          if (markerArray.length > 0) {
            var group = new L.featureGroup(markerArray);
            map.fitBounds(group.getBounds().pad(0.1), {
              animate: true
            });
          }
        };

        if (immediate) performFit();
        else fitTimeout = setTimeout(performFit, 800); // Reduced delay for better responsiveness
      }

      // Tracking state listeners
      map.on('popupopen', function(e) {
        isUserFocusing = true;
        // Find which marker this popup belongs to
        for (let id in markers) {
          if (markers[id].getPopup() === e.popup) {
            // Safety: Ensure any previously tracked marker is reset to outline
            if (trackedSesiId && trackedSesiId !== id && markers[trackedSesiId]) {
              markers[trackedSesiId].setIcon(getMarkerIcon(false));
            }

            trackedSesiId = id;
            markers[id].setIcon(getMarkerIcon(true)); // Set active icon
            break;
          }
        }
      });

      map.on('popupclose', function(e) {
        // Find which marker this popup belongs to
        for (let id in markers) {
          if (markers[id].getPopup() === e.popup) {
            markers[id].setIcon(getMarkerIcon(false)); // Always revert the closed marker to outline

            // Only reset global tracking state if we just closed the currently tracked one
            if (trackedSesiId === id) {
              trackedSesiId = null;
              isUserFocusing = false;
              fitAllMarkers(); // Trigger fit (debounced)
            }
            break;
          }
        }
      });

      // Initialize markers and low battery tracker
      locations.forEach(function(loc) {
        if (loc.is_low_bat && loc.id_sesi) {
          lowBatSessions.add(loc.id_sesi);
        }

        if (loc.lat && loc.long) {
          var customIcon = getMarkerIcon(false); // Default outline icon

          var batLevel = parseInt(loc.battery || 0);
          var batColorClass = batLevel > 70 ? 'text-success' : (batLevel >= 30 ? 'text-warning' : 'text-danger');

          var marker = L.marker([loc.lat, loc.long], {
              icon: customIcon
            })
            .bindPopup(`
              <div class="p-0" style="min-width: 140px;" id="popup-${loc.id_sesi}">
                <div style="border-bottom: 1px solid #eee; padding-bottom: 4px; margin-bottom: 6px;">
                  <div class="fw-bold" style="font-size: 13px; color: #333;">${loc.nama_alat}</div>
                  <div class="text-muted" style="font-size: 10px; margin-top: -2px;">${loc.kendaraan}</div>
                </div>
                <div class="d-flex flex-column gap-1">
                  <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted" style="font-size: 11px;">Baterai</span>
                    <span class="fw-bold bat-text ${batColorClass}" style="font-size: 11px;">${batLevel}%</span>
                  </div>
                  <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted" style="font-size: 11px;">Status</span>
                    <span class="fw-bold ${loc.status === 'Aktif' ? 'text-success' : 'text-danger'}" style="font-size: 11px;">${loc.status}</span>
                  </div>
                  <div class="text-muted text-end mt-1 last-update-text" style="font-size: 9px; opacity: 0.8;">
                    ${loc.last_update}
                  </div>
                </div>
              </div>
            `, {
              minWidth: 140,
              maxWidth: 200,
              autoPanPadding: [50, 50],
              className: 'custom-card-popup'
            });

          markerCluster.addLayer(marker);
          if (loc.id_sesi) {
            markers[loc.id_sesi] = marker;
          }
        }

        // --- Realtime Setup per Active Session ---
        if (loc.id_sesi && loc.status === 'Aktif' && window.Echo) {
          window.Echo.channel(`tracking.sesi.${loc.id_sesi}`)
            .listen('.LokasiBaru', (e) => {
              console.log("Realtime Update for Sesi", loc.id_sesi, e);
              const latLng = [parseFloat(e.lat), parseFloat(e.lng)];
              const battery = parseInt(e.battery || 0);
              const isLowBat = battery <= 20;

              // 1. Update Map Marker
              if (markers[loc.id_sesi]) {
                const marker = markers[loc.id_sesi];
                const isTracked = (loc.id_sesi == trackedSesiId);

                marker.setLatLng(latLng);

                // Update marker in cluster logic
                if (typeof markerCluster.refreshClusters === 'function') {
                  markerCluster.refreshClusters(marker);
                }

                // Update popup content if open
                const popup = marker.getPopup();
                if (isTracked) {
                  // Ensure popup stays open (refreshClusters might close it)
                  if (!popup.isOpen()) {
                    marker.openPopup();
                  }

                  const popupElement = popup.getElement();
                  if (popupElement) {
                    const batText = popupElement.querySelector('.bat-text');
                    const updateText = popupElement.querySelector('.last-update-text');
                    if (batText) {
                      batText.innerText = battery + '%';
                      batText.className = 'fw-bold bat-text ' + (battery > 70 ? 'text-success' : (
                        battery >= 30 ? 'text-warning' : 'text-danger'));
                    }
                    if (updateText) updateText.innerText = 'Sesaat lalu';
                  }

                  // Follow the marker smoothly
                  map.panTo(latLng);
                } else if (!trackedSesiId && !isUserFocusing) {
                  // Trigger debounced fit ONLY if not focusing anyone
                  fitAllMarkers();
                }
              }

              // 2. Update Table Row
              const row = document.getElementById(`row-alat-${loc.id_sesi}`);
              if (row) {
                // Update battery progress bar
                const batCell = row.querySelector('.battery-cell');
                if (batCell) {
                  const bar = batCell.querySelector('.progress-bar');
                  const text = batCell.querySelector('span');
                  const colorClass = battery > 70 ? 'bg-success' : (battery >= 30 ? 'bg-warning' : 'bg-danger');
                  if (bar) {
                    bar.style.width = battery + '%';
                    bar.className = 'progress-bar ' + colorClass;
                  }
                  if (text) text.innerText = battery + '%';
                }

                // Update last update text
                const updateCell = row.querySelector('.update-cell');
                if (updateCell) {
                  const icon = updateCell.querySelector('i') ? updateCell.querySelector('i').outerHTML + ' ' : '';
                  updateCell.innerHTML = icon + 'Sesaat lalu';
                }

                // Handle Low Battery Styling
                if (isLowBat) {
                  row.classList.add('table-danger');
                  const nameCell = row.querySelector('.name-cell');
                  if (nameCell) {
                    nameCell.classList.add('fw-bold', 'text-danger');
                    if (!nameCell.querySelector('.ri-error-warning-fill')) {
                      nameCell.insertAdjacentHTML('afterbegin', '<i class="ri-error-warning-fill me-1"></i>');
                    }
                  }
                } else {
                  row.classList.remove('table-danger');
                  const nameCell = row.querySelector('.name-cell');
                  if (nameCell) {
                    nameCell.classList.remove('fw-bold', 'text-danger');
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

              // Update Alert and Card
              const currentCount = lowBatSessions.size;
              const countDisplay = document.getElementById('count-low-bat');
              const cardLowBat = document.getElementById('card-low-bat');

              if (countDisplay) {
                countDisplay.innerText = currentCount;
                countDisplay.className = 'mb-0 ' + (currentCount > 0 ? 'text-danger' : '');
              }
              if (cardLowBat) {
                if (currentCount > 0) {
                  cardLowBat.classList.add('border-danger');
                  cardLowBat.style.cursor = 'pointer';
                  cardLowBat.onclick = () => document.getElementById('daftar-alat-table').scrollIntoView({
                    behavior: 'smooth'
                  });
                  cardLowBat.title = 'Klik untuk melihat detail alat dengan baterai lemah';
                } else {
                  cardLowBat.classList.remove('border-danger');
                  cardLowBat.style.cursor = 'default';
                  cardLowBat.onclick = null;
                  cardLowBat.title = '';
                }
              }

              // Note: Alert box visibility is harder to toggle perfectly without a container, 
              // but we can update the count inside it if it exists.
              const alertBox = document.querySelector('.alert-danger b');
              if (alertBox) alertBox.innerText = currentCount;
            });
        }
      });

      // Fit map bounds
      var markerArray = Object.values(markers);
      if (markerArray.length > 0) {
        var group = new L.featureGroup(markerArray);
        map.fitBounds(group.getBounds().pad(0.1));
      }

      // Fix map rendering issues
      setTimeout(function() {
        map.invalidateSize();
      }, 500);
    });
  </script>
@endsection
