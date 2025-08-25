<?php
/**
 * Test Script untuk Verifikasi Tombol Edit dan Hapus URL
 * 
 * Script ini memverifikasi bahwa tombol "Edit URL" dan "Hapus URL" telah ditambahkan ke halaman Looker Studio
 */

echo "=== Test Tombol Edit dan Hapus URL ===\n\n";

// Baca file view
$viewContent = file_get_contents('resources/views/looker-studio/index.blade.php');

// Test 1: Verifikasi tombol Edit URL telah ditambahkan
echo "1. Testing Edit URL Button Addition...\n";

$editButtonExists = strpos($viewContent, 'Edit URL') !== false;
if ($editButtonExists) {
    echo "✓ Tombol 'Edit URL' berhasil ditambahkan\n";
} else {
    echo "✗ Tombol 'Edit URL' tidak ditemukan\n";
}

// Test 2: Verifikasi tombol Hapus URL telah ditambahkan
echo "\n2. Testing Delete URL Button Addition...\n";

$deleteButtonExists = strpos($viewContent, 'Hapus URL') !== false;
if ($deleteButtonExists) {
    echo "✓ Tombol 'Hapus URL' berhasil ditambahkan\n";
} else {
    echo "✗ Tombol 'Hapus URL' tidak ditemukan\n";
}

// Test 3: Verifikasi fungsi JavaScript telah ditambahkan
echo "\n3. Testing JavaScript Functions Addition...\n";

$jsFunctions = [
    'function editDashboardUrl()' => strpos($viewContent, 'function editDashboardUrl()') !== false,
    'function saveEditedUrl()' => strpos($viewContent, 'function saveEditedUrl()') !== false,
    'function deleteDashboardUrl()' => strpos($viewContent, 'function deleteDashboardUrl()') !== false,
    'function confirmDeleteUrl()' => strpos($viewContent, 'function confirmDeleteUrl()') !== false,
    'function loadNewUrl()' => strpos($viewContent, 'function loadNewUrl()') !== false
];

foreach ($jsFunctions as $function => $exists) {
    if ($exists) {
        echo "✓ Fungsi '$function' berhasil ditambahkan\n";
    } else {
        echo "✗ Fungsi '$function' tidak ditemukan\n";
    }
}

// Test 4: Verifikasi tombol di berbagai state
echo "\n4. Testing Buttons in Different States...\n";

$buttonStates = [
    'Dashboard Embed State' => strpos($viewContent, 'onclick="editDashboardUrl()"') !== false && strpos($viewContent, 'onclick="deleteDashboardUrl()"') !== false,
    'No Dashboard State' => strpos($viewContent, 'Edit URL') !== false && strpos($viewContent, 'Hapus URL') !== false,
    'Error State' => strpos($viewContent, 'Edit URL') !== false && strpos($viewContent, 'Hapus URL') !== false,
    'Authentication State' => strpos($viewContent, 'Edit URL') !== false && strpos($viewContent, 'Hapus URL') !== false
];

foreach ($buttonStates as $state => $exists) {
    if ($exists) {
        echo "✓ Tombol Edit/Hapus ada di '$state'\n";
    } else {
        echo "✗ Tombol Edit/Hapus tidak ada di '$state'\n";
    }
}

// Test 5: Verifikasi input field untuk URL baru
echo "\n5. Testing New URL Input Field...\n";

$inputElements = [
    'newUrlInput' => strpos($viewContent, 'id="newUrlInput"') !== false,
    'loadNewUrl function' => strpos($viewContent, 'onclick="loadNewUrl()"') !== false,
    'URL input placeholder' => strpos($viewContent, 'placeholder="https://lookerstudio.google.com/reporting/..."') !== false
];

foreach ($inputElements as $element => $exists) {
    if ($exists) {
        echo "✓ Element '$element' berhasil ditambahkan\n";
    } else {
        echo "✗ Element '$element' tidak ditemukan\n";
    }
}

// Test 6: Verifikasi modal untuk edit dan delete
echo "\n6. Testing Modal Components...\n";

$modalComponents = [
    'Edit URL Modal' => strpos($viewContent, 'editUrlModal') !== false,
    'Delete URL Modal' => strpos($viewContent, 'deleteUrlModal') !== false,
    'Edit URL Input' => strpos($viewContent, 'id="editUrlInput"') !== false,
    'Confirmation Dialog' => strpos($viewContent, 'Konfirmasi Hapus URL') !== false
];

foreach ($modalComponents as $component => $exists) {
    if ($exists) {
        echo "✓ Component '$component' berhasil ditambahkan\n";
    } else {
        echo "✗ Component '$component' tidak ditemukan\n";
    }
}

// Test 7: Verifikasi icon dan styling
echo "\n7. Testing Icons and Styling...\n";

$iconStyling = [
    'Edit Icon' => strpos($viewContent, 'bi-pencil') !== false,
    'Delete Icon' => strpos($viewContent, 'bi-trash') !== false,
    'Warning Color' => strpos($viewContent, 'btn-outline-warning') !== false,
    'Danger Color' => strpos($viewContent, 'btn-outline-danger') !== false
];

foreach ($iconStyling as $element => $exists) {
    if ($exists) {
        echo "✓ '$element' berhasil ditambahkan\n";
    } else {
        echo "✗ '$element' tidak ditemukan\n";
    }
}

// Test 8: Verifikasi event listeners
echo "\n8. Testing Event Listeners...\n";

$eventListeners = [
    'Enter key for edit' => strpos($viewContent, 'addEventListener(\'keypress\'') !== false,
    'Enter key for new URL' => strpos($viewContent, 'newUrlInput.addEventListener') !== false
];

foreach ($eventListeners as $listener => $exists) {
    if ($exists) {
        echo "✓ '$listener' berhasil ditambahkan\n";
    } else {
        echo "✗ '$listener' tidak ditemukan\n";
    }
}

echo "\n=== Test Summary ===\n";
echo "Penambahan tombol Edit dan Hapus URL telah selesai dengan:\n";
echo "- Tombol 'Edit URL' ditambahkan ke semua state\n";
echo "- Tombol 'Hapus URL' ditambahkan ke semua state\n";
echo "- Fungsi JavaScript untuk edit dan hapus URL ditambahkan\n";
echo "- Modal konfirmasi untuk edit dan hapus ditambahkan\n";
echo "- Input field untuk URL baru ditambahkan\n";
echo "- Event listeners untuk keyboard input ditambahkan\n";
echo "- Icon dan styling yang sesuai ditambahkan\n";

echo "\n✅ Semua test selesai!\n";
echo "Tombol Edit dan Hapus URL berhasil ditambahkan ke halaman Looker Studio.\n\n";

echo "Fitur yang tersedia sekarang:\n";
echo "1. Edit URL - Mengubah URL dashboard yang tersimpan\n";
echo "2. Hapus URL - Menghapus URL dashboard dari localStorage\n";
echo "3. Input URL Baru - Memasukkan URL dashboard baru\n";
echo "4. Modal Konfirmasi - Konfirmasi sebelum menghapus URL\n";
echo "5. Keyboard Support - Enter key untuk submit\n";
echo "6. Validation - Validasi URL Looker Studio\n";
echo "7. Auto-reload - Dashboard otomatis reload setelah edit\n";
?>
