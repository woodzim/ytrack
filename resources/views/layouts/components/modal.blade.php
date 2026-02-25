            <div class="modal fade" id="header-responsive-search" tabindex="-1" aria-labelledby="header-responsive-search" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="input-group">
                                <input type="text" class="form-control border-end-0" placeholder="Search Anything ..."
                                    aria-label="Search Anything ..." aria-describedby="button-addon2">
                                <button class="btn btn-primary" type="button"
                                    id="button-addon2"><i class="bi bi-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Global Confirmation Modal -->
            <div class="modal fade" id="globalConfirmModal" tabindex="-1" aria-labelledby="globalConfirmModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content shadow-lg border-0">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-semibold" id="globalConfirmModalLabel">Konfirmasi Tindakan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body pt-3">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <div class="bg-warning-transparent p-2 rounded-3 text-warning">
                                        <i class="ri-error-warning-line fs-24"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-0 text-muted fs-14" id="globalConfirmModalMessage">Apakah Anda yakin ingin melanjutkan tindakan ini?</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light btn-wave" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-danger btn-wave shadow-sm" id="globalConfirmModalBtn">Ya, Lanjutkan</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Global Alert Modal -->
            <div class="modal fade" id="globalAlertModal" tabindex="-1" aria-labelledby="globalAlertModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content shadow-lg border-0">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-semibold" id="globalAlertModalLabel">Informasi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body pt-3">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary-transparent p-2 rounded-3 text-primary" id="globalAlertModalIcon">
                                        <i class="ri-information-line fs-24"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-0 text-muted fs-14" id="globalAlertModalMessage">Informasi sistem.</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-primary btn-wave shadow-sm" data-bs-dismiss="modal">Oke, Dimengerti</button>
                        </div>
                    </div>
                </div>
            </div>
