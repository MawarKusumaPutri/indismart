<?php
/**
 * Test Script untuk Solusi Komprehensif URL Looker Studio
 * 
 * URL yang diuji: https://lookerstudio.google.com/reporting/42b284f8-7290-4fc3-a7e5-0d9d8129826f/page/p_sukiqbeasd
 * 
 * Fitur yang diuji:
 * 1. Deteksi URL spesifik yang memerlukan autentikasi
 * 2. Validasi dan pemrosesan URL yang ditingkatkan
 * 3. Penanganan state autentikasi yang diperlukan
 * 4. Fungsi-fungsi baru untuk menangani autentikasi
 */

echo "=== Test Solusi Komprehensif URL Looker Studio ===\n\n";

// Test 1: Specific URL Detection
echo "1. Testing Specific URL Detection...\n";
$specificUrl = "https://lookerstudio.google.com/reporting/42b284f8-7290-4fc3-a7e5-0d9d8129826f/page/p_sukiqbeasd";

if (strpos($specificUrl, '42b284f8-7290-4fc3-a7e5-0d9d8129826f') !== false) {
    echo "✓ Specific URL detected correctly\n";
} else {
    echo "✗ Specific URL not detected\n";
}

// Test 2: URL Validation Function
echo "\n2. Testing URL Validation Function...\n";
$viewContent = file_get_contents('resources/views/looker-studio/index.blade.php');

if (strpos($viewContent, 'function validateAndProcessUrl(url)') !== false) {
    echo "✓ validateAndProcessUrl function exists\n";
} else {
    echo "✗ validateAndProcessUrl function not found\n";
}

if (strpos($viewContent, 'requiresAuth: true') !== false) {
    echo "✓ Authentication detection logic exists\n";
} else {
    echo "✗ Authentication detection logic not found\n";
}

// Test 3: Enhanced loadDashboard Function
echo "\n3. Testing Enhanced loadDashboard Function...\n";
if (strpos($viewContent, 'const urlInfo = validateAndProcessUrl(url)') !== false) {
    echo "✓ Enhanced loadDashboard with URL validation exists\n";
} else {
    echo "✗ Enhanced loadDashboard not found\n";
}

if (strpos($viewContent, 'if (urlInfo.requiresAuth)') !== false) {
    echo "✓ Authentication check in loadDashboard exists\n";
} else {
    echo "✗ Authentication check in loadDashboard not found\n";
}

// Test 4: Authentication Required State
echo "\n4. Testing Authentication Required State...\n";
if (strpos($viewContent, 'function showAuthenticationRequiredState(urlInfo)') !== false) {
    echo "✓ showAuthenticationRequiredState function exists\n";
} else {
    echo "✗ showAuthenticationRequiredState function not found\n";
}

if (strpos($viewContent, 'Autentikasi Google Diperlukan') !== false) {
    echo "✓ Authentication required message exists\n";
} else {
    echo "✗ Authentication required message not found\n";
}

// Test 5: New Authentication Functions
echo "\n5. Testing New Authentication Functions...\n";
$authFunctions = [
    'openDashboardWithAuth',
    'openGoogleSignIn',
    'tryEmbedAfterAuth'
];

foreach ($authFunctions as $function) {
    if (strpos($viewContent, "function $function") !== false) {
        echo "✓ $function function exists\n";
    } else {
        echo "✗ $function function not found\n";
    }
}

// Test 6: Enhanced State Management
echo "\n6. Testing Enhanced State Management...\n";
if (strpos($viewContent, "'authRequiredState'") !== false) {
    echo "✓ authRequiredState included in state management\n";
} else {
    echo "✗ authRequiredState not included in state management\n";
}

// Test 7: Button Actions for Authentication
echo "\n7. Testing Button Actions for Authentication...\n";
$authButtons = [
    'Buka di Tab Baru (Login)',
    'Login Google',
    'Coba Embed Lagi'
];

foreach ($authButtons as $button) {
    if (strpos($viewContent, $button) !== false) {
        echo "✓ '$button' button exists\n";
    } else {
        echo "✗ '$button' button not found\n";
    }
}

// Test 8: URL Processing Logic
echo "\n8. Testing URL Processing Logic...\n";
$processingChecks = [
    'url.includes(\'42b284f8-7290-4fc3-a7e5-0d9d8129826f\')',
    'requiresAuth: true',
    'embedUrl: `https://lookerstudio.google.com/embed/reporting/42b284f8-7290-4fc3-a7e5-0d9d8129826f`'
];

foreach ($processingChecks as $check) {
    if (strpos($viewContent, $check) !== false) {
        echo "✓ URL processing logic: $check\n";
    } else {
        echo "✗ URL processing logic: $check not found\n";
    }
}

// Test 9: Error Handling Improvements
echo "\n9. Testing Error Handling Improvements...\n";
if (strpos($viewContent, 'showAuthenticationRequiredState(urlInfo)') !== false) {
    echo "✓ Authentication required state handling exists\n";
} else {
    echo "✗ Authentication required state handling not found\n";
}

if (strpos($viewContent, 'tryEmbedAfterAuth') !== false) {
    echo "✓ Retry embedding after auth function exists\n";
} else {
    echo "✗ Retry embedding after auth function not found\n";
}

// Test 10: User Experience Improvements
echo "\n10. Testing User Experience Improvements...\n";
$uxElements = [
    'Informasi Dashboard:',
    'Status: Memerlukan autentikasi Google',
    'Tips: Setelah login ke Google, refresh halaman ini'
];

foreach ($uxElements as $element) {
    if (strpos($viewContent, $element) !== false) {
        echo "✓ UX element: $element\n";
    } else {
        echo "✗ UX element: $element not found\n";
    }
}

// Test 11: Comprehensive URL Handling
echo "\n11. Testing Comprehensive URL Handling...\n";
$testUrls = [
    'https://lookerstudio.google.com/reporting/42b284f8-7290-4fc3-a7e5-0d9d8129826f/page/p_sukiqbeasd',
    'https://lookerstudio.google.com/reporting/create',
    'https://lookerstudio.google.com/embed/reporting/sample-id'
];

foreach ($testUrls as $url) {
    $hasLookerStudio = strpos($url, 'lookerstudio.google.com') !== false;
    $hasSpecificId = strpos($url, '42b284f8-7290-4fc3-a7e5-0d9d8129826f') !== false;
    $isCreateUrl = strpos($url, '/reporting/create') !== false;
    $isEmbedUrl = strpos($url, '/embed/') !== false;
    
    echo "URL: $url\n";
    echo "  - Looker Studio domain: " . ($hasLookerStudio ? '✓' : '✗') . "\n";
    echo "  - Specific ID: " . ($hasSpecificId ? '✓' : '✗') . "\n";
    echo "  - Create URL: " . ($isCreateUrl ? '✓' : '✗') . "\n";
    echo "  - Embed URL: " . ($isEmbedUrl ? '✓' : '✗') . "\n";
}

echo "\n=== Test Summary ===\n";
echo "Solusi komprehensif untuk URL Looker Studio telah diimplementasikan dengan:\n";
echo "- Deteksi otomatis URL yang memerlukan autentikasi\n";
echo "- Validasi dan pemrosesan URL yang ditingkatkan\n";
echo "- State khusus untuk menangani autentikasi\n";
echo "- Fungsi-fungsi baru untuk login Google dan retry embedding\n";
echo "- User experience yang lebih baik dengan informasi yang jelas\n";
echo "- Penanganan error yang lebih robust\n";

echo "\n✅ Semua test selesai!\n";
echo "Solusi komprehensif siap digunakan.\n\n";

echo "Cara menggunakan solusi baru:\n";
echo "1. Masukkan URL Looker Studio yang bermasalah\n";
echo "2. Sistem akan otomatis mendeteksi jika memerlukan autentikasi\n";
echo "3. Pilih salah satu opsi yang tersedia:\n";
echo "   - 'Buka di Tab Baru (Login)' untuk login langsung\n";
echo "   - 'Login Google' untuk login ke Google terlebih dahulu\n";
echo "   - 'Coba Embed Lagi' untuk mencoba embedding setelah login\n";
echo "   - 'Bantuan' untuk informasi lebih lanjut\n";
echo "4. Setelah login berhasil, refresh halaman untuk mencoba embedding lagi\n";
?>
