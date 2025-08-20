@extends('layouts.app')

@section('title', 'Profile Saya')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h4 class="page-title">👤 Profile Saya</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    @if($user->isMitra())
                        <a href="{{ route('mitra.dashboard') }}">Dashboard</a>
                    @elseif($user->isStaff())
                        <a href="{{ route('staff.dashboard') }}">Dashboard</a>
                    @endif
                </li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary">
                <i class="bi bi-pencil-square me-1"></i> Edit Profile
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">📄 Informasi Profile</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Avatar dan Info Dasar -->
                    <div class="col-md-4 text-center mb-4">
                        <div class="profile-avatar-section">
                            <!-- Avatar Display (only real photos) -->
                            @if($user->avatar && Storage::disk('public')->exists($user->avatar))
                                <div class="avatar-container mb-3">
                                    <img src="{{ Storage::url($user->avatar) }}" 
                                         alt="Avatar {{ $user->name }}" 
                                         class="profile-avatar rounded-circle img-thumbnail shadow-sm">
                                </div>
                            @endif
                            
                            <!-- Avatar Actions -->
                            <div class="avatar-actions mb-3">
                                <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-camera me-1"></i> 
                                    {{ $user->avatar ? 'Ganti Foto' : 'Upload Foto' }}
                                </a>
                            </div>
                            
                            <!-- User Info -->
                            <div class="user-info">
                                <h5 class="mb-1">{{ $user->name }}</h5>
                                <span class="badge bg-{{ $user->role === 'mitra' ? 'success' : 'secondary' }} fs-6">
                                    <i class="bi bi-{{ $user->role === 'mitra' ? 'building' : 'person' }} me-1"></i>
                                    {{ $user->role === 'mitra' ? 'Mitra' : 'Staff' }}
                                </span>
                            </div>
                            
                            <!-- Status Account -->
                            <div class="mt-3 p-3 bg-light rounded">
                                <small class="text-muted d-block">Status Akun</small>
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i> Aktif
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Detail Informasi -->
                    <div class="col-md-8">
                        <div class="profile-info">
                            <h6 class="section-title mb-3">
                                <i class="bi bi-info-circle me-2"></i>Informasi Personal
                            </h6>
                            
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-envelope text-primary me-2"></i>
                                        <strong>Email</strong>
                                    </div>
                                    <div class="info-value">{{ $user->email }}</div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-telephone text-success me-2"></i>
                                        <strong>Telepon</strong>
                                    </div>
                                </div>
                                
                                @if($user->isMitra())
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-hash text-warning me-2"></i>
                                        <strong>Nomor Kontrak</strong>
                                    </div>
                                    <div class="info-value">
                                        @if($user->nomor_kontrak)
                                            <span class="badge bg-success">{{ $user->nomor_kontrak }}</span>
                                        @else
                                            <span class="badge bg-warning">Belum Ditugaskan</span>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-telephone text-success me-2"></i>
                                        <strong>Telepon</strong>
                                    </div>
                                    <div class="info-value">
                                        @if($user->phone)
                                            {{ $user->phone }}
                                        @else
                                            <span class="text-muted">Belum diisi</span>

                                        @endif
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-geo-alt text-danger me-2"></i>
                                        <strong>Alamat</strong>
                                    </div>
                                    <div class="info-value">
                                        @if($user->address)
                                            {{ $user->address }}
                                        @else
                                            <span class="text-muted">Belum diisi</span>

                                        @endif
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-calendar text-info me-2"></i>
                                        <strong>Tanggal Lahir</strong>
                                    </div>
                                    <div class="info-value">
                                        @if($user->birth_date)
                                            {{ $user->birth_date->format('d F Y') }}
                                            <small class="text-muted ms-2">({{ $user->birth_date->diffInYears(now()) }} tahun)</small>
                                        @else
                                            <span class="text-muted">Belum diisi</span>

                                        @endif
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-{{ $user->gender === 'male' ? 'gender-male text-primary' : ($user->gender === 'female' ? 'gender-female text-danger' : 'question-circle text-muted') }} me-2"></i>
                                        <strong>Jenis Kelamin</strong>
                                    </div>
                                    <div class="info-value">
                                        @if($user->gender)
                                            {{ $user->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}
                                        @else
                                            <span class="text-muted">Belum diisi</span>

                                        @endif
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-clock-history text-secondary me-2"></i>
                                        <strong>Bergabung Sejak</strong>
                                    </div>
                                    <div class="info-value">
                                        {{ $user->created_at->format('d F Y, H:i') }}
                                        <small class="text-muted ms-2">({{ $user->created_at->diffForHumans() }})</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <hr class="my-4">
                <div class="profile-actions">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3">
                                <i class="bi bi-gear me-2"></i>Pengaturan Akun
                            </h6>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-pencil-square me-1"></i> Edit Profile
                                </a>
                                <a href="{{ route('profile.change-password') }}" class="btn btn-warning">
                                    <i class="bi bi-shield-lock me-1"></i> Ubah Password
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="mb-3">
                                <i class="bi bi-arrow-left me-2"></i>Navigasi
                            </h6>
                            @if($user->isMitra())
                                <a href="{{ route('mitra.dashboard') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-speedometer2 me-1"></i> Dashboard Mitra
                                </a>
                            @elseif($user->isStaff())
                                <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-speedometer2 me-1"></i> Dashboard Staff
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .profile-avatar {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border: 4px solid #e9ecef;
    }
    
    .profile-avatar-placeholder {
        width: 120px;
        height: 120px;
    }
    
    .profile-avatar-section {
        padding: 20px 0;
    }
    
    .section-title {
        color: #495057;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 8px;
    }
    
    .info-grid {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .info-item {
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #007bff;
    }
    
    .info-label {
        font-size: 0.9rem;
        margin-bottom: 5px;
        color: #6c757d;
    }
    
    .info-value {
        font-size: 1rem;
        color: #495057;
    }
    
    .profile-actions {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-top: 20px;
    }
    
    .card {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: none;
    }
    
    .badge {
        font-size: 0.875rem;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff, #0056b3);
    }
    
    .shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    @media (max-width: 768px) {
        .info-grid {
            gap: 15px;
        }
        
        .info-item {
            padding: 12px;
        }
        
        .profile-actions .row > div {
            margin-bottom: 20px;
        }
    }
</style>
@endpush 