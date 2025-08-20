@extends('layouts.app')

@section('title', 'Looker Studio Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2">
                                <i class="bi bi-graph-up me-3"></i>
                                Looker Studio Analytics Dashboard
                            </h2>
                            <p class="mb-0 opacity-75">
                                Dashboard analytics terintegrasi untuk monitoring performa mitra dan proyek secara real-time
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-light" onclick="refreshData()">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Refresh Data
                                </button>
                                <button type="button" class="btn btn-light" onclick="exportToExcel()">
                                    <i class="bi bi-download me-2"></i>Export
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card card-stat">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-primary">
                        <i class="bi bi-people text-white"></i>
                    </div>
                    <div>
                        <div class="stat-value" id="totalMitra">-</div>
                        <div class="stat-label">Total Mitra</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-success">
                        <i class="bi bi-file-earmark-text text-white"></i>
                    </div>
                    <div>
                        <div class="stat-value" id="totalDokumen">-</div>
                        <div class="stat-label">Total Proyek</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-warning">
                        <i class="bi bi-play-circle text-white"></i>
                    </div>
                    <div>
                        <div class="stat-value" id="proyekAktif">-</div>
                        <div class="stat-label">Proyek Aktif</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-info">
                        <i class="bi bi-percent text-white"></i>
                    </div>
                    <div>
                        <div class="stat-value" id="completionRate">-</div>
                        <div class="stat-label">Completion Rate</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Looker Studio Embed Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-bar-chart me-2"></i>
                        Interactive Analytics Dashboard
                    </h5>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="showEmbedInstructions()">
                            <i class="bi bi-info-circle me-1"></i>Setup Guide
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="openLookerStudio()">
                            <i class="bi bi-box-arrow-up-right me-1"></i>Open in Looker Studio
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="lookerStudioEmbed" class="text-center py-5">
                        <div class="embed-placeholder">
                            <i class="bi bi-bar-chart display-1 text-muted mb-3"></i>
                            <h4 class="text-muted mb-3">Looker Studio Dashboard</h4>
                            <p class="text-muted mb-4">
                                Embed your Looker Studio dashboard here for interactive analytics
                            </p>
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="embedUrl" 
                                               placeholder="Paste your Looker Studio embed URL here">
                                        <button class="btn btn-primary" type="button" onclick="embedDashboard()">
                                            <i class="bi bi-play-circle me-1"></i>Embed
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-info">
                                <strong>Quick Setup:</strong> 
                                <ol class="mb-0 mt-2">
                                    <li>Create a dashboard in Google Data Studio (Looker Studio)</li>
                                    <li>Use the API endpoints below as data sources</li>
                                    <li>Copy the embed URL and paste it above</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- API Endpoints Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-code-slash me-2"></i>
                        API Endpoints for Looker Studio
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Endpoint</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                                                 <tr>
                                     <td><code>/api/looker-studio/dashboard-data</code></td>
                                     <td>Complete dashboard data</td>
                                     <td>
                                         <button class="btn btn-sm btn-outline-primary" onclick="testEndpoint('/api/looker-studio/dashboard-data')">
                                             Test
                                         </button>
                                     </td>
                                 </tr>
                                 <tr>
                                     <td><code>/api/looker-studio/dashboard-data</code></td>
                                     <td>Export data for analysis</td>
                                     <td>
                                         <button class="btn btn-sm btn-outline-success" onclick="testEndpoint('/api/looker-studio/dashboard-data')">
                                             Test
                                         </button>
                                     </td>
                                 </tr>
                                <tr>
                                    <td><code>/api/looker-studio/mitra-analytics</code></td>
                                    <td>Mitra performance data</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-info" onclick="testEndpoint('/api/looker-studio/mitra-analytics')">
                                            Test
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><code>/api/looker-studio/proyek-analytics</code></td>
                                    <td>Project analytics data</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-warning" onclick="testEndpoint('/api/looker-studio/proyek-analytics')">
                                            Test
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>
                        Real-time Analytics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Monthly Growth</h6>
                                    <div class="display-6 text-primary" id="monthlyGrowth">-</div>
                                    <small class="text-muted">New Mitra</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Project Velocity</h6>
                                    <div class="display-6 text-success" id="projectVelocity">-</div>
                                    <small class="text-muted">New Projects</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <h6>Top Performing Mitra</h6>
                        <div id="topMitraList" class="list-group list-group-flush">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Visualization Section -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>
                        Performance Trends
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="trendsChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-pie-chart me-2"></i>
                        Project Status Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Setup Instructions Modal -->
<div class="modal fade" id="setupModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Looker Studio Setup Guide</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Step 1: Create Data Source</h6>
                        <ol>
                            <li>Go to <a href="https://lookerstudio.google.com" target="_blank">Looker Studio</a></li>
                            <li>Click "Create" → "Data source"</li>
                            <li>Choose "URL" connector</li>
                            <li>Enter one of our API endpoints</li>
                        </ol>
                        
                        <h6>Step 2: Build Dashboard</h6>
                        <ol>
                            <li>Add charts and visualizations</li>
                            <li>Use the data fields from our API</li>
                            <li>Customize colors and layout</li>
                            <li>Save your dashboard</li>
                        </ol>
                    </div>
                    <div class="col-md-6">
                        <h6>Recommended Charts</h6>
                        <ul>
                            <li><strong>Time Series:</strong> Project trends over time</li>
                            <li><strong>Pie Chart:</strong> Status distribution</li>
                            <li><strong>Bar Chart:</strong> Top performing mitra</li>
                            <li><strong>Scorecard:</strong> Key metrics</li>
                            <li><strong>Table:</strong> Detailed data</li>
                        </ul>
                        
                        <h6>API Data Structure</h6>
                        <pre class="bg-light p-2 rounded"><code>{
  "overview": {
    "total_mitra": 150,
    "total_dokumen": 450,
    "completion_rate": 75.5
  },
  "trends": {
    "monthly_trends": [...],
    "weekly_trends": [...]
  }
}</code></pre>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="https://lookerstudio.google.com" target="_blank" class="btn btn-primary">
                    <i class="bi bi-box-arrow-up-right me-1"></i>Open Looker Studio
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card-stat {
    padding: 1.5rem;
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.stat-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    font-size: 1.5rem;
    margin-right: 1rem;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: bold;
    color: #333;
}

.stat-label {
    color: #6c757d;
    font-size: 0.9rem;
}

.embed-placeholder {
    padding: 3rem;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #e22626 0%, #dc3545 100%);
}

#lookerStudioEmbed iframe {
    width: 100%;
    height: 600px;
    border: none;
}

.endpoint-test-result {
    max-height: 200px;
    overflow-y: auto;
    font-size: 0.8rem;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let dashboardData = null;
let trendsChart = null;
let statusChart = null;

document.addEventListener('DOMContentLoaded', function() {
    loadDashboardData();
    initializeCharts();
});

function loadDashboardData() {
    fetch('/api/looker-studio/dashboard-data')
        .then(response => response.json())
        .then(data => {
            dashboardData = data;
            updateQuickStats(data);
            updateTopMitra(data);
            updateCharts(data);
        })
        .catch(error => {
            console.error('Error loading dashboard data:', error);
            showAlert('Error loading dashboard data', 'danger');
        });
}

function updateQuickStats(data) {
    document.getElementById('totalMitra').textContent = data.overview.total_mitra;
    document.getElementById('totalDokumen').textContent = data.overview.total_dokumen;
    document.getElementById('proyekAktif').textContent = data.overview.proyek_aktif;
    document.getElementById('completionRate').textContent = data.overview.completion_rate + '%';
    document.getElementById('monthlyGrowth').textContent = data.overview.mitra_growth_monthly;
    document.getElementById('projectVelocity').textContent = data.overview.proyek_growth_monthly;
}

function updateTopMitra(data) {
    const topMitraList = document.getElementById('topMitraList');
    const topMitra = data.mitra_analytics.top_performing_mitra.slice(0, 5);
    
    topMitraList.innerHTML = topMitra.map(mitra => `
        <div class="list-group-item d-flex justify-content-between align-items-center">
            <div>
                <strong>${mitra.name}</strong>
                <br>
                <small class="text-muted">${mitra.total_proyek} projects</small>
            </div>
            <span class="badge bg-success rounded-pill">${mitra.success_rate}%</span>
        </div>
    `).join('');
}

function initializeCharts() {
    // Initialize charts with empty data
    const trendsCtx = document.getElementById('trendsChart').getContext('2d');
    trendsChart = new Chart(trendsCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: []
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const statusCtx = document.getElementById('statusChart').getContext('2d');
    statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: [],
            datasets: []
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
}

function updateCharts(data) {
    // Update trends chart
    const monthlyTrends = data.trends.monthly_trends;
    trendsChart.data.labels = monthlyTrends.map(trend => trend.month_name);
    trendsChart.data.datasets = [
        {
            label: 'New Mitra',
            data: monthlyTrends.map(trend => trend.new_mitra),
            borderColor: '#e22626',
            backgroundColor: 'rgba(226, 38, 38, 0.1)',
            tension: 0.4
        },
        {
            label: 'New Projects',
            data: monthlyTrends.map(trend => trend.new_proyek),
            borderColor: '#28a745',
            backgroundColor: 'rgba(40, 167, 69, 0.1)',
            tension: 0.4
        },
        {
            label: 'Completed Projects',
            data: monthlyTrends.map(trend => trend.completed_proyek),
            borderColor: '#ffc107',
            backgroundColor: 'rgba(255, 193, 7, 0.1)',
            tension: 0.4
        }
    ];
    trendsChart.update();

    // Update status chart
    const statusData = data.proyek_analytics.status_distribution;
    const statusLabels = ['Inisiasi', 'Planning', 'Executing', 'Controlling', 'Closing'];
    const statusValues = [
        statusData.inisiasi || 0,
        statusData.planning || 0,
        statusData.executing || 0,
        statusData.controlling || 0,
        statusData.closing || 0
    ];

    statusChart.data.labels = statusLabels;
    statusChart.data.datasets = [{
        data: statusValues,
        backgroundColor: [
            '#0d6efd',
            '#17a2b8',
            '#ffc107',
            '#6c757d',
            '#28a745'
        ],
        borderWidth: 2,
        borderColor: '#fff'
    }];
    statusChart.update();
}

function embedDashboard() {
    const embedUrl = document.getElementById('embedUrl').value;
    if (!embedUrl) {
        showAlert('Please enter a valid embed URL', 'warning');
        return;
    }

    const embedContainer = document.getElementById('lookerStudioEmbed');
    embedContainer.innerHTML = `<iframe src="${embedUrl}" frameborder="0" style="border:0" allowfullscreen></iframe>`;
}

function showEmbedInstructions() {
    const modal = new bootstrap.Modal(document.getElementById('setupModal'));
    modal.show();
}

function openLookerStudio() {
    window.open('https://lookerstudio.google.com', '_blank');
}

function testEndpoint(endpoint) {
    fetch(endpoint)
        .then(response => response.json())
        .then(data => {
            showEndpointResult(endpoint, data);
        })
        .catch(error => {
            showEndpointResult(endpoint, { error: error.message });
        });
}

function showEndpointResult(endpoint, data) {
    const resultHtml = `
        <div class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">API Response: ${endpoint}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <pre class="endpoint-test-result bg-light p-3 rounded">${JSON.stringify(data, null, 2)}</pre>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    const modalElement = document.createElement('div');
    modalElement.innerHTML = resultHtml;
    document.body.appendChild(modalElement);
    
    const modal = new bootstrap.Modal(modalElement.querySelector('.modal'));
    modal.show();
    
    modalElement.querySelector('.modal').addEventListener('hidden.bs.modal', function() {
        document.body.removeChild(modalElement);
    });
}

function refreshData() {
    loadDashboardData();
    showAlert('Data refreshed successfully', 'success');
}

function exportToExcel() {
    fetch('/api/looker-studio/dashboard-data')
        .then(response => response.json())
        .then(data => {
            // Create CSV content
            const csvContent = convertToCSV(data);
            downloadCSV(csvContent, 'looker-studio-export.csv');
        })
        .catch(error => {
            showAlert('Error exporting data', 'danger');
        });
}

function convertToCSV(data) {
    // Convert the data to CSV format
    let csv = '';
    
    // Add headers
    csv += 'Data Type,ID,Name,Email,Projects,Status,Date\n';
    
    // Add mitra data
    data.mitra_data.forEach(mitra => {
        csv += `Mitra,${mitra.mitra_id},"${mitra.nama_mitra}","${mitra.email_mitra}",${mitra.total_proyek},${mitra.status_mitra},${mitra.tanggal_bergabung}\n`;
    });
    
    // Add proyek data
    data.proyek_data.forEach(proyek => {
        csv += `Proyek,${proyek.proyek_id},"${proyek.nama_proyek}","${proyek.nama_mitra}",1,${proyek.status_implementasi},${proyek.tanggal_mulai}\n`;
    });
    
    return csv;
}

function downloadCSV(content, filename) {
    const blob = new Blob([content], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', filename);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function showAlert(message, type) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    const alertElement = document.createElement('div');
    alertElement.innerHTML = alertHtml;
    document.querySelector('.container-fluid').insertBefore(alertElement.firstElementChild, document.querySelector('.container-fluid').firstChild);
    
    // Auto dismiss after 3 seconds
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 3000);
}
</script>
@endpush
