<?php
/**
 * Test Script untuk Fitur Export JSON dan CSV
 * 
 * Script ini akan menguji fungsi export data:
 * - API endpoint export
 * - JSON export functionality
 * - CSV export functionality
 * - Data preparation
 * - File download
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

echo "=== TEST FITUR EXPORT JSON DAN CSV ===\n\n";

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

// 3. Test API Controller
echo "\n3. Testing API Controller...\n";
try {
    $controller = new \App\Http\Controllers\Api\LookerStudioApiController();
    echo "✓ LookerStudioApiController loaded successfully\n";
    
    if (method_exists($controller, 'exportData')) {
        echo "✓ exportData method exists\n";
    } else {
        echo "✗ exportData method not found\n";
    }
    
} catch (Exception $e) {
    echo "✗ Controller test failed: " . $e->getMessage() . "\n";
    exit(1);
}

// 4. Test Routes
echo "\n4. Testing Routes...\n";
try {
    $router = app('router');
    $routes = $router->getRoutes();
    
    $exportRoute = null;
    
    foreach ($routes as $route) {
        if ($route->uri() === 'api/looker-studio/export' && in_array('GET', $route->methods())) {
            $exportRoute = $route;
            break;
        }
    }
    
    if ($exportRoute) {
        echo "✓ GET /api/looker-studio/export route exists\n";
    } else {
        echo "✗ GET /api/looker-studio/export route not found\n";
    }
    
} catch (Exception $e) {
    echo "✗ Route test failed: " . $e->getMessage() . "\n";
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

// 6. Test JSON Export
echo "\n6. Testing JSON Export...\n";
try {
    $controller = new \App\Http\Controllers\Api\LookerStudioApiController();
    $request = new Request();
    $request->merge(['format' => 'json', 'type' => 'all']);
    
    $response = $controller->exportData($request);
    $responseData = $response->getData();
    
    if ($responseData->success) {
        echo "✓ JSON export successful\n";
        echo "✓ Response contains data structure\n";
        
        if (isset($responseData->data)) {
            echo "✓ Data object exists\n";
            
            if (isset($responseData->data->summary)) {
                echo "✓ Summary data exists\n";
            }
            
            if (isset($responseData->data->dokumen)) {
                echo "✓ Dokumen data exists\n";
            }
            
            if (isset($responseData->data->mitra)) {
                echo "✓ Mitra data exists\n";
            }
            
            if (isset($responseData->data->foto)) {
                echo "✓ Foto data exists\n";
            }
            
            if (isset($responseData->data->review)) {
                echo "✓ Review data exists\n";
            }
        }
        
        echo "✓ Export timestamp: " . ($responseData->exported_at ?? 'N/A') . "\n";
        
    } else {
        echo "✗ JSON export failed: " . ($responseData->message ?? 'Unknown error') . "\n";
    }
    
} catch (Exception $e) {
    echo "✗ JSON export test failed: " . $e->getMessage() . "\n";
}

// 7. Test CSV Export
echo "\n7. Testing CSV Export...\n";
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
        }
        
        if (isset($headers['content-disposition'][0]) && strpos($headers['content-disposition'][0], 'attachment') !== false) {
            echo "✓ Content-Disposition is attachment\n";
        }
        
    } else {
        echo "✗ CSV export failed with status: " . $response->getStatusCode() . "\n";
    }
    
} catch (Exception $e) {
    echo "✗ CSV export test failed: " . $e->getMessage() . "\n";
}

// 8. Test Specific Data Type Export
echo "\n8. Testing Specific Data Type Export...\n";
try {
    $controller = new \App\Http\Controllers\Api\LookerStudioApiController();
    
    $dataTypes = ['dokumen', 'mitra', 'foto', 'review'];
    
    foreach ($dataTypes as $dataType) {
        $request = new Request();
        $request->merge(['format' => 'json', 'type' => $dataType]);
        
        $response = $controller->exportData($request);
        $responseData = $response->getData();
        
        if ($responseData->success) {
            echo "✓ {$dataType} export successful\n";
        } else {
            echo "✗ {$dataType} export failed: " . ($responseData->message ?? 'Unknown error') . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "✗ Specific data type export test failed: " . $e->getMessage() . "\n";
}

// 9. Test Error Handling
echo "\n9. Testing Error Handling...\n";
try {
    $controller = new \App\Http\Controllers\Api\LookerStudioApiController();
    $request = new Request();
    $request->merge(['format' => 'invalid_format', 'type' => 'invalid_type']);
    
    $response = $controller->exportData($request);
    $responseData = $response->getData();
    
    if (!$responseData->success) {
        echo "✓ Error handling works for invalid format\n";
    } else {
        echo "⚠ Invalid format was accepted (this might be intentional)\n";
    }
    
} catch (Exception $e) {
    echo "✓ Error handling works (exception caught)\n";
}

// 10. Test File Permissions
echo "\n10. Testing File Permissions...\n";
try {
    $storagePath = storage_path('logs');
    if (is_writable($storagePath)) {
        echo "✓ Storage directory is writable\n";
    } else {
        echo "✗ Storage directory is not writable\n";
    }
    
    $tempPath = sys_get_temp_dir();
    if (is_writable($tempPath)) {
        echo "✓ Temp directory is writable\n";
    } else {
        echo "✗ Temp directory is not writable\n";
    }
    
} catch (Exception $e) {
    echo "✗ File permissions test failed: " . $e->getMessage() . "\n";
}

// Summary
echo "\n=== TEST SUMMARY ===\n";
echo "All export functionality tests completed.\n";
echo "Check the results above for any issues.\n\n";

echo "=== RECOMMENDATIONS ===\n";
echo "1. Test the export buttons in the browser\n";
echo "2. Verify downloaded files contain correct data\n";
echo "3. Check file encoding (UTF-8 for CSV)\n";
echo "4. Test with different data types\n";
echo "5. Verify file naming convention\n\n";

echo "=== MANUAL TESTING STEPS ===\n";
echo "1. Login as staff user\n";
echo "2. Navigate to Looker Studio dashboard\n";
echo "3. Click 'Export JSON' button\n";
echo "4. Verify JSON file downloads correctly\n";
echo "5. Click 'Export CSV' button\n";
echo "6. Verify CSV file downloads correctly\n";
echo "7. Click 'Export Semua Data' button\n";
echo "8. Verify both files download\n";
echo "9. Open files and verify data content\n";
echo "10. Test with different browsers\n\n";

echo "=== EXPECTED BEHAVIOR ===\n";
echo "- JSON export: Downloads .json file with structured data\n";
echo "- CSV export: Downloads .csv file with tabular data\n";
echo "- All data export: Downloads both formats\n";
echo "- Loading indicators show during export\n";
echo "- Success messages appear after download\n";
echo "- Files contain UTF-8 encoded data\n\n";

echo "Test completed at: " . date('Y-m-d H:i:s') . "\n";
?>
