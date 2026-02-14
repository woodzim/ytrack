@extends('layouts.master')

@section('styles')
@endsection

@section('content')
  <div class="page-header-section d-flex align-items-center justify-content-between">
    <div>
      <h1>Perjalanan</h1>
      <p>Kelola sesi penggunaan alat GPS di kendaraan</p>
    </div>
    <div class="ms-auto">
      <a href="{{ route('sesi-alat.create') }}" class="btn btn-light btn-wave">
        <i class="ri-add-line me-2"></i>Buat Sesi
      </a>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card mb-3">
        <div class="card-body">
          <form action="{{ route('sesi-alat.index') }}" method="GET" id="filterForm" class="d-flex gap-2 flex-wrap align-items-end">
            <div class="grow" style="min-width: 250px;">
              <label for="searchInput" class="form-label mb-2">Cari Sesi</label>
              <input type="text" id="searchInput" name="search" class="form-control"
                placeholder="Cari alat atau kendaraan..." value="{{ $search }}" autocomplete="off" style="height: 38px;">
            </div>

            <div style="min-width: 200px;">
              <label class="form-label mb-2">Perusahaan</label>
              <input type="hidden" name="id_perusahaan" id="id_perusahaan_input" value="{{ $filter_perusahaan }}">
              <div class="dropdown">
                <button class="custom-filter-dropdown dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="id_perusahaanDropdown">
                  <span class="text-truncate">
                    @php $selectedP = $perusahaan->firstWhere('id', $filter_perusahaan); @endphp
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

            <div style="min-width: 160px;">
              <label class="form-label mb-2">Status</label>
              <input type="hidden" name="status" id="status_input" value="{{ $filter_status }}">
              <div class="dropdown">
                <button class="custom-filter-dropdown dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="statusDropdown">
                  <span>
                    @if($filter_status === '1') Aktif
                    @elseif($filter_status === '0') Nonaktif
                    @else Semua Status @endif
                  </span>
                </button>
                <ul class="dropdown-menu shadow-sm">
                  <li><a class="dropdown-item" href="#" onclick="selectFilter('status', '', 'Semua Status')">Semua Status</a></li>
                  <li><a class="dropdown-item" href="#" onclick="selectFilter('status', '1', 'Aktif')">Aktif</a></li>
                  <li><a class="dropdown-item" href="#" onclick="selectFilter('status', '0', 'Nonaktif')">Nonaktif</a></li>
                </ul>
              </div>
            </div>

            <div id="reset-container" class="{{ $search || $filter_status !== '' || $filter_perusahaan !== '' ? '' : 'd-none' }}">
              <a href="{{ route('sesi-alat.index') }}" class="btn btn-outline-secondary btn-wave d-flex align-items-center" id="resetBtn" style="height: 38px;">
                <i class="ri-close-line me-2"></i>Reset
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
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
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

    function formatDates() {
        document.querySelectorAll('.local-date').forEach(el => {
            const date = new Date(el.textContent.trim());
            el.textContent = new Intl.DateTimeFormat('id-ID', {
                day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'
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
        window.history.pushState({}, '', this.href);
        fetchTable(this.href);
    });

    formatDates();
});
</script>
@endsection
