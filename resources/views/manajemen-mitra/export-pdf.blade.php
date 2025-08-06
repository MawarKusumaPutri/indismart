<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $judul ?? 'Laporan' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #fff;
            font-size: 12px;
            margin: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px 0;
            border-bottom: 3px solid #E22626;
        }
        
        .header h1 {
            color: #E22626;
            font-size: 24px;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        .header p {
            color: #666;
            margin-bottom: 5px;
        }
        
        .info-section {
            margin-bottom: 25px;
        }
        
        .info-section h2 {
            color: #E22626;
            font-size: 18px;
            margin-bottom: 15px;
            border-bottom: 2px solid #E22626;
            padding-bottom: 5px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .stat-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        
        .stat-card h3 {
            color: #E22626;
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .stat-card p {
            color: #666;
            font-size: 12px;
            margin: 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        th, td {
            border: 1px solid #dee2e6;
            padding: 8px 12px;
            text-align: left;
        }
        
        th {
            background: #E22626;
            color: white;
            font-weight: bold;
            font-size: 11px;
        }
        
        td {
            font-size: 10px;
        }
        
        .text-center {
            text-align: center;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        
        @media print {
            body {
                font-size: 10px;
                margin: 0;
            }
            
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $judul ?? 'Laporan' }}</h1>
        <p>Dibuat pada: {{ $tanggal ?? now()->format('d F Y H:i:s') }}</p>
        <p>Sistem Indismart - Manajemen Mitra</p>
    </div>

    @if(isset($statistik))
        <!-- Laporan Umum -->
        <div class="info-section">
            <h2>Statistik Umum</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>{{ $statistik['total_mitra'] ?? 0 }}</h3>
                    <p>Total Mitra</p>
                </div>
                <div class="stat-card">
                    <h3>{{ $statistik['total_dokumen'] ?? 0 }}</h3>
                    <p>Total Dokumen</p>
                </div>
                <div class="stat-card">
                    <h3>{{ $statistik['dokumen_aktif'] ?? 0 }}</h3>
                    <p>Dokumen Aktif</p>
                </div>
                <div class="stat-card">
                    <h3>{{ $statistik['dokumen_selesai'] ?? 0 }}</h3>
                    <p>Dokumen Selesai</p>
                </div>
            </div>
        </div>

        @if(isset($mitra) && $mitra->count() > 0)
        <div class="info-section">
            <h2>Daftar Mitra</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Mitra</th>
                        <th>Email</th>
                        <th class="text-center">Total Dokumen</th>
                        <th class="text-center">Dokumen Aktif</th>
                        <th class="text-center">Dokumen Selesai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mitra as $m)
                    <tr>
                        <td>{{ $m->id ?? 'N/A' }}</td>
                        <td>{{ $m->name ?? 'N/A' }}</td>
                        <td>{{ $m->email ?? 'N/A' }}</td>
                        <td class="text-center">{{ $m->total_dokumen ?? 0 }}</td>
                        <td class="text-center">{{ $m->dokumen_aktif ?? 0 }}</td>
                        <td class="text-center">{{ $m->dokumen_selesai ?? 0 }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if(isset($laporan_jenis_proyek) && $laporan_jenis_proyek->count() > 0)
        <div class="info-section">
            <h2>Laporan Jenis Proyek</h2>
            <table>
                <thead>
                    <tr>
                        <th>Jenis Proyek</th>
                        <th class="text-center">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laporan_jenis_proyek as $item)
                    <tr>
                        <td>{{ $item->jenis_proyek ?? 'N/A' }}</td>
                        <td class="text-center">{{ $item->total ?? 0 }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if(isset($laporan_status) && $laporan_status->count() > 0)
        <div class="info-section">
            <h2>Laporan Status Implementasi</h2>
            <table>
                <thead>
                    <tr>
                        <th>Status</th>
                        <th class="text-center">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laporan_status as $item)
                    <tr>
                        <td>{{ ucfirst($item->status_implementasi ?? 'N/A') }}</td>
                        <td class="text-center">{{ $item->total ?? 0 }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

    @else
        <!-- Detail Mitra -->
        @if(isset($mitra))
        <div class="info-section">
            <h2>Informasi Mitra</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>{{ $mitra->name ?? 'N/A' }}</h3>
                    <p>Nama Mitra</p>
                </div>
                <div class="stat-card">
                    <h3>{{ $mitra->email ?? 'N/A' }}</h3>
                    <p>Email</p>
                </div>
                <div class="stat-card">
                    <h3>{{ $mitra->phone ?? 'N/A' }}</h3>
                    <p>Telepon</p>
                </div>
                <div class="stat-card">
                    <h3>{{ $mitra->created_at ? $mitra->created_at->format('d/m/Y') : 'N/A' }}</h3>
                    <p>Tanggal Bergabung</p>
                </div>
            </div>
        </div>

        @if(isset($statistik_mitra))
        <div class="info-section">
            <h2>Statistik Mitra</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>{{ $statistik_mitra['total_dokumen'] ?? 0 }}</h3>
                    <p>Total Dokumen</p>
                </div>
                <div class="stat-card">
                    <h3>{{ $statistik_mitra['dokumen_aktif'] ?? 0 }}</h3>
                    <p>Dokumen Aktif</p>
                </div>
                <div class="stat-card">
                    <h3>{{ $statistik_mitra['dokumen_selesai'] ?? 0 }}</h3>
                    <p>Dokumen Selesai</p>
                </div>
                <div class="stat-card">
                    <h3>{{ $mitra->address ?? 'N/A' }}</h3>
                    <p>Alamat</p>
                </div>
            </div>
        </div>
        @endif

        @if(isset($dokumen) && $dokumen->count() > 0)
        <div class="info-section">
            <h2>Daftar Dokumen</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nama Dokumen</th>
                        <th>Jenis Proyek</th>
                        <th>Witel</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dokumen as $doc)
                    <tr>
                        <td>{{ $doc->nama_dokumen ?? 'N/A' }}</td>
                        <td>{{ $doc->jenis_proyek ?? 'N/A' }}</td>
                        <td>{{ $doc->witel ?? 'N/A' }}</td>
                        <td>{{ ucfirst($doc->status_implementasi ?? 'N/A') }}</td>
                        <td>{{ $doc->tanggal_dokumen ? $doc->tanggal_dokumen->format('d/m/Y') : 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        @endif
    @endif

    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh sistem Indismart</p>
        <p>© {{ date('Y') }} Indismart - All rights reserved</p>
    </div>
</body>
</html> 