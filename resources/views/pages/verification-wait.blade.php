@extends('layouts.custom-master')

@php
  $bodyClass = 'authentication-background';
@endphp

@section('content')
  <div class="authentication-basic-background">
    <img src="{{ asset('build/assets/images/media/backgrounds/9.png') }}" alt="">
  </div>

  <div class="container">
    <div class="row justify-content-center align-items-center authentication authentication-basic min-vh-100 py-4">
      <div class="col-xxl-5 col-xl-6 col-lg-8 col-md-8 col-sm-10 col-12">
        <div class="card custom-card border-0 my-4">
          <div class="card-body p-5">
            <div class="mb-4">
              <a href="{{ route('login') }}">
                <img src="{{ asset('images/long-logo.webp') }}" alt="logo" class="desktop-dark mt-3 mt-md-0">
              </a>
            </div>
            <div>
              <h4 class="mb-1 fw-semibold">
                <span>Menunggu Verifikasi Admin</span>
              </h4>
              <p class="mb-4 text-muted fw-normal">Email Anda sudah terverifikasi, namun akun Anda masih menunggu validasi
                dari administrator.</p>
            </div>

            @include('components.alert')

            @php
              $user = auth()->user();
              $perusahaan = $user->hasRole('perusahaan') ? \App\Models\Perusahaan::find($user->id_perusahaan) : null;
            @endphp

            @if ($perusahaan && !$perusahaan->berkas)
              <div class="alert alert-primary mb-4">
                <strong>Perhatian:</strong> Anda harus mengunggah seluruh berkas perusahaan di bawah ini yang digabung
                dalam satu file (.pdf) sebelum
                admin dapat memvalidasi akun Anda.

                <div class="accordion mt-3 custom-accordion" id="berkasAccordion">
                  <div class="accordion-item border-primary border-opacity-50 mt-2 bg-primary bg-opacity-10">
                    <h2 class="accordion-header" id="headingOne">
                      <button class="accordion-button collapsed p-3 bg-primary text-white fw-semibold" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false"
                        aria-controls="collapseOne" style="border-radius: 5px;">
                        <i class="ri-information-line me-2 fs-16"></i> Lihat Daftar Ketentuan Berkas
                        <i class="ri-arrow-down-s-line ms-auto fs-16 accordion-indicator"></i>
                      </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                      data-bs-parent="#berkasAccordion">
                      <div class="accordion-body p-3">
                        <strong>Wajib:</strong>
                        <ul class="mb-2 ms-3">
                          <li>Akta Pendirian & SK Kemenkumham</li>
                          <li>NPWP Badan Usaha</li>
                          <li>NIB (OSS)</li>
                          <li>Surat Keterangan Domisili (jika masih berlaku di daerah)</li>
                          <li>KTP Penanggung Jawab</li>
                          <li>NPWP Penanggung Jawab</li>
                        </ul>
                        <strong>Opsional (jika ada):</strong>
                        <ul class="mb-0 ms-3">
                          <li>Izin Usaha / Izin Operasional</li>
                          <li>SIUP / TDP (jika masih digunakan)</li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <form action="{{ route('verification.berkas') }}" method="POST" enctype="multipart/form-data"
                class="mb-4" id="berkas-form">
                @csrf
                <div class="mb-3">
                  <label class="form-label">Unggah Berkas Perusahaan (wajib format .pdf, maks 4MB)</label>
                  <div class="dropzone" id="berkas-dropzone"
                    style="border: 2px dashed #0ba360; border-radius: 5px; background: #f8f9fa; padding: 20px; text-align: center; cursor: pointer;">
                    <div class="dz-message" data-dz-message>
                      <span>Drop file .pdf di sini atau klik untuk memilih file</span>
                    </div>
                  </div>
                  @error('berkas')
                    <div class="text-danger mt-1 small">{{ $message }}</div>
                  @enderror
                </div>
                <div class="d-grid gap-2">
                  <button type="button" id="submit-berkas" class="btn btn-primary">Unggah Berkas</button>
                </div>
              </form>
            @endif

          @section('styles')
            <!-- Filepond CSS -->
            <link rel="stylesheet" href="{{ asset('build/assets/libs/dropzone/dropzone.css') }}">
            <style>
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

              .dz-wavy-loader {
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

          @section('scripts')
            <script src="{{ asset('build/assets/libs/dropzone/dropzone-min.js') }}"></script>
            <script>
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
                if (document.getElementById('berkas-dropzone')) {
                  let myDropzone = new Dropzone("#berkas-dropzone", {
                    url: "{{ route('verification.berkas') }}",
                    autoProcessQueue: false,
                    paramName: "berkas",
                    maxFilesize: 4, // MB
                    acceptedFiles: ".pdf",
                    maxFiles: 1,
                    addRemoveLinks: true,
                    dictRemoveFile: "Ganti File",
                    headers: {
                      'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    init: function() {
                      var submitButton = document.querySelector("#submit-berkas");
                      var dz = this;

                      submitButton.addEventListener("click", function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        if (dz.getQueuedFiles().length > 0) {
                          dz.processQueue();
                        } else {
                          alert("Silakan pilih file berkas terlebih dahulu.");
                        }
                      });

                      this.on("addedfile", function(file) {
                        if (this.files.length > 1) {
                          this.removeFile(this.files[0]);
                        }
                      });

                      this.on("sending", function(file, xhr, formData) {
                        const loader = Dropzone.createElement(
                          `<div class="dz-wavy-loader">
                            <div class="progress-container">
                              <svg class="progress-ring" viewBox="0 0 100 100">
                                <circle class="progress-ring-bg" cx="50" cy="50" r="35"></circle>
                                <path class="progress-ring-fill"></path>
                              </svg>
                              <span class="progress-text">0%</span>
                            </div>
                            <span>Mengunggah...</span>
                          </div>`
                        );

                        // Append the loader to the preview element
                        const dzDetails = file.previewElement.querySelector('.dz-details');
                        if (dzDetails) dzDetails.appendChild(loader);
                        else file.previewElement.appendChild(loader);

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
                        window.location.reload();
                      });

                      this.on("error", function(file, response) {
                        if (file.wavyRequestId) cancelAnimationFrame(file.wavyRequestId);
                        if (file.wavyLoader) file.wavyLoader.remove();

                        var msg = response.message || response || "Gagal mengunggah berkas";
                        if (typeof msg === 'object' && msg.errors && msg.errors.berkas) {
                          msg = msg.errors.berkas[0];
                        }
                        alert(msg);
                        dz.removeFile(file);
                      });
                    }
                  });
                }
              });
            </script>
          @endsection

          <p class="mb-4">
            @if ($perusahaan && $perusahaan->berkas)
              Terima kasih telah mengunggah berkas. Silakan tunggu sampai administrator memvalidasi pendaftaran
              perusahaan Anda. Anda akan dapat mengakses dashboard setelah akun Anda aktif.
            @elseif(!$perusahaan)
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
