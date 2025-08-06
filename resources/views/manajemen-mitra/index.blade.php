@extends('layouts.app')

@section('title', 'Manajemen Mitra')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Manajemen Mitra</h1>
            <p class="text-muted">Laporan dan pengelolaan data mitra</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#exportModal">
                <i class="bi bi-download me-2"></i>Export Laporan
            </button>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 p-3 rounded">
                                <i class="bi bi-people text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Total Mitra</h6>
                            <h3 class="mb-0">{{ $statistik['total_mitra'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                <i class="bi bi-file-earmark-text text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Total Dokumen</h6>
                            <h3 class="mb-0">{{ $statistik['total_dokumen'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 p-3 rounded">
                                <i class="bi bi-clock text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Dokumen Aktif</h6>
                            <h3 class="mb-0">{{ $statistik['dokumen_aktif'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 p-3 rounded">
                                <i class="bi bi-check-circle text-info fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Dokumen Selesai</h6>
                            <h3 class="mb-0">{{ $statistik['dokumen_selesai'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Laporan Charts -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pie-chart me-2"></i>Laporan Jenis Proyek
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
                        <i class="bi bi-bar-chart me-2"></i>Laporan Status Implementasi
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Laporan Witel dan Bulan -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-geo-alt me-2"></i>Laporan Berdasarkan Witel
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Witel</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($laporan_witel as $witel)
                                <tr>
                                    <td>{{ $witel->witel }}</td>
                                    <td class="text-end">{{ $witel->total }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-calendar me-2"></i>Laporan Bulanan {{ date('Y') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Bulan</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($laporan_bulan as $bulan)
                                <tr>
                                    <td>{{ $bulan['bulan'] }}</td>
                                    <td class="text-end">{{ $bulan['total'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Mitra -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-people me-2"></i>Daftar Mitra
                </h5>
                <div class="d-flex gap-2">
                    <form class="d-flex" method="GET">
                        <input type="text" name="search" class="form-control form-control-sm" 
                               placeholder="Cari mitra..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-outline-secondary btn-sm ms-2">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nama Mitra</th>
                            <th>Email</th>
                            <th class="text-center">Total Dokumen</th>
                            <th class="text-center">Dokumen Aktif</th>
                            <th class="text-center">Dokumen Selesai</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mitra as $m)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        @if($m->avatar && Storage::disk('public')->exists($m->avatar))
                                            <img src="{{ Storage::url($m->avatar) }}" 
                                                 alt="Avatar {{ $m->name }}" 
                                                 class="rounded-circle"
                                                 style="width: 32px; height: 32px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                                 style="width: 32px; height: 32px;">
                                                <i class="bi bi-person text-white"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $m->name }}</div>
                                        <small class="text-muted">ID: {{ $m->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $m->email }}</td>
                            <td class="text-center">
                                <span class="badge bg-primary">{{ $m->total_dokumen }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning">{{ $m->dokumen_aktif }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">{{ $m->dokumen_selesai }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('manajemen-mitra.show', $m->id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i>Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="bi bi-inbox text-muted fs-1"></i>
                                <p class="text-muted mb-0">Tidak ada data mitra</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($mitra->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $mitra->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportModalLabel">Export Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="exportForm" method="GET" action="{{ route('manajemen-mitra.export') }}">
                    <div class="mb-3">
                        <label for="jenis_laporan" class="form-label">Jenis Laporan</label>
                        <select class="form-select" id="jenis_laporan" name="jenis" required>
                            <option value="semua">Laporan Umum (Semua Mitra)</option>
                            <option value="mitra">Laporan Detail Mitra</option>
                        </select>
                    </div>
                    
                    <div class="mb-3" id="mitra_select_div" style="display: none;">
                        <label for="mitra_id" class="form-label">Pilih Mitra</label>
                        <select class="form-select" id="mitra_id" name="mitra_id">
                            <option value="">Pilih Mitra</option>
                            @foreach($mitra as $m)
                            <option value="{{ $m->id }}">{{ $m->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="format" class="form-label">Format Export</label>
                        <select class="form-select" id="format" name="format" required>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel (CSV)</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="exportLaporan()">
                    <i class="bi bi-download me-2"></i>Export
                </button>
            </div>
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

// Toggle mitra selection based on report type
document.getElementById('jenis_laporan').addEventListener('change', function() {
    const mitraDiv = document.getElementById('mitra_select_div');
    const mitraSelect = document.getElementById('mitra_id');
    
    if (this.value === 'mitra') {
        mitraDiv.style.display = 'block';
        mitraSelect.required = true;
    } else {
        mitraDiv.style.display = 'none';
        mitraSelect.required = false;
        mitraSelect.value = '';
    }
});

// Export function
function exportLaporan() {
    const form = document.getElementById('exportForm');
    const jenisLaporan = document.getElementById('jenis_laporan').value;
    const mitraId = document.getElementById('mitra_id').value;
    const format = document.getElementById('format').value;
    
    // Validation
    if (jenisLaporan === 'mitra' && !mitraId) {
        alert('Silakan pilih mitra untuk laporan detail');
        return;
    }
    
    // Submit form
    form.submit();
}
</script>
@endsection 