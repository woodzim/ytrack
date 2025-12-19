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
                    <img src="{{ asset('images/long-logo.png') }}" width="160" height="35" alt="logo" class="desktop-logo">
                </div>
                <!-- End::slide__category -->

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
                        <i class="ri-map-pin-line ri-2x pe-2 me-2 me-md-0 text-primary"></i>
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
                                Data Master<span class="badge bg-primary-transparent ms-2">2</span>
                                <i class="ri-arrow-right-s-line side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child2">
                                <li class="slide">
                                    <a href="{{ route('perusahaan.index') }}" class="side-menu__item">Perusahaan</a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('alat.index') }}" class="side-menu__item">Alat</a>
                                </li>
                            </ul>
                        </li>
                        <li class="slide">
                            <a href="{{ route('tracking-tidak-lengkap.index') }}" class="side-menu__item">
                                {{-- <i class="ri-file-damage-fill pe-2 me-2 me-md-0 text-primary"></i> --}}
                                Error Tracking</a>
                        </li>
                    </ul>
                </li>
                <!-- End::slide -->
            </ul>
        </nav>
        <!-- End::nav -->

    </div>
    <!-- End::main-sidebar -->

</aside>
