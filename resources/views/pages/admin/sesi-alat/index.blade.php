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

    /* Memungkinkan konten dropdown keluar dari container kartu */
    #table-container,
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
      <div class="card mb-4 border-0 shadow-none">
        <div class="p-0">
          <form action="{{ route('perjalanan.index') }}" method="GET" id="filterForm"
            class="d-flex gap-3 flex-wrap align-items-end">
            <div class="grow" style="min-width: 250px;">
              <label for="searchInput" class="form-label fw-medium mb-2">Cari Sesi</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="ri-search-line"></i></span>
                <input type="text" id="searchInput" name="search" class="form-control border-start-0 ps-0"
                  placeholder="Cari alat atau kendaraan..." value="{{ $search }}" autocomplete="off"
                  style="height: 40px;">
              </div>
            </div>

            <div style="min-width: 220px;">
              <label class="form-label fw-medium mb-2">Perusahaan</label>
              <input type="hidden" name="id_perusahaan" id="id_perusahaan_input" value="{{ $filter_perusahaan }}">
              <div class="dropdown w-100">
                <button
                  class="btn btn-light border w-100 d-flex justify-content-between align-items-center dropdown-toggle"
                  type="button" data-bs-toggle="dropdown" aria-expanded="false" id="id_perusahaanDropdown"
                  style="height: 40px;">
                  <span class="text-truncate">
                    @php $selectedP = $perusahaan->firstWhere('id', $filter_perusahaan); @endphp
                    {{ $selectedP ? $selectedP->nama : 'Semua Perusahaan' }}
                  </span>
                </button>
                <ul class="dropdown-menu shadow-sm w-100" style="max-height: 300px; overflow-y: auto;">
                  <li><a class="dropdown-item" href="#"
                      onclick="selectFilter('id_perusahaan', '', 'Semua Perusahaan')">Semua Perusahaan</a></li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  @foreach ($perusahaan as $p)
                    <li><a class="dropdown-item" href="#"
                        onclick="selectFilter('id_perusahaan', '{{ $p->id }}', '{{ $p->nama }}')">{{ $p->nama }}</a>
                    </li>
                  @endforeach
                </ul>
              </div>
            </div>

            <div style="min-width: 180px;">
              <label class="form-label fw-medium mb-2">Status</label>
              <input type="hidden" name="status" id="status_input" value="{{ $filter_status }}">
              <div class="dropdown w-100">
                <button
                  class="btn btn-light border w-100 d-flex justify-content-between align-items-center dropdown-toggle"
                  type="button" data-bs-toggle="dropdown" aria-expanded="false" id="statusDropdown"
                  style="height: 40px;">
                  <span>
                    @if ($filter_status === '1')
                      Aktif
                    @elseif($filter_status === '0')
                      Nonaktif
                    @else
                      Semua Status
                    @endif
                  </span>
                </button>
                <ul class="dropdown-menu shadow-sm w-100">
                  <li><a class="dropdown-item" href="#" onclick="selectFilter('status', '', 'Semua Status')">Semua
                      Status</a></li>
                  <li><a class="dropdown-item" href="#" onclick="selectFilter('status', '1', 'Aktif')">Aktif</a>
                  </li>
                  <li><a class="dropdown-item" href="#"
                      onclick="selectFilter('status', '0', 'Nonaktif')">Nonaktif</a></li>
                </ul>
              </div>
            </div>

            <div id="reset-container"
              class="{{ $search || $filter_status !== '' || $filter_perusahaan !== '' ? 'd-flex' : 'd-none' }}">
              <a href="{{ route('perjalanan.index') }}"
                class="btn btn-outline-secondary btn-wave d-flex align-items-center px-3" id="resetBtn"
                style="height: 40px;">
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
