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
                                        <th style="width: 20%;">Nama</th>
                                        <th style="width: 20%;">Email</th>
                                        <th style="width: 15%;">Role</th>
                                        <th style="width: 20%;">Tanggal Daftar</th>
                                        <th style="width: 25%;">Aksi</th>
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
                                            <td data-label="Tanggal Daftar">
                                                <small class="text-muted">
                                                    {{ $user->created_at?->format('d-m-Y') ?? '-' }}
                                                </small>
                                            </td>
                                            <td data-label="Aksi">
                                                @if ($user->hasRole('perusahaan'))
                                                    <button type="button" class="btn btn-sm btn-primary btn-wave"
                                                        data-bs-toggle="modal" data-bs-target="#resetPasswordModal"
                                                        data-user-id="{{ $user->id }}"
                                                        data-user-name="{{ $user->name }}">
                                                        <i class="ri-lock-password-line me-1"></i>Atur Ulang Password
                                                    </button>
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
@endsection

@section('scripts')
    <script>
        // Reset password modal setup
        const resetPasswordModal = document.getElementById('resetPasswordModal');
        const resetPasswordForm = document.getElementById('resetPasswordForm');
        const userNameDisplay = document.getElementById('userNameDisplay');
        const passwordInput = document.getElementById('password');
        const passwordConfirmInput = document.getElementById('password_confirmation');
        const togglePasswordBtn = document.getElementById('togglePassword');
        const togglePasswordConfirmBtn = document.getElementById('togglePasswordConfirm');

        // Handle modal show event to populate form with user data
        resetPasswordModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const userId = button.getAttribute('data-user-id');
            const userName = button.getAttribute('data-user-name');

            // Update modal title and form
            userNameDisplay.textContent = `Atur ulang password untuk: ${userName}`;

            // Update form action
            resetPasswordForm.action = `{{ route('users.index') }}/${userId}/password`;

            // Clear previous values
            passwordInput.value = '';
            passwordConfirmInput.value = '';
            passwordInput.type = 'password';
            passwordConfirmInput.type = 'password';
            togglePasswordBtn.innerHTML = '<i class="ri-eye-line"></i>';
            togglePasswordConfirmBtn.innerHTML = '<i class="ri-eye-line"></i>';
        });

        // Toggle password visibility
        togglePasswordBtn.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                togglePasswordBtn.innerHTML = '<i class="ri-eye-off-line"></i>';
            } else {
                passwordInput.type = 'password';
                togglePasswordBtn.innerHTML = '<i class="ri-eye-line"></i>';
            }
        });

        // Toggle password confirmation visibility
        togglePasswordConfirmBtn.addEventListener('click', function() {
            if (passwordConfirmInput.type === 'password') {
                passwordConfirmInput.type = 'text';
                togglePasswordConfirmBtn.innerHTML = '<i class="ri-eye-off-line"></i>';
            } else {
                passwordConfirmInput.type = 'password';
                togglePasswordConfirmBtn.innerHTML = '<i class="ri-eye-line"></i>';
            }
        });

        // Handle form submission
        resetPasswordForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Validate password match
            if (passwordInput.value !== passwordConfirmInput.value) {
                alert('Password tidak sesuai dengan konfirmasi password');
                return;
            }

            // Submit form
            this.submit();
        });
    </script>
@endsection
