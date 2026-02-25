@extends('layouts.master')

@section('content')
  <div class="page-header-section d-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="page-title fw-medium fs-20 mb-0">Profil Perusahaan</h1>
      <p class=" mb-0">Kelola informasi akun dan data perusahaan Anda</p>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-8">
      <div class="card custom-card">
        <div class="card-header">
          <div class="card-title mb-0">Edit Profil</div>
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

          <form action="{{ route('perusahaan.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="name" class="form-label">Nama Pengguna</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                  name="name" value="{{ old('name', auth()->user()->name) }}" required>
                @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                  name="email" value="{{ old('email', auth()->user()->email) }}" required>
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="mb-3">
              <label for="nama" class="form-label">Nama Perusahaan</label>
              <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
                value="{{ old('nama', $perusahaan->nama) }}" required>
              @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="alamat" class="form-label">Alamat Perusahaan</label>
              <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="4"
                required>{{ old('alamat', $perusahaan->alamat) }}</textarea>
              @error('alamat')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary btn-wave">
                <i class="ri-save-line me-2"></i>Simpan Perubahan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card custom-card">
        <div class="card-header">
          <div class="card-title mb-0">Informasi Akun</div>
        </div>
        <div class="card-body">
          <div class="d-flex align-items-center mb-4">
            <div class="avatar avatar-xl bg-primary-transparent text-primary">
              <i class="ri-building-4-line fs-24"></i>
            </div>
            <div class="ms-3">
              <h6 class="fw-bold mb-0">{{ $perusahaan->nama }}</h6>
              <p class="text-muted mb-0 small">Member sejak {{ auth()->user()->created_at->format('M Y') }}</p>
            </div>
          </div>

          <div class="mb-3">
            <label class="text-muted small d-block mb-1">Status Validasi</label>
            @if (auth()->user()->is_validated)
              <span class="badge bg-success-transparent text-success">
                <i class="ri-checkbox-circle-line me-1"></i>Tervalidasi
              </span>
            @else
              <span class="badge bg-warning-transparent text-warning">
                <i class="ri-time-line me-1"></i>Menunggu Validasi
              </span>
            @endif
          </div>

          <div class="mb-0">
            <label class="text-muted small d-block mb-1">Email Terdaftar</label>
            <span class="fw-medium">{{ auth()->user()->email }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
