<?php

namespace App\Filament\Pages;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Notifications\Notification;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Http;

class SendCustomEmail extends Page implements HasForms, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithInfolists;
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationGroup = 'Email';
    protected static ?string $navigationLabel = 'Send Custom Email';
    public $data = [];

    public function mount()
    {
        $this->form->fill();
    }

    public function sendEmail()
    {
        $this->form->validate();
        $data = $this->form->getState();
        $recipients = $data['recipients'];
        $subject = $data['subject'];
        $message = $data['message'];

        $server = env('BACKEND_URL');
        if (!$server) {
            return [
                'error' => 'BACKEND_URL is not defined in the .env file.',
                'status' => false
            ];
        }

        $endpoint = $server . '/admin/email/custom-email';

        $body = [
            'recipients' => $recipients,
            'subject' => $subject,
            'message' => $message,
        ];

        $token = session('token');
        if (!$token) {
            Notification::make()
                ->title('Error')
                ->body('Token is not defined in the session.')
                ->danger()
                ->send();
            return [
                'error' => 'Token is not defined in the session.',
                'status' => false
            ];
        }

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' .  $token,
        ])->asForm()->post($endpoint, $body);

        if (!$response->successful()) {
            Notification::make()
                ->title('Error')
                ->body('API call failed with status ' . $response->status())
                ->danger()
                ->send();
            return [
                'error' => 'API call failed with status ' . $response->status(),
                'status' => false
            ];
        }

        $responseBody = $response->json();

        if (isset($responseBody['error']) && $responseBody['error'] === true) {
            Notification::make()
                ->title('Error')
                ->body($responseBody['message'])
                ->danger()
                ->send();
            return [
                'error' => $responseBody['message'],
                'status' => false
            ];
        }

        Notification::make()
            ->title('Success')
            ->body('Email sent successfully!')
            ->success()
            ->send();
        return [
            'message' => 'Email sent successfully!',
            'status' => true
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Section::make('Send Custom Email')
                    ->description('Send a custom email to all users, admins, supports, or models.')
                    ->columns(1)
                    ->schema([
                        Forms\Components\Select::make('recipients')
                            ->label('Recipients')
                            ->options([
                                'all' => 'All Users',
                                'admins' => 'All Admins',
                                'supports' => 'All Supports',
                                'models' => 'All Models',
                            ])
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('subject')
                            ->label('Subject')
                            ->required(),
                        Forms\Components\RichEditor::make('message')
                            ->required()
                            ->label('Message')
                    ])
            ]);
    }

    protected static string $view = 'filament.pages.send-custom-email';
}
