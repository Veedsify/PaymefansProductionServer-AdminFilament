<?php

namespace App\Filament\Support\Resources\SupportTicketResource\Pages;

use App\Filament\Support\Resources\SupportTicketResource;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Actions;
use Filament\Resources\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class ReplySupportTicket extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = SupportTicketResource::class;

    protected static string $view = 'filament.resources.support-ticket-resource.pages.reply-support-ticket';

    public $ticket;
    public $message;
    public $data = [];

    public function mount($record): void
    {
        $this->ticket = SupportTicket::findOrFail($record);
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Reply to Ticket')
                    ->description('Send a reply to this support ticket')
                    ->schema([
                        Forms\Components\Placeholder::make('ticket_info')
                            ->label('Ticket Information')
                            ->content(fn() => view('filament.resources.support-ticket-resource.components.ticket-info', [
                                'ticket' => $this->ticket
                            ])),

                        Forms\Components\Textarea::make('message')
                            ->label('Your Reply')
                            ->required()
                            ->rows(6)
                            ->placeholder('Type your reply here...'),
                    ])
            ])
            ->statePath('data');
    }

    public function sendReply(): void
    {
        $data = $this->form->getState();

        SupportTicketReply::create([
            'ticket_id' => $this->ticket->ticket_id,
            'user_id' => Auth::id(), // Admin user
            'message' => $data['message'],
        ]);

        // Update ticket status
        $this->ticket->update([
            'status' => 'pending',
            'updated_at' => now(),
        ]);

        Notification::make()
            ->title('Reply sent successfully')
            ->success()
            ->send();

        $this->form->fill();

        $this->redirect(route('filament.admin.resources.support-tickets.view', $this->ticket));
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('view_ticket')
                ->label('View Ticket')
                ->icon('heroicon-o-eye')
                ->url(fn(): string => route('filament.admin.resources.support-tickets.view', $this->ticket)),
        ];
    }

    public function getTitle(): string
    {
        return "Reply to Ticket #{$this->ticket->ticket_id}";
    }
}
