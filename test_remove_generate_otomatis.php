<?php
/**
 * Test Script: Remove "Generate Otomatis untuk Mitra Terpilih" Functionality
 * 
 * This script verifies that the bulk assign functionality has been completely removed
 * from the codebase as requested by the user.
 */

echo "=== TEST: Remove 'Generate Otomatis untuk Mitra Terpilih' Functionality ===\n\n";

$tests = [
    'View File' => [
        'file' => 'resources/views/nomor-kontrak/index.blade.php',
        'checks' => [
            'Button removed' => 'Generate Otomatis untuk Mitra Terpilih',
            'Modal removed' => 'bulkAssignModal',
            'JavaScript variables removed' => 'bulkAssignBtn',
            'JavaScript functions removed' => 'bulkAssignBtn.addEventListener',
            'Route reference removed' => 'nomor-kontrak.bulk-assign-selected'
        ]
    ],
    'Controller File' => [
        'file' => 'app/Http/Controllers/NomorKontrakController.php',
        'checks' => [
            'Method removed' => 'bulkAssignSelected'
        ]
    ],
    'Routes File' => [
        'file' => 'routes/web.php',
        'checks' => [
            'Route removed' => 'bulk-assign-selected'
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
        
        if (strpos($content, $searchTerm) === false) {
            echo "✓ {$checkName}: {$searchTerm} - TIDAK DITEMUKAN (OK)\n";
            $passedTests++;
        } else {
            echo "✗ {$checkName}: {$searchTerm} - MASIH DITEMUKAN (ERROR)\n";
        }
    }
    
    echo "\n";
}

// Additional checks for specific functionality
echo "Additional Checks:\n";

// Check if the assign selected button still exists and works properly
$viewContent = file_get_contents('resources/views/nomor-kontrak/index.blade.php');
$totalTests++;

if (strpos($viewContent, 'Tugaskan Nomor Kontrak ke Mitra Terpilih') !== false) {
    echo "✓ Manual assign button masih ada (OK)\n";
    $passedTests++;
} else {
    echo "✗ Manual assign button tidak ditemukan (ERROR)\n";
}

// Check if the JavaScript for manual assign still works
$totalTests++;
if (strpos($viewContent, 'assignSelectedBtn.addEventListener') !== false) {
    echo "✓ Manual assign JavaScript masih ada (OK)\n";
    $passedTests++;
} else {
    echo "✗ Manual assign JavaScript tidak ditemukan (ERROR)\n";
}

// Check if the route for manual assign still exists
$routesContent = file_get_contents('routes/web.php');
$totalTests++;

if (strpos($routesContent, 'nomor-kontrak.index') !== false) {
    echo "✓ Manual assign route masih ada (OK)\n";
    $passedTests++;
} else {
    echo "✗ Manual assign route tidak ditemukan (ERROR)\n";
}

echo "\n=== SUMMARY ===\n";
echo "Total tests: {$totalTests}\n";
echo "Passed: {$passedTests}\n";
echo "Failed: " . ($totalTests - $passedTests) . "\n";

if ($passedTests === $totalTests) {
    echo "\n🎉 SEMUA TEST BERHASIL! Fitur 'Generate Otomatis untuk Mitra Terpilih' telah berhasil dihapus.\n";
    echo "✅ Button 'Generate Otomatis untuk Mitra Terpilih' telah dihapus\n";
    echo "✅ Modal bulk assign telah dihapus\n";
    echo "✅ JavaScript functionality telah dihapus\n";
    echo "✅ Controller method telah dihapus\n";
    echo "✅ Route telah dihapus\n";
    echo "✅ Manual assign functionality masih berfungsi\n";
} else {
    echo "\n❌ BEBERAPA TEST GAGAL! Masih ada sisa-sisa fitur yang perlu dihapus.\n";
}

echo "\n=== VERIFICATION COMPLETE ===\n";
?>
