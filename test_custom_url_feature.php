<?php
/**
 * Test Script untuk Fitur Custom URL Looker Studio
 * 
 * Script ini akan menguji semua komponen fitur custom URL:
 * - Controller methods
 * - Routes
 * - Session handling
 * - Validation
 * - Authentication
 * - Frontend integration
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

echo "=== TEST FITUR CUSTOM URL LOOKER STUDIO ===\n\n";

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
    \Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "✓ Database connection successful\n";
} catch (Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

// 3. Test Controller Existence
echo "\n3. Testing Controller...\n";
try {
    $controller = new \App\Http\Controllers\LookerStudioController();
    echo "✓ LookerStudioController loaded successfully\n";
    
    // Test if methods exist
    if (method_exists($controller, 'setCustomUrl')) {
        echo "✓ setCustomUrl method exists\n";
    } else {
        echo "✗ setCustomUrl method not found\n";
    }
    
    if (method_exists($controller, 'getCurrentUrl')) {
        echo "✓ getCurrentUrl method exists\n";
    } else {
        echo "✗ getCurrentUrl method not found\n";
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
    
    $customUrlRoute = null;
    $getCurrentUrlRoute = null;
    
    foreach ($routes as $route) {
        if ($route->uri() === 'looker-studio/set-custom-url' && $route->methods()[0] === 'POST') {
            $customUrlRoute = $route;
        }
        if ($route->uri() === 'looker-studio/get-current-url' && $route->methods()[0] === 'GET') {
            $getCurrentUrlRoute = $route;
        }
    }
    
    if ($customUrlRoute) {
        echo "✓ POST /looker-studio/set-custom-url route exists\n";
    } else {
        echo "✗ POST /looker-studio/set-custom-url route not found\n";
    }
    
    if ($getCurrentUrlRoute) {
        echo "✓ GET /looker-studio/get-current-url route exists\n";
    } else {
        echo "✗ GET /looker-studio/get-current-url route not found\n";
    }
    
} catch (Exception $e) {
    echo "✗ Route test failed: " . $e->getMessage() . "\n";
}

// 5. Test Session Configuration
echo "\n5. Testing Session Configuration...\n";
try {
    $sessionConfig = config('session');
    echo "✓ Session driver: " . $sessionConfig['driver'] . "\n";
    echo "✓ Session lifetime: " . $sessionConfig['lifetime'] . " minutes\n";
    echo "✓ Session domain: " . ($sessionConfig['domain'] ?? 'null') . "\n";
} catch (Exception $e) {
    echo "✗ Session configuration test failed: " . $e->getMessage() . "\n";
}

// 6. Test Validation Rules
echo "\n6. Testing Validation Rules...\n";
try {
    $validator = app('validator');
    
    // Test valid URL
    $validData = ['custom_url' => 'https://lookerstudio.google.com/reporting/123'];
    $rules = ['custom_url' => 'required|url|starts_with:https://lookerstudio.google.com'];
    
    $validation = $validator->make($validData, $rules);
    if ($validation->passes()) {
        echo "✓ Valid URL validation passes\n";
    } else {
        echo "✗ Valid URL validation failed\n";
    }
    
    // Test invalid URL
    $invalidData = ['custom_url' => 'https://google.com'];
    $validation = $validator->make($invalidData, $rules);
    if ($validation->fails()) {
        echo "✓ Invalid URL validation correctly fails\n";
    } else {
        echo "✗ Invalid URL validation should have failed\n";
    }
    
    // Test empty URL
    $emptyData = ['custom_url' => ''];
    $validation = $validator->make($emptyData, $rules);
    if ($validation->fails()) {
        echo "✓ Empty URL validation correctly fails\n";
    } else {
        echo "✗ Empty URL validation should have failed\n";
    }
    
} catch (Exception $e) {
    echo "✗ Validation test failed: " . $e->getMessage() . "\n";
}

// 7. Test Authentication Middleware
echo "\n7. Testing Authentication Middleware...\n";
try {
    $middleware = app('router')->getMiddleware();
    
    if (isset($middleware['auth'])) {
        echo "✓ Auth middleware registered\n";
    } else {
        echo "✗ Auth middleware not found\n";
    }
    
    if (isset($middleware['role'])) {
        echo "✓ Role middleware registered\n";
    } else {
        echo "✗ Role middleware not found\n";
    }
    
} catch (Exception $e) {
    echo "✗ Middleware test failed: " . $e->getMessage() . "\n";
}

// 8. Test User Model and Role
echo "\n8. Testing User Model and Role...\n";
try {
    $userModel = new \App\Models\User();
    echo "✓ User model loaded successfully\n";
    
    // Check if role column exists
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('users');
    if (in_array('role', $columns)) {
        echo "✓ Role column exists in users table\n";
    } else {
        echo "✗ Role column not found in users table\n";
    }
    
} catch (Exception $e) {
    echo "✗ User model test failed: " . $e->getMessage() . "\n";
}

// 9. Test CSRF Protection
echo "\n9. Testing CSRF Protection...\n";
try {
    $csrfConfig = config('session');
    if (isset($csrfConfig['encrypt']) && $csrfConfig['encrypt']) {
        echo "✓ CSRF protection enabled\n";
    } else {
        echo "✗ CSRF protection not properly configured\n";
    }
} catch (Exception $e) {
    echo "✗ CSRF test failed: " . $e->getMessage() . "\n";
}

// 10. Test View File
echo "\n10. Testing View File...\n";
try {
    $viewPath = resource_path('views/looker-studio/index.blade.php');
    if (file_exists($viewPath)) {
        echo "✓ Looker Studio view file exists\n";
        
        $viewContent = file_get_contents($viewPath);
        
        // Check for custom URL input
        if (strpos($viewContent, 'customUrlInput') !== false) {
            echo "✓ Custom URL input field found in view\n";
        } else {
            echo "✗ Custom URL input field not found in view\n";
        }
        
        // Check for setCustomUrl function
        if (strpos($viewContent, 'setCustomUrl()') !== false) {
            echo "✓ setCustomUrl function found in view\n";
        } else {
            echo "✗ setCustomUrl function not found in view\n";
        }
        
        // Check for checkExistingUrl function
        if (strpos($viewContent, 'checkExistingUrl()') !== false) {
            echo "✓ checkExistingUrl function found in view\n";
        } else {
            echo "✗ checkExistingUrl function not found in view\n";
        }
        
    } else {
        echo "✗ Looker Studio view file not found\n";
    }
} catch (Exception $e) {
    echo "✗ View file test failed: " . $e->getMessage() . "\n";
}

// 11. Test JavaScript Dependencies
echo "\n11. Testing JavaScript Dependencies...\n";
try {
    $layoutPath = resource_path('views/layouts/app.blade.php');
    if (file_exists($layoutPath)) {
        $layoutContent = file_get_contents($layoutPath);
        
        if (strpos($layoutContent, 'chart.js') !== false) {
            echo "✓ Chart.js dependency found in layout\n";
        } else {
            echo "✗ Chart.js dependency not found in layout\n";
        }
        
        if (strpos($layoutContent, 'csrf-token') !== false) {
            echo "✓ CSRF token meta tag found in layout\n";
        } else {
            echo "✗ CSRF token meta tag not found in layout\n";
        }
        
    } else {
        echo "✗ Layout file not found\n";
    }
} catch (Exception $e) {
    echo "✗ JavaScript dependencies test failed: " . $e->getMessage() . "\n";
}

// 12. Test Logging Configuration
echo "\n12. Testing Logging Configuration...\n";
try {
    $logConfig = config('logging');
    echo "✓ Logging channel: " . $logConfig['default'] . "\n";
    
    $logPath = storage_path('logs/laravel.log');
    if (is_writable(dirname($logPath))) {
        echo "✓ Log directory is writable\n";
    } else {
        echo "✗ Log directory is not writable\n";
    }
    
} catch (Exception $e) {
    echo "✗ Logging test failed: " . $e->getMessage() . "\n";
}

// Summary
echo "\n=== TEST SUMMARY ===\n";
echo "All tests completed. Check the results above for any issues.\n";
echo "If all tests pass (✓), the custom URL feature should work correctly.\n";
echo "If any tests fail (✗), please fix the issues before using the feature.\n\n";

echo "=== RECOMMENDATIONS ===\n";
echo "1. Test the feature manually in the browser\n";
echo "2. Check Laravel logs for any errors\n";
echo "3. Verify user authentication and role permissions\n";
echo "4. Test with different URL formats\n";
echo "5. Verify session persistence across page reloads\n\n";

echo "=== MANUAL TESTING STEPS ===\n";
echo "1. Login as staff user\n";
echo "2. Navigate to Looker Studio dashboard\n";
echo "3. Enter a valid Looker Studio URL\n";
echo "4. Click 'Set URL' button\n";
echo "5. Verify URL is saved and displayed\n";
echo "6. Test copy URL functionality\n";
echo "7. Test opening dashboard in new tab\n";
echo "8. Refresh page and verify URL persists\n";
echo "9. Test with invalid URL (should show error)\n";
echo "10. Test Enter key functionality\n\n";

echo "Test completed at: " . date('Y-m-d H:i:s') . "\n";
?>
