<?php

namespace App\Filament\Support\Resources\PostResource\Pages;

use App\Filament\Support\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;
}
