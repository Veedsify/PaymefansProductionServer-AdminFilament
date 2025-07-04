<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class ApiNotificationService
{
    /**
     * Base URL for the Express server API
     */
    private static function getBaseUrl(): string
    {
        return config("services.express_api.base_url", "http://localhost:3000");
    }

    /**
     * Get headers for API requests
     */
    private static function getHeaders(): array
    {
        $token = session()->get("token");
        $adminToken = config("services.express_api.admin_token");

        // Use session token first, fallback to admin token, then no auth
        $authToken = $token ?: $adminToken;

        Log::info("API token status", [
            "session_token_exists" => !empty($token),
            "admin_token_exists" => !empty($adminToken),
            "using_token" => !empty($authToken),
        ]);

        $headers = [
            "Content-Type" => "application/json",
            "Accept" => "application/json",
        ];

        if ($authToken) {
            $headers["Authorization"] = "Bearer " . $authToken;
        }

        return $headers;
    }

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
     * Create a notification via Express API
     */
    public static function createNotification(
        string $userId,
        string $message,
        string $action = "message",
        ?string $url = null
    ): array {
        try {
            $response = Http::withHeaders(self::getHeaders())
                ->timeout(10)
                ->post(self::getBaseUrl() . "/admin/notifications/create", [
                    "user_id" => $userId,
                    "message" => $message,
                    "action" => $action,
                    "url" => $url,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info("Notification created successfully via API", [
                    "user_id" => $userId,
                    "message" => $message,
                    "action" => $action,
                ]);

                return [
                    "success" => true,
                    "data" => $data,
                ];
            } else {
                Log::error("Failed to create notification via API", [
                    "status" => $response->status(),
                    "response" => $response->json(),
                ]);

                return [
                    "success" => false,
                    "message" => "Failed to create notification",
                ];
            }
        } catch (\Exception $e) {
            Log::error("API notification error: " . $e->getMessage());

            return [
                "success" => false,
                "message" => $e->getMessage(),
            ];
        }
    }

    /**
     * Send withdrawal rejection notification
     */
    public static function sendWithdrawalRejectionNotification(
        User $user,
        float $amount
    ): array {
        $message =
            "Your withdrawal request of ₦" .
            number_format($amount) .
            " has been rejected. Your points have been restored to your account.";

        return self::createNotification(
            $user->user_id, // Using user_id field instead of id
            $message,
            "message",
            "/withdrawals"
        );
    }

    /**
     * Send withdrawal approval notification
     */
    public static function sendWithdrawalApprovalNotification(
        User $user,
        float $amount
    ): array {
        $message =
            "Your withdrawal request of ₦" .
            number_format($amount) .
            " has been approved and processed successfully.";

        return self::createNotification(
            $user->user_id,
            $message,
            "message",
            "/withdrawals"
        );
    }

    /**
     * Send points restoration notification
     */
    public static function sendPointsRestoredNotification(
        User $user,
        int $points
    ): array {
        $message =
            "Your points have been restored. " .
            number_format($points) .
            " points have been added back to your account.";

        return self::createNotification(
            $user->user_id,
            $message,
            "message",
            "/points"
        );
    }

    /**
     * Update user points via Express API
     */
    public static function updateUserPoints(
        string $userId,
        int $points,
        string $operation = "add"
    ): array {
        try {
            // Use test endpoint if no authentication available
            $endpoint = self::isTestMode()
                ? "/admin/test/points/update"
                : "/admin/points/update";
            $apiUrl = self::getBaseUrl() . $endpoint;
            $payload = [
                "user_id" => $userId,
                "points" => $points,
                "operation" => $operation, // 'add' or 'subtract'
            ];

            // Use admin headers for points operations
            $headers = self::isTestMode()
                ? self::getHeaders()
                : self::getAdminHeaders();

            Log::info("Making API call to update user points", [
                "api_url" => $apiUrl,
                "payload" => $payload,
                "test_mode" => self::isTestMode(),
                "has_auth_header" => isset($headers["Authorization"]),
            ]);

            $response = Http::withHeaders($headers)
                ->timeout(10)
                ->post($apiUrl, $payload);

            Log::info("API response received", [
                "status_code" => $response->status(),
                "response_body" => $response->body(),
                "user_id" => $userId,
                "points" => $points,
                "operation" => $operation,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info("User points updated successfully via API", [
                    "user_id" => $userId,
                    "points" => $points,
                    "operation" => $operation,
                    "api_response" => $data,
                ]);

                return [
                    "success" => true,
                    "data" => $data,
                    "message" =>
                        $data["message"] ?? "Points updated successfully",
                ];
            } else {
                $responseBody = $response->json();
                Log::error("Failed to update user points via API", [
                    "user_id" => $userId,
                    "points" => $points,
                    "operation" => $operation,
                    "status" => $response->status(),
                    "response_body" => $response->body(),
                    "response_json" => $responseBody,
                    "api_url" => $apiUrl,
                ]);

                return [
                    "success" => false,
                    "message" =>
                        $responseBody["message"] ??
                        "Failed to update user points",
                    "error_details" => $responseBody,
                ];
            }
        } catch (\Exception $e) {
            Log::error("API points update exception", [
                "user_id" => $userId,
                "points" => $points,
                "operation" => $operation,
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
                "api_url" => self::getBaseUrl() . "/admin/points/update",
            ]);

            return [
                "success" => false,
                "message" => "API connection error: " . $e->getMessage(),
                "exception" => $e->getMessage(),
            ];
        }
    }

    /**
     * Send email via Express API
     */
    public static function sendEmail(array $emailData): array
    {
        try {
            $response = Http::withHeaders(self::getHeaders())
                ->timeout(30) // Longer timeout for email sending
                ->post(self::getBaseUrl() . "/admin/custom-email", $emailData);

            if ($response->successful()) {
                $data = $response->json();
                Log::info("Email sent successfully via API", [
                    "to" => $emailData["to"] ?? "unknown",
                    "subject" => $emailData["subject"] ?? "unknown",
                ]);

                return [
                    "success" => true,
                    "data" => $data,
                ];
            } else {
                Log::error("Failed to send email via API", [
                    "status" => $response->status(),
                    "response" => $response->json(),
                ]);

                return [
                    "success" => false,
                    "message" => "Failed to send email",
                ];
            }
        } catch (\Exception $e) {
            Log::error("API email error: " . $e->getMessage());

            return [
                "success" => false,
                "message" => $e->getMessage(),
            ];
        }
    }
}
