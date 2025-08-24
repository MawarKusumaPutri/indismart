@extends('layouts.app')

@section('title', 'Detail Dokumen')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h4 class="page-title">Detail Dokumen</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('mitra.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('dokumen.index') }}">Dokumen</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('dokumen.index') }}" class="btn btn-secondary me-2">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <a href="{{ route('dokumen.edit', $dokumen) }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <form method="POST" action="{{ route('dokumen.destroy', $dokumen) }}" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash me-1"></i> Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informasi Dokumen</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Nama Dokumen</strong></td>
                                <td width="60%">{{ $dokumen->nama_dokumen }}</td>
                            </tr>
                            <tr>
                                <td width="40%"><strong>Jenis Proyek</strong></td>
                                <td width="60%">{{ $dokumen->jenis_proyek }}</td>
                            </tr>
                            <tr>
                                                        <td><strong>Nomor Kontrak</strong></td>
                        <td>{{ $dokumen->nomor_kontrak }}</td>
                            </tr>
                            <tr>
                                <td><strong>Witel</strong></td>
                                <td>{{ $dokumen->witel }}</td>
                            </tr>
                            <tr>
                                <td><strong>STO</strong></td>
                                <td>{{ $dokumen->sto }}</td>
                            </tr>
                            <tr>
                                <td><strong>Site Name</strong></td>
                                <td>{{ $dokumen->site_name }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Status</strong></td>
                                <td width="60%">
                                    @php
                                        $statusColors = [
                                            'inisiasi' => 'bg-primary',
                                            'planning' => 'bg-info',
                                            'executing' => 'bg-warning',
                                            'controlling' => 'bg-secondary',
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
                                    <span class="badge {{ $statusColors[$dokumen->status_implementasi] ?? 'bg-secondary' }}">
                                        {{ $statusLabels[$dokumen->status_implementasi] ?? $dokumen->status_implementasi }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Dokumen</strong></td>
                                <td>{{ $dokumen->tanggal_dokumen->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Dibuat Pada</strong></td>
                                <td>{{ $dokumen->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Terakhir Diupdate</strong></td>
                                <td>{{ $dokumen->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>File</strong></td>
                                <td>
                                    @if($dokumen->file_path)
                                        <span class="badge bg-success">
                                            <i class="bi bi-file-earmark-check me-1"></i> Ada
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-file-earmark-x me-1"></i> Tidak Ada
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                @if($dokumen->keterangan)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6><strong>Keterangan:</strong></h6>
                            <p class="text-muted">{{ $dokumen->keterangan }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">File Dokumen</h5>
            </div>
            <div class="card-body">
                @if($dokumen->file_path)
                    <div class="text-center mb-3">
                        <i class="bi bi-file-earmark-text display-1 text-primary"></i>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Nama File:</strong><br>
                        <span class="text-muted">{{ $dokumen->file_name }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Ukuran File:</strong><br>
                        <span class="text-muted">{{ $dokumen->file_size }} KB</span>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('dokumen.download', $dokumen) }}" class="btn btn-success">
                            <i class="bi bi-download me-1"></i> Download File
                        </a>
                        
                        <form method="POST" action="{{ route('dokumen.delete-file', $dokumen) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus file ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-trash me-1"></i> Hapus File
                            </button>
                        </form>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-file-earmark-x display-1 text-muted"></i>
                        <h6 class="mt-3">Tidak ada file</h6>
                        <p class="text-muted">Dokumen ini tidak memiliki file yang diupload.</p>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header bg-white">
                <h5 class="mb-0">Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('dokumen.edit', $dokumen) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-1"></i> Edit Dokumen
                    </a>
                    <a href="{{ route('dokumen.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-list me-1"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Foto Section -->
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Foto Proyek</h5>
                <span class="badge bg-info">{{ $dokumen->fotos->count() }} Foto</span>
            </div>
            <div class="card-body">
                @if($dokumen->fotos->count() > 0)
                    <div class="row">
                        @foreach($dokumen->fotos as $foto)
                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="card h-100">
                                    <div class="position-relative">
                                        <img src="{{ Storage::url($foto->file_path) }}" 
                                             class="card-img-top" 
                                             alt="{{ $foto->caption ?? 'Foto Proyek' }}"
                                             style="height: 200px; object-fit: cover; cursor: pointer;"
                                             onclick="showImageModal('{{ Storage::url($foto->file_path) }}', '{{ $foto->caption ?? 'Foto Proyek' }}')">
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <span class="badge bg-dark">{{ $loop->iteration }}</span>
                                        </div>
                                    </div>
                                    <div class="card-body p-2">
                                        @if($foto->caption)
                                            <p class="card-text small mb-2">{{ $foto->caption }}</p>
                                        @endif
                                        <div class="d-grid gap-1">
                                            <a href="{{ route('fotos.download', $foto) }}" 
                                               class="btn btn-success btn-sm">
                                                <i class="bi bi-download me-1"></i> Download
                                            </a>
                                            @if(Auth::user()->isMitra() && $dokumen->user_id === Auth::id())
                                                <form method="POST" action="{{ route('fotos.destroy', $foto) }}" 
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto ini?')"
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                                        <i class="bi bi-trash me-1"></i> Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-images display-1 text-muted"></i>
                        <h6 class="mt-3">Tidak ada foto</h6>
                        <p class="text-muted">Dokumen ini belum memiliki foto proyek.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Review Section -->
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Riwayat Review</h5>
                @if(Auth::user()->isKaryawan())
                    <a href="{{ route('reviews.create', $dokumen) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Review
                    </a>
                @endif
            </div>
            <div class="card-body">
                @if($dokumen->reviews->count() > 0)
                    <div class="timeline">
                        @foreach($dokumen->reviews->sortByDesc('created_at') as $review)
                            <div class="timeline-item mb-4">
                                <div class="d-flex">
                                    <div class="timeline-icon me-3">
                                        @php
                                            $iconClass = match($review->status) {
                                                'approved' => 'bi bi-check-circle-fill text-success',
                                                'rejected' => 'bi bi-x-circle-fill text-danger',
                                                default => 'bi bi-clock-fill text-warning'
                                            };
                                        @endphp
                                        <i class="{{ $iconClass }} fs-4"></i>
                                    </div>
                                    <div class="timeline-content flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0">
                                                Review oleh {{ $review->reviewer->name }}
                                                <span class="badge {{ $review->status === 'approved' ? 'bg-success' : ($review->status === 'rejected' ? 'bg-danger' : 'bg-warning') }} ms-2">
                                                    {{ ucfirst($review->status) }}
                                                </span>
                                            </h6>
                                            <small class="text-muted">{{ $review->created_at->format('d M Y H:i') }}</small>
                                        </div>
                                        @if($review->komentar)
                                            <p class="mb-2">{{ $review->komentar }}</p>
                                        @endif
                                        @if($review->rating)
                                            <div class="rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi {{ $i <= $review->rating ? 'bi-star-fill text-warning' : 'bi-star' }}"></i>
                                                @endfor
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-clipboard-x display-1 text-muted"></i>
                        <h6 class="mt-3">Belum ada review</h6>
                        <p class="text-muted">Dokumen ini belum memiliki review dari karyawan.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding: 1rem;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 1rem;
    }
    .timeline-item:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 0.85rem;
        top: 2.5rem;
        bottom: 0;
        width: 2px;
        background-color: #e9ecef;
    }
    .timeline-icon {
        width: 2rem;
        height: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
        position: relative;
        background: white;
    }
    .timeline-content {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 0.5rem;
    }
</style>

<!-- Modal untuk preview foto -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Preview Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="" class="img-fluid" style="max-height: 70vh;">
                <p id="modalCaption" class="mt-3 text-muted"></p>
            </div>
        </div>
    </div>
</div>

<script>
function showImageModal(imageSrc, caption) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('modalCaption').textContent = caption || 'Foto Proyek';
    
    const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
    imageModal.show();
}
</script>
@endsection 