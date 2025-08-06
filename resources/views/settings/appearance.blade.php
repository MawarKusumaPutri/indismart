@extends('layouts.app')

@section('title', 'Pengaturan Tampilan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Pengaturan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tampilan</li>
                </ol>
            </nav>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Pengaturan Tampilan</h1>
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
                        
                        <a href="{{ route('settings.notifications') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="bi bi-bell me-3"></i>
                            <div>
                                <h6 class="mb-0">Notifikasi</h6>
                                <small class="text-muted">Pengaturan notifikasi email dan sistem</small>
                            </div>
                        </a>
                        
                        <a href="{{ route('settings.appearance') }}" class="list-group-item list-group-item-action d-flex align-items-center active">
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
            <form action="{{ route('settings.appearance.update') }}" method="POST">
                @csrf
                
                <!-- Theme Settings -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-palette text-primary me-2"></i>
                            Pengaturan Tema
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="theme" class="form-label">Pilih Tema</label>
                                <select class="form-select @error('theme') is-invalid @enderror" id="theme" name="theme">
                                    <option value="light" {{ (Auth::user()->theme ?? 'light') == 'light' ? 'selected' : '' }}>Light Mode</option>
                                    <option value="dark" {{ (Auth::user()->theme ?? 'light') == 'dark' ? 'selected' : '' }}>Dark Mode</option>
                                    <option value="auto" {{ (Auth::user()->theme ?? 'light') == 'auto' ? 'selected' : '' }}>Auto (Sesuai Sistem)</option>
                                </select>
                                @error('theme')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Tema akan mempengaruhi tampilan keseluruhan aplikasi</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Preview Tema</label>
                                <div class="theme-preview">
                                    <div class="theme-option mb-2" data-theme="light">
                                        <div class="theme-card bg-white border">
                                            <div class="theme-header bg-light p-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="theme-logo bg-primary rounded me-2" style="width: 20px; height: 20px;"></div>
                                                    <span class="small">Light Mode</span>
                                                </div>
                                            </div>
                                            <div class="theme-content p-2">
                                                <div class="bg-white border rounded p-1 mb-1"></div>
                                                <div class="bg-light rounded p-1"></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="theme-option mb-2" data-theme="dark">
                                        <div class="theme-card bg-dark text-white border">
                                            <div class="theme-header bg-secondary p-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="theme-logo bg-light rounded me-2" style="width: 20px; height: 20px;"></div>
                                                    <span class="small">Dark Mode</span>
                                                </div>
                                            </div>
                                            <div class="theme-content p-2">
                                                <div class="bg-secondary border rounded p-1 mb-1"></div>
                                                <div class="bg-dark rounded p-1"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Language Settings -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-translate text-success me-2"></i>
                            Pengaturan Bahasa
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="language" class="form-label">Pilih Bahasa</label>
                                <select class="form-select @error('language') is-invalid @enderror" id="language" name="language">
                                    <option value="id" {{ (Auth::user()->language ?? 'id') == 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                                    <option value="en" {{ (Auth::user()->language ?? 'id') == 'en' ? 'selected' : '' }}>English</option>
                                </select>
                                @error('language')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Bahasa akan diterapkan pada seluruh interface aplikasi</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Preview Bahasa</label>
                                <div class="language-preview">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h6 class="card-title">Dashboard</h6>
                                            <p class="card-text small" id="language-preview-text">
                                                Selamat datang di dashboard Indismart. Kelola dokumen dan proyek Anda dengan mudah.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Layout Settings -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-layout-sidebar text-warning me-2"></i>
                            Pengaturan Layout
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="mb-1">Sidebar Collapsed</h6>
                                        <small class="text-muted">Sembunyikan sidebar secara otomatis</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="sidebar_collapsed" name="sidebar_collapsed" 
                                               {{ (Auth::user()->sidebar_collapsed ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sidebar_collapsed"></label>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="mb-1">Compact Mode</h6>
                                        <small class="text-muted">Tampilkan lebih banyak konten dalam satu halaman</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="compact_mode" name="compact_mode" 
                                               {{ (Auth::user()->compact_mode ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="compact_mode"></label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Preview Layout</label>
                                <div class="layout-preview">
                                    <div class="card border">
                                        <div class="card-body p-2">
                                            <div class="d-flex">
                                                <div class="sidebar-preview bg-light me-2" style="width: 60px; height: 100px;"></div>
                                                <div class="content-preview flex-grow-1">
                                                    <div class="bg-white border rounded p-1 mb-1"></div>
                                                    <div class="bg-light rounded p-1"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Color Settings -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-palette2 text-info me-2"></i>
                            Pengaturan Warna
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Warna Utama</label>
                                <div class="color-options">
                                    <div class="color-option active" data-color="#E22626" style="background-color: #E22626;"></div>
                                    <div class="color-option" data-color="#007bff" style="background-color: #007bff;"></div>
                                    <div class="color-option" data-color="#28a745" style="background-color: #28a745;"></div>
                                    <div class="color-option" data-color="#ffc107" style="background-color: #ffc107;"></div>
                                    <div class="color-option" data-color="#6f42c1" style="background-color: #6f42c1;"></div>
                                </div>
                                <div class="form-text">Warna utama akan diterapkan pada tombol, link, dan elemen penting lainnya</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Preview Warna</label>
                                <div class="color-preview">
                                    <div class="card border">
                                        <div class="card-body">
                                            <button class="btn btn-primary me-2">Primary Button</button>
                                            <button class="btn btn-outline-primary">Outline Button</button>
                                            <div class="mt-2">
                                                <a href="#" class="text-primary">Primary Link</a>
                                            </div>
                                        </div>
                                    </div>
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

.theme-card {
    width: 100%;
    height: 80px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.theme-card:hover {
    transform: scale(1.02);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.theme-option.active .theme-card {
    border-color: #E22626 !important;
    box-shadow: 0 0 0 2px rgba(226, 38, 38, 0.25);
}

.color-option {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 10px;
    cursor: pointer;
    border: 3px solid transparent;
    transition: all 0.3s ease;
}

.color-option:hover {
    transform: scale(1.1);
}

.color-option.active {
    border-color: #333;
    box-shadow: 0 0 0 2px rgba(226, 38, 38, 0.25);
}
</style>

<script>
// Theme preview functionality
document.getElementById('theme').addEventListener('change', function() {
    const selectedTheme = this.value;
    document.querySelectorAll('.theme-option').forEach(option => {
        option.classList.remove('active');
    });
    document.querySelector(`[data-theme="${selectedTheme}"]`).classList.add('active');
});

// Language preview functionality
document.getElementById('language').addEventListener('change', function() {
    const selectedLanguage = this.value;
    const previewText = document.getElementById('language-preview-text');
    
    if (selectedLanguage === 'en') {
        previewText.textContent = 'Welcome to Indismart dashboard. Manage your documents and projects easily.';
    } else {
        previewText.textContent = 'Selamat datang di dashboard Indismart. Kelola dokumen dan proyek Anda dengan mudah.';
    }
});

// Color option functionality
document.querySelectorAll('.color-option').forEach(option => {
    option.addEventListener('click', function() {
        document.querySelectorAll('.color-option').forEach(opt => opt.classList.remove('active'));
        this.classList.add('active');
        
        const color = this.dataset.color;
        document.documentElement.style.setProperty('--primary-color', color);
    });
});

// Initialize active states
document.addEventListener('DOMContentLoaded', function() {
    const currentTheme = document.getElementById('theme').value;
    document.querySelector(`[data-theme="${currentTheme}"]`).classList.add('active');
    
    const currentColor = document.querySelector('.color-option').dataset.color;
    document.documentElement.style.setProperty('--primary-color', currentColor);
});

function resetToDefaults() {
    if (confirm('Apakah Anda yakin ingin mengembalikan pengaturan tampilan ke default?')) {
        document.getElementById('theme').value = 'light';
        document.getElementById('language').value = 'id';
        document.getElementById('sidebar_collapsed').checked = false;
        document.getElementById('compact_mode').checked = false;
        
        // Reset theme preview
        document.querySelectorAll('.theme-option').forEach(option => {
            option.classList.remove('active');
        });
        document.querySelector('[data-theme="light"]').classList.add('active');
        
        // Reset language preview
        document.getElementById('language-preview-text').textContent = 'Selamat datang di dashboard Indismart. Kelola dokumen dan proyek Anda dengan mudah.';
        
        // Reset color
        document.querySelectorAll('.color-option').forEach(opt => opt.classList.remove('active'));
        document.querySelector('.color-option').classList.add('active');
        document.documentElement.style.setProperty('--primary-color', '#E22626');
    }
}
</script>
@endsection 