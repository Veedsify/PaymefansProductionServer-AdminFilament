<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Participant;
use App\Models\User;
use App\Services\CloudflareUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    /**
     * Get all conversations for the admin
     */
    public function getConversations()
    {
        $adminUserId = Auth::user()->user_id;

        $conversations = Conversation::whereHas("participants", function (
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

                if ($participant) {
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
            ->values();

        return response()->json([
            "success" => true,
            "conversations" => $conversations,
        ]);
    }

    /**
     * Get messages for a specific conversation
     */
    public function getMessages(Request $request, $conversationId)
    {
        $cursor = $request->get("cursor");
        $limit = $request->get("limit", 20);

        $query = Message::where("conversationsId", $conversationId)
            ->with(["user"])
            ->orderBy("created_at", "desc");

        if ($cursor) {
            $query->where("id", "<", $cursor);
        }

        $messages = $query->limit($limit)->get();

        // Mark messages as seen
        $this->markConversationMessagesAsSeen($conversationId);

        return response()->json([
            "success" => true,
            "messages" => $messages->reverse()->values(),
            "nextCursor" => $messages->last()?->id,
            "hasMore" => $messages->count() === $limit,
        ]);
    }

    /**
     * Send a message in a conversation
     */
    public function sendMessage(Request $request, $conversationId)
    {
        $validator = Validator::make($request->all(), [
            "message" => "nullable|string|max:2000",
            "attachment" => "nullable|file|max:50240", // 50MB max
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    "success" => false,
                    "errors" => $validator->errors(),
                ],
                422,
            );
        }

        if (!$request->message && !$request->hasFile("attachment")) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Either message or attachment is required",
                ],
                422,
            );
        }

        // Get conversation and receiver
        $conversation = Conversation::where("conversation_id", $conversationId)
            ->with("participants")
            ->first();

        if (!$conversation) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Conversation not found",
                ],
                404,
            );
        }

        $adminUserId = Auth::user()->user_id;
        $participant = $conversation->participants->first();
        $receiverId =
            $participant->user_1 === $adminUserId
                ? $participant->user_2
                : $participant->user_1;

        // Handle attachment upload
        $attachmentData = null;
        if ($request->hasFile("attachment")) {
            $file = $request->file("attachment");
            $fileName = time() . "_" . $file->getClientOriginalName();
            $filePath = $file->storeAs("chat-attachments", $fileName, "public");

            // Create simple attachment array like server format
            $attachment = [
                "id" => uniqid("file_", true),
                "url" => Storage::url($filePath),
                "name" => pathinfo(
                    $file->getClientOriginalName(),
                    PATHINFO_FILENAME,
                ),
                "size" => $file->getSize(),
                "type" => str_starts_with($file->getMimeType(), "image/")
                    ? "image"
                    : "video",
                "poster" => "",
                "extension" =>
                    "." .
                    pathinfo(
                        $file->getClientOriginalName(),
                        PATHINFO_EXTENSION,
                    ),
            ];

            $attachmentData = json_encode([$attachment]);
        }

        // Create message
        $messageId = "msg_" . uniqid() . "_" . time();

        $message = Message::create([
            "message_id" => $messageId,
            "sender_id" => $adminUserId,
            "receiver_id" => $receiverId,
            "message" => $request->message ?: "",
            "attachment" => $attachmentData,
            "conversationsId" => $conversationId,
            "seen" => false,
        ]);

        return response()->json([
            "success" => true,
            "message" => [
                "id" => $message->id,
                "message_id" => $message->message_id,
                "sender_id" => $message->sender_id,
                "receiver_id" => $message->receiver_id,
                "message" => $message->message,
                "attachment" => $message->attachment,
                "seen" => $message->seen,
                "created_at" => $message->created_at->toISOString(),
                "conversationsId" => $message->conversationsId,
            ],
        ]);
    }

    /**
     * Create a new conversation
     */
    public function createConversation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "user_id" => "required|string|exists:Users,user_id",
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    "success" => false,
                    "errors" => $validator->errors(),
                ],
                422,
            );
        }

        $adminUserId = Auth::user()->user_id;
        $userId = $request->user_id;

        // Check if conversation already exists
        $existingParticipant = Participant::where(function ($q) use (
            $adminUserId,
            $userId,
        ) {
            $q->where("user_1", $adminUserId)->where("user_2", $userId);
        })
            ->orWhere(function ($q) use ($adminUserId, $userId) {
                $q->where("user_1", $userId)->where("user_2", $adminUserId);
            })
            ->first();

        if ($existingParticipant) {
            $existingConversation = $existingParticipant
                ->conversations()
                ->first();
            if ($existingConversation) {
                return response()->json([
                    "success" => true,
                    "conversation_id" => $existingConversation->conversation_id,
                    "exists" => true,
                ]);
            }
        }

        // Create new conversation
        $conversationId = "conv_" . uniqid() . "_" . time();

        $conversation = Conversation::create([
            "conversation_id" => $conversationId,
        ]);

        // Create participant record
        $participant = Participant::create([
            "user_1" => $adminUserId,
            "user_2" => $userId,
        ]);

        // Link participant to conversation through pivot table
        $conversation->participants()->attach($participant->id);

        return response()->json([
            "success" => true,
            "conversation_id" => $conversationId,
            "exists" => false,
        ]);
    }

    /**
     * Mark a message as seen
     */
    public function markMessageSeen($messageId)
    {
        $message = Message::find($messageId);

        if (!$message) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Message not found",
                ],
                404,
            );
        }

        $message->update(["seen" => true]);

        return response()->json([
            "success" => true,
            "message" => "Message marked as seen",
        ]);
    }

    /**
     * Get all users (excluding admins)
     */
    public function getUsers()
    {
        $users = User::where("role", "!=", "admin")
            ->select("user_id", "name", "username", "profile_image", "role")
            ->get();

        return response()->json([
            "success" => true,
            "users" => $users,
        ]);
    }

    /**
     * Mark all messages in a conversation as seen
     */
    private function markConversationMessagesAsSeen($conversationId)
    {
        $adminUserId = Auth::user()->user_id;

        Message::where("conversationsId", $conversationId)
            ->where("receiver_id", $adminUserId)
            ->where("seen", false)
            ->update(["seen" => true]);
    }
}
