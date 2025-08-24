@extends('layouts.app')

@section('title', 'Review Dokumen')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h4 class="page-title">Review Dokumen</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('reviews.index') }}">Review Dokumen</a></li>
                <li class="breadcrumb-item active">Review Baru</li>
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
                                <td><strong>Mitra:</strong></td>
                                <td>{{ $dokumen->user->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $dokumen->user->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jenis Proyek:</strong></td>
                                <td><span class="badge bg-info">{{ $dokumen->jenis_proyek }}</span></td>
                            </tr>
                            <tr>
                                                        <td><strong>Nomor Kontrak:</strong></td>
                        <td>{{ $dokumen->nomor_kontrak }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Witel:</strong></td>
                                <td>{{ $dokumen->witel }}</td>
                            </tr>
                            <tr>
                                <td><strong>STO:</strong></td>
                                <td>{{ $dokumen->sto }}</td>
                            </tr>
                            <tr>
                                <td><strong>Site Name:</strong></td>
                                <td>{{ $dokumen->site_name }}</td>
                            </tr>
                                                         <tr>
                                 <td><strong>Status Implementasi:</strong></td>
                                 <td>
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
                        </table>
                    </div>
                </div>
                
                @if($dokumen->keterangan)
                    <div class="mt-3">
                        <strong>Keterangan:</strong>
                        <p class="mb-0">{{ $dokumen->keterangan }}</p>
                    </div>
                @endif
                
                @if($dokumen->file_path)
                    <div class="mt-3">
                        <strong>File:</strong>
                        <div class="mt-2">
                            <a href="{{ route('dokumen.download', $dokumen) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-download me-1"></i> Download File
                            </a>
                            <small class="text-muted ms-2">{{ $dokumen->file_name }} ({{ $dokumen->file_size }} KB)</small>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Form Review</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('reviews.store', $dokumen) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status Review *</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="">Pilih Status</option>
                            <option value="approved">Disetujui</option>
                            <option value="rejected">Ditolak</option>
                            <option value="pending">Pending</option>
                        </select>
                        @error('status')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    

                    
                    <div class="mb-3">
                        <label for="komentar" class="form-label">Komentar</label>
                        <textarea name="komentar" id="komentar" rows="4" class="form-control" placeholder="Berikan komentar review...">{{ old('komentar') }}</textarea>
                        @error('komentar')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Simpan Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('form');
    const statusSelect = document.getElementById('status');
    
    form.addEventListener('submit', function(e) {
        if (!statusSelect.value) {
            e.preventDefault();
            alert('Pilih status review terlebih dahulu!');
            statusSelect.focus();
        }
    });
});
</script>
@endpush 