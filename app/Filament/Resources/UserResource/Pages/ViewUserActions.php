<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Constants\Actions;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\Page;
use Illuminate\View\View;

class ViewUserActions extends Page
{

    public $record;
    public $user;
    public $actions;
    public $subscriptionActions;
    public $transactionActions;
    public $reportActions;
    public $notificationActions;
    public $settingsActions;
    public $modelActions;
    public $postActions;
    public $commentActions;
    public $messageActions;
    public $editProfileLink;
    public function mount($record)
    {
        $this->record = $record;
        $this->user = UserResource::getModel()::find($record);
        $this->actions = (new Actions())->Actions($record);
        $this->subscriptionActions = (new Actions())->SubscriptionActions();
        $this->transactionActions = (new Actions())->TransactionActions();
        $this->reportActions = (new Actions())->ReportActions();
        $this->notificationActions = (new Actions())->NotificationActions();
        $this->settingsActions = (new Actions())->SettingActions();
        $this->modelActions = (new Actions())->ModelActions();
        $this->postActions = (new Actions())->PostActions();
        $this->commentActions = (new Actions())->CommentActions();
        $this->messageActions = (new Actions())->MessagesActions();
        $this->editProfileLink = UserResource::getUrl('edit', ['record' => $this->record]);
    }

    protected static string $resource = UserResource::class;

    // public function render(): View
    // {
    //     return view('filament.resources.user-resource.pages.view-user-actions', [
    //     ]);
    // }
    protected static string $view = 'filament.resources.user-resource.pages.view-user-actions';
}
