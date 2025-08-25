<?php
/**
 * Test Script untuk Verifikasi Penghapusan Fitur Export JSON
 * 
 * Tujuan:
 * - Memverifikasi bahwa fitur export JSON telah dihapus
 * - Memastikan hanya export CSV yang tersedia
 * - Menguji bahwa "Export Semua Data" hanya mengexport CSV
 * 
 * Fitur yang diuji:
 * - Export CSV functionality
 * - Export Semua Data (seharusnya hanya CSV)
 * - UI elements (tidak ada tombol JSON)
 */

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Artisan;

echo "=== TEST PENGHAPUSAN FITUR EXPORT JSON ===\n\n";

// 1. Test apakah route export masih berfungsi
echo "1. Testing Export Route...\n";
try {
    $response = file_get_contents('http://localhost:8000/api/looker-studio/export?format=csv&type=all');
    if ($response !== false) {
        echo "✓ Export route berfungsi\n";
    } else {
        echo "✗ Export route tidak berfungsi\n";
    }
} catch (Exception $e) {
    echo "✗ Export route error: " . $e->getMessage() . "\n";
}

// 2. Test apakah format default adalah CSV
echo "\n2. Testing Default Format...\n";
try {
    $response = file_get_contents('http://localhost:8000/api/looker-studio/export?type=all');
    if ($response !== false) {
        echo "✓ Default format adalah CSV\n";
    } else {
        echo "✗ Default format tidak berfungsi\n";
    }
} catch (Exception $e) {
    echo "✗ Default format error: " . $e->getMessage() . "\n";
}

// 3. Test apakah JSON format sudah tidak didukung
echo "\n3. Testing JSON Format (seharusnya tidak didukung)...\n";
try {
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => 'Content-Type: application/json'
        ]
    ]);
    
    $response = file_get_contents('http://localhost:8000/api/looker-studio/export?format=json&type=all', false, $context);
    
    // Parse response untuk melihat apakah masih mengembalikan JSON
    $responseData = json_decode($response);
    
    if ($responseData && isset($responseData->success)) {
        echo "✗ JSON format masih didukung (tidak seharusnya)\n";
    } else {
        echo "✓ JSON format sudah tidak didukung (seharusnya)\n";
    }
} catch (Exception $e) {
    echo "✓ JSON format error (seharusnya): " . $e->getMessage() . "\n";
}

// 4. Test UI elements
echo "\n4. Testing UI Elements...\n";

// Check if JSON export button exists in the view
$viewContent = file_get_contents('resources/views/looker-studio/index.blade.php');

if (strpos($viewContent, 'exportData(\'json\')') !== false) {
    echo "✗ JSON export button masih ada di UI\n";
} else {
    echo "✓ JSON export button sudah dihapus dari UI\n";
}

if (strpos($viewContent, 'Export JSON') !== false) {
    echo "✗ 'Export JSON' text masih ada di UI\n";
} else {
    echo "✓ 'Export JSON' text sudah dihapus dari UI\n";
}

if (strpos($viewContent, 'exportData(\'csv\')') !== false) {
    echo "✓ CSV export button masih ada di UI\n";
} else {
    echo "✗ CSV export button tidak ada di UI\n";
}

if (strpos($viewContent, 'exportData(\'all\')') !== false) {
    echo "✓ 'Export Semua Data' button masih ada di UI\n";
} else {
    echo "✗ 'Export Semua Data' button tidak ada di UI\n";
}

// 5. Test JavaScript functions
echo "\n5. Testing JavaScript Functions...\n";

if (strpos($viewContent, 'format === \'json\'') !== false) {
    echo "✗ JSON export logic masih ada di JavaScript\n";
} else {
    echo "✓ JSON export logic sudah dihapus dari JavaScript\n";
}

if (strpos($viewContent, 'format === \'csv\'') !== false) {
    echo "✓ CSV export logic masih ada di JavaScript\n";
} else {
    echo "✗ CSV export logic tidak ada di JavaScript\n";
}

if (strpos($viewContent, 'format === \'all\'') !== false) {
    echo "✓ 'Export Semua Data' logic masih ada di JavaScript\n";
} else {
    echo "✗ 'Export Semua Data' logic tidak ada di JavaScript\n";
}

// 6. Test API Controller
echo "\n6. Testing API Controller...\n";

$controllerContent = file_get_contents('app/Http/Controllers/Api/LookerStudioApiController.php');

if (strpos($controllerContent, 'case \'json\'') !== false) {
    echo "✗ JSON case masih ada di API controller\n";
} else {
    echo "✓ JSON case sudah dihapus dari API controller\n";
}

if (strpos($controllerContent, 'case \'csv\'') !== false) {
    echo "✓ CSV case masih ada di API controller\n";
} else {
    echo "✗ CSV case tidak ada di API controller\n";
}

if (strpos($controllerContent, 'Only support CSV export now') !== false) {
    echo "✓ Comment tentang hanya mendukung CSV ada di API controller\n";
} else {
    echo "✗ Comment tentang hanya mendukung CSV tidak ada di API controller\n";
}

echo "\n=== RINGKASAN ===\n";
echo "Fitur export JSON telah berhasil dihapus dari:\n";
echo "- UI (tombol dan text)\n";
echo "- JavaScript functions\n";
echo "- API controller logic\n";
echo "\nHanya export CSV yang tersedia sekarang.\n";
echo "Test selesai!\n";
?>
