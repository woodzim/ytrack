@extends('layouts.master')

@section('content')
    <!-- Start::page-header -->
    <div class="d-flex align-items-center justify-content-between mb-3 page-header-breadcrumb flex-wrap gap-2">
        <div>
            {{-- <h1 class="page-title fw-medium fs-20 mb-0">Dashboard</h1> --}}
            <h1 class="page-title fw-medium fs-20 mb-0">Selamat datang, {{  auth()->user()->name }}.</h1>
        </div>
        {{-- <div class="d-flex align-items-center gap-2 flex-wrap">
            <div class="form-group">
                <input type="text" class="form-control breadcrumb-input" id="daterange" placeholder="Search By Date Range">
            </div>
            <div class="btn-list">
                <button class="btn btn-icon btn-primary btn-wave">
                    <i class="ri-refresh-line"></i>
                </button>
                <button class="btn btn-icon btn-primary btn-wave me-0">
                    <i class="ri-filter-3-line"></i>
                </button>
            </div>
        </div> --}}
    </div>
    <!-- End::app-content -->
@endsection

@section('scripts')
@endsection
