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
                                <span>Menunggu Verifikasi Admin</span>
                            </h4>
                            <p class="mb-4 text-muted fw-normal">Email Anda sudah terverifikasi, namun akun Anda masih menunggu validasi dari administrator.</p>
                        </div>

                        <p class="mb-4">
                            Silakan tunggu sampai administrator memvalidasi pendaftaran perusahaan Anda. Anda akan dapat mengakses dashboard setelah akun Anda aktif.
                        </p>

                         <div class="text-center mt-4">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-link text-danger">Logout</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
