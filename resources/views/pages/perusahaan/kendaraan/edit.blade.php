@extends('layouts.master')

@section('content')
  <div class="page-header-section d-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="page-title fw-medium fs-20 mb-0">Edit Kendaraan</h1>
      <p class="">Perbarui informasi dan kelola foto kendaraan operasional</p>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card custom-card">
        <div class="card-body p-0 mt-4">
          <form action="{{ route('perusahaan.kendaraan.update', $kendaraan->id) }}" method="POST"
            enctype="multipart/form-data" id="kendaraan-form">
            @csrf
            @method('PUT')

            <div class="row g-4">
              <div class="col-12">
                <div class="">
                  <h6 class="fw-semibold mb-3"><i class="ri-information-line me-2 text-primary"></i>Informasi Kendaraan
                  </h6>
                  <div class="row">
                    <div class="col-md-4">
                      <label for="id_jenis_kendaraan" class="form-label fw-medium">Jenis Kendaraan <span
                          class="text-danger">*</span></label>
                      <select class="form-select @error('id_jenis_kendaraan') is-invalid @enderror"
                        id="id_jenis_kendaraan" name="id_jenis_kendaraan" required>
                        <option value="">-- Pilih Jenis --</option>
                        @foreach ($jenisKendaraan as $jk)
                          <option value="{{ $jk->id }}"
                            {{ old('id_jenis_kendaraan', $kendaraan->id_jenis_kendaraan) == $jk->id ? 'selected' : '' }}>
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
                        name="nama" placeholder="Nama Kendaraan" value="{{ old('nama', $kendaraan->nama) }}" required>
                      @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>

                    <div class="col-md-4">
                      <label for="plat" class="form-label fw-medium">Nomor Plat <span
                          class="text-danger">*</span></label>
                      <input type="text" class="form-control @error('plat') is-invalid @enderror" id="plat"
                        name="plat" placeholder="Nomor Plat" value="{{ old('plat', $kendaraan->plat) }}" required
                        style="text-transform: uppercase;">
                      @error('plat')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-12 border-top pt-4">
                <div class="mb-4">
                  <h6 class="fw-semibold mb-3"><i class="ri-gallery-line me-2 text-primary"></i>Foto Saat Ini</h6>
                  <div class="row g-3">
                    @forelse ($kendaraan->images as $img)
                      <div class="col-xl-3 col-sm-6">
                        <div class="card shadow-none border mb-0 position-relative overflow-hidden">
                          <img src="{{ asset('storage/' . $img->image_path) }}" class="card-img-top"
                            style="height: 140px; object-fit: cover;">
                          <div class="p-2 d-flex justify-content-between align-items-center">
                            <p class="small text-truncate mb-0 fw-medium" title="{{ $img->caption }}">
                              @if ($img->caption)
                                {{ $img->caption }}
                              @else
                                <span class="text-muted fst-italic">Tanpa Keterangan</span>
                              @endif
                            </p>
                            <div class="d-flex gap-1">
                              <a href="{{ asset('storage/' . $img->image_path) }}"
                                class="glightbox btn btn-sm btn-icon btn-info-light mb-0" data-gallery="kendaraan-gallery"
                                data-title="{{ $img->caption ?: 'Tanpa Keterangan' }}" title="Lihat Foto Utuh"
                                aria-label="Lihat pratinjau foto">
                                <i class="ri-eye-line"></i>
                              </a>

                              <label for="update-img-{{ $img->id }}"
                                class="btn btn-sm btn-icon btn-primary-light mb-0" title="Ubah Foto"
                                style="cursor:pointer;">
                                <i class="ri-edit-line"></i>
                              </label>
                              <input type="file" id="update-img-{{ $img->id }}" name="new_image" accept="image/*"
                                class="d-none" onchange="submitUpdateImage('{{ $img->id }}', this)">

                              <button type="button" class="btn btn-sm btn-icon btn-danger-light" title="Hapus Foto"
                                onclick="submitDeleteImage('{{ $img->id }}')">
                                <i class="ri-delete-bin-line"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    @empty
                      <div class="col-12 text-center py-4 bg-light rounded">
                        <p class="text-muted small mb-0 fst-italic">Belum ada foto yang diunggah.</p>
                      </div>
                    @endforelse
                  </div>
                </div>

                @if ($kendaraan->images->count() < 4)
                  <hr class="my-4 op-1">

                  <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <h6 class="fw-semibold mb-0 text-primary"><i class="ri-image-add-line me-2"></i>Unggah Foto
                        Kendaraan (Opsional)
                      </h6>
                      <button type="button" id="dropzone-upload-btn" class="btn btn-primary-light btn-sm">
                        <i class="ri-add-line me-1"></i>Tambahkan Gambar
                      </button>
                    </div>

                    {{-- Area Dropzone with horizontal overflow --}}
                    <div class="dropzone-scroll-wrapper">
                      <div class="dropzone custom-dropzone" id="vehicle-dropzone-edit">
                        <div class="dz-message" data-dz-message>
                          <div class="mb-3">
                            <i class="ri-upload-cloud-2-line fs-40 text-primary op-3 d-block mb-2"></i>
                          </div>
                          <h5 class="fs-14 fw-semibold mb-1">Tarik foto baru ke sini atau klik untuk memilih</h5>
                          <p class="text-muted small mb-0">Maksimal {{ 4 - $kendaraan->images->count() }} foto lagi.
                          </p>
                        </div>
                      </div>
                    </div>

                    {{-- Hidden container for real file inputs --}}
                    <div id="hidden-inputs-container" style="display: none;"></div>
                  </div>
                @endif
              </div>
            </div>

            <div class="d-flex gap-2 pt-4 border-top mt-4">
              <button type="submit" class="btn btn-primary px-4 py-2">
                <i class="ri-save-line me-2"></i>Simpan Perubahan
              </button>
              <a href="{{ route('perusahaan.kendaraan.index') }}" class="btn btn-light px-4">
                Kembali
              </a>
            </div>
          </form>

          <!-- Hidden forms for direct image actions to avoid nested forms -->
          <form id="delete-image-form" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
          </form>

          <form id="update-image-form" method="POST" enctype="multipart/form-data" style="display: none;">
            @csrf
            @method('PUT')
            <input type="file" id="global-update-image-input" name="new_image">
          </form>
        </div>
      </div>
    </div>
  </div>

  <style>
    .custom-dropzone {
      border: 2px dashed #e2e8ee !important;
      border-radius: 0.75rem !important;
      background-color: #f9fafb !important;
      min-height: 250px !important;
      padding: 1.5rem !important;
      transition: all 0.2s ease;
      display: flex !important;
      flex-direction: row !important;
      flex-wrap: nowrap !important;
      overflow-x: auto !important;
      align-items: center !important;
      gap: 15px;
      scrollbar-width: thin;
      scrollbar-color: var(--primary-color) #f0f0f0;
    }

    .custom-dropzone::-webkit-scrollbar {
      height: 6px;
    }

    .custom-dropzone::-webkit-scrollbar-track {
      background: #f0f0f0;
      border-radius: 10px;
    }

    .custom-dropzone::-webkit-scrollbar-thumb {
      background-color: var(--primary-color);
      border-radius: 10px;
    }

    .custom-dropzone:hover {
      border-color: var(--primary-color) !important;
      background-color: rgba(var(--primary-rgb), 0.02) !important;
    }

    .custom-dropzone .dz-message {
      flex: 0 0 100%;
      width: 100%;
      text-align: center;
      margin: 0 !important;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 2rem 0;
    }

    .custom-dropzone.dz-started .dz-message {
      display: none !important;
    }

    .dropzone .dz-preview {
      margin: 0 !important;
      background: #ffffff;
      padding: 12px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      border: 1px solid #e2e8ee;
      display: flex;
      flex-direction: column;
      align-items: stretch;
      position: relative;
      width: 220px !important;
      min-width: 220px !important;
      flex-shrink: 0;
    }

    .dropzone .dz-preview:hover .dz-image img {
      filter: none !important;
      transform: none !important;
    }

    .dropzone .dz-preview .dz-image {
      border-radius: 8px !important;
      width: 100% !important;
      height: 140px !important;
      margin-bottom: 12px;
      border: 1px solid #f0f0f0;
      overflow: hidden;
    }

    .dropzone .dz-preview .dz-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    .dz-caption-input {
      width: 100%;
      box-sizing: border-box;
      font-size: 12px;
      padding: 8px 10px;
      border: 1px solid #e2e8ee;
      border-radius: 6px;
      transition: border-color 0.15s ease-in-out;
      margin-bottom: 8px;
    }

    .dz-caption-input:hover {
      cursor: text;
    }

    .dz-caption-input:focus {
      border-color: var(--primary-color, #845adf);
      outline: 0;
      box-shadow: 0 0 0 0.25rem rgba(var(--primary-rgb), 0.25);
      cursor: text;
    }

    .dropzone .dz-preview .dz-remove {
      font-size: 12px;
      text-decoration: none;
      color: var(--danger-color, #dc3545);
      display: block;
      width: 100%;
      text-align: center;
      font-weight: 500;
      padding: 6px 10px;
      border-radius: 6px;
      background-color: rgba(var(--danger-rgb, 220, 53, 69), 0.1);
      transition: all 0.2s ease;
      cursor: pointer;
    }

    .dropzone .dz-preview .dz-remove:hover {
      background-color: var(--danger-color, #dc3545);
      color: #fff;
    }

    .dropzone .dz-preview .dz-details {
      display: none !important;
    }

    .dropzone .dz-preview .dz-error-message {
      position: absolute !important;
      top: 12px !important;
      left: 12px !important;
      width: calc(100% - 24px) !important;
      height: 140px !important;
      margin: 0 !important;
      background: rgba(0, 0, 0, 0.75) !important;
      backdrop-filter: blur(4px) !important;
      color: #fff !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      text-align: center !important;
      padding: 10px !important;
      border-radius: 8px !important;
      opacity: 0 !important;
      visibility: hidden !important;
      transition: all 0.3s ease !important;
      z-index: 30 !important;
      pointer-events: none;
      max-width: none !important;
    }

    .dropzone .dz-preview .dz-error-message span {
      font-size: 13px !important;
      font-weight: 500 !important;
      line-height: 1.4 !important;
    }

    .dropzone .dz-preview.dz-error:hover .dz-error-message {
      opacity: 1 !important;
      visibility: visible !important;
    }

    .dropzone .dz-preview .dz-error-message:after {
      display: none !important;
    }

    .dropzone .dz-preview .dz-error-mark,
    .dropzone .dz-preview .dz-success-mark {
      display: none !important;
    }

    .dropzone .dz-preview .dz-error-mark {
      position: absolute !important;
      top: 12px !important;
      left: 12px !important;
      width: calc(100% - 24px) !important;
      height: 140px !important;
      background: transparent !important;
      margin: 0 !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      z-index: 25 !important;
      pointer-events: none !important;
      opacity: 0 !important;
      transition: all 0.3s ease !important;
    }

    .dropzone .dz-preview.dz-error .dz-error-mark {
      opacity: 1 !important;
    }

    .dropzone .dz-preview .dz-error-mark svg {
      width: 45px !important;
      height: 45px !important;
      background: rgba(0, 0, 0, 0.6) !important;
      backdrop-filter: blur(4px) !important;
      border-radius: 50% !important;
      padding: 10px !important;
    }

    .dropzone .dz-preview .dz-error-mark svg circle,
    .dropzone .dz-preview .dz-error-mark svg g [fill="#be2626"] {
      display: none !important;
    }

    .dropzone .dz-preview .dz-error-mark svg path,
    .dropzone .dz-preview .dz-error-mark svg g [fill="#ffffff"] {
      fill: #fff !important;
    }

    /* Eye icon lights up when card is hovered */
    .dropzone .dz-preview:hover .btn-primary {
      background-color: var(--primary-color) !important;
      color: #fff !important;
      box-shadow: 0 4px 8px rgba(var(--primary-rgb), 0.3) !important;
    }

    .dropzone .dz-preview .dz-progress {
      display: none !important;
    }

    .dz-webp-loader {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.9);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      z-index: 15;
      font-size: 10px;
      color: var(--primary-color);
      font-weight: 600;
    }

    /* Circular Progress Bar CSS */
    .dz-webp-loader .progress-container {
      position: relative;
      width: 80px;
      height: 80px;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .dz-webp-loader .progress-ring {
      width: 80px;
      height: 80px;
    }

    .dz-webp-loader .progress-ring circle,
    .dz-webp-loader .progress-ring path {
      fill: none;
      stroke-width: 4;
      stroke-linecap: round;
    }

    .dz-webp-loader .progress-ring .progress-ring-bg {
      stroke: rgba(0, 0, 0, 0.05);
    }

    .dz-webp-loader .progress-ring .progress-ring-fill {
      stroke: var(--primary-color);
      transform: rotate(-90deg);
      transform-origin: 50% 50%;
      transition: stroke-dashoffset 0.1s linear;
    }

    .dz-webp-loader .progress-text {
      position: absolute;
      font-size: 11px;
      font-weight: 800;
      color: var(--primary-color);
    }

    /* Normalize Heights: Master height 42px */
    .choices__inner,
    .form-control:not(.dz-caption-input) {
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

    /* Optimasi performa hover lambat pada tombol card gambar */
    .card .btn-icon,
    .dropzone .btn-icon {
      backdrop-filter: none !important;
      -webkit-backdrop-filter: none !important;
      transform: translateZ(0);
      /* Hardware acceleration */
      will-change: transform, background-color, color, box-shadow;
      transition: background-color 0.15s ease, color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease !important;
    }
  </style>
@endsection

@section('scripts')
  @php
    $dzFilesize = '{{ filesize }}';
    $dzMaxFilesize = '{{ maxFilesize }}';
  @endphp
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Menggunakan escape sequence hexadecimal agar tidak terdeteksi oleh Blade/Formatter
      const dz_fs = "\x7b\x7bfilesize\x7d\x7d";
      const dz_mfs = "\x7b\x7bmaxFilesize\x7d\x7d";

      // Fungsi untuk membuat jalur SVG bergelombang (Wavy)
      function buatJalurGelombang(cx, cy, r, gelombang, tinggi, fase = 0) {
        let d = "";
        for (let i = 0; i <= 360; i += 2) {
          const rad = (i * Math.PI) / 180;
          const rGelombang = r + Math.sin((rad * gelombang) + fase) * tinggi;
          const x = cx + rGelombang * Math.cos(rad);
          const y = cy + rGelombang * Math.sin(rad);
          d += (i === 0 ? "M" : "L") + x + "," + y;
        }
        d += "Z";
        return d;
      }

      const lightbox = GLightbox({
        selector: '.glightbox',
        touchNavigation: true,
        loop: true,
        zoomable: true
      });

      // Fix ARIA error by blurring the trigger element when lightbox opens
      lightbox.on('open', () => {
        if (document.activeElement && (document.activeElement.classList.contains('glightbox') || document
            .activeElement.closest('.glightbox'))) {
          document.activeElement.blur();
        }
      });

      async function convertToWebP(file) {
        let blob = file;
        if (file.type === 'image/heic' || file.type === 'image/heif' || file.name.toLowerCase().endsWith('.heic') ||
          file
          .name.toLowerCase().endsWith('.heif')) {
          try {
            blob = await window.heic2any({
              blob: file,
              toType: 'image/jpeg',
              quality: 0.8
            });
            if (Array.isArray(blob)) blob = blob[0];
          } catch (e) {
            console.error("heic2any error:", e);
            throw e;
          }
        }

        return new Promise((resolve, reject) => {
          const img = new Image();
          const url = URL.createObjectURL(blob);
          img.onload = () => {
            const canvas = document.createElement('canvas');
            canvas.width = img.width;
            canvas.height = img.height;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0);
            canvas.toBlob((webpBlob) => {
              URL.revokeObjectURL(url);
              if (webpBlob) {
                const newName = file.name.replace(/\.[^/.]+$/, "") + ".webp";
                const newFile = new File([webpBlob], newName, {
                  type: 'image/webp',
                  lastModified: Date.now()
                });
                resolve(newFile);
              } else {
                reject(new Error("Canvas toBlob failed"));
              }
            }, 'image/webp', 0.8);
          };
          img.onerror = (e) => {
            URL.revokeObjectURL(url);
            reject(e);
          };
          img.src = url;
        });
      }

      window.submitDeleteImage = function(imageId) {
        if (window.showConfirmModal) {
          window.showConfirmModal('Yakin ingin menghapus foto ini?', function() {
            const form = document.getElementById('delete-image-form');
            form.action = `{{ url('perusahaan/kendaraan') }}/{{ $kendaraan->id }}/images/${imageId}`;
            form.submit();
          });
        } else if (confirm('Yakin ingin menghapus foto ini?')) {
          const form = document.getElementById('delete-image-form');
          form.action = `{{ url('perusahaan/kendaraan') }}/{{ $kendaraan->id }}/images/${imageId}`;
          form.submit();
        }
      }

      window.submitUpdateImage = async function(imageId, inputElement) {
        if (inputElement.files.length > 0) {
          if (inputElement.files[0].size > 2 * 1024 * 1024) {
            alert('Ukuran gambar maksimal 2MB.');
            inputElement.value = '';
            return;
          }

          const labelElement = document.querySelector(`label[for="update-img-${imageId}"]`);
          const originalHtml = labelElement.innerHTML;
          labelElement.innerHTML =
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
          labelElement.style.pointerEvents = 'none';

          try {
            const webpFile = await convertToWebP(inputElement.files[0]);

            const form = document.getElementById('update-image-form');
            form.action = `{{ url('perusahaan/kendaraan') }}/{{ $kendaraan->id }}/images/${imageId}`;

            const globalInput = document.getElementById('global-update-image-input');
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(webpFile);
            globalInput.files = dataTransfer.files;

            form.submit();
          } catch (error) {
            console.error(error);
            alert('Gagal mengkonversi gambar. Pastikan format gambar didukung.');
            labelElement.innerHTML = originalHtml;
            labelElement.style.pointerEvents = 'auto';
            inputElement.value = ''; // reset
          }
        }
      }

      // Pastikan Dropzone tidak meng-auto discover
      Dropzone.autoDiscover = false;

      const form = document.getElementById('kendaraan-form');
      const hiddenInputsContainer = document.getElementById('hidden-inputs-container');
      const dropzoneElement = document.querySelector("#vehicle-dropzone-edit");

      let rejectedAlerts = [];
      let alertTimeout = null;

      function showBatchedAlerts() {
        if (rejectedAlerts.length > 0) {
          const msg = rejectedAlerts.join('<br><br>');
          if (window.showAlertModal) {
            window.showAlertModal(msg, 'warning');
          } else if (window.Swal) {
            Swal.fire({
              icon: 'warning',
              title: 'Peringatan Upload',
              html: msg
            });
          } else {
            alert(msg.replace(/<br><br>/g, '\n\n'));
          }
          rejectedAlerts = [];
        }
      }

      if (dropzoneElement) {
        const myDropzone = new Dropzone("#vehicle-dropzone-edit", {
          url: "{{ route('perusahaan.kendaraan.update', $kendaraan->id) }}",
          autoProcessQueue: false,
          uploadMultiple: true,
          parallelUploads: 4,
          maxFiles: {{ 4 - $kendaraan->images->count() }},
          maxFilesize: 2, // 2MB limit
          acceptedFiles: "image/*,.heic,.heif",
          addRemoveLinks: true,
          clickable: "#dropzone-upload-btn, #vehicle-dropzone-edit",
          dictRemoveFile: "<i class='ri-delete-bin-line me-1'></i>Hapus",
          dictFileTooBig: `Ukuran file terlalu besar (${dz_fs}MB). Maksimal: ${dz_mfs}MB.`,
          dictMaxFilesExceeded: "Anda tidak dapat mengunggah foto lebih banyak lagi.",
          thumbnailWidth: 400,
          thumbnailHeight: 400,

          init: function() {
            this.on("addedfile", async function(file) {
              // Validation Logic
              // Validation Logic: Check file size first
              if (file.size > (this.options.maxFilesize * 1024 * 1024)) {
                rejectedAlerts.push(
                  `Gambar "${file.name}" terlalu besar (${(file.size / (1024 * 1024)).toFixed(2)}MB). Maksimal ${this.options.maxFilesize}MB.`
                );
                this.removeFile(file);
                clearTimeout(alertTimeout);
                alertTimeout = setTimeout(showBatchedAlerts, 100);
                return;
              }

              if (this.files.length > this.options.maxFiles) {
                rejectedAlerts.push(
                  `Gambar "${file.name}" tidak dapat diupload karena batas maksimal 4 foto telah tercapai.`
                );
                this.removeFile(file);
                clearTimeout(alertTimeout);
                alertTimeout = setTimeout(showBatchedAlerts, 100);
                return;
              }

              const captionInput = Dropzone.createElement(
                '<input type="text" class="dz-caption-input form-control" placeholder="Keterangan...">');
              file.previewElement.appendChild(captionInput);
              file.captionInput = captionInput;

              // Handle HEIC Preview (Only convert HEIC for preview)
              if (file.type === 'image/heic' || file.name.toLowerCase().endsWith('.heic') || file.name
                .toLowerCase().endsWith('.heif')) {
                const loader = Dropzone.createElement(
                  `<div class="dz-webp-loader">
                      <div class="progress-container">
                        <svg class="progress-ring" viewBox="0 0 100 100">
                          <circle class="progress-ring-bg" cx="50" cy="50" r="35"></circle>
                          <path class="progress-ring-fill"></path>
                        </svg>
                        <span class="progress-text">0%</span>
                      </div>
                      <span>Mengonversi HEIC...</span>
                    </div>`
                );
                file.previewElement.querySelector(".dz-image").appendChild(loader);

                const garisFill = loader.querySelector('.progress-ring-fill');
                const text = loader.querySelector('.progress-text');

                let progress = 0;
                let fase = 0;
                let requestId;

                // Optimasi: Hindari getTotalLength setiap frame (performa berat)
                const panjangTotal = 220; // 2 * PI * r (35)
                garisFill.style.strokeDasharray = panjangTotal;

                const animate = () => {
                  fase += 0.04;
                  const wavyPathData = buatJalurGelombang(50, 50, 35, 10, 2, fase);
                  garisFill.setAttribute('d', wavyPathData);

                  const offset = panjangTotal - (progress / 100 * panjangTotal);
                  garisFill.style.strokeDashoffset = offset;

                  text.innerText = Math.round(progress) + '%';
                  requestId = requestAnimationFrame(animate);
                };

                requestId = requestAnimationFrame(animate);

                // Progres simulasi
                const interval = setInterval(() => {
                  if (progress < 99) {
                    progress += (99 - progress) * 0.05;
                  }
                }, 300);

                try {
                  const webpFile = await convertToWebP(file);
                  clearInterval(interval);
                  progress = 100;

                  setTimeout(() => {
                    cancelAnimationFrame(requestId);
                    file.webpFile = webpFile;
                    const url = URL.createObjectURL(webpFile);
                    file.dataURL = url;
                    this.emit("thumbnail", file, url);
                    loader.remove();
                  }, 500);
                } catch (e) {
                  clearInterval(interval);
                  cancelAnimationFrame(requestId);
                  console.error("HEIC Preview Error:", e);
                  loader.innerHTML = '<span class="text-danger">Gagal Konversi</span>';
                }
              }

              // Create eye button
              const eyeBtn = Dropzone.createElement(
                '<button type="button" class="btn btn-sm btn-icon btn-primary position-absolute" style="top: 15px; right: 15px; z-index: 20; border-radius: 50%;" title="Lihat Detail"><i class="ri-eye-line"></i></button>'
              );
              eyeBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                // Fallback to URL.createObjectURL if Dropzone thumbnail is missing
                const url = file.dataURL || (file.previewElement.querySelector('img') ? file
                  .previewElement.querySelector('img').src : URL.createObjectURL(file));
                let caption = captionInput.value ||
                  '<span class="text-muted fst-italic">Tanpa Keterangan</span>';

                // Tambahkan peringatan jika file terlalu besar
                if (file.size > (this.options.maxFilesize * 1024 * 1024)) {
                  caption =
                    `<span class="text-danger fw-bold"><i class="ri-error-warning-line me-1"></i> PERINGATAN: File ini terlalu besar (${(file.size / (1024 * 1024)).toFixed(2)}MB) dan tidak akan ikut tersimpan.</span><br>${caption}`;
                }

                if (url) {
                  GLightbox({
                    elements: [{
                      href: url,
                      type: 'image',
                      title: caption
                    }]
                  }).open();
                }
              });
              file.previewElement.appendChild(eyeBtn);

              // Make image clickable
              file.previewElement.querySelector('.dz-image').addEventListener('click', () => {
                const url = file.dataURL || (file.previewElement.querySelector('img') ? file
                  .previewElement.querySelector('img').src : URL.createObjectURL(file));
                let caption = captionInput.value ||
                  '<span class="text-muted fst-italic">Tanpa Keterangan</span>';

                // Tambahkan peringatan jika file terlalu besar
                if (file.size > (this.options.maxFilesize * 1024 * 1024)) {
                  caption =
                    `<span class="text-danger fw-bold"><i class="ri-error-warning-line me-1"></i> PERINGATAN: File ini terlalu besar (${(file.size / (1024 * 1024)).toFixed(2)}MB) dan tidak akan ikut tersimpan.</span><br>${caption}`;
                }

                if (url) {
                  GLightbox({
                    elements: [{
                      href: url,
                      type: 'image',
                      title: caption
                    }]
                  }).open();
                }
              });
            });
          }
        });

        form.addEventListener('submit', async function(e) {
          const queuedFiles = myDropzone.getQueuedFiles();

          if (queuedFiles.length > 0) {
            e.preventDefault();

            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML =
              '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving...';
            submitBtn.disabled = true;

            hiddenInputsContainer.innerHTML = '';

            try {
              const dataTransfer = new DataTransfer();

              // Optimasi: Konversi file HEIC secara paralel untuk mempercepat performa
              const conversionPromises = queuedFiles.map(async (file) => {
                const webpFile = file.webpFile || await convertToWebP(file);
                return {
                  file,
                  webpFile
                };
              });

              const results = await Promise.all(conversionPromises);

              results.forEach(({
                file,
                webpFile
              }) => {
                dataTransfer.items.add(webpFile);

                const hiddenCaption = document.createElement('input');
                hiddenCaption.type = 'hidden';
                hiddenCaption.name = 'captions[]';
                hiddenCaption.value = file.captionInput ? file.captionInput.value : '';
                hiddenInputsContainer.appendChild(hiddenCaption);
              });

              const fileInput = document.createElement('input');
              fileInput.type = 'file';
              fileInput.name = 'images[]';
              fileInput.multiple = true;
              fileInput.files = dataTransfer.files;
              hiddenInputsContainer.appendChild(fileInput);

              form.submit();
            } catch (error) {
              console.error(error);
              alert('Gagal mengkonversi gambar. Pastikan format gambar didukung.');
              submitBtn.innerHTML = originalText;
              submitBtn.disabled = false;
            }
          }
        });
      }
    });
  </script>
@endsection
