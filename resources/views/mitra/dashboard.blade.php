@extends('layouts.app')

@section('title', 'Dashboard Mitra')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card card-stat">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $totalDokumen }}</div>
                        <div class="stat-label">Total Dokumen</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stat">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $proyekAktif }}</div>
                        <div class="stat-label">Proyek Aktif</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stat">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $proyekSelesai }}</div>
                        <div class="stat-label">Proyek Selesai</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Proyek Terbaru</h5>
                </div>
                <div class="card-body">
                    <!-- Form Filter Proyek -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <label for="jenisProjek" class="form-label">Jenis Projek</label>
                            <select class="form-select" id="jenisProjek">
                                <option value="" selected>Semua Jenis</option>
                                <option value="fiber">Pemasangan Fiber Optik</option>
                                <option value="upgrade">Upgrade Jaringan</option>
                                <option value="instalasi">Instalasi BTS</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="nomorKontrak" class="form-label">Nomor Kontrak</label>
                            <input type="text" class="form-control" id="nomorKontrak" placeholder="Cari nomor kontrak...">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="lokasi" class="form-label">Lokasi</label>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button" id="lokasiDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Pilih Lokasi
                                </button>
                                <div class="dropdown-menu p-3" style="width: 300px;">
                                    <div class="mb-3">
                                        <label for="witel" class="form-label">Witel</label>
                                        <select class="form-select" id="witel">
                                            <option value="" selected>Pilih Witel</option>
                                            <option value="jakarta">Jakarta</option>
                                            <option value="bandung">Bandung</option>
                                            <option value="surabaya">Surabaya</option>
                                            <option value="medan">Medan</option>
                                            <option value="yogyakarta">Yogyakarta</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="sto" class="form-label">STO</label>
                                        <select class="form-select" id="sto">
                                            <option value="" selected>Pilih STO</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="siteName" class="form-label">Site Name</label>
                                        <select class="form-select" id="siteName">
                                            <option value="" selected>Pilih Site Name</option>
                                        </select>
                                    </div>
                                    <div class="d-grid">
                                        <button class="btn btn-primary btn-sm" type="button" id="terapkanLokasi">Terapkan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3 d-flex align-items-end">
                            <button type="button" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </div>
                    <!-- End Form Filter Proyek -->
                    
                    <div class="table-responsive">
                        <table class="table table-hover table-projects">
                            <thead>
                                <tr>
                                    <th>Nama Proyek</th>
                                    <th>Jenis Projek</th>
                                    <th>Nomor Kontrak</th>
                                    <th>Lokasi</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($proyekTerbaru as $proyek)
                                    <tr>
                                        <td>{{ $proyek->nama_proyek }}</td>
                                        <td>{{ $proyek->jenis_proyek }}</td>
                                        <td>{{ $proyek->nomor_kontak }}</td>
                                        <td>{{ $proyek->witel }} ({{ $proyek->sto }}, {{ $proyek->site_name }})</td>
                                        <td>{{ $proyek->tanggal_dokumen->format('d M Y') }}</td>
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
                                            <span class="badge {{ $statusColors[$proyek->status_implementasi] ?? 'bg-secondary' }}">
                                                {{ $statusLabels[$proyek->status_implementasi] ?? ucfirst($proyek->status_implementasi) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('dokumen.show', $proyek) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="bi bi-file-earmark-text display-4 text-muted d-block mb-3"></i>
                                            <p class="text-muted mb-0">Belum ada proyek yang ditambahkan</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-stat {
        padding: 1.5rem;
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        background-color: rgba(226, 38, 38, 0.1);
        color: #e22626;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 1.5rem;
        margin-right: 1rem;
    }
    .stat-value {
        font-size: 1.5rem;
        font-weight: bold;
    }
    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
    }
    .table-projects th {
        font-weight: 600;
        background-color: #f8f9fa;
    }
</style>
@endpush

@push('scripts')
<script>
    // Data STO berdasarkan Witel
    const stoData = {
        'jakarta': ['STO Kebayoran', 'STO Gambir', 'STO Cempaka Putih'],
        'bandung': ['STO Dago', 'STO Hegarmanah', 'STO Ujung Berung'],
        'surabaya': ['STO Gubeng', 'STO Manyar', 'STO Rungkut'],
        'medan': ['STO Medan Kota', 'STO Medan Denai', 'STO Medan Amplas'],
        'yogyakarta': ['STO Kotabaru', 'STO Bantul', 'STO Sleman']
    };
    
    // Data Site Name berdasarkan STO
    const siteNameData = {
        'STO Kebayoran': ['Site KB-01', 'Site KB-02', 'Site KB-03'],
        'STO Gambir': ['Site GM-01', 'Site GM-02', 'Site GM-03'],
        'STO Cempaka Putih': ['Site CP-01', 'Site CP-02', 'Site CP-03'],
        // Data untuk STO lainnya akan diisi saat implementasi
    };
    
    // Fungsi untuk mengisi dropdown STO berdasarkan Witel yang dipilih
    document.getElementById('witel').addEventListener('change', function() {
        const witel = this.value;
        const stoSelect = document.getElementById('sto');
        
        // Reset dropdown STO
        stoSelect.innerHTML = '<option value="" selected>Pilih STO</option>';
        
        // Reset dropdown Site Name
        document.getElementById('siteName').innerHTML = '<option value="" selected>Pilih Site Name</option>';
        
        if (witel) {
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
        const siteNameSelect = document.getElementById('siteName');
        
        // Reset dropdown Site Name
        siteNameSelect.innerHTML = '<option value="" selected>Pilih Site Name</option>';
        
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
    
    // Fungsi untuk menampilkan lokasi yang dipilih pada tombol dropdown
    document.getElementById('terapkanLokasi').addEventListener('click', function() {
        const witel = document.getElementById('witel').options[document.getElementById('witel').selectedIndex].text;
        const sto = document.getElementById('sto').options[document.getElementById('sto').selectedIndex].text;
        const siteName = document.getElementById('siteName').options[document.getElementById('siteName').selectedIndex].text;
        
        let lokasiText = 'Pilih Lokasi';
        
        if (witel !== 'Pilih Witel') {
            lokasiText = witel;
            
            if (sto !== 'Pilih STO') {
                lokasiText += ' - ' + sto;
                
                if (siteName !== 'Pilih Site Name') {
                    lokasiText += ' - ' + siteName;
                }
            }
        }
        
        document.getElementById('lokasiDropdown').textContent = lokasiText;
        
        // Tutup dropdown setelah terapkan
        const bsDropdown = bootstrap.Dropdown.getInstance(document.getElementById('lokasiDropdown'));
        
        if (bsDropdown) {
            bsDropdown.hide();
        } else {
            // Alternatif jika getInstance tidak berfungsi
            const dropdownMenu = document.querySelector('#lokasiDropdown').closest('.dropdown').querySelector('.dropdown-menu');
            dropdownMenu.classList.remove('show');
        }
    });
</script>
@endpush