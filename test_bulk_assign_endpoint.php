<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "=== TESTING BULK ASSIGN ENDPOINT ===\n\n";

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

    // Get mitra without contract numbers
    $mitraWithoutContract = User::where('role', 'mitra')
        ->whereNull('nomor_kontrak')
        ->limit(3)
        ->get();

    if ($mitraWithoutContract->isEmpty()) {
        echo "Tidak ada mitra tanpa nomor kontrak\n";
        exit;
    }

    echo "Found " . $mitraWithoutContract->count() . " mitra without contract numbers:\n";
    foreach ($mitraWithoutContract as $mitra) {
        echo "- " . $mitra->name . " (ID: " . $mitra->id . ")\n";
    }

    // Test the bulk assign logic directly
    $mitraIds = $mitraWithoutContract->pluck('id')->toArray();
    echo "\nTesting bulk assign for IDs: " . implode(', ', $mitraIds) . "\n";

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
        
        echo "Assigned: " . $nomorKontrak . " to " . $mitra->name . "\n";
        $assignedCount++;
    }

    echo "\nBulk assign completed! " . $assignedCount . " contract numbers assigned.\n";

    // Verify the assignments
    echo "\nVerifying assignments...\n";
    foreach ($mitraWithoutContract as $mitra) {
        $mitra->refresh();
        echo $mitra->name . ": " . ($mitra->nomor_kontrak ? $mitra->nomor_kontrak : 'No contract number') . "\n";
    }

    echo "\n=== BULK ASSIGN ENDPOINT TEST COMPLETED ===\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
