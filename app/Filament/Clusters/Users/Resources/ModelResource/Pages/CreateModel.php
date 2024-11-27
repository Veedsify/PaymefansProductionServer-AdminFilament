<?php

namespace App\Filament\Clusters\Users\Resources\ModelResource\Pages;

use App\Filament\Clusters\Users\Resources\ModelResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateModel extends CreateRecord
{
    protected static string $resource = ModelResource::class;
}
