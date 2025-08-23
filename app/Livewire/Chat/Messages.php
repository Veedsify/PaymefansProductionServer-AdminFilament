<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Services\CloudflareUploadService;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Messages extends Component
{
    use WithFileUploads;

    public $conversationId = null;
    public $messages = [];
    public $newMessage = "";
    public $receiver = null;

    #[
        Validate(
            "nullable|file|max:51200|mimes:jpg,jpeg,png,gif,webp,mp4,webm,ogg,mov",
        ),
    ]
    public $attachment = null;

    public $isLoading = false;
    public $hasMore = true;
    public $cursor = null;

    public function mount($conversationId = null)
    {
        if ($conversationId) {
            $this->conversationId = $conversationId;
            $this->loadConversation();
            $this->loadMessages();
        }
    }

    public function loadConversation()
    {
        if (!$this->conversationId) {
            return;
        }

        $adminUserId = Auth::user()->user_id;

        $conversation = Conversation::where(
            "conversation_id",
            $this->conversationId,
        )
            ->with("participants")
            ->first();

        if ($conversation) {
            $participant = $conversation->participants->first();
            $otherUserId =
                $participant->user_1 === $adminUserId
                    ? $participant->user_2
                    : $participant->user_1;

            $this->receiver = User::where("user_id", $otherUserId)->first();
        }
    }

    public function loadMessages($loadMore = false)
    {
        if (!$this->conversationId) {
            return;
        }

        $this->isLoading = true;

        $query = Message::where("conversationsId", $this->conversationId)
            ->with(["user"])
            ->orderBy("created_at", "desc");

        if ($loadMore && $this->cursor) {
            $query->where("id", "<", $this->cursor);
        }

        $newMessages = $query->limit(20)->get();

        if ($loadMore) {
            $this->messages = array_merge(
                $this->messages,
                $newMessages->reverse()->toArray(),
            );
        } else {
            $this->messages = $newMessages->reverse()->toArray();
        }

        $this->cursor = $newMessages->last()?->id;
        $this->hasMore = $newMessages->count() === 20;
        $this->isLoading = false;

        // Mark messages as seen
        $this->markMessagesAsSeen();
    }

    public function markMessagesAsSeen()
    {
        if (!$this->conversationId) {
            return;
        }

        $adminUserId = Auth::user()->user_id;

        Message::where("conversationsId", $this->conversationId)
            ->where("receiver_id", $adminUserId)
            ->where("seen", false)
            ->update(["seen" => true]);
    }

    public function sendMessage()
    {
        if (!$this->newMessage && !$this->attachment) {
            return;
        }
        if (!$this->conversationId || !$this->receiver) {
            return;
        }

        // Validate attachment if present
        if ($this->attachment) {
            $this->validate();
        }

        $adminUserId = Auth::user()->user_id;
        $messageId = "msg_" . uniqid() . "_" . time();

        $attachmentData = null;

        if ($this->attachment) {
            // Debug: Log the attachment structure
            \Log::info("Attachment structure:", [
                "type" => gettype($this->attachment),
                "is_array" => is_array($this->attachment),
                "is_object" => is_object($this->attachment),
                "class" => is_object($this->attachment)
                    ? get_class($this->attachment)
                    : null,
                "content" => $this->attachment,
            ]);

            try {
                // Handle Livewire file upload
                $file = $this->attachment;

                // Check if it's an UploadedFile object
                if (!$file instanceof \Illuminate\Http\UploadedFile) {
                    session()->flash(
                        "error",
                        "Invalid file upload - not an UploadedFile",
                    );
                    return;
                }

                $fileName = time() . "_" . $file->getClientOriginalName();
                $filePath = $file->storeAs(
                    "chat-attachments",
                    $fileName,
                    "public",
                );

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
            } catch (\Exception $e) {
                \Log::error("File upload error:", [
                    "message" => $e->getMessage(),
                    "trace" => $e->getTraceAsString(),
                ]);
                session()->flash(
                    "error",
                    "File upload failed: " . $e->getMessage(),
                );
                return;
            }
        }

        $message = Message::create([
            "message_id" => $messageId,
            "sender_id" => $adminUserId,
            "receiver_id" => $this->receiver->user_id,
            "message" => $this->newMessage ?: "",
            "attachment" => $attachmentData,
            "conversationsId" => $this->conversationId,
            "seen" => false,
        ]);

        // Add to local messages array for immediate UI update
        $this->messages[] = [
            "id" => $message->id,
            "message_id" => $message->message_id,
            "sender_id" => $message->sender_id,
            "receiver_id" => $message->receiver_id,
            "message" => $message->message,
            "attachment" => $message->attachment,
            "seen" => $message->seen,
            "created_at" => $message->created_at->toISOString(),
            "conversationsId" => $message->conversationsId,
        ];

        // Reset input
        $this->newMessage = "";
        $this->attachment = null;

        // Emit socket event for real-time delivery (matching server format)
        $this->dispatch("emitSocketMessage", [
            "event" => "new-message",
            "data" => [
                "message_id" => $messageId,
                "sender_id" => $adminUserId,
                "receiver_id" => $this->receiver->user_id,
                "conversationId" => $this->conversationId,
                "message" => $this->newMessage ?: "",
                "attachment" => $attachmentData,
                "story_reply" => null,
            ],
        ]);

        // Dispatch local event for conversations list update
        $this->dispatch("messageSent", [
            "conversationId" => $this->conversationId,
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

    // Remove this method as it's not needed

    #[On("conversationSelected")]
    public function handleConversationSelected($conversationId)
    {
        $this->conversationId = $conversationId;
        $this->loadConversation();
        $this->loadMessages();
        $this->cursor = null;
        $this->hasMore = true;
    }

    #[On("messageReceived")]
    public function handleNewMessage($data)
    {
        if (
            isset($data["conversationId"]) &&
            $data["conversationId"] === $this->conversationId
        ) {
            // Add the new message to the messages array
            $newMessage = [
                "id" => $data["message"]["id"] ?? time(),
                "message_id" => $data["message"]["message_id"] ?? "",
                "sender_id" => $data["message"]["sender_id"] ?? "",
                "receiver_id" => $data["message"]["receiver_id"] ?? "",
                "message" => $data["message"]["message"] ?? "",
                "attachment" => $data["message"]["attachment"] ?? null,
                "seen" => false,
                "created_at" =>
                    $data["message"]["created_at"] ?? now()->toISOString(),
                "conversationsId" => $data["conversationId"],
            ];

            $this->messages[] = $newMessage;
            $this->markMessagesAsSeen();

            // Refresh the view
            $this->dispatch("messageAdded");
        }
    }

    public function loadMoreMessages()
    {
        if ($this->hasMore && !$this->isLoading) {
            $this->loadMessages(true);
        }
    }

    public function isMessageFromAdmin($senderId)
    {
        return $senderId === Auth::user()->user_id;
    }

    public function formatMessageTime($timestamp)
    {
        return \Carbon\Carbon::parse($timestamp)->format("H:i");
    }

    public function formatMessageDate($timestamp)
    {
        $messageDate = \Carbon\Carbon::parse($timestamp);
        $today = \Carbon\Carbon::today();
        $yesterday = \Carbon\Carbon::yesterday();

        if ($messageDate->isSameDay($today)) {
            return "Today";
        } elseif ($messageDate->isSameDay($yesterday)) {
            return "Yesterday";
        } else {
            return $messageDate->format("M j, Y");
        }
    }

    // Remove this method as attachments are handled directly in view

    public function getMessagesByDate()
    {
        $groupedMessages = collect($this->messages)->groupBy(function (
            $message,
        ) {
            return \Carbon\Carbon::parse($message["created_at"])->format(
                "Y-m-d",
            );
        });

        return $groupedMessages;
    }

    public function render()
    {
        return view("livewire.chat.messages", [
            "groupedMessages" => $this->getMessagesByDate(),
        ]);
    }

    public function refreshMessages()
    {
        if ($this->conversationId) {
            $this->loadMessages();
        }
    }

    #[On("conversationRefresh")]
    public function handleConversationRefresh()
    {
        $this->refreshMessages();
    }
}
