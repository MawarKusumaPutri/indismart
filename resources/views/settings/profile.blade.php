@extends('layouts.app')

@section('title', 'Pengaturan Profil')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Pengaturan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Profil</li>
                </ol>
            </nav>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Pengaturan Profil</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar Pengaturan -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('settings.profile') }}" class="list-group-item list-group-item-action d-flex align-items-center active">
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
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Profil</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <!-- Avatar Section -->
                            <div class="col-md-4 text-center mb-4">
                                <div class="avatar-section">
                                    @if($user->avatar && Storage::disk('public')->exists($user->avatar))
                                        <img src="{{ Storage::url($user->avatar) }}" 
                                             alt="Avatar {{ $user->name }}" 
                                             class="profile-avatar rounded-circle img-thumbnail shadow-sm mb-3"
                                             style="width: 150px; height: 150px; object-fit: cover;">
                                    @else
                                        <div class="profile-avatar-placeholder rounded-circle d-flex align-items-center justify-content-center bg-light border mb-3 mx-auto"
                                             style="width: 150px; height: 150px;">
                                            <i class="bi bi-person text-muted" style="font-size: 3rem;"></i>
                                        </div>
                                    @endif
                                    
                                    <div class="mb-3">
                                        <label for="avatar" class="form-label">Ubah Foto Profil</label>
                                        <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                                        <div class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB.</div>
                                    </div>
                                    
                                    @if($user->avatar && Storage::disk('public')->exists($user->avatar))
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteAvatar()">
                                            <i class="bi bi-trash"></i> Hapus Foto
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <!-- Form Fields -->
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Nomor Telepon</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="gender" class="form-label">Jenis Kelamin</label>
                                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                                            <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="birth_date" class="form-label">Tanggal Lahir</label>
                                        <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                               id="birth_date" name="birth_date" value="{{ old('birth_date', $user->birth_date ? $user->birth_date->format('Y-m-d') : '') }}">
                                        @error('birth_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="role" class="form-label">Role</label>
                                        <input type="text" class="form-control" id="role" value="{{ ucfirst($user->role) }}" readonly>
                                        <div class="form-text">Role tidak dapat diubah</div>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label for="address" class="form-label">Alamat</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                                  id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </div>
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

.profile-avatar {
    border: 3px solid #E22626;
}

.profile-avatar-placeholder {
    border: 3px solid #dee2e6;
}
</style>

<script>
function deleteAvatar() {
    if (confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
        fetch('{{ route("profile.delete-avatar") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Terjadi kesalahan saat menghapus foto profil');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus foto profil');
        });
    }
}

// Preview avatar before upload
document.getElementById('avatar').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const avatarSection = document.querySelector('.avatar-section');
            const existingImg = avatarSection.querySelector('img');
            const existingPlaceholder = avatarSection.querySelector('.profile-avatar-placeholder');
            
            if (existingImg) {
                existingImg.src = e.target.result;
            } else if (existingPlaceholder) {
                existingPlaceholder.innerHTML = `<img src="${e.target.result}" class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">`;
                existingPlaceholder.classList.remove('profile-avatar-placeholder');
            }
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection 