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

    <!-- Data Tables Row -->
    <div class="row mb-4">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-table me-2"></i>
                        Aktivitas Mitra Terbaru
                    </h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink3" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                            <a class="dropdown-item" href="#" onclick="exportTableData('mitra')">Export Data</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="mitraTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nama Mitra</th>
                                    <th>Jumlah Dokumen</th>
                                    <th>Jumlah Foto</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dashboardData['aktivitas_mitra'] as $mitra)
                                <tr>
                                    <td>{{ $mitra->name ?? 'Unknown' }}</td>
                                    <td>{{ $mitra->dokumen_count ?? 0 }}</td>
                                    <td>{{ $mitra->fotos_count ?? 0 }}</td>
                                    <td>
                                        @if(($mitra->dokumen_count ?? 0) > 0)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Belum ada aktivitas mitra yang tercatat
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-clock-history me-2"></i>
                        Aktivitas Terbaru
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @forelse($dashboardData['recent_activities'] as $activity)
                        <div class="timeline-item">
                            <div class="timeline-marker">
                                @if($activity['type'] == 'dokumen')
                                    <i class="bi bi-file-earmark-text text-primary"></i>
                                @elseif($activity['type'] == 'foto')
                                    <i class="bi bi-image text-success"></i>
                                @elseif($activity['type'] == 'review')
                                    <i class="bi bi-star text-warning"></i>
                                @else
                                    <i class="bi bi-activity text-info"></i>
                                @endif
                            </div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">{{ $activity['title'] ?? 'Aktivitas' }}</h6>
                                <p class="timeline-text">{{ $activity['user'] ?? 'User' }}</p>
                                <small class="text-muted">{{ $activity['time'] ?? 'Baru saja' }}</small>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-3">
                            <i class="bi bi-info-circle me-2"></i>
                            Belum ada aktivitas terbaru yang tercatat
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Looker Studio Integration -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-link-45deg me-2"></i>
                        Looker Studio Integration
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
                             <div class="mt-4">
                                 <h6>Atau Masukkan URL Looker Studio Eksternal</h6>
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
                        </div>
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

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #f8f9fc;
    display: flex;
    align-items: center;
    justify-content: center;
}

.timeline-content {
    background: #f8f9fc;
    padding: 15px;
    border-radius: 5px;
    border-left: 3px solid #4e73df;
}

.timeline-title {
    margin: 0 0 5px 0;
    font-size: 14px;
    font-weight: 600;
}

.timeline-text {
    margin: 0 0 5px 0;
    font-size: 12px;
    color: #6c757d;
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
                // Show dashboard URL
                document.getElementById('dashboardUrl').value = data.url;
                document.getElementById('openDashboardBtn').href = data.url;
                document.getElementById('dashboardUrlSection').style.display = 'block';
                
                // Show success message
                showAlert('success', 'Dashboard Looker Studio berhasil dibuat!');
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
                // Show dashboard URL
                document.getElementById('dashboardUrl').value = data.url;
                document.getElementById('openDashboardBtn').href = data.url;
                document.getElementById('dashboardUrlSection').style.display = 'block';
                
                // Show success message
                const message = data.type === 'custom' ? 
                    'URL Looker Studio eksternal ditemukan!' : 
                    'URL Looker Studio baru dibuat!';
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
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Reset button
            setUrlBtn.innerHTML = originalText;
            setUrlBtn.disabled = false;
            
            if (data.success) {
                // Show dashboard URL
                document.getElementById('dashboardUrl').value = data.url;
                document.getElementById('openDashboardBtn').href = data.url;
                document.getElementById('dashboardUrlSection').style.display = 'block';
                
                // Clear input
                customUrlInput.value = '';
                
                // Show success message
                showAlert('success', 'URL Looker Studio eksternal berhasil disimpan!');
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

function exportTableData(tableType) {
    try {
        const url = `/api/looker-studio/analytics?type=${tableType}`;
        
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
                    a.download = `indismart_${tableType}_${new Date().toISOString().split('T')[0]}.json`;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    window.URL.revokeObjectURL(downloadUrl);
                    
                    showAlert('success', `Table data ${tableType} berhasil di-export!`);
                } else {
                    showAlert('error', 'Gagal export table data: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error exporting table data:', error);
                showAlert('error', 'Gagal export table data: ' + error.message);
            });
    } catch (error) {
        console.error('Error in exportTableData:', error);
        showAlert('error', 'Gagal export table data: ' + error.message);
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
});



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
</script>
@endpush

