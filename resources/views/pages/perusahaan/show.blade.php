@extends('layouts.master')

@section('styles')
    <style>
        .detail-header {
            background: rgb(152, 95, 253);
            border-radius: 8px;
            padding: 24px;
            color: white;
            margin-bottom: 24px;
        }

        .detail-header h1 {
            margin: 0 0 8px 0;
            font-size: 28px;
            font-weight: 600;
        }

        .detail-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 14px;
        }

        .detail-row {
            display: flex;
            padding: 16px 0;
            border-bottom: 1px solid #f0f0f0;
            align-items: flex-start;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: rgb(152, 95, 253);
            width: 150px;
            flex-shrink: 0;
        }

        .detail-value {
            color: #333;
            flex: 1;
        }

        .detail-value-meta {
            color: #999;
            font-size: 13px;
        }

        .card-detail {
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .btn-action-group {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .kendaraan-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .kendaraan-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            margin-bottom: 8px;
            background: #f9f9f9;
            border-radius: 6px;
            border-left: 4px solid rgb(152, 95, 253);
        }

        .kendaraan-item-name {
            font-weight: 500;
            color: #333;
        }

        .kendaraan-item-actions {
            display: flex;
            gap: 4px;
        }

        .kendaraan-item-actions .btn-sm {
            padding: 4px 8px;
            font-size: 11px;
        }

        /* Tablet & Desktop */
        @media (min-width: 992px) {
            .detail-header {
                padding: 24px;
            }

            .detail-header h1 {
                font-size: 28px;
            }

            .detail-row {
                padding: 16px 0;
            }

            .detail-label {
                width: 150px;
            }
        }

        /* Mobile Responsiveness */
        @media (max-width: 991px) {
            .detail-header {
                padding: 16px;
                margin-bottom: 16px;
            }

            .detail-header h1 {
                font-size: 22px;
                margin-bottom: 4px;
            }

            .detail-header p {
                font-size: 12px;
            }

            .detail-header .text-end {
                margin-top: 12px;
            }

            .detail-row {
                padding: 12px 0;
                flex-direction: column;
            }

            .detail-label {
                width: 100%;
                margin-bottom: 6px;
                color: rgb(152, 95, 253);
                font-size: 14px;
            }

            .detail-value {
                font-size: 14px;
            }

            .card-detail {
                margin-bottom: 12px;
            }

            .card-body {
                padding: 16px !important;
            }

            .btn-action-group {
                gap: 6px;
            }

            .btn-action-group .btn {
                flex: 1;
                font-size: 13px;
                padding: 8px 12px;
                white-space: nowrap;
            }

            .btn-action-group .btn i {
                margin-right: 4px;
            }

            h6.card-title {
                font-size: 14px;
            }

            .row>[class*='col-'] {
                margin-bottom: 12px;
            }
        }

        /* Extra Small Devices */
        @media (max-width: 576px) {
            .detail-header {
                padding: 12px;
                margin-bottom: 12px;
            }

            .detail-header h1 {
                font-size: 18px;
                margin-bottom: 2px;
            }

            .detail-header p {
                font-size: 11px;
            }

            .detail-header .badge {
                font-size: 10px;
                padding: 4px 8px !important;
            }

            .card-header {
                padding: 12px !important;
            }

            .card-body {
                padding: 12px !important;
            }

            .detail-row {
                padding: 10px 0;
            }

            .detail-label {
                font-size: 12px;
                margin-bottom: 4px;
            }

            .detail-value {
                font-size: 13px;
            }

            .btn-action-group {
                flex-direction: column;
                gap: 6px;
            }

            .btn-action-group .btn {
                width: 100%;
                padding: 10px 12px;
                font-size: 12px;
            }

            h5.card-title {
                font-size: 14px;
                margin-bottom: 8px !important;
            }

            h6.card-title {
                font-size: 12px;
                margin-bottom: 8px !important;
            }

            .row {
                margin-left: -4px;
                margin-right: -4px;
            }

            .row>[class*='col-'] {
                padding-left: 4px;
                padding-right: 4px;
                margin-bottom: 8px;
            }

            .text-center h3 {
                font-size: 20px !important;
            }

            .text-center p {
                font-size: 11px !important;
            }
        }
    </style>
@endsection

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
                        <a href="{{ route('perusahaan.edit', $perusahaan->id) }}" class="btn btn-warning btn-wave">
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
                                <h3 class="mb-1" style="color: rgb(152, 95, 253);">{{ $perusahaan->kendaraan->count() }}</h3>
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
                                            <button type="submit" class="btn btn-sm btn-danger btn-wave">
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
                    <button class="btn btn-sm btn-primary btn-wave w-100" data-bs-toggle="modal"
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
                                placeholder="Contoh: Toyota Avanza (BK-001)" required>
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
