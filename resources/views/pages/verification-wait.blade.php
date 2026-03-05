@extends('layouts.custom-master')

@php
  $bodyClass = 'authentication-background';
@endphp

@section('content')
  <div class="authentication-basic-background">
    <img src="{{ asset('build/assets/images/media/backgrounds/9.png') }}" alt="">
  </div>

  <div class="container-fluid px-4 px-md-5">
    <div class="row justify-content-center align-items-center authentication authentication-basic min-vh-100 py-4">
      <div class="col-12">
        <div class="card custom-card border-0 my-4">
          <div class="card-body p-5">
            <div class="mb-4">
              <a href="{{ route('login') }}" class="w-fit bg-danger">
                <img src="{{ asset('images/long-logo.webp') }}" alt="logo" class="desktop-dark mt-3 mt-md-0">
              </a>
            </div>
            @php
              $user = auth()->user();
              $perusahaan = $user->hasRole('perusahaan') ? \App\Models\Perusahaan::find($user->id_perusahaan) : null;

              // Cek status validasi admin legacy
              $isSubmitted = $perusahaan && $perusahaan->berkas === 'submitted_multi_flow';

              $docs = [
                  'akta' => ['label' => 'Akta Pendirian', 'required' => true],
                  'npwp_badan' => ['label' => 'NPWP Badan Usaha', 'required' => true],
                  'nib' => ['label' => 'NIB (OSS)', 'required' => true],
                  'domisili' => ['label' => 'Keterangan Domisili', 'required' => true],
                  'ktp_pj' => ['label' => 'KTP Penanggung Jawab', 'required' => true],
                  'npwp_pj' => ['label' => 'NPWP Penanggung Jawab', 'required' => true],
                  'izin_usaha' => ['label' => 'Izin Usaha / Opsional', 'required' => false],
                  'siup' => ['label' => 'SIUP / TDP', 'required' => false],
              ];
            @endphp

            <div>
              <h4 class="mb-1 fw-semibold">
                <span>{{ $isSubmitted ? 'Menunggu Verifikasi Admin' : 'Lengkapi Dokumen' }}</span>
              </h4>
              <p class="mb-4 text-muted fw-normal">
                @if ($isSubmitted)
                  Email Anda sudah terverifikasi, namun akun Anda masih menunggu validasi dari administrator.
                @else
                  Email Anda sudah terverifikasi, silakan lengkapi dokumen perusahaan di bawah ini untuk melanjutkan.
                @endif
              </p>
            </div>

            @include('components.alert')

            @if ($perusahaan && !$isSubmitted)
              <div class="alert alert-primary mb-4 border-0 shadow-sm">
                <i class="ri-information-line me-2"></i><strong>Aturan:</strong> Silakan unggah masing-masing dokumen
                sesuai kotak yang tersedia di bawah ini. Anda <strong>wajib</strong> melengkapi seluruh dokumen bertanda
                <span class="badge bg-danger ms-1">Wajib</span> sebelum dapat menekan tombol <strong>Kirim Permohonan
                  Validasi</strong>.
              </div>

              <div class="row g-3 mb-4">
                @foreach ($docs as $type => $info)
                  @php
                    $column = 'berkas_' . $type;
                    $hasFile = $perusahaan->{$column} ? true : false;
                  @endphp
                  <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-4">
                    <div class="card border border-primary shadow-sm h-100">
                      <div class="card-body p-3 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start">
                          <h6 class="mb-0 fw-semibold fs-13 lh-base">{{ $info['label'] }}</h6>
                          @if ($info['required'])
                            <span class="badge bg-danger-transparent text-danger">Wajib</span>
                          @else
                            <span class="badge bg-light text-muted">Opsional</span>
                          @endif
                        </div>

                        @php
                          if ($hasFile) {
                              $fullPath = $perusahaan->{$column};
                              $basename = basename($fullPath);
                              $parts = explode('_', $basename, 2);
                              $fileName = isset($parts[1]) ? $parts[1] : $basename;
                          }
                        @endphp

                        <div class="custom-file-container dropzone dz-multi mt-auto w-100 p-2"
                          id="dropzone-{{ $type }}" data-type="{{ $type }}">
                          <div class="dz-message m-0 w-100 h-100 d-flex flex-column align-items-center" data-dz-message
                            style="gap: 5px;">
                            <div class="custom-file-header py-4 flex-grow-1">
                              <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                  <path
                                    d="M7 10V9C7 6.23858 9.23858 4 12 4C14.7614 4 17 6.23858 17 9V10C19.2091 10 21 11.7909 21 14C21 15.4806 20.1956 16.8084 19 17.5M7 10C4.79086 10 3 11.7909 3 14C3 15.4806 3.8044 16.8084 5 17.5M7 10C7.43285 10 7.84965 10.0688 8.24006 10.1959M12 12V21M12 12L15 15M12 12L9 15"
                                    stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                  </path>
                                </g>
                              </svg>
                              <p class="dz-indicator-text">{{ $hasFile ? 'Ganti File' : 'Silakan Unggah File!' }}</p>
                            </div>
                          </div>

                          <div class="custom-file-footer w-100" onclick="event.stopPropagation();">
                            @if ($hasFile)
                              <i class="ri-delete-bin-line text-danger custom-file-delete d-flex justify-content-center align-items-center"
                                style="font-size: 14px; cursor: pointer; height:24px; width: 24px; background-color:rgba(70, 66, 66, 0.103); border-radius:50%;"
                                title="Hapus Berkas"
                                onclick="event.stopPropagation(); deleteBerkas('{{ $type }}', this)"></i>
                            @else
                              <i class="ri-delete-bin-line text-secondary custom-file-delete d-flex justify-content-center align-items-center"
                                style="font-size: 14px; opacity: 0.3; height:24px; width: 24px; background-color:rgba(70, 66, 66, 0.103); border-radius:50%;"
                                title="Belum ada file"></i>
                            @endif
                            <p class="custom-file-name text-truncate mx-2 mb-0" style="font-size: 11px;"
                              title="{{ $hasFile ? $fileName : 'Belum ada file' }}">
                              @if ($hasFile)
                                {{ $fileName }}
                              @else
                                Belum ada file
                              @endif
                            </p>
                            @if ($hasFile)
                              <a href="{{ Storage::url($fullPath) }}" target="_blank" class="custom-file-trash"
                                style="display:flex; align-items:center; text-decoration:none; cursor: pointer;"
                                title="Lihat Berkas">
                                <i class="ri-eye-line d-flex justify-content-center align-items-center"
                                  style="height:24px; cursor: pointer; width: 24px; background-color:rgba(70, 66, 66, 0.103); border-radius:50%; color: #333; font-size: 14px;"></i>
                              </a>
                            @else
                              <div class="custom-file-trash" style="display:flex; align-items:center; cursor: default;"
                                title="Belum ada file">
                                <i class="ri-eye-line d-flex justify-content-center align-items-center"
                                  style="height:24px; width: 24px; background-color:rgba(70, 66, 66, 0.103); border-radius:50%; color: #333; font-size: 14px; opacity: 0.3;"></i>
                              </div>
                            @endif
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>

              <form action="{{ route('verification.submit') }}" method="POST" class="mb-4">
                @csrf
                <div class="d-grid gap-2">
                  <button type="submit" id="submit-validation-btn" class="btn btn-primary btn-lg shadow-sm">
                    <i class="ri-send-plane-fill me-2"></i> Kirim Permohonan Validasi
                  </button>
                </div>
              </form>
            @endif

          @section('styles')
            <!-- Filepond CSS -->
            <link rel="stylesheet" href="{{ asset('build/assets/libs/dropzone/dropzone.css') }}">
            <style>
              .custom-file-container {
                min-height: 130px;
                height: auto;
                width: 100%;
                border-radius: 10px;
                box-shadow: 4px 4px 30px rgba(0, 0, 0, .1);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: space-between;
                padding: 10px;
                background-color: rgba(0, 110, 255, 0.041);
              }

              .custom-file-footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
                width: 100%;
                background-color: white;
                padding: 10px;
                border-radius: 10px;
                box-shadow: 0px 4px 10px rgba(0, 0, 0, .05);
                margin-top: 10px;
                position: relative;
                z-index: 10;
              }

              .custom-file-delete,
              .custom-file-trash {
                position: relative;
                z-index: 20;
                pointer-events: auto;
              }

              .custom-file-header {
                width: 100%;
                border: 2px dashed royalblue;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-direction: column;
                position: relative;
                overflow: hidden;
              }

              .custom-file-delete,
              .custom-file-trash i {
                transition: transform 0.2s ease, background-color 0.2s ease;
              }

              .custom-file-delete:hover,
              .custom-file-trash:hover i {
                transform: scale(1.15);
                background-color: rgba(70, 66, 66, 0.2) !important;
              }

              .custom-file-header svg {
                height: 50px;
              }

              .custom-file-header p {
                text-align: center;
                color: black;
                margin-top: 5px;
                margin-bottom: 0px;
              }

              .custom-file-footer {
                background-color: rgba(0, 110, 255, 0.075);
                height: 40px;
                padding: 8px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: flex-end;
                color: black;
                border: none;
              }

              .custom-file-footer>svg {
                height: 130%;
                fill: royalblue;
              }

              .custom-file-name {
                flex: 1;
                text-align: center;
              }

              .custom-file-trash {
                cursor: pointer;
                transition: transform 0.2s;
              }

              .custom-file-trash:hover {
                transform: scale(1.1);
              }

              .dropzone .dz-preview .dz-success-mark,
              .dropzone .dz-preview .dz-error-mark {
                top: 50%;
                left: 50%;
                margin-top: -27px;
                margin-left: -27px;
              }

              .dropzone .dz-preview .dz-preview-cover {
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
              }

              /* Sembunyikan caret bawaan bawaan bootstrap */
              .accordion-button::after {
                display: none !important;
              }

              /* Remove background and border from filename and size */
              .dropzone .dz-preview .dz-details .dz-size,
              .dropzone .dz-preview .dz-details .dz-size span,
              .dropzone .dz-preview .dz-details .dz-size strong,
              .dropzone .dz-preview .dz-details .dz-filename,
              .dropzone .dz-preview:hover .dz-image img,
              .dropzone .dz-preview .dz-details .dz-filename span,
              .dropzone .dz-preview:hover .dz-details .dz-filename span {
                background-color: transparent !important;
                background: none !important;
                border: none !important;
                color: #333;
              }

              /* Fix filename hover behavior to center fully without wrapping */
              .dropzone .dz-preview:hover .dz-details .dz-filename {
                white-space: nowrap !important;
                overflow: visible !important;
                width: auto !important;
                left: 50% !important;
                transform: translateX(-50%) !important;
                position: absolute !important;
                text-align: center !important;
                z-index: 50 !important;
                padding: 0 !important;
              }

              .dropzone .dz-preview .dz-image {
                display: none;
                border-radius: 8px !important;
                border: 1px solid #e2e8ee;
              }

              /* Ensure centering of filename */
              .dropzone .dz-preview .dz-details .dz-filename span {
                display: inline-block;
                max-width: 100%;
              }

              /* Adjust text color when hovered to ensure readability */
              .dropzone .dz-preview:hover .dz-details .dz-filename span {
                color: #0F2854;
                font-weight: 600;
              }

              /* Icon Indicator Dropdown */
              .accordion-button .accordion-indicator {
                transition: transform 0.2s ease-in-out;
              }

              .accordion-button:not(.collapsed) .accordion-indicator {
                transform: rotate(180deg);
              }

              /* Wavy Loader */
              .dropzone .dz-preview .dz-progress {
                display: none !important;
              }

              .dz-details {
                display: none;
              }

              .dz-image {
                display: none;
              }

              .dropzone .dz-preview.dz-file-preview.dz-processing {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                margin: 0;
                z-index: 10;
              }

              .dz-wavy-loader {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.85);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                z-index: 15;
                font-size: 10px;
                color: var(--primary-color, rgb(15, 40, 84));
                font-weight: 600;
                border-radius: inherit;
              }

              .dz-wavy-loader .progress-container {
                position: relative;
                width: 80px;
                height: 80px;
                margin-bottom: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
              }

              .dz-wavy-loader .progress-ring {
                width: 80px;
                height: 80px;
              }

              .dz-wavy-loader .progress-ring circle,
              .dz-wavy-loader .progress-ring path {
                fill: none;
                stroke-width: 4;
                stroke-linecap: round;
              }

              .dz-wavy-loader .progress-ring .progress-ring-bg {
                stroke: rgba(0, 0, 0, 0.05);
              }

              .dz-wavy-loader .progress-ring .progress-ring-fill {
                stroke: var(--primary-color, rgb(15, 40, 84));
                transform: rotate(-90deg);
                transform-origin: 50% 50%;
                transition: stroke-dashoffset 0.1s linear;
              }

              .dz-wavy-loader .progress-text {
                position: absolute;
                font-size: 11px;
                font-weight: 800;
                color: var(--primary-color, rgb(15, 40, 84));
              }
            </style>
          @endsection

          @include('layouts.components.modal')

          @section('scripts')
            <script src="{{ asset('build/assets/libs/dropzone/dropzone-min.js') }}"></script>
            <script>
              window.showAlertModal = function(message, type = 'info', title = 'Informasi') {
                const modal = new bootstrap.Modal(document.getElementById('globalAlertModal'));
                const messageEl = document.getElementById('globalAlertModalMessage');
                const titleEl = document.getElementById('globalAlertModalLabel');
                const iconContainer = document.getElementById('globalAlertModalIcon');

                messageEl.textContent = message;
                titleEl.textContent = title;

                let iconHtml = '<i class="ri-information-line fs-24"></i>';
                let bgClass = 'bg-primary-transparent text-primary';

                if (type === 'success') {
                  iconHtml = '<i class="ri-checkbox-circle-line fs-24"></i>';
                  bgClass = 'bg-success-transparent text-success';
                } else if (type === 'error') {
                  iconHtml = '<i class="ri-error-warning-line fs-24"></i>';
                  bgClass = 'bg-danger-transparent text-danger';
                } else if (type === 'warning') {
                  iconHtml = '<i class="ri-alert-line fs-24"></i>';
                  bgClass = 'bg-warning-transparent text-warning';
                }

                iconContainer.innerHTML = iconHtml;
                iconContainer.className = 'flex-shrink-0 p-2 rounded-3 ' + bgClass;

                modal.show();
              };
              Dropzone.autoDiscover = false;

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

              document.addEventListener('DOMContentLoaded', function() {
                const dropzones = document.querySelectorAll('.dz-multi');

                dropzones.forEach(function(dzElement) {
                  const type = dzElement.getAttribute('data-type');
                  const dzId = dzElement.getAttribute('id');

                  new Dropzone(`#${dzId}`, {
                    url: "{{ route('verification.berkas') }}",
                    autoProcessQueue: true, // Auto upload when dropped
                    paramName: "berkas",
                    maxFilesize: 4, // MB
                    acceptedFiles: ".pdf",
                    maxFiles: 1,
                    addRemoveLinks: false,
                    previewsContainer: dzElement.querySelector('.custom-file-header'),
                    dictFileTooBig: `Ukuran file terlalu besar (${['{', '{', 'filesize', '}', '}'].join('')}MB). Maksimal ukuran file: ${['{', '{', 'maxFilesize', '}', '}'].join('')}MB.`,
                    headers: {
                      'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    init: function() {
                      this.on("addedfile", function(file) {
                        if (this.files.length > 1) {
                          this.removeFile(this.files[0]);
                        }

                        setTimeout(() => {
                          const dhidden = dzElement.querySelector(
                            '.dz-hidden-input'); // scope to this instance
                          const rmvObj = file.previewElement.querySelector('.dz-remove');
                          if (rmvObj && dhidden) {
                            rmvObj.addEventListener('click', function() {
                              dhidden.click();
                            });
                          }
                        }, 50);

                        // No PDF embed preview on multi-grid
                      });

                      this.on("sending", function(file, xhr, formData) {
                        formData.append("type", type); // Append specific document type to backend

                        const loader = Dropzone.createElement(
                          `<div class="dz-wavy-loader">
                            <div class="progress-container">
                              <svg class="progress-ring" viewBox="0 0 100 100">
                                <circle class="progress-ring-bg" cx="50" cy="50" r="35"></circle>
                                <path class="progress-ring-fill"></path>
                              </svg>
                              <span class="progress-text">0%</span>
                            </div>
                            <span style="font-size: 9px;">Mengunggah...</span>
                          </div>`
                        );

                        const header = dzElement.querySelector('.custom-file-header');
                        if (header) {
                          header.appendChild(loader);
                        } else {
                          const messageContainer = dzElement.querySelector('.dz-message');
                          if (messageContainer) {
                            messageContainer.appendChild(loader);
                          } else {
                            file.previewElement.appendChild(loader);
                          }
                        }
                        const garisFill = loader.querySelector('.progress-ring-fill');
                        const text = loader.querySelector('.progress-text');

                        let fase = 0;
                        let requestId;
                        const panjangTotal = 220; // 2 * PI * r (35)
                        garisFill.style.strokeDasharray = panjangTotal;
                        garisFill.style.strokeDashoffset = panjangTotal;

                        const animate = () => {
                          fase += 0.04;
                          const wavyPathData = buatJalurGelombang(50, 50, 35, 10, 2, fase);
                          garisFill.setAttribute('d', wavyPathData);
                          requestId = requestAnimationFrame(animate);
                        };

                        requestId = requestAnimationFrame(animate);

                        // Store variables to file object for update in uploadprogress
                        file.wavyRequestId = requestId;
                        file.wavyGarisFill = garisFill;
                        file.wavyText = text;
                        file.wavyLoader = loader;
                      });

                      this.on("uploadprogress", function(file, progress, bytesSent) {
                        if (file.wavyGarisFill) {
                          const offset = 220 - (progress / 100 * 220);
                          file.wavyGarisFill.style.strokeDashoffset = offset;
                          file.wavyText.innerText = Math.round(progress) + '%';
                        }
                      });

                      this.on("success", function(file, response) {
                        if (file.wavyRequestId) cancelAnimationFrame(file.wavyRequestId);
                        if (file.wavyLoader) file.wavyLoader.remove();

                        const header = dzElement.querySelector('.custom-file-header');
                        // No need to unhide children anymore

                        // Dynamically update UI without reloads or modals
                        const footerName = dzElement.querySelector('.custom-file-name');
                        if (footerName && response.path) {
                          const fixedPath = '/' + response.path.replace('public/', 'storage/');

                          // Extract original filename without timestamp
                          const fullFilename = response.path.split('/').pop();
                          const filenameParts = fullFilename.split('_');
                          let cleanFilename = fullFilename;
                          if (filenameParts.length > 1) {
                            filenameParts.shift();
                            cleanFilename = filenameParts.join('_');
                          }

                          footerName.innerText = cleanFilename;
                          footerName.setAttribute('title', cleanFilename);

                          // Update the eye view icon to be functional link
                          const trashIcon = dzElement.querySelector('.custom-file-trash');
                          if (trashIcon) {
                            if (trashIcon.tagName === 'DIV') {
                              // It was an empty placeholder, swap to link
                              const a = document.createElement('a');
                              a.href = fixedPath;
                              a.target = "_blank";
                              a.className = "custom-file-trash";
                              a.style.display = "flex";
                              a.style.alignItems = "center";
                              a.style.textDecoration = "none";
                              a.style.cursor = "pointer";
                              a.title = "Lihat Berkas";
                              a.innerHTML = trashIcon.innerHTML; // copy the icon
                              const iconEl = a.querySelector('i');
                              if (iconEl) iconEl.style.opacity = "1";
                              trashIcon.parentNode.replaceChild(a, trashIcon);
                            } else {
                              // Already a link, just update href
                              trashIcon.href = fixedPath;
                              trashIcon.style.cursor = "pointer";
                            }
                          }

                          // Update the delete icon to be active
                          const deleteIcon = dzElement.querySelector('.custom-file-delete');
                          if (deleteIcon) {
                            deleteIcon.className =
                              "ri-delete-bin-line text-danger custom-file-delete d-flex justify-content-center align-items-center";
                            deleteIcon.style.cssText =
                              "font-size: 14px; cursor: pointer; height:24px; width: 24px; background-color:rgba(70, 66, 66, 0.103); border-radius:50%; opacity: 1;";
                            deleteIcon.title = "Hapus Berkas";
                            deleteIcon.setAttribute('onclick',
                              `event.stopPropagation(); deleteBerkas('${type}', this)`);
                          }
                        }

                        const dzText = dzElement.querySelector('.dz-indicator-text');
                        if (dzText) dzText.innerText = 'Ganti File';

                        // Remove the injected Dropzone thumbnail so we don't break custom UI
                        this.removeFile(file);
                      });

                      this.on("error", function(file, response) {
                        if (file.wavyRequestId) cancelAnimationFrame(file.wavyRequestId);
                        if (file.wavyLoader) file.wavyLoader.remove();

                        const header = dzElement.querySelector('.custom-file-header');
                        // No need to unhide children anymore

                        var msg = response.message || response || "Gagal mengunggah berkas";
                        if (typeof msg === 'object' && msg.errors && msg.errors.berkas) {
                          msg = msg.errors.berkas[0];
                        } else if (typeof msg === 'object' && msg.errors && msg.errors.type) {
                          msg = msg.errors.type[0];
                        }

                        window.showAlertModal(msg, 'error', 'Gagal');
                        this.removeFile(file);
                      });
                    }
                  });
                });
              });
            </script>
            <script>
              window.deleteBerkas = function(type, element) {
                const message = 'Apakah Anda yakin ingin menghapus berkas ini? Tindakan ini tidak dapat dibatalkan.';

                window.showConfirmModal(message, function() {
                  executeDelete(type, element);
                }, 'Hapus Berkas?', 'Ya, Hapus', 'btn-danger');
              }

              function executeDelete(type, element) {
                const footer = element.closest('.custom-file-footer');
                const container = element.closest('.custom-file-container');

                fetch(`/verification/berkas/${type}`, {
                    method: 'DELETE',
                    headers: {
                      'X-CSRF-TOKEN': '{{ csrf_token() }}',
                      'Content-Type': 'application/json',
                      'Accept': 'application/json'
                    }
                  })
                  .then(async response => {
                    const isJson = response.headers.get('content-type')?.includes('application/json');
                    const data = isJson ? await response.json() : null;

                    if (!response.ok) {
                      const errorMsg = data?.message || `Error ${response.status}: ${response.statusText}`;
                      throw new Error(errorMsg);
                    }
                    return data;
                  })
                  .then(data => {
                    if (data.success) {
                      // 1. Update Trash Icon (current element)
                      element.className =
                        "ri-delete-bin-line text-secondary custom-file-delete d-flex justify-content-center align-items-center";
                      element.style.opacity = "0.3";
                      element.style.cursor = "default";
                      element.title = "Belum ada file";
                      element.removeAttribute('onclick');

                      // 2. Update File Name text
                      const fileNameEl = footer.querySelector('.custom-file-name');
                      if (fileNameEl) {
                        fileNameEl.innerText = 'Belum ada file';
                        fileNameEl.title = 'Belum ada file';
                      }

                      // 3. Update View Icon (Lihat Berkas)
                      const viewLink = footer.querySelector('.custom-file-trash');
                      if (viewLink) {
                        if (viewLink.tagName === 'A') {
                          // Convert to div or just disable link behavior
                          viewLink.removeAttribute('href');
                          viewLink.removeAttribute('target');
                          viewLink.style.cursor = 'default';
                          viewLink.title = 'Belum ada file';
                          const viewIcon = viewLink.querySelector('i');
                          if (viewIcon) {
                            viewIcon.style.opacity = "0.3";
                            viewIcon.style.cursor = "default";
                          }
                        }
                      }

                      // 4. Reset Dropzone Indicator Text
                      if (container) {
                        const dzText = container.querySelector('.dz-indicator-text');
                        if (dzText) {
                          dzText.innerText = 'Silakan Unggah File!';
                        }
                      }

                      // 5. Show Success Toast/Modal
                      if (typeof window.showAlertModal === 'function') {
                        window.showAlertModal('Berkas berhasil dihapus.', 'success', 'Berhasil');
                      }
                    } else {
                      if (typeof window.showAlertModal === 'function') {
                        window.showAlertModal(data.message || 'Gagal menghapus berkas', 'error', 'Gagal');
                      } else {
                        alert(data.message || 'Gagal menghapus berkas');
                      }
                    }
                  })
                  .catch(error => {
                    console.error('Delete error:', error);
                    if (typeof window.showAlertModal === 'function') {
                      window.showAlertModal('Terjadi kesalahan: ' + error.message, 'error', 'Error');
                    } else {
                      alert('Terjadi kesalahan: ' + error.message);
                    }
                  });
              }
            </script>
          @endsection

          @section('scripts')
          @endsection

          <p class="mb-4">
            @if (isset($isSubmitted) && $isSubmitted)
              Terima kasih telah mengunggah berkas. Silakan tunggu sampai administrator memvalidasi pendaftaran
              Silakan tunggu sampai administrator memvalidasi pendaftaran
              perusahaan Anda. Anda akan dapat mengakses dashboard setelah akun Anda aktif.
            @elseif(!$perusahaan || !isset($isSubmitted) || !$isSubmitted)
              Silakan tunggu sampai administrator memvalidasi pendaftaran Anda. Anda akan dapat mengakses dashboard
              setelah akun Anda aktif.
            @endif
          </p>

          <div class="text-center mt-4">
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="btn btn-link text-danger">Logout</button>
            </form>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
