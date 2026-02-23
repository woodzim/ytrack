@extends('layouts.master')

@section('styles')
  <style>
    /* Force parent containers to allow overflow so dropdowns aren't cut off */
    #table-container,
    .table-wrapper,
    .table-wrapper .card-body,
    .table-responsive {
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
      <h1 class="page-title fw-medium fs-20 mb-0">Daftar Alat GPS</h1>
      <p class="mb-0">Monitor status penggunaan alat perusahaan Anda</p>
    </div>
  </div>

  <div class="card border-0 shadow-none mb-4 bg-transparent">
    <div class="p-0">
      <form action="{{ route('perusahaan.alat.index') }}" method="GET" id="filterForm"
        class="d-flex gap-3 flex-wrap align-items-end">
        <div class="grow" style="min-width: 250px;">
          <label class="form-label text-muted small mb-2">Cari Serial Number</label>
          <div class="input-group">
            <span class="input-group-text bg-white border-end-0" style="height: 38px;"><i
                class="ri-search-line text-muted"></i></span>
            <input type="text" id="searchInput" name="search" class="form-control border-start-0 ps-1"
              placeholder="Contoh: GPS-001..." value="{{ $search }}" autocomplete="off" style="height: 38px;">
          </div>
        </div>

        <div style="min-width: 200px;">
          <label class="form-label text-muted small mb-2">Status Penggunaan</label>
          <input type="hidden" name="status" id="status_input" value="{{ $filter_status }}">
          <div class="dropdown">
            <button class="custom-filter-dropdown dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown"
              aria-expanded="false" id="statusDropdown">
              <div class="d-flex align-items-center">
                <i class="ri-checkbox-circle-line me-2 text-muted"></i>
                <span>
                  @if ($filter_status === '1')
                    Sedang Digunakan
                  @elseif($filter_status === '0')
                    Tersedia (Idle)
                  @else
                    Semua Status
                  @endif
                </span>
              </div>
              <i class="ri-arrow-down-s-line text-muted"></i>
            </button>
            <ul class="dropdown-menu w-100 shadow-sm">
              <li><a class="dropdown-item {{ $filter_status === null || $filter_status === '' ? 'active' : '' }}"
                  href="#" onclick="selectFilter(this, 'status', '', 'Semua Status')">Semua Status</a></li>
              <li><a class="dropdown-item {{ $filter_status === '1' ? 'active' : '' }}" href="#"
                  onclick="selectFilter(this, 'status', '1', 'Sedang Digunakan')">Sedang Digunakan</a></li>
              <li><a class="dropdown-item {{ $filter_status === '0' ? 'active' : '' }}" href="#"
                  onclick="selectFilter(this, 'status', '0', 'Tersedia (Idle)')">Tersedia (Idle)</a></li>
            </ul>
          </div>
        </div>

        <div id="reset-container"
          class="{{ $search || ($filter_status !== null && $filter_status !== '') ? '' : 'd-none' }}">
          <a href="{{ route('perusahaan.alat.index') }}"
            class="btn btn-outline-secondary btn-wave d-flex align-items-center" id="resetBtn" style="height: 38px;">
            <i class="ri-close-line me-2"></i>Reset
          </a>
        </div>
      </form>
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-body p-0" id="table-container">
      @include('pages.perusahaan.alat.table')
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const filterForm = document.getElementById('filterForm');
      const searchInput = document.getElementById('searchInput');
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
          .then(response => response.text())
          .then(html => {
            tableContainer.innerHTML = html;
            tableContainer.style.opacity = '1';
            if (typeof Waves !== 'undefined') Waves.init();
          })
          .catch(error => {
            console.error('Error fetching table:', error);
            tableContainer.style.opacity = '1';
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

      searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(triggerFetch, 500);
      });

      document.getElementById('resetBtn').addEventListener('click', function(e) {
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
        const url = this.href;
        window.history.pushState({}, '', url);
        fetchTable(url);
      });

      tableContainer.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination a');
        if (link) {
          e.preventDefault();
          const url = link.href;
          window.history.pushState({}, '', url);
          fetchTable(url);
          tableContainer.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });
  </script>
@endsection
