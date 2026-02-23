@extends('layouts.master')

@section('content')
  <div class="page-header-section d-flex align-items-center justify-content-between mb-4">
    <div>
      <h1>Log Aktivitas Sistem</h1>
      <p class="">Memantau seluruh aktivitas User, Alat, Kendaraan, dan Sesi.</p>
    </div>
  </div>

  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        {{-- 
             align-middle: Agar konten vertikal di tengah 
             table-striped: Opsional, agar baris selang-seling warnanya 
        --}}
        <table class="table table-hover align-middle">
          <thead class="bg-light">
            <tr>
              <th style="width: 15%;">Waktu</th>
              <th style="width: 20%;">Pelaku (User)</th>
              <th style="width: 10%;">Aksi</th>
              <th style="width: 15%;">Target</th>
              {{-- Beri min-width agar kolom ini tidak terlalu gepeng --}}
              <th style="width: 30%; min-width: 300px;">Deskripsi / Detail</th>
              <th style="width: 10%;">IP Address</th>
            </tr>
          </thead>
          <tbody>
            @forelse($logs as $log)
              <tr>
                {{-- WAKTU --}}
                <td style="font-size: 13px; white-space: nowrap;">
                  {{ $log->created_at->format('d M Y H:i:s') }}
                </td>

                {{-- PELAKU --}}
                <td>
                  @if ($log->user)
                    <div class="d-flex align-items-center gap-2">
                      <div
                        class="avatar avatar-xs bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                        style="width:24px; height:24px; font-size:10px;">
                        {{ substr($log->user->name, 0, 1) }}
                      </div>
                      <span class="fw-semibold">{{ $log->user->name }}</span>
                    </div>
                    <small class="text-muted" style="font-size: 11px;">{{ $log->user->email }}</small>
                  @else
                    <span class="text-danger fst-italic">User Terhapus / Sistem</span>
                  @endif
                </td>

                {{-- AKSI --}}
                <td>
                  @php
                    $badgeColor = 'secondary';
                    if (in_array($log->action, ['CREATE', 'REGISTER'])) {
                        $badgeColor = 'success';
                    }
                    if ($log->action == 'UPDATE') {
                        $badgeColor = 'warning';
                    }
                    if ($log->action == 'DELETE') {
                        $badgeColor = 'danger';
                    }
                    if ($log->action == 'CHANGE_PASSWORD') {
                        $badgeColor = 'dark';
                    }
                  @endphp
                  <span class="badge bg-{{ $badgeColor }} bg-opacity-10 text-{{ $badgeColor }} px-2 py-1">
                    {{ $log->action }}
                  </span>
                </td>

                {{-- TARGET --}}
                <td>
                  @if ($log->subject_id)
                    <span class="badge bg-light text-dark border">
                      {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                    </span>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>

                {{-- DESKRIPSI (TEXT WRAPPING) --}}
                {{-- class="text-wrap" memaksa teks turun ke bawah --}}
                <td class="text-wrap" style="word-break: break-word;">
                  <small class="text-muted" style="line-height: 1.5;">
                    {{ $log->description }}
                  </small>
                </td>

                {{-- IP ADDRESS --}}
                <td class="text-muted" style="font-size: 12px;">
                  {{ $log->ip_address ?? '-' }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center py-5">
                  <div class="d-flex flex-column align-items-center">
                    <i class="ri-file-list-3-line fs-1 text-muted opacity-25"></i>
                    <p class="text-muted mt-2">Belum ada aktivitas tercatat.</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="mt-4">
        {{ $logs->links() }}
      </div>
    </div>
  </div>
@endsection
