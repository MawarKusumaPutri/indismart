<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LookerStudioApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Legacy API routes for backward compatibility (temporary without auth for testing)
Route::get('/mitra-analytics', [\App\Http\Controllers\LookerStudioController::class, 'getMitraAnalyticsApi']);
Route::get('/proyek-analytics', [\App\Http\Controllers\LookerStudioController::class, 'getProyekAnalyticsApi']);

// Looker Studio API Routes (temporary without auth for testing)
Route::prefix('looker-studio')->group(function () {
    // Dashboard Overview Data
    Route::get('/dashboard-overview', [LookerStudioApiController::class, 'getDashboardOverview']);
    
    // Mitra Analytics
    Route::get('/mitra-analytics', [LookerStudioApiController::class, 'getMitraAnalytics']);
    
    // Proyek Analytics
    Route::get('/proyek-analytics', [LookerStudioApiController::class, 'getProyekAnalytics']);
    
    // Performance Metrics
    Route::get('/performance-metrics', [LookerStudioApiController::class, 'getPerformanceMetrics']);
    
    // Trends Data
    Route::get('/trends-monthly', [LookerStudioApiController::class, 'getMonthlyTrends']);
    
    // Export Data
    Route::get('/export-complete', [LookerStudioApiController::class, 'exportCompleteData']);
});

// Public API endpoints (if needed)
Route::prefix('public')->group(function () {
    Route::get('/stats', [LookerStudioApiController::class, 'getPublicStats']);
});
