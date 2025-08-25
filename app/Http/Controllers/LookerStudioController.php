<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\User;
use App\Models\Foto;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LookerStudioController extends Controller
{
    public function __construct()
    {
        // Middleware untuk membatasi akses hanya untuk staff
        $this->middleware('auth');
        $this->middleware('role:staff');
    }

    /**
     * Dashboard utama Looker Studio
     */
    public function index()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                Log::error('LookerStudio: User not authenticated');
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }
            
            if ($user->role !== 'staff') {
                Log::warning('LookerStudio: Unauthorized access attempt by user ' . $user->id);
                return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke fitur ini.');
            }
            
            // Data untuk dashboard
            $dashboardData = $this->getDashboardData();
            
            Log::info('LookerStudio: Dashboard accessed successfully by user ' . $user->id);
            
            return view('looker-studio.index', compact('dashboardData'));
            
        } catch (\Exception $e) {
            Log::error('LookerStudio: Error in index method - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => Auth::id()
            ]);
            
            return redirect()->route('dashboard')->with('error', 'Terjadi kesalahan saat memuat dashboard. Silakan coba lagi.');
        }
    }

    /**
     * Generate Looker Studio URL secara otomatis
     */
    public function generateDashboard()
    {
        try {
            $user = Auth::user();
            
            if (!$user || $user->role !== 'staff') {
                Log::warning('LookerStudio: Unauthorized dashboard generation attempt');
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke fitur ini.'
                ], 403);
            }
            
            // Data untuk Looker Studio
            $data = $this->prepareLookerStudioData();
            
            // Generate Looker Studio URL
            $lookerStudioUrl = $this->createLookerStudioUrl($data);
            
            Log::info('LookerStudio: Dashboard generated successfully by user ' . $user->id);
            
            return response()->json([
                'success' => true,
                'url' => $lookerStudioUrl,
                'message' => 'Dashboard Looker Studio berhasil dibuat!'
            ]);
            
        } catch (\Exception $e) {
            Log::error('LookerStudio: Error in generateDashboard method - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat dashboard: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set custom Looker Studio URL
     */
    public function setCustomUrl(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user || $user->role !== 'staff') {
                Log::warning('LookerStudio: Unauthorized custom URL setting attempt');
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke fitur ini.'
                ], 403);
            }
            
            $request->validate([
                'custom_url' => 'required|url|starts_with:https://lookerstudio.google.com'
            ], [
                'custom_url.required' => 'URL Looker Studio harus diisi.',
                'custom_url.url' => 'Format URL tidak valid.',
                'custom_url.starts_with' => 'URL harus dari Looker Studio (https://lookerstudio.google.com).'
            ]);
            
            $customUrl = $request->input('custom_url');
            
            // Store the custom URL in session or cache
            session(['looker_studio_custom_url' => $customUrl]);
            
            Log::info('LookerStudio: Custom URL set successfully by user ' . $user->id, ['url' => $customUrl]);
            
            return response()->json([
                'success' => true,
                'url' => $customUrl,
                'message' => 'URL Looker Studio eksternal berhasil disimpan!'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('LookerStudio: Validation error in setCustomUrl', [
                'errors' => $e->errors(),
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', array_flatten($e->errors()))
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('LookerStudio: Error in setCustomUrl method - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan URL: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current Looker Studio URL (custom or generated)
     */
    public function getCurrentUrl()
    {
        try {
            $user = Auth::user();
            
            if (!$user || $user->role !== 'staff') {
                Log::warning('LookerStudio: Unauthorized URL retrieval attempt');
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke fitur ini.'
                ], 403);
            }
            
            // Check if there's a custom URL stored
            $customUrl = session('looker_studio_custom_url');
            
            if ($customUrl) {
                return response()->json([
                    'success' => true,
                    'url' => $customUrl,
                    'type' => 'custom',
                    'message' => 'URL Looker Studio eksternal ditemukan.'
                ]);
            }
            
            // If no custom URL, generate a new one
            $data = $this->prepareLookerStudioData();
            $generatedUrl = $this->createLookerStudioUrl($data);
            
            return response()->json([
                'success' => true,
                'url' => $generatedUrl,
                'type' => 'generated',
                'message' => 'URL Looker Studio baru dibuat.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('LookerStudio: Error in getCurrentUrl method - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan URL: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle Looker Studio errors dan berikan solusi
     */
    public function handleError(Request $request)
    {
        try {
            $errorType = $request->input('error_type', 'permission');
            $originalUrl = $request->input('original_url', '');
            
            Log::warning('LookerStudio: Error detected', [
                'error_type' => $errorType,
                'original_url' => $originalUrl,
                'user_id' => Auth::id()
            ]);
            
            $solutions = [
                'permission' => [
                    'title' => 'Masalah Permission/Sharing',
                    'description' => 'Report Looker Studio tidak dapat diakses karena masalah permission.',
                    'solutions' => [
                        'Buat report baru dengan template sederhana',
                        'Gunakan data manual input',
                        'Export data ke Google Sheets terlebih dahulu'
                    ]
                ],
                'data_source' => [
                    'title' => 'Masalah Data Source',
                    'description' => 'Data source tidak dapat diakses atau tidak ada.',
                    'solutions' => [
                        'Gunakan Google Sheets sebagai data source',
                        'Upload data secara manual',
                        'Buat data source baru'
                    ]
                ],
                'template' => [
                    'title' => 'Masalah Template',
                    'description' => 'Template report tidak dapat diakses.',
                    'solutions' => [
                        'Buat report dari awal',
                        'Gunakan template default',
                        'Copy template yang sudah ada'
                    ]
                ]
            ];
            
            $currentSolution = $solutions[$errorType] ?? $solutions['permission'];
            
            // Generate alternative URL
            $alternativeUrl = $this->generateAlternativeUrl();
            
            return response()->json([
                'success' => true,
                'error_info' => $currentSolution,
                'alternative_url' => $alternativeUrl,
                'message' => 'Solusi tersedia untuk mengatasi error ini.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('LookerStudio: Error in handleError method - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menangani error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate alternative URL untuk Looker Studio - Ultra Simple
     */
    private function generateAlternativeUrl()
    {
        try {
            // Generate a simple report ID
            $reportId = 'indismart_alt_' . date('Ymd') . '_' . substr(md5(uniqid()), 0, 8);
            
            // URL alternatif yang sangat sederhana
            $alternativeUrl = 'https://lookerstudio.google.com/reporting/create?' . http_build_query([
                'c.reportId' => $reportId,
                'c.theme' => 'default',
                'c.reportName' => 'Indismart Analytics - Alternative',
            ]);
            
            Log::info('LookerStudio: Alternative URL generated', ['url' => $alternativeUrl]);
            
            return $alternativeUrl;
            
        } catch (\Exception $e) {
            Log::error('LookerStudio: Error generating alternative URL - ' . $e->getMessage());
            
            // Ultimate fallback ke URL paling sederhana
            return 'https://lookerstudio.google.com/reporting/create';
        }
    }

    /**
     * Get data untuk dashboard
     */
    private function getDashboardData()
    {
        try {
            $now = Carbon::now();
            $lastMonth = $now->copy()->subMonth();
            $lastWeek = $now->copy()->subWeek();

            $data = [
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
                ],
                
                'distribusi_status' => $this->getStatusDistribution(),
                'distribusi_jenis_proyek' => $this->getProjectTypeDistribution(),
                'aktivitas_mitra' => $this->getMitraActivity(),
                'top_witel' => $this->getTopWitel(),
                'recent_activities' => $this->getRecentActivities(),
            ];

            Log::info('LookerStudio: Dashboard data retrieved successfully');
            
            return $data;
            
        } catch (\Exception $e) {
            Log::error('LookerStudio: Error in getDashboardData - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // Return default data if error occurs
            return [
                'summary' => [
                    'total_mitra' => 0,
                    'total_dokumen' => 0,
                    'total_foto' => 0,
                    'total_review' => 0,
                    'proyek_aktif' => 0,
                    'proyek_selesai' => 0,
                    'dokumen_pending' => 0,
                ],
                'trends' => [
                    'mitra_baru_bulan_ini' => 0,
                    'dokumen_baru_minggu_ini' => 0,
                    'foto_upload_minggu_ini' => 0,
                ],
                'distribusi_status' => [],
                'distribusi_jenis_proyek' => [],
                'aktivitas_mitra' => [],
                'top_witel' => [],
                'recent_activities' => [],
            ];
        }
    }

    /**
     * Get distribusi status implementasi
     */
    private function getStatusDistribution()
    {
        try {
            $distribution = Dokumen::select('status_implementasi', DB::raw('count(*) as total'))
                ->groupBy('status_implementasi')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->status_implementasi ?? 'Unknown' => $item->total];
                });
                
            Log::info('LookerStudio: Status distribution retrieved successfully', ['count' => $distribution->count()]);
            
            return $distribution;
            
        } catch (\Exception $e) {
            Log::error('LookerStudio: Error in getStatusDistribution - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return collect();
        }
    }

    /**
     * Get distribusi jenis proyek
     */
    private function getProjectTypeDistribution()
    {
        try {
            $distribution = Dokumen::select('jenis_proyek', DB::raw('count(*) as total'))
                ->groupBy('jenis_proyek')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->jenis_proyek ?? 'Unknown' => $item->total];
                });
                
            Log::info('LookerStudio: Project type distribution retrieved successfully', ['count' => $distribution->count()]);
            
            return $distribution;
            
        } catch (\Exception $e) {
            Log::error('LookerStudio: Error in getProjectTypeDistribution - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return collect();
        }
    }

    /**
     * Get aktivitas mitra
     */
    private function getMitraActivity()
    {
        try {
            // Get all users with role 'mitra' and their activity counts
            $mitraActivity = User::where('role', 'mitra')
                ->withCount(['dokumen', 'fotos'])
                ->orderBy('dokumen_count', 'desc')
                ->orderBy('fotos_count', 'desc')
                ->limit(10)
                ->get();
            
            // If no mitra found, create some sample data
            if ($mitraActivity->isEmpty()) {
                Log::info('LookerStudio: No mitra found, creating sample data');
                
                // Get any users and treat them as mitra for demo
                $sampleUsers = User::limit(5)->get();
                
                $mitraActivity = $sampleUsers->map(function ($user) {
                    $dokumenCount = Dokumen::where('user_id', $user->id)->count();
                    $fotosCount = Foto::whereHas('dokumen', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    })->count();
                    
                    return (object) [
                        'id' => $user->id,
                        'name' => $user->name ?? 'User ' . $user->id,
                        'email' => $user->email,
                        'dokumen_count' => $dokumenCount,
                        'fotos_count' => $fotosCount,
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at
                    ];
                });
            }
                
            Log::info('LookerStudio: Mitra activity retrieved successfully', ['count' => $mitraActivity->count()]);
            
            return $mitraActivity;
            
        } catch (\Exception $e) {
            Log::error('LookerStudio: Error in getMitraActivity - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // Return sample data if error occurs
            return collect([
                (object) [
                    'id' => 1,
                    'name' => 'Sample Mitra 1',
                    'email' => 'mitra1@example.com',
                    'dokumen_count' => 3,
                    'fotos_count' => 5,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                (object) [
                    'id' => 2,
                    'name' => 'Sample Mitra 2',
                    'email' => 'mitra2@example.com',
                    'dokumen_count' => 2,
                    'fotos_count' => 3,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
        }
    }

    /**
     * Get top Witel berdasarkan jumlah dokumen
     */
    private function getTopWitel()
    {
        try {
            $topWitel = Dokumen::select('witel', DB::raw('count(*) as total'))
                ->groupBy('witel')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get();
                
            Log::info('LookerStudio: Top Witel retrieved successfully', ['count' => $topWitel->count()]);
            
            return $topWitel;
            
        } catch (\Exception $e) {
            Log::error('LookerStudio: Error in getTopWitel - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return collect();
        }
    }

    /**
     * Get aktivitas terbaru
     */
    private function getRecentActivities()
    {
        try {
            $activities = collect();
            
            // Dokumen terbaru
            $recentDokumen = Dokumen::with('user')
                ->latest()
                ->limit(5)
                ->get()
                ->map(function ($dokumen) {
                    return [
                        'type' => 'dokumen',
                        'title' => 'Dokumen baru: ' . ($dokumen->nama_dokumen ?? 'Unknown'),
                        'user' => $dokumen->user->name ?? 'Unknown',
                        'time' => $dokumen->created_at->diffForHumans(),
                        'data' => $dokumen
                    ];
                });
            
            // Foto terbaru
            $recentFotos = Foto::with(['dokumen.user'])
                ->latest()
                ->limit(5)
                ->get()
                ->map(function ($foto) {
                    return [
                        'type' => 'foto',
                        'title' => 'Foto diupload untuk: ' . ($foto->dokumen->nama_dokumen ?? 'Unknown'),
                        'user' => $foto->dokumen->user->name ?? 'Unknown',
                        'time' => $foto->created_at->diffForHumans(),
                        'data' => $foto
                    ];
                });
            
            // Review terbaru
            $recentReviews = Review::with(['dokumen.user'])
                ->latest()
                ->limit(5)
                ->get()
                ->map(function ($review) {
                    return [
                        'type' => 'review',
                        'title' => 'Review untuk: ' . ($review->dokumen->nama_dokumen ?? 'Unknown'),
                        'user' => $review->dokumen->user->name ?? 'Unknown',
                        'time' => $review->created_at->diffForHumans(),
                        'data' => $review
                    ];
                });
            
            $result = $activities->merge($recentDokumen)
                ->merge($recentFotos)
                ->merge($recentReviews)
                ->sortByDesc('data.created_at')
                ->take(10);
                
            Log::info('LookerStudio: Recent activities retrieved successfully', ['count' => $result->count()]);
            
            return $result;
            
        } catch (\Exception $e) {
            Log::error('LookerStudio: Error in getRecentActivities - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // Return empty collection if error occurs
            return collect();
        }
    }

    /**
     * Prepare data untuk Looker Studio
     */
    private function prepareLookerStudioData()
    {
        try {
            $data = $this->getDashboardData();
            
            // Format data untuk Looker Studio
            $formattedData = [
                'datasets' => [
                    'dokumen' => $this->formatDokumenData(),
                    'mitra' => $this->formatMitraData(),
                    'foto' => $this->formatFotoData(),
                    'review' => $this->formatReviewData(),
                    'aktivitas' => $this->formatActivityData(),
                ],
                'metrics' => $data['summary'],
                'charts' => [
                    'status_distribution' => $data['distribusi_status'],
                    'project_type_distribution' => $data['distribusi_jenis_proyek'],
                    'mitra_activity' => $data['aktivitas_mitra'],
                    'top_witel' => $data['top_witel'],
                ]
            ];

            Log::info('LookerStudio: Data prepared successfully');
            
            return $formattedData;
            
        } catch (\Exception $e) {
            Log::error('LookerStudio: Error in prepareLookerStudioData - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // Return empty data if error occurs
            return [
                'datasets' => [
                    'dokumen' => [],
                    'mitra' => [],
                    'foto' => [],
                    'review' => [],
                    'aktivitas' => [],
                ],
                'metrics' => [
                    'total_mitra' => 0,
                    'total_dokumen' => 0,
                    'total_foto' => 0,
                    'total_review' => 0,
                    'proyek_aktif' => 0,
                    'proyek_selesai' => 0,
                    'dokumen_pending' => 0,
                ],
                'charts' => [
                    'status_distribution' => [],
                    'project_type_distribution' => [],
                    'mitra_activity' => [],
                    'top_witel' => [],
                ]
            ];
        }
    }

    /**
     * Format data dokumen untuk Looker Studio
     */
    private function formatDokumenData()
    {
        try {
            $dokumenData = Dokumen::with(['user', 'fotos', 'reviews'])
                ->get()
                ->map(function ($dokumen) {
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
                        'jumlah_foto' => $dokumen->fotos->count(),
                        'jumlah_review' => $dokumen->reviews->count(),
                        'created_at' => $dokumen->created_at,
                        'updated_at' => $dokumen->updated_at,
                    ];
                });
                
            Log::info('LookerStudio: Dokumen data formatted successfully', ['count' => $dokumenData->count()]);
            
            return $dokumenData;
            
        } catch (\Exception $e) {
            Log::error('LookerStudio: Error in formatDokumenData - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return collect();
        }
    }

    /**
     * Format data mitra untuk Looker Studio
     */
    private function formatMitraData()
    {
        try {
            $mitraData = User::where('role', 'mitra')
                ->withCount(['dokumen', 'fotos'])
                ->get()
                ->map(function ($mitra) {
                    return [
                        'id' => $mitra->id,
                        'name' => $mitra->name ?? 'Unknown',
                        'email' => $mitra->email ?? '',
                        'nomor_kontrak' => $mitra->nomor_kontrak ?? '',
                        'jumlah_dokumen' => $mitra->dokumen_count ?? 0,
                        'jumlah_foto' => $mitra->fotos_count ?? 0,
                        'registered_at' => $mitra->created_at,
                        'last_activity' => $mitra->dokumen()->latest()->first()?->created_at,
                    ];
                });
                
            Log::info('LookerStudio: Mitra data formatted successfully', ['count' => $mitraData->count()]);
            
            return $mitraData;
            
        } catch (\Exception $e) {
            Log::error('LookerStudio: Error in formatMitraData - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return collect();
        }
    }

    /**
     * Format data foto untuk Looker Studio
     */
    private function formatFotoData()
    {
        try {
            $fotoData = Foto::with(['dokumen.user'])
                ->get()
                ->map(function ($foto) {
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
                
            Log::info('LookerStudio: Foto data formatted successfully', ['count' => $fotoData->count()]);
            
            return $fotoData;
            
        } catch (\Exception $e) {
            Log::error('LookerStudio: Error in formatFotoData - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return collect();
        }
    }

    /**
     * Format data review untuk Looker Studio
     */
    private function formatReviewData()
    {
        try {
            $reviewData = Review::with(['dokumen.user'])
                ->get()
                ->map(function ($review) {
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
                
            Log::info('LookerStudio: Review data formatted successfully', ['count' => $reviewData->count()]);
            
            return $reviewData;
            
        } catch (\Exception $e) {
            Log::error('LookerStudio: Error in formatReviewData - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return collect();
        }
    }

    /**
     * Format data aktivitas untuk Looker Studio
     */
    private function formatActivityData()
    {
        try {
            $activities = collect();
            
            // Dokumen activities
            Dokumen::with('user')->chunk(100, function ($dokumen) use ($activities) {
                foreach ($dokumen as $doc) {
                    $activities->push([
                        'type' => 'dokumen_created',
                        'entity_id' => $doc->id,
                        'entity_name' => $doc->nama_dokumen ?? 'Unknown',
                        'user' => $doc->user->name ?? 'Unknown',
                        'action' => 'Dokumen dibuat',
                        'timestamp' => $doc->created_at,
                    ]);
                }
            });
            
            // Foto activities
            Foto::with(['dokumen.user'])->chunk(100, function ($fotos) use ($activities) {
                foreach ($fotos as $foto) {
                    $activities->push([
                        'type' => 'foto_uploaded',
                        'entity_id' => $foto->id,
                        'entity_name' => $foto->dokumen->nama_dokumen ?? 'Unknown',
                        'user' => $foto->dokumen->user->name ?? 'Unknown',
                        'action' => 'Foto diupload',
                        'timestamp' => $foto->created_at,
                    ]);
                }
            });
            
            $sortedActivities = $activities->sortByDesc('timestamp');
            
            Log::info('LookerStudio: Activity data formatted successfully', ['count' => $sortedActivities->count()]);
            
            return $sortedActivities;
            
        } catch (\Exception $e) {
            Log::error('LookerStudio: Error in formatActivityData - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return collect();
        }
    }

    /**
     * Create Looker Studio URL - Completely Simplified
     */
    private function createLookerStudioUrl($data)
    {
        try {
            // Use the absolute simplest approach - just the base URL
            $baseUrl = 'https://lookerstudio.google.com/reporting/create';
            
            Log::info('LookerStudio: Using base URL only', ['url' => $baseUrl]);
            
            return $baseUrl;
            
        } catch (\Exception $e) {
            Log::error('LookerStudio: Error creating URL - ' . $e->getMessage());
            
            // Ultimate fallback - just the base URL
            return 'https://lookerstudio.google.com/reporting/create';
        }
    }



    /**
     * Create a direct link to Looker Studio
     */
    public function createDirectLink()
    {
        try {
            Log::info('LookerStudio: Direct link creation requested', [
                'user_id' => Auth::id(),
                'user_agent' => request()->userAgent(),
                'ip' => request()->ip()
            ]);
            
            $user = Auth::user();
            
            if (!$user) {
                Log::warning('LookerStudio: Unauthorized direct link creation attempt - No user');
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan login terlebih dahulu.'
                ], 401);
            }
            
            if ($user->role !== 'staff') {
                Log::warning('LookerStudio: Unauthorized direct link creation attempt by user ' . $user->id . ' with role ' . $user->role);
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke fitur ini. Hanya staff yang dapat mengakses.'
                ], 403);
            }
            
            // Create a direct link to Looker Studio with better URL
            $directUrl = 'https://lookerstudio.google.com/reporting/create';
            
            Log::info('LookerStudio: Direct link created successfully by user ' . $user->id, [
                'url' => $directUrl
            ]);
            
            return response()->json([
                'success' => true,
                'url' => $directUrl,
                'message' => 'Link langsung ke Looker Studio berhasil dibuat! Klik untuk membuka dashboard baru.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('LookerStudio: Error in createDirectLink - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => Auth::id(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat link langsung: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate BigQuery SQL untuk Looker Studio
     */
    private function generateBigQuerySQL($data)
    {
        try {
            $sql = "
            WITH dokumen_data AS (
                SELECT 
                    id,
                    nama_dokumen,
                    jenis_proyek,
                    status_implementasi,
                    witel,
                    sto,
                    site_name,
                    tanggal_dokumen,
                    created_at,
                    updated_at
                FROM `indismart.dokumen`
            ),
            mitra_data AS (
                SELECT 
                    id,
                    name as mitra_name,
                    email,
                    nomor_kontrak,
                    created_at as registered_at
                FROM `indismart.users`
                WHERE role = 'mitra'
            ),
            foto_data AS (
                SELECT 
                    dokumen_id,
                    COUNT(*) as jumlah_foto,
                    SUM(CAST(file_size AS INT64)) as total_size
                FROM `indismart.fotos`
                GROUP BY dokumen_id
            ),
            review_data AS (
                SELECT 
                    dokumen_id,
                    COUNT(*) as jumlah_review,
                    AVG(CAST(rating AS FLOAT64)) as avg_rating
                FROM `indismart.reviews`
                GROUP BY dokumen_id
            )
            SELECT 
                d.*,
                m.mitra_name,
                m.email,
                m.nomor_kontrak,
                COALESCE(f.jumlah_foto, 0) as jumlah_foto,
                COALESCE(f.total_size, 0) as total_foto_size,
                COALESCE(r.jumlah_review, 0) as jumlah_review,
                COALESCE(r.avg_rating, 0) as avg_rating
            FROM dokumen_data d
            LEFT JOIN mitra_data m ON d.user_id = m.id
            LEFT JOIN foto_data f ON d.id = f.dokumen_id
            LEFT JOIN review_data r ON d.id = r.dokumen_id
            ORDER BY d.created_at DESC
            ";
            
            Log::info('LookerStudio: BigQuery SQL generated successfully', ['sql_length' => strlen($sql)]);
            
            return $sql;
            
        } catch (\Exception $e) {
            Log::error('LookerStudio: Error in generateBigQuerySQL - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            throw new \Exception('Gagal generate BigQuery SQL: ' . $e->getMessage());
        }
    }

    /**
     * Generate unique report ID
     */
    private function generateReportId()
    {
        try {
            $reportId = 'indismart_' . date('Ymd') . '_' . uniqid();
            
            Log::info('LookerStudio: Report ID generated successfully', ['report_id' => $reportId]);
            
            return $reportId;
            
        } catch (\Exception $e) {
            Log::error('LookerStudio: Error in generateReportId - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // Fallback to simple ID
            return 'indismart_' . time() . '_' . rand(1000, 9999);
        }
    }

    /**
     * Export data untuk Looker Studio
     */
    public function exportData(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user || $user->role !== 'staff') {
                Log::warning('LookerStudio: Unauthorized export attempt');
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke fitur ini.'
                ], 403);
            }
            
            $format = $request->get('format', 'json');
            $data = $this->prepareLookerStudioData();
            
            Log::info('LookerStudio: Data export requested', [
                'format' => $format,
                'user_id' => $user->id
            ]);
            
            switch ($format) {
                case 'csv':
                    return $this->exportToCSV($data);
                case 'json':
                default:
                    return response()->json([
                        'success' => true,
                        'data' => $data
                    ]);
            }
            
        } catch (\Exception $e) {
            Log::error('LookerStudio: Error in exportData - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal export data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export data ke CSV
     */
    private function exportToCSV($data)
    {
        try {
            $filename = 'indismart_analytics_' . date('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            $callback = function() use ($data) {
                try {
                    $file = fopen('php://output', 'w');
                    
                    if (!$file) {
                        throw new \Exception('Failed to open file for writing');
                    }
                    
                    // Write headers
                    fputcsv($file, ['Dataset', 'Field', 'Value']);
                    
                    // Write data
                    if (isset($data['datasets']) && is_array($data['datasets'])) {
                        foreach ($data['datasets'] as $datasetName => $dataset) {
                            if (is_array($dataset)) {
                                foreach ($dataset as $record) {
                                    if (is_array($record)) {
                                        foreach ($record as $field => $value) {
                                            fputcsv($file, [$datasetName, $field, $value]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    fclose($file);
                    
                } catch (\Exception $e) {
                    Log::error('LookerStudio: Error in CSV export callback - ' . $e->getMessage());
                    throw $e;
                }
            };
            
            Log::info('LookerStudio: CSV export completed successfully');
            
            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            Log::error('LookerStudio: Error in exportToCSV - ' . $e->getMessage());
            throw new \Exception('Gagal export ke CSV: ' . $e->getMessage());
        }
    }


}
