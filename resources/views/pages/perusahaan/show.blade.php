@extends('layouts.master')

@section('content')
    <!-- Start::page-header -->
    <div class="detail-header">
        <div>
            <h1 class="mb-0">{{ $perusahaan->nama }}</h1>
        </div>
    </div>
    <!-- End::page-header -->

    <!-- Start::app-content -->
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card card-detail">
                <div class="card-header border-bottom bg-white">
                    <h5 class="card-title mb-0"><i class="ri-information-line me-2"></i>Informasi Lengkap</h5>
                </div>
                <div class="card-body">
                    <div class="detail-row">
                        <span class="detail-label">ID Perusahaan</span>
                        <span class="detail-value">
                            <strong>{{ $perusahaan->id }}</strong>
                        </span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Nama Perusahaan</span>
                        <span class="detail-value">
                            <strong>{{ $perusahaan->nama }}</strong>
                        </span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Alamat</span>
                        <span class="detail-value">
                            {{ $perusahaan->alamat }}
                        </span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Dibuat Pada</span>
                        <span class="detail-value">
                            {{ $perusahaan->created_at->translatedFormat('d M Y') }}
                            <div class="detail-value-meta">Pukul {{ $perusahaan->created_at->translatedFormat('H:i') }}
                            </div>
                        </span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Diperbarui Pada</span>
                        <span class="detail-value">
                            {{ $perusahaan->updated_at->translatedFormat('d M Y') }}
                            <div class="detail-value-meta">Pukul {{ $perusahaan->updated_at->translatedFormat('H:i') }}
                            </div>
                        </span>
                    </div>
                </div>
                <div style="padding: 16px; background: #f9f9f9; border-top: 1px solid #f0f0f0; border-radius: 0 0 8px 8px;">
                    <div class="btn-action-group">
                        <a href="{{ route('perusahaan.edit', $perusahaan->id) }}" class="btn btn-primary btn-wave">
                            <i class="ri-edit-line me-2"></i>Edit
                        </a>
                        <a href="{{ route('perusahaan.index') }}" class="btn btn-outline-secondary btn-wave">
                            <i class="ri-arrow-left-line me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <!-- Statistics Section -->
            <div class="card card-detail">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="text-center">
                                <h3 class="mb-1 text-primary">{{ $perusahaan->kendaraan->count() }}</h3>
                                <p class="text-muted small mb-0">Total Kendaraan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="card card-detail">
                <div class="card-header border-bottom bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0"><i class="ri-truck-line me-2"></i>Kendaraan</h5>
                    </div>
                </div>
                <div class="card-body">
                    @if ($perusahaan->kendaraan->count() > 0)
                        <ul class="kendaraan-list">
                            @foreach ($perusahaan->kendaraan as $kendaraan)
                                <li class="kendaraan-item">
                                    <span class="kendaraan-item-name">{{ $kendaraan->nama }}</span>
                                    <div class="kendaraan-item-actions">
                                        <button class="btn btn-sm btn-warning btn-wave" data-bs-toggle="modal"
                                            data-bs-target="#editKendaraanModal" data-id="{{ $kendaraan->id }}"
                                            data-nama="{{ $kendaraan->nama }}">
                                            <i class="ri-edit-line"></i>
                                        </button>
                                        <form action="{{ route('kendaraan.destroy', [$perusahaan->id, $kendaraan->id]) }}"
                                            method="POST" style="display: inline;"
                                            onsubmit="return confirm('Hapus kendaraan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-primary btn-wave">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted text-center mb-0">Belum ada kendaraan</p>
                    @endif
                </div>
                <div
                    style="padding: 12px 16px; background: #f9f9f9; border-top: 1px solid #f0f0f0; border-radius: 0 0 8px 8px;">
                    <button class="btn btn-sm btn-primary btn-wave w-100 py-3" data-bs-toggle="modal"
                        data-bs-target="#addKendaraanModal">
                        <i class="ri-add-line me-2"></i>Tambah Kendaraan
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->

    <!-- Add Kendaraan Modal -->
    <div class="modal fade" id="addKendaraanModal" tabindex="-1" role="dialog" aria-labelledby="addKendaraanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addKendaraanModalLabel">Tambah Kendaraan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('kendaraan.store', $perusahaan->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="namaKendaraan" class="form-label">Nama Kendaraan</label>
                            <input type="text" class="form-control" id="namaKendaraan" name="nama"
                                placeholder="Contoh: &quot;Toyota Avanza&quot; (nama) atau &quot;B 1611 BV&quot; (plat)" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Kendaraan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Kendaraan Modal -->
    <div class="modal fade" id="editKendaraanModal" tabindex="-1" role="dialog"
        aria-labelledby="editKendaraanModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editKendaraanModalLabel">Edit Kendaraan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editKendaraanForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="editNamaKendaraan" class="form-label">Nama Kendaraan</label>
                            <input type="text" class="form-control" id="editNamaKendaraan" name="nama"
                                placeholder="Contoh: Toyota Avanza (BK-001)" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Handle edit modal
        const editKendaraanModal = document.getElementById('editKendaraanModal');
        if (editKendaraanModal) {
            editKendaraanModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const nama = button.getAttribute('data-nama');

                document.getElementById('editNamaKendaraan').value = nama;
                const form = document.getElementById('editKendaraanForm');
                form.action = '{{ route('kendaraan.update', [$perusahaan->id, ':id']) }}'.replace(':id', id);
            });
        }
    </script>
@endsection

@section('scripts')
@endsection
