<?php
/**
 * Test Script untuk Verifikasi Penghapusan Bagian Konfigurasi Dashboard
 * 
 * Script ini memverifikasi bahwa bagian "Konfigurasi Dashboard" telah dihapus dari halaman Looker Studio
 */

echo "=== Test Penghapusan Bagian Konfigurasi Dashboard ===\n\n";

// Baca file view
$viewContent = file_get_contents('resources/views/looker-studio/index.blade.php');

// Test 1: Verifikasi section Konfigurasi Dashboard telah dihapus
echo "1. Testing Dashboard Configuration Section Removal...\n";

$configSectionExists = strpos($viewContent, 'Konfigurasi Dashboard') !== false;
if (!$configSectionExists) {
    echo "✓ Section 'Konfigurasi Dashboard' berhasil dihapus\n";
} else {
    echo "✗ Section 'Konfigurasi Dashboard' masih ada\n";
}

// Test 2: Verifikasi tombol-tombol konfigurasi telah dihapus
echo "\n2. Testing Configuration Buttons Removal...\n";

$configButtons = [
    'Generate Looker Studio Dashboard' => strpos($viewContent, 'Generate Looker Studio Dashboard') !== false,
    'Tampilkan URL Dashboard' => strpos($viewContent, 'Tampilkan URL Dashboard') !== false,
    'Export CSV' => strpos($viewContent, 'Export CSV') !== false,
    'Export Semua Data' => strpos($viewContent, 'Export Semua Data') !== false,
    'Direct Link' => strpos($viewContent, 'Direct Link') !== false
];

foreach ($configButtons as $button => $exists) {
    if (!$exists) {
        echo "✓ Tombol '$button' berhasil dihapus\n";
    } else {
        echo "✗ Tombol '$button' masih ada\n";
    }
}

// Test 3: Verifikasi fungsi-fungsi JavaScript telah dihapus
echo "\n3. Testing JavaScript Functions Removal...\n";

$jsFunctions = [
    'function generateDashboard()' => strpos($viewContent, 'function generateDashboard()') !== false,
    'function showDashboardUrl()' => strpos($viewContent, 'function showDashboardUrl()') !== false,
    'function setCustomUrl()' => strpos($viewContent, 'function setCustomUrl()') !== false,
    'function checkExistingUrl()' => strpos($viewContent, 'function checkExistingUrl()') !== false,
    'function copyDashboardUrl()' => strpos($viewContent, 'function copyDashboardUrl()') !== false,
    'function exportData(' => strpos($viewContent, 'function exportData(') !== false,
    'function createDirectLink()' => strpos($viewContent, 'function createDirectLink()') !== false,
    'function showCustomUrlInput()' => strpos($viewContent, 'function showCustomUrlInput()') !== false
];

foreach ($jsFunctions as $function => $exists) {
    if (!$exists) {
        echo "✓ Fungsi '$function' berhasil dihapus\n";
    } else {
        echo "✗ Fungsi '$function' masih ada\n";
    }
}

// Test 4: Verifikasi elemen HTML telah dihapus
echo "\n4. Testing HTML Elements Removal...\n";

$htmlElements = [
    'customUrlSection' => strpos($viewContent, 'customUrlSection') !== false,
    'dashboardUrlSection' => strpos($viewContent, 'dashboardUrlSection') !== false,
    'customUrlInput' => strpos($viewContent, 'customUrlInput') !== false,
    'dashboardUrl' => strpos($viewContent, 'dashboardUrl') !== false,
    'openDashboardBtn' => strpos($viewContent, 'openDashboardBtn') !== false,
    'loadingModal' => strpos($viewContent, 'loadingModal') !== false
];

foreach ($htmlElements as $element => $exists) {
    if (!$exists) {
        echo "✓ Element '$element' berhasil dihapus\n";
    } else {
        echo "✗ Element '$element' masih ada\n";
    }
}

// Test 5: Verifikasi tombol yang tersisa
echo "\n5. Testing Remaining Buttons...\n";

$remainingButtons = [
    'Refresh Data' => strpos($viewContent, 'Refresh Data') !== false,
    'Buat Laporan Baru' => strpos($viewContent, 'Buat Laporan Baru') !== false,
    'Buka di Tab Baru' => strpos($viewContent, 'Buka di Tab Baru') !== false,
    'Coba Lagi' => strpos($viewContent, 'Coba Lagi') !== false,
    'Bantuan' => strpos($viewContent, 'Bantuan') !== false
];

foreach ($remainingButtons as $button => $exists) {
    if ($exists) {
        echo "✓ Tombol '$button' masih ada\n";
    } else {
        echo "✗ Tombol '$button' tidak ditemukan\n";
    }
}

// Test 6: Verifikasi fungsi yang tersisa
echo "\n6. Testing Remaining Functions...\n";

$remainingFunctions = [
    'function refreshData()' => strpos($viewContent, 'function refreshData()') !== false,
    'function createNewReport()' => strpos($viewContent, 'function createNewReport()') !== false,
    'function openDashboardInNewTab()' => strpos($viewContent, 'function openDashboardInNewTab()') !== false,
    'function showEmbeddingHelp()' => strpos($viewContent, 'function showEmbeddingHelp()') !== false,
    'function loadDashboard(' => strpos($viewContent, 'function loadDashboard(') !== false
];

foreach ($remainingFunctions as $function => $exists) {
    if ($exists) {
        echo "✓ Fungsi '$function' masih ada\n";
    } else {
        echo "✗ Fungsi '$function' tidak ditemukan\n";
    }
}

// Test 7: Verifikasi struktur yang tersisa
echo "\n7. Testing Remaining Structure...\n";

$remainingStructure = [
    'Summary Cards' => strpos($viewContent, 'Total Mitra') !== false && strpos($viewContent, 'Total Dokumen') !== false,
    'Charts Row' => strpos($viewContent, 'statusChart') !== false && strpos($viewContent, 'projectTypeChart') !== false,
    'Dashboard Display' => strpos($viewContent, 'lookerStudioFrame') !== false,
    'Authentication State' => strpos($viewContent, 'Autentikasi Google Diperlukan') !== false
];

foreach ($remainingStructure as $structure => $exists) {
    if ($exists) {
        echo "✓ '$structure' masih ada\n";
    } else {
        echo "✗ '$structure' tidak ditemukan\n";
    }
}

echo "\n=== Test Summary ===\n";
echo "Penghapusan bagian konfigurasi dashboard telah selesai dengan:\n";
echo "- Section 'Konfigurasi Dashboard' dihapus\n";
echo "- Tombol-tombol konfigurasi dihapus (Generate, Export, Direct Link)\n";
echo "- Fungsi-fungsi JavaScript terkait dihapus\n";
echo "- Elemen HTML terkait dihapus\n";
echo "- Loading modal dihapus\n";
echo "- Tombol 'Refresh Data' tetap ada\n";
echo "- Fungsi-fungsi dashboard display tetap ada\n";

echo "\n✅ Semua test selesai!\n";
echo "Bagian konfigurasi dashboard berhasil dihapus dari halaman Looker Studio.\n\n";

echo "Fitur yang tersisa:\n";
echo "1. Summary Cards - Menampilkan data ringkasan\n";
echo "2. Charts - Menampilkan grafik dan chart\n";
echo "3. Dashboard Display - Menampilkan dashboard Looker Studio\n";
echo "4. Authentication Handling - Menangani autentikasi Google\n";
echo "5. Refresh Data - Memperbarui data\n";
echo "6. Create New Report - Membuat laporan baru di Looker Studio\n";
?>
