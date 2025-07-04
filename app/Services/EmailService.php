<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Send withdrawal rejection email
     */
    public static function sendWithdrawalRejectionEmail(User $user, float $amount, ?string $reason = null): bool
    {
        try {
            $subject = 'Withdrawal Request Rejected - PayMeFans';
            $message = self::buildWithdrawalRejectionEmailContent($user, $amount, $reason);

            return self::sendEmail($user->email, $subject, $message);
        } catch (\Exception $e) {
            Log::error('Failed to send withdrawal rejection email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send withdrawal approval email
     */
    public static function sendWithdrawalApprovalEmail(User $user, float $amount, ?string $reference = null): bool
    {
        try {
            $subject = 'Withdrawal Request Approved - PayMeFans';
            $message = self::buildWithdrawalApprovalEmailContent($user, $amount, $reference);

            return self::sendEmail($user->email, $subject, $message);
        } catch (\Exception $e) {
            Log::error('Failed to send withdrawal approval email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send basic email using Laravel Mail
     */
    private static function sendEmail(string $to, string $subject, string $message): bool
    {
        try {
            Mail::html($message, function ($mail) use ($to, $subject) {
                $mail->to($to)
                    ->subject($subject)
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Build withdrawal rejection email content
     */
    private static function buildWithdrawalRejectionEmailContent(User $user, float $amount, ?string $reason): string
    {
        $reasonText = $reason ? "<p><strong>Reason:</strong> {$reason}</p>" : '';

        return "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #dc3545; color: white; padding: 20px; text-align: center; }
                .content { background-color: #f8f9fa; padding: 20px; }
                .footer { background-color: #6c757d; color: white; padding: 10px; text-align: center; font-size: 12px; }
                .amount { font-size: 24px; font-weight: bold; color: #dc3545; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Withdrawal Request Rejected</h1>
                </div>
                <div class='content'>
                    <p>Dear {$user->name},</p>
                    
                    <p>We regret to inform you that your withdrawal request has been rejected.</p>
                    
                    <p><strong>Withdrawal Amount:</strong> <span class='amount'>₦" . number_format($amount) . "</span></p>
                    
                    {$reasonText}
                    
                    <p><strong>Good News:</strong> Your points have been automatically restored to your account. You can request another withdrawal at any time.</p>
                    
                    <p>If you have any questions or concerns, please don't hesitate to contact our support team.</p>
                    
                    <p>Best regards,<br>The PayMeFans Team</p>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " PayMeFans. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>";
    }

    /**
     * Build withdrawal approval email content
     */
    private static function buildWithdrawalApprovalEmailContent(User $user, float $amount, ?string $reference): string
    {
        $referenceText = $reference ? "<p><strong>Reference:</strong> {$reference}</p>" : '';

        return "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #28a745; color: white; padding: 20px; text-align: center; }
                .content { background-color: #f8f9fa; padding: 20px; }
                .footer { background-color: #6c757d; color: white; padding: 10px; text-align: center; font-size: 12px; }
                .amount { font-size: 24px; font-weight: bold; color: #28a745; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Withdrawal Request Approved</h1>
                </div>
                <div class='content'>
                    <p>Dear {$user->name},</p>
                    
                    <p>Great news! Your withdrawal request has been approved and processed successfully.</p>
                    
                    <p><strong>Withdrawal Amount:</strong> <span class='amount'>₦" . number_format($amount) . "</span></p>
                    
                    {$referenceText}
                    
                    <p>The funds should reflect in your bank account within 1-3 business days.</p>
                    
                    <p>Thank you for using PayMeFans!</p>
                    
                    <p>Best regards,<br>The PayMeFans Team</p>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " PayMeFans. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>";
    }
}
