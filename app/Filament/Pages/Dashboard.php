<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\PlatformStatsWidget;
use App\Filament\Widgets\RevenueChartWidget;
use App\Filament\Widgets\UserActivityChartWidget;
use App\Filament\Widgets\RecentTransactionsWidget;
use App\Filament\Widgets\QuickActionsWidget;
use App\Filament\Widgets\ContentPerformanceWidget;
use App\Filament\Widgets\SystemHealthWidget;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\AccountWidget;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.pages.dashboard';

    public function getWidgets(): array
    {
        return [
            AccountWidget::class,
            PlatformStatsWidget::class,
            QuickActionsWidget::class,
            SystemHealthWidget::class,
            RevenueChartWidget::class,
            UserActivityChartWidget::class,
            RecentTransactionsWidget::class,
            ContentPerformanceWidget::class,
        ];
    }

    public function getColumns(): int|string|array
    {
        return [
            'default' => 1,
            'sm' => 2,
            'md' => 3,
            'lg' => 4,
            'xl' => 4,
            '2xl' => 4,
        ];
    }

    public function getTitle(): string
    {
        return 'PayMeFans Admin Dashboard';
    }

    public function getSubheading(): ?string
    {
        return 'Welcome to your comprehensive platform management center';
    }
}
