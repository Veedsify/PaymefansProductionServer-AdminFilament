<?php

namespace App\Filament\Widgets;

use App\Models\Configuration;
use App\Models\User;
use App\Models\Post;
use App\Models\WithdrawalRequest;
use App\Models\UserTransaction;
use App\Models\LiveStream;
use App\Models\Order;
use App\Models\UserPoint;
use App\Models\UserPointsPurchase;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
class PlatformStatsWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected static bool $isLazy = false;
    protected int|string|array $columnSpan = "full";

    protected function getStats(): array
    {
        // Get current month and previous month for comparison
        $currentMonth = now()->startOfMonth();
        $previousMonth = now()->subMonth()->startOfMonth();

        // Total Posts
        $totalPosts = Post::count();
        $postsThisMonth = Post::where(
            "created_at",
            ">=",
            $currentMonth
        )->count();
        $postsLastMonth = Post::whereBetween("created_at", [
            $previousMonth,
            $currentMonth,
        ])->count();
        $postGrowth =
            $postsLastMonth > 0
                ? (($postsThisMonth - $postsLastMonth) / $postsLastMonth) * 100
                : ($postsThisMonth > 0
                    ? 100
                    : 0);

        // Withdrawal Requests
        $pendingWithdrawals = WithdrawalRequest::where(
            "status",
            "pending"
        )->count();
        $totalWithdrawalAmount = WithdrawalRequest::where(
            "status",
            "completed"
        )->sum("amount");

        // Revenue from completed transactions
        $monthlyRevenue = UserTransaction::where("transaction_type", "credit")
            ->where("created_at", ">=", $currentMonth)
            ->sum("amount");

        
        $lastMonthRevenue = UserTransaction::where("transaction_type", "credit")
            ->whereBetween("created_at", [$previousMonth, $currentMonth])
            ->sum("amount");
        $revenueGrowth =
            $lastMonthRevenue > 0
                ? (($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) *
                    100
                : ($monthlyRevenue > 0
                    ? 100
                    : 0);

        // Live Streams
        $activeLiveStreams = LiveStream::where(
            "stream_status",
            "live"
        )->count();

        // Platform Health Score (simplified calculation)
        $healthScore = $this->calculatePlatformHealth();

        return [
            Stat::make(
                "Monthly Revenue",
                "₦" . number_format(($monthlyRevenue * Configuration::first()->point_conversion_rate_ngn), 2)
            )
                ->description(
                    $revenueGrowth >= 0
                        ? "+{$revenueGrowth}% from last month"
                        : "{$revenueGrowth}% from last month"
                )
                ->descriptionIcon(
                    $revenueGrowth >= 0
                        ? "heroicon-m-arrow-trending-up"
                        : "heroicon-m-arrow-trending-down"
                )
                ->color($revenueGrowth >= 0 ? "success" : "danger")
                ->chart($this->getRevenueChart()),

            Stat::make("Total Posts", number_format($totalPosts))
                ->description(
                    $postGrowth >= 0
                        ? "+{$postGrowth}% this month"
                        : "{$postGrowth}% this month"
                )
                ->descriptionIcon(
                    $postGrowth >= 0
                        ? "heroicon-m-arrow-trending-up"
                        : "heroicon-m-arrow-trending-down"
                )
                ->color($postGrowth >= 0 ? "success" : "danger")
                ->chart($this->getPostsChart()),

            Stat::make(
                "Pending Withdrawals",
                number_format($pendingWithdrawals)
            )
                ->description("Requires attention")
                ->descriptionIcon("heroicon-m-clock")
                ->color($pendingWithdrawals > 10 ? "warning" : "success")
                ->url("/admin/withdrawal-requests"),

            Stat::make("Today Revenue Streams", "₦" . number_format(
                UserPoint::whereDate("created_at", Carbon::today())
                    ->sum("points") * Configuration::first()->point_conversion_rate_ngn, 2
            ))
                ->description("Today's earnings")
                ->descriptionIcon("heroicon-m-currency-dollar")
                ->color("success"),

            Stat::make("Platform Health", $healthScore . "%")
                ->description(
                    $healthScore >= 80
                        ? "Excellent"
                        : ($healthScore >= 60
                            ? "Good"
                            : "Needs attention")
                )
                ->descriptionIcon(
                    $healthScore >= 80
                        ? "heroicon-m-check-circle"
                        : "heroicon-m-exclamation-triangle"
                )
                ->color(
                    $healthScore >= 80
                        ? "success"
                        : ($healthScore >= 60
                            ? "warning"
                            : "danger")
                ),

            Stat::make(
                "Total Payouts",
                "₦" . number_format($totalWithdrawalAmount, 2)
            )
                ->description("Completed withdrawals")
                ->descriptionIcon("heroicon-m-banknotes")
                ->color("success"),
        ];
    }

    private function getPostsChart(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $count = Post::whereDate("created_at", $date)->count();
            $data[] = $count;
        }
        return $data;
    }

    private function getRevenueChart(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $amount = UserTransaction::where("transaction_type", "credit")
                ->whereDate("created_at", $date)
                ->sum("amount");
            $data[] = (int) $amount;
        }
        return $data;
    }

    private function calculatePlatformHealth(): int
    {
        $factors = [];

        // Active users factor (30%)
        $totalUsers = User::where("role", "!=", "admin")->count();
        $activeUsers = User::where("role", "!=", "admin")
            ->where("is_active", true)
            ->count();
        $factors["active_users"] =
            $totalUsers > 0 ? ($activeUsers / $totalUsers) * 30 : 0;

        // Content creation factor (25%)
        $recentPosts = Post::where(
            "created_at",
            ">=",
            now()->subDays(7)
        )->count();
        $factors["content_creation"] = min(($recentPosts / 100) * 25, 25); // Max 25 points

        // Transaction health (25%)
        $recentTransactions = UserTransaction::where(
            "created_at",
            ">=",
            now()->subDays(7)
        )->count();
        $factors["transactions"] = min(($recentTransactions / 50) * 25, 25); // Max 25 points

        // System responsiveness (20%)
        $pendingWithdrawals = WithdrawalRequest::where(
            "status",
            "pending"
        )->count();
        $factors["responsiveness"] = max(20 - $pendingWithdrawals * 2, 0); // Deduct 2 points per pending withdrawal

        return (int) array_sum($factors);
    }
}
