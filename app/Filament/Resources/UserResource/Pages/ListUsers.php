<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;


    public function getTabs(): array
    {
        return [
            "All" => Tab::make("All")
                ->icon("heroicon-o-users"),
            "Admin" => Tab::make("Admin")
                ->icon("heroicon-o-shield-check")
                ->modifyQueryUsing(fn ($query) => $query->where('role', 'admin'))
                ->badge(fn () => UserResource::getModel()::where('role', 'admin')->count()),
            "Support" => Tab::make("Support")
                ->icon("gmdi-support-agent-tt")
                ->modifyQueryUsing(fn ($query) => $query->where('role', 'support'))
                ->badge(fn () => UserResource::getModel()::where('role', 'support')->count())
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
