<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SmartPED</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .login-container {
            display: flex;
            min-height: 100vh;
        }
        .login-sidebar {
            background-color: #e22626;
            color: white;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .login-form {
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-logo {
            width: 120px;
            margin-bottom: 1rem;
        }
        .login-title {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .login-description {
            margin-bottom: 2rem;
            font-size: 0.9rem;
            line-height: 1.5;
        }
        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            text-align: left;
            padding: 1rem;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
        }
        .feature-icon {
            font-size: 1.5rem;
            margin-right: 1rem;
            background-color: rgba(255, 255, 255, 0.2);
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }
        .feature-text {
            flex: 1;
        }
        .feature-title {
            font-weight: bold;
            margin-bottom: 0.25rem;
        }
        .feature-desc {
            font-size: 0.8rem;
            opacity: 0.9;
        }
        .btn-login {
            background-color: #e22626;
            border-color: #e22626;
            padding: 0.5rem 0;
        }
        .btn-login:hover {
            background-color: #c41e1e;
            border-color: #c41e1e;
        }
        .form-label {
            font-weight: 500;
        }
        .forgot-password {
            text-align: right;
            font-size: 0.8rem;
            margin-top: -0.5rem;
            margin-bottom: 1rem;
        }
        .register-link {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0 login-container">
            <!-- Sidebar Kiri -->
            <div class="col-md-5 login-sidebar">
                <div class="mb-4">
                    <img src="{{ asset('images/logo-white.png') }}" alt="SmartPED Logo" class="login-logo">
                    <h1 class="login-title">SmartPED</h1>
                </div>
                
                <h2 class="mb-4">Platform Dokumentasi & Manajemen Proyek Telekomunikasi</h2>
                
                <p class="login-description">
                    SmartPED membantu tim telekomunikasi mengelola dokumen, memantau lokasi proyek, dan mengoptimalkan implementasi infrastruktur untuk efisiensi operasional yang lebih baik.
                </p>
                
                <div class="w-100">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <div class="feature-text">
                            <div class="feature-title">Manajemen Dokumen</div>
                            <div class="feature-desc">Upload dan kelola dokumen proyek dengan mudah</div>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div class="feature-text">
                            <div class="feature-title">Lokasi Proyek</div>
                            <div class="feature-desc">Pantau lokasi dan status implementasi proyek</div>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="bi bi-bar-chart"></i>
                        </div>
                        <div class="feature-text">
                            <div class="feature-title">Analisis Data</div>
                            <div class="feature-desc">Visualisasi dan analisis performa proyek</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Form Login -->
            <div class="col-md-7 login-form">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="text-center mb-4">
                                <img src="{{ asset('images/logo.png') }}" alt="SmartPED Logo" height="60">
                                <h2 class="mt-3 mb-2">SMARTPED</h2>
                            </div>
                            
                            <h3 class="text-center mb-4">Masuk ke Akun Anda</h3>
                            <p class="text-center text-muted mb-4">Silakan masukkan kredensial Anda untuk melanjutkan</p>
                            
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email Anda" required autofocus>
                                </div>
                                
                                <div class="mb-2">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password Anda" required>
                                </div>
                                
                                <!-- Tambahkan dropdown role di sini -->
                                <div class="mb-3">
                                    <label for="login_as" class="form-label">Masuk Sebagai</label>
                                    <select class="form-select" id="login_as" name="login_as" required>
                                        <option value="mitra">Mitra</option>
                                        <option value="staff">Staff</option>
                                    </select>
                                </div>
                                
                                <div class="forgot-password">
                                    <a href="#">Lupa password?</a>
                                </div>
                                
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">Ingat saya</label>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-login">Masuk</button>
                                </div>
                            </form>
                            
                            <div class="register-link">
                                <p>atau</p>
                                <p>Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>