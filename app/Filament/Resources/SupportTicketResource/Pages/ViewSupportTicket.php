<?php

namespace App\Filament\Resources\SupportTicketResource\Pages;

use App\Filament\Resources\SupportTicketResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSupportTicket extends ViewRecord
{
    protected static string $resource = SupportTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('reply')
                ->label('Reply to Ticket')
                ->icon('heroicon-o-chat-bubble-left')
                ->url(fn(): string => route('filament.admin.resources.support-tickets.reply', $this->record)),
        ];
    }
}
