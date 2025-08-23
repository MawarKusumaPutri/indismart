@extends('layouts.app')

@section('title', 'Edit Dokumen')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h4 class="page-title">Edit Dokumen</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('mitra.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('dokumen.index') }}">Dokumen</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('dokumen.show', $dokumen) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Form Edit Dokumen</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('dokumen.update', $dokumen) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="nama_dokumen" class="form-label">Nama Dokumen <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_dokumen') is-invalid @enderror" id="nama_dokumen" name="nama_dokumen" value="{{ old('nama_dokumen', $dokumen->nama_dokumen) }}" placeholder="Masukkan nama dokumen" required>
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
                                    <option value="Instalasi Baru" {{ old('jenis_proyek', $dokumen->jenis_proyek) == 'Instalasi Baru' ? 'selected' : '' }}>Instalasi Baru</option>
                                    <option value="Migrasi" {{ old('jenis_proyek', $dokumen->jenis_proyek) == 'Migrasi' ? 'selected' : '' }}>Migrasi</option>
                                    <option value="Upgrade" {{ old('jenis_proyek', $dokumen->jenis_proyek) == 'Upgrade' ? 'selected' : '' }}>Upgrade</option>
                                    <option value="Maintenance" {{ old('jenis_proyek', $dokumen->jenis_proyek) == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    <option value="Troubleshooting" {{ old('jenis_proyek', $dokumen->jenis_proyek) == 'Troubleshooting' ? 'selected' : '' }}>Troubleshooting</option>
                                    <option value="Survey" {{ old('jenis_proyek', $dokumen->jenis_proyek) == 'Survey' ? 'selected' : '' }}>Survey</option>
                                    <option value="Audit" {{ old('jenis_proyek', $dokumen->jenis_proyek) == 'Audit' ? 'selected' : '' }}>Audit</option>
                                    <option value="Lainnya" {{ old('jenis_proyek', $dokumen->jenis_proyek) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
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
                                           value="{{ old('nomor_kontrak', $dokumen->nomor_kontrak) }}" 
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
                                    <option value="Jakarta" {{ old('witel', $dokumen->witel) == 'Jakarta' ? 'selected' : '' }}>Jakarta</option>
                                    <option value="Bandung" {{ old('witel', $dokumen->witel) == 'Bandung' ? 'selected' : '' }}>Bandung</option>
                                    <option value="Surabaya" {{ old('witel', $dokumen->witel) == 'Surabaya' ? 'selected' : '' }}>Surabaya</option>
                                    <option value="Medan" {{ old('witel', $dokumen->witel) == 'Medan' ? 'selected' : '' }}>Medan</option>
                                    <option value="Yogyakarta" {{ old('witel', $dokumen->witel) == 'Yogyakarta' ? 'selected' : '' }}>Yogyakarta</option>
                                    <option value="Semarang" {{ old('witel', $dokumen->witel) == 'Semarang' ? 'selected' : '' }}>Semarang</option>
                                    <option value="Palembang" {{ old('witel', $dokumen->witel) == 'Palembang' ? 'selected' : '' }}>Palembang</option>
                                    <option value="Makassar" {{ old('witel', $dokumen->witel) == 'Makassar' ? 'selected' : '' }}>Makassar</option>
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
                                    <option value="inisiasi" {{ old('status_implementasi', $dokumen->status_implementasi) == 'inisiasi' ? 'selected' : '' }}>Inisiasi</option>
                                    <option value="planning" {{ old('status_implementasi', $dokumen->status_implementasi) == 'planning' ? 'selected' : '' }}>Planning</option>
                                    <option value="executing" {{ old('status_implementasi', $dokumen->status_implementasi) == 'executing' ? 'selected' : '' }}>Executing</option>
                                    <option value="controlling" {{ old('status_implementasi', $dokumen->status_implementasi) == 'controlling' ? 'selected' : '' }}>Controlling</option>
                                    <option value="closing" {{ old('status_implementasi', $dokumen->status_implementasi) == 'closing' ? 'selected' : '' }}>Closing</option>
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
                                    <option value="">Pilih Jenis Dokumen</option>
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
                                <input type="date" class="form-control @error('tanggal_dokumen') is-invalid @enderror" id="tanggal_dokumen" name="tanggal_dokumen" value="{{ old('tanggal_dokumen', $dokumen->tanggal_dokumen->format('Y-m-d')) }}" required>
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
                                @if($dokumen->file_path)
                                    <div class="mt-2">
                                        <small class="text-muted">File saat ini: {{ $dokumen->file_name }} ({{ $dokumen->file_size }} KB)</small>
                                    </div>
                                @endif
                                @error('file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3" placeholder="Masukkan keterangan tambahan">{{ old('keterangan', $dokumen->keterangan) }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Update Dokumen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/dropdown-automatic.js') }}"></script>
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

// Fungsi untuk mengisi dropdown STO berdasarkan Witel yang dipilih
function populateSTO(witel) {
    const stoSelect = document.getElementById('sto');
    const siteNameSelect = document.getElementById('site_name');
    
    // Reset dropdown STO
    stoSelect.innerHTML = '<option value="">Pilih STO</option>';
    
    // Reset dropdown Site Name
    siteNameSelect.innerHTML = '<option value="">Pilih Site Name</option>';
    
    console.log('Populating STO for Witel:', witel);
    
    if (witel && stoData[witel]) {
        // Isi dropdown STO berdasarkan Witel yang dipilih
        stoData[witel].forEach(sto => {
            const option = document.createElement('option');
            option.value = sto;
            option.textContent = sto;
            stoSelect.appendChild(option);
        });
        console.log('STO options added:', stoData[witel]);
    }
}

// Fungsi untuk mengisi dropdown Site Name berdasarkan STO yang dipilih
function populateSiteName(sto) {
    const siteNameSelect = document.getElementById('site_name');
    
    // Reset dropdown Site Name
    siteNameSelect.innerHTML = '<option value="">Pilih Site Name</option>';
    
    console.log('Populating Site Name for STO:', sto);
    
    if (sto && siteNameData[sto]) {
        // Isi dropdown Site Name berdasarkan STO yang dipilih
        siteNameData[sto].forEach(site => {
            const option = document.createElement('option');
            option.value = site;
            option.textContent = site;
            siteNameSelect.appendChild(option);
        });
        console.log('Site Name options added:', siteNameData[sto]);
    }
}

// Fungsi untuk mengisi dropdown Jenis Dokumen berdasarkan Status Implementasi yang dipilih
function populateJenisDokumen(statusImplementasi) {
    const jenisDokumenSelect = document.getElementById('jenis_dokumen');
    
    if (!jenisDokumenSelect) {
        console.error('Jenis Dokumen element not found!');
        return;
    }
    
    // Reset dropdown Jenis Dokumen
    jenisDokumenSelect.innerHTML = '<option value="">Pilih Jenis Dokumen</option>';
    
    console.log('Populating Jenis Dokumen for Status Implementasi:', statusImplementasi);
    console.log('Available data for this status:', jenisDokumenData[statusImplementasi]);
    
    if (statusImplementasi && jenisDokumenData[statusImplementasi]) {
        // Isi dropdown Jenis Dokumen berdasarkan Status Implementasi yang dipilih
        jenisDokumenData[statusImplementasi].forEach(jenis => {
            const option = document.createElement('option');
            option.value = jenis;
            option.textContent = jenis;
            jenisDokumenSelect.appendChild(option);
        });
        console.log('Jenis Dokumen options added:', jenisDokumenData[statusImplementasi]);
        console.log('Total options in dropdown:', jenisDokumenSelect.options.length);
    } else {
        console.log('No data found for status:', statusImplementasi);
    }
}

// Set nilai awal saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Setting up dropdown listeners');
    console.log('Jenis Dokumen Data available:', jenisDokumenData);
    console.log('Available statuses:', Object.keys(jenisDokumenData));
    
    // Event listener untuk Witel
    const witelSelect = document.getElementById('witel');
    if (witelSelect) {
        witelSelect.addEventListener('change', function() {
            console.log('Witel changed to:', this.value);
            populateSTO(this.value);
        });
    }
    
    // Event listener untuk STO
    const stoSelect = document.getElementById('sto');
    if (stoSelect) {
        stoSelect.addEventListener('change', function() {
            console.log('STO changed to:', this.value);
            populateSiteName(this.value);
        });
    }
    
    // Event listener untuk Status Implementasi - DROPDOWN OTOMATIS
    const statusSelect = document.getElementById('status_implementasi');
    if (statusSelect) {
        console.log('✅ Status Implementasi element found, adding event listener');
        statusSelect.addEventListener('change', function() {
            const selectedStatus = this.value;
            console.log('🔄 Status Implementasi changed to:', selectedStatus);
            console.log('📞 Calling populateJenisDokumen with:', selectedStatus);
            populateJenisDokumen(selectedStatus);
        });
        console.log('✅ Event listener untuk Status Implementasi berhasil ditambahkan');
    } else {
        console.error('❌ Status Implementasi element not found!');
    }
    
    // Event listener untuk Jenis Dokumen
    const jenisDokumenSelect = document.getElementById('jenis_dokumen');
    if (jenisDokumenSelect) {
        jenisDokumenSelect.addEventListener('change', function() {
            const selectedJenis = this.value;
            console.log('📄 Jenis Dokumen selected:', selectedJenis);
        });
        console.log('✅ Event listener untuk Jenis Dokumen berhasil ditambahkan');
    } else {
        console.error('❌ Jenis Dokumen element not found!');
    }

    const currentWitel = '{{ $dokumen->witel }}';
    const currentSto = '{{ $dokumen->sto }}';
    const currentSiteName = '{{ $dokumen->site_name }}';
    const currentStatusImplementasi = '{{ $dokumen->status_implementasi }}';
    const currentJenisDokumen = '{{ $dokumen->jenis_dokumen }}';
    
    console.log('Current values:', { currentWitel, currentSto, currentSiteName, currentStatusImplementasi, currentJenisDokumen });
    
    if (currentWitel) {
        document.getElementById('witel').value = currentWitel;
        // Trigger change event untuk mengisi STO
        populateSTO(currentWitel);
        
        // Set STO setelah dropdown terisi
        setTimeout(() => {
            if (currentSto) {
                document.getElementById('sto').value = currentSto;
                // Trigger change event untuk mengisi Site Name
                populateSiteName(currentSto);
                
                // Set Site Name setelah dropdown terisi
                setTimeout(() => {
                    if (currentSiteName) {
                        document.getElementById('site_name').value = currentSiteName;
                    }
                }, 100);
            }
        }, 100);
    }
    
    if (currentStatusImplementasi) {
        document.getElementById('status_implementasi').value = currentStatusImplementasi;
        populateJenisDokumen(currentStatusImplementasi);
        
        // Set Jenis Dokumen setelah dropdown terisi
        setTimeout(() => {
            if (currentJenisDokumen) {
                document.getElementById('jenis_dokumen').value = currentJenisDokumen;
            }
        }, 100);
    }
});
</script>
@endpush 