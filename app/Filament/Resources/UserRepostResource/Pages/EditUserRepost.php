<?php

namespace App\Filament\Resources\UserRepostResource\Pages;

use App\Filament\Resources\UserRepostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserRepost extends EditRecord
{
    protected static string $resource = UserRepostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
