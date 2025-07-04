<?php

namespace App\Filament\Widgets;

use App\Models\UserTransaction;
use App\Models\WithdrawalRequest;
use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RevenueChartWidget extends ChartWidget
{
    protected static ?string $heading = "Revenue Analytics";
    protected static ?int $sort = 5;
    protected static ?string $maxHeight = "300px";
    protected int|string|array $columnSpan = "full";

    public ?string $filter = "30days";

    protected function getFilters(): ?array
    {
        return [
            "7days" => "Last 7 days",
            "30days" => "Last 30 days",
            "90days" => "Last 90 days",
            "365days" => "Last year",
        ];
    }

    protected function getData(): array
    {
        $filter = $this->filter;
        $days = match ($filter) {
            "7days" => 7,
            "30days" => 30,
            "90days" => 90,
            "365days" => 365,
            default => 30,
        };

        $startDate = now()->subDays($days);
        $endDate = now();

        // Generate date range
        $dateRange = [];
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateRange[] = $currentDate->format("Y-m-d");
            $currentDate->addDay();
        }

        // Get daily revenue data
        $revenueData = [];
        $withdrawalData = [];
        $profitData = [];

        foreach ($dateRange as $date) {
            // Daily revenue from transactions
            $dailyRevenue = UserTransaction::whereDate("created_at", $date)
                ->where("transaction_type", "credit")
                ->sum("amount");

            // Daily withdrawals
            $dailyWithdrawals = WithdrawalRequest::whereDate(
                "created_at",
                $date
            )
                ->where("status", "completed")
                ->sum("amount");

            // Calculate profit (revenue minus withdrawals)
            $dailyProfit = $dailyRevenue - $dailyWithdrawals;

            $revenueData[] = round($dailyRevenue, 2);
            $withdrawalData[] = round($dailyWithdrawals, 2);
            $profitData[] = round($dailyProfit, 2);
        }

        return [
            "datasets" => [
                [
                    "label" => "Revenue",
                    "data" => $revenueData,
                    "borderColor" => "rgb(34, 197, 94)",
                    "backgroundColor" => "rgba(34, 197, 94, 0.1)",
                    "fill" => true,
                    "tension" => 0.4,
                ],
                [
                    "label" => "Withdrawals",
                    "data" => $withdrawalData,
                    "borderColor" => "rgb(239, 68, 68)",
                    "backgroundColor" => "rgba(239, 68, 68, 0.1)",
                    "fill" => true,
                    "tension" => 0.4,
                ],
                [
                    "label" => "Net Profit",
                    "data" => $profitData,
                    "borderColor" => "rgb(59, 130, 246)",
                    "backgroundColor" => "rgba(59, 130, 246, 0.1)",
                    "fill" => true,
                    "tension" => 0.4,
                ],
            ],
            "labels" => $this->formatLabels($dateRange, $filter),
        ];
    }

    protected function getType(): string
    {
        return "line";
    }

    protected function getOptions(): array
    {
        return [
            "plugins" => [
                "legend" => [
                    "display" => true,
                    "position" => "top",
                ],
                "tooltip" => [
                    "mode" => "index",
                    "intersect" => false,
                    "callbacks" => [
                        "label" => "function(context) {
                            return context.dataset.label + ': ₦' + context.parsed.y.toLocaleString();
                        }",
                    ],
                ],
            ],
            "scales" => [
                "x" => [
                    "display" => true,
                    "title" => [
                        "display" => true,
                        "text" => "Date",
                    ],
                ],
                "y" => [
                    "display" => true,
                    "title" => [
                        "display" => true,
                        "text" => "Amount (₦)",
                    ],
                    "ticks" => [
                        "callback" => "function(value) {
                            return '₦' + value.toLocaleString();
                        }",
                    ],
                ],
            ],
            "interaction" => [
                "mode" => "nearest",
                "axis" => "x",
                "intersect" => false,
            ],
            "elements" => [
                "point" => [
                    "radius" => 3,
                    "hoverRadius" => 6,
                ],
            ],
            "maintainAspectRatio" => false,
            "responsive" => true,
        ];
    }

    private function formatLabels(array $dateRange, string $filter): array
    {
        return array_map(function ($date) use ($filter) {
            $carbon = Carbon::parse($date);

            return match ($filter) {
                "7days" => $carbon->format("M j"),
                "30days" => $carbon->format("M j"),
                "90days" => $carbon->format("M j"),
                "365days" => $carbon->format("M Y"),
                default => $carbon->format("M j"),
            };
        }, $dateRange);
    }
}
