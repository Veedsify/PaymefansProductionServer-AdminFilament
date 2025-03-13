<?php

namespace App\Filament\Clusters\Users\Resources\ModelResource\Pages;

use App\Filament\Clusters\Users\Resources\ModelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListModels extends ListRecords
{
    protected static string $resource = ModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
