<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackService
{
    private string $secretKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->secretKey = config('services.paystack.secret_key');
        $this->baseUrl = config('services.paystack.url');
    }

    /**
     * Create a transfer recipient
     */
    public function createTransferRecipient(array $data)
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->post("{$this->baseUrl}/transferrecipient", [
                    'type' => 'nuban',
                    'name' => $data['account_name'],
                    'account_number' => $data['account_number'],
                    'bank_code' => $data['bank_code'],
                    'currency' => 'NGN'
                ]);

            $result = $response->json();

            if ($response->successful() && $result['status']) {
                return [
                    'success' => true,
                    'data' => $result['data'],
                    'recipient_code' => $result['data']['recipient_code']
                ];
            }

            return [
                'success' => false,
                'message' => $result['message'] ?? 'Failed to create transfer recipient',
                'data' => $result
            ];
        } catch (\Exception $e) {
            Log::error('Paystack Create Recipient Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Service error occurred'
            ];
        }
    }

    /**
     * Initiate a transfer
     */
    public function initiateTransfer(array $data)
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->post("{$this->baseUrl}/transfer", [
                    'source' => 'balance',
                    'amount' => $data['amount'] * 100, // Convert to kobo
                    'recipient' => $data['recipient_code'],
                    'reason' => $data['reason'] ?? 'Withdrawal request'
                ]);

            $result = $response->json();

            if ($response->successful() && $result['status']) {
                return [
                    'success' => true,
                    'data' => $result['data'],
                    'transfer_code' => $result['data']['transfer_code'],
                    'reference' => $result['data']['reference']
                ];
            }

            return [
                'success' => false,
                'message' => $result['message'] ?? 'Failed to initiate transfer',
                'data' => $result
            ];
        } catch (\Exception $e) {
            Log::error('Paystack Transfer Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Service error occurred'
            ];
        }
    }

    /**
     * Finalize transfer with OTP
     */
    public function finalizeTransfer(string $transferCode, string $otp)
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->post("{$this->baseUrl}/transfer/finalize_transfer", [
                    'transfer_code' => $transferCode,
                    'otp' => $otp
                ]);

            $result = $response->json();

            if ($response->successful() && $result['status']) {
                return [
                    'success' => true,
                    'data' => $result['data'],
                    'message' => $result['message']
                ];
            }

            return [
                'success' => false,
                'message' => $result['message'] ?? 'Failed to finalize transfer',
                'data' => $result
            ];
        } catch (\Exception $e) {
            Log::error('Paystack Finalize Transfer Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Service error occurred'
            ];
        }
    }

    /**
     * Verify transfer status
     */
    public function verifyTransfer(string $reference)
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->get("{$this->baseUrl}/transfer/verify/{$reference}");

            $result = $response->json();

            if ($response->successful() && $result['status']) {
                return [
                    'success' => true,
                    'data' => $result['data']
                ];
            }

            return [
                'success' => false,
                'message' => $result['message'] ?? 'Failed to verify transfer',
                'data' => $result
            ];
        } catch (\Exception $e) {
            Log::error('Paystack Verify Transfer Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Service error occurred'
            ];
        }
    }

    /**
     * Get list of banks
     */
    public function getBanks()
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->get("{$this->baseUrl}/bank");

            $result = $response->json();

            if ($response->successful() && $result['status']) {
                return [
                    'success' => true,
                    'data' => $result['data']
                ];
            }

            return [
                'success' => false,
                'message' => $result['message'] ?? 'Failed to fetch banks',
                'data' => $result
            ];
        } catch (\Exception $e) {
            Log::error('Paystack Get Banks Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Service error occurred'
            ];
        }
    }

    /**
     * Resolve account number
     */
    public function resolveAccount(string $accountNumber, string $bankCode)
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->get("{$this->baseUrl}/bank/resolve", [
                    'account_number' => $accountNumber,
                    'bank_code' => $bankCode
                ]);

            $result = $response->json();

            if ($response->successful() && $result['status']) {
                return [
                    'success' => true,
                    'data' => $result['data'],
                    'account_name' => $result['data']['account_name']
                ];
            }

            return [
                'success' => false,
                'message' => $result['message'] ?? 'Failed to resolve account',
                'data' => $result
            ];
        } catch (\Exception $e) {
            Log::error('Paystack Resolve Account Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Service error occurred'
            ];
        }
    }

    /**
     * Calculate platform fee (should match frontend logic)
     * Frontend uses 0.25 (25%) as FEE_PERCENTAGE
     */
    public static function calculatePlatformFee(int $amount): int
    {
        return (int) floor($amount * 0.25); // 25% fee
    }

    /**
     * Calculate amount after fee (should match frontend logic)
     */
    public static function calculateAmountAfterFee(int $amount): int
    {
        return (int) floor($amount * 0.75); // 75% after 25% fee
    }
}
