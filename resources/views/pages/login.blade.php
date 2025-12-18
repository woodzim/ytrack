@extends('layouts.custom-master')

@php
    // Passing the bodyClass variable from the view to the layout
    $bodyClass = 'authentication-background';
@endphp

@section('styles')
@endsection

@section('content')
    <div class="authentication-basic-background">
        <img src="{{ asset('build/assets/images/media/backgrounds/9.png') }}" alt="">
    </div>

    <div class="container">
        <div class="row justify-content-center align-items-center authentication authentication-basic h-100">
            <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-6 col-sm-8 col-12">
                <div class="card custom-card border-0 my-4">
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <a href="{{ url('index') }}">
                                <img src="{{ asset('build/assets/images/brand-logos/toggle-logo.png') }}" alt="logo"
                                    class="desktop-dark">
                            </a>
                        </div>
                        <div>
                            <h4 class="mb-1 fw-semibold">
                              <span>Hai, Selamat Datang</span>
                              <br>di Y-Track!
                            </h4>
                            <p class="mb-4 text-muted fw-normal">Silakan masukkan kredensial Anda</p>
                        </div>
                        <div class="row gy-3">
                            <form method="POST" action="{{ route('login.store') }}" class="w-100">
                                @csrf
                                <div class="col-xl-12 mb-3">
                                    <label for="signin-email" class="form-label text-default">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="signin-email" name="email" placeholder="Masukkan Email"
                                        value="{{ old('email') }}" required autofocus>
                                    @error('email')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-xl-12 mb-2">
                                    <label for="signin-password" class="form-label text-default d-block">Password</label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="signin-password" name="password" placeholder="Masukkan Password" required>
                                        <a href="javascript:void(0);" class="show-password-button text-muted"
                                            onclick="createpassword('signin-password',this)" id="button-addon2"><i
                                                class="ri-eye-off-line align-middle"></i></a>
                                    </div>
                                    @error('password')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                    <div class="mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" value="true"
                                                id="defaultCheck1">
                                            <label class="form-check-label" for="defaultCheck1">
                                                Ingat Saya
                                            </label>
                                            {{-- <a href="{{ url('reset-password-basic') }}"
                                                class="float-end link-danger fw-medium fs-12">Forget password ?</a> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="d-grid mt-3">
                                    <button type="submit" class="btn btn-primary">Masuk</button>
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
