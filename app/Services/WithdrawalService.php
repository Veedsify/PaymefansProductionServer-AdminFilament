<?php

namespace App\Services;

use App\Models\WithdrawalRequest;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class WithdrawalService
{
    /**
     * Handle withdrawal rejection with point restoration and notifications
     */
    public static function rejectWithdrawal(
        WithdrawalRequest $withdrawalRequest,
        ?string $reason = null
    ): array {
        try {
            DB::beginTransaction();

            // Use dedicated withdrawal rejection endpoint that handles both status update and points restoration
            $result = self::syncWithdrawalRejection(
                $withdrawalRequest,
                $reason
            );

            if (!$result["success"]) {
                DB::rollBack();
                return $result;
            }

            // Update local withdrawal status to match backend
            $withdrawalRequest->update(["status" => "rejected"]);

            // Send notifications via Express API
            $rejectionNotification = ApiNotificationService::sendWithdrawalRejectionNotification(
                $withdrawalRequest->user,
                $withdrawalRequest->amount
            );

            $pointsNotification = [];
            if ($result["points_restored"]) {
                $pointsNotification = ApiNotificationService::sendPointsRestoredNotification(
                    $withdrawalRequest->user,
                    $withdrawalRequest->amount
                );
            }

            // Send email notification via Express API
            $emailSent = ApiEmailService::sendWithdrawalRejectionEmail(
                $withdrawalRequest->user,
                $withdrawalRequest->amount,
                $reason
            );

            DB::commit();

            Log::info(
                "Withdrawal request {$withdrawalRequest->id} rejected successfully",
                [
                    "user_id" => $withdrawalRequest->user_id,
                    "amount" => $withdrawalRequest->amount,
                    "points_restored" => $result["points_restored"],
                    "new_balance" => $result["new_balance"] ?? null,
                    "email_sent" => $emailSent,
                    "reason" => $reason,
                    "rejection_notification_sent" =>
                        $rejectionNotification["success"] ?? false,
                    "points_notification_sent" =>
                        $pointsNotification["success"] ?? false,
                ]
            );

            return [
                "success" => true,
                "message" => "Withdrawal rejected successfully",
                "points_restored" => $result["points_restored"],
                "new_balance" => $result["new_balance"] ?? null,
                "email_sent" => $emailSent,
                "notifications_sent" => [
                    "rejection" => $rejectionNotification["success"] ?? false,
                    "points_restored" =>
                        $pointsNotification["success"] ?? false,
                ],
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error(
                "Failed to reject withdrawal request {$withdrawalRequest->id}: " .
                    $e->getMessage()
            );

            return [
                "success" => false,
                "message" =>
                    "Failed to reject withdrawal request: " . $e->getMessage(),
                "points_restored" => false,
                "email_sent" => false,
                "notifications_sent" => [
                    "rejection" => false,
                    "points_restored" => false,
                ],
            ];
        }
    }

    /**
     * Handle withdrawal approval completion with notifications
     */
    public static function completeWithdrawal(
        WithdrawalRequest $withdrawalRequest
    ): array {
        try {
            // Sync withdrawal approval with backend
            $result = self::syncWithdrawalApproval($withdrawalRequest);

            if (!$result["success"]) {
                Log::warning(
                    "Failed to sync withdrawal approval with backend: " .
                        $result["message"]
                );
            }

            // Send approval notification via Express API
            $approvalNotification = ApiNotificationService::sendWithdrawalApprovalNotification(
                $withdrawalRequest->user,
                $withdrawalRequest->amount
            );

            // Send approval email via Express API
            $emailSent = ApiEmailService::sendWithdrawalApprovalEmail(
                $withdrawalRequest->user,
                $withdrawalRequest->amount,
                $withdrawalRequest->reference
            );

            Log::info(
                "Withdrawal request {$withdrawalRequest->id} completed successfully",
                [
                    "user_id" => $withdrawalRequest->user_id,
                    "amount" => $withdrawalRequest->amount,
                    "reference" => $withdrawalRequest->reference,
                    "email_sent" => $emailSent,
                    "approval_notification_sent" =>
                        $approvalNotification["success"] ?? false,
                    "backend_synced" => $result["success"],
                ]
            );

            return [
                "success" => true,
                "message" => "Withdrawal completed successfully",
                "email_sent" => $emailSent,
                "notification_sent" =>
                    $approvalNotification["success"] ?? false,
            ];
        } catch (\Exception $e) {
            Log::error(
                "Failed to complete withdrawal notifications for request {$withdrawalRequest->id}: " .
                    $e->getMessage()
            );

            return [
                "success" => false,
                "message" =>
                    "Withdrawal completed but notifications failed: " .
                    $e->getMessage(),
                "email_sent" => false,
                "notification_sent" => false,
            ];
        }
    }

    /**
     * Check if user has sufficient points for withdrawal via Express API
     */
    public static function validateWithdrawal(User $user, float $amount): array
    {
        $userPoints = ApiPointService::getUserPoints($user);
        $requiredPoints = (int) $amount;

        if ($userPoints < $requiredPoints) {
            return [
                "valid" => false,
                "message" => "Insufficient points. User has {$userPoints} points but needs {$requiredPoints} points.",
                "user_points" => $userPoints,
                "required_points" => $requiredPoints,
            ];
        }

        return [
            "valid" => true,
            "message" => "User has sufficient points for withdrawal",
            "user_points" => $userPoints,
            "required_points" => $requiredPoints,
        ];
    }

    /**
     * Sync withdrawal rejection with Express API backend
     */
    private static function syncWithdrawalRejection(
        WithdrawalRequest $withdrawalRequest,
        ?string $reason = null
    ): array {
        try {
            $apiUrl =
                config(
                    "services.express_api.base_url",
                    "http://localhost:3000"
                ) . "/admin/withdrawal/reject";

            $payload = [
                "withdrawal_id" => $withdrawalRequest->id,
                "user_id" => $withdrawalRequest->user->user_id,
                "amount" => $withdrawalRequest->amount,
                "reason" => $reason,
            ];

            Log::info("Syncing withdrawal rejection with backend", [
                "api_url" => $apiUrl,
                "payload" => $payload,
            ]);

            $response = \Illuminate\Support\Facades\Http::withHeaders([
                "Content-Type" => "application/json",
                "Accept" => "application/json",
                "Authorization" =>
                    "Bearer " .
                    (session()->get("token") ?:
                        config("services.express_api.admin_token")),
            ])
                ->timeout(15)
                ->post($apiUrl, $payload);

            if ($response->successful()) {
                $data = $response->json();

                Log::info("Withdrawal rejection synced successfully", [
                    "withdrawal_id" => $withdrawalRequest->id,
                    "response" => $data,
                ]);

                return [
                    "success" => true,
                    "message" => "Withdrawal rejection synced successfully",
                    "points_restored" => true,
                    "new_balance" => $data["data"]["new_balance"] ?? null,
                ];
            } else {
                Log::error("Failed to sync withdrawal rejection", [
                    "status" => $response->status(),
                    "response" => $response->body(),
                ]);

                // Fallback to old method if sync fails
                $pointsRestored = ApiPointService::restorePoints(
                    $withdrawalRequest->user,
                    $withdrawalRequest->amount
                );

                return [
                    "success" => $pointsRestored,
                    "message" => $pointsRestored
                        ? "Fallback points restoration successful"
                        : "Failed to restore points",
                    "points_restored" => $pointsRestored,
                    "fallback_used" => true,
                ];
            }
        } catch (\Exception $e) {
            Log::error(
                "Exception during withdrawal rejection sync: " .
                    $e->getMessage()
            );

            // Fallback to old method if sync fails
            $pointsRestored = ApiPointService::restorePoints(
                $withdrawalRequest->user,
                $withdrawalRequest->amount
            );

            return [
                "success" => $pointsRestored,
                "message" => $pointsRestored
                    ? "Fallback points restoration successful"
                    : "Failed to restore points",
                "points_restored" => $pointsRestored,
                "fallback_used" => true,
                "error" => $e->getMessage(),
            ];
        }
    }

    /**
     * Sync withdrawal approval with Express API backend
     */
    private static function syncWithdrawalApproval(
        WithdrawalRequest $withdrawalRequest
    ): array {
        try {
            $apiUrl =
                config(
                    "services.express_api.base_url",
                    "http://localhost:3000"
                ) . "/admin/withdrawal/approve";

            $payload = [
                "withdrawal_id" => $withdrawalRequest->id,
                "transfer_code" => $withdrawalRequest->transfer_code,
                "reference" => $withdrawalRequest->reference,
            ];

            Log::info("Syncing withdrawal approval with backend", [
                "api_url" => $apiUrl,
                "payload" => $payload,
            ]);

            $response = \Illuminate\Support\Facades\Http::withHeaders([
                "Content-Type" => "application/json",
                "Accept" => "application/json",
                "Authorization" =>
                    "Bearer " .
                    (session()->get("token") ?:
                        config("services.express_api.admin_token")),
            ])
                ->timeout(15)
                ->post($apiUrl, $payload);

            if ($response->successful()) {
                $data = $response->json();

                Log::info("Withdrawal approval synced successfully", [
                    "withdrawal_id" => $withdrawalRequest->id,
                    "response" => $data,
                ]);

                return [
                    "success" => true,
                    "message" => "Withdrawal approval synced successfully",
                ];
            } else {
                Log::error("Failed to sync withdrawal approval", [
                    "status" => $response->status(),
                    "response" => $response->body(),
                ]);

                return [
                    "success" => false,
                    "message" =>
                        "Failed to sync withdrawal approval with backend",
                ];
            }
        } catch (\Exception $e) {
            Log::error(
                "Exception during withdrawal approval sync: " . $e->getMessage()
            );

            return [
                "success" => false,
                "message" =>
                    "Failed to sync withdrawal approval: " . $e->getMessage(),
            ];
        }
    }
}
