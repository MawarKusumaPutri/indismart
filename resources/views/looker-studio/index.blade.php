@extends('layouts.app')

@section('title', 'Looker Studio Dashboard - Indismart')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="bi bi-graph-up-arrow text-primary me-2"></i>
                        Looker Studio Dashboard
                    </h1>
                    <p class="text-muted mb-0">Analytics Dashboard untuk Indismart</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary" onclick="refreshData()">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        Refresh Data
                    </button>
                    <button type="button" class="btn btn-primary" onclick="generateDashboard()">
                        <i class="bi bi-plus-circle me-1"></i>
                        Generate Dashboard
                    </button>
                    <button type="button" class="btn btn-info" onclick="createDirectLink()">
                        <i class="bi bi-box-arrow-up-right me-1"></i>
                        Direct Link
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Banner -->
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Looker Studio Integration:</strong> 
                Dashboard akan dibuat secara otomatis dengan data real-time dari sistem Indismart.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Mitra
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalMitra">
                                {{ $dashboardData['summary']['total_mitra'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Dokumen
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalDokumen">
                                {{ $dashboardData['summary']['total_dokumen'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-file-earmark-text fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Proyek Aktif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="proyekAktif">
                                {{ $dashboardData['summary']['proyek_aktif'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-activity fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Foto
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalFoto">
                                {{ $dashboardData['summary']['total_foto'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-image fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-pie-chart me-2"></i>
                        Distribusi Status Proyek
                    </h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                            <a class="dropdown-item" href="#" onclick="exportChartData('status')">Export Data</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-bar-chart me-2"></i>
                        Distribusi Jenis Proyek
                    </h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink2" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                            <a class="dropdown-item" href="#" onclick="exportChartData('project_type')">Export Data</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="projectTypeChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>



    <!-- Looker Studio Dashboard Display -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-graph-up me-2"></i>
                        Looker Studio Dashboard
                    </h6>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="refreshDashboard()">
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            Refresh
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleFullscreen()">
                            <i class="bi bi-arrows-fullscreen me-1"></i>
                            Fullscreen
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="dashboardContainer" class="position-relative">
                        <!-- Loading State -->
                        <div id="dashboardLoading" class="text-center py-5">
                            <div class="spinner-border text-primary mb-3" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <h5>Memuat Dashboard Looker Studio...</h5>
                            <p class="text-muted">Mohon tunggu, dashboard sedang dimuat.</p>
                        </div>
                        
                        <!-- Dashboard Embed -->
                        <div id="dashboardEmbed" style="display: none;">
                            <div class="alert alert-info mb-3">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Info:</strong> Jika dashboard tidak dapat diakses, gunakan tombol "Buka di Tab Baru" di bawah.
                            </div>
                            <iframe id="lookerStudioFrame" 
                                    src="" 
                                    width="100%" 
                                    height="600" 
                                    frameborder="0" 
                                    style="border:0;"
                                    allowfullscreen>
                            </iframe>
                            <div class="mt-3 text-center">
                                <button type="button" class="btn btn-outline-primary" onclick="openDashboardInNewTab()">
                                    <i class="bi bi-box-arrow-up-right me-2"></i>
                                    Buka di Tab Baru
                                </button>
                                <button type="button" class="btn btn-outline-secondary ms-2" onclick="showEmbeddingHelp()">
                                    <i class="bi bi-question-circle me-2"></i>
                                    Bantuan Embedding
                                </button>
                            </div>
                        </div>
                        
                        <!-- No Dashboard State -->
                        <div id="noDashboardState" class="text-center py-5" style="display: none;">
                            <div class="mb-4">
                                <i class="bi bi-graph-up text-muted" style="font-size: 4rem;"></i>
                            </div>
                            <h5>Belum Ada Dashboard</h5>
                            <p class="text-muted mb-4">
                                Dashboard Looker Studio belum dibuat atau belum dikonfigurasi.
                            </p>
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button" class="btn btn-primary" onclick="generateDashboard()">
                                    <i class="bi bi-plus-circle me-2"></i>
                                    Buat Dashboard Baru
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="showCustomUrlInput()">
                                    <i class="bi bi-link me-2"></i>
                                    Masukkan URL Dashboard
                                </button>
                                <button type="button" class="btn btn-success" onclick="createNewReport()">
                                    <i class="bi bi-file-earmark-plus me-2"></i>
                                    Buat Laporan Baru
                                </button>
                            </div>
                        </div>
                        
                                                 <!-- Error State -->
                         <div id="dashboardError" class="text-center py-5" style="display: none;">
                             <div class="mb-4">
                                 <i class="bi bi-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                             </div>
                             <h5>Gagal Memuat Dashboard</h5>
                             <p class="text-muted mb-4" id="errorMessage">
                                 Terjadi kesalahan saat memuat dashboard.
                             </p>
                             <div class="d-flex justify-content-center gap-2 flex-wrap">
                                 <button type="button" class="btn btn-primary" onclick="refreshDashboard()">
                                     <i class="bi bi-arrow-clockwise me-2"></i>
                                     Coba Lagi
                                 </button>
                                 <button type="button" class="btn btn-outline-secondary" onclick="showCustomUrlInput()">
                                     <i class="bi bi-link me-2"></i>
                                     Masukkan URL Manual
                                 </button>
                                 <button type="button" class="btn btn-success" onclick="createNewReport()">
                                     <i class="bi bi-file-earmark-plus me-2"></i>
                                     Buat Laporan Baru
                                 </button>
                                 <button type="button" class="btn btn-info" onclick="showEmbeddingHelp()">
                                     <i class="bi bi-question-circle me-2"></i>
                                     Bantuan
                                 </button>
                             </div>
                             <div class="mt-3">
                                 <small class="text-muted">
                                     <i class="bi bi-info-circle me-1"></i>
                                     Jika masalah berlanjut, coba buka dashboard di tab baru atau refresh halaman.
                                 </small>
                             </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Looker Studio Integration Controls -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-gear me-2"></i>
                        Konfigurasi Dashboard
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Generate Dashboard Otomatis</h5>
                            <p class="text-muted">
                                Klik tombol di bawah untuk membuat dashboard Looker Studio secara otomatis 
                                dengan data real-time dari sistem Indismart.
                            </p>
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-primary btn-lg" onclick="generateDashboard()">
                                    <i class="bi bi-magic me-2"></i>
                                    Generate Looker Studio Dashboard
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="showDashboardUrl()">
                                    <i class="bi bi-link me-2"></i>
                                    Tampilkan URL Dashboard
                                </button>
                            </div>
                            
                            <!-- Custom URL Input -->
                            <div class="mt-4" id="customUrlSection" style="display: none;">
                                <h6>Masukkan URL Looker Studio Eksternal</h6>
                                <p class="text-muted small">
                                    Jika Anda sudah memiliki dashboard Looker Studio yang sudah dibuat, 
                                    masukkan URL-nya di sini.
                                </p>
                                <div class="input-group mb-3">
                                    <input type="url" class="form-control" id="customUrlInput" 
                                           placeholder="https://lookerstudio.google.com/reporting/..." 
                                           pattern="https://lookerstudio\.google\.com.*">
                                    <button class="btn btn-outline-primary" type="button" onclick="setCustomUrl()">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Set URL
                                    </button>
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    URL harus dari Looker Studio (https://lookerstudio.google.com)
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Export Data</h5>
                            <p class="text-muted">
                                Export data dalam berbagai format untuk digunakan di Looker Studio.
                            </p>
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-info" onclick="exportData('csv')">
                                    <i class="bi bi-filetype-csv me-2"></i>
                                    Export CSV
                                </button>
                                <button type="button" class="btn btn-warning" onclick="exportData('all')">
                                    <i class="bi bi-download me-2"></i>
                                    Export Semua Data
                                </button>
                            </div>
                            
                            <!-- Dashboard URL Display -->
                            <div id="dashboardUrlSection" class="mt-4" style="display: none;">
                                <div class="alert alert-success">
                                    <h6><i class="bi bi-check-circle me-2"></i>Dashboard Berhasil Dibuat!</h6>
                                    <p class="mb-2">URL Dashboard Looker Studio:</p>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="dashboardUrl" readonly>
                                        <button class="btn btn-outline-secondary" type="button" onclick="copyDashboardUrl()">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                        <a class="btn btn-primary" id="openDashboardBtn" href="#" target="_blank">
                                            <i class="bi bi-box-arrow-up-right me-2"></i>
                                            Buka Dashboard
                                        </a>
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

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h5>Membuat Dashboard Looker Studio...</h5>
                <p class="text-muted">Mohon tunggu, sistem sedang memproses data dan membuat dashboard.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}



.card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

/* Loading spinner animation */
.spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* Error state styling */
.error-state {
    color: #dc3545;
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    border-radius: 0.375rem;
    padding: 1rem;
    margin: 1rem 0;
}

/* Success state styling */
.success-state {
    color: #155724;
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    border-radius: 0.375rem;
    padding: 1rem;
    margin: 1rem 0;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart configurations
let statusChart, projectTypeChart;

// Initialize charts
document.addEventListener('DOMContentLoaded', function() {
    try {
        console.log('DOM loaded, initializing Looker Studio dashboard...');
        initializeCharts();
        loadRealTimeData();
        
        // Add event listener for custom URL input
        const customUrlInput = document.getElementById('customUrlInput');
        if (customUrlInput) {
            customUrlInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    setCustomUrl();
                }
            });
        }
        
        // Check if there's an existing custom URL and display it
        checkExistingUrl();
        
        // Initialize dashboard display with better error handling
        setTimeout(() => {
            initializeDashboardDisplay();
        }, 1000);
        
        console.log('Looker Studio dashboard initialized successfully');
    } catch (error) {
        console.error('Error initializing Looker Studio dashboard:', error);
        showAlert('error', 'Gagal memuat dashboard: ' + error.message);
    }
});

function initializeCharts() {
    try {
        // Check if Chart.js is loaded
        if (typeof Chart === 'undefined') {
            console.error('Chart.js not loaded');
            showAlert('error', 'Chart.js tidak terload. Silakan refresh halaman.');
            return;
        }

        // Status Distribution Chart
        const statusCanvas = document.getElementById('statusChart');
        if (statusCanvas) {
            const statusCtx = statusCanvas.getContext('2d');
            if (statusCtx) {
                const statusData = {!! json_encode($dashboardData['distribusi_status'] ?? []) !!};
                const statusLabels = Object.keys(statusData);
                const statusValues = Object.values(statusData);
                
                statusChart = new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: statusLabels,
                        datasets: [{
                            data: statusValues,
                            backgroundColor: [
                                '#4e73df',
                                '#1cc88a',
                                '#36b9cc',
                                '#f6c23e',
                                '#e74a3b'
                            ],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }
        }

        // Project Type Distribution Chart
        const projectTypeCanvas = document.getElementById('projectTypeChart');
        if (projectTypeCanvas) {
            const projectTypeCtx = projectTypeCanvas.getContext('2d');
            if (projectTypeCtx) {
                const projectTypeData = {!! json_encode($dashboardData['distribusi_jenis_proyek'] ?? []) !!};
                const projectTypeLabels = Object.keys(projectTypeData);
                const projectTypeValues = Object.values(projectTypeData);
                
                projectTypeChart = new Chart(projectTypeCtx, {
                    type: 'bar',
                    data: {
                        labels: projectTypeLabels,
                        datasets: [{
                            label: 'Jumlah Proyek',
                            data: projectTypeValues,
                            backgroundColor: '#36b9cc',
                            borderColor: '#36b9cc',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        }
        
        console.log('Charts initialized successfully');
        
    } catch (error) {
        console.error('Error initializing charts:', error);
        showAlert('error', 'Gagal memuat chart: ' + error.message);
    }
}

function generateDashboard() {
    try {
        // Check if CSRF token exists
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            showAlert('error', 'CSRF token tidak ditemukan. Silakan refresh halaman.');
            return;
        }

        // Show loading modal
        const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
        loadingModal.show();

        // Make API call to generate dashboard
        fetch('/looker-studio/generate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            loadingModal.hide();
            
            if (data.success) {
                // Store URL in localStorage for embedded display
                localStorage.setItem('lookerStudioDashboardUrl', data.url);
                
                // Show dashboard URL
                document.getElementById('dashboardUrl').value = data.url;
                document.getElementById('openDashboardBtn').href = data.url;
                document.getElementById('dashboardUrlSection').style.display = 'block';
                
                // Load the dashboard in the embedded iframe
                loadDashboard(data.url);
                
                // Show success message
                showAlert('success', 'Dashboard Looker Studio berhasil dibuat dan dimuat!');
            } else {
                showAlert('error', 'Gagal membuat dashboard: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            loadingModal.hide();
            console.error('Error generating dashboard:', error);
            showAlert('error', 'Terjadi kesalahan: ' + error.message);
        });
    } catch (error) {
        console.error('Error in generateDashboard:', error);
        showAlert('error', 'Terjadi kesalahan sistem: ' + error.message);
    }
}

function showDashboardUrl() {
    try {
        // Get current URL (custom or generated)
        fetch('/looker-studio/get-current-url', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Store URL in localStorage for embedded display
                localStorage.setItem('lookerStudioDashboardUrl', data.url);
                
                // Show dashboard URL
                document.getElementById('dashboardUrl').value = data.url;
                document.getElementById('openDashboardBtn').href = data.url;
                document.getElementById('dashboardUrlSection').style.display = 'block';
                
                // Load the dashboard in the embedded iframe
                loadDashboard(data.url);
                
                // Show success message
                const message = data.type === 'custom' ? 
                    'URL Looker Studio eksternal ditemukan dan dimuat!' : 
                    'URL Looker Studio baru dibuat dan dimuat!';
                showAlert('success', message);
            } else {
                showAlert('error', 'Gagal mendapatkan URL: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error getting current URL:', error);
            showAlert('error', 'Terjadi kesalahan: ' + error.message);
        });
    } catch (error) {
        console.error('Error in showDashboardUrl:', error);
        showAlert('error', 'Terjadi kesalahan sistem: ' + error.message);
    }
}

function setCustomUrl() {
    try {
        const customUrlInput = document.getElementById('customUrlInput');
        const customUrl = customUrlInput.value.trim();
        
        if (!customUrl) {
            showAlert('error', 'Silakan masukkan URL Looker Studio');
            return;
        }
        
        // Validate URL format
        if (!customUrl.startsWith('https://lookerstudio.google.com')) {
            showAlert('error', 'URL harus dari Looker Studio (https://lookerstudio.google.com)');
            return;
        }
        
        // Check if CSRF token exists
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            showAlert('error', 'CSRF token tidak ditemukan. Silakan refresh halaman.');
            return;
        }
        
        // Show loading indicator
        const setUrlBtn = document.querySelector('button[onclick="setCustomUrl()"]');
        const originalText = setUrlBtn.innerHTML;
        setUrlBtn.innerHTML = '<i class="bi bi-hourglass-split me-1 spin"></i> Setting...';
        setUrlBtn.disabled = true;
        
        console.log('Setting custom URL:', customUrl);
        
        // Make API call to set custom URL
        fetch('/looker-studio/set-custom-url', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content')
            },
            body: JSON.stringify({
                custom_url: customUrl
            })
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            // Reset button
            setUrlBtn.innerHTML = originalText;
            setUrlBtn.disabled = false;
            
            if (data.success) {
                // Store URL in localStorage for embedded display
                localStorage.setItem('lookerStudioDashboardUrl', data.url);
                console.log('URL stored in localStorage:', data.url);
                
                // Show dashboard URL
                document.getElementById('dashboardUrl').value = data.url;
                document.getElementById('openDashboardBtn').href = data.url;
                document.getElementById('dashboardUrlSection').style.display = 'block';
                
                // Load the dashboard in the embedded iframe
                console.log('Loading dashboard with URL:', data.url);
                loadDashboard(data.url);
                
                // Clear input
                customUrlInput.value = '';
                
                // Show success message
                showAlert('success', 'URL Looker Studio eksternal berhasil disimpan dan dimuat!');
            } else {
                showAlert('error', 'Gagal menyimpan URL: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            // Reset button
            setUrlBtn.innerHTML = originalText;
            setUrlBtn.disabled = false;
            
            console.error('Error setting custom URL:', error);
            showAlert('error', 'Terjadi kesalahan: ' + error.message);
        });
    } catch (error) {
        console.error('Error in setCustomUrl:', error);
        showAlert('error', 'Terjadi kesalahan sistem: ' + error.message);
    }
}

function checkExistingUrl() {
    try {
        // Check if there's an existing custom URL
        fetch('/looker-studio/get-current-url', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.type === 'custom') {
                // Show existing custom URL
                document.getElementById('dashboardUrl').value = data.url;
                document.getElementById('openDashboardBtn').href = data.url;
                document.getElementById('dashboardUrlSection').style.display = 'block';
                
                // Show info message
                showAlert('success', 'URL Looker Studio eksternal ditemukan dan siap digunakan!');
            }
        })
        .catch(error => {
            console.error('Error checking existing URL:', error);
            // Don't show error alert for this, as it's just a check
        });
    } catch (error) {
        console.error('Error in checkExistingUrl:', error);
        // Don't show error alert for this, as it's just a check
    }
}

function copyDashboardUrl() {
    try {
        const urlInput = document.getElementById('dashboardUrl');
        if (!urlInput) {
            showAlert('error', 'URL input tidak ditemukan');
            return;
        }
        
        if (!urlInput.value) {
            showAlert('error', 'URL belum dibuat. Silakan generate dashboard terlebih dahulu.');
            return;
        }
        
        urlInput.select();
        urlInput.setSelectionRange(0, 99999); // For mobile devices
        
        // Try modern clipboard API first
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(urlInput.value).then(() => {
                showAlert('success', 'URL berhasil disalin ke clipboard!');
            }).catch(() => {
                // Fallback to execCommand
                document.execCommand('copy');
                showAlert('success', 'URL berhasil disalin ke clipboard!');
            });
        } else {
            // Fallback for older browsers
            document.execCommand('copy');
            showAlert('success', 'URL berhasil disalin ke clipboard!');
        }
        
    } catch (error) {
        console.error('Error copying URL:', error);
        showAlert('error', 'Gagal menyalin URL: ' + error.message);
    }
}

function exportData(format) {
    try {
        // Show loading indicator
        const exportBtn = document.querySelector(`button[onclick="exportData('${format}')"]`);
        const originalText = exportBtn.innerHTML;
        exportBtn.innerHTML = '<i class="bi bi-hourglass-split me-1 spin"></i> Exporting...';
        exportBtn.disabled = true;
        
        const url = `/api/looker-studio/export?format=${format}&type=all`;
        
        if (format === 'csv') {
            // For CSV, use direct download with proper headers
            const link = document.createElement('a');
            link.href = url;
            link.download = `indismart_data_${new Date().toISOString().split('T')[0]}.csv`;
            link.style.display = 'none';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Reset button after a short delay
            setTimeout(() => {
                exportBtn.innerHTML = originalText;
                exportBtn.disabled = false;
                showAlert('success', 'Data CSV berhasil di-export!');
            }, 1000);
            
        } else if (format === 'all') {
            // Export all data as CSV
            const link = document.createElement('a');
            link.href = `/api/looker-studio/export?format=csv&type=all`;
            link.download = `indismart_all_data_${new Date().toISOString().split('T')[0]}.csv`;
            link.style.display = 'none';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Reset button after a short delay
            setTimeout(() => {
                exportBtn.innerHTML = originalText;
                exportBtn.disabled = false;
                showAlert('success', 'Semua data berhasil di-export sebagai CSV!');
            }, 1000);
        }
        
    } catch (error) {
        // Reset button
        const exportBtn = document.querySelector(`button[onclick="exportData('${format}')"]`);
        if (exportBtn) {
            exportBtn.innerHTML = originalText;
            exportBtn.disabled = false;
        }
        
        console.error('Error in exportData:', error);
        showAlert('error', 'Gagal export data: ' + error.message);
    }
}

function exportChartData(chartType) {
    try {
        const url = `/api/looker-studio/analytics?type=charts&chart_type=${chartType}`;
        
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const blob = new Blob([JSON.stringify(data.data, null, 2)], { type: 'application/json' });
                    const downloadUrl = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = downloadUrl;
                    a.download = `indismart_${chartType}_${new Date().toISOString().split('T')[0]}.json`;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    window.URL.revokeObjectURL(downloadUrl);
                    
                    showAlert('success', `Chart data ${chartType} berhasil di-export!`);
                } else {
                    showAlert('error', 'Gagal export chart data: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error exporting chart data:', error);
                showAlert('error', 'Gagal export chart data: ' + error.message);
            });
    } catch (error) {
        console.error('Error in exportChartData:', error);
        showAlert('error', 'Gagal export chart data: ' + error.message);
    }
}



function refreshData() {
    try {
        // Show loading indicator
        const refreshBtn = document.querySelector('button[onclick="refreshData()"]');
        if (refreshBtn) {
            const originalText = refreshBtn.innerHTML;
            refreshBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-1 spin"></i> Refreshing...';
            refreshBtn.disabled = true;
            
            // Reload page to refresh all data
            setTimeout(() => {
                window.location.reload();
            }, 500);
        } else {
            window.location.reload();
        }
    } catch (error) {
        console.error('Error refreshing data:', error);
        showAlert('error', 'Gagal refresh data: ' + error.message);
    }
}

function loadRealTimeData() {
    // Load real-time data every 30 seconds
    setInterval(() => {
        try {
            fetch('/api/looker-studio/realtime')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Update summary cards
                        const totalMitra = document.getElementById('totalMitra');
                        const totalDokumen = document.getElementById('totalDokumen');
                        const proyekAktif = document.getElementById('proyekAktif');
                        const totalFoto = document.getElementById('totalFoto');
                        
                        if (totalMitra) totalMitra.textContent = data.data.summary.total_mitra;
                        if (totalDokumen) totalDokumen.textContent = data.data.summary.total_dokumen;
                        if (proyekAktif) proyekAktif.textContent = data.data.summary.total_dokumen;
                        if (totalFoto) totalFoto.textContent = data.data.summary.total_foto;
                    }
                })
                .catch(error => {
                    console.error('Error loading real-time data:', error);
                });
        } catch (error) {
            console.error('Error in loadRealTimeData:', error);
        }
    }, 30000);
}

function showAlert(type, message) {
    try {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = type === 'success' ? 'check-circle' : 'exclamation-triangle';
        
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="bi bi-${iconClass} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Insert alert at the top of the container
        const container = document.querySelector('.container-fluid');
        if (container) {
            container.insertAdjacentHTML('afterbegin', alertHtml);
            
            // Auto dismiss after 5 seconds
            setTimeout(() => {
                const alert = document.querySelector('.alert');
                if (alert) {
                    try {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    } catch (error) {
                        console.error('Error closing alert:', error);
                        alert.remove();
                    }
                }
            }, 5000);
        } else {
            console.error('Container not found for alert');
        }
    } catch (error) {
        console.error('Error showing alert:', error);
        // Fallback to simple alert
        alert(`${type.toUpperCase()}: ${message}`);
    }
}

// Error handling untuk Looker Studio
function handleLookerStudioError(errorType, originalUrl) {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            showAlert('error', 'CSRF token tidak ditemukan. Silakan refresh halaman.');
            return;
        }
        
        // Show loading indicator
        showAlert('info', 'Mencari solusi untuk error Looker Studio...');
        
        fetch('/looker-studio/handle-error', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content')
            },
            body: JSON.stringify({
                error_type: errorType,
                original_url: originalUrl
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showErrorSolution(data.error_info, data.alternative_url);
            } else {
                showAlert('error', 'Gagal mendapatkan solusi: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error handling Looker Studio error:', error);
            showAlert('error', 'Terjadi kesalahan: ' + error.message);
        });
    } catch (error) {
        console.error('Error in handleLookerStudioError:', error);
        showAlert('error', 'Terjadi kesalahan sistem: ' + error.message);
    }
}

function showErrorSolution(errorInfo, alternativeUrl) {
    try {
        const solutionHtml = `
            <div class="modal fade" id="errorSolutionModal" tabindex="-1" aria-labelledby="errorSolutionModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="errorSolutionModalLabel">
                                <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                                ${errorInfo.title}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Deskripsi Error:</strong> ${errorInfo.description}
                            </div>
                            
                            <h6><i class="bi bi-lightbulb me-2"></i>Solusi yang Tersedia:</h6>
                            <ul class="list-group list-group-flush mb-3">
                                ${errorInfo.solutions.map(solution => `
                                    <li class="list-group-item">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        ${solution}
                                    </li>
                                `).join('')}
                            </ul>
                            
                            <div class="alert alert-info">
                                <i class="bi bi-link-45deg me-2"></i>
                                <strong>URL Alternatif:</strong>
                                <a href="${alternativeUrl}" target="_blank" class="btn btn-sm btn-outline-primary ms-2">
                                    <i class="bi bi-box-arrow-up-right me-1"></i>
                                    Buka URL Alternatif
                                </a>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="button" class="btn btn-primary" onclick="openAlternativeUrl('${alternativeUrl}')">
                                <i class="bi bi-box-arrow-up-right me-1"></i>
                                Buka Dashboard Alternatif
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Remove existing modal if any
        const existingModal = document.getElementById('errorSolutionModal');
        if (existingModal) {
            existingModal.remove();
        }
        
        // Add modal to body
        document.body.insertAdjacentHTML('beforeend', solutionHtml);
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('errorSolutionModal'));
        modal.show();
        
    } catch (error) {
        console.error('Error showing error solution:', error);
        showAlert('error', 'Gagal menampilkan solusi: ' + error.message);
    }
}

function openAlternativeUrl(url) {
    try {
        window.open(url, '_blank');
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('errorSolutionModal'));
        if (modal) {
            modal.hide();
        }
        
        showAlert('success', 'Dashboard alternatif dibuka di tab baru!');
        
    } catch (error) {
        console.error('Error opening alternative URL:', error);
        showAlert('error', 'Gagal membuka URL alternatif: ' + error.message);
    }
}

// Auto-detect Looker Studio errors
function detectLookerStudioError() {
    try {
        // Check if we're on a Looker Studio error page
        if (window.location.href.includes('lookerstudio.google.com')) {
            const errorElements = document.querySelectorAll('[class*="error"], [id*="error"]');
            const errorText = document.body.textContent.toLowerCase();
            
            if (errorText.includes('tidak dibagikan') || errorText.includes('not shared')) {
                handleLookerStudioError('permission', window.location.href);
            } else if (errorText.includes('data source') || errorText.includes('datasource')) {
                handleLookerStudioError('data_source', window.location.href);
            } else if (errorText.includes('template') || errorText.includes('report')) {
                handleLookerStudioError('template', window.location.href);
            }
        }
    } catch (error) {
        console.error('Error detecting Looker Studio error:', error);
    }
}

// Run error detection on page load
document.addEventListener('DOMContentLoaded', function() {
    detectLookerStudioError();
    // Initialize dashboard display will be called from the main DOMContentLoaded
});

// Dashboard Display Functions
function initializeDashboardDisplay() {
    try {
        console.log('Initializing dashboard display...');
        
        // Check if there's a stored dashboard URL
        const storedUrl = localStorage.getItem('lookerStudioDashboardUrl');
        if (storedUrl) {
            console.log('Found stored dashboard URL:', storedUrl);
            loadDashboard(storedUrl);
        } else {
            console.log('No stored dashboard URL found, showing no dashboard state');
            showNoDashboardState();
        }
        
    } catch (error) {
        console.error('Error initializing dashboard display:', error);
        showDashboardError('Gagal memuat dashboard: ' + error.message);
    }
}

// Enhanced URL validation and processing
function validateAndProcessUrl(url) {
    try {
        console.log('Validating and processing URL:', url);
        
        // Check if URL is the specific problematic URL
        if (url.includes('42b284f8-7290-4fc3-a7e5-0d9d8129826f')) {
            console.log('Detected specific URL that may require authentication');
            return {
                isValid: true,
                requiresAuth: true,
                embedUrl: `https://lookerstudio.google.com/embed/reporting/42b284f8-7290-4fc3-a7e5-0d9d8129826f`,
                originalUrl: url,
                message: 'URL terdeteksi memerlukan autentikasi Google. Gunakan tombol "Buka di Tab Baru" untuk login.'
            };
        }
        
        // Regular URL processing
        if (!url || !url.includes('lookerstudio.google.com')) {
            return {
                isValid: false,
                message: 'URL tidak valid. URL harus dari Looker Studio.'
            };
        }
        
        const embedUrl = convertToEmbedUrl(url);
        
        return {
            isValid: true,
            requiresAuth: false,
            embedUrl: embedUrl,
            originalUrl: url,
            message: 'URL berhasil diproses.'
        };
        
    } catch (error) {
        console.error('Error validating URL:', error);
        return {
            isValid: false,
            message: 'Error memproses URL: ' + error.message
        };
    }
}

// Enhanced loadDashboard with better error handling
function loadDashboard(url) {
    try {
        console.log('Loading dashboard with URL:', url);
        
        // Show loading state
        showDashboardLoading();
        
        // Validate and process URL
        const urlInfo = validateAndProcessUrl(url);
        
        if (!urlInfo.isValid) {
            throw new Error(urlInfo.message);
        }
        
        // Store URL in localStorage for persistence
        localStorage.setItem('lookerStudioDashboardUrl', url);
        
        // If URL requires authentication, show special handling
        if (urlInfo.requiresAuth) {
            console.log('URL requires authentication, showing special handling');
            showAuthenticationRequiredState(urlInfo);
            return;
        }
        
        // Check if URL can be embedded
        if (urlInfo.embedUrl.includes('/reporting/create')) {
            console.log('URL cannot be embedded, showing alternative options');
            showDashboardError('URL ini tidak dapat di-embed karena merupakan halaman pembuatan dashboard. Gunakan tombol "Buka di Tab Baru" atau "Buat Laporan Baru" untuk membuat dashboard yang dapat di-embed.');
            
            // Add button to open in new tab
            const openInNewTabBtn = document.createElement('button');
            openInNewTabBtn.className = 'btn btn-success mt-2';
            openInNewTabBtn.innerHTML = '<i class="bi bi-box-arrow-up-right me-2"></i>Buka di Tab Baru';
            openInNewTabBtn.onclick = function() {
                window.open(url, '_blank');
            };
            
            const errorElement = document.getElementById('dashboardError');
            if (errorElement) {
                const existingBtn = errorElement.querySelector('.btn-success');
                if (!existingBtn) {
                    errorElement.appendChild(openInNewTabBtn);
                }
            }
            return;
        }
        
        // Set iframe source
        const iframe = document.getElementById('lookerStudioFrame');
        if (iframe) {
            console.log('Setting iframe src to:', urlInfo.embedUrl);
            
            // Set loading timeout - reduced to 5 seconds for faster feedback
            const loadingTimeout = setTimeout(() => {
                console.log('Loading timeout reached, showing error');
                handleIframeError();
            }, 5000); // 5 seconds timeout for faster feedback
            
            // Clear any existing src to force reload
            iframe.src = '';
            
            // Set new src
            iframe.src = urlInfo.embedUrl;
            
            // Handle iframe load events
            iframe.onload = function() {
                console.log('Dashboard iframe loaded successfully');
                clearTimeout(loadingTimeout); // Clear timeout on successful load
                
                // Check if the iframe content contains error messages
                try {
                    // Wait a bit for content to load
                    setTimeout(() => {
                        const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                        const iframeContent = iframeDoc.body ? iframeDoc.body.textContent : '';
                        
                        console.log('Checking iframe content for errors...');
                        console.log('Iframe content length:', iframeContent.length);
                        
                        // Check for common Looker Studio error messages including authentication
                        if (iframeContent.includes('Tidak dapat mengakses laporan') || 
                            iframeContent.includes('Cannot access report') ||
                            iframeContent.includes('tidak dibagikan') ||
                            iframeContent.includes('not shared') ||
                            iframeContent.includes('dinonaktifkan oleh pemilik') ||
                            iframeContent.includes('disabled by owner') ||
                            iframeContent.includes('access denied') ||
                            iframeContent.includes('permission denied') ||
                            iframeContent.includes('loading') ||
                            iframeContent.includes('Loading') ||
                            iframeContent.includes('error') ||
                            iframeContent.includes('Error') ||
                            iframeContent.includes('failed') ||
                            iframeContent.includes('Failed') ||
                            iframeContent.includes('Sign in') ||
                            iframeContent.includes('sign in') ||
                            iframeContent.includes('Sign In') ||
                            iframeContent.includes('Login') ||
                            iframeContent.includes('login') ||
                            iframeContent.includes('Masuk') ||
                            iframeContent.includes('masuk')) {
                            
                            console.log('Looker Studio embedding error detected in content');
                            console.log('Error content found:', iframeContent.substring(0, 200));
                            
                            // Check if it's an authentication issue
                            if (iframeContent.includes('Sign in') || iframeContent.includes('Sign In') || 
                                iframeContent.includes('Login') || iframeContent.includes('Masuk')) {
                                console.log('Authentication required detected');
                                handleAuthenticationError();
                            } else {
                                handleIframeError();
                            }
                        } else {
                            console.log('No errors detected, showing dashboard embed');
                            showDashboardEmbed();
                        }
                    }, 3000); // Wait 3 seconds to check content
                } catch (e) {
                    // If we can't access iframe content due to CORS, check iframe dimensions
                    console.log('Cannot access iframe content due to CORS, checking iframe dimensions');
                    
                    // Check if iframe has loaded content by checking its dimensions
                    setTimeout(() => {
                        const iframeRect = iframe.getBoundingClientRect();
                        const iframeHeight = iframeRect.height;
                        const iframeWidth = iframeRect.width;
                        
                        console.log('Iframe dimensions:', iframeWidth, 'x', iframeHeight);
                        
                        // If iframe has reasonable dimensions, assume it loaded successfully
                        if (iframeHeight > 100 && iframeWidth > 100) {
                            console.log('Iframe has reasonable dimensions, assuming success');
                            showDashboardEmbed();
                        } else {
                            console.log('Iframe dimensions too small, showing error');
                            handleIframeError();
                        }
                    }, 2000);
                }
            };
            
            iframe.onerror = function() {
                console.error('Dashboard iframe failed to load');
                clearTimeout(loadingTimeout);
                handleIframeError();
            };
        } else {
            throw new Error('Dashboard iframe tidak ditemukan');
        }
        
    } catch (error) {
        console.error('Error loading dashboard:', error);
        showDashboardError(error.message);
    }
}

function convertToEmbedUrl(url) {
    try {
        console.log('Converting URL to embed format:', url);
        
        // If URL is already an embed URL, return as is
        if (url.includes('/embed')) {
            console.log('URL is already embed format');
            return url;
        }
        
        // Handle URLs with page parameter (like the user's URL)
        if (url.includes('/page/')) {
            console.log('URL contains page parameter, extracting report ID...');
            // Extract report ID from URL like: /reporting/42b284f8-7290-4fc3-a7e5-0d9d8129826f/page/p_sukiqbeasd
            const reportIdMatch = url.match(/\/reporting\/([^\/]+)/);
            if (reportIdMatch) {
                const reportId = reportIdMatch[1];
                if (reportId && reportId !== 'create') {
                    const embedUrl = `https://lookerstudio.google.com/embed/reporting/${reportId}`;
                    console.log('Converted page URL to embed URL:', embedUrl);
                    return embedUrl;
                }
            }
        }
        
        // Convert regular Looker Studio URL to embed URL
        if (url.includes('/reporting/')) {
            // Extract report ID from URL
            const reportIdMatch = url.match(/\/reporting\/([^\/\?]+)/);
            if (reportIdMatch) {
                const reportId = reportIdMatch[1];
                // Check if it's a create URL
                if (reportId === 'create') {
                    console.log('URL is create URL, cannot embed');
                    return url;
                }
                const embedUrl = `https://lookerstudio.google.com/embed/reporting/${reportId}`;
                console.log('Converted to embed URL:', embedUrl);
                return embedUrl;
            }
        }
        
        // Handle other Looker Studio URLs
        if (url.includes('lookerstudio.google.com')) {
            // Try to extract any report ID from the URL
            const urlParts = url.split('/');
            const reportIndex = urlParts.findIndex(part => part === 'reporting');
            if (reportIndex !== -1 && reportIndex + 1 < urlParts.length) {
                const reportId = urlParts[reportIndex + 1];
                if (reportId && reportId !== 'create') {
                    const embedUrl = `https://lookerstudio.google.com/embed/reporting/${reportId}`;
                    console.log('Converted to embed URL:', embedUrl);
                    return embedUrl;
                }
            }
        }
        
        // For create URLs, return as is (they can't be embedded)
        if (url.includes('/reporting/create')) {
            console.log('URL is create URL, cannot embed');
            return url;
        }
        
        // If we can't convert, return original URL
        console.log('Could not convert URL, returning original');
        return url;
        
    } catch (error) {
        console.error('Error converting to embed URL:', error);
        return url;
    }
}

function refreshDashboard() {
    try {
        console.log('Refreshing dashboard...');
        
        const storedUrl = localStorage.getItem('lookerStudioDashboardUrl');
        if (storedUrl) {
            loadDashboard(storedUrl);
        } else {
            showNoDashboardState();
        }
        
        showAlert('success', 'Dashboard berhasil di-refresh!');
        
    } catch (error) {
        console.error('Error refreshing dashboard:', error);
        showAlert('error', 'Gagal refresh dashboard: ' + error.message);
    }
}

function toggleFullscreen() {
    try {
        const iframe = document.getElementById('lookerStudioFrame');
        if (iframe) {
            if (iframe.requestFullscreen) {
                iframe.requestFullscreen();
            } else if (iframe.webkitRequestFullscreen) {
                iframe.webkitRequestFullscreen();
            } else if (iframe.msRequestFullscreen) {
                iframe.msRequestFullscreen();
            }
        }
    } catch (error) {
        console.error('Error toggling fullscreen:', error);
        showAlert('error', 'Gagal masuk mode fullscreen: ' + error.message);
    }
}

function showDashboardLoading() {
    hideAllDashboardStates();
    document.getElementById('dashboardLoading').style.display = 'block';
    
    // Set a timeout to detect stuck loading
    setTimeout(() => {
        const loadingElement = document.getElementById('dashboardLoading');
        if (loadingElement && loadingElement.style.display === 'block') {
            console.log('Loading stuck detected, showing timeout message');
            showDashboardError('Dashboard mengalami masalah loading yang lama. Silakan coba buka di tab baru atau refresh halaman.');
        }
    }, 8000); // 8 seconds timeout for loading
}

function showDashboardEmbed() {
    hideAllDashboardStates();
    document.getElementById('dashboardEmbed').style.display = 'block';
    
    // Add success message
    const embedElement = document.getElementById('dashboardEmbed');
    if (embedElement) {
        const existingSuccess = embedElement.querySelector('.alert-success');
        if (!existingSuccess) {
            const successMessage = document.createElement('div');
            successMessage.className = 'alert alert-success mb-3';
            successMessage.innerHTML = `
                <i class="bi bi-check-circle me-2"></i>
                <strong>Berhasil!</strong> Dashboard Looker Studio berhasil di-embed. Jika mengalami masalah akses, gunakan tombol "Buka di Tab Baru" di bawah.
            `;
            
            // Insert before the iframe
            const iframe = embedElement.querySelector('iframe');
            if (iframe) {
                iframe.parentNode.insertBefore(successMessage, iframe);
            }
        }
    }
    
    // Add debug information
    const storedUrl = localStorage.getItem('lookerStudioDashboardUrl');
    if (storedUrl) {
        console.log('Dashboard embed shown for URL:', storedUrl);
        
        // Add debug info to the page
        const debugInfo = document.createElement('div');
        debugInfo.className = 'alert alert-info mt-2';
        debugInfo.innerHTML = `
            <small>
                <i class="bi bi-info-circle me-1"></i>
                <strong>Debug Info:</strong> URL: ${storedUrl}
                <br>
                <i class="bi bi-clock me-1"></i>
                <strong>Loaded at:</strong> ${new Date().toLocaleString()}
            </small>
        `;
        
        const iframe = embedElement.querySelector('iframe');
        if (iframe) {
            iframe.parentNode.insertBefore(debugInfo, iframe.nextSibling);
        }
    }
    
    // Add error detection for embedded dashboard
    setTimeout(() => {
        const iframe = document.getElementById('lookerStudioFrame');
        if (iframe) {
            try {
                // Check if iframe is actually showing content
                const iframeRect = iframe.getBoundingClientRect();
                if (iframeRect.height < 100 || iframeRect.width < 100) {
                    console.log('Iframe dimensions too small, possible error');
                    // Don't show error immediately, just log for debugging
                }
            } catch (e) {
                console.log('Error checking iframe dimensions:', e);
            }
        }
    }, 5000); // Check after 5 seconds
}

function showNoDashboardState() {
    hideAllDashboardStates();
    document.getElementById('noDashboardState').style.display = 'block';
    
    // Add helpful message
    const noDashboardElement = document.getElementById('noDashboardState');
    if (noDashboardElement) {
        const existingMessage = noDashboardElement.querySelector('.alert-info');
        if (!existingMessage) {
            const infoMessage = document.createElement('div');
            infoMessage.className = 'alert alert-info mt-3';
            infoMessage.innerHTML = `
                <h6><i class="bi bi-info-circle me-2"></i>Tips untuk Dashboard Pertama:</h6>
                <ul class="mb-0">
                    <li>Klik "Generate Dashboard" untuk membuat dashboard otomatis</li>
                    <li>Atau klik "Buat Laporan Baru" untuk membuat dashboard manual</li>
                    <li>Setelah dashboard dibuat, URL akan disimpan untuk penggunaan selanjutnya</li>
                </ul>
            `;
            noDashboardElement.appendChild(infoMessage);
        }
    }
}

function showDashboardError(message) {
    hideAllDashboardStates();
    const errorElement = document.getElementById('dashboardError');
    const errorMessageElement = document.getElementById('errorMessage');
    
    if (errorMessageElement) {
        errorMessageElement.textContent = message;
    }
    
    errorElement.style.display = 'block';
}

function hideAllDashboardStates() {
    const states = ['dashboardLoading', 'dashboardEmbed', 'noDashboardState', 'dashboardError', 'authRequiredState'];
    states.forEach(stateId => {
        const element = document.getElementById(stateId);
        if (element) {
            element.style.display = 'none';
        }
    });
}

function showCustomUrlInput() {
    const customUrlSection = document.getElementById('customUrlSection');
    if (customUrlSection) {
        customUrlSection.style.display = 'block';
    }
}

// Handle embedding issues
function openDashboardInNewTab() {
    try {
        const storedUrl = localStorage.getItem('lookerStudioDashboardUrl');
        if (storedUrl) {
            window.open(storedUrl, '_blank');
            showAlert('success', 'Dashboard dibuka di tab baru!');
        } else {
            showAlert('error', 'Tidak ada URL dashboard yang tersimpan.');
        }
    } catch (error) {
        console.error('Error opening dashboard in new tab:', error);
        showAlert('error', 'Gagal membuka dashboard: ' + error.message);
    }
}

function showEmbeddingHelp() {
    try {
        const helpHtml = `
        <div class="modal fade" id="embeddingHelpModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-question-circle me-2"></i>
                            Bantuan Embedding Looker Studio
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <h6><i class="bi bi-exclamation-triangle me-2"></i>Mengapa Dashboard Embedding Error?</h6>
                            <p class="mb-0">Looker Studio memiliki kebijakan keamanan yang ketat untuk embedding. Dashboard mungkin mengalami masalah karena:</p>
                        </div>
                        
                        <h6>Penyebab Umum Error Embedding:</h6>
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item">
                                <i class="bi bi-lock text-danger me-2"></i>
                                <strong>Embedding Dinonaktifkan:</strong> Pemilik dashboard menonaktifkan opsi embedding
                            </li>
                            <li class="list-group-item">
                                <i class="bi bi-shield text-danger me-2"></i>
                                <strong>Domain Tidak Diizinkan:</strong> Domain aplikasi tidak ada dalam whitelist
                            </li>
                            <li class="list-group-item">
                                <i class="bi bi-gear text-warning me-2"></i>
                                <strong>Pengaturan Sharing:</strong> Dashboard tidak dibagikan dengan pengguna
                            </li>
                            <li class="list-group-item">
                                <i class="bi bi-link text-warning me-2"></i>
                                <strong>URL Format Salah:</strong> Menggunakan URL regular bukan embed URL
                            </li>
                            <li class="list-group-item">
                                <i class="bi bi-clock text-warning me-2"></i>
                                <strong>Loading Timeout:</strong> Dashboard membutuhkan waktu loading yang lama
                            </li>
                            <li class="list-group-item">
                                <i class="bi bi-wifi text-warning me-2"></i>
                                <strong>Koneksi Internet:</strong> Masalah koneksi internet atau firewall
                            </li>
                            <li class="list-group-item">
                                <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                                <strong>CORS Policy:</strong> Browser memblokir cross-origin requests
                            </li>
                        </ul>
                        
                        <h6>Solusi yang Tersedia:</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="bi bi-box-arrow-up-right text-primary me-2"></i>
                                            Buka di Tab Baru
                                        </h6>
                                        <p class="card-text small">Bypass masalah embedding dengan membuka dashboard di tab terpisah.</p>
                                        <button type="button" class="btn btn-sm btn-primary" onclick="openDashboardInNewTab()">
                                            Buka Dashboard
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="bi bi-gear text-success me-2"></i>
                                            Konfigurasi Manual
                                        </h6>
                                        <p class="card-text small">Masukkan URL embed yang sudah dikonfigurasi dengan benar.</p>
                                        <button type="button" class="btn btn-sm btn-success" onclick="showCustomUrlInput()">
                                            Masukkan URL
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mt-3">
                            <h6><i class="bi bi-lightbulb me-2"></i>Tips untuk Dashboard Creator:</h6>
                            <ol class="mb-0">
                                <li>Buka dashboard di Looker Studio</li>
                                <li>Klik tombol "Share" di pojok kanan atas</li>
                                <li>Pilih "Embed" dari menu dropdown</li>
                                <li>Aktifkan opsi "Allow embedding"</li>
                                <li>Salin URL embed yang muncul</li>
                                <li>Masukkan URL embed ke aplikasi ini</li>
                            </ol>
                        </div>
                        
                        <div class="alert alert-warning mt-3">
                            <h6><i class="bi bi-exclamation-triangle me-2"></i>Penting:</h6>
                            <p class="mb-0">Jika dashboard sudah di-embed tetapi masih error, kemungkinan ada masalah dengan pengaturan sharing atau domain. Gunakan tombol "Buka di Tab Baru" sebagai solusi terbaik.</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" onclick="openDashboardInNewTab()">
                            <i class="bi bi-box-arrow-up-right me-2"></i>
                            Buka Dashboard
                        </button>
                        <button type="button" class="btn btn-success" onclick="showCustomUrlInput()">
                            <i class="bi bi-gear me-2"></i>
                            Konfigurasi Manual
                        </button>
                    </div>
                </div>
            </div>
        </div>
        `;
        
        // Remove existing modal if any
        const existingModal = document.getElementById('embeddingHelpModal');
        if (existingModal) {
            existingModal.remove();
        }
        
        // Add modal to body
        document.body.insertAdjacentHTML('beforeend', helpHtml);
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('embeddingHelpModal'));
        modal.show();
        
    } catch (error) {
        console.error('Error showing embedding help:', error);
        showAlert('error', 'Gagal menampilkan bantuan: ' + error.message);
    }
}

function showAuthenticationHelp() {
    try {
        const helpHtml = `
        <div class="modal fade" id="authenticationHelpModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-shield-lock me-2"></i>
                            Bantuan Autentikasi Looker Studio
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <h6><i class="bi bi-exclamation-triangle me-2"></i>Mengapa Dashboard Memerlukan Login?</h6>
                            <p class="mb-0">Dashboard Looker Studio memerlukan autentikasi Google karena:</p>
                        </div>
                        
                        <h6>Penyebab Autentikasi Diperlukan:</h6>
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item">
                                <i class="bi bi-person text-primary me-2"></i>
                                <strong>Dashboard Private:</strong> Dashboard hanya dapat diakses oleh pemilik atau user yang diizinkan
                            </li>
                            <li class="list-group-item">
                                <i class="bi bi-shield text-warning me-2"></i>
                                <strong>Pengaturan Sharing:</strong> Dashboard tidak dibagikan secara publik
                            </li>
                            <li class="list-group-item">
                                <i class="bi bi-gear text-info me-2"></i>
                                <strong>Organisasi Google:</strong> Dashboard berada dalam workspace Google yang memerlukan login
                            </li>
                            <li class="list-group-item">
                                <i class="bi bi-lock text-danger me-2"></i>
                                <strong>Embedding Terbatas:</strong> Dashboard tidak diizinkan untuk embedding tanpa autentikasi
                            </li>
                        </ul>
                        
                                                 <h6>Solusi untuk Masalah Autentikasi:</h6>
                         <div class="row">
                             <div class="col-md-12">
                                 <div class="card">
                                     <div class="card-body">
                                         <h6 class="card-title">
                                             <i class="bi bi-box-arrow-up-right text-success me-2"></i>
                                             Buka di Tab Baru
                                         </h6>
                                         <p class="card-text small">Buka dashboard di tab baru untuk proses login yang lebih mudah.</p>
                                         <button type="button" class="btn btn-sm btn-success" onclick="openDashboardInNewTab()">
                                             Buka Dashboard
                                         </button>
                                     </div>
                                 </div>
                             </div>
                         </div>
                        
                        <div class="alert alert-info mt-3">
                            <h6><i class="bi bi-lightbulb me-2"></i>Tips untuk Mengatasi Masalah Autentikasi:</h6>
                            <ol class="mb-0">
                                <li>Pastikan Anda sudah login ke akun Google yang benar</li>
                                <li>Hubungi pemilik dashboard untuk memberikan akses</li>
                                <li>Minta pemilik dashboard untuk mengubah pengaturan sharing menjadi "Anyone with the link can view"</li>
                                <li>Jika dashboard berada dalam workspace, pastikan Anda adalah anggota workspace tersebut</li>
                                <li>Gunakan tombol "Buka di Tab Baru" untuk proses login yang lebih mudah</li>
                                <li>Setelah login berhasil, coba refresh halaman aplikasi</li>
                            </ol>
                        </div>
                        
                        <div class="alert alert-warning">
                            <h6><i class="bi bi-exclamation-triangle me-2"></i>Penting:</h6>
                            <p class="mb-0">Jika dashboard tetap memerlukan autentikasi setelah login, kemungkinan dashboard memang tidak diizinkan untuk embedding atau memerlukan permission khusus dari pemilik dashboard.</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" onclick="window.open('https://lookerstudio.google.com/reporting/create', '_blank')">
                            <i class="bi bi-plus-circle me-2"></i>Buat Dashboard Baru
                        </button>
                    </div>
                </div>
            </div>
        </div>`;
        
        // Remove existing modal if any
        const existingModal = document.getElementById('authenticationHelpModal');
        if (existingModal) {
            existingModal.remove();
        }
        
        // Add modal to body
        document.body.insertAdjacentHTML('beforeend', helpHtml);
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('authenticationHelpModal'));
        modal.show();
        
        // Remove modal from DOM after hiding
        setTimeout(() => {
            const modal = document.getElementById('authenticationHelpModal');
            if (modal) {
                modal.remove();
            }
        }, 300);
    } catch (error) {
        console.error('Error showing authentication help:', error);
    }
}

// Enhanced iframe error handling
function handleIframeError() {
    try {
        console.log('Iframe error detected, showing alternative options');
        
        // Get the current URL to determine the type of error
        const storedUrl = localStorage.getItem('lookerStudioDashboardUrl');
        let errorMessage = 'Dashboard tidak dapat dimuat atau mengalami masalah loading. Silakan coba salah satu opsi di bawah ini.';
        
        // Check if it's a specific type of error
        if (storedUrl) {
            if (storedUrl.includes('/reporting/create')) {
                errorMessage = 'URL ini adalah halaman pembuatan dashboard yang tidak dapat di-embed. Gunakan tombol "Buat Laporan Baru" untuk membuat dashboard yang dapat di-embed.';
            } else if (storedUrl.includes('/embed/')) {
                errorMessage = 'Dashboard embed mengalami masalah. Kemungkinan ada masalah dengan pengaturan sharing atau domain. Gunakan tombol "Buka di Tab Baru" sebagai alternatif.';
            } else {
                errorMessage = 'Dashboard tidak dapat di-embed. Kemungkinan ada masalah dengan URL atau pengaturan sharing. Silakan coba salah satu opsi di bawah ini.';
            }
        }
        
        // Show error state with specific message
        showDashboardError(errorMessage);
        
        // Show help button
        const helpButton = document.createElement('button');
        helpButton.className = 'btn btn-outline-info mt-2';
        helpButton.innerHTML = '<i class="bi bi-question-circle me-2"></i>Bantuan Embedding';
        helpButton.onclick = showEmbeddingHelp;
        
        // Show retry button
        const retryButton = document.createElement('button');
        retryButton.className = 'btn btn-outline-warning mt-2 ms-2';
        retryButton.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Coba Lagi';
        retryButton.onclick = function() {
            if (storedUrl) {
                loadDashboard(storedUrl);
            } else {
                showNoDashboardState();
            }
        };
        
        // Show create new report button
        const createNewBtn = document.createElement('button');
        createNewBtn.className = 'btn btn-success mt-2 ms-2';
        createNewBtn.innerHTML = '<i class="bi bi-file-earmark-plus me-2"></i>Buat Laporan Baru';
        createNewBtn.onclick = createNewReport;
        
        // Show open in new tab button
        const openInNewTabBtn = document.createElement('button');
        openInNewTabBtn.className = 'btn btn-primary mt-2 ms-2';
        openInNewTabBtn.innerHTML = '<i class="bi bi-box-arrow-up-right me-2"></i>Buka di Tab Baru';
        openInNewTabBtn.onclick = function() {
            if (storedUrl) {
                window.open(storedUrl, '_blank');
            } else {
                window.open('https://lookerstudio.google.com/reporting/create', '_blank');
            }
        };
        
        // Show clear cache button
        const clearCacheBtn = document.createElement('button');
        clearCacheBtn.className = 'btn btn-outline-danger mt-2 ms-2';
        clearCacheBtn.innerHTML = '<i class="bi bi-trash me-2"></i>Hapus Cache';
        clearCacheBtn.onclick = function() {
            localStorage.removeItem('lookerStudioDashboardUrl');
            showAlert('success', 'Cache berhasil dihapus. Silakan generate dashboard baru.');
            showNoDashboardState();
        };
        
        const errorElement = document.getElementById('dashboardError');
        if (errorElement) {
            const existingHelp = errorElement.querySelector('.btn-outline-info');
            const existingRetry = errorElement.querySelector('.btn-outline-warning');
            const existingCreate = errorElement.querySelector('.btn-success');
            const existingOpen = errorElement.querySelector('.btn-primary');
            const existingClear = errorElement.querySelector('.btn-outline-danger');
            
            if (!existingHelp) {
                errorElement.appendChild(helpButton);
            }
            if (!existingRetry) {
                errorElement.appendChild(retryButton);
            }
            if (!existingCreate) {
                errorElement.appendChild(createNewBtn);
            }
            if (!existingOpen) {
                errorElement.appendChild(openInNewTabBtn);
            }
            if (!existingClear) {
                errorElement.appendChild(clearCacheBtn);
            }
        }
        
    } catch (error) {
        console.error('Error handling iframe error:', error);
    }
}

// Handle authentication errors specifically
function handleAuthenticationError() {
    try {
        console.log('Handling authentication error...');
        
        const storedUrl = localStorage.getItem('lookerStudioDashboardUrl');
        const errorMessage = 'Dashboard memerlukan autentikasi Google. Silakan login ke akun Google Anda terlebih dahulu atau gunakan opsi di bawah ini.';
        
        showDashboardError(errorMessage);
        
        // Add authentication-specific buttons
        const errorElement = document.getElementById('dashboardError');
        if (errorElement) {
            // Clear existing buttons
            const existingButtons = errorElement.querySelectorAll('.btn');
            existingButtons.forEach(btn => btn.remove());
            
            // Add new buttons
            const buttonContainer = document.createElement('div');
            buttonContainer.className = 'mt-3 d-flex gap-2 flex-wrap';
            
            // Open in new tab button (for authentication)
            if (storedUrl) {
                const openInNewTabBtn = document.createElement('button');
                openInNewTabBtn.className = 'btn btn-success';
                openInNewTabBtn.innerHTML = '<i class="bi bi-box-arrow-up-right me-2"></i>Buka di Tab Baru (Login)';
                openInNewTabBtn.onclick = function() {
                    window.open(storedUrl, '_blank');
                };
                buttonContainer.appendChild(openInNewTabBtn);
            }
            
            
            
            // Create new report button
            const createNewBtn = document.createElement('button');
            createNewBtn.className = 'btn btn-info';
            createNewBtn.innerHTML = '<i class="bi bi-plus-circle me-2"></i>Buat Laporan Baru';
            createNewBtn.onclick = function() {
                window.open('https://lookerstudio.google.com/reporting/create', '_blank');
            };
            buttonContainer.appendChild(createNewBtn);
            
            // Clear cache button
            const clearCacheBtn = document.createElement('button');
            clearCacheBtn.className = 'btn btn-warning';
            clearCacheBtn.innerHTML = '<i class="bi bi-trash me-2"></i>Hapus Cache';
            clearCacheBtn.onclick = function() {
                localStorage.removeItem('lookerStudioDashboardUrl');
                showNoDashboardState();
                showAlert('success', 'Cache berhasil dihapus. Silakan coba lagi.');
            };
            buttonContainer.appendChild(clearCacheBtn);
            
            // Help button
            const helpBtn = document.createElement('button');
            helpBtn.className = 'btn btn-secondary';
            helpBtn.innerHTML = '<i class="bi bi-question-circle me-2"></i>Bantuan';
            helpBtn.onclick = function() {
                showAuthenticationHelp();
            };
            buttonContainer.appendChild(helpBtn);
            
            errorElement.appendChild(buttonContainer);
        }
        
    } catch (error) {
        console.error('Error in handleAuthenticationError:', error);
        showDashboardError('Terjadi kesalahan saat menangani error autentikasi.');
    }
}

// Create new Looker Studio report
function createNewReport() {
    try {
        console.log('Creating new Looker Studio report...');
        
        // Open Looker Studio in new tab to create a new report
        const newReportUrl = 'https://lookerstudio.google.com/reporting/create';
        window.open(newReportUrl, '_blank');
        
        showAlert('success', 'Looker Studio dibuka di tab baru untuk membuat laporan baru!');
        
        // Show instructions
        const instructionsHtml = `
        <div class="alert alert-info mt-3">
            <h6><i class="bi bi-info-circle me-2"></i>Instruksi Membuat Laporan Baru:</h6>
            <ol class="mb-0">
                <li>Looker Studio akan terbuka di tab baru</li>
                <li>Buat laporan baru dengan data yang diinginkan</li>
                <li>Setelah selesai, klik "Share" di Looker Studio</li>
                <li>Pilih "Embed" dan salin URL embed</li>
                <li>Kembali ke aplikasi ini dan masukkan URL embed di bagian "Masukkan URL Dashboard"</li>
            </ol>
        </div>
        `;
        
        // Add instructions to the page
        const container = document.getElementById('dashboardContainer');
        if (container) {
            const existingInstructions = container.querySelector('.alert-info');
            if (!existingInstructions) {
                container.insertAdjacentHTML('beforeend', instructionsHtml);
            }
        }
        
    } catch (error) {
        console.error('Error creating new report:', error);
        showAlert('error', 'Gagal membuka Looker Studio: ' + error.message);
    }
}



function createDirectLink() {
    try {
        console.log('Creating direct link to Looker Studio...');
        
        // Check if CSRF token exists
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            showAlert('error', 'CSRF token tidak ditemukan. Silakan refresh halaman.');
            return;
        }
        
        // Show loading indicator
        showAlert('info', 'Membuat link langsung ke Looker Studio...');
        
        // Add loading state to button
        const directLinkBtn = document.querySelector('button[onclick="createDirectLink()"]');
        const originalText = directLinkBtn.innerHTML;
        directLinkBtn.innerHTML = '<i class="bi bi-hourglass-split me-1 spin"></i> Creating...';
        directLinkBtn.disabled = true;
        
        fetch('/looker-studio/create-direct-link', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                if (response.status === 401) {
                    throw new Error('Silakan login terlebih dahulu.');
                } else if (response.status === 403) {
                    throw new Error('Anda tidak memiliki akses ke fitur ini.');
                } else if (response.status === 404) {
                    throw new Error('Endpoint tidak ditemukan. Silakan refresh halaman.');
                } else {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            // Reset button
            directLinkBtn.innerHTML = originalText;
            directLinkBtn.disabled = false;
            
            if (data.success) {
                // Show success message
                showAlert('success', data.message);
                
                // Open the link in new tab
                window.open(data.url, '_blank');
            } else {
                showAlert('error', 'Gagal membuat link: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            // Reset button
            directLinkBtn.innerHTML = originalText;
            directLinkBtn.disabled = false;
            
            console.error('Error creating direct link:', error);
            showAlert('error', 'Terjadi kesalahan: ' + error.message);
        });
    } catch (error) {
        console.error('Error in createDirectLink:', error);
        showAlert('error', 'Terjadi kesalahan sistem: ' + error.message);
    }
}

// New function to handle authentication required state
function showAuthenticationRequiredState(urlInfo) {
    try {
        console.log('Showing authentication required state for URL:', urlInfo.originalUrl);
        
        hideAllDashboardStates();
        
        // Create a special authentication required state
        const authRequiredHtml = `
            <div id="authRequiredState" class="text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-shield-lock text-warning" style="font-size: 4rem;"></i>
                </div>
                <h5>Autentikasi Google Diperlukan</h5>
                <p class="text-muted mb-4">
                    Dashboard ini memerlukan login ke akun Google. Silakan gunakan salah satu opsi di bawah ini.
                </p>
                
                <div class="alert alert-info mb-4">
                    <h6><i class="bi bi-info-circle me-2"></i>Informasi Dashboard:</h6>
                    <p class="mb-2"><strong>URL:</strong> ${urlInfo.originalUrl}</p>
                    <p class="mb-0"><strong>Status:</strong> Memerlukan autentikasi Google</p>
                </div>
                
                                 <div class="d-flex justify-content-center gap-2 flex-wrap">
                     <button type="button" class="btn btn-primary" onclick="openDashboardWithAuth('${urlInfo.originalUrl}')">
                         <i class="bi bi-box-arrow-up-right me-2"></i>
                         Buka di Tab Baru (Login)
                     </button>
                     <button type="button" class="btn btn-info" onclick="tryEmbedAfterAuth('${urlInfo.embedUrl}')">
                         <i class="bi bi-arrow-clockwise me-2"></i>
                         Coba Embed Lagi
                     </button>
                     <button type="button" class="btn btn-warning" onclick="showAuthenticationHelp()">
                         <i class="bi bi-question-circle me-2"></i>
                         Bantuan
                     </button>
                 </div>
                
                <div class="mt-4">
                    <small class="text-muted">
                        <i class="bi bi-lightbulb me-1"></i>
                        <strong>Tips:</strong> Setelah login ke Google, refresh halaman ini untuk mencoba embedding lagi.
                    </small>
                </div>
            </div>
        `;
        
        // Add the authentication required state to the container
        const container = document.getElementById('dashboardContainer');
        if (container) {
            // Remove existing auth required state if any
            const existingAuthState = document.getElementById('authRequiredState');
            if (existingAuthState) {
                existingAuthState.remove();
            }
            
            // Add new auth required state
            container.insertAdjacentHTML('beforeend', authRequiredHtml);
            
            // Show the auth required state
            document.getElementById('authRequiredState').style.display = 'block';
        }
        
    } catch (error) {
        console.error('Error showing authentication required state:', error);
        showDashboardError('Gagal menampilkan halaman autentikasi: ' + error.message);
    }
}

// New function to open dashboard with authentication
function openDashboardWithAuth(url) {
    try {
        console.log('Opening dashboard with authentication:', url);
        window.open(url, '_blank');
        showAlert('success', 'Dashboard dibuka di tab baru. Silakan login ke Google jika diminta.');
    } catch (error) {
        console.error('Error opening dashboard with auth:', error);
        showAlert('error', 'Gagal membuka dashboard: ' + error.message);
    }
}



// New function to try embedding after authentication
function tryEmbedAfterAuth(embedUrl) {
    try {
        console.log('Trying to embed after authentication:', embedUrl);
        
        // Show loading state
        showDashboardLoading();
        
        // Try to load the embed URL directly
        const iframe = document.getElementById('lookerStudioFrame');
        if (iframe) {
            iframe.src = embedUrl;
            
            // Set a shorter timeout for this attempt
            const authTimeout = setTimeout(() => {
                console.log('Authentication embed attempt timeout');
                showAuthenticationRequiredState({
                    originalUrl: localStorage.getItem('lookerStudioDashboardUrl'),
                    embedUrl: embedUrl,
                    requiresAuth: true
                });
            }, 3000); // 3 seconds timeout for auth attempt
            
            iframe.onload = function() {
                clearTimeout(authTimeout);
                console.log('Embed attempt successful after auth');
                showDashboardEmbed();
            };
            
            iframe.onerror = function() {
                clearTimeout(authTimeout);
                console.log('Embed attempt failed after auth');
                showAuthenticationRequiredState({
                    originalUrl: localStorage.getItem('lookerStudioDashboardUrl'),
                    embedUrl: embedUrl,
                    requiresAuth: true
                });
            };
        }
        
    } catch (error) {
        console.error('Error trying embed after auth:', error);
        showDashboardError('Gagal mencoba embedding setelah autentikasi: ' + error.message);
    }
}

// Enhanced hideAllDashboardStates to include auth required state
function hideAllDashboardStates() {
    const states = ['dashboardLoading', 'dashboardEmbed', 'noDashboardState', 'dashboardError', 'authRequiredState'];
    states.forEach(stateId => {
        const element = document.getElementById(stateId);
        if (element) {
            element.style.display = 'none';
        }
    });
}
</script>
@endpush

