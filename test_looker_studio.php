<?php

/**
 * Test Script untuk Fitur Looker Studio
 * 
 * Script ini akan menguji semua komponen fitur Looker Studio
 * dan memberikan laporan detail tentang status setiap komponen.
 */

echo "🧪 Test Script untuk Fitur Looker Studio\n";
echo "==========================================\n\n";

// 1. Test Environment Laravel
echo "1. Testing Environment Laravel...\n";
echo "--------------------------------\n";

if (!file_exists('.env')) {
    echo "❌ File .env tidak ditemukan\n";
} else {
    echo "✅ File .env ditemukan\n";
}

if (!file_exists('artisan')) {
    echo "❌ File artisan tidak ditemukan\n";
} else {
    echo "✅ File artisan ditemukan\n";
}

// 2. Test Database Connection
echo "\n2. Testing Database Connection...\n";
echo "--------------------------------\n";

try {
    require_once 'vendor/autoload.php';
    
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    $connection = DB::connection();
    $connection->getPdo();
    echo "✅ Database connection berhasil\n";
    
    // Test query sederhana
    $result = DB::select('SELECT 1 as test');
    if ($result) {
        echo "✅ Database query berhasil\n";
    }
    
} catch (Exception $e) {
    echo "❌ Database connection gagal: " . $e->getMessage() . "\n";
}

// 3. Test Models
echo "\n3. Testing Models...\n";
echo "-------------------\n";

$models = ['User', 'Dokumen', 'Foto', 'Review'];

foreach ($models as $model) {
    try {
        $modelClass = "App\\Models\\$model";
        if (class_exists($modelClass)) {
            $count = $modelClass::count();
            echo "✅ Model $model ditemukan (Total records: $count)\n";
        } else {
            echo "❌ Model $model tidak ditemukan\n";
        }
    } catch (Exception $e) {
        echo "❌ Error pada model $model: " . $e->getMessage() . "\n";
    }
}

// 4. Test Controllers
echo "\n4. Testing Controllers...\n";
echo "------------------------\n";

$controllers = [
    'App\\Http\\Controllers\\LookerStudioController',
    'App\\Http\\Controllers\\Api\\LookerStudioApiController'
];

foreach ($controllers as $controller) {
    try {
        if (class_exists($controller)) {
            echo "✅ Controller $controller ditemukan\n";
            
            // Test method existence
            $reflection = new ReflectionClass($controller);
            $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
            echo "   Methods: " . count($methods) . " public methods\n";
        } else {
            echo "❌ Controller $controller tidak ditemukan\n";
        }
    } catch (Exception $e) {
        echo "❌ Error pada controller $controller: " . $e->getMessage() . "\n";
    }
}

// 5. Test Views
echo "\n5. Testing Views...\n";
echo "------------------\n";

$views = [
    'resources/views/looker-studio/index.blade.php',
    'resources/views/layouts/app.blade.php'
];

foreach ($views as $view) {
    if (file_exists($view)) {
        echo "✅ View $view ditemukan\n";
    } else {
        echo "❌ View $view tidak ditemukan\n";
    }
}

// 6. Test Routes
echo "\n6. Testing Routes...\n";
echo "-------------------\n";

try {
    $routes = Route::getRoutes();
    $lookerStudioRoutes = [];
    
    foreach ($routes as $route) {
        $uri = $route->uri();
        if (strpos($uri, 'looker') !== false || strpos($uri, 'analytics') !== false) {
            $lookerStudioRoutes[] = $uri;
        }
    }
    
    if (count($lookerStudioRoutes) > 0) {
        echo "✅ Looker Studio routes ditemukan:\n";
        foreach ($lookerStudioRoutes as $route) {
            echo "   - $route\n";
        }
    } else {
        echo "❌ Tidak ada Looker Studio routes ditemukan\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error testing routes: " . $e->getMessage() . "\n";
}

// 7. Test Middleware
echo "\n7. Testing Middleware...\n";
echo "----------------------\n";

$middlewares = [
    'App\\Http\\Middleware\\CheckRole',
    'App\\Http\\Middleware\\RoleMiddleware'
];

foreach ($middlewares as $middleware) {
    try {
        if (class_exists($middleware)) {
            echo "✅ Middleware $middleware ditemukan\n";
        } else {
            echo "❌ Middleware $middleware tidak ditemukan\n";
        }
    } catch (Exception $e) {
        echo "❌ Error pada middleware $middleware: " . $e->getMessage() . "\n";
    }
}

// 8. Test API Endpoints
echo "\n8. Testing API Endpoints...\n";
echo "--------------------------\n";

$apiEndpoints = [
    '/api/looker-studio/analytics',
    '/api/looker-studio/summary',
    '/api/looker-studio/export',
    '/api/looker-studio/charts',
    '/api/looker-studio/trends',
    '/api/looker-studio/real-time'
];

foreach ($apiEndpoints as $endpoint) {
    try {
        $response = app()->handle(Request::create($endpoint, 'GET'));
        $statusCode = $response->getStatusCode();
        
        if ($statusCode === 200 || $statusCode === 401 || $statusCode === 403) {
            echo "✅ API $endpoint merespons (Status: $statusCode)\n";
        } else {
            echo "❌ API $endpoint error (Status: $statusCode)\n";
        }
    } catch (Exception $e) {
        echo "❌ API $endpoint gagal: " . $e->getMessage() . "\n";
    }
}

// 9. Test Data Availability
echo "\n9. Testing Data Availability...\n";
echo "------------------------------\n";

try {
    $userCount = App\Models\User::count();
    $dokumenCount = App\Models\Dokumen::count();
    $fotoCount = App\Models\Foto::count();
    $reviewCount = App\Models\Review::count();
    
    echo "✅ Data availability:\n";
    echo "   - Users: $userCount\n";
    echo "   - Dokumen: $dokumenCount\n";
    echo "   - Fotos: $fotoCount\n";
    echo "   - Reviews: $reviewCount\n";
    
    if ($dokumenCount > 0) {
        // Test sample data
        $sampleDokumen = App\Models\Dokumen::first();
        if ($sampleDokumen) {
            echo "✅ Sample dokumen data tersedia\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error testing data availability: " . $e->getMessage() . "\n";
}

// 10. Test Logging
echo "\n10. Testing Logging...\n";
echo "---------------------\n";

try {
    $logFile = 'storage/logs/laravel.log';
    if (file_exists($logFile)) {
        $logSize = filesize($logFile);
        echo "✅ Log file ditemukan (Size: " . number_format($logSize) . " bytes)\n";
        
        // Test writing to log
        Log::info('Test log entry from Looker Studio test script');
        echo "✅ Log writing berhasil\n";
    } else {
        echo "❌ Log file tidak ditemukan\n";
    }
} catch (Exception $e) {
    echo "❌ Error testing logging: " . $e->getMessage() . "\n";
}

// 11. Test Cache
echo "\n11. Testing Cache...\n";
echo "-------------------\n";

try {
    Cache::put('test_key', 'test_value', 60);
    $value = Cache::get('test_key');
    
    if ($value === 'test_value') {
        echo "✅ Cache system berfungsi\n";
    } else {
        echo "❌ Cache system tidak berfungsi\n";
    }
    
    Cache::forget('test_key');
    
} catch (Exception $e) {
    echo "❌ Error testing cache: " . $e->getMessage() . "\n";
}

// 12. Test File Permissions
echo "\n12. Testing File Permissions...\n";
echo "------------------------------\n";

$directories = [
    'storage/logs',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'bootstrap/cache'
];

foreach ($directories as $dir) {
    if (is_dir($dir)) {
        if (is_writable($dir)) {
            echo "✅ Directory $dir writable\n";
        } else {
            echo "❌ Directory $dir tidak writable\n";
        }
    } else {
        echo "❌ Directory $dir tidak ditemukan\n";
    }
}

// 13. Test Dependencies
echo "\n13. Testing Dependencies...\n";
echo "--------------------------\n";

$dependencies = [
    'Illuminate\\Support\\Facades\\DB',
    'Illuminate\\Support\\Facades\\Log',
    'Illuminate\\Support\\Facades\\Cache',
    'Illuminate\\Support\\Facades\\Route',
    'Illuminate\\Http\\Request'
];

foreach ($dependencies as $dependency) {
    try {
        if (class_exists($dependency)) {
            echo "✅ Dependency $dependency tersedia\n";
        } else {
            echo "❌ Dependency $dependency tidak tersedia\n";
        }
    } catch (Exception $e) {
        echo "❌ Error testing dependency $dependency: " . $e->getMessage() . "\n";
    }
}

// 14. Generate Test Report
echo "\n14. Generating Test Report...\n";
echo "-----------------------------\n";

$report = [
    'timestamp' => date('Y-m-d H:i:s'),
    'environment' => app()->environment(),
    'laravel_version' => app()->version(),
    'php_version' => PHP_VERSION,
    'memory_usage' => memory_get_usage(true),
    'peak_memory' => memory_get_peak_usage(true)
];

$reportFile = 'looker_studio_test_report.json';
file_put_contents($reportFile, json_encode($report, JSON_PRETTY_PRINT));

echo "✅ Test report disimpan ke $reportFile\n";

// 15. Summary
echo "\n15. Test Summary\n";
echo "----------------\n";

echo "🎯 Looker Studio Test Script selesai!\n";
echo "📊 Silakan periksa hasil test di atas untuk memastikan semua komponen berfungsi dengan baik.\n";
echo "📝 Jika ada error, gunakan troubleshooting guide yang tersedia.\n";
echo "🔧 Jalankan 'php fix_looker_studio.php' untuk auto-fix jika diperlukan.\n\n";

echo "📋 Langkah selanjutnya:\n";
echo "1. Periksa log file di storage/logs/laravel.log\n";
echo "2. Test fitur Looker Studio di browser\n";
echo "3. Periksa browser console untuk JavaScript errors\n";
echo "4. Verifikasi API endpoints dengan Postman atau browser\n";
echo "5. Test dengan user yang memiliki role 'staff'\n\n";

echo "✨ Happy testing! ✨\n";
