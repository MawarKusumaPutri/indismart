<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\User;
use App\Models\Review;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LookerStudioController extends Controller
{
    public function index()
    {
        return view('looker-studio.index');
    }

    /**
     * API endpoint untuk data dashboard Looker Studio
     */
    public function dashboardData()
    {
        $data = [
            'overview' => $this->getOverviewData(),
            'mitra_analytics' => $this->getMitraAnalytics(),
            'proyek_analytics' => $this->getProyekAnalytics(),
            'trends' => $this->getTrendsData(),
            'performance' => $this->getPerformanceData(),
            'geographic' => $this->getGeographicData(),
            'timeline' => $this->getTimelineData()
        ];

        return response()->json($data);
    }

    /**
     * Data overview untuk dashboard
     */
    private function getOverviewData()
    {
        $totalMitra = User::where('role', 'mitra')->count();
        $totalDokumen = Dokumen::count();
        $proyekAktif = Dokumen::whereIn('status_implementasi', ['inisiasi', 'planning', 'executing', 'controlling'])->count();
        $proyekSelesai = Dokumen::where('status_implementasi', 'closing')->count();
        $dokumenPendingReview = Dokumen::where('status_implementasi', 'inisiasi')->count();
        
        // Growth metrics
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

    /**
     * Analytics data untuk mitra
     */
    private function getMitraAnalytics()
    {
        // Top performing mitra
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

        // Mitra registration trend
        $mitraRegistrationTrend = User::where('role', 'mitra')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Mitra by status
        $mitraByStatus = User::where('role', 'mitra')
            ->selectRaw('
                CASE 
                    WHEN nomor_kontrak IS NOT NULL THEN "Aktif"
                    ELSE "Belum Aktif"
                END as status,
                COUNT(*) as count
            ')
            ->groupBy('status')
            ->get();

        return [
            'top_performing_mitra' => $topMitra,
            'registration_trend' => $mitraRegistrationTrend,
            'mitra_by_status' => $mitraByStatus
        ];
    }

    /**
     * Analytics data untuk proyek
     */
    private function getProyekAnalytics()
    {
        // Status distribution
        $statusDistribution = Dokumen::selectRaw('status_implementasi, COUNT(*) as count')
            ->groupBy('status_implementasi')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->status_implementasi => $item->count];
            });

        // Jenis proyek distribution
        $jenisProyekDistribution = Dokumen::selectRaw('jenis_proyek, COUNT(*) as count')
            ->whereNotNull('jenis_proyek')
            ->groupBy('jenis_proyek')
            ->get();

        // Proyek timeline
        $proyekTimeline = Dokumen::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Average completion time
        $avgCompletionTime = Dokumen::where('status_implementasi', 'closing')
            ->whereNotNull('updated_at')
            ->get()
            ->map(function($dokumen) {
                return Carbon::parse($dokumen->created_at)->diffInDays($dokumen->updated_at);
            })
            ->avg();

        return [
            'status_distribution' => $statusDistribution,
            'jenis_proyek_distribution' => $jenisProyekDistribution,
            'proyek_timeline' => $proyekTimeline,
            'avg_completion_time_days' => round($avgCompletionTime ?? 0, 1)
        ];
    }

    /**
     * Data trends untuk analisis
     */
    private function getTrendsData()
    {
        // Monthly trends
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

        // Weekly trends (last 12 weeks)
        $weeklyTrends = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subWeeks($i);
            $weeklyTrends[] = [
                'week' => $date->format('Y-W'),
                'week_name' => 'Week ' . $date->format('W, Y'),
                'new_mitra' => User::where('role', 'mitra')
                    ->whereBetween('created_at', [$date->startOfWeek(), $date->endOfWeek()])
                    ->count(),
                'new_proyek' => Dokumen::whereBetween('created_at', [$date->startOfWeek(), $date->endOfWeek()])
                    ->count(),
                'completed_proyek' => Dokumen::where('status_implementasi', 'closing')
                    ->whereBetween('updated_at', [$date->startOfWeek(), $date->endOfWeek()])
                    ->count()
            ];
        }

        return [
            'monthly_trends' => $monthlyTrends,
            'weekly_trends' => $weeklyTrends
        ];
    }

    /**
     * Performance metrics
     */
    private function getPerformanceData()
    {
        // KPI metrics
        $totalProyek = Dokumen::count();
        $completedProyek = Dokumen::where('status_implementasi', 'closing')->count();
        $activeProyek = Dokumen::whereIn('status_implementasi', ['inisiasi', 'planning', 'executing', 'controlling'])->count();

        // Performance by status
        $performanceByStatus = [
            'inisiasi' => [
                'count' => Dokumen::where('status_implementasi', 'inisiasi')->count(),
                'percentage' => $totalProyek > 0 ? round((Dokumen::where('status_implementasi', 'inisiasi')->count() / $totalProyek) * 100, 2) : 0
            ],
            'planning' => [
                'count' => Dokumen::where('status_implementasi', 'planning')->count(),
                'percentage' => $totalProyek > 0 ? round((Dokumen::where('status_implementasi', 'planning')->count() / $totalProyek) * 100, 2) : 0
            ],
            'executing' => [
                'count' => Dokumen::where('status_implementasi', 'executing')->count(),
                'percentage' => $totalProyek > 0 ? round((Dokumen::where('status_implementasi', 'executing')->count() / $totalProyek) * 100, 2) : 0
            ],
            'controlling' => [
                'count' => Dokumen::where('status_implementasi', 'controlling')->count(),
                'percentage' => $totalProyek > 0 ? round((Dokumen::where('status_implementasi', 'controlling')->count() / $totalProyek) * 100, 2) : 0
            ],
            'closing' => [
                'count' => $completedProyek,
                'percentage' => $totalProyek > 0 ? round(($completedProyek / $totalProyek) * 100, 2) : 0
            ]
        ];

        return [
            'total_proyek' => $totalProyek,
            'completed_proyek' => $completedProyek,
            'active_proyek' => $activeProyek,
            'completion_rate' => $totalProyek > 0 ? round(($completedProyek / $totalProyek) * 100, 2) : 0,
            'performance_by_status' => $performanceByStatus
        ];
    }

    /**
     * Geographic data (if available)
     */
    private function getGeographicData()
    {
        // This would be populated if you have location data
        // For now, returning empty structure
        return [
            'mitra_by_location' => [],
            'proyek_by_location' => []
        ];
    }

    /**
     * Timeline data for detailed analysis
     */
    private function getTimelineData()
    {
        // Recent activities
        $recentActivities = Dokumen::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function($dokumen) {
                return [
                    'id' => $dokumen->id,
                    'type' => 'dokumen',
                    'title' => $dokumen->nama_proyek ?? 'Dokumen Baru',
                    'description' => "Dokumen {$dokumen->jenis_proyek} oleh {$dokumen->user->name}",
                    'status' => $dokumen->status_implementasi,
                    'user' => $dokumen->user->name,
                    'timestamp' => $dokumen->created_at->format('Y-m-d H:i:s'),
                    'date' => $dokumen->created_at->format('Y-m-d')
                ];
            });

        // Daily activity summary
        $dailyActivity = Dokumen::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'recent_activities' => $recentActivities,
            'daily_activity' => $dailyActivity
        ];
    }

    /**
     * Export data for Looker Studio
     */
    public function exportData()
    {
        $data = [
            'mitra_data' => $this->getMitraExportData(),
            'proyek_data' => $this->getProyekExportData(),
            'activity_data' => $this->getActivityExportData()
        ];

        return response()->json($data);
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

    private function getActivityExportData()
    {
        $activities = [];
        
        // Dokumen activities
        $dokumenActivities = Dokumen::with('user')->get();
        foreach ($dokumenActivities as $dokumen) {
            $activities[] = [
                'activity_id' => 'dok_' . $dokumen->id,
                'activity_type' => 'dokumen',
                'activity_title' => $dokumen->nama_proyek ?? 'Dokumen Baru',
                'user_id' => $dokumen->user_id,
                'user_name' => $dokumen->user->name,
                'activity_date' => $dokumen->created_at->format('Y-m-d'),
                'activity_time' => $dokumen->created_at->format('H:i:s'),
                'status' => $dokumen->status_implementasi,
                'jenis_proyek' => $dokumen->jenis_proyek
            ];
        }

        // User registration activities
        $userActivities = User::where('role', 'mitra')->get();
        foreach ($userActivities as $user) {
            $activities[] = [
                'activity_id' => 'user_' . $user->id,
                'activity_type' => 'registration',
                'activity_title' => 'Mitra Baru Terdaftar',
                'user_id' => $user->id,
                'user_name' => $user->name,
                'activity_date' => $user->created_at->format('Y-m-d'),
                'activity_time' => $user->created_at->format('H:i:s'),
                'status' => 'registered',
                'jenis_proyek' => null
            ];
        }

        // Sort by date
        usort($activities, function($a, $b) {
            return strtotime($b['activity_date'] . ' ' . $b['activity_time']) - strtotime($a['activity_date'] . ' ' . $a['activity_time']);
        });

        return $activities;
    }

    /**
     * API endpoint untuk mitra analytics
     */
    public function getMitraAnalyticsApi()
    {
        return response()->json($this->getMitraAnalytics());
    }

    /**
     * API endpoint untuk proyek analytics
     */
    public function getProyekAnalyticsApi()
    {
        return response()->json($this->getProyekAnalytics());
    }
}
