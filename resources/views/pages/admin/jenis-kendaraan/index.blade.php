@extends('layouts.master')

@section('content')
  <div class="page-header-section d-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="page-title fw-medium fs-20 mb-0">Jenis Kendaraan</h1>
      <p class="">Kelola kategori dan tipe kendaraan</p>
    </div>
    <div class="ms-auto">
      <a href="{{ route('jenis-kendaraan.create') }}" class="btn btn-light btn-wave">
        <i class="ri-add-line me-2"></i>Tambah Jenis
      </a>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card custom-card mb-3">
        <div class="card-body p-3">
          <form action="{{ route('jenis-kendaraan.index') }}" method="GET" id="filterForm"
            class="d-flex flex-column flex-md-row gap-3 align-items-md-end">
            <div class="grow" style="min-width: 250px;">
              <label for="searchInput" class="form-label fw-semibold mb-1 text-muted fs-12 text-uppercase">Cari Jenis
                Kendaraan</label>
              <div class="input-group">
                <span class="input-group-text bg-white border-end-0 border-light" style="height: 40px;">
                  <i class="ri-search-line"></i>
                </span>
                <input type="text" id="searchInput" name="search"
                  class="form-control border-start-0 border-light bg-white" placeholder="Cari nama jenis..."
                  value="{{ $search }}" autocomplete="off" style="height: 40px;">
              </div>
            </div>

            <div id="reset-container" class="{{ $search ? '' : 'd-none' }}">
              <a href="{{ route('jenis-kendaraan.index') }}"
                class="btn btn-outline-secondary btn-wave d-flex align-items-center" id="resetBtn" style="height: 40px;">
                <i class="ri-close-line me-2"></i>Reset
              </a>
            </div>
          </form>
        </div>
      </div>

      <div class="card table-wrapper" id="table-container">
        @include('pages.admin.jenis-kendaraan.table')
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
          tableContainer.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
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
