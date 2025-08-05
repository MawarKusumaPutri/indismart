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
                                <td width="40%"><strong>Jenis Proyek</strong></td>
                                <td width="60%">{{ $dokumen->jenis_proyek }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nomor Kontak</strong></td>
                                <td>{{ $dokumen->nomor_kontak }}</td>
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
</div>
@endsection 