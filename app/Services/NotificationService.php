<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Str;

class NotificationService
{
    /**
     * Create a notification for a user
     */
    public static function createNotification(
        int $userId,
        string $message,
        string $action = 'message',
        ?string $url = null
    ): Notification {
        return Notification::create([
            'notification_id' => Str::uuid(),
            'user_id' => $userId,
            'message' => $message,
            'action' => $action,
            'url' => $url,
            'read' => false,
        ]);
    }

    /**
     * Send withdrawal rejection notification
     */
    public static function sendWithdrawalRejectionNotification(User $user, float $amount): Notification
    {
        $message = "Your withdrawal request of ₦" . number_format($amount) . " has been rejected. Your points have been restored to your account.";

        return self::createNotification(
            $user->id,
            $message,
            'message', // Using 'message' as it's a valid enum value
            '/withdrawals'
        );
    }

    /**
     * Send withdrawal approval notification
     */
    public static function sendWithdrawalApprovalNotification(User $user, float $amount): Notification
    {
        $message = "Your withdrawal request of ₦" . number_format($amount) . " has been approved and processed successfully.";

        return self::createNotification(
            $user->id,
            $message,
            'message', // Using 'message' as it's a valid enum value
            '/withdrawals'
        );
    }

    /**
     * Send points restoration notification
     */
    public static function sendPointsRestoredNotification(User $user, int $points): Notification
    {
        $message = "Your points have been restored. " . number_format($points) . " points have been added back to your account.";

        return self::createNotification(
            $user->id,
            $message,
            'message', // Using 'message' as it's a valid enum value
            '/points'
        );
    }
}
