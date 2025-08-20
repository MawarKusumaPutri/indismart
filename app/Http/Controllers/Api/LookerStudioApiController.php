<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class LookerStudioApiController extends Controller
{
    /**
     * Get complete dashboard overview data
     */
    public function getDashboardOverview(): JsonResponse
    {
        try {
            $data = [
                'overview' => $this->getOverviewData(),
                'mitra_analytics' => $this->getMitraAnalytics(),
                'proyek_analytics' => $this->getProyekAnalytics(),
                'trends' => $this->getTrendsData(),
                'last_updated' => now()->format('Y-m-d H:i:s')
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Dashboard overview data retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving dashboard data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get mitra analytics data
     */
    public function getMitraAnalytics(): JsonResponse
    {
        try {
            $data = $this->getMitraAnalyticsData();
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Mitra analytics data retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving mitra analytics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get proyek analytics data
     */
    public function getProyekAnalytics(): JsonResponse
    {
        try {
            $data = $this->getProyekAnalyticsData();
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Proyek analytics data retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving proyek analytics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get performance metrics
     */
    public function getPerformanceMetrics(): JsonResponse
    {
        try {
            $data = $this->getPerformanceData();
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Performance metrics retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving performance metrics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get monthly trends
     */
    public function getMonthlyTrends(): JsonResponse
    {
        try {
            $monthlyTrends = [];
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $monthlyTrends[] = [
                    'month' => $date->format('Y-m'),
                    'month_name' => $date->format('M Y'),
                    'new_mitra' => User::where('role', 'mitra')
                        ->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count(),
                    'new_proyek' => Dokumen::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count(),
                    'completed_proyek' => Dokumen::where('status_implementasi', 'closing')
                        ->whereYear('updated_at', $date->year)
                        ->whereMonth('updated_at', $date->month)
                        ->count()
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $monthlyTrends,
                'message' => 'Monthly trends data retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving monthly trends: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export complete data
     */
    public function exportCompleteData(): JsonResponse
    {
        try {
            $data = [
                'mitra_data' => $this->getMitraExportData(),
                'proyek_data' => $this->getProyekExportData(),
                'export_date' => now()->format('Y-m-d H:i:s')
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Complete export data retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error exporting complete data: ' . $e->getMessage()
            ], 500);
        }
    }

    // Private helper methods
    private function getOverviewData()
    {
        $totalMitra = User::where('role', 'mitra')->count();
        $totalDokumen = Dokumen::count();
        $proyekAktif = Dokumen::whereIn('status_implementasi', ['inisiasi', 'planning', 'executing', 'controlling'])->count();
        $proyekSelesai = Dokumen::where('status_implementasi', 'closing')->count();
        $dokumenPendingReview = Dokumen::where('status_implementasi', 'inisiasi')->count();
        
        $lastMonthMitra = User::where('role', 'mitra')
            ->where('created_at', '>=', Carbon::now()->subMonth())
            ->count();
        
        $lastMonthProyek = Dokumen::where('created_at', '>=', Carbon::now()->subMonth())->count();

        return [
            'total_mitra' => $totalMitra,
            'total_dokumen' => $totalDokumen,
            'proyek_aktif' => $proyekAktif,
            'proyek_selesai' => $proyekSelesai,
            'dokumen_pending_review' => $dokumenPendingReview,
            'mitra_growth_monthly' => $lastMonthMitra,
            'proyek_growth_monthly' => $lastMonthProyek,
            'completion_rate' => $totalDokumen > 0 ? round(($proyekSelesai / $totalDokumen) * 100, 2) : 0
        ];
    }

    private function getMitraAnalyticsData()
    {
        $topMitra = User::where('role', 'mitra')
            ->withCount(['dokumen as total_proyek'])
            ->withCount(['dokumen as proyek_selesai' => function($query) {
                $query->where('status_implementasi', 'closing');
            }])
            ->orderBy('total_proyek', 'desc')
            ->limit(10)
            ->get()
            ->map(function($mitra) {
                return [
                    'id' => $mitra->id,
                    'name' => $mitra->name,
                    'email' => $mitra->email,
                    'total_proyek' => $mitra->total_proyek,
                    'proyek_selesai' => $mitra->proyek_selesai,
                    'success_rate' => $mitra->total_proyek > 0 ? round(($mitra->proyek_selesai / $mitra->total_proyek) * 100, 2) : 0,
                    'joined_date' => $mitra->created_at->format('Y-m-d')
                ];
            });

        return [
            'top_performing_mitra' => $topMitra,
            'total_mitra' => User::where('role', 'mitra')->count(),
            'active_mitra' => User::where('role', 'mitra')->whereNotNull('nomor_kontrak')->count()
        ];
    }

    private function getProyekAnalyticsData()
    {
        $statusDistribution = Dokumen::selectRaw('status_implementasi, COUNT(*) as count')
            ->groupBy('status_implementasi')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->status_implementasi => $item->count];
            });

        $jenisProyekDistribution = Dokumen::selectRaw('jenis_proyek, COUNT(*) as count')
            ->whereNotNull('jenis_proyek')
            ->groupBy('jenis_proyek')
            ->get();

        return [
            'status_distribution' => $statusDistribution,
            'jenis_proyek_distribution' => $jenisProyekDistribution,
            'total_proyek' => Dokumen::count(),
            'completed_proyek' => Dokumen::where('status_implementasi', 'closing')->count()
        ];
    }

    private function getTrendsData()
    {
        $monthlyTrends = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyTrends[] = [
                'month' => $date->format('Y-m'),
                'month_name' => $date->format('M Y'),
                'new_mitra' => User::where('role', 'mitra')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'new_proyek' => Dokumen::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'completed_proyek' => Dokumen::where('status_implementasi', 'closing')
                    ->whereYear('updated_at', $date->year)
                    ->whereMonth('updated_at', $date->month)
                    ->count()
            ];
        }

        return [
            'monthly_trends' => $monthlyTrends
        ];
    }

    private function getPerformanceData()
    {
        $totalProyek = Dokumen::count();
        $completedProyek = Dokumen::where('status_implementasi', 'closing')->count();
        $activeProyek = Dokumen::whereIn('status_implementasi', ['inisiasi', 'planning', 'executing', 'controlling'])->count();

        return [
            'total_proyek' => $totalProyek,
            'completed_proyek' => $completedProyek,
            'active_proyek' => $activeProyek,
            'completion_rate' => $totalProyek > 0 ? round(($completedProyek / $totalProyek) * 100, 2) : 0
        ];
    }

    private function getMitraExportData()
    {
        return User::where('role', 'mitra')
            ->withCount(['dokumen as total_proyek'])
            ->withCount(['dokumen as proyek_selesai' => function($query) {
                $query->where('status_implementasi', 'closing');
            }])
            ->get()
            ->map(function($mitra) {
                return [
                    'mitra_id' => $mitra->id,
                    'nama_mitra' => $mitra->name,
                    'email_mitra' => $mitra->email,
                    'tanggal_bergabung' => $mitra->created_at->format('Y-m-d'),
                    'total_proyek' => $mitra->total_proyek,
                    'proyek_selesai' => $mitra->proyek_selesai,
                    'proyek_aktif' => $mitra->total_proyek - $mitra->proyek_selesai,
                    'success_rate' => $mitra->total_proyek > 0 ? round(($mitra->proyek_selesai / $mitra->total_proyek) * 100, 2) : 0,
                    'status_mitra' => $mitra->nomor_kontrak ? 'Aktif' : 'Belum Aktif'
                ];
            });
    }

    private function getProyekExportData()
    {
        return Dokumen::with('user')
            ->get()
            ->map(function($dokumen) {
                return [
                    'proyek_id' => $dokumen->id,
                    'nama_proyek' => $dokumen->nama_proyek,
                    'jenis_proyek' => $dokumen->jenis_proyek,
                    'status_implementasi' => $dokumen->status_implementasi,
                    'mitra_id' => $dokumen->user_id,
                    'nama_mitra' => $dokumen->user->name,
                    'tanggal_mulai' => $dokumen->created_at->format('Y-m-d'),
                    'tanggal_update' => $dokumen->updated_at->format('Y-m-d'),
                    'durasi_hari' => Carbon::parse($dokumen->created_at)->diffInDays($dokumen->updated_at),
                    'is_completed' => $dokumen->status_implementasi === 'closing' ? 1 : 0
                ];
            });
    }
}
