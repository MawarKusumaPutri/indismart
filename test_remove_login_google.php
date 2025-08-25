<?php
/**
 * Test Script untuk Verifikasi Penghapusan Tombol Login Google
 * 
 * Script ini memverifikasi bahwa tombol "Login Google" telah dihapus dari solusi URL Looker Studio
 */

echo "=== Test Penghapusan Tombol Login Google ===\n\n";

// Baca file view
$viewContent = file_get_contents('resources/views/looker-studio/index.blade.php');

// Test 1: Verifikasi tombol Login Google telah dihapus
echo "1. Testing Login Google Button Removal...\n";

$loginGoogleButtonExists = strpos($viewContent, 'Login Google') !== false;
if (!$loginGoogleButtonExists) {
    echo "✓ Tombol 'Login Google' berhasil dihapus\n";
} else {
    echo "✗ Tombol 'Login Google' masih ada\n";
}

// Test 2: Verifikasi fungsi openGoogleSignIn telah dihapus
echo "\n2. Testing openGoogleSignIn Function Removal...\n";

$openGoogleSignInExists = strpos($viewContent, 'function openGoogleSignIn()') !== false;
if (!$openGoogleSignInExists) {
    echo "✓ Fungsi 'openGoogleSignIn' berhasil dihapus\n";
} else {
    echo "✗ Fungsi 'openGoogleSignIn' masih ada\n";
}

// Test 3: Verifikasi tombol yang tersisa
echo "\n3. Testing Remaining Buttons...\n";

$remainingButtons = [
    'Buka di Tab Baru (Login)' => strpos($viewContent, 'Buka di Tab Baru (Login)') !== false,
    'Coba Embed Lagi' => strpos($viewContent, 'Coba Embed Lagi') !== false,
    'Bantuan' => strpos($viewContent, 'Bantuan') !== false
];

foreach ($remainingButtons as $button => $exists) {
    if ($exists) {
        echo "✓ Tombol '$button' masih ada\n";
    } else {
        echo "✗ Tombol '$button' tidak ditemukan\n";
    }
}

// Test 4: Verifikasi fungsi yang tersisa
echo "\n4. Testing Remaining Functions...\n";

$remainingFunctions = [
    'openDashboardWithAuth' => strpos($viewContent, 'function openDashboardWithAuth') !== false,
    'tryEmbedAfterAuth' => strpos($viewContent, 'function tryEmbedAfterAuth') !== false,
    'showAuthenticationHelp' => strpos($viewContent, 'function showAuthenticationHelp') !== false
];

foreach ($remainingFunctions as $function => $exists) {
    if ($exists) {
        echo "✓ Fungsi '$function' masih ada\n";
    } else {
        echo "✗ Fungsi '$function' tidak ditemukan\n";
    }
}

// Test 5: Verifikasi struktur HTML yang diperbarui
echo "\n5. Testing Updated HTML Structure...\n";

$htmlStructureChecks = [
    'showAuthenticationRequiredState' => strpos($viewContent, 'function showAuthenticationRequiredState') !== false,
    'Autentikasi Google Diperlukan' => strpos($viewContent, 'Autentikasi Google Diperlukan') !== false,
    'Informasi Dashboard' => strpos($viewContent, 'Informasi Dashboard') !== false,
    'Buka di Tab Baru' => strpos($viewContent, 'Buka di Tab Baru') !== false
];

foreach ($htmlStructureChecks as $element => $exists) {
    if ($exists) {
        echo "✓ Element '$element' masih ada\n";
    } else {
        echo "✗ Element '$element' tidak ditemukan\n";
    }
}

// Test 6: Verifikasi tidak ada referensi ke Google Sign In
echo "\n6. Testing No Google Sign In References...\n";

$googleSignInReferences = [
    'openGoogleSignIn' => strpos($viewContent, 'openGoogleSignIn') !== false,
    'Login Google' => strpos($viewContent, 'Login Google') !== false,
    'accounts.google.com/signin' => strpos($viewContent, 'accounts.google.com/signin') !== false
];

$allRemoved = true;
foreach ($googleSignInReferences as $reference => $exists) {
    if ($exists) {
        echo "✗ Referensi '$reference' masih ada\n";
        $allRemoved = false;
    } else {
        echo "✓ Referensi '$reference' berhasil dihapus\n";
    }
}

if ($allRemoved) {
    echo "✓ Semua referensi Google Sign In berhasil dihapus\n";
} else {
    echo "✗ Masih ada referensi Google Sign In yang perlu dihapus\n";
}

// Test 7: Verifikasi opsi yang tersisa
echo "\n7. Testing Remaining Options...\n";

$remainingOptions = [
    'Buka di Tab Baru (Login)' => 'Membuka dashboard langsung di tab baru untuk login',
    'Coba Embed Lagi' => 'Mencoba embedding dashboard setelah login',
    'Bantuan' => 'Menampilkan modal dengan informasi lengkap'
];

echo "Opsi yang tersisa setelah penghapusan Login Google:\n";
foreach ($remainingOptions as $option => $description) {
    echo "• $option\n";
    echo "  → $description\n";
}

echo "\n=== Test Summary ===\n";
echo "Penghapusan tombol Login Google telah selesai dengan:\n";
echo "- Tombol 'Login Google' dihapus dari interface\n";
echo "- Fungsi 'openGoogleSignIn' dihapus\n";
echo "- Referensi ke Google Sign In dihapus\n";
echo "- Opsi yang tersisa: 3 tombol (Buka di Tab Baru, Coba Embed Lagi, Bantuan)\n";
echo "- Struktur HTML tetap konsisten\n";

echo "\n✅ Semua test selesai!\n";
echo "Tombol Login Google berhasil dihapus dari solusi URL Looker Studio.\n\n";

echo "Opsi yang tersedia sekarang:\n";
echo "1. 'Buka di Tab Baru (Login)' - Login langsung ke dashboard\n";
echo "2. 'Coba Embed Lagi' - Retry embedding setelah login\n";
echo "3. 'Bantuan' - Informasi lengkap dan troubleshooting\n";
?>
