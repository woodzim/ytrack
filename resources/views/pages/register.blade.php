@extends('layouts.custom-master')

@php
    $bodyClass = 'authentication-background';
@endphp

@section('content')
    <div class="authentication-basic-background">
        <img src="{{ asset('build/assets/images/media/backgrounds/9.png') }}" alt="">
    </div>

    <div class="container">
        <div class="row justify-content-center align-items-center authentication authentication-basic min-vh-100 py-4">
            <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-6 col-sm-8 col-12">
                <div class="card custom-card border-0 my-4">
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <a href="{{ route('login') }}">
                                <img src="{{ asset('images/long-logo.png') }}" alt="logo"
                                    class="desktop-dark mt-3 mt-md-0">
                            </a>
                        </div>
                        <div>
                            <h4 class="mb-1 fw-semibold">
                                <span>Daftar Perusahaan</span>
                            </h4>
                            <p class="mb-4 text-muted fw-normal">Buat akun perusahaan baru untuk GPS Tracking System</p>
                        </div>

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

                        <form method="POST" action="{{ route('register.store') }}">
                            @csrf

                            <!-- Nama Pengguna -->
                            <div class="mb-3">
                                <label for="name" class="form-label text-default">Nama Pengguna</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" placeholder="Masukkan nama Anda"
                                    value="{{ old('name') }}" required>
                                @error('name')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label text-default">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" placeholder="Masukkan email perusahaan"
                                    value="{{ old('email') }}" required>
                                @error('email')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label text-default">Password</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="Masukkan password (minimal 8 karakter)" required>
                                    <a href="javascript:void(0);" class="show-password-button text-muted"
                                        onclick="createpassword('password',this)"><i class="ri-eye-off-line align-middle"></i></a>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Konfirmasi Password -->
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label text-default">Konfirmasi Password</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                        id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi password" required>
                                    <a href="javascript:void(0);" class="show-password-button text-muted"
                                        onclick="createpassword('password_confirmation',this)"><i class="ri-eye-off-line align-middle"></i></a>
                                </div>
                                @error('password_confirmation')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Nama Perusahaan -->
                            <div class="mb-3">
                                <label for="nama_perusahaan" class="form-label text-default">Nama Perusahaan</label>
                                <input type="text" class="form-control @error('nama_perusahaan') is-invalid @enderror"
                                    id="nama_perusahaan" name="nama_perusahaan" placeholder="Masukkan nama perusahaan"
                                    value="{{ old('nama_perusahaan') }}" required>
                                @error('nama_perusahaan')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Alamat Perusahaan -->
                            <div class="mb-3">
                                <label for="alamat_perusahaan" class="form-label text-default">Alamat Perusahaan</label>
                                <textarea class="form-control @error('alamat_perusahaan') is-invalid @enderror"
                                    id="alamat_perusahaan" name="alamat_perusahaan" placeholder="Masukkan alamat perusahaan"
                                    rows="3" required>{{ old('alamat_perusahaan') }}</textarea>
                                @error('alamat_perusahaan')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Register Button -->
                            <div class="d-grid mt-4 mb-3">
                                <button type="submit" class="btn btn-primary">Daftar</button>
                            </div>

                            <!-- Login Link -->
                            <div class="text-center">
                                <p class="text-muted fw-normal">
                                    Sudah punya akun? 
                                    <a href="{{ route('login') }}" class="text-primary fw-semibold">Masuk di sini</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Show Password JS -->
    <script src="{{ asset('build/assets/show-password.js') }}"></script>
@endsection
