<?php
/**
 * Simple CSV Export Test
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Http\Request;

echo "=== SIMPLE CSV EXPORT TEST ===\n\n";

// Load Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Test CSV Export
try {
    $controller = new \App\Http\Controllers\Api\LookerStudioApiController();
    $request = new Request();
    $request->merge(['format' => 'csv', 'type' => 'all']);
    
    $response = $controller->exportData($request);
    
    echo "Response Status: " . $response->getStatusCode() . "\n";
    
    $headers = $response->headers->all();
    echo "Content-Type: " . ($headers['content-type'][0] ?? 'Not set') . "\n";
    echo "Content-Disposition: " . ($headers['content-disposition'][0] ?? 'Not set') . "\n";
    
    $content = $response->getContent();
    echo "Content Length: " . strlen($content) . " bytes\n";
    
    if (!empty($content)) {
        echo "✓ CSV content generated successfully\n";
        echo "First 500 characters:\n";
        echo substr($content, 0, 500) . "...\n";
    } else {
        echo "✗ CSV content is empty\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== TEST COMPLETE ===\n";
?>
