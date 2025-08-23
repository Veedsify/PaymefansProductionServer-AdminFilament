<?php

namespace App\Filament\Support\Resources\UserRepostResource\Pages;

use App\Filament\Support\Resources\UserRepostResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserReposts extends ListRecords
{
    protected static string $resource = UserRepostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
