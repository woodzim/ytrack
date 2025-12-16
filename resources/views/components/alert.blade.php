<style>
    .alert-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
    }

    .custom-alert {
        padding: 16px 20px;
        border-radius: 8px;
        margin-bottom: 12px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }

    .custom-alert.closing {
        animation: slideOut 0.3s ease-out forwards;
    }

    .alert-success {
        background-color: #d1fae5;
        border: 1px solid #6ee7b7;
        color: #065f46;
    }

    .alert-error {
        background-color: #fee2e2;
        border: 1px solid #fca5a5;
        color: #7f1d1d;
    }

    .alert-warning {
        background-color: #fef3c7;
        border: 1px solid #fde68a;
        color: #78350f;
    }

    .alert-info {
        background-color: #dbeafe;
        border: 1px solid #93c5fd;
        color: #0c2340;
    }

    .alert-icon {
        font-size: 18px;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .alert-content {
        flex: 1;
    }

    .alert-title {
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 4px;
    }

    .alert-message {
        font-size: 13px;
        line-height: 1.5;
    }

    .alert-close {
        flex-shrink: 0;
        background: none;
        border: none;
        color: inherit;
        font-size: 18px;
        cursor: pointer;
        padding: 0;
        margin-left: 8px;
        opacity: 0.7;
        transition: opacity 0.2s;
    }

    .alert-close:hover {
        opacity: 1;
    }

    /* Mobile Responsive */
    @media (max-width: 576px) {
        .alert-container {
            left: 12px;
            right: 12px;
            max-width: none;
        }

        .custom-alert {
            padding: 14px 16px;
        }

        .alert-icon {
            font-size: 16px;
        }

        .alert-title {
            font-size: 13px;
        }

        .alert-message {
            font-size: 12px;
        }
    }
</style>

<div class="alert-container">
    <!-- Success Messages -->
    @if (session('success'))
        <div class="custom-alert alert-success" role="alert">
            <div class="alert-icon">
                <i class="ri-checkbox-circle-fill"></i>
            </div>
            <div class="alert-content">
                <div class="alert-title">Berhasil!</div>
                <div class="alert-message">{{ session('success') }}</div>
            </div>
            <button type="button" class="alert-close" aria-label="Close">
                <i class="ri-close-line"></i>
            </button>
        </div>
    @endif

    <!-- Error Messages -->
    @if (session('error'))
        <div class="custom-alert alert-error" role="alert">
            <div class="alert-icon">
                <i class="ri-error-warning-fill"></i>
            </div>
            <div class="alert-content">
                <div class="alert-title">Gagal!</div>
                <div class="alert-message">{{ session('error') }}</div>
            </div>
            <button type="button" class="alert-close" aria-label="Close">
                <i class="ri-close-line"></i>
            </button>
        </div>
    @endif

    <!-- Warning Messages -->
    @if (session('warning'))
        <div class="custom-alert alert-warning" role="alert">
            <div class="alert-icon">
                <i class="ri-alert-fill"></i>
            </div>
            <div class="alert-content">
                <div class="alert-title">Peringatan!</div>
                <div class="alert-message">{{ session('warning') }}</div>
            </div>
            <button type="button" class="alert-close" aria-label="Close">
                <i class="ri-close-line"></i>
            </button>
        </div>
    @endif

    <!-- Info Messages -->
    @if (session('info'))
        <div class="custom-alert alert-info" role="alert">
            <div class="alert-icon">
                <i class="ri-information-fill"></i>
            </div>
            <div class="alert-content">
                <div class="alert-title">Informasi</div>
                <div class="alert-message">{{ session('info') }}</div>
            </div>
            <button type="button" class="alert-close" aria-label="Close">
                <i class="ri-close-line"></i>
            </button>
        </div>
    @endif

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="custom-alert alert-error" role="alert">
            <div class="alert-icon">
                <i class="ri-error-warning-fill"></i>
            </div>
            <div class="alert-content">
                <div class="alert-title">Ada kesalahan pada form!</div>
                <div class="alert-message">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="alert-close" aria-label="Close">
                <i class="ri-close-line"></i>
            </button>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.custom-alert');
        
        alerts.forEach(alert => {
            // Auto-close after 5 seconds
            const timeout = setTimeout(() => {
                closeAlert(alert);
            }, 5000);

            // Close button handler
            const closeBtn = alert.querySelector('.alert-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    clearTimeout(timeout);
                    closeAlert(alert);
                });
            }
        });

        function closeAlert(alert) {
            alert.classList.add('closing');
            setTimeout(() => {
                alert.remove();
            }, 300);
        }
    });
</script>
