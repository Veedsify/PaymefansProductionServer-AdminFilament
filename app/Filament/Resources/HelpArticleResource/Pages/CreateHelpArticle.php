<?php

namespace App\Filament\Resources\HelpArticleResource\Pages;

use App\Filament\Resources\HelpArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateHelpArticle extends CreateRecord
{
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['article_id'] = random_int(100000000, 999999999);
        return $data;
    }
    protected static string $resource = HelpArticleResource::class;

}
