<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use App\Models\User;

class SuspendUser extends Page
{
    protected static string $resource = UserResource::class;
    public $userId;
    public $user;
    public function mount($record)
    {
        $this->userId = $record;
        $this->user = UserResource::getModel()::find($record);
    }

    public function suspendUser(): Action
    {
        return Action::make('suspendUser')
            ->label('Suspend User')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('Suspend User')
            ->modalDescription('Are you sure you want to suspend this user?')
            ->modalSubmitActionLabel('Yes, Suspend')
            ->action(function () {
                $user = User::find($this->userId);
                if ($user->role == 'admin') {
                    Notification::make("user-suspended")
                        ->title('Admin User Cannot Be Suspended')
                        ->danger()
                        ->send();
                    return;
                }
                $user->update(['active_status' => false]);
                Notification::make("user-suspended")
                    ->title('User Account Has Been Suspended')
                    ->success()
                    ->send();
            });
    }
    protected static string $view = 'filament.resources.user-resource.pages.suspend-user';
}
