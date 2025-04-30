<?php

namespace App\Filament\Resources\PlatformExchangeRateResource\Pages;

use App\Filament\Resources\PlatformExchangeRateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlatformExchangeRate extends EditRecord
{
    protected static string $resource = PlatformExchangeRateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
