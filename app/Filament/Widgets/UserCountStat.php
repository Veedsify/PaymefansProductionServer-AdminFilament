<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class UserCountStat extends BaseWidget
{
    protected static bool $isLazy = false;
    protected function getStats(): array
    {
        return [
            Stat::make('All Users', number_format(User::where('id', '!=', Auth::id())->count()))
                ->color('success')
                ->label('All Users')
                ->url(UserResource::getUrl())
        ];
    }
}
