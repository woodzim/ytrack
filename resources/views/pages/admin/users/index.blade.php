@extends('layouts.master')

@section('styles')
  <style>
    /* Force parent containers to allow overflow so dropdowns aren't cut off */
    .table-wrapper,
    .table-wrapper .card-body,
    .table-responsive {
      overflow: visible !important;
    }

    /* Hide the default Bootstrap caret */
    .hide-arrow::after {
      display: none !important;
    }

    /* Custom Dropdown Styling */
    .custom-filter-dropdown {
      height: 38px;
      border: 1px solid #dee2e6;
      background-color: #fff;
      display: flex !important;
      align-items: center !important;
      justify-content: space-between !important;
      padding: 0.375rem 0.75rem;
      border-radius: 0.25rem;
      width: 100%;
      transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
      text-align: left;
    }

    .custom-filter-dropdown:hover {
      border-color: #b1b5b9;
    }

    .custom-filter-dropdown:focus {
      border-color: #86b7fe;
      outline: 0;
      box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
  </style>
@endsection

@section('content')
  <!-- Start::page-header -->
  <div class="page-header-section d-flex align-items-center justify-content-between">
    <div>
      <h1>Pengguna</h1>
      <p>Daftar pengguna dalam sistem</p>
    </div>
  </div>
  <!-- End::page-header -->

  <!-- Start::app-content -->
  <div class="row">
    <div class="col-12">
      <!-- Filter & Search Section -->
      <div class="card mb-3 border-0 shadow-none bg-transparent">
        <div class="p-0">
          <form action="{{ route('users.index') }}" method="GET" id="filterForm"
            class="d-flex gap-2 flex-wrap align-items-end">
            <div class="flex-grow-1" style="min-width: 250px;">
              <label for="searchInput" class="form-label mb-2">Cari Pengguna</label>
              <div class="input-group">
                <span class="input-group-text bg-white border-end-0" style="height: 38px;">
                  <i class="ri-search-line text-muted"></i>
                </span>
                <input type="text" id="searchInput" name="search" class="form-control border-start-0 ps-1"
                  placeholder="Cari nama atau email..." value="{{ $search }}" autocomplete="off"
                  style="height: 38px;">
              </div>
            </div>
            <div style="min-width: 180px;">
              <label class="form-label mb-2">Role</label>
              <input type="hidden" name="role" id="role_input" value="{{ $filter_role }}">
              <div class="dropdown">
                <button class="custom-filter-dropdown dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown"
                  aria-expanded="false" id="roleDropdown">
                  <div class="d-flex align-items-center text-truncate">
                    <i class="ri-user-settings-line me-2 text-muted"></i>
                    <span class="text-truncate">
                      {{ $filter_role ? ucfirst($filter_role) : 'Semua Role' }}
                    </span>
                  </div>
                  <i class="ri-arrow-down-s-line text-muted"></i>
                </button>
                <ul class="dropdown-menu w-100 shadow-sm">
                  <li><a class="dropdown-item {{ $filter_role === '' ? 'active' : '' }}" href="#"
                      onclick="selectFilter(this, 'role', '', 'Semua Role')">Semua Role</a></li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  @foreach ($roles as $role)
                    <li><a class="dropdown-item {{ $filter_role === $role->name ? 'active' : '' }}" href="#"
                        onclick="selectFilter(this, 'role', '{{ $role->name }}', '{{ ucfirst($role->name) }}')">{{ ucfirst($role->name) }}</a>
                    </li>
                  @endforeach
                </ul>
              </div>
            </div>

            <div id="reset-container" class="{{ $search || $filter_role !== '' ? '' : 'd-none' }}">
              <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-wave d-flex align-items-center"
                id="resetBtn" style="height: 38px;">
                <i class="ri-close-line me-2"></i>Reset
              </a>
            </div>
          </form>
        </div>
      </div>

      <div class="card table-wrapper" id="table-container">
        @include('pages.admin.users.table')
      </div>
    </div>
  </div>
  <!-- End::app-content -->
@endsection

@section('modals')
  <!-- Reset Password Modal -->
  <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="resetPasswordLabel">
            <i class="ri-lock-password-line me-2"></i>Atur Ulang Password
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="resetPasswordForm" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <div class="alert alert-info mb-3" role="alert">
              <i class="ri-information-line me-2"></i>
              <span id="userNameDisplay"></span>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Password Baru</label>
              <div class="input-group">
                <input type="password" class="form-control" id="password" name="password"
                  placeholder="Minimal 8 karakter" required>
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                  <i class="ri-eye-line"></i>
                </button>
              </div>
              <small class="text-muted d-block mt-1">Minimal 8 karakter</small>
              @error('password')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
              <div class="input-group">
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                  placeholder="Konfirmasi password baru" required>
                <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                  <i class="ri-eye-line"></i>
                </button>
              </div>
              @error('password_confirmation')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Password</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Validate User Modal -->
  <div class="modal fade" id="validateUserModal" tabindex="-1" aria-labelledby="validateUserLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="validateUserLabel">
            <i class="ri-shield-check-line me-2"></i>Validasi Akun Perusahaan
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="validateUserForm" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <div class="alert alert-info mb-3" role="alert">
              <i class="ri-information-line me-2"></i>
              <span id="validateUserNameDisplay"></span>
            </div>
            <div id="validateMessage"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" id="validateSubmitBtn" class="btn btn-primary">Validasi</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const filterForm = document.getElementById('filterForm');
      const searchInput = document.getElementById('searchInput');
      const tableContainer = document.getElementById('table-container');
      const resetContainer = document.getElementById('reset-container');
      let debounceTimer;

      const fetchTable = (url) => {
        tableContainer.style.opacity = '0.5';
        fetch(url, {
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            }
          })
          .then(response => response.text())
          .then(html => {
            tableContainer.innerHTML = html;
            tableContainer.style.opacity = '1';
            if (typeof Waves !== 'undefined') Waves.init();
          })
          .catch(error => {
            console.error('Error fetching table:', error);
            tableContainer.style.opacity = '1';
          });
      };

      const triggerFetch = () => {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData).toString();
        const url = `${filterForm.action}?${params}`;

        const search = formData.get('search');
        const role = formData.get('role');

        if ((search && search.trim() !== "") || role !== "") {
          resetContainer.classList.remove('d-none');
        } else {
          resetContainer.classList.add('d-none');
        }

        window.history.pushState({}, '', url);
        fetchTable(url);
      };

      window.selectFilter = function(el, type, value, label) {
        document.getElementById(type + '_input').value = value;
        const btn = document.getElementById(type + 'Dropdown');

        const labelContainer = btn.querySelector('span');
        if (labelContainer) {
          labelContainer.textContent = label;
        }

        const dropdownMenu = el.closest('.dropdown-menu');
        dropdownMenu.querySelectorAll('.dropdown-item').forEach(item => {
          item.classList.remove('active');
        });
        el.classList.add('active');

        triggerFetch();
      };

      searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(triggerFetch, 500);
      });

      tableContainer.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination a');
        if (link) {
          e.preventDefault();
          const url = link.href;
          window.history.pushState({}, '', url);
          fetchTable(url);
          tableContainer.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });

      document.getElementById('resetBtn').addEventListener('click', function(e) {
        e.preventDefault();
        searchInput.value = '';
        document.getElementById('role_input').value = '';

        const rDropdown = document.getElementById('roleDropdown');
        if (rDropdown.querySelector('span')) rDropdown.querySelector('span').textContent = 'Semua Role';

        document.querySelectorAll('.dropdown-menu .dropdown-item').forEach(item => {
          item.classList.remove('active');
        });
        document.querySelectorAll('.dropdown-menu li:first-child .dropdown-item').forEach(item => {
          item.classList.add('active');
        });

        resetContainer.classList.add('d-none');
        const url = this.href;
        window.history.pushState({}, '', url);
        fetchTable(url);
      });

      // Reset password modal setup
      const resetPasswordModal = document.getElementById('resetPasswordModal');
      const resetPasswordForm = document.getElementById('resetPasswordForm');
      const userNameDisplay = document.getElementById('userNameDisplay');
      const passwordInput = document.getElementById('password');
      const passwordConfirmInput = document.getElementById('password_confirmation');
      const togglePasswordBtn = document.getElementById('togglePassword');
      const togglePasswordConfirmBtn = document.getElementById('togglePasswordConfirm');

      if (resetPasswordModal) {
        resetPasswordModal.addEventListener('show.bs.modal', function(event) {
          const button = event.relatedTarget;
          const userId = button.getAttribute('data-user-id');
          const userName = button.getAttribute('data-user-name');

          userNameDisplay.textContent = `Atur ulang password untuk: ${userName}`;
          resetPasswordForm.action = `{{ route('users.index') }}/${userId}/password`;

          passwordInput.value = '';
          passwordConfirmInput.value = '';
          passwordInput.type = 'password';
          passwordConfirmInput.type = 'password';
          togglePasswordBtn.innerHTML = '<i class="ri-eye-line"></i>';
          togglePasswordConfirmBtn.innerHTML = '<i class="ri-eye-line"></i>';
        });
      }

      if (togglePasswordBtn) {
        togglePasswordBtn.addEventListener('click', function() {
          if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            togglePasswordBtn.innerHTML = '<i class="ri-eye-off-line"></i>';
          } else {
            passwordInput.type = 'password';
            togglePasswordBtn.innerHTML = '<i class="ri-eye-line"></i>';
          }
        });
      }

      if (togglePasswordConfirmBtn) {
        togglePasswordConfirmBtn.addEventListener('click', function() {
          if (passwordConfirmInput.type === 'password') {
            passwordConfirmInput.type = 'text';
            togglePasswordConfirmBtn.innerHTML = '<i class="ri-eye-off-line"></i>';
          } else {
            passwordConfirmInput.type = 'password';
            togglePasswordConfirmBtn.innerHTML = '<i class="ri-eye-line"></i>';
          }
        });
      }

      if (resetPasswordForm) {
        resetPasswordForm.addEventListener('submit', function(e) {
          if (passwordInput.value !== passwordConfirmInput.value) {
            e.preventDefault();
            alert('Password tidak sesuai dengan konfirmasi password');
            return;
          }
        });
      }

      // Validate User Modal setup
      const validateUserModal = document.getElementById('validateUserModal');
      const validateUserForm = document.getElementById('validateUserForm');
      const validateUserNameDisplay = document.getElementById('validateUserNameDisplay');
      const validateMessage = document.getElementById('validateMessage');
      const validateSubmitBtn = document.getElementById('validateSubmitBtn');

      if (validateUserModal) {
        // Use event delegation for dynamic content
        document.body.addEventListener('show.bs.modal', function(event) {
          if (event.target.id === 'validateUserModal') {
            const button = event.relatedTarget;
            const userId = button.getAttribute('data-user-id');
            const userName = button.getAttribute('data-user-name');
            const isValidated = button.getAttribute('data-is-validated') === '1';

            validateUserNameDisplay.textContent = `Pengguna: ${userName}`;

            const validateUrl = `{{ route('users.validate', '__ID__') }}`.replace('__ID__',
              userId);
            const rejectUrl = `{{ route('users.reject', '__ID__') }}`.replace('__ID__', userId);

            if (isValidated) {
              validateMessage.innerHTML =
                '<p>Apakah Anda yakin ingin <strong>membatalkan validasi</strong> akun ini? Pengguna tidak akan dapat login ke sistem.</p>';
              validateSubmitBtn.textContent = 'Batalkan Validasi';
              validateSubmitBtn.className = 'btn btn-warning';
              validateUserForm.action = rejectUrl;
            } else {
              validateMessage.innerHTML =
                '<p>Apakah Anda yakin ingin <strong>memvalidasi</strong> akun ini? Pengguna akan dapat login ke sistem.</p>';
              validateSubmitBtn.textContent = 'Validasi Akun';
              validateSubmitBtn.className = 'btn btn-success';
              validateUserForm.action = validateUrl;
            }
          }
        });
      }
    });
  </script>
@endsection
