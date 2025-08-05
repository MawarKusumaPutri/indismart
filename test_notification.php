<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Dokumen;
use App\Services\NotificationService;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing Notification System...\n";

// Create test users if they don't exist
$staffUser = User::firstOrCreate(
    ['email' => 'staff@test.com'],
    [
        'name' => 'Staff Test',
        'password' => bcrypt('password'),
        'role' => 'staff'
    ]
);

$mitraUser = User::firstOrCreate(
    ['email' => 'mitra@test.com'],
    [
        'name' => 'Mitra Test',
        'password' => bcrypt('password'),
        'role' => 'mitra'
    ]
);

echo "Staff User: " . $staffUser->name . " (ID: " . $staffUser->id . ")\n";
echo "Mitra User: " . $mitraUser->name . " (ID: " . $mitraUser->id . ")\n";

// Create a test dokumen
$dokumen = Dokumen::create([
    'user_id' => $mitraUser->id,
    'jenis_proyek' => 'Instalasi Baru',
    'nomor_kontak' => '08123456789',
    'witel' => 'Jakarta',
    'sto' => 'STO Kebayoran',
    'site_name' => 'Site KB-01',
    'status_implementasi' => 'inisiasi',
    'tanggal_dokumen' => now(),
    'keterangan' => 'Test dokumen untuk notifikasi'
]);

echo "Created test dokumen with ID: " . $dokumen->id . "\n";

// Send notification
NotificationService::notifyDokumenUploaded($dokumen);

echo "Notification sent!\n";

// Check if notification was created
$notificationCount = \App\Models\Notification::where('user_id', $staffUser->id)->count();
echo "Total notifications for staff: " . $notificationCount . "\n";

$unreadCount = NotificationService::getUnreadCount($staffUser->id);
echo "Unread notifications for staff: " . $unreadCount . "\n";

echo "Test completed!\n"; 