# Looker Studio Troubleshooting Guide

## Daftar Isi
1. [Pengecekan Error Umum](#pengecekan-error-umum)
2. [Error Authentication](#error-authentication)
3. [Error Database](#error-database)
4. [Error JavaScript](#error-javascript)
5. [Error API](#error-api)
6. [Error View/Blade](#error-viewblade)
7. [Error Route](#error-route)
8. [Error Middleware](#error-middleware)
9. [Solusi Langkah demi Langkah](#solusi-langkah-demi-langkah)
10. [Log Monitoring](#log-monitoring)

## Pengecekan Error Umum

### 1. Cek Log Laravel
```bash
# Cek log error Laravel
tail -f storage/logs/laravel.log

# Cek log error terbaru
grep -i "looker" storage/logs/laravel.log
```

### 2. Cek Console Browser
1. Buka browser
2. Tekan F12
3. Pilih tab "Console"
4. Lihat error yang muncul

### 3. Cek Network Tab
1. Buka browser
2. Tekan F12
3. Pilih tab "Network"
4. Refresh halaman
5. Lihat request yang gagal

## Error Authentication

### Gejala:
- "Anda tidak memiliki akses ke fitur ini"
- Redirect ke login page
- Error 403 Forbidden

### Solusi:
```bash
# Cek user role di database
php artisan tinker
>>> App\Models\User::find(1)->role
```

### Perbaikan:
```php
// Pastikan user memiliki role 'staff'
$user = Auth::user();
if ($user->role !== 'staff') {
    return redirect()->route('dashboard')->with('error', 'Akses ditolak');
}
```

## Error Database

### Gejala:
- "SQLSTATE[42S02]: Base table or view not found"
- "Column not found"
- Error 500 Internal Server Error

### Solusi:
```bash
# Jalankan migration
php artisan migrate

# Cek status migration
php artisan migrate:status

# Refresh database (HATI-HATI: akan menghapus data)
php artisan migrate:fresh --seed
```

### Perbaikan:
```php
// Pastikan model relationships benar
class Dokumen extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function fotos()
    {
        return $this->hasMany(Foto::class);
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
```

## Error JavaScript

### Gejala:
- Chart tidak muncul
- Button tidak berfungsi
- Error di console browser

### Solusi:
```javascript
// Pastikan Chart.js terload
if (typeof Chart === 'undefined') {
    console.error('Chart.js not loaded');
}

// Pastikan CSRF token ada
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
```

### Perbaikan:
```html
<!-- Tambahkan di layout -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
```

## Error API

### Gejala:
- "Failed to fetch"
- Error 404 Not Found
- Error 500 Internal Server Error

### Solusi:
```bash
# Cek route API
php artisan route:list --path=api/looker-studio

# Test API endpoint
curl -X GET http://localhost:8000/api/looker-studio/analytics
```

### Perbaikan:
```php
// Pastikan route terdaftar
Route::prefix('looker-studio')->group(function () {
    Route::get('/analytics', [LookerStudioApiController::class, 'getAnalyticsData']);
    Route::get('/realtime', [LookerStudioApiController::class, 'getRealTimeData']);
    Route::get('/export', [LookerStudioApiController::class, 'exportData']);
});
```

## Error View/Blade

### Gejala:
- "View not found"
- Error syntax di blade
- Variable undefined

### Solusi:
```bash
# Clear view cache
php artisan view:clear

# Cek file view ada
ls resources/views/looker-studio/
```

### Perbaikan:
```php
// Pastikan view ada dan data terkirim
return view('looker-studio.index', compact('dashboardData'));
```

## Error Route

### Gejala:
- "Route not found"
- Error 404
- Link tidak berfungsi

### Solusi:
```bash
# Clear route cache
php artisan route:clear

# Cek route terdaftar
php artisan route:list --name=looker-studio
```

### Perbaikan:
```php
// Pastikan route terdaftar dengan benar
Route::group(['middleware' => ['auth', 'role:staff']], function () {
    Route::get('/looker-studio', [LookerStudioController::class, 'index'])->name('looker-studio.index');
    Route::post('/looker-studio/generate', [LookerStudioController::class, 'generateDashboard'])->name('looker-studio.generate');
    Route::get('/looker-studio/export', [LookerStudioController::class, 'exportData'])->name('looker-studio.export');
});
```

## Error Middleware

### Gejala:
- "Middleware not found"
- Error 500
- Akses ditolak

### Solusi:
```bash
# Cek middleware terdaftar
php artisan route:list --middleware=role

# Clear config cache
php artisan config:clear
```

### Perbaikan:
```php
// Pastikan middleware terdaftar di Kernel.php
protected $routeMiddleware = [
    'role' => \App\Http\Middleware\RoleMiddleware::class,
];
```

## Solusi Langkah demi Langkah

### Langkah 1: Cek Dependencies
```bash
# Install dependencies
composer install
npm install

# Clear semua cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Langkah 2: Cek Database
```bash
# Jalankan migration
php artisan migrate

# Cek ada data
php artisan tinker
>>> App\Models\User::count()
>>> App\Models\Dokumen::count()
```

### Langkah 3: Cek File Permissions
```bash
# Set permission storage
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

### Langkah 4: Cek Environment
```bash
# Copy .env.example
cp .env.example .env

# Generate key
php artisan key:generate

# Set database config
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=indihome
DB_USERNAME=root
DB_PASSWORD=
```

### Langkah 5: Test Fitur
```bash
# Start server
php artisan serve

# Buka browser
http://localhost:8000/looker-studio
```

## Log Monitoring

### Setup Log Monitoring
```php
// Tambahkan di .env
LOG_CHANNEL=daily
LOG_LEVEL=debug
```

### Cek Log Real-time
```bash
# Monitor log
tail -f storage/logs/laravel-$(date +%Y-%m-%d).log

# Filter log Looker Studio
grep -i "looker" storage/logs/laravel-$(date +%Y-%m-%d).log
```

### Log yang Perlu Diperhatikan
- `LookerStudio: User not authenticated`
- `LookerStudio: Unauthorized access attempt`
- `LookerStudio API: Error in getAnalyticsData`
- `SQLSTATE[42S02]: Base table or view not found`

## Troubleshooting Checklist

- [ ] User sudah login
- [ ] User memiliki role 'staff'
- [ ] Database migration sudah dijalankan
- [ ] Route terdaftar dengan benar
- [ ] Middleware berfungsi
- [ ] View file ada
- [ ] JavaScript dependencies terload
- [ ] API endpoint bisa diakses
- [ ] Log tidak menunjukkan error
- [ ] File permissions benar

## Contact Support

Jika masalah masih berlanjut, silakan:
1. Cek log error di `storage/logs/laravel.log`
2. Screenshot error di browser console
3. Berikan detail langkah yang dilakukan
4. Berikan informasi environment (OS, PHP version, Laravel version)
