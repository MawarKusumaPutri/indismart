@extends('layouts.app')

@section('title', 'Review Dokumen')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h4 class="page-title">Review Dokumen</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Review Dokumen</li>
            </ol>
        </div>
        <div class="col-md-6 text-end">
            <span class="badge bg-warning fs-6" id="pendingCount">
                <i class="bi bi-clock me-1"></i> <span id="pendingNumber">0</span> Dokumen Pending
            </span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">Daftar Dokumen untuk Review</h5>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('reviews.index') }}" class="row g-3">
                            <div class="col-md-4">
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="jenis_proyek" class="form-select">
                                    <option value="">Semua Jenis Proyek</option>
                                    <option value="Instalasi Baru" {{ request('jenis_proyek') == 'Instalasi Baru' ? 'selected' : '' }}>Instalasi Baru</option>
                                    <option value="Migrasi" {{ request('jenis_proyek') == 'Migrasi' ? 'selected' : '' }}>Migrasi</option>
                                    <option value="Upgrade" {{ request('jenis_proyek') == 'Upgrade' ? 'selected' : '' }}>Upgrade</option>
                                    <option value="Maintenance" {{ request('jenis_proyek') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    <option value="Troubleshooting" {{ request('jenis_proyek') == 'Troubleshooting' ? 'selected' : '' }}>Troubleshooting</option>
                                    <option value="Survey" {{ request('jenis_proyek') == 'Survey' ? 'selected' : '' }}>Survey</option>
                                    <option value="Audit" {{ request('jenis_proyek') == 'Audit' ? 'selected' : '' }}>Audit</option>
                                    <option value="Recovery" {{ request('jenis_proyek') == 'Recovery' ? 'selected' : '' }}>Recovery</option>
                                    <option value="Preventif" {{ request('jenis_proyek') == 'Preventif' ? 'selected' : '' }}>Preventif</option>
                                    <option value="Relokasi" {{ request('jenis_proyek') == 'Relokasi' ? 'selected' : '' }}>Relokasi</option>
                                    <option value="Lainnya" {{ request('jenis_proyek') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search me-1"></i> Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($dokumen->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mitra</th>
                                    <th>Jenis Proyek</th>
                                    <th>Lokasi</th>
                                    <th>Status Implementasi</th>
                                    <th>Status Review</th>
                                    <th>Tanggal Upload</th>
                                    <th>Reviewer</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dokumen as $doc)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-3">
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="bi bi-person text-white"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $doc->user->name }}</h6>
                                                    <small class="text-muted">{{ $doc->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $doc->jenis_proyek }}</span>
                                        </td>
                                        <td>
                                            <small>{{ $doc->witel }} - {{ $doc->sto }} - {{ $doc->site_name }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $doc->status_implementasi }}</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $doc->getReviewStatusBadgeClass() }}">
                                                {{ $doc->getReviewStatusText() }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>@indonesianDate($doc->created_at)</small>
                                        </td>
                                        <td>
                                            @if($doc->latestReview && $doc->latestReview->reviewer)
                                                <small>{{ $doc->latestReview->reviewer->name }}</small>
                                            @else
                                                <small class="text-muted">-</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('dokumen.show', $doc) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i> Lihat
                                                </a>
                                                @if(!$doc->isReviewed() || $doc->getReviewStatus() == 'pending')
                                                    <a href="{{ route('reviews.create', $doc) }}" class="btn btn-sm btn-outline-success">
                                                        <i class="bi bi-check-circle"></i> Review
                                                    </a>
                                                @else
                                                    <a href="{{ route('reviews.edit', $doc->latestReview) }}" class="btn btn-sm btn-outline-warning">
                                                        <i class="bi bi-pencil"></i> Edit Review
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-4">
                        {{ $dokumen->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-clipboard-check display-1 text-muted"></i>
                        <h5 class="mt-3">Tidak ada dokumen untuk direview</h5>
                        <p class="text-muted">Semua dokumen telah direview atau belum ada dokumen yang diupload oleh mitra.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load pending count
    loadPendingCount();
    
    // Auto refresh pending count every 30 seconds
    setInterval(loadPendingCount, 30000);
});

function loadPendingCount() {
    fetch('{{ route("reviews.pending-count") }}')
        .then(response => response.json())
        .then(data => {
            const pendingNumber = document.getElementById('pendingNumber');
            const pendingCount = document.getElementById('pendingCount');
            
            pendingNumber.textContent = data.count;
            
            if (data.count > 0) {
                pendingCount.classList.remove('bg-warning');
                pendingCount.classList.add('bg-danger');
            } else {
                pendingCount.classList.remove('bg-danger');
                pendingCount.classList.add('bg-warning');
            }
        });
}
</script>
@endpush 