@extends('layouts.master')

@section('content')
    <!-- Start::page-header -->
    <div class="page-header-section">
        <h1>Edit Data Tracking</h1>
        <p>Ubah informasi lokasi GPS</p>
    </div>
    <!-- End::page-header -->

    <!-- Start::app-content -->
    <div class="row">
        <div class="col-lg-8 col-12">
            <div class="form-card">
                <div class="card-header">
                    <h5>Informasi Tracking</h5>
                </div>
                <div class="card-body">
                    <!-- Info Display -->
                    <div class="info-group">
                        <strong>Waktu Recording:</strong> 
                        <span class="text-muted">{{ $riwayat->created_at->translatedFormat('d F Y H:i:s') }}</span>
                    </div>

                    <form action="{{ route('riwayat-perjalanan.update', $riwayat->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Perusahaan Select -->
                        <div class="form-group">
                            <label for="id_perusahaan" class="form-label">Perusahaan</label>
                            <select id="id_perusahaan" name="id_perusahaan" class="form-control @error('id_perusahaan') is-invalid @enderror">
                                <option value="">-- Tidak Ada --</option>
                                @foreach($perusahaan as $item)
                                    <option value="{{ $item->id }}" {{ old('id_perusahaan', $riwayat->id_perusahaan) == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_perusahaan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kendaraan Select -->
                        <div class="form-group">
                            <label for="id_kendaraan" class="form-label">Kendaraan</label>
                            <select id="id_kendaraan" name="id_kendaraan" class="form-control @error('id_kendaraan') is-invalid @enderror">
                                <option value="">-- Tidak Ada --</option>
                                @foreach($kendaraan as $item)
                                    <option value="{{ $item->id }}" {{ old('id_kendaraan', $riwayat->id_kendaraan) == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_kendaraan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Latitude -->
                        <div class="form-group">
                            <label for="lat" class="form-label">Latitude</label>
                            <input type="text" id="lat" name="lat" class="form-control @error('lat') is-invalid @enderror" 
                                   value="{{ old('lat', $riwayat->lat) }}" required>
                            @error('lat')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Longitude -->
                        <div class="form-group">
                            <label for="long" class="form-label">Longitude</label>
                            <input type="text" id="long" name="long" class="form-control @error('long') is-invalid @enderror" 
                                   value="{{ old('long', $riwayat->long) }}" required>
                            @error('long')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Altitude -->
                        <div class="form-group">
                            <label for="alt" class="form-label">Ketinggian (meter)</label>
                            <input type="text" id="alt" name="alt" class="form-control @error('alt') is-invalid @enderror" 
                                   placeholder="Opsional" value="{{ old('alt', $riwayat->alt) }}">
                            @error('alt')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Satellites -->
                        <div class="form-group">
                            <label for="sat" class="form-label">Jumlah Satelit</label>
                            <input type="number" id="sat" name="sat" class="form-control @error('sat') is-invalid @enderror" 
                                   placeholder="Opsional" value="{{ old('sat', $riwayat->sat) }}">
                            @error('sat')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Speed -->
                        <div class="form-group">
                            <label for="speed" class="form-label">Kecepatan (km/h)</label>
                            <input type="number" id="speed" name="speed" class="form-control @error('speed') is-invalid @enderror" 
                                   placeholder="Opsional" value="{{ old('speed', $riwayat->speed) }}">
                            @error('speed')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="button-group">
                            <button type="submit" class="btn-save">
                                <i class="ri-save-line me-2"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('riwayat-perjalanan.show', $riwayat->id_alat) }}" class="btn-cancel">
                                <i class="ri-close-line me-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-12">
            <div class="form-card">
                <div class="card-header">
                    <h5>Informasi Rekam</h5>
                </div>
                <div class="card-body">
                    <div class="info-group">
                        <strong>ID Alat:</strong><br>
                        <span class="text-muted">{{ $riwayat->id_alat }}</span>
                    </div>

                    @if($riwayat->id_perusahaan)
                        <div class="info-group">
                            <strong>Perusahaan:</strong><br>
                            <span class="text-muted">{{ $riwayat->perusahaan->nama ?? 'N/A' }}</span>
                        </div>
                    @endif

                    @if($riwayat->id_kendaraan)
                        <div class="info-group">
                            <strong>Kendaraan:</strong><br>
                            <span class="text-muted">{{ $riwayat->kendaraan->nama ?? 'N/A' }}</span>
                        </div>
                    @endif

                    <div class="info-group">
                        <strong>Dibuat:</strong><br>
                        <span class="text-muted">{{ $riwayat->created_at->translatedFormat('d F Y H:i:s') }}</span>
                    </div>

                    <div class="info-group">
                        <strong>Diperbarui:</strong><br>
                        <span class="text-muted">{{ $riwayat->updated_at->translatedFormat('d F Y H:i:s') }}</span>
                    </div>

                    <div class="info-group" style="border-left-color: #fbbf24; background: #fffbeb;">
                        <strong style="color: #92400e;">⚠️ Catatan:</strong><br>
                        <span class="text-muted">Data ini tidak memiliki perjalanan. Pastikan koordinat sudah benar sebelum menyimpan.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@endsection

@section('scripts')

@endsection
