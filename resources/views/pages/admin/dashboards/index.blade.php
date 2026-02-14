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
  </style>
@endsection

@section('content')
  <!-- Start::page-header -->
  <div class="d-flex align-items-center justify-content-between mb-3 page-header-breadcrumb flex-wrap gap-2">
    <div>
      <h1 class="page-title fw-medium fs-20 mb-0">Selamat datang, {{ auth()->user()->name }}.</h1>
      <p class="text-muted mb-0">Dashboard YA-Track</p>
    </div>
  </div>
  <!-- End::page-header -->

  <!-- Start::app-content -->
  <div class="row g-2">
    <!-- Statistics Cards -->
    <div class="col-6 col-md-6 col-lg-3">
      <div class="card stats-card">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <h6 class="card-title text-muted mb-2">Sesi Aktif</h6>
              <h3 class="mb-0">{{ $totalSesiAlatAktif }}</h3>
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

  <div class="row g-2 mt-1">
    <!-- Active Sessions -->
    <div class="col-12">
      <div class="card h-100">
        <div class="card-header border-bottom">
          <div class="card-title mb-0">Sesi Aktif</div>
          <p class="text-muted mb-0 small">Perangkat yang sedang aktif melacak</p>
        </div>
        <div class="card-body p-0">
          @if ($activeSessions->count() > 0)
            <div class="table-responsive mb-0">
              <table class="table table-hover mb-0">
                <thead>
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
        <div class="card-footer border-top mt-auto">
          <a href="{{ route('sesi-alat.index') }}" class="btn btn-sm btn-outline-primary">
            Lihat Semua <i class="ri-arrow-right-line ms-1"></i>
          </a>
        </div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="col-xl-6 col-lg-6 col-md-12">
      <div class="card h-100">
        <div class="card-header">
          <div class="card-title">Statistik Perjalanan (7 Hari Terakhir)</div>
        </div>
        <div class="card-body p-0 chart-container-fixed">
          <div id="travel-chart"></div>
        </div>
      </div>
    </div>
    <div class="col-xl-6 col-lg-6 col-md-12">
      <div class="card h-100">
        <div class="card-header">
          <div class="card-title">Distribusi Kendaraan per Perusahaan</div>
        </div>
        <div class="card-body p-0 chart-container-fixed d-flex align-items-center justify-content-center">
          <div id="company-chart" class="w-100"></div>
        </div>
      </div>
    </div>

    <!-- Activity Logs -->
    <div class="col-xl-6 col-lg-12">
      <div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <div class="card-title mb-0">Log Aktivitas Terbaru</div>
          <a href="{{ route('logs.index') }}" class="btn btn-sm btn-primary-transparent">
            Lihat Semua <i class="ri-arrow-right-line ms-1"></i>
          </a>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead>
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
    <div class="col-xl-6 col-lg-12">
      <div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <div class="card-title mb-0">Tracking Tidak Lengkap</div>
          <a href="{{ route('tracking-tidak-lengkap.index') }}" class="btn btn-sm btn-danger-transparent">
            Lihat Semua <i class="ri-arrow-right-line ms-1"></i>
          </a>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead>
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
  <!-- Apex Charts JS -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Travel History Chart
      var travelOptions = {
        series: [{
          name: 'Jumlah Perjalanan',
          data: @json($riwayatChart->pluck('count'))
        }],
        chart: {
          height: 380,
          width: '100%',
          type: 'area',
          toolbar: {
            show: false
          },
          sparkline: {
            enabled: false
          },
          parentHeightOffset: 0
        },
        grid: {
          borderColor: '#f1f1f1',
          padding: {
            top: 10,
            right: 0,
            bottom: 0,
            left: 0
          }
        },
        colors: ['#be1e2d'],
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth',
          width: 2
        },
        xaxis: {
          type: 'category',
          categories: @json($riwayatChart->pluck('date')),
          labels: {
            formatter: function(val) {
              return new Date(val).toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'short'
              });
            },
            offsetY: 0
          },
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false
          }
        },
        yaxis: {
          show: true,
          labels: {
            offsetX: 0
          }
        },
        tooltip: {
          x: {
            format: 'dd MMM yyyy'
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
        colors: ['#be1e2d', '#28a745', '#ffc107', '#17a2b8', '#6610f2'],
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
    });
  </script>
@endsection
