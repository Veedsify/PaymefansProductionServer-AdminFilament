<?php

namespace App\Filament\Support\Resources\OuterPageResource\Pages;

use App\Filament\Support\Resources\OuterPageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOuterPages extends ListRecords
{
    protected static string $resource = OuterPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
