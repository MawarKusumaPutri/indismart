<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class ManajemenMitraController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:staff');
    }

    /**
     * Display a listing of mitra dengan laporan dokumen
     */
    public function index(Request $request)
    {
        try {
            // Filter berdasarkan pencarian nama mitra
            $query = User::where('role', 'mitra')
                ->withCount(['dokumen as total_dokumen'])
                ->withCount(['dokumen as dokumen_aktif' => function($query) {
                    $query->whereIn('status_implementasi', ['inisiasi', 'planning', 'executing', 'controlling']);
                }])
                ->withCount(['dokumen as dokumen_selesai' => function($query) {
                    $query->where('status_implementasi', 'closing');
                }]);

            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('email', 'like', '%' . $request->search . '%');
            }

            $mitra = $query->orderBy('name')->paginate(15);

            // Statistik keseluruhan
            $statistik = [
                'total_mitra' => User::where('role', 'mitra')->count(),
                'total_dokumen' => Dokumen::count(),
                'dokumen_aktif' => Dokumen::whereIn('status_implementasi', ['inisiasi', 'planning', 'executing', 'controlling'])->count(),
                'dokumen_selesai' => Dokumen::where('status_implementasi', 'closing')->count(),
            ];

            // Laporan berdasarkan jenis proyek
            $laporan_jenis_proyek = Dokumen::selectRaw('jenis_proyek, COUNT(*) as total')
                ->groupBy('jenis_proyek')
                ->orderBy('total', 'desc')
                ->get();

            // Laporan berdasarkan status implementasi
            $laporan_status = Dokumen::selectRaw('status_implementasi, COUNT(*) as total')
                ->groupBy('status_implementasi')
                ->orderBy('total', 'desc')
                ->get();

            // Laporan berdasarkan witel
            $laporan_witel = Dokumen::selectRaw('witel, COUNT(*) as total')
                ->groupBy('witel')
                ->orderBy('total', 'desc')
                ->get();

            // Laporan berdasarkan bulan
            $laporan_bulan = Dokumen::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
                ->whereYear('created_at', date('Y'))
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get()
                ->map(function($item) {
                    $bulan = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ];
                    return [
                        'bulan' => $bulan[$item->bulan] ?? 'Unknown',
                        'total' => $item->total
                    ];
                });

            return view('manajemen-mitra.index', compact(
                'mitra',
                'statistik',
                'laporan_jenis_proyek',
                'laporan_status',
                'laporan_witel',
                'laporan_bulan'
            ));
        } catch (\Exception $e) {
            Log::error('Error in ManajemenMitraController@index: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show detail laporan untuk mitra tertentu
     */
    public function show($id)
    {
        try {
            $mitra = User::where('role', 'mitra')->findOrFail($id);
            
            // Dokumen mitra dengan detail
            $dokumen = Dokumen::where('user_id', $id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            // Statistik mitra
            $statistik_mitra = [
                'total_dokumen' => Dokumen::where('user_id', $id)->count(),
                'dokumen_aktif' => Dokumen::where('user_id', $id)
                    ->whereIn('status_implementasi', ['inisiasi', 'planning', 'executing', 'controlling'])
                    ->count(),
                'dokumen_selesai' => Dokumen::where('user_id', $id)
                    ->where('status_implementasi', 'closing')
                    ->count(),
            ];

            // Laporan berdasarkan jenis proyek untuk mitra ini
            $laporan_jenis_proyek = Dokumen::where('user_id', $id)
                ->selectRaw('jenis_proyek, COUNT(*) as total')
                ->groupBy('jenis_proyek')
                ->orderBy('total', 'desc')
                ->get();

            // Laporan berdasarkan status untuk mitra ini
            $laporan_status = Dokumen::where('user_id', $id)
                ->selectRaw('status_implementasi, COUNT(*) as total')
                ->groupBy('status_implementasi')
                ->orderBy('total', 'desc')
                ->get();

            return view('manajemen-mitra.show', compact(
                'mitra',
                'dokumen',
                'statistik_mitra',
                'laporan_jenis_proyek',
                'laporan_status'
            ));
        } catch (\Exception $e) {
            Log::error('Error in ManajemenMitraController@show: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Export laporan dalam format PDF atau Excel
     */
    public function export(Request $request)
    {
        try {
            $format = $request->get('format', 'pdf');
            $jenis_laporan = $request->get('jenis', 'semua');
            $mitra_id = $request->get('mitra_id');

            // Debug logging
            Log::info('Export request:', [
                'format' => $format,
                'jenis_laporan' => $jenis_laporan,
                'mitra_id' => $mitra_id
            ]);

            // Ambil data berdasarkan jenis laporan
            if ($jenis_laporan === 'mitra' && $mitra_id) {
                return $this->exportMitraDetail($mitra_id, $format);
            } else {
                return $this->exportLaporanUmum($format);
            }
        } catch (\Exception $e) {
            Log::error('Error in ManajemenMitraController@export: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Terjadi kesalahan saat export: ' . $e->getMessage());
        }
    }

    /**
     * Export laporan umum
     */
    private function exportLaporanUmum($format)
    {
        try {
            Log::info('Starting exportLaporanUmum with format: ' . $format);
            
            // Ambil data untuk laporan umum
            $statistik = [
                'total_mitra' => User::where('role', 'mitra')->count(),
                'total_dokumen' => Dokumen::count(),
                'dokumen_aktif' => Dokumen::whereIn('status_implementasi', ['inisiasi', 'planning', 'executing', 'controlling'])->count(),
                'dokumen_selesai' => Dokumen::where('status_implementasi', 'closing')->count(),
            ];

            Log::info('Statistik calculated:', $statistik);

            // Ambil data mitra dengan error handling
            $mitra = User::where('role', 'mitra')->get();
            Log::info('Found ' . $mitra->count() . ' mitra users');

            // Hitung statistik dokumen untuk setiap mitra
            $mitraWithStats = $mitra->map(function($user) {
                $total_dokumen = Dokumen::where('user_id', $user->id)->count();
                $dokumen_aktif = Dokumen::where('user_id', $user->id)
                    ->whereIn('status_implementasi', ['inisiasi', 'planning', 'executing', 'controlling'])
                    ->count();
                $dokumen_selesai = Dokumen::where('user_id', $user->id)
                    ->where('status_implementasi', 'closing')
                    ->count();
                
                $user->total_dokumen = $total_dokumen;
                $user->dokumen_aktif = $dokumen_aktif;
                $user->dokumen_selesai = $dokumen_selesai;
                
                return $user;
            });

            Log::info('Mitra stats calculated');

            $laporan_jenis_proyek = Dokumen::selectRaw('jenis_proyek, COUNT(*) as total')
                ->groupBy('jenis_proyek')
                ->orderBy('total', 'desc')
                ->get();

            $laporan_status = Dokumen::selectRaw('status_implementasi, COUNT(*) as total')
                ->groupBy('status_implementasi')
                ->orderBy('total', 'desc')
                ->get();

            $laporan_witel = Dokumen::selectRaw('witel, COUNT(*) as total')
                ->groupBy('witel')
                ->orderBy('total', 'desc')
                ->get();

            // Laporan bulanan
            $laporan_bulan = Dokumen::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
                ->whereYear('created_at', date('Y'))
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get()
                ->map(function($item) {
                    $bulan = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ];
                    return [
                        'bulan' => $bulan[$item->bulan] ?? 'Unknown',
                        'total' => $item->total
                    ];
                });

            $data = [
                'statistik' => $statistik,
                'mitra' => $mitraWithStats,
                'laporan_jenis_proyek' => $laporan_jenis_proyek,
                'laporan_status' => $laporan_status,
                'laporan_witel' => $laporan_witel,
                'laporan_bulan' => $laporan_bulan,
                'tanggal' => now()->format('d F Y H:i:s'),
                'judul' => 'Laporan Manajemen Mitra'
            ];

            Log::info('Data prepared successfully');

            if ($format === 'pdf') {
                return $this->generatePDF($data, 'laporan-manajemen-mitra.pdf');
            } else {
                return $this->generateExcel($data, 'laporan-manajemen-mitra.xlsx');
            }
        } catch (\Exception $e) {
            Log::error('Error in exportLaporanUmum: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Terjadi kesalahan saat export: ' . $e->getMessage());
        }
    }

    /**
     * Export detail mitra
     */
    private function exportMitraDetail($mitra_id, $format)
    {
        try {
            $mitra = User::where('role', 'mitra')->find($mitra_id);
            
            if (!$mitra) {
                return back()->with('error', 'Mitra tidak ditemukan');
            }
            
            $dokumen = Dokumen::where('user_id', $mitra_id)
                ->orderBy('created_at', 'desc')
                ->get();

            $statistik_mitra = [
                'total_dokumen' => Dokumen::where('user_id', $mitra_id)->count(),
                'dokumen_aktif' => Dokumen::where('user_id', $mitra_id)
                    ->whereIn('status_implementasi', ['inisiasi', 'planning', 'executing', 'controlling'])
                    ->count(),
                'dokumen_selesai' => Dokumen::where('user_id', $mitra_id)
                    ->where('status_implementasi', 'closing')
                    ->count(),
            ];

            $laporan_jenis_proyek = Dokumen::where('user_id', $mitra_id)
                ->selectRaw('jenis_proyek, COUNT(*) as total')
                ->groupBy('jenis_proyek')
                ->orderBy('total', 'desc')
                ->get();

            $laporan_status = Dokumen::where('user_id', $mitra_id)
                ->selectRaw('status_implementasi, COUNT(*) as total')
                ->groupBy('status_implementasi')
                ->orderBy('total', 'desc')
                ->get();

            $data = [
                'mitra' => $mitra,
                'dokumen' => $dokumen,
                'statistik_mitra' => $statistik_mitra,
                'laporan_jenis_proyek' => $laporan_jenis_proyek,
                'laporan_status' => $laporan_status,
                'tanggal' => now()->format('d F Y H:i:s'),
                'judul' => 'Laporan Detail Mitra - ' . $mitra->name
            ];

            if ($format === 'pdf') {
                return $this->generatePDF($data, 'laporan-detail-mitra-' . $mitra->id . '.pdf');
            } else {
                return $this->generateExcel($data, 'laporan-detail-mitra-' . $mitra->id . '.xlsx');
            }
        } catch (\Exception $e) {
            Log::error('Error in exportMitraDetail: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat export: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF
     */
    private function generatePDF($data, $filename)
    {
        try {
            Log::info('Generating PDF for filename: ' . $filename);
            
            // Untuk sementara, kita akan menggunakan HTML yang bisa di-print
            // karena DomPDF belum terinstall
            $html = view('manajemen-mitra.export-pdf', $data)->render();
            
            Log::info('PDF HTML generated successfully');
            
            return response($html, 200, [
                'Content-Type' => 'text/html',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]);
        } catch (\Exception $e) {
            Log::error('Error in generatePDF: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    /**
     * Generate Excel/CSV
     */
    private function generateExcel($data, $filename)
    {
        $csv = $this->generateCSV($data);
        
        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Generate CSV content
     */
    private function generateCSV($data)
    {
        $output = fopen('php://temp', 'r+');
        
        // Header
        fputcsv($output, [$data['judul']]);
        fputcsv($output, ['Tanggal: ' . $data['tanggal']]);
        fputcsv($output, []);
        
        if (isset($data['statistik'])) {
            // Statistik
            fputcsv($output, ['STATISTIK UMUM']);
            fputcsv($output, ['Total Mitra', $data['statistik']['total_mitra']]);
            fputcsv($output, ['Total Dokumen', $data['statistik']['total_dokumen']]);
            fputcsv($output, ['Dokumen Aktif', $data['statistik']['dokumen_aktif']]);
            fputcsv($output, ['Dokumen Selesai', $data['statistik']['dokumen_selesai']]);
            fputcsv($output, []);
            
            // Daftar Mitra
            fputcsv($output, ['DAFTAR MITRA']);
            fputcsv($output, ['Nama', 'Email', 'Total Dokumen', 'Dokumen Aktif', 'Dokumen Selesai']);
            foreach ($data['mitra'] as $m) {
                fputcsv($output, [$m->name, $m->email, $m->total_dokumen, $m->dokumen_aktif, $m->dokumen_selesai]);
            }
            fputcsv($output, []);
            
            // Laporan Jenis Proyek
            fputcsv($output, ['LAPORAN JENIS PROYEK']);
            fputcsv($output, ['Jenis Proyek', 'Total']);
            foreach ($data['laporan_jenis_proyek'] as $jp) {
                fputcsv($output, [$jp->jenis_proyek, $jp->total]);
            }
            fputcsv($output, []);
            
            // Laporan Status
            fputcsv($output, ['LAPORAN STATUS IMPLEMENTASI']);
            fputcsv($output, ['Status', 'Total']);
            foreach ($data['laporan_status'] as $s) {
                fputcsv($output, [$s->status_implementasi, $s->total]);
            }
            fputcsv($output, []);
            
            // Laporan Witel
            fputcsv($output, ['LAPORAN BERDASARKAN WITEL']);
            fputcsv($output, ['Witel', 'Total']);
            foreach ($data['laporan_witel'] as $w) {
                fputcsv($output, [$w->witel, $w->total]);
            }
            fputcsv($output, []);
            
            // Laporan Bulanan
            fputcsv($output, ['LAPORAN BULANAN ' . date('Y')]);
            fputcsv($output, ['Bulan', 'Total']);
            foreach ($data['laporan_bulan'] as $bulan) {
                fputcsv($output, [$bulan['bulan'], $bulan['total']]);
            }
        } else {
            // Detail Mitra
            fputcsv($output, ['INFORMASI MITRA']);
            fputcsv($output, ['Nama', $data['mitra']->name]);
            fputcsv($output, ['Email', $data['mitra']->email]);
            fputcsv($output, ['Tanggal Bergabung', $data['mitra']->created_at->format('d F Y')]);
            fputcsv($output, []);
            
            fputcsv($output, ['STATISTIK MITRA']);
            fputcsv($output, ['Total Dokumen', $data['statistik_mitra']['total_dokumen']]);
            fputcsv($output, ['Dokumen Aktif', $data['statistik_mitra']['dokumen_aktif']]);
            fputcsv($output, ['Dokumen Selesai', $data['statistik_mitra']['dokumen_selesai']]);
            fputcsv($output, []);
            
            fputcsv($output, ['DAFTAR DOKUMEN']);
            fputcsv($output, ['Nama Dokumen', 'Jenis Proyek', 'Witel', 'Site Name', 'Status', 'Tanggal']);
            foreach ($data['dokumen'] as $doc) {
                fputcsv($output, [
                    $doc->nama_dokumen,
                    $doc->jenis_proyek,
                    $doc->witel,
                    $doc->site_name,
                    $doc->status_implementasi,
                    $doc->tanggal_dokumen->format('d/m/Y')
                ]);
            }
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }
}
