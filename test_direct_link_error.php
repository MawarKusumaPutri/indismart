<?php
/**
 * Test Script untuk Debug Error Direct Link
 * 
 * Tujuan:
 * - Mengidentifikasi error spesifik pada fitur Direct Link
 * - Memverifikasi route dan controller method
 * - Menguji response dari API
 */

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Artisan;

echo "=== TEST DEBUG DIRECT LINK ERROR ===\n\n";

// 1. Test apakah route terdaftar
echo "1. Testing Route Registration...\n";
try {
    $routes = Artisan::call('route:list', ['--name' => 'looker-studio.create-direct-link']);
    echo "✓ Route terdaftar dengan benar\n";
} catch (Exception $e) {
    echo "✗ Route tidak terdaftar: " . $e->getMessage() . "\n";
}

// 2. Test apakah controller method ada
echo "\n2. Testing Controller Method...\n";
try {
    $controllerPath = 'app/Http/Controllers/LookerStudioController.php';
    if (file_exists($controllerPath)) {
        $controllerContent = file_get_contents($controllerPath);
        if (strpos($controllerContent, 'public function createDirectLink()') !== false) {
            echo "✓ Method createDirectLink() ada di controller\n";
        } else {
            echo "✗ Method createDirectLink() tidak ada di controller\n";
        }
    } else {
        echo "✗ Controller file tidak ditemukan\n";
    }
} catch (Exception $e) {
    echo "✗ Error checking controller: " . $e->getMessage() . "\n";
}

// 3. Test apakah middleware auth dan role:staff ada
echo "\n3. Testing Middleware...\n";
try {
    $controllerContent = file_get_contents('app/Http/Controllers/LookerStudioController.php');
    if (strpos($controllerContent, 'middleware(\'auth\')') !== false) {
        echo "✓ Auth middleware ada\n";
    } else {
        echo "✗ Auth middleware tidak ada\n";
    }
    
    if (strpos($controllerContent, 'middleware(\'role:staff\')') !== false) {
        echo "✓ Role:staff middleware ada\n";
    } else {
        echo "✗ Role:staff middleware tidak ada\n";
    }
} catch (Exception $e) {
    echo "✗ Error checking middleware: " . $e->getMessage() . "\n";
}

// 4. Test apakah JavaScript function ada
echo "\n4. Testing JavaScript Function...\n";
try {
    $viewContent = file_get_contents('resources/views/looker-studio/index.blade.php');
    if (strpos($viewContent, 'function createDirectLink()') !== false) {
        echo "✓ JavaScript function createDirectLink() ada\n";
    } else {
        echo "✗ JavaScript function createDirectLink() tidak ada\n";
    }
    
    if (strpos($viewContent, '/looker-studio/create-direct-link') !== false) {
        echo "✓ URL endpoint ada di JavaScript\n";
    } else {
        echo "✗ URL endpoint tidak ada di JavaScript\n";
    }
} catch (Exception $e) {
    echo "✗ Error checking JavaScript: " . $e->getMessage() . "\n";
}

// 5. Test apakah CSRF token ada di layout
echo "\n5. Testing CSRF Token...\n";
try {
    $layoutContent = file_get_contents('resources/views/layouts/app.blade.php');
    if (strpos($layoutContent, 'csrf-token') !== false) {
        echo "✓ CSRF token meta tag ada di layout\n";
    } else {
        echo "✗ CSRF token meta tag tidak ada di layout\n";
    }
} catch (Exception $e) {
    echo "✗ Error checking CSRF token: " . $e->getMessage() . "\n";
}

// 6. Test apakah ada syntax error di controller
echo "\n6. Testing Controller Syntax...\n";
try {
    $controllerContent = file_get_contents('app/Http/Controllers/LookerStudioController.php');
    
    // Check for common syntax issues
    $bracketCount = substr_count($controllerContent, '{') - substr_count($controllerContent, '}');
    if ($bracketCount === 0) {
        echo "✓ Bracket balance OK\n";
    } else {
        echo "✗ Bracket imbalance: " . $bracketCount . "\n";
    }
    
    // Check for missing semicolons in method definitions
    if (preg_match('/public function createDirectLink\(\)\s*\{/', $controllerContent)) {
        echo "✓ Method syntax OK\n";
    } else {
        echo "✗ Method syntax error\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error checking syntax: " . $e->getMessage() . "\n";
}

// 7. Test apakah ada error di log
echo "\n7. Testing Log Files...\n";
try {
    $logPath = 'storage/logs/laravel.log';
    if (file_exists($logPath)) {
        $logContent = file_get_contents($logPath);
        $lines = explode("\n", $logContent);
        $recentLines = array_slice($lines, -50); // Last 50 lines
        
        $directLinkErrors = array_filter($recentLines, function($line) {
            return strpos($line, 'createDirectLink') !== false || 
                   strpos($line, 'Direct Link') !== false ||
                   strpos($line, 'looker-studio') !== false;
        });
        
        if (!empty($directLinkErrors)) {
            echo "⚠ Recent errors found in log:\n";
            foreach (array_slice($directLinkErrors, -5) as $error) {
                echo "  - " . trim($error) . "\n";
            }
        } else {
            echo "✓ No recent errors found in log\n";
        }
    } else {
        echo "✗ Log file tidak ditemukan\n";
    }
} catch (Exception $e) {
    echo "✗ Error checking log: " . $e->getMessage() . "\n";
}

// 8. Test apakah ada konflik route
echo "\n8. Testing Route Conflicts...\n";
try {
    $routesContent = file_get_contents('routes/web.php');
    $directLinkRoutes = substr_count($routesContent, 'create-direct-link');
    if ($directLinkRoutes === 1) {
        echo "✓ Tidak ada konflik route\n";
    } else {
        echo "✗ Kemungkinan ada konflik route (found " . $directLinkRoutes . " occurrences)\n";
    }
} catch (Exception $e) {
    echo "✗ Error checking route conflicts: " . $e->getMessage() . "\n";
}

echo "\n=== RINGKASAN DEBUG ===\n";
echo "Jika semua test di atas menunjukkan ✓, kemungkinan error terjadi karena:\n";
echo "1. User tidak login atau tidak memiliki role 'staff'\n";
echo "2. CSRF token expired atau tidak valid\n";
echo "3. Network connectivity issue\n";
echo "4. Browser JavaScript error\n";
echo "\nUntuk debug lebih lanjut, cek browser console dan network tab.\n";
echo "Test selesai!\n";
?>
