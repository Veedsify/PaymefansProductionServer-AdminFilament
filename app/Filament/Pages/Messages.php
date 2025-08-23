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
    protected static ?string $navigationIcon = "ri-chat-3-line";
    protected static ?string $navigationGroup = "Chats";

    public $conversations;
    public $backendServer;
    public $users;
    public $selectedConversationId = null;

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }

    public function loadConversations()
    {
        $adminUserId = Auth::user()->user_id;

        $this->conversations = Conversation::whereHas("participants", function (
            $q,
        ) use ($adminUserId) {
            $q->where("user_1", $adminUserId)->orWhere("user_2", $adminUserId);
        })
            ->with([
                "messages" => function ($query) {
                    $query->latest()->limit(1);
                },
                "participants",
            ])
            ->get()
            ->map(function ($conversation) use ($adminUserId) {
                $lastMessage = $conversation->messages->first();

                // Get the other participant (not the admin)
                $participant = $conversation->participants->first();
                $otherUserId =
                    $participant->user_1 === $adminUserId
                        ? $participant->user_2
                        : $participant->user_1;

                // Get user details
                $otherUser = User::where("user_id", $otherUserId)->first();

                return [
                    "conversation_id" => $conversation->conversation_id,
                    "lastMessage" => $lastMessage
                        ? [
                            "id" => $lastMessage->id,
                            "message" => $lastMessage->message,
                            "created_at" => $lastMessage->created_at->toISOString(),
                            "sender_id" => $lastMessage->sender_id,
                            "seen" => $lastMessage->seen,
                        ]
                        : null,
                    "conversation" => [
                        "name" => $otherUser->name ?? "Unknown User",
                        "username" => $otherUser->username ?? "unknown",
                        "profile_image" =>
                            $otherUser->profile_image ?? "/default-avatar.png",
                        "user_id" => $otherUser->user_id ?? null,
                    ],
                ];
            })
            ->sortByDesc(function ($conversation) {
                return $conversation["lastMessage"]["created_at"] ?? "";
            })
            ->values()
            ->toArray();
    }

    public function mount()
    {
        $this->loadConversations();
        $this->backendServer = config(
            "custom.backend_url",
            "http://localhost:3001",
        );
        $this->users = User::where("role", "!=", "admin")->get();
    }

    public function selectConversation($conversationId)
    {
        $this->selectedConversationId = $conversationId;
        $this->dispatch(
            "conversationSelected",
            conversationId: $conversationId,
        );
    }

    public function refreshData()
    {
        $this->loadConversations();
        $this->dispatch("refreshMessages");
    }

    protected static string $view = "filament.clusters.chats.pages.messages";
}
