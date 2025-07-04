<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class ApiPointService
{
    /**
     * Get admin token from config or environment
     */
    private static function getAdminToken(): ?string
    {
        // Try to get from config first
        $adminToken = config("services.express_api.admin_token");

        // If not in config, try environment directly
        if (!$adminToken) {
            $adminToken = env("EXPRESS_API_ADMIN_TOKEN");
        }

        return $adminToken;
    }

    /**
     * Get headers with guaranteed admin token
     */
    private static function getAdminHeaders(): array
    {
        $adminToken = self::getAdminToken();

        if (!$adminToken) {
            Log::warning("No admin token configured for Express API");
        }

        $headers = [
            "Content-Type" => "application/json",
            "Accept" => "application/json",
        ];

        if ($adminToken) {
            $headers["Authorization"] = "Bearer " . $adminToken;
        }

        return $headers;
    }

    /**
     * Check if we should use test mode (no authentication)
     */
    private static function isTestMode(): bool
    {
        $token = session()->get("token");
        $adminToken = self::getAdminToken();
        return empty($token) && empty($adminToken);
    }

    /**
     * Restore points to user account via Express API
     */
    public static function restorePoints(User $user, float $amount): bool
    {
        try {
            // Calculate points from amount (assuming 1 Naira = 1 point conversion)
            $points = (int) $amount;

            // Log detailed information for debugging
            Log::info("Attempting to restore points", [
                "user_id" => $user->id,
                "user_string_id" => $user->user_id,
                "amount" => $amount,
                "points" => $points,
                "api_base_url" => config("services.express_api.base_url"),
            ]);

            // Use user_id string field for API call (as expected by Express API)
            $result = ApiNotificationService::updateUserPoints(
                $user->user_id,
                $points,
                "add"
            );

            if ($result["success"]) {
                Log::info(
                    "Successfully restored {$points} points to user {$user->user_id} (amount: ₦{$amount})",
                    [
                        "api_response" => $result,
                    ]
                );
                return true;
            } else {
                Log::error("Failed to restore points", [
                    "user_id" => $user->id,
                    "user_string_id" => $user->user_id,
                    "points" => $points,
                    "api_response" => $result,
                    "message" => $result["message"] ?? "Unknown error",
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Exception while restoring points", [
                "user_id" => $user->id,
                "user_string_id" => $user->user_id,
                "amount" => $amount,
                "points" => $points,
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    /**
     * Deduct points from user account via Express API
     */
    public static function deductPoints(User $user, float $amount): bool
    {
        try {
            $points = (int) $amount;

            // Log detailed information for debugging
            Log::info("Attempting to deduct points", [
                "user_id" => $user->id,
                "user_string_id" => $user->user_id,
                "amount" => $amount,
                "points" => $points,
                "api_base_url" => config("services.express_api.base_url"),
            ]);

            $result = ApiNotificationService::updateUserPoints(
                $user->user_id,
                $points,
                "subtract"
            );

            if ($result["success"]) {
                Log::info(
                    "Successfully deducted {$points} points from user {$user->user_id} (amount: ₦{$amount})",
                    [
                        "api_response" => $result,
                    ]
                );
                return true;
            } else {
                Log::error("Failed to deduct points", [
                    "user_id" => $user->id,
                    "user_string_id" => $user->user_id,
                    "points" => $points,
                    "api_response" => $result,
                    "message" => $result["message"] ?? "Unknown error",
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Exception while deducting points", [
                "user_id" => $user->id,
                "user_string_id" => $user->user_id,
                "amount" => $amount,
                "points" => $points,
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    /**
     * Get user's current points balance via Express API
     */
    public static function getUserPoints(User $user): int
    {
        try {
            // Use test endpoint if no authentication available
            $endpoint = self::isTestMode()
                ? "/admin/test/points/balance/"
                : "/admin/points/balance/";

            $apiUrl =
                config(
                    "services.express_api.base_url",
                    "http://localhost:3000"
                ) .
                $endpoint .
                $user->user_id;

            Log::info("Fetching user points balance", [
                "user_id" => $user->id,
                "user_string_id" => $user->user_id,
                "api_url" => $apiUrl,
                "test_mode" => self::isTestMode(),
            ]);

            // Use admin headers when available
            $headers = self::isTestMode()
                ? [
                    "Content-Type" => "application/json",
                    "Accept" => "application/json",
                ]
                : self::getAdminHeaders();

            $response = \Illuminate\Support\Facades\Http::withHeaders($headers)
                ->timeout(10)
                ->get($apiUrl);

            if ($response->successful()) {
                $data = $response->json();
                $points = $data["points"] ?? 0;

                Log::info("Successfully fetched user points", [
                    "user_id" => $user->id,
                    "user_string_id" => $user->user_id,
                    "points" => $points,
                    "api_response" => $data,
                ]);

                return $points;
            } else {
                Log::error("Failed to fetch user points - API error", [
                    "user_id" => $user->id,
                    "user_string_id" => $user->user_id,
                    "status_code" => $response->status(),
                    "response_body" => $response->body(),
                    "api_url" => $apiUrl,
                ]);
            }

            return 0;
        } catch (\Exception $e) {
            Log::error("Exception while fetching user points", [
                "user_id" => $user->id,
                "user_string_id" => $user->user_id,
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ]);
            return 0;
        }
    }

    /**
     * Convert amount to points based on conversion rate
     */
    public static function convertAmountToPoints(
        float $amount,
        float $conversionRate = 1.0
    ): int {
        return (int) ($amount * $conversionRate);
    }

    /**
     * Convert points to amount based on conversion rate
     */
    public static function convertPointsToAmount(
        int $points,
        float $conversionRate = 1.0
    ): float {
        return $points / $conversionRate;
    }
}
