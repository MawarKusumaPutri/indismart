@extends('layouts.app')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Pengaturan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Sistem</li>
                </ol>
            </nav>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Pengaturan Sistem</h1>
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
                        
                        <a href="{{ route('settings.appearance') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="bi bi-palette me-3"></i>
                            <div>
                                <h6 class="mb-0">Tampilan</h6>
                                <small class="text-muted">Tema, bahasa, dan preferensi tampilan</small>
                            </div>
                        </a>
                        
                        <a href="{{ route('settings.system') }}" class="list-group-item list-group-item-action d-flex align-items-center active">
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
            <!-- System Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-info-circle text-info me-2"></i>
                        Informasi Sistem
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Versi Aplikasi:</strong>
                                <span class="text-muted">Indismart v1.0.0</span>
                            </div>
                            <div class="mb-3">
                                <strong>Framework:</strong>
                                <span class="text-muted">Laravel {{ app()->version() }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>PHP Version:</strong>
                                <span class="text-muted">{{ phpversion() }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Database:</strong>
                                <span class="text-muted">MySQL</span>
                            </div>
                            <div class="mb-3">
                                <strong>Environment:</strong>
                                <span class="badge bg-{{ app()->environment() === 'production' ? 'success' : 'warning' }}">
                                    {{ app()->environment() }}
                                </span>
                            </div>
                            <div class="mb-3">
                                <strong>Last Updated:</strong>
                                <span class="text-muted">{{ now()->format('d F Y H:i:s') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cache Management -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-trash text-warning me-2"></i>
                        Manajemen Cache
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h6>Bersihkan Cache</h6>
                            <p class="text-muted small">Membersihkan cache dapat meningkatkan performa aplikasi dan memperbaiki masalah tampilan.</p>
                            
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <small>Cache yang akan dibersihkan: Application Cache, Config Cache, View Cache, Route Cache</small>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <form action="{{ route('settings.system.clear-cache') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning" onclick="return confirm('Apakah Anda yakin ingin membersihkan cache?')">
                                    <i class="bi bi-trash"></i> Bersihkan Cache
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Export -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-download text-success me-2"></i>
                        Export Data
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h6>Export Data Pribadi</h6>
                            <p class="text-muted small">Download semua data pribadi Anda dalam format JSON, termasuk profil, dokumen, dan notifikasi.</p>
                            
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle me-2"></i>
                                <small>Data yang akan diexport: Informasi Profil, Dokumen, Notifikasi, Pengaturan</small>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('settings.system.export-data') }}" class="btn btn-success">
                                <i class="bi bi-download"></i> Export Data
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Management -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-person-x text-danger me-2"></i>
                        Manajemen Akun
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h6>Hapus Akun</h6>
                            <p class="text-muted small">Menghapus akun akan menghapus semua data Anda secara permanen dan tidak dapat dikembalikan.</p>
                            
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <small><strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan. Semua data Anda akan dihapus permanen.</small>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                <i class="bi bi-person-x"></i> Hapus Akun
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Logs -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-file-text text-secondary me-2"></i>
                        Log Aktivitas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Aktivitas</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ now()->subMinutes(5)->format('H:i') }}</td>
                                    <td>Login ke sistem</td>
                                    <td><span class="badge bg-success">Berhasil</span></td>
                                </tr>
                                <tr>
                                    <td>{{ now()->subHours(2)->format('H:i') }}</td>
                                    <td>Upload dokumen</td>
                                    <td><span class="badge bg-success">Berhasil</span></td>
                                </tr>
                                <tr>
                                    <td>{{ now()->subDays(1)->format('d/m H:i') }}</td>
                                    <td>Update profil</td>
                                    <td><span class="badge bg-success">Berhasil</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Maintenance Mode -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-tools text-warning me-2"></i>
                        Mode Maintenance
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h6>Status Maintenance</h6>
                            <p class="text-muted small">Saat ini sistem berjalan normal. Mode maintenance hanya dapat diaktifkan oleh administrator.</p>
                            
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle me-2"></i>
                                <small>Sistem berjalan normal - Tidak ada maintenance yang dijadwalkan</small>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <button type="button" class="btn btn-outline-secondary" disabled>
                                <i class="bi bi-tools"></i> Mode Maintenance
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountModalLabel">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Konfirmasi Hapus Akun
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('settings.system.delete-account') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <h6 class="alert-heading">Peringatan!</h6>
                        <p class="mb-0">Tindakan ini akan menghapus akun Anda secara permanen. Semua data termasuk:</p>
                        <ul class="mb-0 mt-2">
                            <li>Profil dan informasi pribadi</li>
                            <li>Semua dokumen yang diupload</li>
                            <li>Riwayat notifikasi</li>
                            <li>Pengaturan dan preferensi</li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Masukkan Password untuk Konfirmasi</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirmation" class="form-label">Ketik "DELETE" untuk Konfirmasi</label>
                        <input type="text" class="form-control @error('confirmation') is-invalid @enderror" 
                               id="confirmation" name="confirmation" required>
                        @error('confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Ketik "DELETE" (tanpa tanda kutip) untuk mengkonfirmasi penghapusan akun</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger" id="confirmDeleteBtn" disabled>
                        <i class="bi bi-person-x"></i> Hapus Akun Permanen
                    </button>
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
</style>

<script>
// Delete account confirmation
document.getElementById('confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmation = this.value;
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    
    if (password && confirmation === 'DELETE') {
        confirmBtn.disabled = false;
    } else {
        confirmBtn.disabled = true;
    }
});

document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const confirmation = document.getElementById('confirmation').value;
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    
    if (password && confirmation === 'DELETE') {
        confirmBtn.disabled = false;
    } else {
        confirmBtn.disabled = true;
    }
});

// Confirm delete account
document.getElementById('confirmDeleteBtn').addEventListener('click', function(e) {
    if (!confirm('Apakah Anda benar-benar yakin ingin menghapus akun Anda? Tindakan ini tidak dapat dibatalkan!')) {
        e.preventDefault();
        return false;
    }
});
</script>
@endsection 