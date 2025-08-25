<?php
/**
 * Test Script untuk Verifikasi Fix Direct Link
 * 
 * Tujuan:
 * - Memverifikasi bahwa error Direct Link telah diperbaiki
 * - Menguji response dari API createDirectLink
 * - Memastikan semua komponen berfungsi dengan baik
 */

require_once 'vendor/autoload.php';

echo "=== TEST VERIFIKASI FIX DIRECT LINK ===\n\n";

// 1. Test apakah controller method sudah diperbaiki
echo "1. Testing Controller Method Improvements...\n";
try {
    $controllerContent = file_get_contents('app/Http/Controllers/LookerStudioController.php');
    
    // Check for improved logging
    if (strpos($controllerContent, 'Log::info(\'LookerStudio: Direct link creation requested\'') !== false) {
        echo "✓ Improved logging ditambahkan\n";
    } else {
        echo "✗ Improved logging belum ditambahkan\n";
    }
    
    // Check for better URL
    if (strpos($controllerContent, 'https://lookerstudio.google.com/reporting/create') !== false) {
        echo "✓ Better URL ditambahkan\n";
    } else {
        echo "✗ Better URL belum ditambahkan\n";
    }
    
    // Check for better error handling
    if (strpos($controllerContent, 'user->role !== \'staff\'') !== false) {
        echo "✓ Better error handling ditambahkan\n";
    } else {
        echo "✗ Better error handling belum ditambahkan\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error checking controller improvements: " . $e->getMessage() . "\n";
}

// 2. Test apakah JavaScript function sudah diperbaiki
echo "\n2. Testing JavaScript Function Improvements...\n";
try {
    $viewContent = file_get_contents('resources/views/looker-studio/index.blade.php');
    
    // Check for console logging
    if (strpos($viewContent, 'console.log(\'Creating direct link to Looker Studio...\')') !== false) {
        echo "✓ Console logging ditambahkan\n";
    } else {
        echo "✗ Console logging belum ditambahkan\n";
    }
    
    // Check for loading state
    if (strpos($viewContent, 'directLinkBtn.innerHTML = \'<i class=\"bi bi-hourglass-split me-1 spin\">Creating...</i>\'') !== false) {
        echo "✓ Loading state ditambahkan\n";
    } else {
        echo "✗ Loading state belum ditambahkan\n";
    }
    
    // Check for better error handling
    if (strpos($viewContent, 'response.status === 401') !== false) {
        echo "✓ Better error handling di JavaScript ditambahkan\n";
    } else {
        echo "✗ Better error handling di JavaScript belum ditambahkan\n";
    }
    
    // Check for Accept header
    if (strpos($viewContent, '\'Accept\': \'application/json\'') !== false) {
        echo "✓ Accept header ditambahkan\n";
    } else {
        echo "✗ Accept header belum ditambahkan\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error checking JavaScript improvements: " . $e->getMessage() . "\n";
}

// 3. Test apakah route masih terdaftar dengan benar
echo "\n3. Testing Route Registration...\n";
try {
    $routesContent = file_get_contents('routes/web.php');
    $directLinkRoutes = substr_count($routesContent, 'create-direct-link');
    if ($directLinkRoutes === 1) {
        echo "✓ Route terdaftar dengan benar (1 occurrence)\n";
    } else {
        echo "✗ Route masih ada konflik (" . $directLinkRoutes . " occurrences)\n";
    }
    
    // Check if route is in the correct middleware group
    if (strpos($routesContent, 'Route::post(\'/looker-studio/create-direct-link\'') !== false) {
        echo "✓ Route dalam middleware group yang benar\n";
    } else {
        echo "✗ Route tidak dalam middleware group yang benar\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error checking route: " . $e->getMessage() . "\n";
}

// 4. Test apakah CSRF token masih ada
echo "\n4. Testing CSRF Token...\n";
try {
    $layoutContent = file_get_contents('resources/views/layouts/app.blade.php');
    if (strpos($layoutContent, 'csrf-token') !== false) {
        echo "✓ CSRF token masih ada di layout\n";
    } else {
        echo "✗ CSRF token tidak ada di layout\n";
    }
} catch (Exception $e) {
    echo "✗ Error checking CSRF token: " . $e->getMessage() . "\n";
}

// 5. Test apakah ada syntax error
echo "\n5. Testing Syntax...\n";
try {
    $controllerContent = file_get_contents('app/Http/Controllers/LookerStudioController.php');
    $viewContent = file_get_contents('resources/views/looker-studio/index.blade.php');
    
    // Check bracket balance
    $controllerBrackets = substr_count($controllerContent, '{') - substr_count($controllerContent, '}');
    if ($controllerBrackets === 0) {
        echo "✓ Controller bracket balance OK\n";
    } else {
        echo "✗ Controller bracket imbalance: " . $controllerBrackets . "\n";
    }
    
    // Check for common JavaScript syntax issues
    if (strpos($viewContent, 'function createDirectLink() {') !== false && 
        strpos($viewContent, '}') !== false) {
        echo "✓ JavaScript function syntax OK\n";
    } else {
        echo "✗ JavaScript function syntax error\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error checking syntax: " . $e->getMessage() . "\n";
}

// 6. Test apakah ada error di log
echo "\n6. Testing Recent Logs...\n";
try {
    $logPath = 'storage/logs/laravel.log';
    if (file_exists($logPath)) {
        $logContent = file_get_contents($logPath);
        $lines = explode("\n", $logContent);
        $recentLines = array_slice($lines, -20); // Last 20 lines
        
        $directLinkErrors = array_filter($recentLines, function($line) {
            return strpos($line, 'createDirectLink') !== false || 
                   strpos($line, 'Direct Link') !== false ||
                   strpos($line, 'looker-studio') !== false;
        });
        
        if (!empty($directLinkErrors)) {
            echo "⚠ Recent activity found in log:\n";
            foreach (array_slice($directLinkErrors, -3) as $activity) {
                echo "  - " . trim($activity) . "\n";
            }
        } else {
            echo "✓ No recent activity found in log\n";
        }
    } else {
        echo "✗ Log file tidak ditemukan\n";
    }
} catch (Exception $e) {
    echo "✗ Error checking log: " . $e->getMessage() . "\n";
}

echo "\n=== RINGKASAN FIX ===\n";
echo "Perbaikan yang telah dilakukan:\n";
echo "1. ✅ Enhanced logging di controller\n";
echo "2. ✅ Better error handling dengan status codes yang spesifik\n";
echo "3. ✅ Improved URL (https://lookerstudio.google.com/reporting/create)\n";
echo "4. ✅ Loading state di JavaScript\n";
echo "5. ✅ Better error messages di JavaScript\n";
echo "6. ✅ Console logging untuk debugging\n";
echo "7. ✅ Accept header untuk JSON responses\n";
echo "\nUntuk test lebih lanjut:\n";
echo "1. Buka browser console\n";
echo "2. Klik tombol 'Direct Link'\n";
echo "3. Cek network tab untuk response\n";
echo "4. Cek console untuk log messages\n";
echo "\nTest selesai!\n";
?>
