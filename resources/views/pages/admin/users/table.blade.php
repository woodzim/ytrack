<div class="py-3">
  <div class="d-flex justify-content-between align-items-center">
    <h5 class="card-title mb-0">Daftar Pengguna ({{ $users->total() }})</h5>
  </div>
</div>
<div class="card-body p-0">
  @if ($users->count() > 0)
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="bg-light">
          <tr>
            <th style="width: 18%;">Nama</th>
            <th style="width: 18%;">Email</th>
            <th style="width: 14%;">Status</th>
            <th style="width: 10%;">Berkas</th>
            <th style="width: 14%;">Tanggal Daftar</th>
            <th style="width: 20%;" class="text-center">Aksi</th>
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
              <td data-label="Status">
                @if ($user->hasRole('perusahaan'))
                  @php
                    $isRejected = $user->perusahaan && $user->perusahaan->berkas === 'rejected';
                  @endphp
                  @if ($user->is_validated)
                    <span class="badge bg-success">
                      <i class="ri-check-line me-1"></i>Tervalidasi
                    </span>
                  @elseif ($isRejected)
                    <span class="badge bg-danger">
                      <i class="ri-close-circle-line me-1"></i>Ditolak
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
              <td data-label="Berkas">
                @if ($user->hasRole('perusahaan') && $user->perusahaan)
                  @php
                    $docs = [
                        'akta' => 'Akta Pendirian',
                        'npwp_badan' => 'NPWP Badan Usaha',
                        'nib' => 'NIB (OSS)',
                        'domisili' => 'Ket. Domisili',
                        'ktp_pj' => 'KTP PJ',
                        'npwp_pj' => 'NPWP PJ',
                        'izin_usaha' => 'Izin Usaha',
                        'siup' => 'SIUP / TDP',
                    ];

                    $hasMultiFiles = false;
                    foreach ($docs as $key => $label) {
                        if (!empty($user->perusahaan->{'berkas_' . $key})) {
                            $hasMultiFiles = true;
                            break;
                        }
                    }

                    $hasSubmitted = $user->perusahaan && $user->perusahaan->berkas === 'submitted_multi_flow';
                  @endphp

                  @if ($hasMultiFiles)
                    <div class="dropdown">
                      <button class="btn btn-sm btn-outline-primary dropdown-toggle hide-arrow px-2" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="window"
                        title="Lihat Berkas Perusahaan">
                        <i class="ri-folder-open-line me-1"></i> Berkas
                      </button>
                      <ul class="dropdown-menu fs-13">

                        @foreach ($docs as $key => $label)
                          @if (!empty($user->perusahaan->{'berkas_' . $key}))
                            <li>
                              <button type="button" class="dropdown-item d-flex align-items-center"
                                data-bs-toggle="modal" data-bs-target="#pdfViewerModal"
                                data-pdf-url="{{ Storage::disk('public')->url($user->perusahaan->{'berkas_' . $key}) }}">
                                <i class="ri-file-pdf-line text-danger me-2"></i> {{ $label }}
                              </button>
                            </li>
                          @endif
                        @endforeach
                      </ul>
                    </div>
                  @else
                    <span class="badge bg-danger-transparent text-danger">Kosong</span>
                  @endif
                @else
                  <span class="text-muted small">-</span>
                @endif
              </td>
              <td data-label="Tanggal Daftar">
                <small class="text-muted">
                  {{ $user->created_at?->format('d-m-Y') ?? '-' }}
                </small>
              </td>
              <td data-label="Aksi" class="text-center">
                @if ($user->hasRole('perusahaan'))
                  @php
                    $isCompanySubmitted = $user->perusahaan && $user->perusahaan->berkas === 'submitted_multi_flow';
                  @endphp
                  <div class="dropdown">
                    <button class="btn btn-sm btn-light btn-wave dropdown-toggle hide-arrow" type="button"
                      data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="window">
                      <i class="ri-more-2-fill"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                      @if (!$user->is_validated)
                        @if ($isCompanySubmitted)
                          <li>
                            <button type="button" class="dropdown-item text-success" data-bs-toggle="modal"
                              data-bs-target="#validateUserModal" data-user-id="{{ $user->id }}"
                              data-user-name="{{ $user->name }}"
                              data-is-validated="{{ $user->is_validated ? '1' : '0' }}">
                              <i class="ri-check-line me-2 align-middle"></i>Validasi Akun
                            </button>
                          </li>
                          <li>
                            <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal"
                              data-bs-target="#rejectUserModal" data-user-id="{{ $user->id }}"
                              data-user-name="{{ $user->name }}">
                              <i class="ri-close-circle-line me-2 align-middle"></i>Tolak Pendaftaran
                            </button>
                          </li>
                        @else
                          @php
                            $isRejected = $user->perusahaan && $user->perusahaan->berkas === 'rejected';
                            $tooltipText = $isRejected
                                ? 'Pendaftaran telah ditolak, menunggu perusahaan mengirim ulang berkas'
                                : 'Perusahaan belum mengirim berkas';
                          @endphp
                          <li>
                            <button type="button" class="dropdown-item text-muted disabled" disabled="disabled"
                              style="cursor: not-allowed;" title="{{ $tooltipText }}">
                              <i class="ri-check-line me-2 align-middle"></i>Validasi Akun
                            </button>
                          </li>
                          <li>
                            <button type="button" class="dropdown-item text-muted disabled" disabled="disabled"
                              style="cursor: not-allowed;" title="{{ $tooltipText }}">
                              <i class="ri-close-circle-line me-2 align-middle"></i>Tolak Pendaftaran
                            </button>
                          </li>
                        @endif
                      @else
                        <li>
                          <button type="button" class="dropdown-item text-warning" data-bs-toggle="modal"
                            data-bs-target="#validateUserModal" data-user-id="{{ $user->id }}"
                            data-user-name="{{ $user->name }}"
                            data-is-validated="{{ $user->is_validated ? '1' : '0' }}">
                            <i class="ri-close-line me-2 align-middle"></i>Batalkan Validasi
                          </button>
                        </li>
                      @endif
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
  <div class="card-footer border-top">
    <nav aria-label="Page navigation">
      {{ $users->links() }}
    </nav>
  </div>
@endif
