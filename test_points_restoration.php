<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Bootstrap Laravel
$app = new Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
);

// Now we can use Laravel services
use App\Models\User;
use App\Models\WithdrawalRequest;
use App\Services\ApiPointService;
use App\Services\ApiNotificationService;
use App\Services\WithdrawalService;
use Illuminate\Support\Facades\Log;

echo "=== Points Restoration Debug Test ===\n\n";

// Test 1: Check API configuration
echo "1. Testing API Configuration...\n";
$apiBaseUrl = config('services.express_api.base_url', 'http://localhost:3000');
$apiToken = config('services.express_api.admin_token', null);
echo "API Base URL: $apiBaseUrl\n";
echo "API Token configured: " . ($apiToken ? 'Yes' : 'No') . "\n\n";

// Test 2: Test basic API connectivity
echo "2. Testing API Connectivity...\n";
try {
    $response = \Illuminate\Support\Facades\Http::withHeaders([
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ])
    ->timeout(5)
    ->get($apiBaseUrl . '/health');

    if ($response->successful()) {
        echo "✓ API is reachable\n";
        echo "Response: " . $response->body() . "\n";
    } else {
        echo "✗ API returned error: " . $response->status() . "\n";
        echo "Response: " . $response->body() . "\n";
    }
} catch (\Exception $e) {
    echo "✗ API connection failed: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 3: Find a test user
echo "3. Finding Test User...\n";
$testUser = User::where('is_active', true)->first();
if (!$testUser) {
    echo "✗ No active users found in database\n";
    exit(1);
}

echo "✓ Found test user:\n";
echo "  ID: {$testUser->id}\n";
echo "  User ID: {$testUser->user_id}\n";
echo "  Email: {$testUser->email}\n";
echo "  Name: {$testUser->name}\n\n";

// Test 4: Test getting user points balance
echo "4. Testing Get User Points Balance...\n";
try {
    $points = ApiPointService::getUserPoints($testUser);
    echo "✓ Current points balance: $points\n";
} catch (\Exception $e) {
    echo "✗ Failed to get points balance: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 5: Test points addition (restore)
echo "5. Testing Points Addition (Restore)...\n";
$testAmount = 100.0;
$testPoints = (int) $testAmount;

try {
    echo "Attempting to add $testPoints points to user {$testUser->user_id}...\n";

    $result = ApiNotificationService::updateUserPoints($testUser->user_id, $testPoints, 'add');

    if ($result['success']) {
        echo "✓ Points addition successful\n";
        echo "Response: " . json_encode($result, JSON_PRETTY_PRINT) . "\n";

        // Verify the points were actually added
        echo "Verifying points were added...\n";
        $newPoints = ApiPointService::getUserPoints($testUser);
        echo "New points balance: $newPoints\n";
    } else {
        echo "✗ Points addition failed\n";
        echo "Error: " . json_encode($result, JSON_PRETTY_PRINT) . "\n";
    }
} catch (\Exception $e) {
    echo "✗ Exception during points addition: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 6: Test full restoration flow
echo "6. Testing Full Restoration Flow...\n";
try {
    echo "Testing ApiPointService::restorePoints()...\n";
    $restored = ApiPointService::restorePoints($testUser, $testAmount);

    if ($restored) {
        echo "✓ Full restoration flow successful\n";
    } else {
        echo "✗ Full restoration flow failed\n";
    }
} catch (\Exception $e) {
    echo "✗ Exception during full restoration: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 7: Test with a real withdrawal request if exists
echo "7. Testing With Real Withdrawal Request...\n";
$withdrawalRequest = WithdrawalRequest::where('status', 'pending')
    ->with('user')
    ->first();

if ($withdrawalRequest) {
    echo "Found test withdrawal request:\n";
    echo "  ID: {$withdrawalRequest->id}\n";
    echo "  User: {$withdrawalRequest->user->email}\n";
    echo "  Amount: ₦{$withdrawalRequest->amount}\n";
    echo "  Status: {$withdrawalRequest->status}\n\n";

    echo "Testing rejection with points restoration...\n";
    try {
        $result = WithdrawalService::rejectWithdrawal($withdrawalRequest, "Test rejection for debugging");

        if ($result['success']) {
            echo "✓ Withdrawal rejection successful\n";
            echo "Points restored: " . ($result['points_restored'] ? 'Yes' : 'No') . "\n";
            echo "Email sent: " . ($result['email_sent'] ? 'Yes' : 'No') . "\n";
            echo "Notifications sent: " . json_encode($result['notifications_sent']) . "\n";
        } else {
            echo "✗ Withdrawal rejection failed\n";
            echo "Error: {$result['message']}\n";
        }
    } catch (\Exception $e) {
        echo "✗ Exception during withdrawal rejection: " . $e->getMessage() . "\n";
    }
} else {
    echo "No pending withdrawal requests found for testing\n";
}
echo "\n";

// Test 8: Check recent logs
echo "8. Checking Recent Logs...\n";
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $logContent = file_get_contents($logFile);
    $lines = explode("\n", $logContent);
    $recentLines = array_slice($lines, -50); // Last 50 lines

    $relevantLines = array_filter($recentLines, function($line) {
        return stripos($line, 'points') !== false ||
               stripos($line, 'withdrawal') !== false ||
               stripos($line, 'restore') !== false ||
               stripos($line, 'api') !== false;
    });

    if (!empty($relevantLines)) {
        echo "Recent relevant log entries:\n";
        foreach ($relevantLines as $line) {
            echo "  $line\n";
        }
    } else {
        echo "No relevant log entries found in recent logs\n";
    }
} else {
    echo "Log file not found: $logFile\n";
}

echo "\n=== Test Complete ===\n";
echo "Check the logs above for any errors or issues.\n";
echo "Make sure your Express server is running on $apiBaseUrl\n";
