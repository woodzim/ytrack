@extends('layouts.master')

@section('content')
  <div class="page-header-section d-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="page-title fw-medium fs-20 mb-0">Manajemen Kendaraan</h1>
      <p class="">Kelola semua kendaraan perusahaan Anda</p>
    </div>
    <div class="ms-auto">
      <a href="{{ route('perusahaan.kendaraan.create') }}" class="btn btn-light btn-wave">
        <i class="ri-add-line me-2"></i>Tambah Kendaraan
      </a>
    </div>
  </div>
  <div class="row">
    <div class="col-12">

      <div class="card custom-card mb-3">
        <div class="card-body">
          <form action="{{ route('perusahaan.kendaraan.index') }}" method="GET" id="filterForm"
            class="d-flex gap-2 flex-wrap align-items-end">
            <div class="grow" style="min-width: 250px;">
              <label for="searchInput" class="form-label mb-1">Cari Kendaraan</label>
              <input type="text" id="searchInput" name="search" class="form-control"
                placeholder="Cari No Polisi atau Nama..." value="{{ $search }}" autocomplete="off">
            </div>
            
            <div id="reset-container" class="{{ $search ? '' : 'd-none' }}">
              <a href="{{ route('perusahaan.kendaraan.index') }}" class="btn btn-light btn-wave" id="resetBtn">
                <i class="ri-close-line me-2"></i>Reset
              </a>
            </div>
          </form>
        </div>
      </div>

      <div class="card custom-card table-wrapper" id="table-container">
        @include('pages.perusahaan.kendaraan.table')
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
            
            // Re-initialize tooltips if any
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
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

        if (search && search.trim() !== "") {
            resetContainer.classList.remove('d-none');
        } else {
            resetContainer.classList.add('d-none');
        }

        window.history.pushState({}, '', url);
        fetchTable(url);
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
        resetContainer.classList.add('d-none');
        const url = this.href;
        window.history.pushState({}, '', url);
        fetchTable(url);
    });
});
</script>
@endsection
