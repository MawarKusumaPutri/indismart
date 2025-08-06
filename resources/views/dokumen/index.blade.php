@extends('layouts.app')

@section('title', 'Daftar Dokumen')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h4 class="page-title">Daftar Dokumen</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ Auth::user()->isMitra() ? route('mitra.dashboard') : route('staff.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Dokumen</li>
            </ol>
        </div>
        <div class="col-md-6 text-end">
            @if(Auth::user()->isMitra())
                <a href="{{ route('dokumen.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Dokumen
                </a>
            @endif
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
                <!-- Search Box Utama -->
                <div class="row mb-4">
                    <div class="col-12">
                        <form method="GET" action="{{ route('dokumen.index') }}" class="d-flex" id="searchForm">
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-white">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control form-control-lg" id="search" name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Cari dokumen berdasarkan nama, jenis proyek, nomor kontak, lokasi, status, atau keterangan..."
                                       autocomplete="off">
                                <button type="submit" class="btn btn-primary" id="searchBtn">
                                    <i class="bi bi-search me-1"></i> Cari
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('dokumen.index') }}" class="btn btn-outline-secondary" id="clearSearch">
                                        <i class="bi bi-x-lg"></i>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Filter Lanjutan (Collapsible) -->
                <div class="row">
                    <div class="col-12">
                        <button class="btn btn-outline-primary btn-sm mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#advancedFilter" aria-expanded="false" aria-controls="advancedFilter">
                            <i class="bi bi-funnel me-1"></i> Filter Lanjutan
                        </button>
                        
                        <div class="collapse" id="advancedFilter">
                            <form method="GET" action="{{ route('dokumen.index') }}" class="row g-3">
                                <!-- Preserve search parameter -->
                                @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                                
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
                                    <label for="status_implementasi" class="form-label">Status Implementasi</label>
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
                                    <label for="status_review" class="form-label">Status Review</label>
                                    <select class="form-select" id="status_review" name="status_review">
                                        <option value="">Semua Status</option>
                                        <option value="approved" {{ request('status_review') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                        <option value="rejected" {{ request('status_review') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                        <option value="pending" {{ request('status_review') == 'pending' ? 'selected' : '' }}>Menunggu Review</option>
                                        <option value="none" {{ request('status_review') == 'none' ? 'selected' : '' }}>Belum Direview</option>
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
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Dokumen</h5>
                    <div class="d-flex align-items-center">
                        @if(request('search') || request('jenis_proyek') || request('status_implementasi') || request('status_review') || request('nomor_kontak') || request('witel') || request('sto') || request('site_name'))
                            <span class="badge bg-info me-2">
                                <i class="bi bi-funnel me-1"></i> Filter Aktif
                            </span>
                        @endif
                        <span class="badge bg-secondary">
                            <i class="bi bi-file-earmark-text me-1"></i> {{ $dokumen->total() }} Dokumen
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Search Results Info -->
                @if(request('search') || request('jenis_proyek') || request('status_implementasi') || request('status_review') || request('nomor_kontak') || request('witel') || request('sto') || request('site_name'))
                    <div class="alert alert-info mb-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-search me-2"></i>
                            <div>
                                <strong>Hasil Pencarian:</strong>
                                @if(request('search'))
                                    <span class="badge bg-primary me-1">"{{ request('search') }}"</span>
                                @endif
                                @if(request('jenis_proyek'))
                                    <span class="badge bg-success me-1">Jenis: {{ request('jenis_proyek') }}</span>
                                @endif
                                @if(request('status_implementasi'))
                                    <span class="badge bg-warning me-1">Status: {{ request('status_implementasi') }}</span>
                                @endif
                                @if(request('status_review'))
                                    <span class="badge bg-info me-1">Review: {{ request('status_review') }}</span>
                                @endif
                                <span class="text-muted">({{ $dokumen->count() }} dari {{ $dokumen->total() }} dokumen)</span>
                            </div>
                        </div>
                    </div>
                @endif
                @if($dokumen->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    @if(Auth::user()->isStaff())
                                        <th>Mitra</th>
                                    @endif
                                    <th>Nama Dokumen</th>
                                    <th>Jenis Proyek</th>
                                    <th>Nomor Kontak</th>
                                    <th>Lokasi</th>
                                    <th>Status Implementasi</th>
                                    <th>Status Review</th>
                                    <th>Tanggal Dokumen</th>
                                    <th>File</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dokumen as $index => $item)
                                    <tr>
                                        <td>{{ $dokumen->firstItem() + $index }}</td>
                                        @if(Auth::user()->isStaff())
                                            <td>{{ $item->user->name }}</td>
                                        @endif
                                        <td>
                                            <strong>{{ $item->nama_dokumen }}</strong>
                                        </td>
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
                                        <td>
                                            @php
                                                $reviewStatus = $item->getReviewStatus();
                                                $reviewStatusBadge = match($reviewStatus) {
                                                    'approved' => 'bg-success',
                                                    'rejected' => 'bg-danger',
                                                    'pending' => 'bg-warning',
                                                    default => 'bg-secondary'
                                                };
                                                $reviewStatusText = match($reviewStatus) {
                                                    'approved' => 'Disetujui',
                                                    'rejected' => 'Ditolak',
                                                    'pending' => 'Menunggu Review',
                                                    default => 'Belum Direview'
                                                };
                                                $reviewStatusIcon = match($reviewStatus) {
                                                    'approved' => 'bi-check-circle',
                                                    'rejected' => 'bi-x-circle',
                                                    'pending' => 'bi-clock',
                                                    default => 'bi-dash-circle'
                                                };
                                            @endphp
                                            <span class="badge {{ $reviewStatusBadge }}">
                                                <i class="bi {{ $reviewStatusIcon }} me-1"></i>
                                                {{ $reviewStatusText }}
                                            </span>
                                            @if($item->latestReview)
                                                <br>
                                                <small class="text-muted">
                                                    {{ $item->latestReview->reviewer->name }}
                                                    <br>
                                                    {{ $item->latestReview->created_at->format('d/m/Y H:i') }}
                                                </small>
                                            @endif
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
                                                @if(Auth::user()->isMitra() && $item->user_id === Auth::id())
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
                                                @else
                                                    @if($item->file_path)
                                                        <a href="{{ route('dokumen.download', $item) }}" class="btn btn-sm btn-outline-success" title="Download">
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                    @endif
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const searchForm = document.getElementById('searchForm');
    const searchBtn = document.getElementById('searchBtn');
    const clearSearch = document.getElementById('clearSearch');
    
    if (searchInput) {
        // Focus search box setelah halaman dimuat
        setTimeout(() => {
            searchInput.focus();
        }, 200);
        
        // Handle Enter key untuk submit
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchForm.submit();
            }
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + K untuk focus ke search box
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                searchInput.focus();
            }
        });
    }
    
    // Clear search button
    if (clearSearch) {
        clearSearch.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = '{{ route('dokumen.index') }}';
        });
    }
    
    // Highlight search terms in table
    const searchTerm = '{{ request('search') }}';
    if (searchTerm && searchTerm.trim() !== '') {
        const tableCells = document.querySelectorAll('td');
        tableCells.forEach(cell => {
            const text = cell.textContent;
            if (text.toLowerCase().includes(searchTerm.toLowerCase())) {
                // Escape HTML untuk keamanan
                const escapedSearchTerm = searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                cell.innerHTML = text.replace(new RegExp(escapedSearchTerm, 'gi'), match => 
                    `<mark class="bg-warning">${match}</mark>`
                );
            }
        });
    }
});
</script>
@endsection 