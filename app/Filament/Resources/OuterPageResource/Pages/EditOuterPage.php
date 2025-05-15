<?php

namespace App\Filament\Resources\OuterPageResource\Pages;

use App\Filament\Resources\OuterPageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOuterPage extends EditRecord
{
    protected static string $resource = OuterPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
