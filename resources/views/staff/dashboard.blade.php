@extends('layouts.app')

@section('title', 'Dashboard Staff')

@section('content')
<div class="container-fluid">
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
                                                <a href="#" class="btn btn-sm btn-outline-primary">Detail</a>
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
});
</script>
@endpush