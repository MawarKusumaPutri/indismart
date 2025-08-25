<?php
/**
 * Test Aktivitas Mitra Terbaru
 */

require_once __DIR__ . '/vendor/autoload.php';

echo "=== TEST AKTIVITAS MITRA TERBARU ===\n\n";

// Load Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "=== TESTING MITRA ACTIVITY DATA ===\n";
    
    $controller = new \App\Http\Controllers\LookerStudioController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('getMitraActivity');
    $method->setAccessible(true);
    
    $mitraActivity = $method->invoke($controller);
    
    echo "✓ Mitra activity data retrieved successfully\n";
    echo "Total mitra found: " . $mitraActivity->count() . "\n\n";
    
    if ($mitraActivity->count() > 0) {
        echo "=== MITRA ACTIVITY DETAILS ===\n";
        foreach ($mitraActivity as $index => $mitra) {
            echo ($index + 1) . ". " . ($mitra->name ?? 'Unknown') . "\n";
            echo "   - Dokumen: " . ($mitra->dokumen_count ?? 0) . "\n";
            echo "   - Foto: " . ($mitra->fotos_count ?? 0) . "\n";
            echo "   - Status: " . (($mitra->dokumen_count ?? 0) > 0 ? 'Aktif' : 'Tidak Aktif') . "\n";
            echo "\n";
        }
    } else {
        echo "No mitra activity data found\n";
    }
    
    // Test recent activities
    echo "=== TESTING RECENT ACTIVITIES ===\n";
    
    $recentMethod = $reflection->getMethod('getRecentActivities');
    $recentMethod->setAccessible(true);
    
    $recentActivities = $recentMethod->invoke($controller);
    
    echo "✓ Recent activities data retrieved successfully\n";
    echo "Total activities found: " . $recentActivities->count() . "\n\n";
    
    if ($recentActivities->count() > 0) {
        echo "=== RECENT ACTIVITIES DETAILS ===\n";
        foreach ($recentActivities as $index => $activity) {
            echo ($index + 1) . ". " . ($activity['title'] ?? 'Unknown Activity') . "\n";
            echo "   - User: " . ($activity['user'] ?? 'Unknown') . "\n";
            echo "   - Time: " . ($activity['time'] ?? 'Unknown') . "\n";
            echo "   - Type: " . ($activity['type'] ?? 'Unknown') . "\n";
            echo "\n";
        }
    } else {
        echo "No recent activities found\n";
    }
    
    // Test dashboard data
    echo "=== TESTING DASHBOARD DATA ===\n";
    
    $dashboardMethod = $reflection->getMethod('getDashboardData');
    $dashboardMethod->setAccessible(true);
    
    $dashboardData = $dashboardMethod->invoke($controller);
    
    echo "✓ Dashboard data retrieved successfully\n";
    echo "Summary data:\n";
    echo "- Total Mitra: " . ($dashboardData['summary']['total_mitra'] ?? 0) . "\n";
    echo "- Total Dokumen: " . ($dashboardData['summary']['total_dokumen'] ?? 0) . "\n";
    echo "- Total Foto: " . ($dashboardData['summary']['total_foto'] ?? 0) . "\n";
    echo "- Proyek Aktif: " . ($dashboardData['summary']['proyek_aktif'] ?? 0) . "\n";
    
    echo "\nAktivitas Mitra count: " . ($dashboardData['aktivitas_mitra']->count() ?? 0) . "\n";
    echo "Recent Activities count: " . ($dashboardData['recent_activities']->count() ?? 0) . "\n";
    
} catch (Exception $e) {
    echo "✗ Error testing mitra activity: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== TEST COMPLETE ===\n";
echo "Aktivitas mitra terbaru telah diuji!\n";
?>
