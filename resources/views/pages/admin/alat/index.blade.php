@extends('layouts.master')

@section('content')
  <div class="page-header-section d-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="page-title fw-medium fs-20 mb-0">Daftar Alat (GPS)</h1>
      <p>Kelola semua perangkat GPS di sistem</p>
    </div>
    <div class="ms-auto">
      <a href="{{ route('alat.create') }}" class="btn btn-light btn-wave">
        <i class="ri-add-line me-2"></i>Tambah Alat
      </a>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card mb-3">
        <div class="card-body">
          <form action="{{ route('alat.index') }}" method="GET" id="filterForm" class="d-flex gap-2 flex-wrap align-items-end">
            <div class="grow" style="min-width: 250px;">
              <label for="searchInput" class="form-label mb-2">Cari Alat</label>
              <input type="text" id="searchInput" name="search" class="form-control"
                placeholder="Cari Serial Number atau Perusahaan..." value="{{ $search }}" autocomplete="off" style="height: 38px;">
            </div>

            <!-- Custom Dropdown Perusahaan -->
            <div style="min-width: 180px;">
              <label class="form-label mb-2">Perusahaan</label>
              <input type="hidden" name="id_perusahaan" id="id_perusahaan_input" value="{{ $id_perusahaan }}">
              <div class="dropdown">
                <button class="custom-filter-dropdown dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="id_perusahaanDropdown">
                  <span class="text-truncate">
                    @php $selectedP = $perusahaan->firstWhere('id', $id_perusahaan); @endphp
                    {{ $selectedP ? $selectedP->nama : 'Semua Perusahaan' }}
                  </span>
                </button>
                <ul class="dropdown-menu shadow-sm" style="max-height: 300px; overflow-y: auto;">
                  <li><a class="dropdown-item" href="#" onclick="selectFilter('id_perusahaan', '', 'Semua Perusahaan')">Semua Perusahaan</a></li>
                  <li><hr class="dropdown-divider"></li>
                  @foreach ($perusahaan as $p)
                    <li><a class="dropdown-item" href="#" onclick="selectFilter('id_perusahaan', '{{ $p->id }}', '{{ $p->nama }}')">{{ $p->nama }}</a></li>
                  @endforeach
                </ul>
              </div>
            </div>

            <!-- Custom Dropdown Status -->
            <div style="min-width: 150px;">
              <label class="form-label mb-2">Status</label>
              <input type="hidden" name="status" id="status_input" value="{{ $status }}">
              <div class="dropdown">
                <button class="custom-filter-dropdown dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="statusDropdown">
                  <span>
                    @if($status === '1') Digunakan
                    @elseif($status === '0') Tersedia
                    @else Semua Status @endif
                  </span>
                </button>
                <ul class="dropdown-menu shadow-sm">
                  <li><a class="dropdown-item" href="#" onclick="selectFilter('status', '', 'Semua Status')">Semua Status</a></li>
                  <li><a class="dropdown-item" href="#" onclick="selectFilter('status', '1', 'Digunakan')">Digunakan</a></li>
                  <li><a class="dropdown-item" href="#" onclick="selectFilter('status', '0', 'Tersedia')">Tersedia</a></li>
                </ul>
              </div>
            </div>

            <div id="reset-container" class="{{ ($search && trim($search) !== '') || $id_perusahaan !== '' || $status !== '' ? '' : 'd-none' }}">
              <a href="{{ route('alat.index') }}" class="btn btn-outline-secondary btn-wave d-flex align-items-center" id="resetBtn" style="height: 38px;">
                <i class="ri-close-line me-2"></i>Reset
              </a>
            </div>
          </form>
        </div>
      </div>

      <div id="table-container">
        @include('pages.admin.alat.table')
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
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.text())
        .then(html => {
            tableContainer.innerHTML = html;
            tableContainer.style.opacity = '1';
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
        } else {
            resetContainer.classList.add('d-none');
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
            tableContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });

    document.getElementById('resetBtn').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('searchInput').value = '';
        document.getElementById('id_perusahaan_input').value = '';
        document.getElementById('status_input').value = '';
        document.getElementById('id_perusahaanDropdown').querySelector('span').textContent = 'Semua Perusahaan';
        document.getElementById('statusDropdown').querySelector('span').textContent = 'Semua Status';
        resetContainer.classList.add('d-none');
        window.history.pushState({}, '', this.href);
        fetchTable(this.href);
    });
});
</script>
@endsection
