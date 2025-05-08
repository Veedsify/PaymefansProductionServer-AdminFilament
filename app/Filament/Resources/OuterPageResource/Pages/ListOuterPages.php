<?php

namespace App\Filament\Resources\OuterPageResource\Pages;

use App\Filament\Resources\OuterPageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOuterPages extends ListRecords
{
    protected static string $resource = OuterPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Page')
                ->icon('heroicon-s-plus'),
        ];
    }
}
