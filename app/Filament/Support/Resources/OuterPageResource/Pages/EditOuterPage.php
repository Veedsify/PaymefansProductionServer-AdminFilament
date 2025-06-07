<?php

namespace App\Filament\Support\Resources\OuterPageResource\Pages;

use App\Filament\Support\Resources\OuterPageResource;
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
