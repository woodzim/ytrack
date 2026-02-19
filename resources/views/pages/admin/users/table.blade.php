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
                        <th style="width: 18%;">Nama</th>
                        <th style="width: 18%;">Email</th>
                        <th style="width: 12%;">Role</th>
                        <th style="width: 14%;">Status</th>
                        <th style="width: 16%;">Tanggal Daftar</th>
                        <th style="width: 22%;" class="text-center">Aksi</th>
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
                            <td data-label="Status">
                                @if ($user->hasRole('perusahaan'))
                                    @if ($user->is_validated)
                                        <span class="badge bg-success">
                                            <i class="ri-check-line me-1"></i>Tervalidasi
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="ri-time-line me-1"></i>Menunggu Validasi
                                        </span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                            <td data-label="Tanggal Daftar">
                                <small class="text-muted">
                                    {{ $user->created_at?->format('d-m-Y') ?? '-' }}
                                </small>
                            </td>
                            <td data-label="Aksi" class="text-center">
                                @if ($user->hasRole('perusahaan'))
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light btn-wave dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-2-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if (!$user->is_validated)
                                                <li>
                                                    <button type="button" class="dropdown-item text-success"
                                                        data-bs-toggle="modal" data-bs-target="#validateUserModal"
                                                        data-user-id="{{ $user->id }}"
                                                        data-user-name="{{ $user->name }}"
                                                        data-is-validated="{{ $user->is_validated ? '1' : '0' }}">
                                                        <i class="ri-check-line me-2 align-middle"></i>Validasi Akun
                                                    </button>
                                                </li>
                                            @else
                                                <li>
                                                    <button type="button" class="dropdown-item text-warning"
                                                        data-bs-toggle="modal" data-bs-target="#validateUserModal"
                                                        data-user-id="{{ $user->id }}"
                                                        data-user-name="{{ $user->name }}"
                                                        data-is-validated="{{ $user->is_validated ? '1' : '0' }}">
                                                        <i class="ri-close-line me-2 align-middle"></i>Batalkan Validasi
                                                    </button>
                                                </li>
                                            @endif
                                            <li>
                                                <button type="button" class="dropdown-item text-primary"
                                                    data-bs-toggle="modal" data-bs-target="#resetPasswordModal"
                                                    data-user-id="{{ $user->id }}"
                                                    data-user-name="{{ $user->name }}">
                                                    <i class="ri-lock-password-line me-2 align-middle"></i>Reset Password
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
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
