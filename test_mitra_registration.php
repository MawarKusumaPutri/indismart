<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Services\NotificationService;

echo "=== TESTING MITRA REGISTRATION NOTIFICATION ===\n\n";

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

    // Create a test mitra user
    $mitraUser = User::create([
        'name' => 'Mitra Test Registration',
        'email' => 'mitra.test.registration@test.com',
        'password' => bcrypt('password'),
        'role' => 'mitra'
    ]);

    echo "Created test mitra: " . $mitraUser->name . " (ID: " . $mitraUser->id . ")\n";

    // Test notification
    NotificationService::notifyMitraRegistration($mitraUser);

    echo "Notification sent!\n";

    // Check if notification was created for staff
    $notificationCount = \App\Models\Notification::where('user_id', $staffUser->id)->count();
    echo "Total notifications for staff: " . $notificationCount . "\n";

    $unreadCount = NotificationService::getUnreadCount($staffUser->id);
    echo "Unread notifications for staff: " . $unreadCount . "\n";

    // Get the latest notification
    $latestNotification = \App\Models\Notification::where('user_id', $staffUser->id)
        ->orderBy('created_at', 'desc')
        ->first();

    if ($latestNotification) {
        echo "\nLatest notification details:\n";
        echo "Title: " . $latestNotification->title . "\n";
        echo "Message: " . $latestNotification->message . "\n";
        echo "Type: " . $latestNotification->type . "\n";
        echo "Data: " . json_encode($latestNotification->data) . "\n";
    }

    // Clean up - delete test mitra
    $mitraUser->delete();
    echo "\nTest mitra cleaned up.\n";

    echo "\n=== MITRA REGISTRATION NOTIFICATION TEST COMPLETED ===\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
