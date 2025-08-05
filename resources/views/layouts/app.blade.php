<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Indismart')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            background-color: #e22626;
            color: white;
            min-height: 100vh;
            position: fixed;
            width: 250px;
        }
        .sidebar-header {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-logo {
            height: 40px;
        }
        .sidebar-menu {
            padding: 1rem 0;
        }
        .sidebar-menu-item {
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
        }
        .sidebar-menu-item:hover, .sidebar-menu-item.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }
        .sidebar-menu-icon {
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }
        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }
        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
        }
        .page-title-box {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }
        .breadcrumb {
            background: none;
            padding: 0;
            margin: 0;
        }
        .breadcrumb-item a {
            color: #e22626;
            text-decoration: none;
        }
        .breadcrumb-item.active {
            color: #6c757d;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="d-flex align-items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Indismart Logo" class="sidebar-logo me-2">
                <h4 class="mb-0">Indismart</h4>
            </div>
        </div>
        
        <div class="sidebar-menu">
            @if(Auth::user()->isMitra())
                <a href="{{ route('mitra.dashboard') }}" class="sidebar-menu-item {{ request()->routeIs('mitra.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 sidebar-menu-icon"></i> Dashboard
                </a>
            @elseif(Auth::user()->isStaff())
                <a href="{{ route('staff.dashboard') }}" class="sidebar-menu-item {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 sidebar-menu-icon"></i> Dashboard
                </a>
            @endif
            
            <a href="{{ route('dokumen.index') }}" class="sidebar-menu-item {{ request()->routeIs('dokumen.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text sidebar-menu-icon"></i> Dokumen
            </a>
            
            @if(Auth::user()->isMitra())
                <a href="#" class="sidebar-menu-item">
                    <i class="bi bi-geo-alt sidebar-menu-icon"></i> Lokasi Proyek
                </a>
                <a href="#" class="sidebar-menu-item">
                    <i class="bi bi-bar-chart sidebar-menu-icon"></i> Analisis
                </a>
            @elseif(Auth::user()->isStaff())
                <a href="#" class="sidebar-menu-item">
                    <i class="bi bi-people sidebar-menu-icon"></i> Manajemen Mitra
                </a>
                <a href="{{ route('reviews.index') }}" class="sidebar-menu-item {{ request()->routeIs('reviews.*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard-check sidebar-menu-icon"></i> Review Dokumen
                </a>
            @endif
            
            <a href="#" class="sidebar-menu-item">
                <i class="bi bi-gear sidebar-menu-icon"></i> Pengaturan
            </a>
            
            <form method="POST" action="{{ route('logout') }}" class="mt-5">
                @csrf
                <button type="submit" class="sidebar-menu-item btn text-start w-100">
                    <i class="bi bi-box-arrow-left sidebar-menu-icon"></i> Logout
                </button>
            </form>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light mb-4">
            <div class="container-fluid">
                <h5 class="mb-0">@yield('title', 'SmartPED')</h5>
                <div class="ms-auto d-flex align-items-center">
                    <!-- Notification Bell -->
                    <div class="dropdown me-3">
                        <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown" id="notificationDropdown">
                            <i class="bi bi-bell fs-5"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationBadge" style="display: none;">
                                0
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" style="width: 350px; max-height: 400px; overflow-y: auto;">
                            <li class="dropdown-header d-flex justify-content-between align-items-center">
                                <span>Notifikasi</span>
                                <button class="btn btn-sm btn-outline-primary" id="markAllAsRead">Tandai Semua Dibaca</button>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <div id="notificationList">
                                <li class="dropdown-item text-center py-3">
                                    <i class="bi bi-inbox text-muted fs-1"></i>
                                    <p class="text-muted mb-0">Tidak ada notifikasi</p>
                                </li>
                            </div>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center" href="{{ route('notifications.index') }}">Lihat Semua Notifikasi</a></li>
                        </ul>
                    </div>
                    
                    <!-- User Dropdown -->
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}">Profil</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Edit Profil</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.change-password') }}">Ubah Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Content -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @yield('content')
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
    
    <!-- Notification Scripts -->
    <script>
        // Load notifications on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadNotifications();
            
            // Auto refresh notifications every 30 seconds
            setInterval(loadNotifications, 30000);
        });

        function loadNotifications() {
            // Load unread count
            fetch('{{ route("notifications.unread-count") }}')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notificationBadge');
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.style.display = 'block';
                    } else {
                        badge.style.display = 'none';
                    }
                });

            // Load unread notifications
            fetch('{{ route("notifications.unread") }}')
                .then(response => response.json())
                .then(data => {
                    const notificationList = document.getElementById('notificationList');
                    
                    if (data.length > 0) {
                        let html = '';
                        data.forEach(notification => {
                            const timeAgo = getTimeAgo(notification.created_at);
                            const iconClass = getNotificationIcon(notification.type);
                            
                            html += `
                                <li class="dropdown-item notification-item" data-id="${notification.id}">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3">
                                            <i class="${iconClass} fs-5"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">${notification.title}</h6>
                                            <p class="mb-1 small">${notification.message}</p>
                                            <small class="text-muted">${timeAgo}</small>
                                        </div>
                                        <button class="btn btn-sm btn-outline-secondary mark-as-read" data-id="${notification.id}">
                                            <i class="bi bi-check"></i>
                                        </button>
                                    </div>
                                </li>
                            `;
                        });
                        notificationList.innerHTML = html;
                    } else {
                        notificationList.innerHTML = `
                            <li class="dropdown-item text-center py-3">
                                <i class="bi bi-inbox text-muted fs-1"></i>
                                <p class="text-muted mb-0">Tidak ada notifikasi</p>
                            </li>
                        `;
                    }
                });
        }

        function getTimeAgo(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffInSeconds = Math.floor((now - date) / 1000);
            
            if (diffInSeconds < 60) return 'Baru saja';
            if (diffInSeconds < 3600) return Math.floor(diffInSeconds / 60) + ' menit yang lalu';
            if (diffInSeconds < 86400) return Math.floor(diffInSeconds / 3600) + ' jam yang lalu';
            return Math.floor(diffInSeconds / 86400) + ' hari yang lalu';
        }

        function getNotificationIcon(type) {
            switch(type) {
                case 'success': return 'bi bi-check-circle text-success';
                case 'warning': return 'bi bi-exclamation-triangle text-warning';
                case 'error': return 'bi bi-x-circle text-danger';
                default: return 'bi bi-info-circle text-info';
            }
        }

        // Mark as read functionality
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('mark-as-read') || e.target.closest('.mark-as-read')) {
                const button = e.target.classList.contains('mark-as-read') ? e.target : e.target.closest('.mark-as-read');
                const notificationId = button.dataset.id;
                
                fetch('{{ route("notifications.mark-as-read") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({notification_id: notificationId})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        button.closest('.notification-item').remove();
                        loadNotifications(); // Reload to update count
                    }
                });
            }
        });

        // Mark all as read
        document.getElementById('markAllAsRead').addEventListener('click', function() {
            fetch('{{ route("notifications.mark-all-as-read") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications(); // Reload to update count and list
                }
            });
        });
    </script>
</body>
</html> 