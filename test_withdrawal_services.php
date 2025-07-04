<?php

// Quick test script to verify our services are working
// Run this with: php artisan tinker

use App\Models\User;
use App\Models\WithdrawalRequest;
use App\Services\WithdrawalService;
use App\Services\PointService;
use App\Services\NotificationService;
use App\Services\EmailService;

// Example usage:

// Test point restoration
// $user = User::find(1);
// $restored = PointService::restorePoints($user, 1000);
// echo "Points restored: " . ($restored ? 'Yes' : 'No') . "\n";

// Test notification creation
// $notification = NotificationService::sendWithdrawalRejectionNotification($user, 1000);
// echo "Notification created: ID " . $notification->id . "\n";

// Test email sending (if mail is configured)
// $emailSent = EmailService::sendWithdrawalRejectionEmail($user, 1000, 'Test rejection');
// echo "Email sent: " . ($emailSent ? 'Yes' : 'No') . "\n";

// Test complete withdrawal rejection
// $withdrawalRequest = WithdrawalRequest::find(1);
// $result = WithdrawalService::rejectWithdrawal($withdrawalRequest, 'Test rejection reason');
// echo "Withdrawal rejection result: " . json_encode($result) . "\n";

echo "Test script ready. Use these examples in tinker to test the functionality.\n";
