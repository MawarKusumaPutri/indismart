@extends('layouts.app')

@section('title', 'Tugaskan Nomor Kontrak')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h4 class="page-title">Tugaskan Nomor Kontrak</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('nomor-kontrak.index') }}">Nomor Kontrak</a></li>
                <li class="breadcrumb-item active">Tugaskan</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Form Penugasan Nomor Kontrak</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Informasi Mitra</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nama:</strong></td>
                                <td>{{ $mitra->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $mitra->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nomor Kontrak Saat Ini:</strong></td>
                                <td>
                                    @if($mitra->nomor_kontrak)
                                        <span class="badge bg-success">{{ $mitra->nomor_kontrak }}</span>
                                    @else
                                        <span class="badge bg-warning">Belum Ditugaskan</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <form action="{{ route('nomor-kontrak.store', $mitra->id) }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="nomor_kontrak" class="form-label">Nomor Kontrak <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('nomor_kontrak') is-invalid @enderror" 
                                           id="nomor_kontrak" name="nomor_kontrak" 
                                           value="{{ old('nomor_kontrak', $mitra->nomor_kontrak) }}" 
                                           placeholder="Masukkan nomor kontrak" required>
                                    <button type="button" class="btn btn-outline-secondary" id="generateBtn">
                                        <i class="bi bi-magic me-1"></i> Generate
                                    </button>
                                </div>
                                @error('nomor_kontrak')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Format: KTRK[YYYY][MM][XXXX] (contoh: KTRK2025080001)</small>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Simpan
                        </button>
                        <a href="{{ route('nomor-kontrak.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informasi</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="bi bi-info-circle me-1"></i> Panduan Penomoran</h6>
                    <ul class="mb-0">
                        <li>Format: <strong>KTRK[YYYY][MM][XXXX]</strong></li>
                        <li>KTRK: Prefix tetap</li>
                        <li>YYYY: Tahun (4 digit)</li>
                        <li>MM: Bulan (2 digit)</li>
                        <li>XXXX: Urutan (4 digit)</li>
                    </ul>
                </div>
                
                <div class="alert alert-warning">
                    <h6><i class="bi bi-exclamation-triangle me-1"></i> Perhatian</h6>
                    <ul class="mb-0">
                        <li>Nomor kontrak harus unik</li>
                        <li>Tidak dapat diubah setelah dokumen dibuat</li>
                        <li>Mitra akan mendapat notifikasi otomatis</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const generateBtn = document.getElementById('generateBtn');
    const nomorKontrakInput = document.getElementById('nomor_kontrak');
    
    generateBtn.addEventListener('click', function() {
        // Show loading state
        generateBtn.disabled = true;
        generateBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Generating...';
        
        // Make AJAX request to generate contract number
        fetch('{{ route("nomor-kontrak.generate") }}', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            nomorKontrakInput.value = data.nomor_kontrak;
            generateBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i> Generated!';
            setTimeout(() => {
                generateBtn.innerHTML = '<i class="bi bi-magic me-1"></i> Generate';
            }, 2000);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat generate nomor kontrak');
            generateBtn.innerHTML = '<i class="bi bi-magic me-1"></i> Generate';
        })
        .finally(() => {
            generateBtn.disabled = false;
        });
    });
});
</script>
@endpush
