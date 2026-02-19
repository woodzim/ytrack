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
    (function () {
      const html = document.querySelector('html');
      const getStorage = (key) => {
        try { return localStorage.getItem(key); } catch (e) { return null; }
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
    [data-nav-layout="vertical"] .slide.has-sub.open > .slide-menu {
        display: block !important;
    }
    /* Force sidebar arrow visibility with high specificity */
    .app-sidebar .side-menu__angle {
        color: rgba(0, 0, 0, 0.3) !important; /* Default visible grey */
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
  </style>

  <!-- ICONS CSS -->
  <link href="{{ asset('build/assets/icon-fonts/icons.css') }}" rel="stylesheet">

  @include('layouts.components.styles')

  <!-- APP CSS & APP SCSS -->
  @vite(['resources/sass/app.scss'])
  @vite('resources/js/app.js')


  @yield('styles')
  @stack('styles')

</head>

<body class="">

  <div class="progress-top-bar"></div>

  <!-- Start::main-switcher -->
  @include('layouts.components.switcher')
  <!-- End::main-switcher -->

  <!-- Loader -->
  <div id="loader">
    <img src="{{ asset('build/assets/images/media/loader.svg') }}" alt="">
  </div>
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
    <div class="main-content app-content">
      <div class="container-fluid page-container main-body-container">

        @yield('content')

      </div>
    </div>
    <!-- End::content  -->

    <!-- Start::main-footer -->
    @include('layouts.components.footer')
    <!-- End::main-footer -->

    <!-- Start::main-modal -->
    @include('layouts.components.modal')
    <!-- End::main-modal -->

    @yield('modals')

  </div>

  <!-- Scripts -->
  @include('layouts.components.scripts')

  <!-- Sticky JS -->
  <script src="{{ asset('build/assets/sticky.js') }}"></script>

  <!-- Defaultmenu JS -->
  @vite('resources/assets/js/defaultmenu.js')

  <!-- Custom-Switcher JS -->
  @vite('resources/assets/js/custom-switcher.js')

  <!-- App JS-->

  <!-- End Scripts -->

</body>

</html>
