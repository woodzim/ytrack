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
                  <h6 class="fw-semibold mb-3"><i class="ri-information-line me-2 text-primary"></i>Informasi Kendaraan</h6>
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
                      <label for="plat" class="form-label fw-medium">Nomor Plat <span
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

              <div class="col-12 border-top pt-4">
                <div class="mb-0">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-semibold mb-0"><i class="ri-image-add-line me-2 text-primary"></i>Unggah Foto Kendaraan (Opsional)
                    </h6>
                  </div>

                  {{-- Area Dropzone with horizontal overflow --}}
                  <div class="dropzone-scroll-wrapper">
                    <div class="dropzone custom-dropzone" id="vehicle-dropzone">
                      <div class="dz-message" data-dz-message>
                        <div class="mb-3">
                          <i class="ri-upload-cloud-2-line fs-40 text-primary op-3"></i>
                        </div>
                        <h5 class="fs-14 fw-semibold mb-1">Klik atau tarik foto di sini </h5>
                        <p class="text-muted small mb-0">Maksimal 4 foto (Maks: 2MB/file)
                        </p>
                      </div>
                    </div>
                  </div>

                  {{-- Hidden container for real file inputs --}}
                  <div id="hidden-inputs-container" style="display: none;"></div>
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
    /* GLightbox custom styles for caption */
    .glightbox-clean .gslide-desc {
      background: rgba(0, 0, 0, 0.65) !important;
      backdrop-filter: blur(8px) !important;
      color: #fff !important;
      padding: 12px 15px !important;
      border-radius: 8px !important;
      margin-bottom: 15px !important;
    }

    .glightbox-clean .gslide-title {
      color: #fff !important;
      margin-bottom: 0 !important;
      font-size: 14px !important;
      font-weight: 500 !important;
    }

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

    /* Hide message when there are files */
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
      cursor: pointer;
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
      display: none !important; /* Remove the default arrow */
    }

    .dropzone .dz-preview .dz-error-mark,
    .dropzone .dz-preview .dz-success-mark {
      display: none !important;
    }

    .dropzone .dz-preview .dz-error-mark {
      position: absolute !important;
      top: 12px !important; /* Top padding of card */
      left: 12px !important; /* Left padding of card */
      width: calc(100% - 24px) !important;
      height: 140px !important; /* Height of the image area */
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
    .dropzone .dz-preview:hover .btn-info-light {
      background-color: #0dcaf0 !important; /* Solid info color */
      color: #fff !important;
      box-shadow: 0 4px 8px rgba(13, 202, 240, 0.3) !important;
      border-color: #0dcaf0 !important;
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
      transition: stroke-dashoffset 0.1s linear; /* Faster transition for smoother wave animation */
    }

    .dz-webp-loader .progress-text {
      position: absolute;
      font-size: 11px;
      font-weight: 800;
      color: var(--primary-color);
    }

    /* Normalize Heights: Set Choices.js height as the master, others follow */
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
    .card .btn-icon, .dropzone .btn-icon {
      backdrop-filter: none !important;
      -webkit-backdrop-filter: none !important;
      transform: translateZ(0); /* Hardware acceleration */
      will-change: transform, background-color, color, box-shadow;
      transition: background-color 0.15s ease, color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease !important;
    }
  </style>
@endsection

@section('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Menggunakan escape sequence hexadecimal agar tidak terdeteksi oleh Blade/Formatter
      const dz_fs = "\x7b\x7bfilesize\x7d\x7d";
      const dz_mfs = "\x7b\x7bmaxFilesize\x7d\x7d";

      // Fungsi untuk membuat jalur SVG bergelombang dinamis (Expressive Wavy)
      function buatJalurGelombang(cx, cy, r, gelombang, tinggi, fase = 0) {
        let d = "";
        for (let i = 0; i <= 360; i += 2) { // i += 2 untuk performa lebih baik
          const rad = (i * Math.PI) / 180;
          // Menambahkan parameter 'fase' ke dalam Math.sin agar gelombang bergeser
          const rGelombang = r + Math.sin((rad * gelombang) + fase) * tinggi;
          const x = cx + rGelombang * Math.cos(rad);
          const y = cy + rGelombang * Math.sin(rad);
          d += (i === 0 ? "M" : "L") + x + "," + y;
        }
        d += "Z";
        return d;
      }

      // Inisialisasi GLightbox
      const lightbox = GLightbox({
        selector: '.glightbox',
        touchNavigation: true,
        loop: true,
        zoomable: true
      });

      // Fix ARIA error by blurring the trigger element when lightbox opens
      lightbox.on('open', () => {
        if (document.activeElement && (document.activeElement.classList.contains('glightbox') || document.activeElement.closest('.glightbox'))) {
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

      // Pastikan Dropzone tidak meng-auto discover
      Dropzone.autoDiscover = false;

      const form = document.getElementById('kendaraan-form');
      const hiddenInputsContainer = document.getElementById('hidden-inputs-container');

      const myDropzone = new Dropzone("#vehicle-dropzone", {
        url: "{{ route('perusahaan.kendaraan.store') }}",
        autoProcessQueue: false, // Jangan upload langsung
        uploadMultiple: true,
        parallelUploads: 4,
        maxFiles: 4,
        maxFilesize: 2, // 2MB
        acceptedFiles: "image/*,.heic,.heif",
        addRemoveLinks: true,
        clickable: "#dropzone-upload-btn, #vehicle-dropzone", // Hubungkan tombol ke dropzone
        dictRemoveFile: "<i class='ri-delete-bin-line me-1'></i>Hapus",
        dictFileTooBig: `Ukuran file terlalu besar (${dz_fs}MB). Maksimal: ${dz_mfs}MB.`,
        dictMaxFilesExceeded: "Anda tidak dapat mengunggah foto lebih banyak lagi.",
        thumbnailWidth: 400,
        thumbnailHeight: 400,

        init: function() {
          this.on("addedfile", async function(file) {
            // Buat input keterangan untuk setiap file
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
                fase += 0.04; // Disetel ke 0.04
                const pathData = buatJalurGelombang(50, 50, 35, 10, 2, fase);
                garisFill.setAttribute('d', pathData);

                const offset = panjangTotal - (progress / 100 * panjangTotal);
                garisFill.style.strokeDashoffset = offset;

                text.innerText = Math.round(progress) + '%';
                requestId = requestAnimationFrame(animate);
              };

              requestId = requestAnimationFrame(animate);

              // Progres simulasi angka
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

            // Make image clickable
            const imgEl = file.previewElement.querySelector('.dz-image');
            imgEl.style.cursor = 'pointer';
            imgEl.addEventListener('click', () => {
              // Jika dataURL tidak ada (karena error size), buat URL objek manual dari file asli
              const url = file.dataURL || (file.previewElement.querySelector('img') ? file.previewElement.querySelector('img').src : URL.createObjectURL(file));
              let caption = captionInput.value || '<span class="text-muted fst-italic">Tanpa Keterangan</span>';
              
              // Tambahkan peringatan jika file terlalu besar
              if (file.size > (myDropzone.options.maxFilesize * 1024 * 1024)) {
                caption = `<span class="text-danger fw-bold"><i class="ri-error-warning-line me-1"></i> PERINGATAN: File ini terlalu besar (${(file.size / (1024 * 1024)).toFixed(2)}MB) dan tidak akan ikut tersimpan.</span><br>${caption}`;
              }
              
              GLightbox({
                elements: [{
                  href: url,
                  type: 'image',
                  title: caption
                }]
              }).open();
            });

            // Create eye button
            const eyeBtn = Dropzone.createElement(
              '<button type="button" class="btn btn-sm btn-icon btn-info-light position-absolute" style="top: 20px; cursor: pointer; right: 20px; z-index: 20;" title="Lihat Detail"><i class="ri-eye-line"></i></button>'
            );
            eyeBtn.addEventListener('click', (e) => {
              e.preventDefault();
              e.stopPropagation();
              const url = file.dataURL || (file.previewElement.querySelector('img') ? file.previewElement.querySelector('img').src : URL.createObjectURL(file));
              let caption = captionInput.value || '<span class="text-muted fst-italic">Tanpa Keterangan</span>';

              // Tambahkan peringatan jika file terlalu besar
              if (file.size > (myDropzone.options.maxFilesize * 1024 * 1024)) {
                caption = `<span class="text-danger fw-bold"><i class="ri-error-warning-line me-1"></i> PERINGATAN: File ini terlalu besar (${(file.size / (1024 * 1024)).toFixed(2)}MB) dan tidak akan ikut tersimpan.</span><br>${caption}`;
              }

              GLightbox({
                elements: [{
                  href: url,
                  type: 'image',
                  title: caption
                }]
              }).open();
            });
            file.previewElement.appendChild(eyeBtn);
          });
        }
      });
      // Handle Submit
      form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Validasi form dasar (nama, plat, dll)
        if (!form.checkValidity()) {
          form.reportValidity();
          return;
        }

        const acceptedFiles = myDropzone.getAcceptedFiles();

        const submitBtn = document.getElementById('submit-all');
        const originalText = submitBtn.innerHTML;

        if (acceptedFiles.length > 0) {
          submitBtn.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses Gambar...';
          submitBtn.disabled = true;
        }

        // Bersihkan input lama
        hiddenInputsContainer.innerHTML = '';

        try {
          const dataTransfer = new DataTransfer();
          
          // Optimasi: Konversi file HEIC secara paralel untuk mempercepat performa
          const conversionPromises = acceptedFiles.map(async (file) => {
            const webpFile = file.webpFile || await convertToWebP(file);
            return { file, webpFile };
          });
          
          const results = await Promise.all(conversionPromises);
          
          results.forEach(({ file, webpFile }) => {
            dataTransfer.items.add(webpFile);

            // Tambahkan keterangan sebagai input hidden
            const hiddenCaption = document.createElement('input');
            hiddenCaption.type = 'hidden';
            hiddenCaption.name = 'captions[]';
            hiddenCaption.value = file.captionInput ? file.captionInput.value : '';
            hiddenInputsContainer.appendChild(hiddenCaption);
          });

          // Buat input file dinamis
          if (dataTransfer.files.length > 0) {
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.name = 'images[]';
            fileInput.multiple = true;
            fileInput.files = dataTransfer.files;
            hiddenInputsContainer.appendChild(fileInput);
          }

          // Submit form
          form.submit();
        } catch (error) {
          console.error(error);
          alert('Gagal mengkonversi gambar. Pastikan format gambar didukung.');
          if (acceptedFiles.length > 0) {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
          }
        }
      });
    });
  </script>
@endsection
