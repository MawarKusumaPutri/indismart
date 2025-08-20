@extends('layouts.app')

@section('title', 'Detail Mitra - ' . $mitra->name)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('manajemen-mitra.index') }}">Manajemen Mitra</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $mitra->name }}</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 mt-2">{{ $mitra->name }}</h1>
            <p class="text-muted mb-0">{{ $mitra->email }}</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary" onclick="exportLaporanMitra()">
                <i class="bi bi-download me-2"></i>Export Laporan
            </button>
            <a href="{{ route('manajemen-mitra.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Info Mitra -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-person me-2"></i>Informasi Mitra
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <p class="mb-0">{{ $mitra->name }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <p class="mb-0">{{ $mitra->email }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Telepon</label>
                                <p class="mb-0">{{ $mitra->phone ?? 'Tidak tersedia' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Alamat</label>
                                <p class="mb-0">{{ $mitra->address ?? 'Tidak tersedia' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal Bergabung</label>
                                <p class="mb-0">{{ $mitra->created_at->format('d F Y') }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <span class="badge bg-success">Aktif</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-graph-up me-2"></i>Statistik
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 p-2 rounded">
                                <i class="bi bi-file-earmark-text text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Total Dokumen</h6>
                            <h4 class="mb-0">{{ $statistik_mitra['total_dokumen'] }}</h4>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 p-2 rounded">
                                <i class="bi bi-clock text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Dokumen Aktif</h6>
                            <h4 class="mb-0">{{ $statistik_mitra['dokumen_aktif'] }}</h4>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 p-2 rounded">
                                <i class="bi bi-check-circle text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Dokumen Selesai</h6>
                            <h4 class="mb-0">{{ $statistik_mitra['dokumen_selesai'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pie-chart me-2"></i>Jenis Proyek
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="jenisProyekChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-bar-chart me-2"></i>Status Implementasi
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Dokumen -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent">
            <h5 class="card-title mb-0">
                <i class="bi bi-file-earmark-text me-2"></i>Daftar Dokumen
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nama Dokumen</th>
                            <th>Jenis Proyek</th>
                            <th>Witel</th>
                            <th>Site Name</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dokumen as $doc)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $doc->nama_dokumen }}</div>
                                <small class="text-muted">{{ $doc->nomor_kontrak }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $doc->jenis_proyek }}</span>
                            </td>
                            <td>{{ $doc->witel }}</td>
                            <td>{{ $doc->site_name }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'inisiasi' => 'bg-secondary',
                                        'planning' => 'bg-primary',
                                        'executing' => 'bg-warning',
                                        'controlling' => 'bg-info',
                                        'closing' => 'bg-success'
                                    ];
                                    $statusLabels = [
                                        'inisiasi' => 'Inisiasi',
                                        'planning' => 'Planning',
                                        'executing' => 'Executing',
                                        'controlling' => 'Controlling',
                                        'closing' => 'Closing'
                                    ];
                                @endphp
                                <span class="badge {{ $statusColors[$doc->status_implementasi] ?? 'bg-secondary' }}">
                                    {{ $statusLabels[$doc->status_implementasi] ?? $doc->status_implementasi }}
                                </span>
                            </td>
                            <td>{{ $doc->tanggal_dokumen->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <a href="{{ route('dokumen.show', $doc->id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-inbox text-muted fs-1"></i>
                                <p class="text-muted mb-0">Tidak ada dokumen</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($dokumen->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $dokumen->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Data untuk charts
const jenisProyekData = @json($laporan_jenis_proyek);
const statusData = @json($laporan_status);

// Chart Jenis Proyek
const jenisProyekCtx = document.getElementById('jenisProyekChart').getContext('2d');
new Chart(jenisProyekCtx, {
    type: 'doughnut',
    data: {
        labels: jenisProyekData.map(item => item.jenis_proyek),
        datasets: [{
            data: jenisProyekData.map(item => item.total),
            backgroundColor: [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', 
                '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
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

// Chart Status Implementasi
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'bar',
    data: {
        labels: statusData.map(item => item.status_implementasi),
        datasets: [{
            label: 'Jumlah Dokumen',
            data: statusData.map(item => item.total),
            backgroundColor: [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'
            ],
            borderWidth: 1,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Function untuk export laporan mitra
function exportLaporanMitra() {
    const format = prompt('Pilih format export:\n1. PDF\n2. Excel (CSV)\n\nMasukkan 1 atau 2:');
    
    if (format === '1') {
        window.location.href = '{{ route("manajemen-mitra.export") }}?jenis=mitra&mitra_id={{ $mitra->id }}&format=pdf';
    } else if (format === '2') {
        window.location.href = '{{ route("manajemen-mitra.export") }}?jenis=mitra&mitra_id={{ $mitra->id }}&format=excel';
    } else if (format !== null) {
        alert('Pilihan tidak valid. Silakan pilih 1 untuk PDF atau 2 untuk Excel.');
    }
}
</script>
@endsection 