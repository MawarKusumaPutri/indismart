@extends('layouts.app')

@section('title', 'Edit Profile')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h4 class="page-title">✏️ Edit Profile</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    @if($user->isMitra())
                        <a href="{{ route('mitra.dashboard') }}">Dashboard</a>
                    @elseif($user->isStaff())
                        <a href="{{ route('staff.dashboard') }}">Dashboard</a>
                    @endif
                </li>
                <li class="breadcrumb-item"><a href="{{ route('profile.show') }}">Profile</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">📝 Edit Informasi Profile</h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Avatar Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="bi bi-image me-2"></i>Foto Profil</h6>
                                </div>
                                <div class="card-body text-center">
                                    <div class="avatar-upload-section">
                                        <div id="avatar-preview" class="avatar-preview mb-3">
                                            @if($user->avatar && Storage::disk('public')->exists($user->avatar))
                                                <img src="{{ Storage::url($user->avatar) }}" 
                                                     alt="Avatar {{ $user->name }}" 
                                                     class="rounded-circle img-thumbnail shadow-sm"
                                                     style="width: 120px; height: 120px; object-fit: cover;">
                                            @else
                                                <div class="avatar-placeholder rounded-circle bg-gradient-primary d-flex align-items-center justify-content-center mx-auto shadow-sm"
                                                     style="width: 120px; height: 120px;">
                                                    <i class="bi bi-person-circle fs-1 text-white"></i>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="avatar" class="form-label">
                                                <i class="bi bi-upload me-1"></i>Pilih Foto Baru
                                            </label>
                                            <input type="file" 
                                                   class="form-control @error('avatar') is-invalid @enderror" 
                                                   id="avatar" 
                                                   name="avatar" 
                                                   accept="image/jpeg,image/png,image/jpg,image/gif">
                                            <div class="form-text">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Format: JPEG, PNG, JPG, GIF. Maksimal 2MB.
                                            </div>
                                            @error('avatar')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        @if($user->avatar)
                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button" 
                                                        class="btn btn-outline-danger btn-sm"
                                                        onclick="deleteAvatar()">
                                                    <i class="bi bi-trash me-1"></i>Hapus Foto
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="bi bi-person me-2"></i>Informasi Personal</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                                <input type="text" 
                                                       class="form-control @error('name') is-invalid @enderror" 
                                                       id="name" 
                                                       name="name" 
                                                       value="{{ old('name', $user->name) }}" 
                                                       required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                                <input type="email" 
                                                       class="form-control @error('email') is-invalid @enderror" 
                                                       id="email" 
                                                       name="email" 
                                                       value="{{ old('email', $user->email) }}" 
                                                       required>
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="phone" class="form-label">Nomor Telepon</label>
                                                <input type="tel" 
                                                       class="form-control @error('phone') is-invalid @enderror" 
                                                       id="phone" 
                                                       name="phone" 
                                                       value="{{ old('phone', $user->phone) }}"
                                                       placeholder="Contoh: 08123456789">
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="birth_date" class="form-label">Tanggal Lahir</label>
                                                <input type="date" 
                                                       class="form-control @error('birth_date') is-invalid @enderror" 
                                                       id="birth_date" 
                                                       name="birth_date" 
                                                       value="{{ old('birth_date', $user->birth_date ? $user->birth_date->format('Y-m-d') : '') }}">
                                                @error('birth_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="gender" class="form-label">Jenis Kelamin</label>
                                                <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                                    <option value="">Pilih Jenis Kelamin</option>
                                                    <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Laki-laki</option>
                                                    <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Perempuan</option>
                                                </select>
                                                @error('gender')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="address" class="form-label">Alamat</label>
                                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                                          id="address" 
                                                          name="address" 
                                                          rows="3"
                                                          placeholder="Masukkan alamat lengkap">{{ old('address', $user->address) }}</textarea>
                                                @error('address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="d-flex justify-content-center gap-3">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                                        </button>
                                        <a href="{{ route('profile.show') }}" class="btn btn-secondary btn-lg">
                                            <i class="bi bi-x-circle me-2"></i>Batal
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Avatar Preview
    document.getElementById('avatar').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatar-preview');
                preview.innerHTML = `<img src="${e.target.result}" 
                                           class="rounded-circle img-thumbnail shadow-sm" 
                                           style="width: 120px; height: 120px; object-fit: cover;" 
                                           alt="Preview Avatar">`;
            };
            reader.readAsDataURL(file);
        }
    });

    // Delete Avatar Function
    function deleteAvatar() {
        if (confirm('Yakin ingin menghapus foto profil?')) {
            fetch('{{ route('profile.delete-avatar') }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Gagal menghapus foto profil');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            });
        }
    }
</script>
@endpush