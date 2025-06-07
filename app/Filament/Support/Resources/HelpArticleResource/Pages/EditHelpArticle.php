<?php

namespace App\Filament\Support\Resources\HelpArticleResource\Pages;

use App\Filament\Support\Resources\HelpArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHelpArticle extends EditRecord
{
    protected static string $resource = HelpArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
