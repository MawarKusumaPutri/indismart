<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use App\Models\User;
use App\Models\Foto;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LookerStudioApiController extends Controller
{
    /**
     * Get analytics data untuk Looker Studio
     */
    public function getAnalyticsData(Request $request)
    {
        try {
            Log::info('LookerStudio API: Analytics data requested', [
                'type' => $request->get('type', 'summary'),
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
                'method' => $request->method()
            ]);
            
            $dataType = $request->get('type', 'summary');
            
            switch ($dataType) {
                case 'summary':
                    return $this->getSummaryData();
                case 'dokumen':
                    return $this->getDokumenData($request);
                case 'mitra':
                    return $this->getMitraData($request);
                case 'foto':
                    return $this->getFotoData($request);
                case 'review':
                    return $this->getReviewData($request);
                case 'trends':
                    return $this->getTrendsData($request);
                case 'charts':
                    return $this->getChartsData($request);
                default:
                    Log::warning('LookerStudio API: Invalid data type requested', ['type' => $dataType]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid data type: ' . $dataType
                    ], 400);
            }
            
        } catch (\Exception $e) {
            Log::error('LookerStudio API: Error in getAnalyticsData - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_type' => $request->get('type'),
                'stack_trace' => $e->getTraceAsString(),
                'user_agent' => $request->userAgent()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get summary data
     */
    private function getSummaryData()
    {
        try {
            Log::info('LookerStudio API: Summary data requested');
            
            $now = Carbon::now();
            $lastMonth = $now->copy()->subMonth();
            $lastWeek = $now->copy()->subWeek();

            $summaryData = [
                'summary' => [
                    'total_mitra' => User::where('role', 'mitra')->count(),
                    'total_dokumen' => Dokumen::count(),
                    'total_foto' => Foto::count(),
                    'total_review' => Review::count(),
                    'proyek_aktif' => Dokumen::whereIn('status_implementasi', ['inisiasi', 'planning', 'executing', 'controlling'])->count(),
                    'proyek_selesai' => Dokumen::where('status_implementasi', 'closing')->count(),
                    'dokumen_pending' => Dokumen::whereDoesntHave('reviews')->count(),
                ],
                'trends' => [
                    'mitra_baru_bulan_ini' => User::where('role', 'mitra')
                        ->whereBetween('created_at', [$lastMonth, $now])
                        ->count(),
                    'dokumen_baru_minggu_ini' => Dokumen::whereBetween('created_at', [$lastWeek, $now])->count(),
                    'foto_upload_minggu_ini' => Foto::whereBetween('created_at', [$lastWeek, $now])->count(),
                ]
            ];

            Log::info('LookerStudio API: Summary data retrieved successfully');
            
            return response()->json([
                'success' => true,
                'data' => $summaryData
            ]);
            
        } catch (\Exception $e) {
            Log::error('LookerStudio API: Error in getSummaryData - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get dokumen data
     */
    private function getDokumenData(Request $request)
    {
        try {
            Log::info('LookerStudio API: Dokumen data requested', [
                'status' => $request->get('status'),
                'witel' => $request->get('witel'),
                'jenis_proyek' => $request->get('jenis_proyek'),
                'date_from' => $request->get('date_from'),
                'date_to' => $request->get('date_to')
            ]);
            
            $query = Dokumen::with(['user', 'fotos', 'reviews']);
            
            // Apply filters
            if ($request->has('status')) {
                $query->where('status_implementasi', $request->status);
            }
            
            if ($request->has('witel')) {
                $query->where('witel', $request->witel);
            }
            
            if ($request->has('jenis_proyek')) {
                $query->where('jenis_proyek', $request->jenis_proyek);
            }
            
            if ($request->has('date_from')) {
                $query->where('created_at', '>=', $request->date_from);
            }
            
            if ($request->has('date_to')) {
                $query->where('created_at', '<=', $request->date_to);
            }
            
            $dokumen = $query->get()->map(function ($dokumen) {
                return [
                    'id' => $dokumen->id,
                    'nama_dokumen' => $dokumen->nama_dokumen ?? 'Unknown',
                    'jenis_proyek' => $dokumen->jenis_proyek ?? 'Unknown',
                    'status_implementasi' => $dokumen->status_implementasi ?? 'Unknown',
                    'witel' => $dokumen->witel ?? 'Unknown',
                    'sto' => $dokumen->sto ?? 'Unknown',
                    'site_name' => $dokumen->site_name ?? 'Unknown',
                    'tanggal_dokumen' => $dokumen->tanggal_dokumen,
                    'mitra' => $dokumen->user->name ?? 'Unknown',
                    'mitra_email' => $dokumen->user->email ?? '',
                    'jumlah_foto' => $dokumen->fotos->count(),
                    'jumlah_review' => $dokumen->reviews->count(),
                    'created_at' => $dokumen->created_at,
                    'updated_at' => $dokumen->updated_at,
                ];
            });
            
            Log::info('LookerStudio API: Dokumen data retrieved successfully', ['count' => $dokumen->count()]);
            
            return response()->json([
                'success' => true,
                'data' => $dokumen,
                'total' => $dokumen->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('LookerStudio API: Error in getDokumenData - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get mitra data
     */
    private function getMitraData(Request $request)
    {
        try {
            Log::info('LookerStudio API: Mitra data requested', [
                'active_only' => $request->get('active_only')
            ]);
            
            $query = User::where('role', 'mitra')->withCount(['dokumen', 'fotos']);
            
            // Apply filters
            if ($request->has('active_only')) {
                $query->whereHas('dokumen', function ($q) {
                    $q->where('created_at', '>=', Carbon::now()->subMonth());
                });
            }
            
            $mitra = $query->get()->map(function ($mitra) {
                return [
                    'id' => $mitra->id,
                    'name' => $mitra->name ?? 'Unknown',
                    'email' => $mitra->email ?? '',
                    'nomor_kontrak' => $mitra->nomor_kontrak ?? '',
                    'jumlah_dokumen' => $mitra->dokumen_count ?? 0,
                    'jumlah_foto' => $mitra->fotos_count ?? 0,
                    'registered_at' => $mitra->created_at,
                    'last_activity' => $mitra->dokumen()->latest()->first()?->created_at,
                    'status' => ($mitra->dokumen_count ?? 0) > 0 ? 'Aktif' : 'Tidak Aktif',
                ];
            });
            
            Log::info('LookerStudio API: Mitra data retrieved successfully', ['count' => $mitra->count()]);
            
            return response()->json([
                'success' => true,
                'data' => $mitra,
                'total' => $mitra->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('LookerStudio API: Error in getMitraData - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get foto data
     */
    private function getFotoData(Request $request)
    {
        try {
            Log::info('LookerStudio API: Foto data requested', [
                'dokumen_id' => $request->get('dokumen_id'),
                'date_from' => $request->get('date_from'),
                'date_to' => $request->get('date_to')
            ]);
            
            $query = Foto::with(['dokumen.user']);
            
            // Apply filters
            if ($request->has('dokumen_id')) {
                $query->where('dokumen_id', $request->dokumen_id);
            }
            
            if ($request->has('date_from')) {
                $query->where('created_at', '>=', $request->date_from);
            }
            
            if ($request->has('date_to')) {
                $query->where('created_at', '<=', $request->date_to);
            }
            
            $fotos = $query->get()->map(function ($foto) {
                return [
                    'id' => $foto->id,
                    'dokumen_id' => $foto->dokumen_id,
                    'nama_dokumen' => $foto->dokumen->nama_dokumen ?? 'Unknown',
                    'mitra' => $foto->dokumen->user->name ?? 'Unknown',
                    'caption' => $foto->caption ?? '',
                    'file_size' => $foto->foto_size ?? 0,
                    'uploaded_at' => $foto->created_at,
                    'order' => $foto->order ?? 0,
                ];
            });
            
            Log::info('LookerStudio API: Foto data retrieved successfully', ['count' => $fotos->count()]);
            
            return response()->json([
                'success' => true,
                'data' => $fotos,
                'total' => $fotos->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('LookerStudio API: Error in getFotoData - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get review data
     */
    private function getReviewData(Request $request)
    {
        try {
            Log::info('LookerStudio API: Review data requested', [
                'dokumen_id' => $request->get('dokumen_id'),
                'rating' => $request->get('rating'),
                'status' => $request->get('status')
            ]);
            
            $query = Review::with(['dokumen.user']);
            
            // Apply filters
            if ($request->has('dokumen_id')) {
                $query->where('dokumen_id', $request->dokumen_id);
            }
            
            if ($request->has('rating')) {
                $query->where('rating', $request->rating);
            }
            
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
            
            $reviews = $query->get()->map(function ($review) {
                return [
                    'id' => $review->id,
                    'dokumen_id' => $review->dokumen_id,
                    'nama_dokumen' => $review->dokumen->nama_dokumen ?? 'Unknown',
                    'mitra' => $review->dokumen->user->name ?? 'Unknown',
                    'rating' => $review->rating ?? 0,
                    'komentar' => $review->komentar ?? '',
                    'status' => $review->status ?? 'pending',
                    'reviewed_at' => $review->created_at,
                ];
            });
            
            Log::info('LookerStudio API: Review data retrieved successfully', ['count' => $reviews->count()]);
            
            return response()->json([
                'success' => true,
                'data' => $reviews,
                'total' => $reviews->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('LookerStudio API: Error in getReviewData - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }





    /**
     * Get charts data
     */
    public function getChartsData(Request $request)
    {
        try {
            $chartType = $request->get('chart_type', 'all');
            
            Log::info('LookerStudio API: Charts data requested', ['chartType' => $chartType]);
            
            $data = [];
            
            if ($chartType === 'all' || $chartType === 'status_distribution') {
                $data['status_distribution'] = Dokumen::select('status_implementasi', DB::raw('count(*) as total'))
                    ->groupBy('status_implementasi')
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->status_implementasi ?? 'Unknown' => $item->total];
                    });
            }
            
            if ($chartType === 'all' || $chartType === 'project_type_distribution') {
                $data['project_type_distribution'] = Dokumen::select('jenis_proyek', DB::raw('count(*) as total'))
                    ->groupBy('jenis_proyek')
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->jenis_proyek ?? 'Unknown' => $item->total];
                    });
            }
            
            if ($chartType === 'all' || $chartType === 'witel_distribution') {
                $data['witel_distribution'] = Dokumen::select('witel', DB::raw('count(*) as total'))
                    ->groupBy('witel')
                    ->orderBy('total', 'desc')
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->witel ?? 'Unknown' => $item->total];
                    });
            }
            
            if ($chartType === 'all' || $chartType === 'mitra_activity') {
                $data['mitra_activity'] = User::where('role', 'mitra')
                    ->withCount(['dokumen', 'fotos'])
                    ->orderBy('dokumen_count', 'desc')
                    ->limit(10)
                    ->get()
                    ->map(function ($mitra) {
                        return [
                            'name' => $mitra->name ?? 'Unknown',
                            'dokumen_count' => $mitra->dokumen_count ?? 0,
                            'foto_count' => $mitra->fotos_count ?? 0,
                        ];
                    });
            }
            
            Log::info('LookerStudio API: Charts data retrieved successfully', ['chartCount' => count($data)]);
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            Log::error('LookerStudio API: Error in getChartsData - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'chartType' => $request->get('chart_type'),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get trends data
     */
    public function getTrendsData(Request $request)
    {
        try {
            $period = $request->get('period', 'month');
            $now = Carbon::now();
            
            Log::info('LookerStudio API: Trends data requested', ['period' => $period]);
            
            switch ($period) {
                case 'week':
                    $startDate = $now->copy()->subWeek();
                    $groupBy = 'DATE(created_at)';
                    break;
                case 'month':
                    $startDate = $now->copy()->subMonth();
                    $groupBy = 'DATE(created_at)';
                    break;
                case 'quarter':
                    $startDate = $now->copy()->subQuarter();
                    $groupBy = 'WEEK(created_at)';
                    break;
                case 'year':
                    $startDate = $now->copy()->subYear();
                    $groupBy = 'MONTH(created_at)';
                    break;
                default:
                    $startDate = $now->copy()->subMonth();
                    $groupBy = 'DATE(created_at)';
            }
            
            // Dokumen trends
            $dokumenTrends = Dokumen::select(DB::raw("$groupBy as date"), DB::raw('count(*) as total'))
                ->where('created_at', '>=', $startDate)
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            
            // Foto trends
            $fotoTrends = Foto::select(DB::raw("$groupBy as date"), DB::raw('count(*) as total'))
                ->where('created_at', '>=', $startDate)
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            
            // Mitra trends
            $mitraTrends = User::where('role', 'mitra')
                ->select(DB::raw("$groupBy as date"), DB::raw('count(*) as total'))
                ->where('created_at', '>=', $startDate)
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            
            $data = [
                'period' => $period,
                'start_date' => $startDate->toDateString(),
                'end_date' => $now->toDateString(),
                'dokumen_trends' => $dokumenTrends,
                'foto_trends' => $fotoTrends,
                'mitra_trends' => $mitraTrends,
            ];
            
            Log::info('LookerStudio API: Trends data retrieved successfully', ['period' => $period]);
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            Log::error('LookerStudio API: Error in getTrendsData - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'period' => $request->get('period'),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get real-time data untuk Looker Studio
     */
    public function getRealTimeData()
    {
        try {
            Log::info('LookerStudio API: Real-time data requested');
            
            $now = Carbon::now();
            $lastHour = $now->copy()->subHour();
            $lastDay = $now->copy()->subDay();
            
            $data = [
                'timestamp' => $now->toISOString(),
                'last_hour' => [
                    'new_dokumen' => Dokumen::where('created_at', '>=', $lastHour)->count(),
                    'new_fotos' => Foto::where('created_at', '>=', $lastHour)->count(),
                    'new_reviews' => Review::where('created_at', '>=', $lastHour)->count(),
                    'new_mitra' => User::where('role', 'mitra')->where('created_at', '>=', $lastHour)->count(),
                ],
                'last_day' => [
                    'new_dokumen' => Dokumen::where('created_at', '>=', $lastDay)->count(),
                    'new_fotos' => Foto::where('created_at', '>=', $lastDay)->count(),
                    'new_reviews' => Review::where('created_at', '>=', $lastDay)->count(),
                    'new_mitra' => User::where('role', 'mitra')->where('created_at', '>=', $lastDay)->count(),
                ],
                'summary' => [
                    'total_mitra' => User::where('role', 'mitra')->count(),
                    'total_dokumen' => Dokumen::count(),
                    'total_foto' => Foto::count(),
                    'total_review' => Review::count(),
                ]
            ];
            
            Log::info('LookerStudio API: Real-time data retrieved successfully');
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            Log::error('LookerStudio API: Error in getRealTimeData - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export data untuk Looker Studio
     */
    public function exportData(Request $request)
    {
        try {
            Log::info('LookerStudio API: Export data requested', [
                'format' => $request->get('format', 'json'),
                'type' => $request->get('type', 'all'),
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip()
            ]);
            
            $format = $request->get('format', 'json');
            $dataType = $request->get('type', 'all');
            
            // Prepare comprehensive data for export
            $data = $this->prepareComprehensiveExportData($dataType);
            
            switch ($format) {
                case 'csv':
                    return $this->exportToCSV($data, $dataType);
                case 'json':
                default:
                    return response()->json([
                        'success' => true,
                        'data' => $data,
                        'exported_at' => Carbon::now()->toISOString(),
                        'format' => $format,
                        'type' => $dataType
                    ]);
            }
            
        } catch (\Exception $e) {
            Log::error('LookerStudio API: Error in exportData - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'format' => $request->get('format'),
                'type' => $request->get('type'),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Export failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Prepare comprehensive data untuk export
     */
    private function prepareComprehensiveExportData($dataType)
    {
        try {
            Log::info('LookerStudio API: Preparing comprehensive export data', ['dataType' => $dataType]);
            
            $now = Carbon::now();
            $lastMonth = $now->copy()->subMonth();
            $lastWeek = $now->copy()->subWeek();
            
            switch ($dataType) {
                case 'dokumen':
                    return [
                        'dokumen' => Dokumen::with(['user', 'reviews', 'fotos'])->get()->map(function($dokumen) {
                            return [
                                'id' => $dokumen->id,
                                'judul' => $dokumen->judul,
                                'deskripsi' => $dokumen->deskripsi,
                                'jenis_proyek' => $dokumen->jenis_proyek,
                                'status_implementasi' => $dokumen->status_implementasi,
                                'mitra' => $dokumen->user->name ?? 'Unknown',
                                'lokasi' => $dokumen->lokasi,
                                'witel' => $dokumen->witel,
                                'created_at' => $dokumen->created_at->format('Y-m-d H:i:s'),
                                'updated_at' => $dokumen->updated_at->format('Y-m-d H:i:s'),
                                'jumlah_foto' => $dokumen->fotos->count(),
                                'jumlah_review' => $dokumen->reviews->count()
                            ];
                        })->toArray()
                    ];
                    
                case 'mitra':
                    return [
                        'mitra' => User::where('role', 'mitra')->with(['dokumen', 'dokumen.fotos', 'dokumen.reviews'])->get()->map(function($user) {
                            return [
                                'id' => $user->id,
                                'name' => $user->name,
                                'email' => $user->email,
                                'phone' => $user->phone,
                                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                                'jumlah_dokumen' => $user->dokumen->count(),
                                'jumlah_foto' => $user->dokumen->sum(function($dokumen) {
                                    return $dokumen->fotos->count();
                                }),
                                'jumlah_review' => $user->dokumen->sum(function($dokumen) {
                                    return $dokumen->reviews->count();
                                })
                            ];
                        })->toArray()
                    ];
                    
                case 'foto':
                    return [
                        'foto' => Foto::with(['dokumen', 'dokumen.user'])->get()->map(function($foto) {
                            return [
                                'id' => $foto->id,
                                'filename' => $foto->filename,
                                'path' => $foto->path,
                                'dokumen_judul' => $foto->dokumen->judul ?? 'Unknown',
                                'mitra' => $foto->dokumen->user->name ?? 'Unknown',
                                'created_at' => $foto->created_at->format('Y-m-d H:i:s'),
                                'updated_at' => $foto->updated_at->format('Y-m-d H:i:s')
                            ];
                        })->toArray()
                    ];
                    
                case 'review':
                    return [
                        'review' => Review::with(['dokumen', 'dokumen.user'])->get()->map(function($review) {
                            return [
                                'id' => $review->id,
                                'rating' => $review->rating,
                                'komentar' => $review->komentar,
                                'dokumen_judul' => $review->dokumen->judul ?? 'Unknown',
                                'mitra' => $review->dokumen->user->name ?? 'Unknown',
                                'created_at' => $review->created_at->format('Y-m-d H:i:s'),
                                'updated_at' => $review->updated_at->format('Y-m-d H:i:s')
                            ];
                        })->toArray()
                    ];
                    
                case 'all':
                default:
                    return [
                        'summary' => [
                            'total_mitra' => User::where('role', 'mitra')->count(),
                            'total_dokumen' => Dokumen::count(),
                            'total_foto' => Foto::count(),
                            'total_review' => Review::count(),
                            'proyek_aktif' => Dokumen::whereIn('status_implementasi', ['inisiasi', 'planning', 'executing', 'controlling'])->count(),
                            'proyek_selesai' => Dokumen::where('status_implementasi', 'closing')->count(),
                            'exported_at' => $now->format('Y-m-d H:i:s')
                        ],
                        'trends' => [
                            'mitra_baru_bulan_ini' => User::where('role', 'mitra')->whereBetween('created_at', [$lastMonth, $now])->count(),
                            'dokumen_baru_minggu_ini' => Dokumen::whereBetween('created_at', [$lastWeek, $now])->count(),
                            'foto_upload_minggu_ini' => Foto::whereBetween('created_at', [$lastWeek, $now])->count()
                        ],
                        'dokumen' => Dokumen::with(['user', 'reviews', 'fotos'])->get()->map(function($dokumen) {
                            return [
                                'id' => $dokumen->id,
                                'judul' => $dokumen->judul,
                                'deskripsi' => $dokumen->deskripsi,
                                'jenis_proyek' => $dokumen->jenis_proyek,
                                'status_implementasi' => $dokumen->status_implementasi,
                                'mitra' => $dokumen->user->name ?? 'Unknown',
                                'lokasi' => $dokumen->lokasi,
                                'witel' => $dokumen->witel,
                                'created_at' => $dokumen->created_at->format('Y-m-d H:i:s'),
                                'jumlah_foto' => $dokumen->fotos->count(),
                                'jumlah_review' => $dokumen->reviews->count()
                            ];
                        })->toArray(),
                        'mitra' => User::where('role', 'mitra')->with(['dokumen'])->get()->map(function($user) {
                            return [
                                'id' => $user->id,
                                'name' => $user->name,
                                'email' => $user->email,
                                'phone' => $user->phone,
                                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                                'jumlah_dokumen' => $user->dokumen->count()
                            ];
                        })->toArray(),
                        'foto' => Foto::with(['dokumen', 'dokumen.user'])->get()->map(function($foto) {
                            return [
                                'id' => $foto->id,
                                'filename' => $foto->filename,
                                'dokumen_judul' => $foto->dokumen->judul ?? 'Unknown',
                                'mitra' => $foto->dokumen->user->name ?? 'Unknown',
                                'created_at' => $foto->created_at->format('Y-m-d H:i:s')
                            ];
                        })->toArray(),
                        'review' => Review::with(['dokumen', 'dokumen.user'])->get()->map(function($review) {
                            return [
                                'id' => $review->id,
                                'rating' => $review->rating,
                                'komentar' => $review->komentar,
                                'dokumen_judul' => $review->dokumen->judul ?? 'Unknown',
                                'mitra' => $review->dokumen->user->name ?? 'Unknown',
                                'created_at' => $review->created_at->format('Y-m-d H:i:s')
                            ];
                        })->toArray()
                    ];
            }
            
        } catch (\Exception $e) {
            Log::error('LookerStudio API: Error in prepareComprehensiveExportData - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'dataType' => $dataType,
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            throw new \Exception('Gagal menyiapkan data export komprehensif: ' . $e->getMessage());
        }
    }

    /**
     * Prepare data untuk export (legacy method)
     */
    private function prepareExportData($dataType)
    {
        try {
            Log::info('LookerStudio API: Preparing export data', ['dataType' => $dataType]);
            
            switch ($dataType) {
                case 'dokumen':
                    return $this->getDokumenData(new Request())->getData()->data;
                case 'mitra':
                    return $this->getMitraData(new Request())->getData()->data;
                case 'foto':
                    return $this->getFotoData(new Request())->getData()->data;
                case 'review':
                    return $this->getReviewData(new Request())->getData()->data;
                case 'all':
                default:
                    return [
                        'dokumen' => $this->getDokumenData(new Request())->getData()->data,
                        'mitra' => $this->getMitraData(new Request())->getData()->data,
                        'foto' => $this->getFotoData(new Request())->getData()->data,
                        'review' => $this->getReviewData(new Request())->getData()->data,
                    ];
            }
            
        } catch (\Exception $e) {
            Log::error('LookerStudio API: Error in prepareExportData - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'dataType' => $dataType,
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            throw new \Exception('Gagal menyiapkan data export: ' . $e->getMessage());
        }
    }

    /**
     * Export data ke CSV
     */
    private function exportToCSV($data, $dataType)
    {
        try {
            $filename = "indismart_{$dataType}_" . date('Y-m-d_H-i-s') . '.csv';
            
            // Generate CSV content as string first
            $csvContent = '';
            
            // Add BOM for UTF-8
            $csvContent .= chr(0xEF).chr(0xBB).chr(0xBF);
            
            // Debug: Log data structure
            Log::info('LookerStudio API: CSV export data structure', [
                'dataType' => $dataType,
                'dataKeys' => array_keys($data),
                'dataType' => gettype($data)
            ]);
            
            if ($dataType === 'all') {
                // Export all data types to separate sections
                foreach ($data as $datasetName => $dataset) {
                    if (is_array($dataset) && !empty($dataset)) {
                        // Write dataset header
                        $csvContent .= "\n";
                        $csvContent .= "=== " . strtoupper($datasetName) . " ===\n";
                        $csvContent .= "\n";
                        
                        // Check if dataset has records
                        if (is_array($dataset) && !empty($dataset) && isset($dataset[0]) && is_array($dataset[0])) {
                            // Write headers for this dataset
                            $headers = array_keys($dataset[0]);
                            $csvContent .= implode(',', $headers) . "\n";
                            
                            // Write data rows
                            foreach ($dataset as $record) {
                                if (is_array($record)) {
                                    // Convert all values to strings and handle nulls
                                    $csvRow = array_map(function($value) {
                                        if (is_null($value)) return '';
                                        if (is_array($value)) return json_encode($value);
                                        return '"' . str_replace('"', '""', (string) $value) . '"';
                                    }, $record);
                                    $csvContent .= implode(',', $csvRow) . "\n";
                                }
                            }
                        } else {
                            // Handle non-array datasets (like summary)
                            $csvContent .= "value\n";
                            if (is_array($dataset)) {
                                foreach ($dataset as $key => $value) {
                                    $csvContent .= '"' . str_replace('"', '""', (string) $key) . '","' . str_replace('"', '""', (string) $value) . '"' . "\n";
                                }
                            } else {
                                $csvContent .= '"' . str_replace('"', '""', (string) $dataset) . '"' . "\n";
                            }
                        }
                        $csvContent .= "\n"; // Empty line between datasets
                    }
                }
            } else {
                // Export specific data type
                if (is_array($data) && !empty($data)) {
                    // Find the actual data array
                    $actualData = null;
                    if (isset($data[$dataType]) && is_array($data[$dataType])) {
                        $actualData = $data[$dataType];
                    } elseif (is_array($data) && isset($data[0]) && is_array($data[0])) {
                        $actualData = $data;
                    }
                    
                    if ($actualData && !empty($actualData) && isset($actualData[0])) {
                        // Write headers
                        $headers = array_keys($actualData[0]);
                        $csvContent .= implode(',', $headers) . "\n";
                        
                        // Write data rows
                        foreach ($actualData as $record) {
                            if (is_array($record)) {
                                // Convert all values to strings and handle nulls
                                $csvRow = array_map(function($value) {
                                    if (is_null($value)) return '';
                                    if (is_array($value)) return json_encode($value);
                                    return '"' . str_replace('"', '""', (string) $value) . '"';
                                }, $record);
                                $csvContent .= implode(',', $csvRow) . "\n";
                            }
                        }
                    }
                }
            }
            
            Log::info('LookerStudio API: CSV export completed successfully', [
                'dataType' => $dataType,
                'contentLength' => strlen($csvContent)
            ]);
            
            return response($csvContent, 200, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'no-cache, must-revalidate',
                'Pragma' => 'no-cache'
            ]);
            
        } catch (\Exception $e) {
            Log::error('LookerStudio API: Error in exportToCSV - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'dataType' => $dataType,
                'stack_trace' => $e->getTraceAsString()
            ]);
            throw new \Exception('Gagal export ke CSV: ' . $e->getMessage());
        }
    }
}
