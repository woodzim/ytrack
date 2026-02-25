@extends('layouts.master')

@section('content')
    <!-- Start::page-header -->
    <div class="page-header-section d-flex align-items-center justify-content-between">
        <div>
            <h1>Profil Saya</h1>
            <p>Kelola informasi profil dan keamanan akun</p>
        </div>
    </div>
    <!-- End::page-header -->

    <!-- Start::app-content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Edit Profil</h5>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="ri-check-line me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Nama -->
                        <div class="mb-4">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" placeholder="Masukkan nama Anda"
                                value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" placeholder="Masukkan email Anda"
                                value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Divider -->
                        <hr class="my-4">

                        <!-- Password Section -->
                        <div class="mb-4">
                            <h6 class="mb-3">Ubah Password</h6>
                            <p class="text-muted small mb-3">Kosongkan kolom password jika Anda tidak ingin mengubahnya</p>

                            <!-- Password Baru -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password Baru</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="Masukkan password baru (minimal 8 karakter)">
                                    <a href="javascript:void(0);" class="show-password-button text-muted"
                                        onclick="createpassword('password',this)"><i class="ri-eye-off-line align-middle"></i></a>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Konfirmasi Password -->
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                        id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi password baru">
                                    <a href="javascript:void(0);" class="show-password-button text-muted"
                                        onclick="createpassword('password_confirmation',this)"><i class="ri-eye-off-line align-middle"></i></a>
                                </div>
                                @error('password_confirmation')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-wave">
                                <i class="ri-save-line me-2"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-wave">
                                <i class="ri-close-line me-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Account Info Card -->
            <div class="card mt-4">
                <div class="card-header py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Informasi Akun</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Akun Dibuat</p>
                            <p class="mb-3">{{ $user->created_at?->format('d-m-Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Update Terakhir</p>
                            <p class="mb-3">{{ $user->updated_at?->format('d-m-Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@endsection

@section('scripts')
    <!-- Show Password JS -->
    <script src="{{ asset('build/assets/show-password.js') }}"></script>
@endsection
