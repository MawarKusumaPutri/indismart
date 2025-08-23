@extends('layouts.app')

@section('title', 'Tambah Dokumen Baru')

@section('styles')
<style>
    /* Upload Foto Styles */
    .upload-foto-container {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 20px;
        background-color: #f8f9fa;
    }

    .upload-area {
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 30px;
        border: 2px dashed #6c757d;
        border-radius: 8px;
        margin-top: 15px;
        background-color: #f8f9fa;
    }

    .upload-area:hover {
        background-color: #e9ecef;
        border-color: #495057;
    }

    .upload-area.dragover {
        background-color: #d4edda;
        border-color: #28a745;
        transform: scale(1.02);
    }

    .upload-area.dragging {
        background-color: #cce5ff;
        border-color: #007bff;
        border-style: solid;
    }

    .upload-icon {
        font-size: 3rem;
        color: #6c757d;
        margin-bottom: 15px;
    }

    .upload-text {
        font-size: 1.1rem;
        font-weight: 500;
        color: #495057;
        margin-bottom: 10px;
    }

    .upload-info {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 0;
    }

    .foto-preview-container {
        margin-top: 20px;
    }

    .foto-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }

    .foto-preview-item {
        position: relative;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        background: white;
    }

    .foto-preview-item img {
        width: 100%;
        height: 120px;
        object-fit: cover;
    }

    .foto-preview-item .foto-caption {
        padding: 8px;
        font-size: 0.8rem;
    }

    .foto-preview-item .foto-caption input {
        width: 100%;
        border: none;
        outline: none;
        font-size: 0.8rem;
    }

    .foto-preview-item .remove-foto {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        font-size: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .foto-preview-item .remove-foto:hover {
        background: rgba(220, 53, 69, 1);
    }

    .foto-validation {
        text-align: center;
    }

    .foto-validation.error {
        color: #dc3545;
    }

    .foto-validation.success {
        color: #28a745;
    }

    /* File List Styles */
    .file-list {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 10px;
        background-color: #f8f9fa;
    }

    .file-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px;
        margin-bottom: 5px;
        background: white;
        border-radius: 4px;
        border: 1px solid #e9ecef;
    }

    .file-item:last-child {
        margin-bottom: 0;
    }

    .file-info {
        display: flex;
        align-items: center;
        flex: 1;
    }

    .file-icon {
        margin-right: 10px;
        color: #6c757d;
    }

    .file-details {
        flex: 1;
    }

    .file-name {
        font-weight: 500;
        color: #495057;
        margin-bottom: 2px;
    }

    .file-size {
        font-size: 0.8rem;
        color: #6c757d;
    }

    .file-status {
        margin-left: 10px;
        font-size: 0.8rem;
    }

    .file-status.valid {
        color: #28a745;
    }

    .file-status.invalid {
        color: #dc3545;
    }

    .remove-file {
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        font-size: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 10px;
    }

    .remove-file:hover {
        background: #c82333;
    }
</style>
@endsection

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h4 class="page-title">Tambah Dokumen Baru</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('mitra.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('dokumen.index') }}">Dokumen</a></li>
                <li class="breadcrumb-item active">Tambah Baru</li>
            </ol>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('dokumen.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Form Dokumen</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('dokumen.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="nama_dokumen" class="form-label">Nama Dokumen <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_dokumen') is-invalid @enderror" id="nama_dokumen" name="nama_dokumen" value="{{ old('nama_dokumen') }}" placeholder="Masukkan nama dokumen" required>
                                @error('nama_dokumen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jenis_proyek" class="form-label">Jenis Proyek <span class="text-danger">*</span></label>
                                <select class="form-select @error('jenis_proyek') is-invalid @enderror" id="jenis_proyek" name="jenis_proyek" required>
                                    <option value="">Pilih Jenis Proyek</option>
                                    <option value="Instalasi Baru" {{ old('jenis_proyek') == 'Instalasi Baru' ? 'selected' : '' }}>Instalasi Baru</option>
                                    <option value="Migrasi" {{ old('jenis_proyek') == 'Migrasi' ? 'selected' : '' }}>Migrasi</option>
                                    <option value="Upgrade" {{ old('jenis_proyek') == 'Upgrade' ? 'selected' : '' }}>Upgrade</option>
                                    <option value="Maintenance" {{ old('jenis_proyek') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    <option value="Troubleshooting" {{ old('jenis_proyek') == 'Troubleshooting' ? 'selected' : '' }}>Troubleshooting</option>
                                    <option value="Survey" {{ old('jenis_proyek') == 'Survey' ? 'selected' : '' }}>Survey</option>
                                    <option value="Audit" {{ old('jenis_proyek') == 'Audit' ? 'selected' : '' }}>Audit</option>
                                    <option value="Lainnya" {{ old('jenis_proyek') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('jenis_proyek')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nomor_kontrak" class="form-label">Nomor Kontrak <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('nomor_kontrak') is-invalid @enderror" 
                                           id="nomor_kontrak" name="nomor_kontrak" 
                                           value="{{ old('nomor_kontrak', auth()->user()->nomor_kontrak) }}" 
                                           placeholder="Nomor kontrak akan diisi otomatis" 
                                           {{ auth()->user()->nomor_kontrak ? 'readonly' : 'required' }}>
                                    @if(!auth()->user()->nomor_kontrak)
                                        <button type="button" class="btn btn-outline-warning" onclick="alert('Silakan hubungi karyawan untuk mendapatkan nomor kontrak terlebih dahulu.')">
                                            <i class="bi bi-exclamation-triangle me-1"></i> Belum Ada
                                        </button>
                                    @else
                                        <span class="input-group-text bg-success text-white">
                                            <i class="bi bi-check-circle"></i>
                                        </span>
                                    @endif
                                </div>
                                @error('nomor_kontrak')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if(!auth()->user()->nomor_kontrak)
                                    <div class="alert alert-warning mt-2">
                                        <small><i class="bi bi-info-circle me-1"></i> Anda belum memiliki nomor kontrak. Silakan hubungi karyawan untuk mendapatkan nomor kontrak terlebih dahulu.</small>
                                    </div>
                                @else
                                    <small class="text-muted">Nomor kontrak Anda: <strong>{{ auth()->user()->nomor_kontrak }}</strong></small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="witel" class="form-label">Witel <span class="text-danger">*</span></label>
                                <select class="form-select @error('witel') is-invalid @enderror" id="witel" name="witel" required>
                                    <option value="">Pilih Witel</option>
                                    <option value="Jakarta" {{ old('witel') == 'Jakarta' ? 'selected' : '' }}>Jakarta</option>
                                    <option value="Bandung" {{ old('witel') == 'Bandung' ? 'selected' : '' }}>Bandung</option>
                                    <option value="Surabaya" {{ old('witel') == 'Surabaya' ? 'selected' : '' }}>Surabaya</option>
                                    <option value="Medan" {{ old('witel') == 'Medan' ? 'selected' : '' }}>Medan</option>
                                    <option value="Yogyakarta" {{ old('witel') == 'Yogyakarta' ? 'selected' : '' }}>Yogyakarta</option>
                                    <option value="Semarang" {{ old('witel') == 'Semarang' ? 'selected' : '' }}>Semarang</option>
                                    <option value="Palembang" {{ old('witel') == 'Palembang' ? 'selected' : '' }}>Palembang</option>
                                    <option value="Makassar" {{ old('witel') == 'Makassar' ? 'selected' : '' }}>Makassar</option>
                                </select>
                                @error('witel')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="sto" class="form-label">STO <span class="text-danger">*</span></label>
                                <select class="form-select @error('sto') is-invalid @enderror" id="sto" name="sto" required>
                                    <option value="">Pilih STO</option>
                                    <!-- Options will be populated dynamically -->
                                </select>
                                @error('sto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="site_name" class="form-label">Site Name <span class="text-danger">*</span></label>
                                <select class="form-select @error('site_name') is-invalid @enderror" id="site_name" name="site_name" required>
                                    <option value="">Pilih Site Name</option>
                                    <!-- Options will be populated dynamically -->
                                </select>
                                @error('site_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status_implementasi" class="form-label">Status Implementasi <span class="text-danger">*</span></label>
                                <select class="form-select @error('status_implementasi') is-invalid @enderror" id="status_implementasi" name="status_implementasi" required>
                                    <option value="">Pilih Status</option>
                                    <option value="inisiasi" {{ old('status_implementasi') == 'inisiasi' ? 'selected' : '' }}>Inisiasi</option>
                                    <option value="planning" {{ old('status_implementasi') == 'planning' ? 'selected' : '' }}>Planning</option>
                                    <option value="executing" {{ old('status_implementasi') == 'executing' ? 'selected' : '' }}>Executing</option>
                                    <option value="controlling" {{ old('status_implementasi') == 'controlling' ? 'selected' : '' }}>Controlling</option>
                                    <option value="closing" {{ old('status_implementasi') == 'closing' ? 'selected' : '' }}>Closing</option>
                                </select>
                                @error('status_implementasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jenis_dokumen" class="form-label">Jenis Dokumen <span class="text-danger">*</span></label>
                                <select class="form-select @error('jenis_dokumen') is-invalid @enderror" id="jenis_dokumen" name="jenis_dokumen" required>
                                    <option value="">Pilih Status Implementasi terlebih dahulu</option>
                                </select>
                                @error('jenis_dokumen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal_dokumen" class="form-label">Tanggal Dokumen <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_dokumen') is-invalid @enderror" id="tanggal_dokumen" name="tanggal_dokumen" value="{{ old('tanggal_dokumen') }}" required>
                                @error('tanggal_dokumen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="file" class="form-label">Upload File</label>
                                <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                                <div class="form-text">Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX (Max: 10MB)</div>
                                @error('file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3" placeholder="Masukkan keterangan tambahan">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Upload Foto Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Upload Foto <span class="text-danger">*</span></label>
                                <div class="upload-foto-container">
                                    <!-- File Input yang Visible -->
                                    <div class="mb-3">
                                        <input type="file" class="form-control" id="fotoInput" name="fotos[]" multiple accept=".jpg,.jpeg,.png">
                                        <div class="form-text">Minimal 3 foto, maksimal 10 foto (JPG, JPEG, PNG - Max: 5MB)</div>
                                    </div>
                                    
                                    <!-- Drag & Drop Area -->
                                    <div class="upload-area" id="uploadArea">
                                        <div class="upload-icon">
                                            <i class="bi bi-camera"></i>
                                        </div>
                                        <p class="upload-text">Atau drag & drop foto ke sini</p>
                                        <p class="upload-info">Pilih minimal 3 foto untuk melanjutkan</p>
                                        <div class="drag-instructions mt-2">
                                            <small class="text-muted">
                                                <i class="bi bi-mouse me-1"></i>Klik untuk browse atau 
                                                <i class="bi bi-arrows-move me-1"></i>drag file dari komputer Anda
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <!-- File List -->
                                    <div class="selected-files mt-3" id="selectedFiles" style="display: none;">
                                        <h6>File yang Dipilih:</h6>
                                        <div class="file-list" id="fileList"></div>
                                    </div>
                                    
                                    <!-- Preview Container -->
                                    <div class="foto-preview-container" id="fotoPreviewContainer" style="display: none;">
                                        <h6>Preview Foto:</h6>
                                        <div class="foto-preview-grid" id="fotoPreviewGrid"></div>
                                        <div class="foto-validation mt-2">
                                            <small class="text-muted">Minimal 3 foto diperlukan</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Simpan Dokumen
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
// Data STO berdasarkan Witel
const stoData = {
    'Jakarta': ['STO Kebayoran', 'STO Gambir', 'STO Cempaka Putih', 'STO Tanjung Priok', 'STO Jakarta Utara'],
    'Bandung': ['STO Dago', 'STO Hegarmanah', 'STO Ujung Berung', 'STO Cimahi', 'STO Bandung Selatan'],
    'Surabaya': ['STO Gubeng', 'STO Manyar', 'STO Rungkut', 'STO Surabaya Pusat', 'STO Surabaya Barat'],
    'Medan': ['STO Medan Kota', 'STO Medan Denai', 'STO Medan Amplas', 'STO Medan Timur', 'STO Medan Barat'],
    'Yogyakarta': ['STO Kotabaru', 'STO Bantul', 'STO Sleman', 'STO Yogyakarta Pusat', 'STO Yogyakarta Selatan'],
    'Semarang': ['STO Semarang Pusat', 'STO Semarang Utara', 'STO Semarang Timur', 'STO Semarang Barat'],
    'Palembang': ['STO Palembang Pusat', 'STO Palembang Utara', 'STO Palembang Timur', 'STO Palembang Barat'],
    'Makassar': ['STO Makassar Pusat', 'STO Makassar Utara', 'STO Makassar Timur', 'STO Makassar Barat']
};

// Data Site Name berdasarkan STO
const siteNameData = {
    'STO Kebayoran': ['Site KB-01', 'Site KB-02', 'Site KB-03', 'Site KB-04', 'Site KB-05'],
    'STO Gambir': ['Site GM-01', 'Site GM-02', 'Site GM-03', 'Site GM-04', 'Site GM-05'],
    'STO Cempaka Putih': ['Site CP-01', 'Site CP-02', 'Site CP-03', 'Site CP-04', 'Site CP-05'],
    'STO Tanjung Priok': ['Site TP-01', 'Site TP-02', 'Site TP-03', 'Site TP-04', 'Site TP-05'],
    'STO Jakarta Utara': ['Site JU-01', 'Site JU-02', 'Site JU-03', 'Site JU-04', 'Site JU-05'],
    'STO Dago': ['Site DG-01', 'Site DG-02', 'Site DG-03', 'Site DG-04', 'Site DG-05'],
    'STO Hegarmanah': ['Site HG-01', 'Site HG-02', 'Site HG-03', 'Site HG-04', 'Site HG-05'],
    'STO Ujung Berung': ['Site UB-01', 'Site UB-02', 'Site UB-03', 'Site UB-04', 'Site UB-05'],
    'STO Cimahi': ['Site CM-01', 'Site CM-02', 'Site CM-03', 'Site CM-04', 'Site CM-05'],
    'STO Bandung Selatan': ['Site BS-01', 'Site BS-02', 'Site BS-03', 'Site BS-04', 'Site BS-05'],
    'STO Gubeng': ['Site GB-01', 'Site GB-02', 'Site GB-03', 'Site GB-04', 'Site GB-05'],
    'STO Manyar': ['Site MY-01', 'Site MY-02', 'Site MY-03', 'Site MY-04', 'Site MY-05'],
    'STO Rungkut': ['Site RK-01', 'Site RK-02', 'Site RK-03', 'Site RK-04', 'Site RK-05'],
    'STO Surabaya Pusat': ['Site SP-01', 'Site SP-02', 'Site SP-03', 'Site SP-04', 'Site SP-05'],
    'STO Surabaya Barat': ['Site SB-01', 'Site SB-02', 'Site SB-03', 'Site SB-04', 'Site SB-05'],
    'STO Medan Kota': ['Site MK-01', 'Site MK-02', 'Site MK-03', 'Site MK-04', 'Site MK-05'],
    'STO Medan Denai': ['Site MD-01', 'Site MD-02', 'Site MD-03', 'Site MD-04', 'Site MD-05'],
    'STO Medan Amplas': ['Site MA-01', 'Site MA-02', 'Site MA-03', 'Site MA-04', 'Site MA-05'],
    'STO Medan Timur': ['Site MT-01', 'Site MT-02', 'Site MT-03', 'Site MT-04', 'Site MT-05'],
    'STO Medan Barat': ['Site MB-01', 'Site MB-02', 'Site MB-03', 'Site MB-04', 'Site MB-05'],
    'STO Kotabaru': ['Site KB-01', 'Site KB-02', 'Site KB-03', 'Site KB-04', 'Site KB-05'],
    'STO Bantul': ['Site BT-01', 'Site BT-02', 'Site BT-03', 'Site BT-04', 'Site BT-05'],
    'STO Sleman': ['Site SL-01', 'Site SL-02', 'Site SL-03', 'Site SL-04', 'Site SL-05'],
    'STO Yogyakarta Pusat': ['Site YP-01', 'Site YP-02', 'Site YP-03', 'Site YP-04', 'Site YP-05'],
    'STO Yogyakarta Selatan': ['Site YS-01', 'Site YS-02', 'Site YS-03', 'Site YS-04', 'Site YS-05'],
    'STO Semarang Pusat': ['Site SP-01', 'Site SP-02', 'Site SP-03', 'Site SP-04', 'Site SP-05'],
    'STO Semarang Utara': ['Site SU-01', 'Site SU-02', 'Site SU-03', 'Site SU-04', 'Site SU-05'],
    'STO Semarang Timur': ['Site ST-01', 'Site ST-02', 'Site ST-03', 'Site ST-04', 'Site ST-05'],
    'STO Semarang Barat': ['Site SB-01', 'Site SB-02', 'Site SB-03', 'Site SB-04', 'Site SB-05'],
    'STO Palembang Pusat': ['Site PP-01', 'Site PP-02', 'Site PP-03', 'Site PP-04', 'Site PP-05'],
    'STO Palembang Utara': ['Site PN-01', 'Site PN-02', 'Site PN-03', 'Site PN-04', 'Site PN-05'],
    'STO Palembang Timur': ['Site PT-01', 'Site PT-02', 'Site PT-03', 'Site PT-04', 'Site PT-05'],
    'STO Palembang Barat': ['Site PB-01', 'Site PB-02', 'Site PB-03', 'Site PB-04', 'Site PB-05'],
    'STO Makassar Pusat': ['Site MP-01', 'Site MP-02', 'Site MP-03', 'Site MP-04', 'Site MP-05'],
    'STO Makassar Utara': ['Site MN-01', 'Site MN-02', 'Site MN-03', 'Site MN-04', 'Site MN-05'],
    'STO Makassar Timur': ['Site MT-01', 'Site MT-02', 'Site MT-03', 'Site MT-04', 'Site MT-05'],
    'STO Makassar Barat': ['Site MB-01', 'Site MB-02', 'Site MB-03', 'Site MB-04', 'Site MB-05']
};

// Fungsi untuk mengisi dropdown STO berdasarkan Witel yang dipilih
document.getElementById('witel').addEventListener('change', function() {
    const witel = this.value;
    const stoSelect = document.getElementById('sto');
    const siteNameSelect = document.getElementById('site_name');
    
    // Reset dropdown STO
    stoSelect.innerHTML = '<option value="">Pilih STO</option>';
    
    // Reset dropdown Site Name
    siteNameSelect.innerHTML = '<option value="">Pilih Site Name</option>';
    
    if (witel && stoData[witel]) {
        // Isi dropdown STO berdasarkan Witel yang dipilih
        stoData[witel].forEach(sto => {
            const option = document.createElement('option');
            option.value = sto;
            option.textContent = sto;
            stoSelect.appendChild(option);
        });
    }
});

// Fungsi untuk mengisi dropdown Site Name berdasarkan STO yang dipilih
document.getElementById('sto').addEventListener('change', function() {
    const sto = this.value;
    const siteNameSelect = document.getElementById('site_name');
    
    // Reset dropdown Site Name
    siteNameSelect.innerHTML = '<option value="">Pilih Site Name</option>';
    
    if (sto && siteNameData[sto]) {
        // Isi dropdown Site Name berdasarkan STO yang dipilih
        siteNameData[sto].forEach(site => {
            const option = document.createElement('option');
            option.value = site;
            option.textContent = site;
            siteNameSelect.appendChild(option);
        });
    }
});

// Data Jenis Dokumen berdasarkan Status Implementasi
const jenisDokumenData = {
    'inisiasi': [
        'Dokumen Kontrak Harga Satuan',
        'Dokumen Surat Pesanan'
    ],
    'planning': [
        'Berita Acara Design Review Meeting',
        'As Planned Drawing',
        'Rencana Anggaran Belanja',
        'Lainnya (Eviden Pendukung)'
    ],
    'executing': [
        'Berita Acara Penyelesaian Pekerjaan',
        'Berita Acara Uji Fungsi',
        'Lampiran Hasil Uji Fungsi',
        'Lainnya (Eviden Pendukung)'
    ],
    'controlling': [
        'Berita Acara Uji Terima',
        'Lampiran Hasil Uji Terima',
        'As Built Drawing Uji Terima'
    ],
    'closing': [
        'Berita Acara Rekonsiliasi',
        'Lampiran BoQ Hasil Rekonsiliasi',
        'Berita Acara Serah Terima'
    ]
};

// Fungsi untuk mengisi dropdown Jenis Dokumen berdasarkan Status Implementasi yang dipilih
document.getElementById('status_implementasi').addEventListener('change', function() {
    const statusImplementasi = this.value;
    const jenisDokumenSelect = document.getElementById('jenis_dokumen');
    
    // Reset dropdown Jenis Dokumen
    jenisDokumenSelect.innerHTML = '<option value="">Pilih Jenis Dokumen</option>';
    
    if (statusImplementasi && jenisDokumenData[statusImplementasi]) {
        // Isi dropdown Jenis Dokumen berdasarkan Status Implementasi yang dipilih
        jenisDokumenData[statusImplementasi].forEach(jenis => {
            const option = document.createElement('option');
            option.value = jenis;
            option.textContent = jenis;
            jenisDokumenSelect.appendChild(option);
        });
    }
});

// Set nilai awal jika ada old input
document.addEventListener('DOMContentLoaded', function() {
    const oldWitel = '{{ old("witel") }}';
    const oldSto = '{{ old("sto") }}';
    const oldSiteName = '{{ old("site_name") }}';
    const oldStatusImplementasi = '{{ old("status_implementasi") }}';
    const oldJenisDokumen = '{{ old("jenis_dokumen") }}';
    
            if (oldWitel) {
            document.getElementById('witel').value = oldWitel;
            // Trigger change event untuk mengisi STO
            document.getElementById('witel').dispatchEvent(new Event('change'));
        }
    });

    // Upload Foto JavaScript
    let selectedFotos = [];
    const uploadArea = document.getElementById('uploadArea');
    const fotoInput = document.getElementById('fotoInput');
    const fotoPreviewContainer = document.getElementById('fotoPreviewContainer');
    const fotoPreviewGrid = document.getElementById('fotoPreviewGrid');
    const fotoValidation = document.querySelector('.foto-validation');
    const selectedFiles = document.getElementById('selectedFiles');
    const fileList = document.getElementById('fileList');

    // Click to select files
    uploadArea.addEventListener('click', () => {
        fotoInput.click();
    });

    // Drag and drop functionality
    let dragCounter = 0;

    // Prevent default drag behaviors on entire page
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
        document.addEventListener(eventName, preventDefaults, false);
    });

    // Add drag & drop to entire page for better UX
    document.addEventListener('dragenter', (e) => {
        if (e.dataTransfer.types.includes('Files')) {
            uploadArea.classList.add('dragging');
            uploadArea.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

    document.addEventListener('dragleave', (e) => {
        // Only remove if leaving the entire document
        if (e.clientX === 0 && e.clientY === 0) {
            uploadArea.classList.remove('dragging');
        }
    });

    document.addEventListener('drop', (e) => {
        uploadArea.classList.remove('dragging');
        // If not dropped on upload area, handle it anyway
        if (!uploadArea.contains(e.target)) {
            handleDrop(e);
        }
    });

    // Highlight drop area when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, unhighlight, false);
    });

    // Handle dropped files
    uploadArea.addEventListener('drop', handleDrop, false);

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight(e) {
        uploadArea.classList.add('dragover');
        if (e.type === 'dragenter') {
            dragCounter++;
        }
    }

    function unhighlight(e) {
        if (e.type === 'dragleave') {
            dragCounter--;
        }
        if (dragCounter === 0 || e.type === 'drop') {
            uploadArea.classList.remove('dragover');
            dragCounter = 0;
        }
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = Array.from(dt.files);
        
        // Filter only image files
        const imageFiles = files.filter(file => file.type.startsWith('image/'));
        
        if (imageFiles.length === 0) {
            alert('Hanya file gambar yang diizinkan!');
            return;
        }
        
        if (imageFiles.length !== files.length) {
            alert(`${files.length - imageFiles.length} file bukan gambar dan diabaikan.`);
        }
        
        // Update input file dengan drag & drop files
        const dataTransfer = new DataTransfer();
        imageFiles.forEach(file => dataTransfer.items.add(file));
        fotoInput.files = dataTransfer.files;
        
        selectedFotos = []; // Reset array
        handleFotos(imageFiles);
        updateFileList();
        
        // Visual feedback
        uploadArea.style.background = '#d4edda';
        uploadArea.style.borderColor = '#28a745';
        
        // Success message
        const successMsg = document.createElement('div');
        successMsg.className = 'alert alert-success mt-2';
        successMsg.innerHTML = `<i class="bi bi-check-circle me-2"></i>${imageFiles.length} foto berhasil ditambahkan!`;
        uploadArea.appendChild(successMsg);
        
        setTimeout(() => {
            uploadArea.style.background = '';
            uploadArea.style.borderColor = '';
            if (successMsg.parentNode) {
                successMsg.remove();
            }
        }, 3000);
    }

    // File input change
    fotoInput.addEventListener('change', (e) => {
        const files = Array.from(e.target.files);
        selectedFotos = []; // Reset array
        handleFotos(files);
        updateFileList();
    });

    function handleFotos(files) {
        const validFiles = files.filter(file => {
            const isValidType = ['image/jpeg', 'image/jpg', 'image/png'].includes(file.type);
            const isValidSize = file.size <= 5 * 1024 * 1024; // 5MB
            
            if (!isValidType) {
                alert(`File ${file.name} bukan format gambar yang valid.`);
                return false;
            }
            
            if (!isValidSize) {
                alert(`File ${file.name} terlalu besar. Maksimal 5MB.`);
                return false;
            }
            
            return true;
        });

        if (selectedFotos.length + validFiles.length > 10) {
            alert('Maksimal hanya bisa upload 10 foto.');
            return;
        }

        validFiles.forEach(file => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const foto = {
                    file: file,
                    preview: e.target.result,
                    id: Date.now() + Math.random()
                };
                selectedFotos.push(foto);
                updateFotoPreview();
                updateFileList();
            };
            reader.readAsDataURL(file);
        });
    }

    function updateFotoPreview() {
        if (selectedFotos.length === 0) {
            fotoPreviewContainer.style.display = 'none';
            return;
        }

        fotoPreviewContainer.style.display = 'block';
        fotoPreviewGrid.innerHTML = '';

        selectedFotos.forEach((foto, index) => {
            const fotoItem = document.createElement('div');
            fotoItem.className = 'foto-preview-item';
            fotoItem.innerHTML = `
                <img src="${foto.preview}" alt="Preview">
                <div class="foto-caption">
                    <input type="text" placeholder="Caption (opsional)" class="foto-caption-input" data-index="${index}">
                </div>
                <button type="button" class="remove-foto" onclick="removeFoto(${index})">
                    <i class="bi bi-x"></i>
                </button>
            `;
            fotoPreviewGrid.appendChild(fotoItem);
        });

        updateValidation();
        updateFileList();
    }

    window.removeFoto = function(index) {
        selectedFotos.splice(index, 1);
        
        // Update input file
        const dt = new DataTransfer();
        selectedFotos.forEach(foto => dt.items.add(foto.file));
        fotoInput.files = dt.files;
        
        updateFotoPreview();
        updateFileList();
    }

    function updateFileList() {
        if (selectedFotos.length === 0) {
            selectedFiles.style.display = 'none';
            return;
        }

        selectedFiles.style.display = 'block';
        fileList.innerHTML = '';

        selectedFotos.forEach((foto, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-item';
            
            const fileSize = (foto.file.size / 1024 / 1024).toFixed(2);
            const isValidType = ['image/jpeg', 'image/jpg', 'image/png'].includes(foto.file.type);
            const isValidSize = foto.file.size <= 5 * 1024 * 1024;
            
            fileItem.innerHTML = `
                <div class="file-info">
                    <div class="file-icon">
                        <i class="bi bi-image"></i>
                    </div>
                    <div class="file-details">
                        <div class="file-name">${foto.file.name}</div>
                        <div class="file-size">${fileSize} MB</div>
                    </div>
                    <div class="file-status ${isValidType && isValidSize ? 'valid' : 'invalid'}">
                        ${isValidType && isValidSize ? '✓ Valid' : '✗ Invalid'}
                    </div>
                </div>
                <button type="button" class="remove-file" onclick="removeFoto(${index})">
                    <i class="bi bi-x"></i>
                </button>
            `;
            
            fileList.appendChild(fileItem);
        });
    }

    function updateValidation() {
        if (selectedFotos.length < 3) {
            fotoValidation.innerHTML = '<small class="text-danger">Minimal 3 foto diperlukan</small>';
            fotoValidation.className = 'foto-validation error';
        } else {
            fotoValidation.innerHTML = '<small class="text-success">✓ Foto siap diupload</small>';
            fotoValidation.className = 'foto-validation success';
        }
    }

    // Form submission
    document.querySelector('form').addEventListener('submit', function(e) {
        const fileInput = document.getElementById('fotoInput');
        
        if (!fileInput.files || fileInput.files.length < 3) {
            e.preventDefault();
            alert('Minimal harus upload 3 foto.');
            return;
        }

        if (fileInput.files.length > 10) {
            e.preventDefault();
            alert('Maksimal hanya bisa upload 10 foto.');
            return;
        }

        // Validasi setiap file
        for (let i = 0; i < fileInput.files.length; i++) {
            const file = fileInput.files[i];
            
            if (!['image/jpeg', 'image/jpg', 'image/png'].includes(file.type)) {
                e.preventDefault();
                alert(`File ${file.name} bukan format gambar yang valid.`);
                return;
            }
            
            if (file.size > 5 * 1024 * 1024) {
                e.preventDefault();
                alert(`File ${file.name} terlalu besar. Maksimal 5MB.`);
                return;
            }
        }

        // Form akan disubmit secara normal ke DokumenController
        console.log('Form submitted with', fileInput.files.length, 'files');
    });
        
        // Set STO setelah dropdown terisi
        setTimeout(() => {
            if (oldSto) {
                document.getElementById('sto').value = oldSto;
                // Trigger change event untuk mengisi Site Name
                document.getElementById('sto').dispatchEvent(new Event('change'));
                
                // Set Site Name setelah dropdown terisi
                setTimeout(() => {
                    if (oldSiteName) {
                        document.getElementById('site_name').value = oldSiteName;
                    }
                }, 100);
            }
        }, 100);
    }
    
    // Set Status Implementasi dan Jenis Dokumen jika ada old input
    if (oldStatusImplementasi) {
        document.getElementById('status_implementasi').value = oldStatusImplementasi;
        // Trigger change event untuk mengisi Jenis Dokumen
        document.getElementById('status_implementasi').dispatchEvent(new Event('change'));
        
        // Set Jenis Dokumen setelah dropdown terisi
        setTimeout(() => {
            if (oldJenisDokumen) {
                document.getElementById('jenis_dokumen').value = oldJenisDokumen;
            }
        }, 100);
    }
});
</script>
@endpush 