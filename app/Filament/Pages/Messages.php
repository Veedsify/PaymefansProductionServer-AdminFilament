<?php

namespace App\Filament\Pages;

use App\Models\Conversation;
use App\Models\User;
use App\Services\Socket;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;

class Messages extends Page
{
    protected static ?string $navigationIcon = 'ri-chat-3-line';
    protected static ?string $navigationGroup = 'Chats';
    public $conversations;
    public $backendServer;
    public $users;

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }

    public function loadConversations()
    {

        $this->conversations = Conversation::whereHas('participants', function ($q) {
            $q->where('user_1', Auth::user()->user_id)->orWhere('user_2', Auth::user()->user_id);
        })
            ->withMax('messages', 'created_at')
            ->orderByDesc('messages_max_created_at')
            ->get();
    }

    public function mount()
    {
        $this->loadConversations();
        $this->backendServer = env('BACKEND_URL');
        $this->users = User::where('role', '!=', 'admin')->get();
    }

    protected static string $view = 'filament.clusters.chats.pages.messages';
}
