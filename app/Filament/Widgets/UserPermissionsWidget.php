<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class UserPermissionsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';
    protected static bool $isLazy = true;
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        // Get user counts by permissions
        $totalUsers = User::count();
        $adminUsers = User::where('admin', true)->count();
        $activeUsers = User::where('is_active', true)->count();
        $verifiedUsers = User::where('is_verified', true)->count();
        $contentCreators = User::where('is_model', true)->count();
        $shadowBannedUsers = User::where('is_shadowbanned', true)->count();

        // Calculate percentages
        $activePercentage = $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 1) : 0;
        $verifiedPercentage = $totalUsers > 0 ? round(($verifiedUsers / $totalUsers) * 100, 1) : 0;
        $creatorPercentage = $totalUsers > 0 ? round(($contentCreators / $totalUsers) * 100, 1) : 0;

        return [
            Stat::make('Total Users', number_format($totalUsers))
                ->description('All registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Active Users', number_format($activeUsers))
                ->description("{$activePercentage}% of total users")
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Verified Users', number_format($verifiedUsers))
                ->description("{$verifiedPercentage}% verification rate")
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('info'),

            Stat::make('Content Creators', number_format($contentCreators))
                ->description("{$creatorPercentage}% are creators")
                ->descriptionIcon('heroicon-m-video-camera')
                ->color('warning'),

            Stat::make('Admin Users', number_format($adminUsers))
                ->description('Platform administrators')
                ->descriptionIcon('heroicon-m-key')
                ->color('danger'),

            Stat::make('Restricted Users', number_format($shadowBannedUsers))
                ->description('Shadow banned users')
                ->descriptionIcon('heroicon-m-eye-slash')
                ->color('gray'),
        ];
    }

    protected function getTableQuery(): ?\Illuminate\Database\Eloquent\Builder
    {
        return User::query();
    }

    public static function canView(): bool
    {
        return auth()->user()?->admin ?? false;
    }
}
