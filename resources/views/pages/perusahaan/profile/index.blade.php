@extends('layouts.master')

@section('content')
  <div class="page-header-section d-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="page-title fw-medium fs-20 mb-0">Profil Perusahaan</h1>
      <p class="mb-0">Kelola informasi akun dan data perusahaan Anda secara terpusat</p>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-8">
      <div class="card custom-card overflow-hidden shadow-sm">
        <div class="justify-content-between">
          <div class="card-title fs-5">
            <i class="ri-edit-box-line fw-medium me-1 mb-1 align-middle text-primary"></i>
            <span class="fw-medium">Edit Profil</span>
          </div>
        </div>
        <div class="pt-2">
          @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
              <div class="d-flex align-items-center">
                <i class="ri-error-warning-line fs-20 me-2"></i>
                <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
              <div class="d-flex align-items-center">
                <i class="ri-checkbox-circle-line fs-20 me-2"></i>
                <span>{{ session('success') }}</span>
              </div>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          <form action="{{ route('perusahaan.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-4">
              <div class="col-md-6">
                <label for="name" class="form-label fw-semibold">
                  <i class="ri-user-line me-1 text-muted"></i>Nama Pengguna
                </label>
                <div class="input-group">
                  <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                    name="name" value="{{ old('name', auth()->user()->name) }}" placeholder="Masukkan nama pengguna"
                    required>
                  @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="col-md-6">
                <label for="email" class="form-label fw-semibold">
                  <i class="ri-mail-line me-1 text-muted"></i>Email Akun
                </label>
                <div class="input-group">
                  <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                    name="email" value="{{ old('email', auth()->user()->email) }}" placeholder="email@contoh.com"
                    required>
                  @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="col-12 mt-4">
                <label for="nama" class="form-label fw-semibold">
                  <i class="ri-building-line me-1 text-muted"></i>Nama Perusahaan
                </label>
                <div class="input-group">
                  <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                    name="nama" value="{{ old('nama', $perusahaan->nama) }}"
                    placeholder="Masukkan nama legal perusahaan" required>
                  @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="col-12 mt-4">
                <label for="alamat" class="form-label fw-semibold">
                  <i class="ri-map-pin-line me-1 text-muted"></i>Alamat Perusahaan
                </label>
                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="4"
                  placeholder="Masukkan alamat lengkap kantor pusat" required>{{ old('alamat', $perusahaan->alamat) }}</textarea>
                @error('alamat')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-12 mt-4 border-top pt-4">
                <div class="d-flex align-items-center gap-3">
                  <button type="submit" class="btn btn-primary px-4 py-2">
                    <i class="ri-save-line me-2 align-middle"></i>Simpan Perubahan
                  </button>
                  <span class="text-muted small">
                    <i class="ri-information-line me-1 align-middle"></i>Perubahan akan langsung diterapkan pada seluruh
                    sistem
                  </span>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-xl-4">
      <div class="card custom-card shadow-sm border-0 overflow-hidden">
        <div class="card-body p-0">
          <div class="p-4 text-center bg-primary text-fixed-white position-relative overflow-hidden"
            style="min-height: 160px;">
            <div class="position-absolute top-0 start-0 w-100 h-100 op-1"
              style="background-image: url('data:image/svg+xml,%3Csvg width=\"100\" height=\"100\" viewBox=\"0 0 100 100\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 86c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm66 3c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm-46-15c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm45-68c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm-28 45c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm-23-39c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zM9 26c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm89 70c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zM5 8c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm81 5c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm-2-1c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm-57 59c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm0-24c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm0 24c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z\" fill=\"%23ffffff\" fill-opacity=\"1\" fill-rule=\"evenodd\"/%3E%3C/svg%3E'); background-repeat: repeat;">
            </div>
            <div class="position-relative z-1 mt-3">
              <div class="avatar avatar-xxl avatar-rounded border border-4 border-white-transparent shadow-lg mb-3">
                <img src="{{ auth()->user()->avatar_url }}" alt="img">
                <span class="avatar-badge bg-success rounded-circle"
                  style="width: 14px; height: 14px; bottom: 8px; right: 8px; border: 3px solid #fff;"></span>
              </div>
              <h5 class="fw-semibold text-fixed-white mb-1">{{ auth()->user()->name }}</h5>
              <p class="text-fixed-white op-8 small mb-0">{{ ucfirst(auth()->user()->roles->first()?->name) }}</p>
            </div>
          </div>

          <div class="p-4">
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-light">
              <div class="avatar avatar-sm bg-primary-transparent text-primary rounded-circle">
                <i class="ri-building-line"></i>
              </div>
              <div class="ms-3">
                <span class="d-block text-muted fs-11 lh-1 mb-1">Perusahaan</span>
                <span class="fw-semibold">{{ $perusahaan->nama }}</span>
              </div>
            </div>

            <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-light">
              <div class="avatar avatar-sm bg-info-transparent text-info rounded-circle">
                <i class="ri-calendar-line"></i>
              </div>
              <div class="ms-3">
                <span class="d-block text-muted fs-11 lh-1 mb-1">Terdaftar Sejak</span>
                <span class="fw-semibold text-muted small">{{ auth()->user()->created_at->format('d M Y') }}</span>
              </div>
            </div>

            <div class="mb-4">
              <span class="d-block text-muted fs-11 lh-1 mb-2 text-uppercase fw-bold ls-1">Status Validasi</span>
              @if (auth()->user()->is_validated)
                <div
                  class="alert alert-success d-flex align-items-center p-2 mb-0 border-0 bg-success-transparent shadow-none">
                  <i class="ri-checkbox-circle-line me-2 fs-18"></i>
                  <span class="fw-medium">Akun Tervalidasi</span>
                </div>
              @else
                <div
                  class="alert alert-warning d-flex align-items-center p-2 mb-0 border-0 bg-warning-transparent shadow-none">
                  <i class="ri-time-line me-2 fs-18"></i>
                  <span class="fw-medium">Menunggu Validasi</span>
                </div>
              @endif
            </div>

            <div class="p-3 bg-light rounded-2 border">
              <div class="d-flex align-items-start gap-2">
                <i class="ri-shield-user-line text-muted mt-1"></i>
                <div>
                  <span class="d-block text-muted fs-11 lh-1 mb-1">Email Terdaftar</span>
                  <span class="fw-medium text-break">{{ auth()->user()->email }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
