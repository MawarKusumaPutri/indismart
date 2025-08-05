<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function mitraDashboard()
    {
        $user = Auth::user();
        
        // Hitung statistik untuk mitra
        $totalDokumen = Dokumen::where('user_id', $user->id)->count();
        $proyekAktif = Dokumen::where('user_id', $user->id)
            ->whereIn('status_implementasi', ['inisiasi', 'planning', 'executing', 'controlling'])
            ->count();
        $proyekSelesai = Dokumen::where('user_id', $user->id)
            ->where('status_implementasi', 'closing')
            ->count();

        // Ambil proyek terbaru untuk mitra
        $proyekTerbaru = Dokumen::where('user_id', $user->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mitra.dashboard', compact(
            'totalDokumen',
            'proyekAktif',
            'proyekSelesai',
            'proyekTerbaru'
        ));
    }

    public function staffDashboard()
    {
        // Hitung statistik untuk staff
        $totalMitra = User::where('role', 'mitra')->count();
        $totalDokumen = Dokumen::count();
        $proyekAktif = Dokumen::whereIn('status_implementasi', ['inisiasi', 'planning', 'executing', 'controlling'])->count();
        $proyekSelesai = Dokumen::where('status_implementasi', 'closing')->count();
        $dokumenPendingReview = Dokumen::where('status_implementasi', 'inisiasi')->count();

        // Ambil daftar mitra dengan jumlah proyek
        $daftarMitra = User::where('role', 'mitra')
            ->withCount(['dokumen as total_proyek'])
            ->withCount(['dokumen as proyek_aktif' => function($query) {
                $query->whereIn('status_implementasi', ['inisiasi', 'planning', 'executing', 'controlling']);
            }])
            ->get();

        // Ambil aktivitas terbaru
        $aktivitasTerbaru = Dokumen::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($dokumen) {
                return [
                    'icon' => $this->getActivityIcon($dokumen->status_implementasi),
                    'color' => $this->getActivityColor($dokumen->status_implementasi),
                    'message' => $this->getActivityMessage($dokumen),
                    'time' => $dokumen->created_at->diffForHumans()
                ];
            });

        return view('staff.dashboard', compact(
            'totalMitra',
            'totalDokumen',
            'proyekAktif',
            'proyekSelesai',
            'dokumenPendingReview',
            'daftarMitra',
            'aktivitasTerbaru'
        ));
    }

    private function getActivityIcon($status)
    {
        return match($status) {
            'inisiasi' => 'bi-file-earmark-plus',
            'planning' => 'bi-calendar-check',
            'executing' => 'bi-gear',
            'controlling' => 'bi-eye',
            'closing' => 'bi-check-circle',
            default => 'bi-file-earmark-text'
        };
    }

    private function getActivityColor($status)
    {
        return match($status) {
            'inisiasi' => 'primary',
            'planning' => 'info',
            'executing' => 'warning',
            'controlling' => 'secondary',
            'closing' => 'success',
            default => 'primary'
        };
    }

    private function getActivityMessage($dokumen)
    {
        return match($dokumen->status_implementasi) {
            'inisiasi' => "{$dokumen->user->name} menambahkan dokumen baru",
            'planning' => "Proyek {$dokumen->nama_proyek} dalam tahap perencanaan",
            'executing' => "Proyek {$dokumen->nama_proyek} dalam tahap pelaksanaan",
            'controlling' => "Proyek {$dokumen->nama_proyek} dalam tahap pengawasan",
            'closing' => "Proyek {$dokumen->nama_proyek} telah selesai",
            default => "{$dokumen->user->name} mengupdate dokumen"
        };
    }
}