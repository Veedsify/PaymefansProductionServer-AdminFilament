<?php
namespace App\Filament\Resources\PlatformExchangeRateResource\Pages;

use App\Filament\Resources\PlatformExchangeRateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlatformExchangeRates extends ListRecords
{
    protected static string $resource = PlatformExchangeRateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label("Add Currency")
                ->icon("heroicon-s-banknotes")
            ,
        ];
    }
}
