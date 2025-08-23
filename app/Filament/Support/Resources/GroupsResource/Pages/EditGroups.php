<?php

namespace App\Filament\Support\Resources\GroupsResource\Pages;

use App\Filament\Support\Resources\GroupsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGroups extends EditRecord
{
    protected static string $resource = GroupsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
