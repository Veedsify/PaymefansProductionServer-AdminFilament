<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Conversations extends Component
{
    public $users;
    public $conversations = [];
    public $selectedConversationId = null;
    public $isLoading = false;

    public function mount()
    {
        $this->users = User::where("role", "!=", "admin")->get();
        $this->loadConversations();
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

                // Get the participant record
                $participant = $conversation->participants->first();

                if ($participant) {
                    // Get the other user ID (not the admin)
                    $otherUserId =
                        $participant->user_1 === $adminUserId
                            ? $participant->user_2
                            : $participant->user_1;
                } else {
                    $otherUserId = null;
                }

                // Get user details
                $otherUser = $otherUserId
                    ? User::where("user_id", $otherUserId)->first()
                    : null;

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

    public function selectConversation($conversationId)
    {
        $this->selectedConversationId = $conversationId;
        $this->dispatch(
            "conversationSelected",
            conversationId: $conversationId,
        );

        // Emit socket join event
        $this->dispatch("emitSocketMessage", [
            "event" => "join",
            "data" => $conversationId,
        ]);
    }

    public function startNewConversation($userId)
    {
        $this->isLoading = true;
        $adminUserId = Auth::user()->user_id;

        // Check if a participant relationship already exists
        $existingParticipant = \App\Models\Participant::where(function (
            $q,
        ) use ($adminUserId, $userId) {
            $q->where("user_1", $adminUserId)->where("user_2", $userId);
        })
            ->orWhere(function ($q) use ($adminUserId, $userId) {
                $q->where("user_1", $userId)->where("user_2", $adminUserId);
            })
            ->first();

        if ($existingParticipant) {
            // Find the conversation for this participant
            $existingConversation = $existingParticipant
                ->conversations()
                ->first();
            if ($existingConversation) {
                $this->selectConversation(
                    $existingConversation->conversation_id,
                );
                return;
            }
        }

        // Create new conversation
        $conversationId = "conv_" . uniqid() . "_" . time();

        $conversation = Conversation::create([
            "conversation_id" => $conversationId,
        ]);

        // Create participant record
        $participant = \App\Models\Participant::create([
            "user_1" => $adminUserId,
            "user_2" => $userId,
        ]);

        // Link participant to conversation through pivot table
        $conversation->participants()->attach($participant->id);

        $this->loadConversations();
        $this->selectConversation($conversationId);
        $this->isLoading = false;
    }

    #[On("messageReceived")]
    public function handleNewMessage($data)
    {
        // Update conversations in real-time
        $this->loadConversations();

        // If it's for the current conversation, emit update
        if (
            isset($data["conversationId"]) &&
            $data["conversationId"] === $this->selectedConversationId
        ) {
            $this->dispatch("newMessageInConversation", $data);
        }
    }

    #[On("messageSent")]
    public function handleSentMessage($data)
    {
        $this->loadConversations();
    }

    public function refreshConversations()
    {
        $this->loadConversations();
    }

    #[On("socketConnected")]
    public function handleSocketConnected()
    {
        // Join admin notification room
        $adminUserId = Auth::user()->user_id;
        $this->dispatch("emitSocketMessage", [
            "event" => "notifications-join",
            "data" => $adminUserId,
        ]);

        // Request latest conversations
        $this->dispatch("emitSocketMessage", [
            "event" => "get-conversations",
            "data" => ["userId" => $adminUserId],
        ]);
    }

    #[On("socketMessageReceived")]
    public function handleSocketMessage($event, $data)
    {
        switch ($event) {
            case "new_message":
                $this->handleNewMessage([
                    "conversationId" =>
                        $data["conversationId"] ??
                        ($data["conversation_id"] ?? null),
                    "message" => $data["message"] ?? $data,
                ]);
                break;

            case "conversations":
                $this->loadConversations();
                break;

            case "message-seen":
                $this->loadConversations();
                break;
        }
    }

    public function getConversationUnreadCount($conversation)
    {
        if (!isset($conversation["lastMessage"])) {
            return 0;
        }

        $lastMessage = $conversation["lastMessage"];
        $adminUserId = Auth::user()->user_id;

        // If last message is not from admin and not seen, it's unread
        if (
            $lastMessage["sender_id"] !== $adminUserId &&
            !$lastMessage["seen"]
        ) {
            return 1;
        }

        return 0;
    }

    public function render()
    {
        return view("livewire.chat.conversations");
    }
}
