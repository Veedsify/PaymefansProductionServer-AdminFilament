<?php

namespace App\Filament\Resources\WithdrawalRequestResource\Pages;

use App\Filament\Resources\WithdrawalRequestResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListWithdrawalRequests extends ListRecords
{
    protected static string $resource = WithdrawalRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create Withdrawal Request')
                ->icon('heroicon-s-plus'),
        ];
    }

    public function getTabs(): array
    {
        return [
            Tab::make('Pending')
                ->icon('heroicon-s-clock')
                ->badge(5)
                ->badgeColor('warning')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'pending')),
            Tab::make('Processing')
                ->icon('heroicon-s-arrow-path')
                ->badgeColor('info')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'processing')),
            Tab::make('Completed')
                ->icon('heroicon-s-check-circle')
                ->badgeColor('success')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'completed')),
            Tab::make('Rejected')
                ->icon('heroicon-s-x-circle')
                ->badgeColor('danger')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'rejected')),
        ];
    }
}
