@extends('layouts.master')

@section('content')
  <div class="page-header-section d-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="page-title fw-medium fs-20 mb-0">Tambah Kendaraan Baru</h1>
      <p class="">Daftarkan aset kendaraan ke dalam sistem</p>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card custom-card">
        <div class="pt-4">
          <form action="{{ route('perusahaan.kendaraan.store') }}" method="POST" enctype="multipart/form-data"
            id="kendaraan-form">
            @csrf

            <div class="row g-4">
              <div class="col-12">
                <div class="">
                  <h6 class="fw-semibold mb-3"><i class="ri-information-line me-2 text-primary"></i>Informasi Kendaraan
                  </h6>
                  <div class="row">
                    <div class="col-md-4 d-flex flex-column">
                      <label for="id_jenis_kendaraan" class="form-label fw-medium">Jenis Kendaraan <span
                          class="text-danger">*</span></label>
                      <select class="form-select @error('id_jenis_kendaraan') is-invalid @enderror"
                        id="id_jenis_kendaraan" name="id_jenis_kendaraan" required>
                        <option value="">-- Pilih Jenis --</option>
                        @foreach ($jenisKendaraan as $jk)
                          <option value="{{ $jk->id }}"
                            {{ old('id_jenis_kendaraan') == $jk->id ? 'selected' : '' }}>
                            {{ $jk->nama }}</option>
                        @endforeach
                      </select>
                      @error('id_jenis_kendaraan')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>

                    <div class="col-md-4">
                      <label for="nama" class="form-label fw-medium">Nama Kendaraan <span
                          class="text-danger">*</span></label>
                      <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                        name="nama" placeholder="Contoh: Truck Hino 500" value="{{ old('nama') }}" required>
                      @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>

                    <div class="col-md-4">
                      <label for="plat" class="form-label fw-medium">Nomor Polisi <span
                          class="text-danger">*</span></label>
                      <input type="text" class="form-control @error('plat') is-invalid @enderror" id="plat"
                        name="plat" placeholder="B 1234 ABC" value="{{ old('plat') }}" required
                        style="text-transform: uppercase;">
                      @error('plat')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>

              {{-- STNK Upload --}}
              <div class="col-12 border-top pt-4">
                <h6 class="fw-semibold mb-3"><i class="ri-file-text-line me-2 text-primary"></i>STNK Kendaraan
                  <span class="text-danger">*</span>
                </h6>
                <div class="row">
                  <div class="col-md-6 col-lg-4">
                    <div class="upload-card" id="stnk-upload-card">
                      <input type="file" name="stnk" id="stnk-input" accept=".pdf,.jpeg,.jpg,.png,.webp"
                        class="d-none" onchange="handleFilePreview(this, 'stnk')">
                      <div class="upload-preview" id="stnk-preview" onclick="document.getElementById('stnk-input').click()">
                        <div class="upload-placeholder" id="stnk-placeholder">
                          <i class="ri-file-upload-line fs-30 text-primary op-5"></i>
                          <p class="mb-0 mt-2 fs-13 fw-medium">Klik untuk unggah STNK</p>
                          <p class="mb-0 text-muted fs-11">PDF, JPEG, PNG, WEBP (Maks: 4MB)</p>
                        </div>
                        <div class="upload-thumb d-none" id="stnk-thumb">
                          <img src="" alt="Preview STNK" class="img-fluid">
                          <div class="upload-thumb-overlay">
                            <i class="ri-refresh-line fs-20"></i>
                            <span class="fs-11">Ganti File</span>
                          </div>
                        </div>
                        <div class="upload-file-info d-none" id="stnk-file-info">
                          <i class="ri-file-pdf-2-line fs-30 text-danger"></i>
                          <p class="mb-0 mt-2 fs-12 fw-medium text-truncate" id="stnk-file-name"></p>
                          <div class="upload-thumb-overlay">
                            <i class="ri-refresh-line fs-20"></i>
                            <span class="fs-11">Ganti File</span>
                          </div>
                        </div>
                      </div>
                      @error('stnk')
                        <div class="text-danger small mt-2"><i class="ri-error-warning-line me-1"></i>{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>

              {{-- Foto Kendaraan Per-Angle --}}
              <div class="col-12 border-top pt-4">
                <h6 class="fw-semibold mb-3"><i class="ri-camera-line me-2 text-primary"></i>Foto Kendaraan
                  <span class="badge bg-light text-muted ms-2">Opsional</span>
                </h6>
                <div class="row g-3">
                  @php
                    $fotoFields = [
                        'foto_depan' => ['label' => 'Tampak Depan', 'icon' => 'ri-arrow-up-line'],
                        'foto_samping_kanan' => ['label' => 'Tampak Samping Kanan', 'icon' => 'ri-arrow-right-line'],
                        'foto_samping_kiri' => ['label' => 'Tampak Samping Kiri', 'icon' => 'ri-arrow-left-line'],
                        'foto_belakang' => ['label' => 'Tampak Belakang', 'icon' => 'ri-arrow-down-line'],
                    ];
                  @endphp

                  @foreach ($fotoFields as $fieldName => $fieldInfo)
                    <div class="col-md-6 col-lg-3">
                      <div class="upload-card">
                        <label class="form-label fw-medium fs-13 mb-2">
                          <i class="{{ $fieldInfo['icon'] }} me-1 text-primary"></i>{{ $fieldInfo['label'] }}
                        </label>
                        <input type="file" name="{{ $fieldName }}" id="{{ $fieldName }}-input"
                          accept="image/jpeg,image/png,image/jpg,image/webp" class="d-none"
                          onchange="handleImagePreview(this, '{{ $fieldName }}')">
                        <div class="upload-preview upload-preview-photo" id="{{ $fieldName }}-preview"
                          onclick="document.getElementById('{{ $fieldName }}-input').click()">
                          <div class="upload-placeholder" id="{{ $fieldName }}-placeholder">
                            <i class="ri-image-add-line fs-28 text-primary op-5"></i>
                            <p class="mb-0 mt-1 fs-12 fw-medium">{{ $fieldInfo['label'] }}</p>
                            <p class="mb-0 text-muted fs-11">Maks: 2MB</p>
                          </div>
                          <div class="upload-thumb d-none" id="{{ $fieldName }}-thumb">
                            <img src="" alt="Preview {{ $fieldInfo['label'] }}" class="img-fluid">
                            <div class="upload-thumb-overlay">
                              <i class="ri-refresh-line fs-20"></i>
                              <span class="fs-11">Ganti</span>
                            </div>
                          </div>
                        </div>
                        @error($fieldName)
                          <div class="text-danger small mt-2"><i class="ri-error-warning-line me-1"></i>{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>

            <div class="d-flex gap-2 pt-4 border-top mt-4">
              <button type="submit" class="btn btn-primary px-4 py-2" id="submit-all">
                <i class="ri-save-line me-2"></i>Simpan Kendaraan
              </button>
              <a href="{{ route('perusahaan.kendaraan.index') }}" class="btn btn-light px-4">
                Kembali
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <style>
    .upload-card {
      position: relative;
    }

    .upload-preview {
      border: 2px dashed #e2e8ee;
      border-radius: 0.75rem;
      background-color: #f9fafb;
      min-height: 180px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.2s ease;
      overflow: hidden;
      position: relative;
    }

    .upload-preview:hover {
      border-color: var(--primary-color);
      background-color: rgba(var(--primary-rgb), 0.03);
    }

    .upload-preview-photo {
      min-height: 160px;
    }

    .upload-placeholder {
      text-align: center;
      padding: 1.5rem;
    }

    .upload-thumb {
      width: 100%;
      height: 100%;
      position: relative;
    }

    .upload-thumb img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      display: block;
    }

    .upload-preview-photo .upload-thumb img {
      height: 160px;
    }

    .upload-thumb-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      color: #fff;
      opacity: 0;
      transition: opacity 0.2s ease;
    }

    .upload-preview:hover .upload-thumb-overlay {
      opacity: 1;
    }

    .upload-file-info {
      text-align: center;
      padding: 1.5rem;
      width: 100%;
      position: relative;
    }

    .upload-file-info .upload-thumb-overlay {
      border-radius: 0.75rem;
    }

    /* Normalize Heights */
    .choices__inner,
    .form-control {
      height: 42px !important;
      min-height: 42px !important;
      background-color: #fff !important;
      border: 1px solid #e2e8ee !important;
      border-radius: 0.375rem !important;
    }

    .choices__inner {
      padding: 0 0.75rem !important;
      display: flex !important;
      align-items: center !important;
      transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .choices.is-focused .choices__inner {
      border-color: var(--primary-color) !important;
      box-shadow: 0 0 0 0.25rem rgba(var(--primary-rgb), 0.25);
    }

    .choices__list--single {
      padding: 0 !important;
      width: 100%;
    }

    .choices__list--single .choices__item {
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      margin-bottom: 0 !important;
    }

    .choices[data-type*="select-one"]::after {
      border-color: #8c90a7 transparent transparent transparent !important;
      right: 15px !important;
      margin-top: -2.5px !important;
    }

    .choices[data-type*="select-one"].is-open::after {
      border-color: transparent transparent #8c90a7 transparent !important;
      margin-top: -7.5px !important;
    }
  </style>
@endsection

@section('scripts')
  <script>
    /**
     * Handle preview for image files (foto kendaraan).
     */
    function handleImagePreview(input, fieldName) {
      const file = input.files[0];
      if (!file) return;

      const placeholder = document.getElementById(fieldName + '-placeholder');
      const thumb = document.getElementById(fieldName + '-thumb');

      if (file.size > 2 * 1024 * 1024) {
        if (window.showAlertModal) {
          window.showAlertModal('Ukuran gambar maksimal 2MB.', 'warning', 'Peringatan');
        } else {
          alert('Ukuran gambar maksimal 2MB.');
        }
        input.value = '';
        return;
      }

      const reader = new FileReader();
      reader.onload = function(e) {
        thumb.querySelector('img').src = e.target.result;
        placeholder.classList.add('d-none');
        thumb.classList.remove('d-none');
      };
      reader.readAsDataURL(file);
    }

    /**
     * Handle preview for STNK files (pdf or image).
     */
    function handleFilePreview(input, fieldName) {
      const file = input.files[0];
      if (!file) return;

      const placeholder = document.getElementById(fieldName + '-placeholder');
      const thumb = document.getElementById(fieldName + '-thumb');
      const fileInfo = document.getElementById(fieldName + '-file-info');
      const fileName = document.getElementById(fieldName + '-file-name');

      if (file.size > 4 * 1024 * 1024) {
        if (window.showAlertModal) {
          window.showAlertModal('Ukuran file maksimal 4MB.', 'warning', 'Peringatan');
        } else {
          alert('Ukuran file maksimal 4MB.');
        }
        input.value = '';
        return;
      }

      placeholder.classList.add('d-none');

      if (file.type.startsWith('image/')) {
        // Image preview
        fileInfo.classList.add('d-none');
        thumb.classList.remove('d-none');
        const reader = new FileReader();
        reader.onload = function(e) {
          thumb.querySelector('img').src = e.target.result;
        };
        reader.readAsDataURL(file);
      } else {
        // PDF file info
        thumb.classList.add('d-none');
        fileInfo.classList.remove('d-none');
        fileName.textContent = file.name;
      }
    }
  </script>
@endsection
