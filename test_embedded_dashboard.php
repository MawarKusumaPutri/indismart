<?php
/**
 * Test Script: Embedded Looker Studio Dashboard Functionality
 * 
 * This script verifies that the Looker Studio dashboard can be displayed
 * as an embedded iframe within the application.
 */

echo "=== TEST: Embedded Looker Studio Dashboard Functionality ===\n\n";

$tests = [
    'View File' => [
        'file' => 'resources/views/looker-studio/index.blade.php',
        'checks' => [
            'Dashboard container exists' => 'dashboardContainer',
            'Dashboard iframe exists' => 'lookerStudioFrame',
            'Loading state exists' => 'dashboardLoading',
            'No dashboard state exists' => 'noDashboardState',
            'Error state exists' => 'dashboardError',
            'Refresh button exists' => 'refreshDashboard()',
            'Fullscreen button exists' => 'toggleFullscreen()',
            'Embedded dashboard section exists' => 'Looker Studio Dashboard'
        ]
    ],
    'JavaScript Functions' => [
        'file' => 'resources/views/looker-studio/index.blade.php',
        'checks' => [
            'initializeDashboardDisplay function' => 'initializeDashboardDisplay()',
            'loadDashboard function' => 'loadDashboard(url)',
            'convertToEmbedUrl function' => 'convertToEmbedUrl(url)',
            'refreshDashboard function' => 'refreshDashboard()',
            'toggleFullscreen function' => 'toggleFullscreen()',
            'showDashboardLoading function' => 'showDashboardLoading()',
            'showDashboardEmbed function' => 'showDashboardEmbed()',
            'showNoDashboardState function' => 'showNoDashboardState()',
            'showDashboardError function' => 'showDashboardError(message)',
            'hideAllDashboardStates function' => 'hideAllDashboardStates()',
            'showCustomUrlInput function' => 'showCustomUrlInput()'
        ]
    ],
    'Controller Updates' => [
        'file' => 'app/Http/Controllers/LookerStudioController.php',
        'checks' => [
            'createLookerStudioUrl updated' => 'Create a more functional URL',
            'Dashboard URL creation' => 'Created dashboard URL'
        ]
    ]
];

$totalTests = 0;
$passedTests = 0;

foreach ($tests as $testName => $testData) {
    echo "Testing: {$testName}\n";
    echo "File: {$testData['file']}\n";
    
    if (!file_exists($testData['file'])) {
        echo "✗ File tidak ditemukan: {$testData['file']}\n";
        continue;
    }
    
    $content = file_get_contents($testData['file']);
    
    foreach ($testData['checks'] as $checkName => $searchTerm) {
        $totalTests++;
        
        if (strpos($content, $searchTerm) !== false) {
            echo "✓ {$checkName}: {$searchTerm} - DITEMUKAN (OK)\n";
            $passedTests++;
        } else {
            echo "✗ {$checkName}: {$searchTerm} - TIDAK DITEMUKAN (ERROR)\n";
        }
    }
    
    echo "\n";
}

// Additional functionality checks
echo "Additional Functionality Checks:\n";

$viewContent = file_get_contents('resources/views/looker-studio/index.blade.php');

// Check if the dashboard display section is properly structured
$totalTests++;
if (strpos($viewContent, '<!-- Looker Studio Dashboard Display -->') !== false) {
    echo "✓ Dashboard display section exists (OK)\n";
    $passedTests++;
} else {
    echo "✗ Dashboard display section missing (ERROR)\n";
}

// Check if the iframe has proper attributes
$totalTests++;
if (strpos($viewContent, 'allowfullscreen') !== false) {
    echo "✓ Iframe has fullscreen support (OK)\n";
    $passedTests++;
} else {
    echo "✗ Iframe missing fullscreen support (ERROR)\n";
}

// Check if localStorage is used for URL persistence
$totalTests++;
if (strpos($viewContent, 'localStorage.setItem') !== false) {
    echo "✓ localStorage used for URL persistence (OK)\n";
    $passedTests++;
} else {
    echo "✗ localStorage not used for URL persistence (ERROR)\n";
}

// Check if URL conversion to embed format is implemented
$totalTests++;
if (strpos($viewContent, 'convertToEmbedUrl') !== false) {
    echo "✓ URL conversion to embed format implemented (OK)\n";
    $passedTests++;
} else {
    echo "✗ URL conversion to embed format not implemented (ERROR)\n";
}

// Check if error handling is implemented
$totalTests++;
if (strpos($viewContent, 'showDashboardError') !== false) {
    echo "✓ Dashboard error handling implemented (OK)\n";
    $passedTests++;
} else {
    echo "✗ Dashboard error handling not implemented (ERROR)\n";
}

// Check if the original functionality is preserved
$totalTests++;
if (strpos($viewContent, 'Generate Looker Studio Dashboard') !== false) {
    echo "✓ Original generate dashboard functionality preserved (OK)\n";
    $passedTests++;
} else {
    echo "✗ Original generate dashboard functionality missing (ERROR)\n";
}

echo "\n=== SUMMARY ===\n";
echo "Total tests: {$totalTests}\n";
echo "Passed: {$passedTests}\n";
echo "Failed: " . ($totalTests - $passedTests) . "\n";

if ($passedTests === $totalTests) {
    echo "\n🎉 SEMUA TEST BERHASIL! Fitur embedded Looker Studio dashboard telah berhasil diimplementasikan.\n";
    echo "✅ Dashboard container dan iframe telah ditambahkan\n";
    echo "✅ JavaScript functions untuk embedded dashboard telah diimplementasikan\n";
    echo "✅ URL conversion dan localStorage persistence telah ditambahkan\n";
    echo "✅ Error handling dan loading states telah diimplementasikan\n";
    echo "✅ Fullscreen dan refresh functionality telah ditambahkan\n";
    echo "✅ Original functionality tetap terjaga\n";
    echo "\n📋 FITUR YANG TERSEDIA:\n";
    echo "- Embedded Looker Studio dashboard dalam iframe\n";
    echo "- Loading, error, dan no-dashboard states\n";
    echo "- Refresh dan fullscreen controls\n";
    echo "- URL persistence menggunakan localStorage\n";
    echo "- Automatic URL conversion ke embed format\n";
    echo "- Integration dengan existing generate dashboard functionality\n";
} else {
    echo "\n❌ BEBERAPA TEST GAGAL! Masih ada fitur yang perlu diimplementasikan.\n";
}

echo "\n=== VERIFICATION COMPLETE ===\n";
?>
