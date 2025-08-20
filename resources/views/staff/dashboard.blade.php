@extends('layouts.app')

@section('title', 'Dashboard Staff')

@section('content')
<div class="container-fluid">
    <!-- Notifikasi Mitra Baru -->
    @php
        $recentMitra = \App\Models\User::where('role', 'mitra')
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    @endphp
    
    @if($recentMitra->count() > 0)
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-person-plus fs-4 me-2"></i>
                <div>
                    <strong>Mitra Baru Terdaftar!</strong>
                    <br>
                    <small class="text-muted">
                        {{ $recentMitra->count() }} mitra baru telah mendaftar dalam 7 hari terakhir.
                        <a href="{{ route('manajemen-mitra.index') }}" class="alert-link">Lihat daftar mitra</a>
                    </small>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-3">
            <div class="card card-stat">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $totalMitra }}</div>
                        <div class="stat-label">Total Mitra</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $totalDokumen }}</div>
                        <div class="stat-label">Total Dokumen</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $proyekAktif }}</div>
                        <div class="stat-label">Proyek Aktif</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $proyekSelesai }}</div>
                        <div class="stat-label">Proyek Selesai</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="bi bi-clipboard-check"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $dokumenPendingReview }}</div>
                        <div class="stat-label">Dokumen Pending Review</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Row untuk Chart -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Distribusi Status Proyek</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusProyekChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Distribusi Jenis Proyek</h5>
                </div>
                <div class="card-body">
                    <canvas id="jenisProyekChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Daftar Mitra</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-projects">
                            <thead>
                                <tr>
                                    <th>Nama Mitra</th>
                                    <th>Email</th>
                                    <th>Jumlah Proyek</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($daftarMitra as $mitra)
                                    <tr>
                                        <td>{{ $mitra->name }}</td>
                                        <td>{{ $mitra->email }}</td>
                                        <td>{{ $mitra->total_proyek }}</td>
                                        <td>
                                            @if($mitra->proyek_aktif > 0)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="showMitraDetail({{ $mitra->id }})">Detail</button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="bi bi-people display-4 text-muted d-block mb-3"></i>
                                            <p class="text-muted mb-0">Belum ada mitra yang terdaftar</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Aktivitas Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($aktivitasTerbaru as $aktivitas)
                            <li class="list-group-item d-flex align-items-center py-3">
                                <div class="me-3">
                                    <div class="bg-{{ $aktivitas['color'] }} rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                        <i class="bi {{ $aktivitas['icon'] }} text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-0">{{ $aktivitas['message'] }}</p>
                                    <small class="text-muted">{{ $aktivitas['time'] }}</small>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center py-5">
                                <i class="bi bi-clock-history display-4 text-muted d-block mb-3"></i>
                                <p class="text-muted mb-0">Belum ada aktivitas</p>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Mitra -->
<div class="modal fade" id="mitraDetailModal" tabindex="-1" aria-labelledby="mitraDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mitraDetailModalLabel">Detail Mitra</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="mitraDetailContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Memuat data mitra...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mitra Terbaru -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-person-plus me-2"></i>Mitra Terbaru
                    </h5>
                    <a href="{{ route('manajemen-mitra.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Mitra</th>
                                    <th>Email</th>
                                    <th>Tanggal Registrasi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentMitra as $mitra)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-3">
                                                    @if($mitra->avatar)
                                                        <img src="{{ asset('storage/' . $mitra->avatar) }}" alt="Avatar" class="rounded-circle" width="40">
                                                    @else
                                                        <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                                            <span class="text-white fw-bold">{{ strtoupper(substr($mitra->name, 0, 1)) }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $mitra->name }}</h6>
                                                    <small class="text-muted">ID: {{ $mitra->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $mitra->email }}</td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $mitra->created_at->format('d M Y H:i') }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($mitra->nomor_kontrak)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-warning">Belum Ada Nomor Kontrak</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('manajemen-mitra.show', $mitra->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @if(!$mitra->nomor_kontrak)
                                                    <a href="{{ route('nomor-kontrak.assign', $mitra->id) }}" class="btn btn-sm btn-outline-success">
                                                        <i class="bi bi-hash"></i> Tugaskan Kontrak
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-people display-4"></i>
                                                <p class="mt-2">Tidak ada mitra baru</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-stat {
        padding: 1.5rem;
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        background-color: rgba(226, 38, 38, 0.1);
        color: #e22626;
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
    }
    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
    }
    .table-projects th {
        font-weight: 600;
        background-color: #f8f9fa;
    }
</style>
@endpush

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load pending review count
    loadPendingReviewCount();
    
    function loadPendingReviewCount() {
        fetch('{{ route("reviews.pending-count") }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('pendingReviewCount').textContent = data.count;
            });
    }

    // Data untuk pie chart status proyek
    const statusData = @json($statusProyek);
    const statusLabels = ['Inisiasi', 'Planning', 'Executing', 'Controlling', 'Closing'];
    const statusValues = [
        statusData.inisiasi || 0,
        statusData.planning || 0,
        statusData.executing || 0,
        statusData.controlling || 0,
        statusData.closing || 0
    ];

    // Pie Chart untuk Status Proyek
    const statusCtx = document.getElementById('statusProyekChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'pie',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusValues,
                backgroundColor: [
                    '#0d6efd', // Inisiasi - Primary Blue
                    '#17a2b8', // Planning - Info Cyan
                    '#ffc107', // Executing - Warning Yellow
                    '#6c757d', // Controlling - Secondary Gray
                    '#28a745'  // Closing - Success Green
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
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Data untuk pie chart jenis proyek
    const jenisData = @json($jenisProyek);
    const jenisLabels = Object.keys(jenisData);
    const jenisValues = Object.values(jenisData);

    // Warna dinamis untuk jenis proyek
    const jenisColors = [
        '#e22626', // Brand Red
        '#fd7e14', // Orange
        '#20c997', // Teal
        '#6f42c1', // Purple
        '#dc3545', // Danger Red
        '#198754', // Success Green
        '#0dcaf0', // Info Light Blue
        '#adb5bd'  // Light Gray
    ];

    // Pie Chart untuk Jenis Proyek
    if (jenisLabels.length > 0) {
        const jenisCtx = document.getElementById('jenisProyekChart').getContext('2d');
        new Chart(jenisCtx, {
            type: 'pie',
            data: {
                labels: jenisLabels.map(label => {
                    // Kapitalisasi label
                    return label.charAt(0).toUpperCase() + label.slice(1);
                }),
                datasets: [{
                    data: jenisValues,
                    backgroundColor: jenisColors.slice(0, jenisLabels.length),
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    } else {
        // Tampilkan pesan jika tidak ada data jenis proyek
        document.getElementById('jenisProyekChart').style.display = 'none';
        const chartContainer = document.getElementById('jenisProyekChart').parentNode;
        chartContainer.innerHTML = '<div class="text-center py-5"><i class="bi bi-pie-chart display-4 text-muted"></i><p class="text-muted mt-3">Belum ada data jenis proyek</p></div>';
    }
});

// Function untuk menampilkan detail mitra
function showMitraDetail(mitraId) {
    const modal = new bootstrap.Modal(document.getElementById('mitraDetailModal'));
    const modalContent = document.getElementById('mitraDetailContent');
    
    // Tampilkan loading
    modalContent.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Memuat data mitra...</p>
        </div>
    `;
    
    modal.show();
    
    // Fetch data mitra
    fetch(`/staff/mitra/${mitraId}/detail`)
        .then(response => response.json())
        .then(data => {
            renderMitraDetail(data);
        })
        .catch(error => {
            modalContent.innerHTML = `
                <div class="text-center py-5">
                    <i class="bi bi-exclamation-circle display-4 text-danger"></i>
                    <p class="mt-3 text-muted">Gagal memuat data mitra</p>
                </div>
            `;
        });
}

function renderMitraDetail(data) {
    const { mitra, statistik, proyek_list, status_distribusi, jenis_distribusi } = data;
    
    const modalContent = document.getElementById('mitraDetailContent');
    
    modalContent.innerHTML = `
        <!-- Info Mitra -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="bi bi-person-circle me-2"></i>Informasi Mitra</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Nama:</strong><br>
                                <span class="text-muted">${mitra.name}</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Email:</strong><br>
                                <span class="text-muted">${mitra.email}</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Bergabung:</strong><br>
                                <span class="text-muted">${new Date(mitra.created_at).toLocaleDateString('id-ID')}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Statistik -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-file-earmark-text display-6 text-primary"></i>
                        <h4 class="mt-2">${statistik.total_proyek}</h4>
                        <p class="text-muted mb-0">Total Proyek</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-play-circle display-6 text-warning"></i>
                        <h4 class="mt-2">${statistik.proyek_aktif}</h4>
                        <p class="text-muted mb-0">Proyek Aktif</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-check-circle display-6 text-success"></i>
                        <h4 class="mt-2">${statistik.proyek_selesai}</h4>
                        <p class="text-muted mb-0">Proyek Selesai</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Charts -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Distribusi Status Proyek</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="mitraStatusChart" style="max-height: 250px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Distribusi Jenis Proyek</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="mitraJenisChart" style="max-height: 250px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Daftar Proyek -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-list-ul me-2"></i>Daftar Proyek</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama Proyek</th>
                                        <th>Jenis</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${proyek_list.map(proyek => `
                                        <tr>
                                            <td>${proyek.nama_proyek || '-'}</td>
                                            <td>${proyek.jenis_proyek || '-'}</td>
                                            <td>
                                                <span class="badge ${getStatusBadgeClass(proyek.status_implementasi)}">
                                                    ${getStatusLabel(proyek.status_implementasi)}
                                                </span>
                                            </td>
                                            <td>${new Date(proyek.created_at).toLocaleDateString('id-ID')}</td>
                                            <td>
                                                <a href="/dokumen/${proyek.id}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Render charts untuk mitra
    setTimeout(() => {
        renderMitraCharts(status_distribusi, jenis_distribusi);
    }, 100);
}

function renderMitraCharts(statusDistribusi, jenisDistribusi) {
    // Chart Status Proyek Mitra
    const statusCtx = document.getElementById('mitraStatusChart').getContext('2d');
    const statusValues = [
        statusDistribusi.inisiasi || 0,
        statusDistribusi.planning || 0,
        statusDistribusi.executing || 0,
        statusDistribusi.controlling || 0,
        statusDistribusi.closing || 0
    ];
    
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Inisiasi', 'Planning', 'Executing', 'Controlling', 'Closing'],
            datasets: [{
                data: statusValues,
                backgroundColor: ['#0d6efd', '#17a2b8', '#ffc107', '#6c757d', '#28a745'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 15, usePointStyle: true }
                }
            }
        }
    });
    
    // Chart Jenis Proyek Mitra
    if (Object.keys(jenisDistribusi).length > 0) {
        const jenisCtx = document.getElementById('mitraJenisChart').getContext('2d');
        new Chart(jenisCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(jenisDistribusi).map(label => label.charAt(0).toUpperCase() + label.slice(1)),
                datasets: [{
                    data: Object.values(jenisDistribusi),
                    backgroundColor: ['#e22626', '#fd7e14', '#20c997', '#6f42c1', '#dc3545'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 15, usePointStyle: true }
                    }
                }
            }
        });
    } else {
        document.getElementById('mitraJenisChart').parentNode.innerHTML = 
            '<div class="text-center py-4"><i class="bi bi-pie-chart text-muted"></i><p class="text-muted mt-2">Belum ada data</p></div>';
    }
}

function getStatusBadgeClass(status) {
    const classes = {
        'inisiasi': 'bg-primary',
        'planning': 'bg-info',
        'executing': 'bg-warning',
        'controlling': 'bg-secondary',
        'closing': 'bg-success'
    };
    return classes[status] || 'bg-secondary';
}

function getStatusLabel(status) {
    const labels = {
        'inisiasi': 'Inisiasi',
        'planning': 'Planning',
        'executing': 'Executing',
        'controlling': 'Controlling',
        'closing': 'Closing'
    };
    return labels[status] || status;
}
</script>
@endpush