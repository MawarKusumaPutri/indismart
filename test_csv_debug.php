<?php
/**
 * Debug Script untuk Export CSV
 * 
 * Script ini akan debug masalah export CSV step by step
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

echo "=== DEBUG EXPORT CSV ===\n\n";

// 1. Load Laravel
echo "1. Loading Laravel...\n";
try {
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    echo "✓ Laravel loaded\n";
} catch (Exception $e) {
    echo "✗ Laravel failed: " . $e->getMessage() . "\n";
    exit(1);
}

// 2. Test Database
echo "\n2. Testing Database...\n";
try {
    DB::connection()->getPdo();
    echo "✓ Database connected\n";
    
    $userCount = \App\Models\User::where('role', 'mitra')->count();
    $dokumenCount = \App\Models\Dokumen::count();
    echo "✓ Users: {$userCount}, Dokumen: {$dokumenCount}\n";
} catch (Exception $e) {
    echo "✗ Database failed: " . $e->getMessage() . "\n";
    exit(1);
}

// 3. Test Controller
echo "\n3. Testing Controller...\n";
try {
    $controller = new \App\Http\Controllers\Api\LookerStudioApiController();
    echo "✓ Controller created\n";
    
    // Test data preparation
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('prepareComprehensiveExportData');
    $method->setAccessible(true);
    
    $data = $method->invoke($controller, 'all');
    echo "✓ Data preparation successful\n";
    
    // Check data structure
    if (isset($data['summary'])) {
        echo "✓ Summary data exists\n";
        print_r($data['summary']);
    }
    
    if (isset($data['dokumen']) && is_array($data['dokumen'])) {
        echo "✓ Dokumen data exists: " . count($data['dokumen']) . " records\n";
    }
    
    if (isset($data['mitra']) && is_array($data['mitra'])) {
        echo "✓ Mitra data exists: " . count($data['mitra']) . " records\n";
    }
    
} catch (Exception $e) {
    echo "✗ Controller test failed: " . $e->getMessage() . "\n";
    echo "  File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}

// 4. Test CSV Generation
echo "\n4. Testing CSV Generation...\n";
try {
    // Create a simple CSV string for testing
    $csvData = "id,name,email\n";
    $csvData .= "1,Test User,test@example.com\n";
    $csvData .= "2,Another User,another@example.com\n";
    
    $filename = "test_export_" . date('Y-m-d_H-i-s') . '.csv';
    $filepath = storage_path('app/' . $filename);
    
    file_put_contents($filepath, $csvData);
    echo "✓ Test CSV file created: {$filepath}\n";
    
    if (file_exists($filepath)) {
        $content = file_get_contents($filepath);
        echo "✓ File content length: " . strlen($content) . " bytes\n";
        echo "✓ File content preview:\n";
        echo substr($content, 0, 200) . "...\n";
    }
    
} catch (Exception $e) {
    echo "✗ CSV generation failed: " . $e->getMessage() . "\n";
}

// 5. Test Route Registration
echo "\n5. Testing Route Registration...\n";
try {
    $router = app('router');
    $routes = $router->getRoutes();
    
    $foundRoutes = [];
    foreach ($routes as $route) {
        if (strpos($route->uri(), 'looker-studio') !== false) {
            $foundRoutes[] = $route->uri();
        }
    }
    
    echo "✓ Found " . count($foundRoutes) . " looker-studio routes:\n";
    foreach ($foundRoutes as $route) {
        echo "  - {$route}\n";
    }
    
    // Check specific export route
    $exportRoute = null;
    foreach ($routes as $route) {
        if ($route->uri() === 'api/looker-studio/export') {
            $exportRoute = $route;
            break;
        }
    }
    
    if ($exportRoute) {
        echo "✓ Export route found\n";
        echo "  Methods: " . implode(', ', $exportRoute->methods()) . "\n";
        echo "  Middleware: " . implode(', ', $exportRoute->middleware()) . "\n";
    } else {
        echo "✗ Export route not found\n";
    }
    
} catch (Exception $e) {
    echo "✗ Route test failed: " . $e->getMessage() . "\n";
}

// 6. Test Simple Export
echo "\n6. Testing Simple Export...\n";
try {
    $controller = new \App\Http\Controllers\Api\LookerStudioApiController();
    $request = new Request();
    $request->merge(['format' => 'json', 'type' => 'all']);
    
    $response = $controller->exportData($request);
    $responseData = $response->getData();
    
    if ($responseData->success) {
        echo "✓ JSON export successful\n";
        echo "✓ Data structure exists\n";
        
        if (isset($responseData->data->summary)) {
            echo "✓ Summary in response\n";
        }
        
        if (isset($responseData->data->dokumen)) {
            echo "✓ Dokumen in response: " . count($responseData->data->dokumen) . " records\n";
        }
    } else {
        echo "✗ JSON export failed: " . ($responseData->message ?? 'Unknown error') . "\n";
    }
    
} catch (Exception $e) {
    echo "✗ Simple export failed: " . $e->getMessage() . "\n";
    echo "  File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== DEBUG COMPLETE ===\n";
echo "Check the results above for any issues.\n";
echo "If data preparation works but CSV doesn't, the issue is likely in the streaming response.\n\n";
?>
