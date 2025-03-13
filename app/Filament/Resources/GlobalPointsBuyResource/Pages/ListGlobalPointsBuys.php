<?php

namespace App\Filament\Resources\GlobalPointsBuyResource\Pages;

use App\Filament\Resources\GlobalPointsBuyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGlobalPointsBuys extends ListRecords
{
    protected static string $resource = GlobalPointsBuyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('New Points Buy'),
        ];
    }
}
