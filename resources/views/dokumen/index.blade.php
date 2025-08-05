@extends('layouts.app')

@section('title', 'Daftar Dokumen')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h4 class="page-title">Daftar Dokumen</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('mitra.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Dokumen</li>
            </ol>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('dokumen.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Tambah Dokumen
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Filter Dokumen</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('dokumen.index') }}" class="row g-3">
                    <div class="col-md-2">
                        <label for="jenis_proyek" class="form-label">Jenis Proyek</label>
                        <select class="form-select" id="jenis_proyek" name="jenis_proyek">
                            <option value="">Semua Jenis</option>
                            <option value="Instalasi Baru" {{ request('jenis_proyek') == 'Instalasi Baru' ? 'selected' : '' }}>Instalasi Baru</option>
                            <option value="Migrasi" {{ request('jenis_proyek') == 'Migrasi' ? 'selected' : '' }}>Migrasi</option>
                            <option value="Upgrade" {{ request('jenis_proyek') == 'Upgrade' ? 'selected' : '' }}>Upgrade</option>
                            <option value="Maintenance" {{ request('jenis_proyek') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="Troubleshooting" {{ request('jenis_proyek') == 'Troubleshooting' ? 'selected' : '' }}>Troubleshooting</option>
                            <option value="Survey" {{ request('jenis_proyek') == 'Survey' ? 'selected' : '' }}>Survey</option>
                            <option value="Audit" {{ request('jenis_proyek') == 'Audit' ? 'selected' : '' }}>Audit</option>
                            <option value="Lainnya" {{ request('jenis_proyek') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="status_implementasi" class="form-label">Status</label>
                        <select class="form-select" id="status_implementasi" name="status_implementasi">
                            <option value="">Semua Status</option>
                            <option value="inisiasi" {{ request('status_implementasi') == 'inisiasi' ? 'selected' : '' }}>Inisiasi</option>
                            <option value="planning" {{ request('status_implementasi') == 'planning' ? 'selected' : '' }}>Planning</option>
                            <option value="executing" {{ request('status_implementasi') == 'executing' ? 'selected' : '' }}>Executing</option>
                            <option value="controlling" {{ request('status_implementasi') == 'controlling' ? 'selected' : '' }}>Controlling</option>
                            <option value="closing" {{ request('status_implementasi') == 'closing' ? 'selected' : '' }}>Closing</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="nomor_kontak" class="form-label">Nomor Kontak</label>
                        <input type="text" class="form-control" id="nomor_kontak" name="nomor_kontak" value="{{ request('nomor_kontak') }}" placeholder="Cari nomor kontak...">
                    </div>
                    <div class="col-md-2">
                        <label for="witel" class="form-label">Witel</label>
                        <input type="text" class="form-control" id="witel" name="witel" value="{{ request('witel') }}" placeholder="Cari witel...">
                    </div>
                    <div class="col-md-2">
                        <label for="sto" class="form-label">STO</label>
                        <input type="text" class="form-control" id="sto" name="sto" value="{{ request('sto') }}" placeholder="Cari STO...">
                    </div>
                    <div class="col-md-2">
                        <label for="site_name" class="form-label">Site Name</label>
                        <input type="text" class="form-control" id="site_name" name="site_name" value="{{ request('site_name') }}" placeholder="Cari site name...">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-search me-1"></i> Filter
                        </button>
                        <a href="{{ route('dokumen.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Daftar Dokumen</h5>
            </div>
            <div class="card-body">
                @if($dokumen->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis Proyek</th>
                                    <th>Nomor Kontak</th>
                                    <th>Lokasi</th>
                                    <th>Status</th>
                                    <th>Tanggal Dokumen</th>
                                    <th>File</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dokumen as $index => $item)
                                    <tr>
                                        <td>{{ $dokumen->firstItem() + $index }}</td>
                                        <td>{{ $item->jenis_proyek }}</td>
                                        <td>{{ $item->nomor_kontak }}</td>
                                        <td>
                                            <small class="text-muted d-block">{{ $item->witel }}</small>
                                            <small class="text-muted d-block">{{ $item->sto }}</small>
                                            <small class="text-muted">{{ $item->site_name }}</small>
                                        </td>
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
                                            <span class="badge {{ $statusColors[$item->status_implementasi] ?? 'bg-secondary' }}">
                                                {{ $statusLabels[$item->status_implementasi] ?? $item->status_implementasi }}
                                            </span>
                                        </td>
                                        <td>{{ $item->tanggal_dokumen->format('d/m/Y') }}</td>
                                        <td>
                                            @if($item->file_path)
                                                <span class="badge bg-success">
                                                    <i class="bi bi-file-earmark-check me-1"></i> Ada
                                                </span>
                                                <br>
                                                <small class="text-muted">{{ $item->file_name }}</small>
                                                <br>
                                                <small class="text-muted">{{ $item->file_size }} KB</small>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="bi bi-file-earmark-x me-1"></i> Tidak Ada
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('dokumen.show', $item) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('dokumen.edit', $item) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                @if($item->file_path)
                                                    <a href="{{ route('dokumen.download', $item) }}" class="btn btn-sm btn-outline-success" title="Download">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                @endif
                                                <form method="POST" action="{{ route('dokumen.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
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
                        <i class="bi bi-file-earmark-text display-1 text-muted"></i>
                        <h5 class="mt-3">Belum ada dokumen</h5>
                        <p class="text-muted">Mulai dengan menambahkan dokumen pertama Anda.</p>
                        <a href="{{ route('dokumen.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Dokumen
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 