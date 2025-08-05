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
                        <div class="stat-value">42</div>
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
                        <div class="stat-value">156</div>
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
                        <div class="stat-value">38</div>
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
                                <div class="stat-value">24</div>
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
                                <div class="stat-value" id="pendingReviewCount">0</div>
                                <div class="stat-label">Dokumen Pending Review</div>
                            </div>
                        </div>
                    </div>
                </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Mitra</h5>
                    <button class="btn btn-sm btn-primary">Tambah Mitra</button>
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
                                <tr>
                                    <td>PT Telkom Indonesia</td>
                                    <td>contact@telkom.co.id</td>
                                    <td>12</td>
                                    <td><span class="badge bg-success">Aktif</span></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="#" class="btn btn-sm btn-outline-primary">Detail</a>
                                            <a href="#" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>PT Indosat Ooredoo</td>
                                    <td>support@indosat.com</td>
                                    <td>8</td>
                                    <td><span class="badge bg-success">Aktif</span></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="#" class="btn btn-sm btn-outline-primary">Detail</a>
                                            <a href="#" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>PT XL Axiata</td>
                                    <td>info@xl.co.id</td>
                                    <td>6</td>
                                    <td><span class="badge bg-success">Aktif</span></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="#" class="btn btn-sm btn-outline-primary">Detail</a>
                                            <a href="#" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>PT Smartfren</td>
                                    <td>cs@smartfren.com</td>
                                    <td>4</td>
                                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="#" class="btn btn-sm btn-outline-primary">Detail</a>
                                            <a href="#" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>PT Hutchison 3 Indonesia</td>
                                    <td>support@three.co.id</td>
                                    <td>3</td>
                                    <td><span class="badge bg-secondary">Tidak Aktif</span></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="#" class="btn btn-sm btn-outline-primary">Detail</a>
                                            <a href="#" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        </div>
                                    </td>
                                </tr>
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
                        <li class="list-group-item d-flex align-items-center py-3">
                            <div class="me-3">
                                <div class="bg-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                    <i class="bi bi-file-earmark-plus text-white"></i>
                                </div>
                            </div>
                            <div>
                                <p class="mb-0">PT Telkom Indonesia menambahkan dokumen baru</p>
                                <small class="text-muted">2 jam yang lalu</small>
                            </div>
                        </li>
                        <li class="list-group-item d-flex align-items-center py-3">
                            <div class="me-3">
                                <div class="bg-success rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                    <i class="bi bi-check-circle text-white"></i>
                                </div>
                            </div>
                            <div>
                                <p class="mb-0">Proyek Pemasangan Fiber Optik selesai</p>
                                <small class="text-muted">5 jam yang lalu</small>
                            </div>
                        </li>
                        <li class="list-group-item d-flex align-items-center py-3">
                            <div class="me-3">
                                <div class="bg-info rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                    <i class="bi bi-person-plus text-white"></i>
                                </div>
                            </div>
                            <div>
                                <p class="mb-0">PT XL Axiata terdaftar sebagai mitra baru</p>
                                <small class="text-muted">1 hari yang lalu</small>
                            </div>
                        </li>
                        <li class="list-group-item d-flex align-items-center py-3">
                            <div class="me-3">
                                <div class="bg-warning rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                    <i class="bi bi-exclamation-triangle text-white"></i>
                                </div>
                            </div>
                            <div>
                                <p class="mb-0">Proyek Upgrade Jaringan 4G tertunda</p>
                                <small class="text-muted">2 hari yang lalu</small>
                            </div>
                        </li>
                        <li class="list-group-item d-flex align-items-center py-3">
                            <div class="me-3">
                                <div class="bg-secondary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                    <i class="bi bi-gear text-white"></i>
                                </div>
                            </div>
                            <div>
                                <p class="mb-0">Pembaruan sistem berhasil diterapkan</p>
                                <small class="text-muted">3 hari yang lalu</small>
                            </div>
                        </li>
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