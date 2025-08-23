<?php

namespace App\Filament\Support\Resources\SupportTicketResource\Pages;

use App\Filament\Support\Resources\SupportTicketResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSupportTicket extends CreateRecord
{
    protected static string $resource = SupportTicketResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['ticket_id'] = 'TICKET-' . strtoupper(substr(md5(uniqid()), 0, 10));

        return $data;
    }
}
