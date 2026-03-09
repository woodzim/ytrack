<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light"
  data-header-styles="light" data-menu-styles="light" data-toggled="close">

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
  <title> Y-TRACK | Login </title>

  <!-- Favicon -->
  <link rel="icon" href="{{ asset('images/square-logo.ico') }}" type="image/x-icon">

  <!-- Main Theme Js -->
  <script src="{{ asset('build/assets/authentication-main.js') }}"></script>

  <!-- Bootstrap Css -->
  <link id="style" href="{{ asset('build/assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

  <!-- ICONS CSS -->
  <link href="{{ asset('build/assets/icon-fonts/icons.css') }}" rel="stylesheet">

  <!-- APP CSS & APP SCSS -->
  @vite(['resources/sass/app.scss'])
  @vite('resources/js/app.js')

  @yield('styles')

</head>

<body class="{{ $bodyClass }}">
  @yield('content')

  <!-- Bootstrap JS -->
  <script src="{{ asset('build/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

  @include('layouts.components.modal')

  <script>
    /**
     * Global Confirm Modal Helper
     */
    window.showConfirmModal = function(message, onConfirm, title = 'Konfirmasi Tindakan', confirmBtnText =
      'Ya, Lanjutkan', confirmBtnClass = 'btn-danger') {
      const modalEl = document.getElementById('globalConfirmModal');
      if (!modalEl) return;

      const modal = new bootstrap.Modal(modalEl);
      const messageEl = document.getElementById('globalConfirmModalMessage');
      const titleEl = document.getElementById('globalConfirmModalLabel');
      const confirmBtn = document.getElementById('globalConfirmModalBtn');

      if (messageEl) messageEl.textContent = message;
      if (titleEl) titleEl.textContent = title;
      if (confirmBtn) {
        confirmBtn.textContent = confirmBtnText;
        confirmBtn.className = 'btn btn-wave shadow-sm ' + confirmBtnClass;

        // Clone button to remove old event listeners
        const newConfirmBtn = confirmBtn.cloneNode(true);
        confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

        newConfirmBtn.addEventListener('click', function() {
          onConfirm();
          modal.hide();
        });
      }

      modal.show();
    };

    /**
     * Global Alert Modal Helper
     */
    window.showAlertModal = function(message, type = 'info', title = 'Informasi') {
      const modalEl = document.getElementById('globalAlertModal');
      if (!modalEl) return;

      const modal = new bootstrap.Modal(modalEl);
      const messageEl = document.getElementById('globalAlertModalMessage');
      const titleEl = document.getElementById('globalAlertModalLabel');
      const iconContainer = document.getElementById('globalAlertModalIcon');

      if (messageEl) messageEl.textContent = message;
      if (titleEl) titleEl.textContent = title;

      if (iconContainer) {
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
      }

      modal.show();
    };
  </script>

  @yield('scripts')

</body>

</html>
