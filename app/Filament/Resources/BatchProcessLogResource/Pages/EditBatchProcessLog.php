<?php

namespace App\Filament\Resources\BatchProcessLogResource\Pages;

use App\Filament\Resources\BatchProcessLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBatchProcessLog extends EditRecord
{
    protected static string $resource = BatchProcessLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
