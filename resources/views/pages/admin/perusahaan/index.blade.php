@extends('layouts.master')

@section('content')
  <!-- Start::page-header -->
  <div class="page-header-section d-flex align-items-center justify-content-between">
    <div>
      <h1>Daftar Perusahaan</h1>
      <p>Kelola semua perusahaan di sistem</p>
    </div>
    <div class="ms-auto">
      <a href="{{ route('perusahaan.create') }}" class="btn btn-light btn-wave">
        <i class="ri-add-line me-2"></i>Tambah Perusahaan
      </a>
    </div>
  </div>
  <!-- End::page-header -->

  <!-- Start::app-content -->
  <div class="row">
    <div class="col-12">
      <!-- Search Section -->
      <div class="card mb-3">
        <div class="card-body">
          <form action="{{ route('perusahaan.index') }}" method="GET" id="filterForm" class="d-flex gap-2 flex-wrap align-items-end">
            <div class="grow" style="min-width: 250px;">
              <label for="searchInput" class="form-label mb-2">Cari Perusahaan</label>
              <input type="text" id="searchInput" name="search" class="form-control"
                placeholder="Cari berdasarkan nama atau alamat..." value="{{ $search }}" autocomplete="off">
            </div>
            <div id="reset-container" class="{{ $search ? '' : 'd-none' }}">
              <a href="{{ route('perusahaan.index') }}" class="btn btn-outline-secondary btn-wave" id="resetBtn">
                <i class="ri-close-line me-2"></i>Reset
              </a>
            </div>
          </form>
        </div>
      </div>

      <div id="table-container">
        @include('pages.admin.perusahaan.table')
      </div>

    </div>
  </div>
  <!-- End::app-content -->
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
        // Show loading state if needed
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
            
            // Re-init dropdowns and waves if needed (Bootstrap auto-handles most)
            if (typeof Waves !== 'undefined') Waves.init();
        })
        .catch(error => {
            console.error('Error fetching table:', error);
            tableContainer.style.opacity = '1';
        });
    };

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const query = this.value;
        
        // Toggle reset button
        if (query.length > 0) {
            resetContainer.classList.remove('d-none');
        } else {
            resetContainer.classList.add('d-none');
        }

        debounceTimer = setTimeout(() => {
            const formData = new FormData(filterForm);
            const params = new URLSearchParams(formData).toString();
            const url = `${filterForm.action}?${params}`;
            
            // Update URL in browser without refresh
            window.history.pushState({}, '', url);
            
            fetchTable(url);
        }, 500); // 500ms debounce
    });

    // Handle pagination clicks
    tableContainer.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination a');
        if (link) {
            e.preventDefault();
            const url = link.href;
            window.history.pushState({}, '', url);
            fetchTable(url);
            
            // Scroll to top of table
            tableContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });

    // Handle Reset Button
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
