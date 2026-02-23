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

    /* Memastikan card tidak memotong konten yang keluar (seperti dropdown) */
    .custom-card {
      overflow: visible !important;
    }

    .custom-card .card-body {
      overflow: visible !important;
    }

    /* Hide the default Bootstrap caret */
    .hide-arrow::after {
      display: none !important;
    }

    /* Standardize the custom dropdown button layout */
    .custom-filter-dropdown {
      height: 38px;
      border: 1px solid #dee2e6;
      background-color: #fff;
      display: flex !important;
      align-items: center !important;
      justify-content: space-between !important;
      padding: 0.375rem 0.75rem;
      border-radius: 0.25rem;
      width: 100%;
      transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
      text-align: left;
    }

    .custom-filter-dropdown:hover {
      border-color: #b1b5b9;
    }

    .custom-filter-dropdown:focus {
      border-color: #86b7fe;
      outline: 0;
      box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
  </style>
@endsection

@section('content')
  <div class="page-header-section d-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="page-title fw-medium fs-20 mb-0">Perjalanan</h1>
      <p class="">Kelola sesi penggunaan alat GPS di kendaraan</p>
    </div>
    <div class="ms-auto">
      {{-- TOMBOL BUAT SESI --}}
      <a href="{{ route('perusahaan.sesi-alat.create') }}" class="btn btn-light btn-wave">
        <i class="ri-add-line me-2"></i>Buat Sesi Baru
      </a>
    </div>
  </div>
  <div class="row">
    <div class="col-12">

      <div class="card mb-0 border-0 shadow-none bg-transparent">
        <div class="p-0">
          <form action="{{ route('perusahaan.sesi-alat.index') }}" method="GET" id="filterForm"
            class="d-flex gap-3 flex-wrap align-items-end">

            {{-- SEARCH INPUT --}}
            <div class="grow" style="min-width: 250px;">
              <label for="searchInput" class="form-label fw-medium mb-2">Cari Sesi</label>
              <div class="input-group">
                <span class="input-group-text bg-white border-end-0" style="height: 38px;"><i
                    class="ri-search-line text-muted"></i></span>
                {{-- Input pencarian --}}
                <input type="text" id="searchInput" name="search" class="form-control border-start-0 ps-1"
                  placeholder="Cari nama sesi, serial number alat, atau kendaraan..." value="{{ request('search') }}"
                  autocomplete="off" style="height: 38px;">
              </div>
            </div>

            {{-- FILTER STATUS --}}
            <div style="min-width: 180px;">
              <label class="form-label fw-medium mb-2">Status</label>
              <input type="hidden" name="status" id="status_input" value="{{ request('status') }}">
              <div class="dropdown">
                <button class="custom-filter-dropdown dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown"
                  aria-expanded="false" id="statusDropdown">
                  <div class="d-flex align-items-center">
                    <i class="ri-checkbox-circle-line me-2 text-muted"></i>
                    <span>
                      @if (request('status') === '1')
                        Aktif
                      @elseif(request('status') === '0')
                        Nonaktif
                      @else
                        Semua Status
                      @endif
                    </span>
                  </div>
                  <i class="ri-arrow-down-s-line text-muted"></i>
                </button>
                <ul class="dropdown-menu w-100 shadow-sm">
                  <li><a
                      class="dropdown-item {{ request('status') === null || request('status') === '' ? 'active' : '' }}"
                      href="#" onclick="selectFilter(this, 'status', '', 'Semua Status')">Semua Status</a></li>
                  <li><a class="dropdown-item {{ request('status') === '1' ? 'active' : '' }}" href="#"
                      onclick="selectFilter(this, 'status', '1', 'Aktif')">Aktif</a></li>
                  <li><a class="dropdown-item {{ request('status') === '0' ? 'active' : '' }}" href="#"
                      onclick="selectFilter(this, 'status', '0', 'Nonaktif')">Nonaktif</a></li>
                </ul>
              </div>
            </div>

            <div id="reset-container"
              class="{{ request()->filled('search') || (request()->has('status') && request('status') !== '') ? '' : 'd-none' }}">
              <a href="{{ route('perusahaan.sesi-alat.index') }}"
                class="btn btn-outline-secondary btn-wave d-flex align-items-center" id="resetBtn" style="height: 38px;">
                <i class="ri-close-line me-2"></i>Reset
              </a>
            </div>

          </form>
        </div>
      </div>

      <div class="card custom-card">
        <div class="card-header justify-content-between">

        </div>

        <div class="card-body p-0" id="table-container">
          @include('pages.perusahaan.sesi-alat.table')
        </div>
      </div>

    </div>
  </div>
@endsection

@section('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const filterForm = document.getElementById('filterForm');
      const tableContainer = document.getElementById('table-container');
      const searchInput = document.getElementById('searchInput');
      const resetContainer = document.getElementById('reset-container');
      const resetBtn = document.getElementById('resetBtn');
      let debounceTimer;

      const formatDates = () => {
        const dateElements = document.querySelectorAll('.local-date');
        dateElements.forEach(el => {
          const rawDate = el.textContent.trim();
          if (rawDate && rawDate !== '-') {
            const date = new Date(rawDate);
            if (!isNaN(date.getTime())) {
              const formatted = new Intl.DateTimeFormat('id-ID', {
                day: 'numeric',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
              }).format(date);
              el.textContent = formatted;
            }
          } else {
            el.textContent = '-';
          }
        });
      };

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
        const status = formData.get('status');

        if ((search && search.trim() !== "") || (status !== null && status !== "")) {
          resetContainer.classList.remove('d-none');
        } else {
          resetContainer.classList.add('d-none');
        }

        window.history.pushState({}, '', url);
        fetchTable(url);
      };

      window.selectFilter = function(el, type, value, label) {
        document.getElementById(type + '_input').value = value;
        const btn = document.getElementById(type + 'Dropdown');

        const labelContainer = btn.querySelector('span');
        if (labelContainer) {
          labelContainer.textContent = label;
        }

        const dropdownMenu = el.closest('.dropdown-menu');
        dropdownMenu.querySelectorAll('.dropdown-item').forEach(item => {
          item.classList.remove('active');
        });
        el.classList.add('active');

        triggerFetch();
      };

      searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(triggerFetch, 500);
      });

      resetBtn.addEventListener('click', (e) => {
        e.preventDefault();
        searchInput.value = '';
        document.getElementById('status_input').value = '';

        const sDropdown = document.getElementById('statusDropdown');
        if (sDropdown.querySelector('span')) sDropdown.querySelector('span').textContent = 'Semua Status';

        document.querySelectorAll('.dropdown-menu .dropdown-item').forEach(item => {
          item.classList.remove('active');
        });
        document.querySelectorAll('.dropdown-menu li:first-child .dropdown-item').forEach(item => {
          item.classList.add('active');
        });

        resetContainer.classList.add('d-none');
        const url = resetBtn.href;
        window.history.pushState({}, '', url);
        fetchTable(url);
      });

      tableContainer.addEventListener('click', (e) => {
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

      formatDates();
    });
  </script>
@endsection
