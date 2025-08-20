<?php
/**
 * API Testing Script for Looker Studio Integration
 * 
 * This script tests all API endpoints for the Looker Studio integration
 * Run this script to verify that all endpoints are working correctly
 */

// Configuration
$baseUrl = 'http://localhost:8000'; // Change this to your domain
$endpoints = [
    'dashboard-overview' => '/api/looker-studio/dashboard-overview',
    'mitra-analytics' => '/api/looker-studio/mitra-analytics',
    'proyek-analytics' => '/api/looker-studio/proyek-analytics',
    'performance-metrics' => '/api/looker-studio/performance-metrics',
    'trends-monthly' => '/api/looker-studio/trends-monthly',
    'export-complete' => '/api/looker-studio/export-complete'
];

// Test function
function testEndpoint($url, $endpointName) {
    echo "\n🔍 Testing: {$endpointName}\n";
    echo "URL: {$url}\n";
    echo str_repeat("-", 50) . "\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Content-Type: application/json'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "❌ cURL Error: {$error}\n";
        return false;
    }
    
    echo "📊 HTTP Status: {$httpCode}\n";
    
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        if ($data) {
            if (isset($data['success']) && $data['success']) {
                echo "✅ Success: {$data['message']}\n";
                
                // Show data structure
                if (isset($data['data'])) {
                    echo "📋 Data Structure:\n";
                    printDataStructure($data['data'], 1);
                }
            } else {
                echo "❌ API Error: " . ($data['message'] ?? 'Unknown error') . "\n";
            }
        } else {
            echo "❌ Invalid JSON Response\n";
            echo "Response: {$response}\n";
        }
    } else {
        echo "❌ HTTP Error: {$httpCode}\n";
        echo "Response: {$response}\n";
    }
    
    return $httpCode === 200;
}

// Helper function to print data structure
function printDataStructure($data, $level = 0) {
    $indent = str_repeat("  ", $level);
    
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (count($value) > 0 && is_array($value[0])) {
                    echo "{$indent}📊 {$key}: Array (" . count($value) . " items)\n";
                    if ($level < 2) { // Limit nesting
                        printDataStructure($value[0], $level + 1);
                    }
                } else {
                    echo "{$indent}📊 {$key}: Array\n";
                    printDataStructure($value, $level + 1);
                }
            } else {
                $type = gettype($value);
                $preview = is_string($value) && strlen($value) > 50 ? substr($value, 0, 50) . '...' : $value;
                echo "{$indent}📄 {$key}: {$type} = {$preview}\n";
            }
        }
    }
}

// Main test execution
echo "🚀 Starting API Tests for Looker Studio Integration\n";
echo "Base URL: {$baseUrl}\n";
echo str_repeat("=", 60) . "\n";

$successCount = 0;
$totalCount = count($endpoints);

foreach ($endpoints as $name => $endpoint) {
    $url = $baseUrl . $endpoint;
    if (testEndpoint($url, $name)) {
        $successCount++;
    }
    echo "\n";
}

// Summary
echo str_repeat("=", 60) . "\n";
echo "📈 Test Summary:\n";
echo "✅ Successful: {$successCount}/{$totalCount}\n";
echo "❌ Failed: " . ($totalCount - $successCount) . "/{$totalCount}\n";

if ($successCount === $totalCount) {
    echo "\n🎉 All API endpoints are working correctly!\n";
    echo "You can now use these endpoints in Looker Studio.\n";
} else {
    echo "\n⚠️  Some endpoints failed. Please check your configuration.\n";
}

echo "\n📚 Next Steps:\n";
echo "1. Copy the working endpoint URLs\n";
echo "2. Use them in Google Looker Studio as data sources\n";
echo "3. Create your dashboard visualizations\n";
echo "4. Embed the dashboard in your application\n";

// Example usage
echo "\n💡 Example Usage in Looker Studio:\n";
echo "1. Go to https://lookerstudio.google.com\n";
echo "2. Create → Data source → URL\n";
echo "3. Enter: {$baseUrl}/api/looker-studio/dashboard-overview\n";
echo "4. Connect and start building your dashboard!\n";
?>
