<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Services\NotificationService;

echo "=== TESTING CHECKBOX AND BULK ASSIGN FUNCTIONALITY ===\n\n";

try {
    // Get or create a staff user for testing
    $staffUser = User::firstOrCreate(
        ['email' => 'staff@test.com'],
        [
            'name' => 'Staff Test',
            'password' => bcrypt('password'),
            'role' => 'staff'
        ]
    );

    echo "Staff User: " . $staffUser->name . " (ID: " . $staffUser->id . ")\n";

    // Create test mitra users without contract numbers
    $testMitra = [];
    for ($i = 1; $i <= 3; $i++) {
        $mitra = User::create([
            'name' => 'Mitra Test ' . $i,
            'email' => 'mitra.test' . $i . '@test.com',
            'password' => bcrypt('password'),
            'role' => 'mitra'
        ]);
        $testMitra[] = $mitra;
        echo "Created test mitra: " . $mitra->name . " (ID: " . $mitra->id . ")\n";
    }

    // Test bulk assign functionality
    echo "\nTesting bulk assign functionality...\n";
    
    $mitraIds = array_column($testMitra, 'id');
    $mitraWithoutContract = User::where('role', 'mitra')
        ->whereIn('id', $mitraIds)
        ->whereNull('nomor_kontrak')
        ->get();

    echo "Found " . $mitraWithoutContract->count() . " mitra without contract numbers\n";

    $assignedCount = 0;
    $prefix = 'KTRK';
    $year = date('Y');
    $month = date('m');

    foreach ($mitraWithoutContract as $mitra) {
        // Get the last contract number for this month
        $lastContract = User::where('nomor_kontrak', 'like', $prefix . $year . $month . '%')
            ->orderBy('nomor_kontrak', 'desc')
            ->first();

        if ($lastContract) {
            $lastSequence = (int) substr($lastContract->nomor_kontrak, -4);
            $newSequence = $lastSequence + 1;
        } else {
            $newSequence = 1;
        }

        $nomorKontrak = $prefix . $year . $month . str_pad($newSequence, 4, '0', STR_PAD_LEFT);
        
        $mitra->update(['nomor_kontrak' => $nomorKontrak]);
        
        // Send notification
        NotificationService::notifyUser(
            $mitra->id,
            'Nomor Kontrak Ditugaskan',
            'Anda telah ditugaskan nomor kontrak: ' . $nomorKontrak,
            'info'
        );
        
        echo "Assigned contract number: " . $nomorKontrak . " to " . $mitra->name . "\n";
        $assignedCount++;
    }

    echo "\nBulk assign completed! " . $assignedCount . " contract numbers assigned.\n";

    // Verify the assignments
    echo "\nVerifying assignments...\n";
    foreach ($testMitra as $mitra) {
        $mitra->refresh();
        echo $mitra->name . ": " . ($mitra->nomor_kontrak ? $mitra->nomor_kontrak : 'No contract number') . "\n";
    }

    // Check notifications
    $notificationCount = \App\Models\Notification::where('user_id', $staffUser->id)->count();
    echo "\nTotal notifications for staff: " . $notificationCount . "\n";

    // Clean up - delete test mitra
    foreach ($testMitra as $mitra) {
        $mitra->delete();
    }
    echo "\nTest mitra cleaned up.\n";

    echo "\n=== CHECKBOX AND BULK ASSIGN TEST COMPLETED ===\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
