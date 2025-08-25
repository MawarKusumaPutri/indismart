@extends('layouts.app')

@section('title', 'Manajemen Nomor Kontrak')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h4 class="page-title">Manajemen Nomor Kontrak</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Nomor Kontrak</li>
            </ol>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('nomor-kontrak.bulk-assign') }}" class="btn btn-success" onclick="return confirm('Yakin ingin menugaskan nomor kontrak secara otomatis untuk semua mitra yang belum memiliki nomor kontrak?')">
                <i class="bi bi-magic me-1"></i> Tugaskan Otomatis
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Daftar Mitra dan Nomor Kontrak</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>
                                    <input type="checkbox" class="form-check-input" id="selectAll">
                                </th>
                                <th>No</th>
                                <th>Nama Mitra</th>
                                <th>Email</th>
                                <th>Nomor Kontrak</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mitraUsers as $index => $mitra)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input mitra-checkbox" value="{{ $mitra->id }}" name="selected_mitra[]">
                                    </td>
                                    <td>{{ $index + 1 + ($mitraUsers->currentPage() - 1) * $mitraUsers->perPage() }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                @if($mitra->avatar)
                                                    <img src="{{ asset('storage/' . $mitra->avatar) }}" alt="Avatar" class="rounded-circle" width="40">
                                                @else
                                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                                        <span class="text-white fw-bold">{{ strtoupper(substr($mitra->name, 0, 1)) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $mitra->name }}</h6>
                                                <small class="text-muted">ID: {{ $mitra->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $mitra->email }}</td>
                                    <td>
                                        @if($mitra->nomor_kontrak)
                                            <span class="badge bg-success">{{ $mitra->nomor_kontrak }}</span>
                                        @else
                                            <span class="badge bg-warning">Belum Ditugaskan</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($mitra->nomor_kontrak)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('nomor-kontrak.assign', $mitra->id) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-people display-4"></i>
                                            <p class="mt-2">Tidak ada data mitra</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <button type="button" class="btn btn-success btn-sm" id="assignSelectedBtn" style="display: none;">
                            <i class="bi bi-hash me-1"></i> Tugaskan Nomor Kontrak ke Mitra Terpilih
                        </button>
                    </div>
                    <div>
                        {{ $mitraUsers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const mitraCheckboxes = document.querySelectorAll('.mitra-checkbox');
    const assignSelectedBtn = document.getElementById('assignSelectedBtn');

    // Handle select all checkbox
    selectAllCheckbox.addEventListener('change', function() {
        mitraCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateActionButtons();
    });

    // Handle individual checkboxes
    mitraCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectAllState();
            updateActionButtons();
        });
    });

    // Update select all state
    function updateSelectAllState() {
        const checkedCount = document.querySelectorAll('.mitra-checkbox:checked').length;
        const totalCount = mitraCheckboxes.length;
        
        if (checkedCount === 0) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = false;
        } else if (checkedCount === totalCount) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.indeterminate = true;
            selectAllCheckbox.checked = false;
        }
    }

    // Update action buttons visibility
    function updateActionButtons() {
        const checkedCount = document.querySelectorAll('.mitra-checkbox:checked').length;
        
        if (checkedCount > 0) {
            assignSelectedBtn.style.display = 'inline-block';
        } else {
            assignSelectedBtn.style.display = 'none';
        }
    }



    // Handle assign selected button
    assignSelectedBtn.addEventListener('click', function() {
        const selectedMitra = Array.from(document.querySelectorAll('.mitra-checkbox:checked'))
            .map(checkbox => checkbox.value);
        
        if (selectedMitra.length === 0) {
            alert('Pilih mitra terlebih dahulu!');
            return;
        }

        if (selectedMitra.length === 1) {
            // Redirect to assign page for single mitra
            window.location.href = `{{ route('nomor-kontrak.index') }}/${selectedMitra[0]}/assign`;
        } else {
            alert('Untuk menugaskan nomor kontrak manual, pilih satu mitra saja.');
        }
    });
});
</script>
@endpush
