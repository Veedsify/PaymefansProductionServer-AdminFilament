<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class UserCountStat extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static bool $isLazy = false;
    protected int|string|array $columnSpan = 2;

    protected function getStats(): array
    {
        return [
            Stat::make(
                "Total Users",
                number_format(User::where("role", "!=", "admin")->count())
            )
                ->description("Registered users")
                ->descriptionIcon("heroicon-m-users")
                ->color("success")
                ->url(UserResource::getUrl()),

            Stat::make(
                "Active Models",
                number_format(
                    User::where("is_model", true)
                        ->where("is_active", true)
                        ->count()
                )
            )
                ->description("Content creators")
                ->descriptionIcon("heroicon-m-star")
                ->color("info")
                ->url(
                    UserResource::getUrl() . "?tableFilters[is_model][value]=1"
                ),

            Stat::make(
                "New This Week",
                number_format(
                    User::where("role", "!=", "admin")
                        ->where("created_at", ">=", now()->subWeek())
                        ->count()
                )
            )
                ->description("Recent signups")
                ->descriptionIcon("heroicon-m-arrow-trending-up")
                ->color("warning")
                ->url(UserResource::getUrl()),
        ];
    }
}
