@extends('layouts.master')

@section('styles')
  <style>
    /* Memberikan ruang di bawah tabel agar dropdown tidak terpotong */
    .table-responsive {
      padding-bottom: 80px !important;
      margin-bottom: -80px !important;
      overflow-x: auto !important;
      overflow-y: visible !important;
    }

    /* Force parent containers to allow overflow so dropdowns aren't cut off */
    .table-wrapper,
    .table-wrapper .card-body {
      overflow: visible !important;
    }
  </style>
@endsection

@section('content')
  <div class="page-header-section d-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="page-title fw-medium fs-20 mb-0">Perjalanan</h1>
      <p class="">Kelola sesi penggunaan alat GPS di kendaraan</p>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <!-- Filter Section -->
      <div class="card custom-card">
        <div class="card-body p-0">
          <form action="{{ route('perjalanan.index') }}" method="GET" id="filterForm"
            class="d-flex flex-column flex-md-row gap-3 align-items-md-end border-bottom">
            <div class="flex-grow-1" style="min-width: 250px;">
              <label for="searchInput" class="form-label fw-semibold mb-1 text-muted fs-12 text-uppercase">Cari
                Sesi</label>
              <div class="input-group">
                <span class="input-group-text bg-white border-end-0 border-light"><i class="ri-search-line"></i></span>
                <input type="text" id="searchInput" name="search"
                  class="form-control border-start-0 border-light bg-white" placeholder="Cari alat atau kendaraan..."
                  value="{{ $search }}" autocomplete="off" style="height: 40px;">
              </div>
            </div>

            <div style="min-width: 220px;">
              <label class="form-label fw-semibold mb-1 text-muted fs-12 text-uppercase">Perusahaan</label>
              <input type="hidden" name="id_perusahaan" id="id_perusahaan_input" value="{{ $filter_perusahaan }}">
              <div class="dropdown w-100">
                <button class="btn bg-white border text-dark w-100 d-flex justify-content-between align-items-center"
                  type="button" data-bs-toggle="dropdown" aria-expanded="false" id="id_perusahaanDropdown"
                  style="height: 40px;">
                  <div class="d-flex align-items-center text-truncate">
                    <i class="ri-building-4-line me-2 text-muted flex-shrink-0"></i>
                    <span class="fw-medium text-truncate">
                      @php $selectedP = $perusahaan->firstWhere('id', $filter_perusahaan); @endphp
                      {{ $selectedP ? $selectedP->nama : 'Semua Perusahaan' }}
                    </span>
                  </div>
                  <i class="ri-arrow-down-s-line text-muted ms-2"></i>
                </button>
                <ul class="dropdown-menu shadow-sm border-0 mt-1 w-100" style="max-height: 300px; overflow-y: auto;">
                  <li><a
                      class="dropdown-item py-2 {{ $filter_perusahaan === '' ? 'active bg-primary-transparent text-primary' : '' }}"
                      href="#" onclick="selectFilter('id_perusahaan', '', 'Semua Perusahaan')">Semua Perusahaan</a>
                  </li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  @foreach ($perusahaan as $p)
                    <li><a
                        class="dropdown-item py-2 {{ $filter_perusahaan == $p->id ? 'active bg-primary-transparent text-primary' : '' }}"
                        href="#"
                        onclick="selectFilter('id_perusahaan', '{{ $p->id }}', '{{ $p->nama }}')">{{ $p->nama }}</a>
                    </li>
                  @endforeach
                </ul>
              </div>
            </div>

            <div style="min-width: 180px;">
              <label class="form-label fw-semibold mb-1 text-muted fs-12 text-uppercase">Status</label>
              <input type="hidden" name="status" id="status_input" value="{{ $filter_status }}">
              <div class="dropdown w-100">
                <button class="btn bg-white border text-dark w-100 d-flex justify-content-between align-items-center"
                  type="button" data-bs-toggle="dropdown" aria-expanded="false" id="statusDropdown"
                  style="height: 40px;">
                  <div class="d-flex align-items-center">
                    <i class="ri-filter-3-line me-2 text-muted"></i>
                    <span class="fw-medium">
                      @if ($filter_status === '1')
                        Aktif
                      @elseif($filter_status === '0')
                        Nonaktif
                      @else
                        Semua Status
                      @endif
                    </span>
                  </div>
                  <i class="ri-arrow-down-s-line text-muted"></i>
                </button>
                <ul class="dropdown-menu shadow-sm border-0 mt-1 w-100">
                  <li><a
                      class="dropdown-item py-2 {{ $filter_status === '' ? 'active bg-primary-transparent text-primary' : '' }}"
                      href="#" onclick="selectFilter('status', '', 'Semua Status')">Semua Status</a></li>
                  <li><a
                      class="dropdown-item py-2 {{ $filter_status === '1' ? 'active bg-success-transparent text-success' : '' }}"
                      href="#" onclick="selectFilter('status', '1', 'Aktif')"><i
                        class="ri-checkbox-circle-line me-2"></i>Aktif</a></li>
                  <li><a
                      class="dropdown-item py-2 {{ $filter_status === '0' ? 'active bg-danger-transparent text-danger' : '' }}"
                      href="#" onclick="selectFilter('status', '0', 'Nonaktif')"><i
                        class="ri-close-circle-line me-2"></i>Nonaktif</a></li>
                </ul>
              </div>
            </div>

            <div id="reset-container"
              class="{{ $search || $filter_status !== '' || $filter_perusahaan !== '' ? 'd-flex' : 'd-none' }}">
              <a href="{{ route('perjalanan.index') }}"
                class="btn btn-danger-transparent btn-wave d-flex align-items-center justify-content-center"
                id="resetBtn" style="height: 40px; min-width: 100px;">
                <i class="ri-refresh-line me-2"></i>Reset
              </a>
            </div>
          </form>
        </div>
      </div>

      <div id="table-container">
        @include('pages.admin.sesi-alat.table')
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const filterForm = document.getElementById('filterForm');
      const tableContainer = document.getElementById('table-container');
      const resetContainer = document.getElementById('reset-container');
      let debounceTimer;

      const fetchTable = (url) => {
        tableContainer.style.opacity = '0.5';
        fetch(url, {
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            }
          })
          .then(res => res.text())
          .then(html => {
            tableContainer.innerHTML = html;
            tableContainer.style.opacity = '1';
            formatDates();
            if (typeof Waves !== 'undefined') Waves.init();
          });
      };

      const triggerFetch = () => {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData).toString();
        const url = `${filterForm.action}?${params}`;

        const search = formData.get('search');
        const id_perusahaan = formData.get('id_perusahaan');
        const status = formData.get('status');

        if ((search && search.trim() !== "") || id_perusahaan !== "" || status !== "") {
          resetContainer.classList.remove('d-none');
          resetContainer.classList.add('d-flex');
        } else {
          resetContainer.classList.add('d-none');
          resetContainer.classList.remove('d-flex');
        }

        window.history.pushState({}, '', url);
        fetchTable(url);
      };

      window.selectFilter = function(type, value, label) {
        document.getElementById(type + '_input').value = value;
        const btn = document.getElementById(type + 'Dropdown');
        btn.querySelector('span').textContent = label;
        triggerFetch();
      };

      document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(triggerFetch, 500);
      });

      tableContainer.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination a');
        if (link) {
          e.preventDefault();
          window.history.pushState({}, '', link.href);
          fetchTable(link.href);
          tableContainer.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });

      function formatDates() {
        document.querySelectorAll('.local-date').forEach(el => {
          const date = new Date(el.textContent.trim());
          el.textContent = new Intl.DateTimeFormat('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
          }).format(date);
        });
      }

      document.getElementById('resetBtn').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('searchInput').value = '';
        document.getElementById('id_perusahaan_input').value = '';
        document.getElementById('status_input').value = '';
        document.getElementById('id_perusahaanDropdown').querySelector('span').textContent = 'Semua Perusahaan';
        document.getElementById('statusDropdown').querySelector('span').textContent = 'Semua Status';
        resetContainer.classList.add('d-none');
        resetContainer.classList.remove('d-flex');
        window.history.pushState({}, '', this.href);
        fetchTable(this.href);
      });

      formatDates();
    });
  </script>
@endsection
