@extends('layouts.app')

@section('title', 'Pengaturan Keamanan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Pengaturan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Keamanan</li>
                </ol>
            </nav>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Pengaturan Keamanan</h1>
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
                        
                        <a href="{{ route('settings.security') }}" class="list-group-item list-group-item-action d-flex align-items-center active">
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
            <!-- Informasi Keamanan -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-shield-check text-success me-2"></i>
                        Status Keamanan Akun
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span>Email terverifikasi</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span>Password kuat</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-clock text-warning me-2"></i>
                                <span>Login terakhir: {{ Auth::user()->last_login_at ? Auth::user()->last_login_at->diffForHumans() : 'Belum pernah login' }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-calendar text-info me-2"></i>
                                <span>Bergabung: {{ Auth::user()->created_at->format('d F Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ubah Password -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-key me-2"></i>
                        Ubah Password
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.security.update') }}" method="POST" id="passwordForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="current_password" class="form-label">Password Saat Ini <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                           id="current_password" name="current_password" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                        <i class="bi bi-eye" id="current_password_icon"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="new_password" class="form-label">Password Baru <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                           id="new_password" name="new_password" required minlength="8">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password')">
                                        <i class="bi bi-eye" id="new_password_icon"></i>
                                    </button>
                                </div>
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <div id="password-strength" class="mt-2">
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar" id="strength-bar" role="progressbar" style="width: 0%"></div>
                                        </div>
                                        <small id="strength-text" class="text-muted">Kekuatan password</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" 
                                           id="new_password_confirmation" name="new_password_confirmation" required minlength="8">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password_confirmation')">
                                        <i class="bi bi-eye" id="new_password_confirmation_icon"></i>
                                    </button>
                                </div>
                                @error('new_password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <span id="password-match" class="text-muted">Konfirmasi password Anda</span>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="bi bi-info-circle me-2"></i>
                                Tips Password yang Aman
                            </h6>
                            <ul class="mb-0 small">
                                <li>Minimal 8 karakter</li>
                                <li>Kombinasi huruf besar dan kecil</li>
                                <li>Angka dan karakter khusus</li>
                                <li>Hindari informasi pribadi</li>
                                <li>Jangan gunakan password yang sama di tempat lain</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                <i class="bi bi-check-circle"></i> Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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

.progress-bar {
    transition: width 0.3s ease;
}
</style>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '_icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

function checkPasswordStrength(password) {
    let strength = 0;
    let feedback = [];
    
    if (password.length >= 8) strength += 25;
    if (/[a-z]/.test(password)) strength += 25;
    if (/[A-Z]/.test(password)) strength += 25;
    if (/[0-9]/.test(password)) strength += 25;
    
    const bar = document.getElementById('strength-bar');
    const text = document.getElementById('strength-text');
    
    bar.style.width = strength + '%';
    
    if (strength <= 25) {
        bar.className = 'progress-bar bg-danger';
        text.textContent = 'Sangat Lemah';
        text.className = 'text-danger';
    } else if (strength <= 50) {
        bar.className = 'progress-bar bg-warning';
        text.textContent = 'Lemah';
        text.className = 'text-warning';
    } else if (strength <= 75) {
        bar.className = 'progress-bar bg-info';
        text.textContent = 'Sedang';
        text.className = 'text-info';
    } else {
        bar.className = 'progress-bar bg-success';
        text.textContent = 'Kuat';
        text.className = 'text-success';
    }
    
    return strength;
}

function checkPasswordMatch() {
    const password = document.getElementById('new_password').value;
    const confirmation = document.getElementById('new_password_confirmation').value;
    const matchSpan = document.getElementById('password-match');
    const submitBtn = document.getElementById('submitBtn');
    
    if (confirmation === '') {
        matchSpan.textContent = 'Konfirmasi password Anda';
        matchSpan.className = 'text-muted';
        submitBtn.disabled = true;
    } else if (password === confirmation) {
        matchSpan.textContent = 'Password cocok';
        matchSpan.className = 'text-success';
        submitBtn.disabled = false;
    } else {
        matchSpan.textContent = 'Password tidak cocok';
        matchSpan.className = 'text-danger';
        submitBtn.disabled = true;
    }
}

// Event listeners
document.getElementById('new_password').addEventListener('input', function() {
    checkPasswordStrength(this.value);
    checkPasswordMatch();
});

document.getElementById('new_password_confirmation').addEventListener('input', checkPasswordMatch);

// Form validation
document.getElementById('passwordForm').addEventListener('submit', function(e) {
    const password = document.getElementById('new_password').value;
    const confirmation = document.getElementById('new_password_confirmation').value;
    
    if (password !== confirmation) {
        e.preventDefault();
        alert('Password dan konfirmasi password tidak cocok!');
        return false;
    }
    
    if (password.length < 8) {
        e.preventDefault();
        alert('Password minimal 8 karakter!');
        return false;
    }
});
</script>
@endsection 