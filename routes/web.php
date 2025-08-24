<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ManajemenMitraController;
use App\Http\Controllers\NomorKontrakController;
use App\Http\Controllers\SettingsController;

use App\Http\Controllers\FotoController;
use App\Http\Controllers\LookerStudioController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Mitra Routes
Route::group(['prefix' => 'mitra'], function () {
    Route::get('/dashboard', [DashboardController::class, 'mitraDashboard'])->name('mitra.dashboard');
});

// Karyawan Routes
Route::group(['prefix' => 'staff'], function () {
    Route::get('/dashboard', [DashboardController::class, 'staffDashboard'])->name('staff.dashboard');
    Route::get('/mitra/{id}/detail', [DashboardController::class, 'mitraDetail'])->name('staff.mitra.detail');
});

// Profile Routes
Route::group(['middleware' => 'auth'], function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.delete-avatar');
});

// Dokumen Routes
Route::group(['middleware' => 'auth'], function () {
    Route::get('/dokumen', [DokumenController::class, 'index'])->name('dokumen.index');
    Route::get('/dokumen/create', [DokumenController::class, 'create'])->name('dokumen.create');
    Route::post('/dokumen', [DokumenController::class, 'store'])->name('dokumen.store');
    Route::get('/dokumen/{dokumen}', [DokumenController::class, 'show'])->name('dokumen.show');
    Route::get('/dokumen/{dokumen}/edit', [DokumenController::class, 'edit'])->name('dokumen.edit');
    Route::put('/dokumen/{dokumen}', [DokumenController::class, 'update'])->name('dokumen.update');
    Route::delete('/dokumen/{dokumen}', [DokumenController::class, 'destroy'])->name('dokumen.destroy');
    Route::get('/dokumen/{dokumen}/download', [DokumenController::class, 'download'])->name('dokumen.download');
    Route::delete('/dokumen/{dokumen}/file', [DokumenController::class, 'deleteFile'])->name('dokumen.delete-file');
});

// Foto Routes
Route::group(['middleware' => 'auth'], function () {
    Route::get('/dokumen/{dokumen}/fotos', [FotoController::class, 'index'])->name('fotos.index');
    Route::post('/dokumen/{dokumen}/fotos', [FotoController::class, 'store'])->name('fotos.store');
    Route::delete('/fotos/{foto}', [FotoController::class, 'destroy'])->name('fotos.destroy');
    Route::put('/dokumen/{dokumen}/fotos/order', [FotoController::class, 'updateOrder'])->name('fotos.update-order');
    Route::get('/fotos/{foto}/download', [FotoController::class, 'download'])->name('fotos.download');
});

// Notification Routes
Route::group(['middleware' => 'auth'], function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread', [NotificationController::class, 'getUnreadNotifications'])->name('notifications.unread');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
});

// Review Routes (Karyawan Only)
Route::group(['middleware' => ['auth', 'role:staff']], function () {
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/create/{dokumen}', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews/{dokumen}', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::get('/reviews/pending/count', [ReviewController::class, 'getPendingReviews'])->name('reviews.pending-count');
});

// Manajemen Mitra Routes (Karyawan Only)
Route::group(['middleware' => ['auth', 'role:staff']], function () {
    Route::get('/manajemen-mitra', [ManajemenMitraController::class, 'index'])->name('manajemen-mitra.index');
    Route::get('/manajemen-mitra/{id}', [ManajemenMitraController::class, 'show'])->name('manajemen-mitra.show');
    Route::get('/manajemen-mitra/export', [ManajemenMitraController::class, 'export'])->name('manajemen-mitra.export');
});

// Nomor Kontrak Routes (Karyawan Only)
Route::group(['middleware' => ['auth', 'role:staff']], function () {
    Route::get('/nomor-kontrak', [NomorKontrakController::class, 'index'])->name('nomor-kontrak.index');
    Route::get('/nomor-kontrak/{id}/assign', [NomorKontrakController::class, 'assign'])->name('nomor-kontrak.assign');
    Route::post('/nomor-kontrak/{id}', [NomorKontrakController::class, 'store'])->name('nomor-kontrak.store');
    Route::get('/nomor-kontrak/generate', [NomorKontrakController::class, 'generate'])->name('nomor-kontrak.generate');
    Route::get('/nomor-kontrak/bulk-assign', [NomorKontrakController::class, 'bulkAssign'])->name('nomor-kontrak.bulk-assign');
    Route::post('/nomor-kontrak/bulk-assign', [NomorKontrakController::class, 'bulkAssignStore'])->name('nomor-kontrak.bulk-assign-store');
    Route::post('/nomor-kontrak/bulk-assign-selected', [NomorKontrakController::class, 'bulkAssignSelected'])->name('nomor-kontrak.bulk-assign-selected');
});

// Settings Routes
Route::group(['middleware' => 'auth'], function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/appearance', [SettingsController::class, 'appearance'])->name('settings.appearance');
    Route::get('/settings/notifications', [SettingsController::class, 'notifications'])->name('settings.notifications');
    Route::get('/settings/profile', [SettingsController::class, 'profile'])->name('settings.profile');
    Route::get('/settings/security', [SettingsController::class, 'security'])->name('settings.security');
    Route::get('/settings/system', [SettingsController::class, 'system'])->name('settings.system');
});



// Dashboard Routes
Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/manajemen-dokumen', [DashboardController::class, 'manajemenDokumen'])->name('manajemen-dokumen');
});

// Looker Studio Routes (Staff Only)
Route::group(['middleware' => ['auth', 'role:staff']], function () {
    Route::get('/looker-studio', [LookerStudioController::class, 'index'])->name('looker-studio.index');
    Route::post('/looker-studio/generate', [LookerStudioController::class, 'generateDashboard'])->name('looker-studio.generate');
    Route::post('/looker-studio/set-custom-url', [LookerStudioController::class, 'setCustomUrl'])->name('looker-studio.set-custom-url');
    Route::get('/looker-studio/get-current-url', [LookerStudioController::class, 'getCurrentUrl'])->name('looker-studio.get-current-url');
    Route::post('/looker-studio/handle-error', [LookerStudioController::class, 'handleError'])->name('looker-studio.handle-error');
});

