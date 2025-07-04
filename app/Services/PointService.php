<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserPoint;
use Illuminate\Support\Facades\Log;

class PointService
{
    /**
     * Restore points to user account
     */
    public static function restorePoints(User $user, float $amount): bool
    {
        try {
            // Calculate points from amount (assuming 1 Naira = 1 point conversion)
            // You may need to adjust this based on your conversion rate logic
            $points = (int) $amount;

            $userPoint = UserPoint::where('user_id', $user->id)->first();

            if ($userPoint) {
                // Update existing points
                $userPoint->increment('points', $points);
            } else {
                // Create new points record if none exists
                UserPoint::create([
                    'user_id' => $user->id,
                    'points' => $points,
                    'conversion_rate' => 1.0, // Default conversion rate
                ]);
            }

            Log::info("Restored {$points} points to user {$user->id} (amount: ₦{$amount})");
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to restore points: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Deduct points from user account (for withdrawals)
     */
    public static function deductPoints(User $user, float $amount): bool
    {
        try {
            $points = (int) $amount;
            $userPoint = UserPoint::where('user_id', $user->id)->first();

            if (!$userPoint || $userPoint->points < $points) {
                return false; // Insufficient points
            }

            $userPoint->decrement('points', $points);

            Log::info("Deducted {$points} points from user {$user->id} (amount: ₦{$amount})");
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to deduct points: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get user's current points balance
     */
    public static function getUserPoints(User $user): int
    {
        $userPoint = UserPoint::where('user_id', $user->id)->first();
        return $userPoint ? $userPoint->points : 0;
    }

    /**
     * Convert amount to points based on conversion rate
     */
    public static function convertAmountToPoints(float $amount, float $conversionRate = 1.0): int
    {
        return (int) ($amount * $conversionRate);
    }

    /**
     * Convert points to amount based on conversion rate
     */
    public static function convertPointsToAmount(int $points, float $conversionRate = 1.0): float
    {
        return $points / $conversionRate;
    }
}
