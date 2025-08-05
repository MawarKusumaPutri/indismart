<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - {{ $user->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-user me-2"></i>Profile Saya
                        </h4>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-4 text-center mb-4">
                                <div class="position-relative d-inline-block">
                                    @if($user->avatar)
                                        <img src="{{ Storage::url($user->avatar) }}" 
                                             alt="Avatar" 
                                             class="rounded-circle img-thumbnail" 
                                             style="width: 150px; height: 150px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mx-auto" 
                                             style="width: 150px; height: 150px;">
                                            <i class="fas fa-user fa-3x text-white"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-3">
                                    <span class="badge bg-{{ $user->role === 'mitra' ? 'success' : 'info' }} fs-6">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row mb-3">
                                    <div class="col-sm-4 fw-bold">Nama:</div>
                                    <div class="col-sm-8">{{ $user->name }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 fw-bold">Email:</div>
                                    <div class="col-sm-8">{{ $user->email }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 fw-bold">Telepon:</div>
                                    <div class="col-sm-8">{{ $user->phone ?? 'Belum diisi' }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 fw-bold">Alamat:</div>
                                    <div class="col-sm-8">{{ $user->address ?? 'Belum diisi' }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 fw-bold">Tanggal Lahir:</div>
                                    <div class="col-sm-8">{{ $user->birth_date ? $user->birth_date->format('d/m/Y') : 'Belum diisi' }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 fw-bold">Jenis Kelamin:</div>
                                    <div class="col-sm-8">
                                        @if($user->gender)
                                            <i class="fas fa-{{ $user->gender === 'male' ? 'mars text-primary' : 'venus text-danger' }} me-2"></i>
                                            {{ $user->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}
                                        @else
                                            Belum diisi
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 fw-bold">Bergabung Sejak:</div>
                                    <div class="col-sm-8">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i>Edit Profile
                            </a>
                            <a href="{{ route('profile.change-password') }}" class="btn btn-warning">
                                <i class="fas fa-key me-2"></i>Ubah Password
                            </a>
                            @if($user->isMitra())
                                <a href="{{ route('mitra.dashboard') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                                </a>
                            @elseif($user->isStaff())
                                <a href="{{ route('staff.dashboard') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 