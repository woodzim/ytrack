<aside class="app-sidebar sticky" id="sidebar">

  <div class="main-sidebar-header d-flex align-items-center justify-content-between position-relative">
    <a href="{{ route('dashboard') }}" class="header-logo">
      <img src="{{ asset('images/long-logo.webp') }}" alt="logo" class="desktop-logo">
      <img src="{{ asset('images/square-logo.webp') }}" alt="logo" class="toggle-logo">
      <img src="{{ asset('images/long-logo.webp') }}" alt="logo" class="desktop-dark">
      <img src="{{ asset('images/square-logo.webp') }}" alt="logo" class="toggle-dark">
    </a>
  </div>
  <div class="main-sidebar" id="sidebar-scroll">
    <nav class="main-menu-container nav nav-pills flex-column sub-open">
      <div class="slide-left" id="slide-left">
        <img src="{{ asset('images/square-logo.webp') }}">
      </div>
      <ul class="main-menu">
        <div
          class="text-center mb-4 mt-3 d-flex justify-content-center align-items-center d-lg-none position-relative px-3">
          <img src="{{ asset('images/long-logo.webp') }}" width="160" height="35" alt="logo"
            class="desktop-logo object-fit-cover">
          <button type="button" class="btn btn-icon btn-sm btn-light btn-wave" id="sidebar-close-mobile"
            aria-label="Close Sidebar" style="position: absolute; right: 15px;">
            <i class="ri-close-line fs-20"></i>
          </button>
        </div>

        {{-- ================================================= --}}
        {{-- MENU FOR ADMIN ROLE                               --}}
        {{-- ================================================= --}}
        @role('admin')
          <li class="slide__category"><span class="category-name">Admin</span></li>

          <li class="slide">
            <a href="{{ route('dashboard') }}" class="side-menu__item {{ Route::is('dashboard') ? 'active' : '' }}">
              <i class="ri-dashboard-line side-menu__icon text-primary"></i>
              <span class="side-menu__label">Dashboard</span>
            </a>
          </li>

          <li
            class="slide has-sub {{ Route::is('perjalanan.*', 'admin.perusahaan.*', 'alat.*', 'kendaraan.*', 'jenis-kendaraan.*', 'tracking-tidak-lengkap.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);"
              class="side-menu__item {{ Route::is('perjalanan.*', 'admin.perusahaan.*', 'alat.*', 'kendaraan.*', 'jenis-kendaraan.*', 'tracking-tidak-lengkap.*') ? 'active' : '' }}">
              <i class="ri-archive-line side-menu__icon text-primary"></i>
              <span class="side-menu__label">Menu Utama</span>
              <i class="ri-arrow-right-s-line side-menu__angle"></i>
            </a>
            <ul class="slide-menu child1">
              <li class="slide side-menu__label1">
                <a href="javascript:void(0)">Tracking</a>
              </li>
              <li class="slide">
                <a href="{{ route('perjalanan.index') }}"
                  class="side-menu__item {{ Route::is('perjalanan.*') ? 'active' : '' }}">
                  <i class="ri-road-map-line side-menu__icon"></i>
                  <span class="side-menu__label">Perjalanan</span>
                  @php
                    $activeSesiAlatCount = \App\Models\SesiAlat::where('aktif', true)->count();
                  @endphp
                  @if ($activeSesiAlatCount > 0)
                    <span class="badge bg-primary-transparent text-primary ms-2">{{ $activeSesiAlatCount }}</span>
                  @endif
                </a>
              </li>

              <li class="slide has-sub {{ Route::is('admin.perusahaan.*', 'alat.*') ? 'active open' : '' }}">
                <a href="javascript:void(0);"
                  class="side-menu__item {{ Route::is('admin.perusahaan.*', 'alat.*') ? 'active' : '' }}">
                  <i
                    class="ri-building-line side-menu__icon {{ Route::is('admin.perusahaan.*', 'alat.*') ? 'text-primary' : '' }}"></i>
                  <span class="side-menu__label">Aset & Mitra</span>
                  <i
                    class="ri-arrow-right-s-line side-menu__angle {{ Route::is('admin.perusahaan.*', 'alat.*') ? 'text-primary' : '' }}"></i>
                </a>
                <ul class="slide-menu child2">
                  <li class="slide">
                    <a href="{{ route('admin.perusahaan.index') }}"
                      class="side-menu__item d-flex align-items-center justify-content-between {{ Route::is('admin.perusahaan.*') ? 'active' : '' }}">
                      <span><i class="ri-community-line me-1"></i> Perusahaan</span>
                      @php $perusahaanCount = \App\Models\Perusahaan::count(); @endphp
                      @if ($perusahaanCount > 0)
                        <span class="badge bg-primary-transparent text-primary ms-2">{{ $perusahaanCount }}</span>
                      @endif
                    </a>
                  </li>
                  <li class="slide">
                    <a href="{{ route('alat.index') }}"
                      class="side-menu__item d-flex align-items-center justify-content-between {{ Route::is('alat.*') ? 'active' : '' }}">
                      <span><i class="ri-rfid-line me-1"></i> Alat</span>
                      @php $alatCount = \App\Models\Alat::count(); @endphp
                      @if ($alatCount > 0)
                        <span class="badge bg-primary-transparent text-primary ms-2">{{ $alatCount }}</span>
                      @endif
                    </a>
                  </li>
                </ul>
              </li>

              <li class="slide has-sub {{ Route::is('kendaraan.*', 'jenis-kendaraan.*') ? 'active open' : '' }}">
                <a href="javascript:void(0);"
                  class="side-menu__item {{ Route::is('kendaraan.*', 'jenis-kendaraan.*') ? 'active' : '' }}">
                  <i
                    class="ri-car-line side-menu__icon {{ Route::is('kendaraan.*', 'jenis-kendaraan.*') ? 'text-primary' : '' }}"></i>
                  <span class="side-menu__label">Kendaraan</span>
                  <i
                    class="ri-arrow-right-s-line side-menu__angle {{ Route::is('kendaraan.*', 'jenis-kendaraan.*') ? 'text-primary' : '' }}"></i>
                </a>
                <ul class="slide-menu child2">
                  <li class="slide">
                    <a href="{{ route('kendaraan.index') }}"
                      class="side-menu__item d-flex align-items-center justify-content-between {{ Route::is('kendaraan.*') ? 'active' : '' }}">
                      <span><i class="ri-steering-2-line me-1"></i> Daftar Kendaraan</span>
                      @php $kendaraanCount = \App\Models\Kendaraan::count(); @endphp
                      @if ($kendaraanCount > 0)
                        <span class="badge bg-primary-transparent text-primary ms-2">{{ $kendaraanCount }}</span>
                      @endif
                    </a>
                  </li>
                  <li class="slide">
                    <a href="{{ route('jenis-kendaraan.index') }}"
                      class="side-menu__item d-flex align-items-center justify-content-between {{ Route::is('jenis-kendaraan.*') ? 'active' : '' }}">
                      <span><i class="ri-truck-line me-1"></i> Jenis Kendaraan</span>
                      @php $jenisKendaraan = \App\Models\JenisKendaraan::count(); @endphp
                      @if ($jenisKendaraan > 0)
                        <span class="badge bg-primary-transparent text-primary ms-2">{{ $jenisKendaraan }}</span>
                      @endif
                    </a>
                  </li>
                </ul>
              </li>

              <li class="slide">
                <a href="{{ route('tracking-tidak-lengkap.index') }}"
                  class="side-menu__item {{ Route::is('tracking-tidak-lengkap.*') ? 'active' : '' }}">
                  <i class="ri-error-warning-line side-menu__icon text-danger"></i>
                  <span class="side-menu__label">Error Tracking</span>
                  @php $errorTracking = \App\Models\RiwayatPerjalanan::uncompleted()->count(); @endphp
                  @if ($errorTracking > 0)
                    <span class="badge bg-primary-transparent text-primary ms-2">{{ $errorTracking }}</span>
                  @endif
                </a>
              </li>
            </ul>
          </li>

          <li class="slide">
            <a href="{{ route('users.index') }}" class="side-menu__item {{ Route::is('users.*') ? 'active' : '' }}">
              <i class="ri-user-line side-menu__icon text-primary"></i>
              <span class="side-menu__label">Pengaturan Pengguna</span>
            </a>
          </li>

          <li class="slide">
            <a href="{{ route('logs.index') }}" class="side-menu__item {{ Route::is('logs.*') ? 'active' : '' }}">
              <i class="ri-file-list-3-line side-menu__icon text-primary"></i>
              <span class="side-menu__label">Log Aktivitas Sistem</span>
            </a>
          </li>
        @endrole


        {{-- ================================================= --}}
        {{-- MENU FOR PERUSAHAAN ROLE                          --}}
        {{-- ================================================= --}}
        @role('perusahaan')
          <li class="slide__category"><span class="category-name">Menu Perusahaan</span></li>

          <li class="slide">
            <a href="{{ route('perusahaan.dashboard.index') }}"
              class="side-menu__item {{ Route::is('perusahaan.dashboard.*') ? 'active' : '' }}">
              <i class="ri-dashboard-line side-menu__icon text-primary"></i>
              <span class="side-menu__label">Dashboard Monitoring</span>
            </a>
          </li>

          <li class="slide">
            <a href="{{ route('perusahaan.perjalanan.index') }}"
              class="side-menu__item {{ Route::is('perusahaan.perjalanan.*') ? 'active' : '' }}">
              <i class="ri-map-pin-line side-menu__icon text-primary"></i>
              <span class="side-menu__label">Perjalanan</span>
            </a>
          </li>

          <li class="slide">
            <a href="{{ route('perusahaan.kendaraan.index') }}"
              class="side-menu__item {{ Route::is('perusahaan.kendaraan.*') ? 'active' : '' }}">
              <i class="ri-car-line side-menu__icon text-primary"></i>
              <span class="side-menu__label">Manajemen Kendaraan</span>
            </a>
          </li>

          <li class="slide">
            <a href="{{ route('perusahaan.alat.index') }}"
              class="side-menu__item {{ Route::is('perusahaan.alat.*') ? 'active' : '' }}">
              <i class="ri-rfid-line side-menu__icon text-primary"></i>
              <span class="side-menu__label">Perangkat Alat GPS</span>
            </a>
          </li>
        @endrole

      </ul>
    </nav>
  </div>
</aside>
