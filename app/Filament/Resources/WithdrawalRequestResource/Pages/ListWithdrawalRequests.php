<?php

namespace App\Filament\Resources\WithdrawalRequestResource\Pages;

use App\Filament\Resources\WithdrawalRequestResource;
use App\Models\WithdrawalRequest;
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
            'pending' => Tab::make('Pending')
                ->icon('heroicon-s-clock')
                ->badge(WithdrawalRequest::where('status', 'pending')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'pending')),
            'processing' => Tab::make('Processing')
                ->icon('heroicon-s-arrow-path')
                ->badge(WithdrawalRequest::where('status', 'processing')->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'processing')),
            'completed' => Tab::make('Completed')
                ->icon('heroicon-s-check-circle')
                ->badge(WithdrawalRequest::where('status', 'completed')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'completed')),
            'rejected' => Tab::make('Rejected')
                ->icon('heroicon-s-x-circle')
                ->badge(WithdrawalRequest::where('status', 'rejected')->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'rejected')),
        ];
    }
}
