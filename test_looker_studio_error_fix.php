<?php
/**
 * Test Looker Studio Error Handling Solution
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Http\Request;

echo "=== LOOKER STUDIO ERROR HANDLING TEST ===\n\n";

// Load Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Test error handling
try {
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
    echo "✗ Error: " . $e->getMessage() . "\n";
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
    
} catch (Exception $e) {
    echo "✗ Error generating alternative URL: " . $e->getMessage() . "\n";
}

// Test simplified URL generation
try {
    echo "\n=== TESTING SIMPLIFIED URL GENERATION ===\n";
    
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('createLookerStudioUrl');
    $method->setAccessible(true);
    
    $data = ['test' => 'data'];
    $url = $method->invoke($controller, $data);
    
    echo "✓ Simplified URL generated: " . $url . "\n";
    
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
    
} catch (Exception $e) {
    echo "✗ Error generating simplified URL: " . $e->getMessage() . "\n";
}

echo "\n=== TEST COMPLETE ===\n";
?>
