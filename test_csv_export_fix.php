<?php
/**
 * Test Script untuk Memperbaiki Export CSV
 * 
 * Script ini akan menguji dan memperbaiki masalah export CSV:
 * - Route conflicts
 * - Middleware issues
 * - API endpoint functionality
 * - CSV download
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

echo "=== TEST PERBAIKAN EXPORT CSV ===\n\n";

// 1. Test Environment
echo "1. Testing Environment...\n";
try {
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    echo "✓ Laravel environment loaded successfully\n";
} catch (Exception $e) {
    echo "✗ Failed to load Laravel environment: " . $e->getMessage() . "\n";
    exit(1);
}

// 2. Test Database Connection
echo "\n2. Testing Database Connection...\n";
try {
    DB::connection()->getPdo();
    echo "✓ Database connection successful\n";
} catch (Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

// 3. Test Route Registration
echo "\n3. Testing Route Registration...\n";
try {
    $router = app('router');
    $routes = $router->getRoutes();
    
    $apiExportRoute = null;
    $webExportRoute = null;
    
    foreach ($routes as $route) {
        if ($route->uri() === 'api/looker-studio/export') {
            $apiExportRoute = $route;
        }
        if ($route->uri() === 'looker-studio/export') {
            $webExportRoute = $route;
        }
    }
    
    if ($apiExportRoute) {
        echo "✓ API export route exists: " . $apiExportRoute->uri() . "\n";
        echo "  Methods: " . implode(', ', $apiExportRoute->methods()) . "\n";
        echo "  Middleware: " . implode(', ', $apiExportRoute->middleware()) . "\n";
    } else {
        echo "✗ API export route not found\n";
    }
    
    if ($webExportRoute) {
        echo "⚠ Web export route exists (this might cause conflicts): " . $webExportRoute->uri() . "\n";
    } else {
        echo "✓ No web export route found (good, no conflicts)\n";
    }
    
} catch (Exception $e) {
    echo "✗ Route test failed: " . $e->getMessage() . "\n";
}

// 4. Test API Controller
echo "\n4. Testing API Controller...\n";
try {
    $controller = new \App\Http\Controllers\Api\LookerStudioApiController();
    echo "✓ LookerStudioApiController loaded successfully\n";
    
    if (method_exists($controller, 'exportData')) {
        echo "✓ exportData method exists\n";
    } else {
        echo "✗ exportData method not found\n";
    }
    
    if (method_exists($controller, 'exportToCSV')) {
        echo "✓ exportToCSV method exists\n";
    } else {
        echo "✗ exportToCSV method not found\n";
    }
    
} catch (Exception $e) {
    echo "✗ Controller test failed: " . $e->getMessage() . "\n";
    exit(1);
}

// 5. Test Data Availability
echo "\n5. Testing Data Availability...\n";
try {
    $userCount = \App\Models\User::where('role', 'mitra')->count();
    $dokumenCount = \App\Models\Dokumen::count();
    $fotoCount = \App\Models\Foto::count();
    $reviewCount = \App\Models\Review::count();
    
    echo "✓ Mitra count: {$userCount}\n";
    echo "✓ Dokumen count: {$dokumenCount}\n";
    echo "✓ Foto count: {$fotoCount}\n";
    echo "✓ Review count: {$reviewCount}\n";
    
    if ($userCount > 0 || $dokumenCount > 0 || $fotoCount > 0 || $reviewCount > 0) {
        echo "✓ Data available for export\n";
    } else {
        echo "⚠ No data available for export (this is normal for empty database)\n";
    }
    
} catch (Exception $e) {
    echo "✗ Data availability test failed: " . $e->getMessage() . "\n";
}

// 6. Test CSV Export Directly
echo "\n6. Testing CSV Export Directly...\n";
try {
    $controller = new \App\Http\Controllers\Api\LookerStudioApiController();
    $request = new Request();
    $request->merge(['format' => 'csv', 'type' => 'all']);
    
    $response = $controller->exportData($request);
    
    if ($response->getStatusCode() === 200) {
        echo "✓ CSV export successful\n";
        echo "✓ Response status: 200\n";
        
        $headers = $response->headers->all();
        if (isset($headers['content-type'][0]) && strpos($headers['content-type'][0], 'text/csv') !== false) {
            echo "✓ Content-Type is text/csv\n";
        } else {
            echo "⚠ Content-Type: " . ($headers['content-type'][0] ?? 'Not set') . "\n";
        }
        
        if (isset($headers['content-disposition'][0]) && strpos($headers['content-disposition'][0], 'attachment') !== false) {
            echo "✓ Content-Disposition is attachment\n";
        } else {
            echo "⚠ Content-Disposition: " . ($headers['content-disposition'][0] ?? 'Not set') . "\n";
        }
        
        // Test response content
        $content = $response->getContent();
        if (!empty($content)) {
            echo "✓ Response has content\n";
            echo "  Content length: " . strlen($content) . " bytes\n";
            
            // Check if it looks like CSV
            if (strpos($content, ',') !== false || strpos($content, "\n") !== false) {
                echo "✓ Content appears to be CSV format\n";
            } else {
                echo "⚠ Content doesn't appear to be CSV format\n";
            }
        } else {
            echo "⚠ Response content is empty\n";
        }
        
    } else {
        echo "✗ CSV export failed with status: " . $response->getStatusCode() . "\n";
        if ($response->getStatusCode() === 404) {
            echo "  This indicates a routing issue\n";
        }
    }
    
} catch (Exception $e) {
    echo "✗ CSV export test failed: " . $e->getMessage() . "\n";
    echo "  Error type: " . get_class($e) . "\n";
    echo "  File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

// 7. Test URL Access
echo "\n7. Testing URL Access...\n";
try {
    $baseUrl = 'http://127.0.0.1:8000';
    $testUrl = $baseUrl . '/api/looker-studio/export?format=csv&type=all';
    
    echo "Testing URL: {$testUrl}\n";
    
    // Use cURL to test the endpoint
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $testUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "HTTP Status Code: {$httpCode}\n";
    
    if ($httpCode === 200) {
        echo "✓ URL is accessible\n";
    } elseif ($httpCode === 404) {
        echo "✗ URL returns 404 (route not found)\n";
    } elseif ($httpCode === 401) {
        echo "⚠ URL returns 401 (authentication required)\n";
    } elseif ($httpCode === 403) {
        echo "⚠ URL returns 403 (forbidden - role issue)\n";
    } else {
        echo "⚠ URL returns {$httpCode}\n";
    }
    
} catch (Exception $e) {
    echo "✗ URL test failed: " . $e->getMessage() . "\n";
}

// 8. Test Middleware
echo "\n8. Testing Middleware...\n";
try {
    $middleware = app('router')->getMiddleware();
    
    if (isset($middleware['role'])) {
        echo "✓ Role middleware is registered\n";
    } else {
        echo "✗ Role middleware is not registered\n";
    }
    
    if (isset($middleware['auth'])) {
        echo "✓ Auth middleware is registered\n";
    } else {
        echo "✗ Auth middleware is not registered\n";
    }
    
} catch (Exception $e) {
    echo "✗ Middleware test failed: " . $e->getMessage() . "\n";
}

// Summary
echo "\n=== TEST SUMMARY ===\n";
echo "CSV export functionality test completed.\n";
echo "Check the results above for any issues.\n\n";

echo "=== RECOMMENDATIONS ===\n";
echo "1. If route returns 404: Check route registration\n";
echo "2. If route returns 401: Authentication issue\n";
echo "3. If route returns 403: Role middleware issue\n";
echo "4. If CSV content is empty: Check data preparation\n";
echo "5. If Content-Type is wrong: Check response headers\n\n";

echo "=== MANUAL TESTING STEPS ===\n";
echo "1. Login as staff user\n";
echo "2. Navigate to Looker Studio dashboard\n";
echo "3. Click 'Export CSV' button\n";
echo "4. Check browser network tab for request/response\n";
echo "5. Verify file downloads correctly\n";
echo "6. Open CSV file and verify data content\n\n";

echo "=== EXPECTED BEHAVIOR ===\n";
echo "- CSV export should return HTTP 200\n";
echo "- Content-Type should be 'text/csv; charset=UTF-8'\n";
echo "- Content-Disposition should be 'attachment; filename=...'\n";
echo "- File should contain CSV data with proper encoding\n\n";

echo "Test completed at: " . date('Y-m-d H:i:s') . "\n";
?>
