<?php

namespace App\Filament\Support\Resources\UserMediaResource\Pages;

use App\Filament\Support\Resources\UserMediaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserMedia extends ListRecords
{
    protected static string $resource = UserMediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
