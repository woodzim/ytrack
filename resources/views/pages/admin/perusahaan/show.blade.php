@extends('layouts.master')

@section('content')
  <div class="detail-header">
    <div>
      <h1 class="mb-0">{{ $perusahaan->nama }}</h1>
    </div>
  </div>
  <div class="row">
    {{-- Kolom Kiri: Informasi Perusahaan --}}
    <div class="col-12 col-lg-8">
      <div class="card card-detail">
        <div class="card-header py-3">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="ri-information-line me-2"></i>Informasi Lengkap</h5>
          </div>
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
              {{-- Bagian Tanggal --}}
              <span class="local-date-only">{{ $perusahaan->created_at->toIso8601String() }}</span>

              {{-- Bagian Jam --}}
              <div class="detail-value-meta">
                Pukul <span class="local-time-only">{{ $perusahaan->created_at->toIso8601String() }}</span>
              </div>
            </span>
          </div>

          <div class="detail-row">
            <span class="detail-label">Diperbarui Pada</span>
            <span class="detail-value">
              {{-- Bagian Tanggal --}}
              <span class="local-date-only">{{ $perusahaan->updated_at->toIso8601String() }}</span>

              {{-- Bagian Jam --}}
              <div class="detail-value-meta">
                Pukul <span class="local-time-only">{{ $perusahaan->updated_at->toIso8601String() }}</span>
              </div>
            </span>
          </div>
        </div>

        {{-- Tombol Aksi Perusahaan --}}
        <div style="padding: 16px; background: #f9f9f9; border-top: 1px solid #f0f0f0; border-radius: 0 0 8px 8px;">
          <div class="btn-action-group">
            <a href="{{ route('admin.perusahaan.edit', $perusahaan->id) }}" class="btn btn-primary btn-wave">
              <i class="ri-edit-line me-2"></i>Edit Perusahaan
            </a>
            <a href="{{ route('admin.perusahaan.index') }}" class="btn btn-outline-secondary btn-wave">
              <i class="ri-arrow-left-line me-2"></i>Kembali
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- Kolom Kanan: Statistik & Daftar Aset (Dihapus sesuai permintaan) --}}
    <div class="col-12 col-lg-4">
      <div class="card card-detail">
        <div class="card-header py-3">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="ri-history-line me-2"></i>Aktivitas Terakhir</h5>
          </div>
        </div>
        <div class="card-body">
            <p class="text-muted small mb-0">Fitur aktivitas akan segera hadir.</p>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', () => {

      // 1. Fungsi Format Tanggal (Contoh: 25 Desember 2023)
      function convertToLocalDate(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return new Intl.DateTimeFormat('id-ID', {
          day: '2-digit',
          month: 'long',
          year: 'numeric'
        }).format(date);
      }

      // 2. Fungsi Format Jam (Contoh: 14:30)
      function convertToLocalTime(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return new Intl.DateTimeFormat('id-ID', {
          hour: '2-digit',
          minute: '2-digit',
          hour12: false
        }).format(date).replace(/\./g, ':');
      }

      // Eksekusi untuk Tanggal
      document.querySelectorAll('.local-date-only').forEach(el => {
        el.textContent = convertToLocalDate(el.textContent);
      });

      // Eksekusi untuk Jam
      document.querySelectorAll('.local-time-only').forEach(el => {
        el.textContent = convertToLocalTime(el.textContent);
      });

    });
  </script>
@endsection
