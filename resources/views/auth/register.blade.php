<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SmartPED</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .register-container {
            display: flex;
            min-height: 100vh;
        }
        .register-sidebar {
            background-color: #e22626;
            color: white;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .register-form {
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .register-logo {
            width: 120px;
            margin-bottom: 1rem;
        }
        .register-title {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .register-description {
            margin-bottom: 2rem;
            font-size: 0.9rem;
            line-height: 1.5;
        }
        .btn-register {
            background-color: #e22626;
            border-color: #e22626;
            padding: 0.5rem 0;
        }
        .btn-register:hover {
            background-color: #c41e1e;
            border-color: #c41e1e;
        }
        .form-label {
            font-weight: 500;
        }
        .login-link {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0 register-container">
            <!-- Sidebar Kiri -->
            <div class="col-md-5 register-sidebar">
                <div class="mb-4">
                    <img src="{{ asset('images/logo-white.png') }}" alt="SmartPED Logo" class="register-logo">
                    <h1 class="register-title">SmartPED</h1>
                </div>
                
                <h2 class="mb-4">Platform Dokumentasi & Manajemen Proyek Telekomunikasi</h2>
                
                <p class="register-description">
                    SmartPED membantu tim telekomunikasi mengelola dokumen, memantau lokasi proyek, dan mengoptimalkan implementasi infrastruktur untuk efisiensi operasional yang lebih baik.
                </p>
            </div>
            
            <!-- Form Register -->
            <div class="col-md-7 register-form">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="text-center mb-4">
                                <img src="{{ asset('images/logo.png') }}" alt="SmartPED Logo" height="60">
                                <h2 class="mt-3 mb-2">SMARTPED</h2>
                            </div>
                            
                            <h3 class="text-center mb-4">Daftar Akun Baru</h3>
                            <p class="text-center text-muted mb-4">Silakan lengkapi data berikut untuk membuat akun</p>
                            
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            
                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required autofocus>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi password" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="role" class="form-label">Daftar Sebagai</label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="mitra">Mitra</option>
                                        <option value="staff">Staff</option>
                                    </select>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-register">Daftar</button>
                                </div>
                            </form>
                            
                            <div class="login-link">
                                <p>atau</p>
                                <p>Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>