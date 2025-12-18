<header class="app-header sticky" id="header">

    <!-- Start::main-header-container -->
    <div class="main-header-container container-fluid">

        <!-- Start::header-content-left -->
        <div class="header-content-left">

            <!-- Start::header-element -->
            <div class="header-element">
                <div class="horizontal-logo">
                    <a href="{{ url('index') }}" class="header-logo">
                        <img src="{{ asset('build/assets/images/brand-logos/desktop-logo.png') }}" alt="logo"
                            class="desktop-logo">
                        <img src="{{ asset('build/assets/images/brand-logos/toggle-logo.png') }}" alt="logo"
                            class="toggle-logo">
                        <img src="{{ asset('build/assets/images/brand-logos/desktop-dark.png') }}" alt="logo"
                            class="desktop-dark">
                        <img src="{{ asset('build/assets/images/brand-logos/toggle-dark.png') }}" alt="logo"
                            class="toggle-dark">
                    </a>
                </div>
            </div>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <div class="header-element mx-lg-0 mx-2">
                <a aria-label="Hide Sidebar"
                    class="sidemenu-toggle header-link animated-arrow hor-toggle horizontal-navtoggle"
                    data-bs-toggle="sidebar" href="javascript:void(0);"><span></span></a>
            </div>
            <!-- End::header-element -->
        </div>
        <!-- End::header-content-left -->

        <!-- Start::header-content-right -->
        <ul class="header-content-right">
            <!-- Start::header-element -->
            <li class="header-element d-md-none d-block">
                <a href="javascript:void(0);" class="header-link" data-bs-toggle="modal"
                    data-bs-target="#header-responsive-search">
                    <!-- Start::header-link-icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon" viewBox="0 0 256 256">
                        <rect width="256" height="256" fill="none" />
                        <circle cx="112" cy="112" r="80" opacity="0.2" />
                        <circle cx="112" cy="112" r="80" fill="none" stroke="currentColor"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                        <line x1="168.57" y1="168.57" x2="224" y2="224" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                    </svg>
                    <!-- End::header-link-icon -->
                </a>
            </li>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <li class="header-element notifications-dropdown d-xl-block d-none dropdown">
                <!-- Start::header-link|dropdown-toggle -->
                <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-toggle="dropdown"
                    data-bs-auto-close="outside" id="messageDropdown" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon" viewBox="0 0 256 256">
                        <rect width="256" height="256" fill="none" />
                        <path
                            d="M56,104a72,72,0,0,1,144,0c0,35.82,8.3,64.6,14.9,76A8,8,0,0,1,208,192H48a8,8,0,0,1-6.88-12C47.71,168.6,56,139.81,56,104Z"
                            opacity="0.2" />
                        <path d="M96,192a32,32,0,0,0,64,0" fill="none" stroke="currentColor" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="16" />
                        <path
                            d="M56,104a72,72,0,0,1,144,0c0,35.82,8.3,64.6,14.9,76A8,8,0,0,1,208,192H48a8,8,0,0,1-6.88-12C47.71,168.6,56,139.81,56,104Z"
                            fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="16" />
                    </svg>
                    <span class="header-icon-pulse bg-secondary rounded pulse pulse-secondary"></span>
                </a>
                <!-- End::header-link|dropdown-toggle -->
                <!-- Start::main-header-dropdown -->
                <div class="main-header-dropdown dropdown-menu dropdown-menu-end" data-popper-placement="none">
                    <div class="p-3 bg-primary text-fixed-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="mb-0 fs-16">Notifications</p>
                            <a href="javascript:void(0);" class="badge bg-light text-default border">Clear All</a>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <ul class="list-unstyled mb-0" id="header-notification-scroll">
                        <li class="dropdown-item position-relative">
                            <a href="javascript:void(0);" class="stretched-link"></a>
                            <div class="d-flex align-items-start gap-3">
                                <div class="lh-1">
                                    <span class="avatar avatar-sm avatar-rounded bg-primary-transparent">
                                        <img src="{{ asset('build/assets/images/faces/1.jpg') }}" alt="">
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <span class="d-block fw-semibold">New Message</span>
                                    <span class="d-block text-muted fs-12">You have received a new message from John
                                        Doe</span>
                                </div>
                                <div class="text-end">
                                    <span class="d-block mb-1 fs-12 text-muted">11:45am</span>
                                    <span class="d-block text-primary d-none"><i
                                            class="ri-circle-fill fs-9"></i></span>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-item position-relative">
                            <a href="javascript:void(0);" class="stretched-link"></a>
                            <div class="d-flex align-items-start gap-3">
                                <div class="lh-1">
                                    <span class="avatar avatar-sm avatar-rounded bg-primary-transparent">
                                        <i class="ri-notification-line fs-16"></i>
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <span class="d-block fw-semibold">Task Reminder</span>
                                    <span class="d-block text-muted fs-12">Don't forget to submit your report by 3 PM
                                        today</span>
                                </div>
                                <div class="text-end">
                                    <span class="d-block mb-1 fs-12 text-muted">02:16pm</span>
                                    <span class="d-block text-primary d-none"><i
                                            class="ri-circle-fill fs-9"></i></span>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-item position-relative">
                            <a href="javascript:void(0);" class="stretched-link"></a>
                            <div class="d-flex align-items-start gap-3">
                                <div class="lh-1">
                                    <span class="avatar avatar-sm avatar-rounded bg-primary-transparent fs-5">
                                        <img src="{{ asset('build/assets/images/faces/12.jpg') }}" alt="">
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <span class="d-block fw-semibold">Friend Request</span>
                                    <span class="d-block text-muted fs-12">Jane Smith sent you a friend request</span>
                                </div>
                                <div class="text-end">
                                    <span class="d-block mb-1 fs-12 text-muted">10:04am</span>
                                    <span class="d-block text-primary"><i class="ri-circle-fill fs-9"></i></span>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-item position-relative">
                            <a href="javascript:void(0);" class="stretched-link"></a>
                            <div class="d-flex align-items-start gap-3">
                                <div class="lh-1">
                                    <span class="avatar avatar-sm avatar-rounded bg-primary-transparent fs-5">
                                        <i class="ri-notification-line fs-16"></i>
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <span class="d-block fw-semibold">Event Reminder</span>
                                    <span class="d-block text-muted fs-12">You have an upcoming event: Team Meeting on
                                        October 25 at 10 AM.</span>
                                </div>
                                <div class="text-end">
                                    <span class="d-block mb-1 fs-12 text-muted">12:58pm</span>
                                    <span class="d-block text-primary"><i class="ri-circle-fill fs-9"></i></span>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-item position-relative">
                            <a href="javascript:void(0);" class="stretched-link"></a>
                            <div class="d-flex align-items-start gap-3">
                                <div class="lh-1">
                                    <span class="avatar avatar-sm avatar-rounded bg-primary-transparent fs-5">
                                        <i class="ri-notification-line fs-16"></i>
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <span class="d-block fw-semibold">File Uploaded</span>
                                    <span class="d-block text-muted fs-12">The file "Project_Proposal.pdf" has been
                                        uploaded successfully</span>
                                </div>
                                <div class="text-end">
                                    <span class="d-block mb-1 fs-12 text-muted">05:13pm</span>
                                    <span class="d-block text-primary"><i class="ri-circle-fill fs-9"></i></span>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="p-5 empty-item1 d-none">
                        <div class="text-center">
                            <span class="avatar avatar-xl avatar-rounded bg-secondary-transparent">
                                <i class="ri-notification-off-line fs-2"></i>
                            </span>
                            <h6 class="fw-medium mt-3">No New Notifications</h6>
                        </div>
                    </div>
                </div>
                <!-- End::main-header-dropdown -->
            </li>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <li class="header-element dropdown">
                <!-- Start::header-link|dropdown-toggle -->
                <a href="javascript:void(0);" class="header-link dropdown-toggle" id="mainHeaderProfile"
                    data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    <div>
                        <img src="{{ asset('build/assets/images/faces/12.jpg') }}" alt="img"
                            class="header-link-icon">
                    </div>
                </a>
                <!-- End::header-link|dropdown-toggle -->
                <div class="main-header-dropdown dropdown-menu pt-0 overflow-hidden header-profile-dropdown dropdown-menu-end"
                    aria-labelledby="mainHeaderProfile">
                    {{-- <div class="p-3 bg-primary text-fixed-white">
									<div class="d-flex align-items-center justify-content-between">
										<p class="mb-0 fs-16">Profile</p>
										<a href="javascript:void(0);" class="text-fixed-white"><i class="ti ti-settings-cog"></i></a>
									</div>
								</div> --}}
                    <div class="dropdown-divider"></div>
                    <div class="p-3">
                        <div class="d-flex align-items-start gap-2">
                            {{-- <div class="lh-1">
											<span class="avatar avatar-sm bg-primary-transparent avatar-rounded">
												<img src="{{asset('build/assets/images/faces/12.jpg')}}" alt="">
											</span>
										</div> --}}
                            <div>
                                <span class="d-block fw-semibold lh-1">{{ auth()->user()->name }}</span>
                                <span class="text-muted fs-12">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <ul class="list-unstyled mb-0">
                        <li>
                            <ul class="list-unstyled mb-0 sub-list">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);"><i
                                            class="ti ti-user-circle me-2 fs-18"></i>Profil</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="dropdown-item d-flex align-items-center"
                                    style="border: none; background: none; cursor: pointer; width: 100%; text-align: left;">
                                    <i class="ti ti-logout me-2 fs-18"></i>Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </li>
            <!-- End::header-element -->

        </ul>
        <!-- End::header-content-right -->

    </div>
    <!-- End::main-header-container -->

</header>
