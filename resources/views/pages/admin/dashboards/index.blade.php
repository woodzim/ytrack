@extends('layouts.master')

@section('styles')
  <style>
    @media (max-width: 575.98px) {
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

      .stats-icon {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .stats-icon i {
        font-size: 1.25rem !important;
      }

      .page-header-breadcrumb {
        margin-bottom: 0.75rem !important;
      }
    }

    /* Fixed height for chart containers to keep dashboard uniform */
    .chart-container-fixed {
      min-height: 380px;
      position: relative;
      width: 100%;
    }

    #map-admin {
      height: 55vh;
      width: 100% !important;
      border-radius: 8px;
      position: relative;
      z-index: 1;
    }

    .leaflet-top,
    .leaflet-bottom {
      z-index: 400 !important;
    }
  </style>
@endsection

@section('content')
  <!-- Start::page-header -->
  <div class="d-flex align-items-center justify-content-between mb-3 page-header-breadcrumb flex-wrap gap-2">
    <div>
      <h1 class="page-title fw-medium fs-20 mb-0">Beranda Admin YA-Track</h1>
      <p class="text-muted mb-0">Dashboard Monitoring Seluruh Alat YA-Track</p>
    </div>
  </div>
  <!-- End::page-header -->

  <!-- Alert Section -->
  <div id="low-bat-alert-container">
    {{-- Diisi via JS Realtime jika ada --}}
  </div>

  <!-- Start::app-content -->
  <div class="row g-2">
    <!-- Statistics Cards -->
    <div class="col-6 col-md-6 col-lg-3">
      <div class="card stats-card">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <h6 class="card-title text-muted mb-2">Sesi Aktif</h6>
              <h3 class="mb-0" id="stat-active-sessions">{{ $totalSesiAlatAktif }}</h3>
            </div>
            <div class="stats-icon bg-success-transparent">
              <i class="ri-check-double-line fs-24 text-success"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-6 col-md-6 col-lg-3">
      <div class="card stats-card">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <h6 class="card-title text-muted mb-2">Total Alat GPS</h6>
              <h3 class="mb-0">{{ $totalAlat }}</h3>
            </div>
            <div class="stats-icon bg-info-transparent">
              <i class="ri-radar-line fs-24 text-info"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-6 col-md-6 col-lg-3">
      <div class="card stats-card">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <h6 class="card-title text-muted mb-2">Total Kendaraan</h6>
              <h3 class="mb-0">{{ $totalKendaraan }}</h3>
            </div>
            <div class="stats-icon bg-warning-transparent">
              <i class="ri-taxi-line fs-24 text-warning"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-6 col-md-6 col-lg-3">
      <div class="card stats-card">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <h6 class="card-title text-muted mb-2">Total Perusahaan</h6>
              <h3 class="mb-0">{{ $totalPerusahaan }}</h3>
            </div>
            <div class="stats-icon bg-primary-transparent">
              <i class="ri-building-4-line fs-24 text-primary"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Map Section -->
  <div class="row g-2 mt-1">
    <div class="col-12">
      <div class="card overflow-hidden">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <div class="card-title mb-0 fw-bold fs-14 text-uppercase ls-1">
            <i class="ri-map-pin-2-fill me-2 text-primary"></i>Monitoring Lokasi Alat Terkini (Seluruh Perusahaan)
          </div>
          <span class="badge bg-primary-transparent text-primary px-3 rounded-pill">{{ count($lokasiAlat) }} Alat
            Terdeteksi</span>
        </div>
        <div class="card-body p-0">
          <div id="map-admin"></div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-2 mt-1">
    <!-- Active Sessions -->
    <div class="col-12">
      <div class="card h-100">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <div>
            <h5 class="card-title mb-0">Sesi Perjalanan Aktif</h5>
            <p class="text-muted mb-0 small">Perangkat yang sedang aktif melacak secara real-time</p>
          </div>
          <div class="card-footer border-top mt-auto">
            <a href="{{ route('sesi-alat.index') }}" class="btn btn-sm btn-outline-primary">
              Lihat Semua <i class="ri-arrow-right-line ms-1"></i>
            </a>
          </div>

        </div>
        <div class="card-body p-0">
          @if ($activeSessions->count() > 0)
            <div class="table-responsive mb-0">
              <table class="table table-hover mb-0">
                <thead class="bg-light">
                  <tr>
                    <th class="ps-4">Alat ID</th>
                    <th>Kendaraan</th>
                    <th>Perusahaan</th>
                    <th class="pe-4">Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($activeSessions->take(5) as $session)
                    <tr>
                      <td class="ps-4">
                        <span class="badge bg-primary">{{ $session->alat?->id ?? '-' }}</span>
                      </td>
                      <td>{{ $session->kendaraan?->nama ?? '-' }}</td>
                      <td>{{ $session->perusahaan?->nama ?? '-' }}</td>
                      <td class="pe-4">
                        <span class="badge bg-success">
                          <i class="ri-checkbox-circle-line"></i> Aktif
                        </span>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="alert alert-info mb-0 m-3">
              <i class="ri-information-line me-2"></i>
              Tidak ada perjalanan yang aktif saat ini
            </div>
          @endif
        </div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="col-12">
      <div class="mb-3">
        <h5 class="fw-semibold mb-0">Statistik Perjalanan (7 Hari Terakhir)</h5>
      </div>
      <div class="chart-container-fixed" id="travel-chart-container">
        <div id="travel-chart"></div>
      </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-12">
      <div class="card h-100">
        <div class="card-header">
          <h5 class="card-title fw-semibold mb-0">Distribusi Kendaraan per Perusahaan</h5>
        </div>
        <div class="card-body p-0 chart-container-fixed d-flex align-items-center justify-content-center">
          <div id="company-chart" class="w-100"></div>
        </div>
      </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-12">
      <div class="card h-100">
        <div class="card-header">
          <h5 class="card-title fw-semibold mb-0">Status Baterai Semua Alat</h5>
        </div>
        <div class="card-body p-0 chart-container-fixed d-flex align-items-center justify-content-center">
          <div id="battery-chart" class="w-100"></div>
        </div>
      </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-12">
      <div class="card h-100">
        <div class="card-header">
          <h5 class="card-title fw-semibold mb-0">Penggunaan Semua Alat</h5>
        </div>
        <div class="card-body p-0 chart-container-fixed d-flex align-items-center justify-content-center">
          <div id="tool-usage-chart" class="w-100"></div>
        </div>
      </div>
    </div>

    <!-- Activity Logs -->
    <div class="col-12">
      <div class="card h-100">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">Log Aktivitas Terbaru</h5>
          <a href="{{ route('logs.index') }}" class="btn btn-sm btn-primary-transparent">
            Lihat Semua <i class="ri-arrow-right-line ms-1"></i>
          </a>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="bg-light">
                <tr>
                  <th class="ps-4">User</th>
                  <th>Aksi</th>
                  <th>Waktu</th>
                </tr>
              </thead>
              <tbody>
                @forelse($activityLogs as $log)
                  <tr>
                    <td class="ps-4">
                      <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm me-2 bg-primary-transparent">
                          {{ substr($log->user?->name ?? '?', 0, 1) }}
                        </div>
                        <div>
                          <p class="mb-0 fw-medium">{{ $log->user?->name ?? 'System' }}</p>
                        </div>
                      </div>
                    </td>
                    <td>
                      <span class="badge bg-light text-dark border">{{ $log->action }}</span>
                      <small class="d-block text-muted">{{ Str::limit($log->description, 30) }}</small>
                    </td>
                    <td>
                      <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="3" class="text-center p-3 text-muted">Tidak ada log aktivitas</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Error Tracking (Incomplete) -->
    <div class="col-12">
      <div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <div class="card-title mb-0">Tracking Tidak Lengkap</div>
          <a href="{{ route('tracking-tidak-lengkap.index') }}" class="btn btn-sm btn-primary-transparent">
            Lihat Semua <i class="ri-arrow-right-line ms-1"></i>
          </a>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="bg-light">
                <tr>
                  <th class="ps-4">Alat/Kendaraan</th>
                  <th>Perusahaan</th>
                  <th>Waktu</th>
                </tr>
              </thead>
              <tbody>
                @forelse($errorTrackings as $error)
                  <tr>
                    <td class="ps-4">
                      <p class="mb-0 fw-medium">{{ $error->kendaraan?->nama ?? 'N/A' }}</p>
                      <small class="text-muted">{{ $error->alat?->id ?? 'Unknown' }}</small>
                    </td>
                    <td>{{ $error->perusahaan?->nama ?? '-' }}</td>
                    <td>
                      <small class="text-muted">{{ $error->created_at->diffForHumans() }}</small>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="3" class="text-center p-3 text-muted">Tidak ada data tracking bermasalah</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End::app-content -->
@endsection

@section('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // --- MAP LOGIC ---
      let markers = {};
      let map;
      let markerCluster;

      const streets = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; CARTO',
        maxZoom: 18
      });

      map = L.map('map-admin', {
        center: [-2.5489, 118.0149],
        zoom: 5,
        layers: [streets],
        gestureHandling: true,
        fullscreenControl: {
          position: 'bottomright'
        },
        preferCanvas: true,
        attributionControl: false
      });

      markerCluster = L.markerClusterGroup({
        showCoverageOnHover: false,
        spiderfyOnMaxZoom: true,
        disableClusteringAtZoom: 17
      });
      map.addLayer(markerCluster);

      const locations = @json($lokasiAlat);

      const getMarkerIcon = (isActive = true) => {
        const color = isActive ? '#09637E' : '#64748b';
        const html = `<div style="background: white; border-radius: 50%; padding: 2px; box-shadow: 0 2px 5px rgba(0,0,0,0.3); border: 2px solid ${color}; display: flex; align-items: center; justify-content: center; width: 32px; height: 32px;">
                        <i class="ri-map-pin-line" style="color: ${color}; font-size: 20px;"></i>
                      </div>`;
        return L.divIcon({
          html: html,
          className: '',
          iconSize: [32, 32],
          iconAnchor: [16, 32],
          popupAnchor: [0, -32]
        });
      };

      const getBatteryHtml = (level) => {
        const levelInt = parseInt(level || 0);
        let color = '#be1e2d'; // Keeping low battery red for danger indication
        if (levelInt > 70) color = '#10b981';
        else if (levelInt > 30) color = '#f59e0b';

        return `<div class="d-flex align-items-center gap-2">
                  <div style="position: relative; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: radial-gradient(closest-side, white calc(100% - 3px), transparent calc(100% - 3px + 0.5px)), conic-gradient(${color} ${levelInt}%, #e9ecef 0);">
                    <span style="font-size: 8px; font-weight: 700; color: ${color}; z-index: 1;">${levelInt}%</span>
                  </div>
                </div>`;
      };

      const lowBatSessions = new Set();
      locations.forEach(function(loc) {
        if (loc.is_low_bat && loc.id_sesi) lowBatSessions.add(String(loc.id_sesi));

        if (loc.lat && loc.long) {
          const marker = L.marker([loc.lat, loc.long], {
              icon: getMarkerIcon(loc.status === 'Aktif')
            })
            .bindPopup(`<div class="p-1" id="popup-${loc.id_sesi}">
                          <div class="fw-bold mb-1">${loc.nama_alat}</div>
                          <div class="badge bg-primary-transparent text-primary mb-2">${loc.perusahaan}</div>
                          <div class="text-muted small mb-2">Kendaraan: ${loc.kendaraan}</div>
                          <div class="d-flex justify-content-between align-items-center border-top pt-2">
                            <span class="small">Baterai:</span>
                            <div class="battery-popup-container">${getBatteryHtml(loc.battery)}</div>
                          </div>
                          <div class="text-muted text-end mt-1 last-update-text" style="font-size: 9px;">
                            ${loc.last_update}
                          </div>
                        </div>`, {
              minWidth: 150
            });

          markerCluster.addLayer(marker);
          if (loc.id_sesi) markers[loc.id_sesi] = marker;
        }

        // --- REALTIME ECHO LISTENER ---
        if (loc.id_sesi && loc.status === 'Aktif' && window.Echo) {
          window.Echo.channel(`tracking.sesi.${loc.id_sesi}`)
            .listen('.LokasiBaru', (e) => {
              const latLng = [parseFloat(e.lat), parseFloat(e.lng)];
              const battery = parseInt(e.battery || 0);
              const isLowBat = battery <= 20;

              // 1. Update Map Marker
              if (markers[loc.id_sesi]) {
                const marker = markers[loc.id_sesi];
                marker.setLatLng(latLng);
                if (typeof markerCluster.refreshClusters === 'function') {
                  markerCluster.refreshClusters(marker);
                }

                // Update popup if open
                const popup = marker.getPopup();
                if (popup && popup.isOpen()) {
                  const popupElement = popup.getElement();
                  if (popupElement) {
                    const batContainer = popupElement.querySelector('.battery-popup-container');
                    const updateText = popupElement.querySelector('.last-update-text');
                    if (batContainer) batContainer.innerHTML = getBatteryHtml(battery);
                    if (updateText) updateText.innerText = 'Sesaat lalu';
                  }
                }
              }

              // 2. Update Low Bat Logic
              const sesiIdStr = String(loc.id_sesi);
              if (isLowBat) lowBatSessions.add(sesiIdStr);
              else lowBatSessions.delete(sesiIdStr);

              updateLowBatAlert(lowBatSessions.size);
            });
        }
      });

      function updateLowBatAlert(count) {
        const container = document.getElementById('low-bat-alert-container');
        if (count > 0) {
          container.innerHTML = `<div class="alert alert-danger d-flex align-items-center mb-3 shadow-sm border-0" role="alert">
            <i class="ri-error-warning-fill fs-20 me-2"></i>
            <div>
              <strong>Peringatan!</strong> Terdapat <b>${count}</b> perangkat dengan baterai lemah (<20%).
            </div>
          </div>`;
        } else {
          container.innerHTML = '';
        }
      }

      // Initial check
      if (lowBatSessions.size > 0) updateLowBatAlert(lowBatSessions.size);

      if (Object.keys(markers).length > 0) {
        map.fitBounds(markerCluster.getBounds().pad(0.1), {
          maxZoom: 18
        });
      }

      setTimeout(() => {
        map.invalidateSize();
      }, 500);

      // --- DATA CALCULATION FOR NEW CHARTS ---
      let batteryStats = {
        good: 0,
        moderate: 0,
        low: 0
      };
      let toolStatusStats = {
        active: 0,
        idle: 0
      };

      locations.forEach(loc => {
        // Battery
        let bat = parseInt(loc.battery || 0);
        if (bat > 70) batteryStats.good++;
        else if (bat > 30) batteryStats.moderate++;
        else batteryStats.low++;

        // Status
        if (loc.status === 'Aktif') toolStatusStats.active++;
        else toolStatusStats.idle++;
      });

      // Travel History Chart
      const travelData = @json($last7Days);

      var travelOptions = {
        series: [{
          name: 'Jumlah Perjalanan',
          data: travelData.map(item => item.count)
        }],
        chart: {
          height: 380,
          width: '100%',
          type: 'area',
          toolbar: {
            show: false
          },
          zoom: {
            enabled: false
          },
          fontFamily: 'inherit',
          animations: {
            enabled: true
          },
          sparkline: {
            enabled: false
          },
          parentHeightOffset: 0
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth',
          width: 3,
          colors: ['#09637E']
        },
        fill: {
          type: 'gradient',
          gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.4,
            opacityTo: 0.1,
            stops: [0, 90, 100],
            colorStops: [{
              offset: 0,
              color: '#09637E',
              opacity: 0.4
            }]
          }
        },
        xaxis: {
          type: 'category',
          categories: travelData.map(d => new Date(d.date).toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'short'
          })),
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false
          },
          labels: {
            style: {
              colors: '#64748b',
              fontWeight: 500
            }
          },
          tooltip: {
            enabled: false
          }
        },
        yaxis: {
          show: true,
          labels: {
            style: {
              colors: '#64748b',
              fontWeight: 500
            }
          }
        },
        grid: {
          borderColor: '#f1f1f1',
          strokeDashArray: 4,
          padding: {
            left: 20,
            right: 20,
            top: 0,
            bottom: 0
          }
        },
        tooltip: {
          theme: 'light',
          x: {
            show: true
          }
        }
      };
      var travelChart = new ApexCharts(document.querySelector("#travel-chart"), travelOptions);
      travelChart.render();

      // Company Distribution Chart
      var companyOptions = {
        series: @json($perusahaanChart->pluck('kendaraan_count')),
        chart: {
          height: 380,
          width: '100%',
          type: 'donut',
          parentHeightOffset: 0
        },
        labels: @json($perusahaanChart->pluck('nama')),
        colors: ['#09637E', '#088395', '#7AB2B2', '#28a745', '#ffc107'],
        legend: {
          show: true,
          position: 'bottom',
          horizontalAlign: 'center',
          offsetY: 0
        },
        plotOptions: {
          pie: {
            donut: {
              size: '65%',
              labels: {
                show: true,
                total: {
                  show: true,
                  label: 'Total',
                  formatter: function(w) {
                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                  }
                }
              }
            }
          }
        },
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: '100%'
            },
            legend: {
              position: 'bottom'
            }
          }
        }]
      };
      var companyChart = new ApexCharts(document.querySelector("#company-chart"), companyOptions);
      companyChart.render();

      // Battery Status Chart
      var batteryOptions = {
        series: [batteryStats.good, batteryStats.moderate, batteryStats.low],
        chart: {
          type: 'donut',
          height: 280,
          width: '100%'
        },
        labels: ['Bagus (>70%)', 'Cukup (30-70%)', 'Lemah (<30%)'],
        colors: ['#10b981', '#f59e0b', '#ef4444'],
        legend: {
          position: 'bottom',
          fontWeight: 600
        },
        plotOptions: {
          pie: {
            donut: {
              size: '65%',
              labels: {
                show: true,
                total: {
                  show: true,
                  label: 'Total',
                  fontWeight: 700,
                  color: '#1e293b',
                  formatter: () => locations.length
                }
              }
            }
          }
        },
        dataLabels: {
          enabled: false
        }
      };
      var batteryChart = new ApexCharts(document.querySelector("#battery-chart"), batteryOptions);
      batteryChart.render();

      // Tool Usage Chart
      var toolStatusOptions = {
        series: [toolStatusStats.active, toolStatusStats.idle],
        chart: {
          type: 'donut',
          height: 300,
          width: '100%'
        },
        labels: ['Aktif', 'Nonaktif'],
        colors: ['#f59e0b', '#cbd5e1'],
        legend: {
          position: 'bottom',
          fontWeight: 600
        },
        plotOptions: {
          pie: {
            donut: {
              size: '65%',
              labels: {
                show: true,
                total: {
                  show: true,
                  label: 'Total',
                  formatter: () => locations.length
                }
              }
            }
          }
        },
        dataLabels: {
          enabled: false
        }
      };
      var toolUsageChart = new ApexCharts(document.querySelector("#tool-usage-chart"), toolStatusOptions);
      toolUsageChart.render();
    });
  </script>
@endsection
