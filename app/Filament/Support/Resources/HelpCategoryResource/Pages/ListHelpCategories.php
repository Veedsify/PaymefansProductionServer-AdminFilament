<?php

namespace App\Filament\Support\Resources\HelpCategoryResource\Pages;

use App\Filament\Support\Resources\HelpCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHelpCategories extends ListRecords
{
    protected static string $resource = HelpCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
