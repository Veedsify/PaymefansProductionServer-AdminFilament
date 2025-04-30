<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Pages\ViewUserActions;
use App\Models\Post;
use App\Models\User;
use Filament\Resources\Pages\Page;

class UserDetail extends Page
{
    public $activeTab = 'posts';
    public $user;
    public $record;
    public $postCount = 0;
    public function mount($record)
    {
        $this->record = $record;
        $this->user = User::find($record);
        $this->postCount = $this->user->posts()->count();
    }
    public function redirectToActions()
    {
        $this->redirect(ViewUserActions::getUrl(['record' => $this->record]));
    }

    protected static string $resource = UserResource::class;
    protected static string $view = 'filament.resources.user-resource.pages.user-detail';
}
