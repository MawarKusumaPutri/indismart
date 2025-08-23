<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lupa Password - Indismart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #ffffff;
        }
        .forgot-container {
            display: flex;
            min-height: 100vh;
        }
        .forgot-sidebar {
            background-color: #E22626;
            color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: left;
        }
        .forgot-form {
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .forgot-logo {
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
        .forgot-title {
            font-size: 2.5rem;
            font-weight: 600;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }
        .forgot-description {
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
            margin: 0 10px;
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
        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        .info-box {
            background: #e3f2fd;
            border: 1px solid #2196f3;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
        }
        .info-box h6 {
            color: #1976d2;
            margin-bottom: 10px;
            font-weight: 600;
        }
        .info-box p {
            color: #1565c0;
            margin: 0;
            font-size: 14px;
        }
        .divider {
            color: #6c757d;
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0 forgot-container">
            <!-- Sidebar Kiri -->
            <div class="col-md-5 forgot-sidebar">
                <div class="text-center mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Indismart Logo" class="forgot-logo">
                    <h2 class="logo-title">IndiSmart</h2>
                </div>
                
                <h1 class="forgot-title">Pantau & Kelola Proyek Telekomunikasi untuk Masa Depan yang Lebih Baik</h1>
                
                <p class="forgot-description">
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
            
            <!-- Form Lupa Password -->
            <div class="col-md-7 forgot-form">
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
                                <h1 class="mb-2">Lupa Password</h1>
                                <p class="text-muted">Reset password akun Anda untuk melanjutkan</p>
                            </div>
                            
                            <div class="info-box">
                                <h6><i class="bi bi-info-circle me-2"></i>Reset Password</h6>
                                <p>Masukkan email akun Anda untuk menerima link reset password</p>
                            </div>
                            
                            @if (session('status'))
                                <div class="alert alert-success">
                                    <h6 class="alert-heading">✅ Berhasil</h6>
                                    <p class="mb-0">{{ session('status') }}</p>
                                    <hr>
                                    <small class="text-muted">
                                        <strong>Note:</strong> Cek log Laravel untuk token reset password.
                                    </small>
                                </div>
                            @endif
                            
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
                            
                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf
                                
                                <div class="mb-4">
                                    <label for="email" class="form-label">Email Akun</label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="Masukkan email akun Anda" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-submit">
                                        <i class="bi bi-send me-2"></i>Kirim Link Reset Password
                                    </button>
                                </div>
                            </form>
                            
                            <div class="action-links">
                                <a href="{{ route('login') }}">
                                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Login
                                </a>
                                <span class="divider">|</span>
                                <a href="{{ route('register') }}">
                                    <i class="bi bi-person-plus me-1"></i>Daftar Akun Baru
                                </a>
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
