<aside class="app-sidebar sticky" id="sidebar">

  <div class="main-sidebar-header">
    <a href="{{ route('dashboard') }}" class="header-logo">
      <img src="{{ asset('images/long-logo.png') }}" alt="logo" class="desktop-logo">
      <img src="{{ asset('images/square-logo.png') }}" alt="logo" class="toggle-logo">
      <img src="{{ asset('images/long-logo.png') }}" alt="logo" class="desktop-dark">
      <img src="{{ asset('images/square-logo.png') }}" alt="logo" class="toggle-dark">
    </a>
  </div>
  <div class="main-sidebar" id="sidebar-scroll">
    <nav class="main-menu-container nav nav-pills flex-column sub-open">
      <div class="slide-left" id="slide-left">
        <img src="{{ asset('images/square-logo.png') }}">
      </div>
      <ul class="main-menu">
        <div class="text-center mb-4 mt-3 d-block d-md-none">
          <img src="{{ asset('images/long-logo.png') }}" width="160" height="35" alt="logo"
            class="desktop-logo">
        </div>

        {{-- ================================================= --}}
        {{-- MENU FOR ADMIN ROLE                               --}}
        {{-- ================================================= --}}
        @role('admin')
          <li class="slide__category"><span class="category-name">Admin</span></li>

          <li class="slide">
            <a href="{{ route('dashboard') }}" class="side-menu__item">
              <i class="ri-dashboard-line ri-2x pe-2 me-2 me-md-0 text-primary"></i>
              <span class="side-menu__label">Dashboard</span>
            </a>
          </li>

          <li class="slide has-sub">
            <a href="javascript:void(0);" class="side-menu__item">
              <i class="ri-archive-line ri-2x pe-2 me-2 me-md-0 text-primary"></i>
              <span class="side-menu__label">Menu</span>
              <i class="ri-arrow-right-s-line side-menu__angle"></i>
            </a>
            <ul class="slide-menu child1">
              <li class="slide side-menu__label1">
                <a href="javascript:void(0)">Tracking</a>
              </li>
              <li class="slide">
                <a href="{{ route('sesi-alat.index') }}"
                  class="side-menu__item d-flex align-items-center justify-content-between">
                  <div class="d-flex align-items-center">Sesi Alat</div>
                  @php
                    $activeSesiAlatCount = \App\Models\SesiAlat::where('aktif', true)->count();
                  @endphp
                  @if ($activeSesiAlatCount > 0)
                    <span class="badge bg-white text-primary ms-2">{{ $activeSesiAlatCount }}</span>
                  @endif
                </a>
              </li>

              <li class="slide has-sub">
                <a href="javascript:void(0);" class="side-menu__item">
                  Aset & Mitra
                  <i class="ri-arrow-right-s-line side-menu__angle"></i>
                </a>
                <ul class="slide-menu child2">
                  <li class="slide">
                    <a href="{{ route('perusahaan.index') }}"
                      class="side-menu__item d-flex align-items-center justify-content-between">
                      <span>Perusahaan</span>
                      @php $perusahaanCount = \App\Models\Perusahaan::count(); @endphp
                      @if ($perusahaanCount > 0)
                        <span class="badge bg-white text-primary ms-2">{{ $perusahaanCount }}</span>
                      @endif
                    </a>
                  </li>
                  <li class="slide">
                    <a href="{{ route('alat.index') }}"
                      class="side-menu__item d-flex align-items-center justify-content-between">
                      <span>Alat</span>
                      @php $alatCount = \App\Models\Alat::count(); @endphp
                      @if ($alatCount > 0)
                        <span class="badge bg-white text-primary ms-2">{{ $alatCount }}</span>
                      @endif
                    </a>
                  </li>
                </ul>
              </li>

              <li class="slide has-sub">
                <a href="javascript:void(0);" class="side-menu__item">
                  Kendaraan
                  <i class="ri-arrow-right-s-line side-menu__angle"></i>
                </a>
                <ul class="slide-menu child2">
                  <li class="slide">
                    <a href="{{ route('kendaraan.index') }}"
                      class="side-menu__item d-flex align-items-center justify-content-between">
                      <span>Kendaraan</span>
                      @php $kendaraanCount = \App\Models\Kendaraan::count(); @endphp
                      @if ($kendaraanCount > 0)
                        <span class="badge bg-white text-primary ms-2">{{ $kendaraanCount }}</span>
                      @endif
                    </a>
                  </li>
                  <li class="slide">
                    <a href="{{ route('jenis-kendaraan.index') }}"
                      class="side-menu__item d-flex align-items-center justify-content-between">
                      <span>Jenis Kendaraan</span>
                      @php $jenisKendaraan = \App\Models\JenisKendaraan::count(); @endphp
                      @if ($jenisKendaraan > 0)
                        <span class="badge bg-white text-primary ms-2">{{ $jenisKendaraan }}</span>
                      @endif
                    </a>
                  </li>
                </ul>
              </li>

              <li class="slide">
                <a href="{{ route('tracking-tidak-lengkap.index') }}"
                  class="side-menu__item d-flex align-items-center justify-content-between">
                  <div class="d-flex align-items-center">Error Tracking</div>
                  @php $errorTracking = \App\Models\RiwayatPerjalanan::uncompleted()->count(); @endphp
                  @if ($errorTracking > 0)
                    <span class="badge bg-white text-primary ms-2">{{ $errorTracking }}</span>
                  @endif
                </a>
              </li>
            </ul>
          </li>

          <li class="slide">
            <a href="{{ route('users.index') }}" class="side-menu__item">
              <i class="ri-user-line ri-2x pe-2 me-2 me-md-0 text-primary"></i>
              <span class="side-menu__label">Pengguna</span>
            </a>
          </li>

          <li class="slide">
            {{-- Pastikan route ini sesuai dengan yang kita buat di web.php (admin.logs.index) --}}
            <a href="{{ route('logs.index') }}" class="side-menu__item">
              <i class="ri-file-list-3-line ri-2x pe-2 me-2 me-md-0 text-primary"></i>
              <span class="side-menu__label">Log Aktivitas</span>
            </a>
          </li>
        @endrole


        {{-- ================================================= --}}
        {{-- MENU FOR PERUSAHAAN ROLE                          --}}
        {{-- ================================================= --}}
        @role('perusahaan')
          <li class="slide__category"><span class="category-name">Menu Perusahaan</span></li>

          <li class="slide">
            <a href="{{ route('perusahaan.dashboard.index') }}" class="side-menu__item">
              <i class="ri-dashboard-line ri-2x pe-2 me-2 me-md-0 text-primary"></i>
              <span class="side-menu__label">Dashboard</span>
            </a>
          </li>

          <li class="slide">
            <a href="{{ route('perusahaan.kendaraan.index') }}" class="side-menu__item">
              <i class="ri-car-line ri-2x pe-2 me-2 me-md-0 text-primary"></i>
              <span class="side-menu__label">Kendaraan</span>
            </a>
          </li>

          <li class="slide">
            <a href="{{ route('perusahaan.sesi-alat.index') }}" class="side-menu__item">
              <i class="ri-map-pin-line ri-2x pe-2 me-2 me-md-0 text-primary"></i>
              <span class="side-menu__label">Sesi Alat</span>
            </a>
          </li>
        @endrole

      </ul>
    </nav>
  </div>
</aside>
