@extends('layouts.app')

@section('title', 'Pengaturan Notifikasi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Pengaturan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Notifikasi</li>
                </ol>
            </nav>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Pengaturan Notifikasi</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar Pengaturan -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('settings.profile') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="bi bi-person-circle me-3"></i>
                            <div>
                                <h6 class="mb-0">Profil</h6>
                                <small class="text-muted">Informasi pribadi dan avatar</small>
                            </div>
                        </a>
                        
                        <a href="{{ route('settings.security') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="bi bi-shield-lock me-3"></i>
                            <div>
                                <h6 class="mb-0">Keamanan</h6>
                                <small class="text-muted">Password dan keamanan akun</small>
                            </div>
                        </a>
                        
                        <a href="{{ route('settings.notifications') }}" class="list-group-item list-group-item-action d-flex align-items-center active">
                            <i class="bi bi-bell me-3"></i>
                            <div>
                                <h6 class="mb-0">Notifikasi</h6>
                                <small class="text-muted">Pengaturan notifikasi email dan sistem</small>
                            </div>
                        </a>
                        
                        <a href="{{ route('settings.appearance') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="bi bi-palette me-3"></i>
                            <div>
                                <h6 class="mb-0">Tampilan</h6>
                                <small class="text-muted">Tema, bahasa, dan preferensi tampilan</small>
                            </div>
                        </a>
                        
                        <a href="{{ route('settings.system') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="bi bi-gear me-3"></i>
                            <div>
                                <h6 class="mb-0">Sistem</h6>
                                <small class="text-muted">Cache, export data, dan pengaturan sistem</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Konten Utama -->
        <div class="col-md-9">
            <form action="{{ route('settings.notifications.update') }}" method="POST">
                @csrf
                
                <!-- Email Notifications -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-envelope text-primary me-2"></i>
                            Notifikasi Email
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="mb-1">Notifikasi Email</h6>
                                        <small class="text-muted">Terima notifikasi melalui email</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" 
                                               {{ ($user->email_notifications ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="email_notifications"></label>
                                    </div>
                                </div>
                                
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <small>Notifikasi email akan dikirim ke: <strong>{{ $user->email }}</strong></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Document Notifications -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-file-earmark-text text-success me-2"></i>
                            Notifikasi Dokumen
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="mb-1">Upload Dokumen</h6>
                                        <small class="text-muted">Notifikasi ketika ada dokumen baru diupload</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="document_upload_notifications" name="document_upload_notifications" 
                                               {{ ($user->document_upload_notifications ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="document_upload_notifications"></label>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="mb-1">Review Dokumen</h6>
                                        <small class="text-muted">Notifikasi ketika dokumen direview atau diperbarui</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="review_notifications" name="review_notifications" 
                                               {{ ($user->review_notifications ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="review_notifications"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Notifications -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-gear text-warning me-2"></i>
                            Notifikasi Sistem
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="mb-1">Notifikasi Sistem</h6>
                                        <small class="text-muted">Notifikasi penting dari sistem dan admin</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="system_notifications" name="system_notifications" 
                                               {{ ($user->system_notifications ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="system_notifications"></label>
                                    </div>
                                </div>
                                
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <small>Notifikasi sistem berisi informasi penting seperti maintenance, update, dan pengumuman resmi.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notification Preview -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-eye text-info me-2"></i>
                            Preview Notifikasi
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Notifikasi Email</h6>
                                <div class="border rounded p-3 bg-light">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-envelope text-primary me-2"></i>
                                        <strong>Dokumen Baru Diupload</strong>
                                    </div>
                                    <p class="small mb-1">Mitra telah mengupload dokumen baru: "Laporan Survey Site A"</p>
                                    <small class="text-muted">2 menit yang lalu</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h6>Notifikasi Sistem</h6>
                                <div class="border rounded p-3 bg-light">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-gear text-warning me-2"></i>
                                        <strong>Maintenance Scheduled</strong>
                                    </div>
                                    <p class="small mb-1">Sistem akan offline untuk maintenance pada 15 Agustus 2025, 02:00-04:00 WIB</p>
                                    <small class="text-muted">1 jam yang lalu</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <button type="button" class="btn btn-outline-secondary" onclick="resetToDefaults()">
                                    <i class="bi bi-arrow-clockwise"></i> Reset ke Default
                                </button>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Simpan Pengaturan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.list-group-item.active {
    background-color: #E22626;
    border-color: #E22626;
}

.list-group-item:hover {
    background-color: #f8f9fa;
}

.form-check-input:checked {
    background-color: #E22626;
    border-color: #E22626;
}

.form-check-input:focus {
    border-color: #E22626;
    box-shadow: 0 0 0 0.25rem rgba(226, 38, 38, 0.25);
}
</style>

<script>
function resetToDefaults() {
    if (confirm('Apakah Anda yakin ingin mengembalikan pengaturan notifikasi ke default?')) {
        document.getElementById('email_notifications').checked = true;
        document.getElementById('document_upload_notifications').checked = true;
        document.getElementById('review_notifications').checked = true;
        document.getElementById('system_notifications').checked = true;
    }
}

// Auto-save functionality
let autoSaveTimeout;
const form = document.querySelector('form');

function autoSave() {
    clearTimeout(autoSaveTimeout);
    autoSaveTimeout = setTimeout(() => {
        const formData = new FormData(form);
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Network response was not ok');
        })
        .then(data => {
            if (data.success) {
                showToast('Pengaturan berhasil disimpan otomatis', 'success');
            }
        })
        .catch(error => {
            console.error('Auto-save error:', error);
        });
    }, 2000); // Auto-save after 2 seconds of inactivity
}

// Add event listeners for auto-save
document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
    checkbox.addEventListener('change', autoSave);
});

function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0 position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Show toast
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Remove toast after it's hidden
    toast.addEventListener('hidden.bs.toast', () => {
        document.body.removeChild(toast);
    });
}
</script>
@endsection 