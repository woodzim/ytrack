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
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256">
                            <rect width="256" height="256" fill="none" />
                            <path d="M104,224H32a8,8,0,0,1-8-8V120a8,8,0,0,1,8-8h72a8,8,0,0,1,8,8v96A8,8,0,0,1,104,224Z"
                                opacity="0.2" />
                            <path
                                d="M224,224h-72a8,8,0,0,1-8-8V40a8,8,0,0,1,8-8h72a8,8,0,0,1,8,8V216A8,8,0,0,1,224,224Z"
                                opacity="0.2" />
                            <path d="M104,224H32a8,8,0,0,1-8-8V120a8,8,0,0,1,8-8h72a8,8,0,0,1,8,8v96A8,8,0,0,1,104,224Z"
                                fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="16" />
                            <path
                                d="M224,224h-72a8,8,0,0,1-8-8V40a8,8,0,0,1,8-8h72a8,8,0,0,1,8,8V216A8,8,0,0,1,224,224Z"
                                fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="16" />
                            <line x1="128" y1="80" x2="168" y2="80" fill="none"
                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="16" />
                            <line x1="128" y1="128" x2="168" y2="128" fill="none"
                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="16" />
                        </svg>
                        <span class="side-menu__label">Dashboard</span>
                    </a>
                </li>
                <!-- End::slide Dashboard -->

                <!-- Start::slide -->
                <li class="slide has-sub">
                    <a href="javascript:void(0);" class="side-menu__item">
                        <i class="ri-dashboard-line ri-2x me-2"></i>
                        <span class="side-menu__label">Menu</span>
                        <i class="ri-arrow-right-s-line side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide side-menu__label1">
                            <a href="javascript:void(0)">Menu</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('sesi-alat.index') }}"
                                class="side-menu__item d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu-doublemenu__icon"
                                        viewBox="0 0 256 256">
                                        <rect width="256" height="256" fill="none" />
                                        <path d="M33.6,145.5A96,96,0,0,1,96,37.5v72Z" opacity="0.2" />
                                        <path d="M33.6,145.5A96,96,0,0,1,96,37.5v72Z" fill="none"
                                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="16" />
                                        <path d="M128,128.42V32A96,96,0,1,1,45.22,176.64Z" fill="none"
                                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="16" />
                                    </svg>
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
                                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu-doublemenu__icon"
                                    width="32" height="32" viewBox="0 0 256 256">
                                    <path
                                        d="M224,56V200a8,8,0,0,1-8,8H40a8,8,0,0,1-8-8V56a8,8,0,0,1,8-8H216A8,8,0,0,1,224,56Z"
                                        opacity="0.2"></path>
                                    <path
                                        d="M216,40H40A16,16,0,0,0,24,56V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A16,16,0,0,0,216,40Zm0,160H40V56H216V200ZM176,88a48,48,0,0,1-96,0,8,8,0,0,1,16,0,32,32,0,0,0,64,0,8,8,0,0,1,16,0Z">
                                    </path>
                                </svg>
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
                                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu-doublemenu__icon"
                                    viewBox="0 0 256 256">
                                    <rect width="256" height="256" fill="none" />
                                    <circle cx="128" cy="128" r="96" opacity="0.2" />
                                    <circle cx="128" cy="128" r="96" fill="none"
                                        stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="16" />
                                    <polyline points="128 72 128 128 172 156" fill="none" stroke="currentColor"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                </svg>
                                Tracking Tidak Lengkap</a>
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
