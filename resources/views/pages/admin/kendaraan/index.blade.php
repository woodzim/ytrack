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
    
    /* Hide the default Bootstrap caret (down arrow) */
    .hide-arrow::after {
        display: none !important;
    }

    /* Standardize the custom dropdown button layout */
    .custom-filter-dropdown {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        width: 100%;
        text-align: left;
    }
  </style>
@endsection

@section('content')
  <div class="page-header-section d-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="page-title fw-medium fs-20 mb-0">Kendaraan</h1>
      <p class="">Kelola semua kendaraan di sistem</p>
    </div>
    <div class="ms-auto">
      <a href="{{ route('kendaraan.create') }}" class="btn btn-light btn-wave">
        <i class="ri-add-line me-2"></i>Tambah Kendaraan
      </a>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card mb-3 border-0 shadow-none bg-transparent">
        <div class="p-0">
          <form action="{{ route('kendaraan.index') }}" method="GET" id="filterForm" class="d-flex gap-2 flex-wrap align-items-end">
            <div class="grow" style="min-width: 250px;">
              <label for="searchInput" class="form-label mb-2">Cari Kendaraan</label>
              <div class="input-group">
                <span class="input-group-text bg-white border-end-0" style="height: 38px;">
                  <i class="ri-search-line text-muted"></i>
                </span>
                <input type="text" id="searchInput" name="search" class="form-control border-start-0 ps-1"
                  placeholder="Cari berdasarkan nama atau plat nomor..." value="{{ $search }}" autocomplete="off" style="height: 38px;">
              </div>
            </div>

            <!-- Custom Dropdown Perusahaan -->
            <div style="min-width: 200px;">
              <label class="form-label mb-2">Perusahaan</label>
              <input type="hidden" name="id_perusahaan" id="id_perusahaan_input" value="{{ $id_perusahaan }}">
              <div class="dropdown">
                <button class="custom-filter-dropdown dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="id_perusahaanDropdown">
                  <div class="d-flex align-items-center text-truncate">
                    <i class="ri-building-line me-2 text-muted"></i>
                    <span class="text-truncate">
                      @php
                          $selectedP = $perusahaan->firstWhere('id', $id_perusahaan);
                      @endphp
                      {{ $selectedP ? $selectedP->nama : 'Semua Perusahaan' }}
                    </span>
                  </div>
                  <i class="ri-arrow-down-s-line text-muted"></i>
                </button>
                <ul class="dropdown-menu w-100 shadow-sm" style="max-height: 300px; overflow-y: auto;">
                  <li><a class="dropdown-item {{ !$id_perusahaan ? 'active' : '' }}" href="#" onclick="selectFilter(this, 'id_perusahaan', '', 'Semua Perusahaan')">Semua Perusahaan</a></li>
                  <li><hr class="dropdown-divider"></li>
                  @foreach ($perusahaan as $p)
                    <li><a class="dropdown-item {{ $id_perusahaan == $p->id ? 'active' : '' }}" href="#" onclick="selectFilter(this, 'id_perusahaan', '{{ $p->id }}', '{{ $p->nama }}')">{{ $p->nama }}</a></li>
                  @endforeach
                </ul>
              </div>
            </div>

            <!-- Custom Dropdown Status -->
            <div style="min-width: 180px;">
              <label class="form-label mb-2">Status</label>
              <input type="hidden" name="status" id="status_input" value="{{ $status }}">
              <div class="dropdown">
                <button class="custom-filter-dropdown dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="statusDropdown">
                  <div class="d-flex align-items-center">
                    <i class="ri-checkbox-circle-line me-2 text-muted"></i>
                    <span>
                      @if($status === 'tersedia') Tersedia
                      @elseif($status === 'digunakan') Digunakan
                      @else Semua Status @endif
                    </span>
                  </div>
                  <i class="ri-arrow-down-s-line text-muted"></i>
                </button>
                <ul class="dropdown-menu w-100 shadow-sm">
                  <li><a class="dropdown-item {{ !$status ? 'active' : '' }}" href="#" onclick="selectFilter(this, 'status', '', 'Semua Status')">Semua Status</a></li>
                  <li><a class="dropdown-item {{ $status === 'tersedia' ? 'active' : '' }}" href="#" onclick="selectFilter(this, 'status', 'tersedia', 'Tersedia')">Tersedia</a></li>
                  <li><a class="dropdown-item {{ $status === 'digunakan' ? 'active' : '' }}" href="#" onclick="selectFilter(this, 'status', 'digunakan', 'Digunakan')">Digunakan</a></li>
                </ul>
              </div>
            </div>

            <div id="reset-container" class="{{ $search || $id_perusahaan || $status ? '' : 'd-none' }}">
              <a href="{{ route('kendaraan.index') }}" class="btn btn-outline-secondary btn-wave d-flex align-items-center" id="resetBtn" style="height: 38px;">
                <i class="ri-close-line me-2"></i>Reset
              </a>
            </div>
          </form>
        </div>
      </div>

      <div id="table-container">
        @include('pages.admin.kendaraan.table')
      </div>
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
        const id_perusahaan = formData.get('id_perusahaan');
        const status = formData.get('status');

        if ((search && search.trim() !== "") || id_perusahaan !== "" || status !== "") {
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
        
        // Find the span/span within the flex container inside the button
        const labelContainer = btn.querySelector('span');
        if (labelContainer) {
            labelContainer.textContent = label;
        }

        // Update active class in dropdown items
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

    tableContainer.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination a');
        if (link) {
            e.preventDefault();
            const url = link.href;
            window.history.pushState({}, '', url);
            fetchTable(url);
            tableContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });

    document.getElementById('resetBtn').addEventListener('click', function(e) {
        e.preventDefault();
        searchInput.value = '';
        document.getElementById('id_perusahaan_input').value = '';
        document.getElementById('status_input').value = '';
        
        // Reset label texts
        const pDropdown = document.getElementById('id_perusahaanDropdown');
        if (pDropdown.querySelector('span')) pDropdown.querySelector('span').textContent = 'Semua Perusahaan';
        
        const sDropdown = document.getElementById('statusDropdown');
        if (sDropdown.querySelector('span')) sDropdown.querySelector('span').textContent = 'Semua Status';

        // Reset active classes in dropdowns
        document.querySelectorAll('.dropdown-menu .dropdown-item').forEach(item => {
            item.classList.remove('active');
        });
        // Set "Semua ..." as active
        document.querySelectorAll('.dropdown-menu li:first-child .dropdown-item').forEach(item => {
            item.classList.add('active');
        });
        
        resetContainer.classList.add('d-none');
        const url = this.href;
        window.history.pushState({}, '', url);
        fetchTable(url);
    });
});
</script>
@endsection
