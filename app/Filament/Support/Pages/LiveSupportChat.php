<?php

namespace App\Filament\Support\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;

class LiveSupportChat extends Page
{
    public $sessions = [];
    public $activeSession = null;
    public $messages = [];
    public $newMessage = "";
    public $agentId;
    public $isConnected = false;
    public $connectionError = null;
    public $isLoading = false;

    // Define the properties for Livewire
    protected $rules = [
        "newMessage" => "required|string|min:1|max:1000",
    ];

    protected $validationMessages = [
        "newMessage.required" => "Message cannot be empty",
        "newMessage.min" => "Message must be at least 1 character",
        "newMessage.max" => "Message cannot exceed 1000 characters",
    ];

    protected $listeners = [
        "newMessage" => "onNewMessage",
        "newAgentMessage" => "onNewAgentMessage",
        "sessionEnded" => "onSessionEnded",
        "refreshSessions" => "fetchSessions",
        "clearError" => "clearError",
        "socketConnected" => "onSocketConnected",
        "socketDisconnected" => "onSocketDisconnected",
    ];

    protected static ?string $navigationIcon = "entypo-chat";
    protected static ?string $navigationGroup = "Supports";
    protected static ?string $navigationLabel = "Live Support Chat";
    protected static string $view = "livewire.support.support-chat-agent";

    public function onNewMessage($data)
    {
        if (
            $this->activeSession &&
            isset($data["message"]) &&
            $data["message"]["sessionId"] === $this->activeSession
        ) {
            $this->messages[] = $data["message"];
            $this->dispatch("scroll-to-bottom");
        }
    }

    public function onNewAgentMessage($msg)
    {
        if (
            $this->activeSession &&
            $msg["sessionId"] === $this->activeSession
        ) {
            $this->messages[] = $msg;
            $this->dispatch("scroll-to-bottom");
        }
    }

    public function onSessionEnded($data)
    {
        if (
            $this->activeSession &&
            $data["sessionId"] === $this->activeSession
        ) {
            $this->resetSessionData();
            $this->fetchSessions();
        }
    }

    public function onSocketConnected()
    {
        $this->isConnected = true;
        $this->connectionError = null;
        $this->fetchSessions();
    }

    public function onSocketDisconnected()
    {
        $this->isConnected = false;
        $this->connectionError = "Socket connection lost";
    }

    public function mount()
    {
        $this->agentId = \Illuminate\Support\Facades\Auth::user()->id;
        $this->connectionError = null;
        $this->isConnected = false;
        $this->isLoading = false;
        $this->activeSession = null;
        $this->sessions = [];
        $this->messages = [];
        $this->newMessage = "";
        $this->fetchSessions();
    }

    public function hydrate()
    {
        // Fallback initialization for edge cases
        if (!isset($this->activeSession)) {
            $this->activeSession = null;
        }
        if (!isset($this->sessions)) {
            $this->sessions = [];
        }
        if (!isset($this->messages)) {
            $this->messages = [];
        }
        if (!isset($this->newMessage)) {
            $this->newMessage = "";
        }
        if (!isset($this->isLoading)) {
            $this->isLoading = false;
        }
        if (!isset($this->isConnected)) {
            $this->isConnected = false;
        }
        if (!isset($this->connectionError)) {
            $this->connectionError = null;
        }
        if (!isset($this->agentId)) {
            $this->agentId = \Illuminate\Support\Facades\Auth::user()->id;
        }
    }

    public function resetSessionData()
    {
        $this->activeSession = null;
        $this->messages = [];
        $this->connectionError = null;
        $this->resetErrorBag();
    }

    public function testVariables()
    {
        // Test method to verify all variables are properly initialized
        $status = [
            "agentId" => $this->agentId ?? "NOT_SET",
            "activeSession" => $this->activeSession ?? "NOT_SET",
            "sessions" => is_array($this->sessions) ? "ARRAY_SET" : "NOT_ARRAY",
            "messages" => is_array($this->messages) ? "ARRAY_SET" : "NOT_ARRAY",
            "newMessage" => $this->newMessage ?? "NOT_SET",
            "isLoading" => isset($this->isLoading)
                ? ($this->isLoading
                    ? "TRUE"
                    : "FALSE")
                : "NOT_SET",
            "isConnected" => isset($this->isConnected)
                ? ($this->isConnected
                    ? "TRUE"
                    : "FALSE")
                : "NOT_SET",
            "connectionError" => $this->connectionError ?? "NOT_SET",
        ];

        $this->connectionError = "Test Status: " . json_encode($status);
        return $status;
    }

    public function clearError()
    {
        $this->connectionError = null;
        $this->resetErrorBag();
    }

    public function refreshAndClearError()
    {
        $this->clearError();
        $this->resetErrorBag();
        $this->fetchSessions();
    }

    public function fetchSessions()
    {
        $this->isLoading = true;
        try {
            $backendUrl = env("BACKEND_URL");

            $response = Http::timeout(10)->get(
                $backendUrl . "/api/support/session?status=waiting"
            );

            if ($response->successful()) {
                $this->sessions = $response->json();
                $this->connectionError = null;
                // Don't override socket connection status
                if (!$this->isConnected) {
                    $this->isConnected = true;
                }
            } else {
                $this->connectionError =
                    "Failed to fetch sessions: " . $response->status();
                $this->sessions = [];
            }
        } catch (\Exception $e) {
            $this->connectionError = "Connection error: " . $e->getMessage();
            $this->sessions = [];
            $this->isConnected = false;
        } finally {
            $this->isLoading = false;
        }
    }

    public function joinSession($sessionId)
    {
        if (!$sessionId) {
            return;
        }

        $this->isLoading = true;
        try {
            $backendUrl = env("BACKEND_URL");

            // Mark as joined in backend
            $joinResponse = Http::timeout(10)->patch(
                $backendUrl . "/api/support/session/join",
                [
                    "sessionId" => $sessionId,
                    "agentId" => $this->agentId,
                ]
            );

            if (!$joinResponse->successful()) {
                $this->connectionError =
                    "Failed to join session: " . $joinResponse->status();
                return;
            }

            // Set active session only after successful join
            $this->activeSession = $sessionId;

            // Fetch messages
            $response = Http::timeout(10)->get(
                $backendUrl . "/api/support/messages",
                [
                    "sessionId" => $sessionId,
                ]
            );
            if ($response->successful()) {
                $this->messages = $response->json();
            }

            $this->dispatch("session-joined", [
                "sessionId" => $sessionId,
            ]);
            $this->connectionError = null;
        } catch (\Exception $e) {
            $this->connectionError =
                "Error joining session: " . $e->getMessage();
            $this->activeSession = null;
        } finally {
            $this->isLoading = false;
        }
    }

    public function leaveSession()
    {
        if ($this->activeSession) {
            try {
                $backendUrl = env("BACKEND_URL");
                Http::timeout(10)->patch(
                    $backendUrl . "/api/support/session/leave",
                    [
                        "sessionId" => $this->activeSession,
                        "agentId" => $this->agentId,
                    ]
                );
                $this->dispatch("session-left", [
                    "sessionId" => $this->activeSession,
                ]);
            } catch (\Exception $e) {
                $this->connectionError =
                    "Error leaving session: " . $e->getMessage();
            }
        }
        $this->resetSessionData();
        $this->fetchSessions();
    }

    public function endSession()
    {
        if ($this->activeSession) {
            try {
                $backendUrl = env("BACKEND_URL");
                Http::timeout(10)->patch(
                    $backendUrl . "/api/support/session/end",
                    [
                        "sessionId" => $this->activeSession,
                    ]
                );
                $this->dispatch("session-ended", [
                    "sessionId" => $this->activeSession,
                ]);
            } catch (\Exception $e) {
                $this->connectionError =
                    "Error ending session: " . $e->getMessage();
            }
        }
        $this->resetSessionData();
        $this->fetchSessions();
    }

    public function sendMessage()
    {
        if (!$this->activeSession || !$this->newMessage || $this->isLoading) {
            return;
        }

        // Validate message
        try {
            $this->validate(
                [
                    "newMessage" => "required|string|min:1|max:1000",
                ],
                $this->validationMessages
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->connectionError =
                "Message validation failed: " .
                collect($e->validator->errors()->all())->implode(", ");
            return;
        }

        $this->isLoading = true;
        $messageToSend = trim($this->newMessage);
        $this->newMessage = ""; // Clear immediately for better UX

        try {
            $backendUrl = env("BACKEND_URL");

            $response = Http::timeout(10)->post(
                $backendUrl . "/api/support/message",
                [
                    "sessionId" => $this->activeSession,
                    "sender" => "agent",
                    "senderId" => $this->agentId,
                    "message" => $messageToSend,
                ]
            );

            if ($response->successful()) {
                // Message will be added via socket event, but also add locally for immediate display
                $this->messages[] = [
                    "sessionId" => $this->activeSession,
                    "sender" => "agent",
                    "senderId" => $this->agentId,
                    "message" => $messageToSend,
                    "createdAt" => now()->toISOString(),
                ];
                $this->connectionError = null;
            } else {
                $this->connectionError =
                    "Failed to send message: " . $response->status();
                // Restore message on failure
                $this->newMessage = $messageToSend;
            }
        } catch (\Exception $e) {
            $this->connectionError =
                "Error sending message: " . $e->getMessage();
            // Restore message on failure
            $this->newMessage = $messageToSend;
        } finally {
            $this->isLoading = false;
        }
    }

    public function checkEnvironmentConfiguration()
    {
        $backendUrl = env("BACKEND_URL");
        $status = [
            "backend_url" => "Set to localhost:3009",
            "backend_url_value" => $backendUrl,
            "connection_test" => "Not tested",
        ];

        try {
            $response = Http::timeout(5)->get($backendUrl . "/api/health");
            $status["connection_test"] = $response->successful()
                ? "Success"
                : "Failed";
        } catch (\Exception $e) {
            $status["connection_test"] = "Error: " . $e->getMessage();
        }

        $this->connectionError = "Environment Check: " . json_encode($status);
        return $status;
    }

    public function getSessionsProperty()
    {
        return collect($this->sessions)
            ->map(function ($session) {
                $session["timeAgo"] = isset($session["startedAt"])
                    ? \Carbon\Carbon::parse(
                        $session["startedAt"]
                    )->diffForHumans()
                    : "Recently";
                return $session;
            })
            ->toArray();
    }

    public function getMessagesProperty()
    {
        return collect($this->messages)
            ->map(function ($message) {
                $message["formattedTime"] = isset($message["createdAt"])
                    ? \Carbon\Carbon::parse($message["createdAt"])->format(
                        "H:i"
                    )
                    : "";
                return $message;
            })
            ->toArray();
    }

    public function isSessionActive($sessionId)
    {
        return $this->activeSession === $sessionId;
    }

    public function getConnectionStatus()
    {
        if ($this->isConnected) {
            return "connected";
        } elseif ($this->connectionError) {
            return "error";
        } else {
            return "disconnected";
        }
    }

    public function hasWaitingSessions()
    {
        return !empty($this->sessions);
    }
}
