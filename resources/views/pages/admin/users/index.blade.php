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
                                        <th style="width: 25%;">Nama</th>
                                        <th style="width: 25%;">Email</th>
                                        <th style="width: 20%;">Role</th>
                                        <th style="width: 30%;">Tanggal Daftar</th>
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
@endsection

@section('scripts')
@endsection
