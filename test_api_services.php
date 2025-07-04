<?php

// Test API Services
// Run this with: php artisan tinker

use App\Services\ApiNotificationService;
use App\Services\ApiPointService;
use App\Services\ApiEmailService;
use App\Models\User;

echo "=== API Services Test ===\n";

// Test user (replace with actual user_id from your database)
// $user = User::where('user_id', 'test_user_id')->first();

// if ($user) {
//     echo "Testing with user: {$user->name} ({$user->user_id})\n\n";

//     // Test notification creation
//     echo "1. Testing notification creation...\n";
//     $notificationResult = ApiNotificationService::createNotification(
//         $user->user_id,
//         'Test notification from admin panel',
//         'message',
//         '/test'
//     );
//     echo "Notification result: " . json_encode($notificationResult) . "\n\n";

//     // Test points update
//     echo "2. Testing points update...\n";
//     $pointsResult = ApiNotificationService::updateUserPoints($user->user_id, 100, 'add');
//     echo "Points update result: " . json_encode($pointsResult) . "\n\n";

//     // Test points balance
//     echo "3. Testing points balance retrieval...\n";
//     $balance = ApiPointService::getUserPoints($user);
//     echo "User points balance: {$balance}\n\n";

//     // Test email sending
//     echo "4. Testing email sending...\n";
//     $emailSent = ApiEmailService::sendWithdrawalRejectionEmail($user, 5000, 'Test rejection');
//     echo "Email sent: " . ($emailSent ? 'Yes' : 'No') . "\n\n";

// } else {
//     echo "No test user found. Please create a user first or update the user_id in this script.\n";
// }

echo "Test script ready. Uncomment the code above and update the user_id to test.\n";
echo "Make sure your Express server is running on " . config('services.express_api.base_url') . "\n";
