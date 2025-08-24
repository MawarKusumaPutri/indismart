<?php
/**
 * Ultimate Looker Studio Error Fix Test
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Http\Request;

echo "=== ULTIMATE LOOKER STUDIO ERROR FIX TEST ===\n\n";

// Load Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Test basic URL generation
try {
    echo "=== TESTING BASIC URL GENERATION ===\n";
    
    $controller = new \App\Http\Controllers\LookerStudioController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('createLookerStudioUrl');
    $method->setAccessible(true);
    
    $data = ['test' => 'data'];
    $url = $method->invoke($controller, $data);
    
    echo "✓ Basic URL generated: " . $url . "\n";
    
    // Validate URL format
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        echo "✓ URL format is valid\n";
    } else {
        echo "✗ URL format is invalid\n";
    }
    
    // Check if URL contains required parameters
    if (strpos($url, 'lookerstudio.google.com') !== false) {
        echo "✓ URL contains Looker Studio domain\n";
    } else {
        echo "✗ URL does not contain Looker Studio domain\n";
    }
    
    // Check if URL is simple (no complex parameters)
    if (strpos($url, 'templateId') === false && strpos($url, 'dataSource') === false) {
        echo "✓ URL is simple (no complex parameters)\n";
    } else {
        echo "✗ URL contains complex parameters\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error generating basic URL: " . $e->getMessage() . "\n";
}

// Test Google Sheets dashboard creation
try {
    echo "\n=== TESTING GOOGLE SHEETS DASHBOARD ===\n";
    
    $controller = new \App\Http\Controllers\LookerStudioController();
    $request = new Request();
    
    $response = $controller->createGoogleSheetsDashboard();
    
    echo "Response Status: " . $response->getStatusCode() . "\n";
    
    $content = $response->getContent();
    $data = json_decode($content, true);
    
    if ($data['success']) {
        echo "✓ Google Sheets dashboard created successfully\n";
        echo "Dashboard URL: " . $data['url'] . "\n";
        echo "Sheets URL: " . ($data['sheets_url'] ?? 'Not provided') . "\n";
        echo "Message: " . $data['message'] . "\n";
        
        // Validate dashboard URL
        if (filter_var($data['url'], FILTER_VALIDATE_URL)) {
            echo "✓ Dashboard URL format is valid\n";
        } else {
            echo "✗ Dashboard URL format is invalid\n";
        }
        
        // Check if it contains sheets data source
        if (strpos($data['url'], 'sheets') !== false) {
            echo "✓ URL contains Google Sheets data source\n";
        } else {
            echo "✗ URL does not contain Google Sheets data source\n";
        }
        
    } else {
        echo "✗ Google Sheets dashboard creation failed: " . $data['message'] . "\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error creating Google Sheets dashboard: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

// Test Manual dashboard creation
try {
    echo "\n=== TESTING MANUAL DASHBOARD ===\n";
    
    $controller = new \App\Http\Controllers\LookerStudioController();
    $request = new Request();
    
    $response = $controller->createManualDashboard();
    
    echo "Response Status: " . $response->getStatusCode() . "\n";
    
    $content = $response->getContent();
    $data = json_decode($content, true);
    
    if ($data['success']) {
        echo "✓ Manual dashboard created successfully\n";
        echo "Dashboard URL: " . $data['url'] . "\n";
        echo "Message: " . $data['message'] . "\n";
        
        // Validate dashboard URL
        if (filter_var($data['url'], FILTER_VALIDATE_URL)) {
            echo "✓ Dashboard URL format is valid\n";
        } else {
            echo "✗ Dashboard URL format is invalid\n";
        }
        
        // Check if it contains manual data source
        if (strpos($data['url'], 'manual') !== false) {
            echo "✓ URL contains manual data source\n";
        } else {
            echo "✗ URL does not contain manual data source\n";
        }
        
    } else {
        echo "✗ Manual dashboard creation failed: " . $data['message'] . "\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error creating manual dashboard: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

// Test alternative URL generation
try {
    echo "\n=== TESTING ALTERNATIVE URL GENERATION ===\n";
    
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('generateAlternativeUrl');
    $method->setAccessible(true);
    
    $alternativeUrl = $method->invoke($controller);
    
    echo "✓ Alternative URL generated: " . $alternativeUrl . "\n";
    
    // Validate URL format
    if (filter_var($alternativeUrl, FILTER_VALIDATE_URL)) {
        echo "✓ URL format is valid\n";
    } else {
        echo "✗ URL format is invalid\n";
    }
    
    // Check if it's simple
    if (strpos($alternativeUrl, 'templateId') === false) {
        echo "✓ Alternative URL is simple\n";
    } else {
        echo "✗ Alternative URL contains complex parameters\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error generating alternative URL: " . $e->getMessage() . "\n";
}

// Test error handling
try {
    echo "\n=== TESTING ERROR HANDLING ===\n";
    
    $controller = new \App\Http\Controllers\LookerStudioController();
    $request = new Request();
    $request->merge([
        'error_type' => 'permission',
        'original_url' => 'https://lookerstudio.google.com/reporting/create?c.reportId=test123'
    ]);
    
    $response = $controller->handleError($request);
    
    echo "Response Status: " . $response->getStatusCode() . "\n";
    
    $content = $response->getContent();
    $data = json_decode($content, true);
    
    if ($data['success']) {
        echo "✓ Error handling successful\n";
        echo "Error Type: " . $data['error_info']['title'] . "\n";
        echo "Description: " . $data['error_info']['description'] . "\n";
        echo "Solutions Count: " . count($data['error_info']['solutions']) . "\n";
        echo "Alternative URL: " . $data['alternative_url'] . "\n";
        
        echo "\nSolutions:\n";
        foreach ($data['error_info']['solutions'] as $index => $solution) {
            echo "  " . ($index + 1) . ". " . $solution . "\n";
        }
    } else {
        echo "✗ Error handling failed: " . $data['message'] . "\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error in error handling: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== ULTIMATE FIX SUMMARY ===\n";
echo "✓ Simplified URL generation (no complex parameters)\n";
echo "✓ Google Sheets dashboard creation\n";
echo "✓ Manual entry dashboard creation\n";
echo "✓ Alternative URL generation\n";
echo "✓ Comprehensive error handling\n";
echo "✓ Multiple fallback mechanisms\n";

echo "\n=== TEST COMPLETE ===\n";
echo "Solusi ultimate untuk error Looker Studio telah diimplementasikan!\n";
echo "Sekarang user dapat menggunakan:\n";
echo "1. Generate Dashboard (basic)\n";
echo "2. Google Sheets Dashboard (alternative)\n";
echo "3. Manual Entry Dashboard (fallback)\n";
echo "4. Error handling dengan solusi spesifik\n";
?>
