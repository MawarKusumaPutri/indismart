<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Indismart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #ffffff;
        }
        .register-container {
            display: flex;
            min-height: 100vh;
        }
        .register-sidebar {
            background-color: #E22626;
            color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: left;
        }
        .register-form {
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .register-logo {
            width: 150px;
            margin-bottom: 1rem;
            background: white;
            border-radius: 50%;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .logo-title {
            color: white;
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 2rem;
        }
        .register-title {
            font-size: 2.5rem;
            font-weight: 600;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }
        .register-description {
            margin-bottom: 3rem;
            font-size: 1rem;
            line-height: 1.6;
            opacity: 0.9;
        }
        .feature-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 2rem;
        }
        .feature-button {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        .feature-button:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .feature-icon {
            font-size: 1.2rem;
        }
        .form-control {
            padding: 0.8rem 1rem;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        .form-control:focus {
            border-color: #E22626;
            box-shadow: 0 0 0 2px rgba(226, 38, 38, 0.1);
        }
        .password-container {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6B7280;
            cursor: pointer;
            padding: 0;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }
        .password-toggle:hover {
            color: #E22626;
        }
        .password-container .form-control {
            padding-right: 3rem;
        }
        .btn-register {
            background-color: #E22626;
            border: none;
            padding: 0.8rem;
            font-size: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }
        .btn-register:hover {
            background-color: #c41e1e;
        }
        .form-label {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        .login-link {
            text-align: center;
            margin-top: 2rem;
            color: #374151;
        }
        .login-link a {
            color: #E22626;
            text-decoration: none;
            font-weight: 500;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .logo-circle {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .logo-img {
            width: 30px;
            height: 30px;
            object-fit: contain;
        }
        h2 {
            color: #333;
            font-size: 1.5rem;
        }

    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0 register-container">
            <!-- Sidebar Kiri -->
            <div class="col-md-5 register-sidebar">
                <div class="text-center mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Indismart Logo" class="register-logo">
                    <h2 class="logo-title">IndiSmart</h2>
                </div>
                
                <h1 class="register-title">Pantau & Kelola Proyek Telekomunikasi untuk Masa Depan yang Lebih Baik</h1>
                
                <p class="register-description">
                    Indismart membantu tim telekomunikasi mengelola dokumen, memantau implementasi, dan mengoptimalkan proyek untuk efisiensi operasional yang lebih baik.
                </p>
                
                <div class="feature-buttons">
                    <button class="feature-button">
                        <i class="bi bi-graph-up feature-icon"></i>
                        Analisis Realtime
                    </button>
                    
                    <button class="feature-button">
                        <i class="bi bi-file-text feature-icon"></i>
                        Manajemen Dokumen
                    </button>
                    
                    <button class="feature-button">
                        <i class="bi bi-bar-chart feature-icon"></i>
                        Visualisasi Data
                    </button>
                </div>
            </div>
            
            <!-- Form Register -->
            <div class="col-md-7 register-form">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="text-center mb-5">
                                <div class="d-flex align-items-center justify-content-center gap-2 mb-4">
                                    <div class="logo-circle">
                                        <img src="{{ asset('images/logo.png') }}" alt="Indismart Logo" class="logo-img">
                                    </div>
                                    <h2 class="mb-0 fw-bold">INDISMART</h2>
                                </div>
                                <h1 class="mb-2">Daftar Akun Baru</h1>
                                <p class="text-muted">Silakan lengkapi data berikut untuk membuat akun</p>
                            </div>
                            
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
                                    <div class="password-container">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                                        <button type="button" class="password-toggle" onclick="togglePassword('password', 'password-icon')">
                                            <i class="bi bi-eye" id="password-icon"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                    <div class="password-container">
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi password" required>
                                        <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', 'password-confirmation-icon')">
                                            <i class="bi bi-eye" id="password-confirmation-icon"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="role" class="form-label">Daftar Sebagai</label>
                                    <input type="text" class="form-control" value="Mitra" readonly>
                                    <input type="hidden" name="role" value="mitra">
                                    <small class="text-muted">Registrasi sebagai Mitra</small>
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
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function untuk toggle password visibility
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const passwordIcon = document.getElementById(iconId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('bi-eye');
                passwordIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('bi-eye-slash');
                passwordIcon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>