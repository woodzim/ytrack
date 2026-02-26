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
        <div class="card-body p-4">
          <form action="{{ route('perusahaan.kendaraan.update', $kendaraan->id) }}" method="POST"
            enctype="multipart/form-data" id="kendaraan-form">
            @csrf
            @method('PUT')

            <div class="row g-4">
              <div class="col-lg-5">
                <div class="mb-4">
                  <h6 class="fw-semibold mb-3"><i class="ri-information-line me-2 text-primary"></i>Informasi Dasar</h6>
                  <div class="mb-3">
                    <label for="id_jenis_kendaraan" class="form-label fw-medium">Jenis Kendaraan <span
                        class="text-danger">*</span></label>
                    <select class="form-select @error('id_jenis_kendaraan') is-invalid @enderror" id="id_jenis_kendaraan"
                      name="id_jenis_kendaraan" required>
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

                  <div class="mb-3">
                    <label for="nama" class="form-label fw-medium">Nama Kendaraan <span
                        class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                      name="nama" placeholder="Nama Kendaraan" value="{{ old('nama', $kendaraan->nama) }}" required>
                    @error('nama')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="mb-3">
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

              <div class="col-lg-7 border-start">
                <div class="mb-4">
                  <h6 class="fw-semibold mb-3"><i class="ri-gallery-line me-2 text-primary"></i>Foto Saat Ini</h6>
                  <div class="row g-3">
                    @forelse ($kendaraan->images as $img)
                      <div class="col-sm-6">
                        <div class="card shadow-none border mb-0 position-relative overflow-hidden">
                          <img src="{{ asset('storage/' . $img->image_path) }}" class="card-img-top"
                            style="height: 140px; object-fit: cover;">
                          <div class="p-2 d-flex justify-content-between align-items-center">
                            <p class="small text-truncate mb-0 fw-medium" title="{{ $img->caption }}">
                              {{ $img->caption ?: 'Tanpa Keterangan' }}</p>
                            <div class="d-flex gap-1">
                              <a href="{{ asset('storage/' . $img->image_path) }}"
                                class="glightbox btn btn-sm btn-icon btn-info-light mb-0" data-gallery="kendaraan-gallery"
                                title="Lihat Foto Utuh">
                                <i class="ri-eye-line"></i>
                              </a>

                              <label for="update-img-{{ $img->id }}"
                                class="btn btn-sm btn-icon btn-primary-light mb-0" title="Ubah Foto"
                                style="cursor:pointer;">
                                <i class="ri-edit-line"></i>
                              </label>
                              <input type="file" id="update-img-{{ $img->id }}" name="new_image" accept="image/*"
                                class="d-none" onchange="submitUpdateImage({{ $img->id }}, this)">

                              <button type="button" class="btn btn-sm btn-icon btn-danger-light" title="Hapus Foto"
                                onclick="submitDeleteImage({{ $img->id }})">
                                <i class="ri-delete-bin-line"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    @empty
                      <div class="col-12 text-center py-4 bg-light rounded">
                        <p class="text-muted small mb-0">Belum ada foto yang diunggah.</p>
                      </div>
                    @endforelse
                  </div>
                </div>

                @if ($kendaraan->images->count() < 4)
                  <hr class="my-4 op-1">

                  <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <h6 class="fw-semibold mb-0 text-primary"><i class="ri-image-add-line me-2"></i>Tambah Foto Baru
                      </h6>
                    </div>

                    {{-- Area Dropzone --}}
                    <div class="dropzone custom-dropzone" id="vehicle-dropzone-edit">
                      <div class="dz-message" data-dz-message>
                        <div class="mb-3">
                          <i class="ri-upload-cloud-2-line fs-48 text-primary op-3 d-block mb-2"></i>
                        </div>
                        <h5 class="fs-14 fw-semibold mb-1">Tarik foto baru ke sini atau klik tombol "Pilih File"</h5>
                        <p class="text-muted small mb-0">Bisa lebih dari 1 foto (Maksimal
                          {{ 4 - $kendaraan->images->count() }} foto). Ukuran maks: 2MB/file.
                        </p>
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

  <!-- GLightbox CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />

  <style>
    .custom-dropzone {
      border: 2px dashed #e2e8ee !important;
      border-radius: 0.75rem !important;
      background-color: #f9fafb !important;
      min-height: 250px !important;
      padding: 2rem !important;
      transition: all 0.2s ease;
      display: grid !important;
      grid-template-columns: repeat(2, 1fr);
      align-items: center;
      gap: 15px;
    }

    .custom-dropzone:hover {
      border-color: var(--primary-color) !important;
      background-color: rgba(var(--primary-rgb), 0.02) !important;
    }

    .custom-dropzone .dz-message {
      grid-column: 1 / -1;
      width: 100%;
      text-align: center;
      margin: 0 !important;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 2rem 0;
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
      width: 100%;
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
      top: 150px !important;
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
  </style>

  <!-- GLightbox Script -->
  <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>

  <script>
    const lightbox = GLightbox({
      selector: '.glightbox',
      touchNavigation: true,
      loop: true,
      zoomable: true
    });

    async function convertToWebP(file) {
      let blob = file;
      if (file.type === 'image/heic' || file.type === 'image/heif' || file.name.toLowerCase().endsWith('.heic') || file
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

    function submitDeleteImage(imageId) {
      if (confirm('Yakin ingin menghapus foto ini?')) {
        const form = document.getElementById('delete-image-form');
        form.action = `{{ url('perusahaan/kendaraan') }}/{{ $kendaraan->id }}/images/${imageId}`;
        form.submit();
      }
    }

    async function submitUpdateImage(imageId, inputElement) {
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

        document.addEventListener('DOMContentLoaded', function() {
          Dropzone.autoDiscover = false;
    
          const form = document.getElementById('kendaraan-form');
          const hiddenInputsContainer = document.getElementById('hidden-inputs-container');
    
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
            dictFileTooBig: "Ukuran file terlalu besar (@{{filesize}}MB). Maksimal: @{{maxFilesize}}MB.",
            thumbnailWidth: 800,
            thumbnailHeight: 800,
    
                    init: function() {
                      this.on("addedfile", async function(file) {
                        const captionInput = Dropzone.createElement(
                          '<input type="text" class="dz-caption-input form-control" placeholder="Keterangan...">');
                        file.previewElement.appendChild(captionInput);
                        file.captionInput = captionInput;
            
                        // Handle HEIC Preview (Only convert HEIC for preview)
                        if (file.type === 'image/heic' || file.name.toLowerCase().endsWith('.heic') || file.name.toLowerCase().endsWith('.heif')) {
                            const loader = Dropzone.createElement('<div class="dz-webp-loader"><div class="spinner-border spinner-border-sm mb-1" role="status"></div><span>Converting HEIC...</span></div>');
                            file.previewElement.querySelector(".dz-image").appendChild(loader);
            
                            try {
                                const webpFile = await convertToWebP(file);
                                file.webpFile = webpFile;
                                const url = URL.createObjectURL(webpFile);
                                file.dataURL = url;
                                this.emit("thumbnail", file, url);
                                loader.remove();
                            } catch (e) {
                                console.error("HEIC Preview Error:", e);
                                loader.innerHTML = '<span class="text-danger">Error</span>';
                            }
                        }
            
                        // Create eye button
                        const eyeBtn = Dropzone.createElement('<button type="button" class="btn btn-sm btn-icon btn-primary position-absolute" style="top: 15px; right: 15px; z-index: 20; border-radius: 50%;" title="Lihat Detail"><i class="ri-eye-line"></i></button>');
                        eyeBtn.addEventListener('click', (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            const url = file.dataURL || (file.previewElement.querySelector('img') ? file.previewElement.querySelector('img').src : null);
                            if (url) {
                                const caption = captionInput.value || 'Tanpa Keterangan';
                                GLightbox({ elements: [{ href: url, type: 'image', title: caption }] }).open();
                            }
                        });
                        file.previewElement.appendChild(eyeBtn);
            
                        // Make image clickable
                        file.previewElement.querySelector('.dz-image').addEventListener('click', () => {
                          const url = file.dataURL || (file.previewElement.querySelector('img') ? file.previewElement.querySelector('img').src : null);
                          if (url) {
                            const caption = captionInput.value || 'Tanpa Keterangan';
                            GLightbox({ elements: [{ href: url, type: 'image', title: caption }] }).open();
                          }
                        });
                      });
                    }          });
    
          form.addEventListener('submit', async function(e) {
            const queuedFiles = myDropzone.getQueuedFiles();
    
            if (queuedFiles.length > 0) {
              e.preventDefault();
              
              const submitBtn = form.querySelector('button[type="submit"]');
              const originalText = submitBtn.innerHTML;
              submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving...';
              submitBtn.disabled = true;
              
              hiddenInputsContainer.innerHTML = '';
    
              try {
                const dataTransfer = new DataTransfer();
                for (const file of queuedFiles) {
                  const webpFile = file.webpFile || await convertToWebP(file);
                  dataTransfer.items.add(webpFile);
    
                  const hiddenCaption = document.createElement('input');
                  hiddenCaption.type = 'hidden';
                  hiddenCaption.name = 'captions[]';
                  hiddenCaption.value = file.captionInput ? file.captionInput.value : '';
                  hiddenInputsContainer.appendChild(hiddenCaption);
                }
    
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
        });  </script>
@endsection
