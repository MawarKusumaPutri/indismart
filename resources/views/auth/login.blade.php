<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Indismart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #ffffff;
        }
        .login-container {
            display: flex;
            min-height: 100vh;
        }
        .login-sidebar {
            background-color: #E22626;
            color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: left;
        }
        .login-form {
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-logo {
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
        .login-title {
            font-size: 2.5rem;
            font-weight: 600;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }
        .login-description {
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
        .form-control[type="select"] {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 6 7 7 7-7'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
            padding-right: 2.5rem;
        }
        select.form-control {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 6 7 7 7-7'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
            padding-right: 2.5rem;
        }
        .btn-login {
            background-color: #E22626;
            border: none;
            padding: 0.8rem;
            font-size: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            background-color: #c41e1e;
        }
        .form-label {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        .forgot-password {
            text-align: right;
            font-size: 0.875rem;
            color: #E22626;
            text-decoration: none;
            margin-top: 0.5rem;
        }
        .forgot-password:hover {
            text-decoration: underline;
        }
        .register-link {
            text-align: center;
            margin-top: 2rem;
            color: #374151;
        }
        .register-link a {
            color: #E22626;
            text-decoration: none;
            font-weight: 500;
        }
        .register-link a:hover {
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
        <div class="row g-0 login-container">
            <!-- Sidebar Kiri -->
            <div class="col-md-5 login-sidebar">
                <div class="text-center mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Indismart Logo" class="login-logo">
                    <h2 class="logo-title">IndiSmart</h2>
                </div>
                
                <h1 class="login-title">Pantau & Kelola Proyek Telekomunikasi untuk Masa Depan yang Lebih Baik</h1>
                
                <p class="login-description">
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
            
            <!-- Form Login -->
            <div class="col-md-7 login-form">
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
                                <h1 class="mb-2">Masuk ke Akun Anda</h1>
                                <p class="text-muted">Silakan masukkan kredensial Anda untuk melanjutkan</p>
                            </div>
                            
                            @if (session('info'))
                                <div class="alert alert-info">
                                    {{ session('info') }}
                                </div>
                            @endif
                            
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <h6 class="alert-heading">⚠️ Error Login</h6>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <hr>
                                    <small class="text-muted">
                                        <strong>Tips:</strong> Pastikan email dan role yang dipilih sesuai dengan akun Anda.
                                    </small>
                                </div>
                            @endif
                            
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email Anda" required autofocus>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="password-container">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password Anda" required>
                                        <button type="button" class="password-toggle" onclick="togglePassword()">
                                            <i class="bi bi-eye" id="password-icon"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="login_as" class="form-label">Masuk Sebagai</label>
                                    <select class="form-control" id="login_as" name="login_as" required onchange="toggleCredentials()">
                                        <option value="mitra">Mitra</option>
                                        <option value="staff">Karyawan</option>
                                    </select>
                                    <small class="text-muted" id="karyawan-credentials" style="display: none;">
                                        Karyawan: karyawan@telkom.co.id | Password: Ped123*
                                    </small>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-login">
                                        <i class="bi bi-arrow-right me-2"></i>Masuk
                                    </button>
                                </div>
                                
                                <div class="text-center mt-4">
                                    <a href="{{ route('password.request') }}" class="forgot-password d-block mb-3">Lupa password?</a>
                                    <div class="register-link">
                                        Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set CSRF token untuk semua request AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Function untuk toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');
            
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

        // Function untuk toggle kredensial karyawan
        function toggleCredentials() {
            const loginAs = document.getElementById('login_as').value;
            const karyawanCredentials = document.getElementById('karyawan-credentials');
            const emailInput = document.getElementById('email');
            
            if (loginAs === 'staff') {
                karyawanCredentials.style.display = 'block';
                // Set placeholder untuk email karyawan
                emailInput.placeholder = 'Masukkan email karyawan (karyawan@telkom.co.id)';
                // Validasi email karyawan
                emailInput.addEventListener('input', validateKaryawanEmail);
            } else {
                karyawanCredentials.style.display = 'none';
                // Reset placeholder untuk email mitra
                emailInput.placeholder = 'Masukkan email Anda';
                // Hapus validasi email karyawan
                emailInput.removeEventListener('input', validateKaryawanEmail);
            }
        }
        
        // Function untuk validasi email karyawan
        function validateKaryawanEmail() {
            const emailInput = document.getElementById('email');
            const loginAs = document.getElementById('login_as').value;
            
            if (loginAs === 'staff' && emailInput.value !== 'karyawan@telkom.co.id') {
                emailInput.setCustomValidity('Untuk login sebagai Karyawan, gunakan email: karyawan@telkom.co.id');
            } else {
                emailInput.setCustomValidity('');
            }
        }

        // Jalankan function saat halaman dimuat untuk set initial state
        document.addEventListener('DOMContentLoaded', function() {
            toggleCredentials();
        });
    </script>
</body>
</html>