<?php

namespace App\Filament\Support\Resources\HelpCategoryResource\Pages;

use App\Filament\Support\Resources\HelpCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHelpCategory extends EditRecord
{
    protected static string $resource = HelpCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
