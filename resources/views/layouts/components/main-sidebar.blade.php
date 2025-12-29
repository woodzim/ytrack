<aside class="app-sidebar sticky" id="sidebar">

  <!-- Start::main-sidebar-header -->
  <div class="main-sidebar-header">
    <a href="{{ route('dashboard') }}" class="header-logo">
      <img src="{{ asset('images/long-logo.png') }}" alt="logo" class="desktop-logo">
      <img src="{{ asset('images/square-logo.png') }}" alt="logo" class="toggle-logo">
      <img src="{{ asset('images/long-logo.png') }}" alt="logo" class="desktop-dark">
      <img src="{{ asset('images/square-logo.png') }}" alt="logo" class="toggle-dark">
    </a>
  </div>
  <!-- End::main-sidebar-header -->

  <!-- Start::main-sidebar -->
  <div class="main-sidebar" id="sidebar-scroll">

    <!-- Start::nav -->
    <nav class="main-menu-container nav nav-pills flex-column sub-open">
      <div class="slide-left" id="slide-left">
        <img src="{{ asset('images/square-logo.png') }}">
      </div>
      <ul class="main-menu">
        <!-- Start::slide__category -->
        <div class="text-center mb-4 mt-3 d-block d-md-none">
          <img src="{{ asset('images/long-logo.png') }}" width="160" height="35" alt="logo"
            class="desktop-logo">
        </div>
        <!-- End::slide__category -->


        <!-- MENU FOR ADMIN ROLE -->
        @role('admin')
          <!-- Start::slide Dashboard -->
          <li class="slide">
            <a href="{{ route('dashboard') }}" class="side-menu__item">
              <i class="ri-dashboard-line ri-2x pe-2 me-2 me-md-0 text-primary"></i>
              <span class="side-menu__label">Dashboard</span>
            </a>
          </li>
          <!-- End::slide Dashboard -->

          <!-- Start::slide -->
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
                  <div class="d-flex align-items-center">
                    {{-- <i class="ri-focus-3-fill pe-2 me-2 me-md-0 text-primary"></i> --}}
                    Sesi Alat
                  </div>
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
                  {{-- <i class="ri-database-2-fill pe-2 me-2 me-md-0 text-primary"></i> --}}
                  Aset & Mitra
                  <i class="ri-arrow-right-s-line side-menu__angle"></i>
                </a>
                <ul class="slide-menu child2">
                  <li class="slide">
                    <a href="{{ route('perusahaan.index') }}"
                      class="side-menu__item d-flex align-items-center justify-content-between">
                      <span>Perusahaan</span>
                      @php
                        $perusahaanCount = \App\Models\Perusahaan::count();
                      @endphp
                      @if ($perusahaanCount > 0)
                        <span class="badge bg-white text-primary ms-2">{{ $perusahaanCount }}</span>
                      @endif
                    </a>
                  </li>
                  <li class="slide">
                    <a href="{{ route('alat.index') }}"
                      class="side-menu__item d-flex align-items-center justify-content-between">
                      <span>Alat</span>
                      @php
                        $alatCount = \App\Models\Alat::count();
                      @endphp
                      @if ($alatCount > 0)
                        <span class="badge bg-white text-primary ms-2">{{ $alatCount }}</span>
                      @endif
                    </a>
                  </li>
                </ul>
              </li>
              <li class="slide has-sub">
                <a href="javascript:void(0);" class="side-menu__item">
                  {{-- <i class="ri-database-2-fill pe-2 me-2 me-md-0 text-primary"></i> --}}
                  Kendaraan
                  <i class="ri-arrow-right-s-line side-menu__angle"></i>
                </a>
                <ul class="slide-menu child2">
                  <li class="slide">
                    <a href="{{ route('kendaraan.index') }}"
                      class="side-menu__item d-flex align-items-center justify-content-between">
                      <span>Kendaraan</span>
                      @php
                        $alatCount = \App\Models\Kendaraan::count();
                      @endphp
                      @if ($alatCount > 0)
                        <span class="badge bg-white text-primary ms-2">{{ $alatCount }}</span>
                      @endif
                    </a>
                  </li>
                  <li class="slide">
                    <a href="{{ route('jenis-kendaraan.index') }}"
                      class="side-menu__item d-flex align-items-center justify-content-between">
                      <span>Jenis Kendaraan</span>
                      @php
                        $alatCount = \App\Models\JenisKendaraan::count();
                      @endphp
                      @if ($alatCount > 0)
                        <span class="badge bg-white text-primary ms-2">{{ $alatCount }}</span>
                      @endif
                    </a>
                  </li>
                </ul>
              </li>
              <li class="slide">
                <a href="{{ route('tracking-tidak-lengkap.index') }}"
                  class="side-menu__item d-flex align-items-center justify-content-between">
                  <div class="d-flex align-items-center">
                    {{-- <i class="ri-file-damage-fill pe-2 me-2 me-md-0 text-primary"></i> --}}
                    Error Tracking
                  </div>
                  @php
                    $errorTracking = \App\Models\RiwayatPerjalanan::uncompleted()->count();
                  @endphp
                  @if ($errorTracking > 0)
                    <span class="badge bg-white text-primary ms-2">{{ $errorTracking }}</span>
                  @endif
                </a>
              </li>
            </ul>
          </li>
          <!-- End::slide -->

          <!-- Start::slide Users -->
          <li class="slide">
            <a href="{{ route('users.index') }}" class="side-menu__item">
              <i class="ri-user-line ri-2x pe-2 me-2 me-md-0 text-primary"></i>
              <span class="side-menu__label">Pengguna</span>
            </a>
          </li>
          <!-- End::slide Users -->
        @endrole

        <!-- MENU FOR PERUSAHAAN ROLE -->
        @role('perusahaan')
          <!-- Start::slide Dashboard -->
          <li class="slide">
            <a href="{{ route('perusahaan.dashboard.index') }}" class="side-menu__item">
              <i class="ri-dashboard-line ri-2x pe-2 me-2 me-md-0 text-primary"></i>
              <span class="side-menu__label">Dashboard</span>
            </a>
          </li>
          <!-- End::slide Dashboard -->

          <!-- Start::slide Kendaraan -->
          <li class="slide">
            <a href="{{ route('kendaraan.index') }}" class="side-menu__item">
              <i class="ri-car-line ri-2x pe-2 me-2 me-md-0 text-primary"></i>
              <span class="side-menu__label">Kendaraan</span>
            </a>
          </li>
          <!-- End::slide Kendaraan -->

          <!-- Start::slide Sesi Alat -->
          <li class="slide">
            <a href="{{ route('perusahaan.sesi-alat.index') }}" class="side-menu__item">
              <i class="ri-map-pin-line ri-2x pe-2 me-2 me-md-0 text-primary"></i>
              <span class="side-menu__label">Sesi Alat</span>
            </a>
          </li>
          <!-- End::slide Sesi Alat -->
        @endrole
      </ul>
    </nav>
    <!-- End::nav -->

  </div>
  <!-- End::main-sidebar -->

</aside>
