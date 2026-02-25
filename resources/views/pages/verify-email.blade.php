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
                                <img src="{{ asset('images/long-logo.webp') }}" alt="logo"
                                    class="desktop-dark mt-3 mt-md-0">
                            </a>
                        </div>
                        <div>
                            <h4 class="mb-1 fw-semibold">
                                <span>Verifikasi Email</span>
                            </h4>
                            <p class="mb-4 text-muted fw-normal">Link verifikasi telah dikirim ke email Anda.</p>
                        </div>

                        @if (session('message'))
                            <div class="alert alert-success" role="alert">
                                {{ session('message') }}
                            </div>
                        @endif
                         @if (session('status') == 'verification-link-sent')
                            <div class="alert alert-success" role="alert">
                                Link verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.
                            </div>
                        @endif


                        <p class="mb-4">
                            Sebelum melanjutkan, silakan periksa email Anda untuk link verifikasi.
                            Jika Anda tidak menerima email tersebut, silakan klik tombol di bawah ini.
                        </p>

                        <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">Kirim Ulang Link Verifikasi</button>
                        </form>
                        
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
