@extends('layouts.master')

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
            <div class="card mb-3">
                <div class="card-body">
                    <form action="{{ route('users.index') }}" method="GET" class="d-flex gap-2 flex-wrap align-items-end">
                        <div class="flex-grow-1" style="min-width: 200px;">
                            <label for="searchInput" class="form-label mb-2">Cari Pengguna</label>
                            <input type="text" id="searchInput" name="search" class="form-control"
                                placeholder="Cari nama atau email..." value="{{ $search }}">
                        </div>
                        <div>
                            <label for="roleFilter" class="form-label mb-2">Role</label>
                            <select id="roleFilter" name="role" class="form-select">
                                <option value="">Semua Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ $filter_role === $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-wave">
                            <i class="ri-search-line me-2"></i>Cari
                        </button>
                        @if ($search || $filter_role !== '')
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-wave">
                                <i class="ri-close-line me-2"></i>Reset
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <div class="card table-wrapper">
                <div class="card-header border-bottom-0 bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Daftar Pengguna ({{ $users->total() }})</h5>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if ($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 18%;">Nama</th>
                                        <th style="width: 18%;">Email</th>
                                        <th style="width: 12%;">Role</th>
                                        <th style="width: 14%;">Status</th>
                                        <th style="width: 16%;">Tanggal Daftar</th>
                                        <th style="width: 22%;" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td data-label="Nama">
                                                <strong>{{ $user->name }}</strong>
                                            </td>
                                            <td data-label="Email">
                                                <small class="text-muted">{{ $user->email }}</small>
                                            </td>
                                            <td data-label="Role">
                                                @forelse ($user->roles as $role)
                                                    <span class="badge bg-primary">{{ ucfirst($role->name) }}</span>
                                                @empty
                                                    <span class="badge bg-secondary">Tidak Ada Role</span>
                                                @endforelse
                                            </td>
                                            <td data-label="Status">
                                                @if ($user->hasRole('perusahaan'))
                                                    @if ($user->is_validated)
                                                        <span class="badge bg-success">
                                                            <i class="ri-check-line me-1"></i>Tervalidasi
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning">
                                                            <i class="ri-time-line me-1"></i>Menunggu Validasi
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">-</span>
                                                @endif
                                            </td>
                                            <td data-label="Tanggal Daftar">
                                                <small class="text-muted">
                                                    {{ $user->created_at?->format('d-m-Y') ?? '-' }}
                                                </small>
                                            </td>
                                            <td data-label="Aksi" class="text-center">
                                                @if ($user->hasRole('perusahaan'))
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-light btn-wave dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="ri-more-2-fill"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            @if (!$user->is_validated)
                                                                <li>
                                                                    <button type="button" class="dropdown-item text-success"
                                                                        data-bs-toggle="modal" data-bs-target="#validateUserModal"
                                                                        data-user-id="{{ $user->id }}"
                                                                        data-user-name="{{ $user->name }}"
                                                                        data-is-validated="{{ $user->is_validated ? '1' : '0' }}">
                                                                        <i class="ri-check-line me-2 align-middle"></i>Validasi Akun
                                                                    </button>
                                                                </li>
                                                            @else
                                                                <li>
                                                                    <button type="button" class="dropdown-item text-warning"
                                                                        data-bs-toggle="modal" data-bs-target="#validateUserModal"
                                                                        data-user-id="{{ $user->id }}"
                                                                        data-user-name="{{ $user->name }}"
                                                                        data-is-validated="{{ $user->is_validated ? '1' : '0' }}">
                                                                        <i class="ri-close-line me-2 align-middle"></i>Batalkan Validasi
                                                                    </button>
                                                                </li>
                                                            @endif
                                                            <li>
                                                                <button type="button" class="dropdown-item text-primary"
                                                                    data-bs-toggle="modal" data-bs-target="#resetPasswordModal"
                                                                    data-user-id="{{ $user->id }}"
                                                                    data-user-name="{{ $user->name }}">
                                                                    <i class="ri-lock-password-line me-2 align-middle"></i>Reset Password
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                @else
                                                    <span class="text-muted small">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-4 text-center">
                            <div class="mb-3">
                                <i class="ri-user-line" style="font-size: 48px; color: #ddd;"></i>
                            </div>
                            <h5 class="text-muted mb-2">Tidak ada pengguna</h5>
                            <p class="text-muted mb-3">Tidak ada data pengguna yang sesuai dengan kriteria pencarian</p>
                        </div>
                    @endif
                </div>

                @if ($users->hasPages())
                    <div class="card-footer bg-white border-top">
                        <nav aria-label="Page navigation">
                            {{ $users->links() }}
                        </nav>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- End::app-content -->

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
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" placeholder="Konfirmasi password baru" required>
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
                validateUserModal.addEventListener('show.bs.modal', function(event) {
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
                });
            }
        });
    </script>
@endsection
