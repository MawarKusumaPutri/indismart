@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ Auth::user()->isMitra() ? route('mitra.dashboard') : route('staff.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pengaturan</li>
                </ol>
            </nav>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Pengaturan</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar Pengaturan -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('settings.profile') }}" class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('settings.profile') ? 'active' : '' }}">
                            <i class="bi bi-person-circle me-3"></i>
                            <div>
                                <h6 class="mb-0">Profil</h6>
                                <small class="text-muted">Informasi pribadi dan avatar</small>
                            </div>
                        </a>
                        
                        <a href="{{ route('settings.security') }}" class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('settings.security') ? 'active' : '' }}">
                            <i class="bi bi-shield-lock me-3"></i>
                            <div>
                                <h6 class="mb-0">Keamanan</h6>
                                <small class="text-muted">Password dan keamanan akun</small>
                            </div>
                        </a>
                        
                        <a href="{{ route('settings.notifications') }}" class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('settings.notifications') ? 'active' : '' }}">
                            <i class="bi bi-bell me-3"></i>
                            <div>
                                <h6 class="mb-0">Notifikasi</h6>
                                <small class="text-muted">Pengaturan notifikasi email dan sistem</small>
                            </div>
                        </a>
                        
                        <a href="{{ route('settings.appearance') }}" class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('settings.appearance') ? 'active' : '' }}">
                            <i class="bi bi-palette me-3"></i>
                            <div>
                                <h6 class="mb-0">Tampilan</h6>
                                <small class="text-muted">Tema, bahasa, dan preferensi tampilan</small>
                            </div>
                        </a>
                        
                        <a href="{{ route('settings.system') }}" class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('settings.system') ? 'active' : '' }}">
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
                <div class="card-body">
                    <div class="text-center py-5">
                        <i class="bi bi-gear text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 mb-2">Selamat Datang di Pengaturan</h4>
                        <p class="text-muted mb-4">Pilih kategori pengaturan di sidebar untuk mengatur preferensi Anda</p>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <i class="bi bi-person-circle text-primary mb-2" style="font-size: 2rem;"></i>
                                        <h6>Profil</h6>
                                        <p class="small text-muted mb-2">Kelola informasi pribadi dan avatar</p>
                                        <a href="{{ route('settings.profile') }}" class="btn btn-sm btn-outline-primary">Kelola Profil</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <i class="bi bi-shield-lock text-success mb-2" style="font-size: 2rem;"></i>
                                        <h6>Keamanan</h6>
                                        <p class="small text-muted mb-2">Ubah password dan pengaturan keamanan</p>
                                        <a href="{{ route('settings.security') }}" class="btn btn-sm btn-outline-success">Kelola Keamanan</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <i class="bi bi-bell text-warning mb-2" style="font-size: 2rem;"></i>
                                        <h6>Notifikasi</h6>
                                        <p class="small text-muted mb-2">Atur preferensi notifikasi</p>
                                        <a href="{{ route('settings.notifications') }}" class="btn btn-sm btn-outline-warning">Kelola Notifikasi</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <i class="bi bi-palette text-info mb-2" style="font-size: 2rem;"></i>
                                        <h6>Tampilan</h6>
                                        <p class="small text-muted mb-2">Kustomisasi tema dan tampilan</p>
                                        <a href="{{ route('settings.appearance') }}" class="btn btn-sm btn-outline-info">Kelola Tampilan</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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

.card.bg-light {
    background-color: #f8f9fa !important;
}
</style>
@endsection 