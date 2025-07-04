<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class ApiEmailService
{
    /**
     * Send withdrawal rejection email via Express API
     */
    public static function sendWithdrawalRejectionEmail(User $user, float $amount, ?string $reason = null): bool
    {
        try {
            $emailData = [
                'to' => $user->email,
                'subject' => 'Withdrawal Request Rejected - PayMeFans',
                'template' => 'withdrawal_rejection',
                'data' => [
                    'user_name' => $user->name,
                    'amount' => $amount,
                    'formatted_amount' => '₦' . number_format($amount),
                    'reason' => $reason,
                    'year' => date('Y')
                ]
            ];

            $result = ApiNotificationService::sendEmail($emailData);
            return $result['success'];
        } catch (\Exception $e) {
            Log::error('Failed to send withdrawal rejection email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send withdrawal approval email via Express API
     */
    public static function sendWithdrawalApprovalEmail(User $user, float $amount, ?string $reference = null): bool
    {
        try {
            $emailData = [
                'to' => $user->email,
                'subject' => 'Withdrawal Request Approved - PayMeFans',
                'template' => 'withdrawal_approval',
                'data' => [
                    'user_name' => $user->name,
                    'amount' => $amount,
                    'formatted_amount' => '₦' . number_format($amount),
                    'reference' => $reference,
                    'year' => date('Y')
                ]
            ];

            $result = ApiNotificationService::sendEmail($emailData);
            return $result['success'];
        } catch (\Exception $e) {
            Log::error('Failed to send withdrawal approval email: ' . $e->getMessage());
            return false;
        }
    }
}
