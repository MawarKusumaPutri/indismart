@extends('layouts.app')

@section('title', 'Edit Review Dokumen')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h4 class="page-title">Edit Review Dokumen</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('reviews.index') }}">Review Dokumen</a></li>
                <li class="breadcrumb-item active">Edit Review</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Detail Dokumen</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Mitra</strong></td>
                                <td width="60%">{{ $review->dokumen->user->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email</strong></td>
                                <td>{{ $review->dokumen->user->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jenis Proyek</strong></td>
                                <td>
                                    <span class="badge bg-primary">{{ $review->dokumen->jenis_proyek }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Nomor Kontrak</strong></td>
                                <td>{{ $review->dokumen->nomor_kontrak }}</td>
                            </tr>
                            <tr>
                                <td><strong>Witel</strong></td>
                                <td>{{ $review->dokumen->witel }}</td>
                            </tr>
                            <tr>
                                <td><strong>STO</strong></td>
                                <td>{{ $review->dokumen->sto }}</td>
                            </tr>
                            <tr>
                                <td><strong>Site Name</strong></td>
                                <td>{{ $review->dokumen->site_name }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Status Implementasi</strong></td>
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
                                    <span class="badge {{ $statusColors[$review->dokumen->status_implementasi] ?? 'bg-secondary' }}">
                                        {{ $statusLabels[$review->dokumen->status_implementasi] ?? $review->dokumen->status_implementasi }}
                                    </span>
                                </td>
                            </tr>
                                                         <tr>
                                 <td><strong>Tanggal Dokumen</strong></td>
                                 <td>@indonesianDateOnly($review->dokumen->tanggal_dokumen)</td>
                             </tr>
                             <tr>
                                 <td><strong>Dibuat Pada</strong></td>
                                 <td>@indonesianDate($review->dokumen->created_at)</td>
                             </tr>
                            <tr>
                                <td><strong>File</strong></td>
                                <td>
                                    @if($review->dokumen->file_path)
                                        <span class="badge bg-success">
                                            <i class="bi bi-file-earmark-check me-1"></i> Ada
                                        </span>
                                        <br>
                                        <small class="text-muted">{{ $review->dokumen->file_name }}</small>
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
                
                @if($review->dokumen->keterangan)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6><strong>Keterangan:</strong></h6>
                            <p class="text-muted">{{ $review->dokumen->keterangan }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Form Edit Review</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('reviews.update', $review) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status Review *</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="">Pilih Status</option>
                            <option value="approved" {{ old('status', $review->status) == 'approved' ? 'selected' : '' }}>Disetujui</option>
                            <option value="rejected" {{ old('status', $review->status) == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            <option value="pending" {{ old('status', $review->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                        @error('status')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="komentar" class="form-label">Komentar</label>
                        <textarea name="komentar" id="komentar" rows="4" class="form-control" placeholder="Berikan komentar review...">{{ old('komentar', $review->komentar) }}</textarea>
                        @error('komentar')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Update Review
                        </button>
                        <a href="{{ route('reviews.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Review Info Card -->
        <div class="card mt-3">
            <div class="card-header bg-white">
                <h6 class="mb-0">Informasi Review</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <small class="text-muted">Reviewer:</small><br>
                        <strong>{{ $review->reviewer->name }}</strong>
                    </div>
                                         <div class="col-6">
                         <small class="text-muted">Tanggal Review:</small><br>
                         <strong>@indonesianDate($review->reviewed_at)</strong>
                     </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12">
                        <small class="text-muted">Status Saat Ini:</small><br>
                        <span class="badge {{ $review->status === 'approved' ? 'bg-success' : ($review->status === 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                            {{ ucfirst($review->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
