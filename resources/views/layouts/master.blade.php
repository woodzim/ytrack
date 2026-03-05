<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="transparent"
  data-width="fullwidth" data-menu-styles="transparent" data-page-style="flat" data-vertical-style="overlay">

<head>

  <!-- Meta Data -->
  <meta charset="UTF-8">
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="Description" content="Laravel Bootstrap Responsive Admin Web Dashboard Template">
  <meta name="Author" content="Spruko Technologies Private Limited">
  <meta name="keywords"
    content="laravel, laravel admin panel, laravel dashboard, bootstrap dashboard, bootstrap admin panel, vite laravel, admin dashboard, admin panel in laravel, admin dashboard ui, laravel admin, admin panel template, laravel framework, dashboard, admin dashboard template, laravel template.">

  <!-- Title-->
  <title> Y-TRACK </title>

  <!-- Favicon -->
  <link rel="icon" href="{{ asset('images/square-logo.ico') }}" type="image/x-icon">

  <!-- Main Theme Js -->
  <script src="{{ asset('build/assets/main.js') }}"></script>

  <script>
    /* Pre-rendering script to prevent flickering */
    (function() {
      const html = document.querySelector('html');
      const getStorage = (key) => {
        try {
          return localStorage.getItem(key);
        } catch (e) {
          return null;
        }
      };

      // Theme Mode
      const themeMode = getStorage("vyzorthememode") || getStorage("vyzordarktheme");
      if (themeMode === 'dark') html.setAttribute("data-theme-mode", "dark");

      // Vertical Style (Sidebar mode)
      const verticalStyle = getStorage("vyzorverticalstyles");
      if (verticalStyle) html.setAttribute("data-vertical-style", verticalStyle);

      // Toggled State
      const toggled = getStorage("sidebar-toggled") || getStorage("vyzortoggled");
      if (toggled && window.innerWidth >= 992) {
        html.setAttribute("data-toggled", toggled);
      } else if (window.innerWidth < 992) {
        html.setAttribute("data-toggled", "close");
      }

      // Other persistable settings
      const navLayout = getStorage("vyzornavlayout");
      if (navLayout) html.setAttribute("data-nav-layout", navLayout);

      const menuStyles = getStorage("vyzormenustyles");
      if (menuStyles) html.setAttribute("data-menu-styles", menuStyles);
    })();
  </script>

  <style>
    /* Prevent submenu flickering: force display if 'open' class is present from server */
    [data-nav-layout="vertical"] .slide.has-sub.open>.slide-menu {
      display: block !important;
    }

    /* Force sidebar arrow visibility with high specificity */
    .app-sidebar .side-menu__angle {
      color: rgba(0, 0, 0, 0.3) !important;
      /* Default visible grey */
      opacity: 1 !important;
      visibility: visible !important;
    }

    /* Active and Hover states */
    .app-sidebar .side-menu__item.active .side-menu__angle,
    .app-sidebar .side-menu__item:hover .side-menu__angle,
    .app-sidebar .side-menu__item.active:hover .side-menu__angle {
      color: #be1e2d !important;
      opacity: 1 !important;
      visibility: visible !important;
    }

    /* Center Page Content Inside the column */
    .main-content.app-content {
      display: flex !important;
      justify-items: center !important;
    }

    .main-content.app-content>.container {
      margin-inline: auto !important;
      max-width: 100% !important;
      width: 100% !important;
    }

    @media (min-width: 1400px) {
      .main-content.app-content>.container {
        max-width: 1920px !important;
      }
    }

    .main-sidebar-header .header-logo {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 100%;
      text-align: center;
    }

    .main-sidebar-header .header-logo img {
      margin: 0 auto;
      max-width: 100%;
    }

    /* Aggressive fix for sidebar header width on hover/overlay */
    .app-sidebar:hover .main-sidebar-header,
    [data-icon-overlay="open"] .app-sidebar .main-sidebar-header,
    [data-toggled="open"] .app-sidebar .main-sidebar-header {
      width: 16rem !important;
    }

    /* Hide ALL logos by default when sidebar is hovered/open to prevent double logos */
    .app-sidebar:hover .main-sidebar-header .header-logo img,
    [data-icon-overlay="open"] .app-sidebar .main-sidebar-header .header-logo img,
    [data-toggled="open"] .app-sidebar .main-sidebar-header .header-logo img {
      display: none !important;
    }

    /* Show only the correct DESKTOP logo based on theme mode when expanded */
    [data-theme-mode="light"] .app-sidebar:hover .main-sidebar-header .header-logo .desktop-logo,
    [data-theme-mode="light"][data-icon-overlay="open"] .app-sidebar .main-sidebar-header .header-logo .desktop-logo,
    [data-theme-mode="light"][data-toggled="open"] .app-sidebar .main-sidebar-header .header-logo .desktop-logo {
      display: block !important;
    }

    [data-theme-mode="dark"] .app-sidebar:hover .main-sidebar-header .header-logo .desktop-dark,
    [data-theme-mode="dark"][data-icon-overlay="open"] .app-sidebar .main-sidebar-header .header-logo .desktop-dark,
    [data-theme-mode="dark"][data-toggled="open"] .app-sidebar .main-sidebar-header .header-logo .desktop-dark {
      display: block !important;
    }

    /* Special handling for various toggle states in Vyzor template */
    [data-toggled="icon-hover-closed"] .app-sidebar:hover .main-sidebar-header,
    [data-toggled="icon-click-closed"] .app-sidebar:hover .main-sidebar-header,
    [data-toggled="menu-hover-closed"] .app-sidebar:hover .main-sidebar-header,
    [data-toggled="menu-click-closed"] .app-sidebar:hover .main-sidebar-header,
    [data-icon-overlay="open"] .main-sidebar-header {
      width: 16rem !important;
    }

    /* Skeleton Loader Styles */
    #loader {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: var(--custom-white, #fff);
      z-index: 9999;
      display: flex;
      overflow: hidden;
    }

    .skeleton-sidebar {
      width: 16rem;
      height: 100%;
      background-color: var(--menu-bg, #fff);
      border-right: 1px solid var(--default-border, #e2e8ee);
      padding: 1rem;
      display: flex;
      flex-direction: column;
      gap: 1rem;
      flex-shrink: 0;
    }

    .skeleton-content-wrapper {
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      height: 100%;
      background-color: var(--default-body-bg-color, #ebf4f6);
    }

    .skeleton-header {
      height: 5.25rem;
      width: 100%;
      background-color: var(--header-bg, #fff);
      border-bottom: 1px solid var(--default-border, #e2e8ee);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 2rem;
    }

    .skeleton-main {
      padding: 2rem;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
      overflow: hidden;
    }

    /* Shimmer Effect */
    .skeleton-box {
      background: #e0e0e0;
      position: relative;
      overflow: hidden;
      border-radius: 4px;
    }

    [data-theme-mode="dark"] .skeleton-box {
      background: #2a2a35;
    }

    .skeleton-box::after {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
      animation: shimmer 1.5s infinite;
      transform: skewX(-20deg);
    }

    [data-theme-mode="dark"] .skeleton-box::after {
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.05), transparent);
    }

    @keyframes shimmer {
      0% {
        transform: translateX(-150%) skewX(-20deg);
      }

      100% {
        transform: translateX(150%) skewX(-20deg);
      }
    }

    /* Specific Skeleton Elements */
    .sk-logo {
      width: 8rem;
      height: 2.5rem;
      margin-bottom: 2rem;
    }

    .sk-menu-item {
      width: 100%;
      height: 2.5rem;
      border-radius: 0.3rem;
    }

    .sk-header-left {
      width: 15rem;
      height: 2rem;
    }

    .sk-header-right {
      width: 10rem;
      height: 2rem;
    }

    .sk-title {
      width: 12rem;
      height: 2rem;
    }

    .sk-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1.5rem;
    }

    .sk-card {
      height: 8rem;
      background-color: var(--custom-white, #fff);
      border: 1px solid var(--default-border, #e2e8ee);
      border-radius: 0.5rem;
      padding: 1.25rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .sk-large-box {
      flex-grow: 1;
      background-color: var(--custom-white, #fff);
      border: 1px solid var(--default-border, #e2e8ee);
      border-radius: 0.5rem;
      width: 100%;
    }

    /* Mobile Responsive Skeleton */
    @media (max-width: 991.98px) {
      .skeleton-sidebar {
        display: none;
      }

      .sk-grid {
        grid-template-columns: repeat(1, 1fr);
      }
    }
  </style>

  <!-- ICONS CSS -->
  <link href="{{ asset('build/assets/icon-fonts/icons.css') }}" rel="stylesheet">

  @include('layouts.components.styles')

  <!-- APP CSS & APP SCSS -->
  @vite(['resources/sass/app.scss'])
  @vite('resources/js/app.js')


  @yield('styles')
  @stack('styles')

  <style>
    /* Enforce Sticky Header and Sidebar on Desktop within existing Grid Layout */
    @media (min-width: 992px) {
      .app-sidebar {
        position: sticky !important;
        top: 0 !important;
        height: 100vh !important;
        overflow-y: auto !important;
        z-index: 100 !important;
        /* Diatur rendah agar modal (1050+) bisa menutupi */
        /* Higher than header to stay on top when expanded */
      }

      .app-header {
        position: sticky !important;
        top: 0 !important;
        z-index: 99 !important;
        /* Diatur rendah agar modal (1050+) bisa menutupi */
        width: 100% !important;
        padding-inline-start: 0 !important;
        /* Remove excessive gap to bring toggle closer to sidebar */
      }

      /* Custom Content Wrapper to replace main-content app-content */
      .content-center-wrapper {
        grid-area: content;
        display: flex !important;
        flex-direction: column;
        align-items: center;
        /* Center horizontally in flex column */
        justify-content: flex-start;
        width: 100% !important;
        min-width: 0;
        padding-inline: 1.5rem !important;
        /* Added side padding/margins */
        padding-block-start: 0 !important;
        padding-block-end: 0 !important;
        margin-block: 1.25rem !important;
      }

      .content-center-wrapper>.container {
        width: 100% !important;
        margin-inline: auto !important;
      }
    }

    /* Mobile Fallback */
    @media (max-width: 991.98px) {
      .content-center-wrapper {
        padding-block-start: 1.25rem !important;
        /* Kept a small padding since the main container handles the header gap */
        padding-inline: 1rem !important;
        margin-block: 0 !important;
        width: 100% !important;
        min-width: 0 !important;
      }

      /* Fix alignment for card headers and page headers on mobile/tablet */
      .card-header,
      .page-header,
      .page-header-section {
        text-align: left !important;
        align-items: flex-start !important;
        justify-content: flex-start !important;
        flex-direction: column !important;
        gap: 0.5rem !important;
      }

      .card-header .card-title,
      .page-header-section .page-title,
      .card-title {
        text-align: left !important;
        width: 100% !important;
      }

      /* Mobile Sidebar Overlay */
      #responsive-overlay {
        cursor: pointer;
        visibility: hidden;
        opacity: 0;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 101;
        /* Above mobile header, below sidebar (102) */
        transition: opacity 0.3s ease, visibility 0.3s ease;
      }

      [data-toggled="open"] #responsive-overlay {
        visibility: visible;
        opacity: 1;
      }

      [data-toggled="open"] .app-sidebar {
        z-index: 102 !important;
      }
    }

    /* Force .page to hug content with grid */
    .page {
      display: grid !important;
      grid-template-rows: auto 1fr auto !important;
      /* Header, Content, Footer */
      height: auto !important;
      min-height: auto !important;
    }

    /* Force Global Modals to be on TOP of everything - REMOVED TO FIX Z-INDEX ISSUES */
    /*
    #globalConfirmModal, #globalAlertModal {
        z-index: 9999 !important;
    }
    .modal-backdrop {
        z-index: 9998 !important;
    }
    */
  </style>

</head>

<body class="">

  <div id="responsive-overlay"></div>

  <div class="progress-top-bar"></div>

  <!-- Start::main-switcher -->
  @include('layouts.components.switcher')
  <!-- End::main-switcher -->

  <!-- Loader -->
  <div id="loader">
    <!-- Sidebar Skeleton -->
    <div class="skeleton-sidebar">
      <div class="skeleton-box sk-logo"></div>
      <div class="skeleton-box sk-menu-item"></div>
      <div class="skeleton-box sk-menu-item"></div>
      <div class="skeleton-box sk-menu-item"></div>
      <div class="skeleton-box sk-menu-item"></div>
      <div class="skeleton-box sk-menu-item"></div>
      <div class="skeleton-box sk-menu-item"></div>
    </div>

    <!-- Content Wrapper Skeleton -->
    <div class="skeleton-content-wrapper">
      <!-- Header Skeleton -->
      <div class="skeleton-header">
        <div class="skeleton-box sk-header-left"></div>
        <div class="skeleton-box sk-header-right"></div>
      </div>

      <!-- Main Content Skeleton -->
      <div class="skeleton-main">
        <div class="skeleton-box sk-title"></div>

        <!-- Cards Grid -->
        <div class="sk-grid">
          <div class="sk-card">
            <div class="skeleton-box" style="width: 30%; height: 1rem;"></div>
            <div class="skeleton-box" style="width: 60%; height: 2rem;"></div>
          </div>
          <div class="sk-card">
            <div class="skeleton-box" style="width: 30%; height: 1rem;"></div>
            <div class="skeleton-box" style="width: 60%; height: 2rem;"></div>
          </div>
          <div class="sk-card">
            <div class="skeleton-box" style="width: 30%; height: 1rem;"></div>
            <div class="skeleton-box" style="width: 60%; height: 2rem;"></div>
          </div>
          <div class="sk-card">
            <div class="skeleton-box" style="width: 30%; height: 1rem;"></div>
            <div class="skeleton-box" style="width: 60%; height: 2rem;"></div>
          </div>
        </div>

        <!-- Large Content Area (Table/Chart) -->
        <div class="sk-large-box"></div>
      </div>
    </div>
  </div>
  <script>
    (function() {
      const loader = document.getElementById("loader");
      if (loader) {
        try {
          const themeMode = localStorage.getItem("vyzorthememode") || localStorage.getItem("vyzordarktheme");
          if (themeMode === 'dark') {
            // Update variables for dark mode instantly
            loader.style.setProperty('--custom-white', '#161623'); // body-bg-rgb
            loader.style.setProperty('--menu-bg', '#161623');
            loader.style.setProperty('--header-bg', '#161623');
            loader.style.setProperty('--default-border', 'rgba(255, 255, 255, 0.1)');
            loader.style.setProperty('--default-body-bg-color', '#262634'); // body-bg-rgb2
          }
        } catch (e) {}
      }
    })();
  </script>
  <!-- Loader -->

  <div class="page">

    <!-- Start::alerts -->
    @include('components.alert')
    <!-- End::alerts -->

    <!-- Start::main-header -->
    @include('layouts.components.main-header')
    <!-- End::main-header -->

    <!-- Start::main-sidebar -->
    @include('layouts.components.main-sidebar')
    <!-- End::main-sidebar -->

    <!-- Start::app-content -->
    <div class="main-content app-content content-center-wrapper">
      <div class="container page-container main-body-container">

        @yield('content')

      </div>
    </div>
    <!-- End::content  -->

    <!-- Start::main-footer -->
    @include('layouts.components.footer')
    <!-- End::main-footer -->

  </div>

  <!-- Start::main-modal -->
  @include('layouts.components.modal')
  <!-- End::main-modal -->

  @yield('modals')

  <!-- Scripts -->
  @include('layouts.components.scripts')

  <script>
    /**
     * Global Confirm Modal Helper
     * @param {string} message - The message to display
     * @param {function} onConfirm - Callback when user clicks 'Confirm'
     * @param {string} title - Optional title
     * @param {string} confirmBtnText - Optional confirm button text
     * @param {string} confirmBtnClass - Optional confirm button class
     */
    window.showConfirmModal = function(message, onConfirm, title = 'Konfirmasi Tindakan', confirmBtnText =
      'Ya, Lanjutkan', confirmBtnClass = 'btn-danger') {
      const modalEl = document.getElementById('globalConfirmModal');
      const modal = new bootstrap.Modal(modalEl);
      const messageEl = document.getElementById('globalConfirmModalMessage');
      const titleEl = document.getElementById('globalConfirmModalLabel');
      const confirmBtn = document.getElementById('globalConfirmModalBtn');

      messageEl.textContent = message;
      titleEl.textContent = title;
      confirmBtn.textContent = confirmBtnText;

      // Reset classes and add the requested one
      confirmBtn.className = 'btn btn-wave shadow-sm ' + confirmBtnClass;

      // Clone button to remove old event listeners
      const newConfirmBtn = confirmBtn.cloneNode(true);
      confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

      newConfirmBtn.addEventListener('click', function() {
        onConfirm();
        modal.hide();
      });

      modal.show();
    };

    // Global listener for forms with data-confirm
    document.addEventListener('submit', function(e) {
      const form = e.target;
      if (form.hasAttribute('data-confirm')) {
        e.preventDefault();
        const message = form.getAttribute('data-confirm') || 'Apakah Anda yakin ingin melanjutkan?';
        const title = form.getAttribute('data-confirm-title') || 'Konfirmasi Tindakan';
        const btnText = form.getAttribute('data-confirm-btn') || 'Ya, Lanjutkan';
        const btnClass = form.getAttribute('data-confirm-btn-class') || 'btn-danger';

        window.showConfirmModal(message, function() {
          // Submit form without triggering this listener again
          form.removeAttribute('data-confirm');
          form.submit();
        }, title, btnText, btnClass);
      }
    });

    /**
     * Global Alert Modal Helper
     * @param {string} message - The message to display
     * @param {string} type - 'success', 'error', 'info', 'warning'
     * @param {string} title - Optional title
     */
    window.showAlertModal = function(message, type = 'info', title = 'Informasi') {
      const modal = new bootstrap.Modal(document.getElementById('globalAlertModal'));
      const messageEl = document.getElementById('globalAlertModalMessage');
      const titleEl = document.getElementById('globalAlertModalLabel');
      const iconContainer = document.getElementById('globalAlertModalIcon');

      messageEl.textContent = message;
      titleEl.textContent = title;

      // Set icon and colors based on type
      let iconHtml = '<i class="ri-information-line fs-24"></i>';
      let bgClass = 'bg-primary-transparent text-primary';

      if (type === 'success') {
        iconHtml = '<i class="ri-checkbox-circle-line fs-24"></i>';
        bgClass = 'bg-success-transparent text-success';
      } else if (type === 'error') {
        iconHtml = '<i class="ri-error-warning-line fs-24"></i>';
        bgClass = 'bg-danger-transparent text-danger';
      } else if (type === 'warning') {
        iconHtml = '<i class="ri-alert-line fs-24"></i>';
        bgClass = 'bg-warning-transparent text-warning';
      }

      iconContainer.innerHTML = iconHtml;
      iconContainer.className = 'flex-shrink-0 p-2 rounded-3 ' + bgClass;

      modal.show();
    };

    // Override default window.confirm and window.alert if needed? 
    // Usually better to call the custom functions explicitly to handle async.
  </script>

  <!-- Sticky JS -->
  <script src="{{ asset('build/assets/sticky.js') }}"></script>

  <!-- Defaultmenu JS -->
  @vite('resources/assets/js/defaultmenu.js')

  <!-- Custom-Switcher JS -->
  @vite('resources/assets/js/custom-switcher.js')

  <!-- App JS-->

  <!-- Responsive Sidebar Toggle JS -->
  <script>
    document.addEventListener('click', function(e) {
      if (e.target.id === 'responsive-overlay' || e.target.closest('#sidebar-close-mobile')) {
        if (window.innerWidth < 992) {
          document.documentElement.setAttribute("data-toggled", "close");
        }
      }
    });
  </script>
  <!-- End Scripts -->

</body>

</html>
