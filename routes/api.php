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

// Looker Studio API Routes
Route::prefix('looker-studio')->group(function () {
    Route::get('/analytics', [LookerStudioApiController::class, 'getAnalyticsData']);
    Route::get('/summary', [LookerStudioApiController::class, 'getSummaryData']);
    Route::get('/realtime', [LookerStudioApiController::class, 'getRealTimeData']);
    Route::get('/export', [LookerStudioApiController::class, 'exportData']);
    Route::get('/charts', [LookerStudioApiController::class, 'getChartsData']);
    Route::get('/trends', [LookerStudioApiController::class, 'getTrendsData']);
});


