<?php

namespace App\Filament\Resources\GlobalPointsBuyResource\Pages;

use App\Filament\Resources\GlobalPointsBuyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGlobalPointsBuy extends EditRecord
{
    protected static string $resource = GlobalPointsBuyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
