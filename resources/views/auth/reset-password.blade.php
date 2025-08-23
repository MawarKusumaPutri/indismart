<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - Indismart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #ffffff;
        }
        .reset-container {
            display: flex;
            min-height: 100vh;
        }
        .reset-sidebar {
            background-color: #E22626;
            color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: left;
        }
        .reset-form {
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .reset-logo {
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
        .reset-title {
            font-size: 2.5rem;
            font-weight: 600;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }
        .reset-description {
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
        .btn-submit {
            background-color: #E22626;
            border: none;
            padding: 0.8rem;
            font-size: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
            transition: all 0.3s ease;
            color: white;
        }
        .btn-submit:hover {
            background-color: #c41e1e;
        }
        .form-label {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        .action-links {
            text-align: center;
            margin-top: 2rem;
            color: #374151;
        }
        .action-links a {
            color: #E22626;
            text-decoration: none;
            font-weight: 500;
        }
        .action-links a:hover {
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
        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 1rem;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        .info-box {
            background: #d1ecf1;
            border: 1px solid #17a2b8;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
        }
        .info-box h6 {
            color: #0c5460;
            margin-bottom: 10px;
            font-weight: 600;
        }
        .info-box p {
            color: #0a4b53;
            margin: 0;
            font-size: 14px;
        }
        .password-field {
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
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0 reset-container">
            <!-- Sidebar Kiri -->
            <div class="col-md-5 reset-sidebar">
                <div class="text-center mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Indismart Logo" class="reset-logo">
                    <h2 class="logo-title">IndiSmart</h2>
                </div>
                
                <h1 class="reset-title">Pantau & Kelola Proyek Telekomunikasi untuk Masa Depan yang Lebih Baik</h1>
                
                <p class="reset-description">
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
            
            <!-- Form Reset Password -->
            <div class="col-md-7 reset-form">
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
                                <h1 class="mb-2">Reset Password</h1>
                                <p class="text-muted">Buat password baru untuk akun Anda</p>
                            </div>
                            
                            <div class="info-box">
                                <h6><i class="bi bi-info-circle me-2"></i>Password Baru</h6>
                                <p>Masukkan password baru untuk akun: <strong>{{ $email }}</strong></p>
                            </div>
                            
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <h6 class="alert-heading">⚠️ Error</h6>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            
                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf
                                
                                <input type="hidden" name="token" value="{{ $token }}">
                                <input type="hidden" name="email" value="{{ $email }}">
                                
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password Baru</label>
                                    <div class="password-field">
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Masukkan password baru" 
                                               required>
                                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                            <i class="bi bi-eye" id="password-icon"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Minimal 8 karakter</small>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                    <div class="password-field">
                                        <input type="password" 
                                               class="form-control @error('password_confirmation') is-invalid @enderror" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               placeholder="Konfirmasi password baru" 
                                               required>
                                        <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                                            <i class="bi bi-eye" id="password-confirmation-icon"></i>
                                        </button>
                                    </div>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-submit">
                                        <i class="bi bi-check-circle me-2"></i>Reset Password
                                    </button>
                                </div>
                            </form>
                            
                            <div class="action-links">
                                <a href="{{ route('login') }}">
                                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Login
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const passwordIcon = document.getElementById(fieldId === 'password' ? 'password-icon' : 'password-confirmation-icon');
            
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
