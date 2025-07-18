<div>
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-sm border mb-6">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6 rounded-t-lg">
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-2xl font-bold flex items-center gap-3">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                Live Support Dashboard
                            </h1>
                            <p class="text-blue-100 mt-1">Manage customer support conversations in real-time</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <!-- Socket Connection Status -->
                            <div class="flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full cursor-pointer" onclick="window.dispatchEvent(new CustomEvent('manual-reconnect'))">
                                <div id="connection-indicator" class="w-3 h-3 bg-red-400 rounded-full animate-pulse"></div>
                                <span id="connection-status" class="text-sm font-medium">Connecting...</span>
                            </div>
                            <!-- Agent Info -->
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="text-sm">
                                    <div class="font-medium">Agent {{ $agentId ?? 'Unknown' }}</div>
                                    <div class="text-blue-100">{{ isset($isConnected) && $isConnected ? 'Online' : 'Offline' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Stats Bar -->
            <div class="bg-gray-50 px-6 py-4 border-b">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-6">
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-yellow-400 rounded-full"></div>
                            <span class="text-sm font-medium text-gray-700">
                                {{ count($sessions ?? []) }} Waiting
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-green-400 rounded-full"></div>
                            <span class="text-sm font-medium text-gray-700">
                                {{ isset($activeSession) && $activeSession ? '1' : '0' }} Active
                            </span>
                        </div>
                    </div>
                    <button wire:click="refreshAndClearError" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50" {{ isset($isLoading) && $isLoading ? 'disabled' : '' }}>
                        @if(isset($isLoading) && $isLoading)
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Refreshing...
                        @else
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Refresh
                        @endif
                    </button>
                </div>
            </div>
        </div>

        <!-- Error Alert -->
        @if(isset($connectionError) && $connectionError)
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Connection Error</h3>
                    <p class="mt-1 text-sm text-red-700">{{ $connectionError }}</p>
                    <div class="mt-2">
                        <button wire:click="refreshAndClearError" class="text-sm text-red-600 hover:text-red-800 font-medium underline">
                            Try Again
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Sessions List -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="p-6 border-b">
                        <h2 class="text-lg font-semibold text-gray-900">Waiting Sessions</h2>
                        <p class="text-sm text-gray-500 mt-1">Click on a session to start helping</p>
                    </div>
                    <div class="divide-y divide-gray-200 max-h-[600px] overflow-y-auto">
                        @if(empty($sessions ?? []))
                        <div class="p-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <h3 class="mt-4 text-sm font-medium text-gray-900">No waiting sessions</h3>
                            <p class="mt-2 text-sm text-gray-500">All customers are currently being helped or there are no active requests.</p>
                        </div>
                        @else
                        @foreach($sessions ?? [] as $session)
                        <div class="p-4 hover:bg-gray-50 cursor-pointer transition-colors duration-200 {{ isset($activeSession) && $activeSession === ($session['_id'] ?? null) ? 'bg-blue-50 border-r-4 border-blue-500' : '' }}" wire:click="joinSession('{{ $session['_id'] ?? '' }}')">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">
                                            User {{ substr($session['userId'] ?? 'Unknown', 0, 8) }}...
                                        </h4>
                                        <p class="text-xs text-gray-500">
                                            Started {{ isset($session['startedAt']) ? \Carbon\Carbon::parse($session['startedAt'])->diffForHumans() : 'recently' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ $session['status'] ?? 'waiting' }}
                                    </span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border">
                    @if(isset($activeSession) && $activeSession)
                    <!-- Chat Header -->
                    <div class="p-6 border-b bg-gradient-to-r from-gray-50 to-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Active Support Session</h3>
                                    <p class="text-sm text-gray-500" id="user-typing-indicator">
                                        Session ID: {{ $activeSession }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button wire:click="leaveSession" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50" {{ isset($isLoading) && $isLoading ? 'disabled' : '' }}>
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Leave
                                </button>
                                <button wire:click="endSession" class="inline-flex items-center px-3 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-red-600 hover:bg-red-700 disabled:opacity-50" {{ isset($isLoading) && $isLoading ? 'disabled' : '' }}>
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    End Session
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Messages Area -->
                    <div class="p-6 h-96 overflow-y-auto bg-gray-50" id="messages-container">
                        @if(empty($messages ?? []))
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <p class="mt-4 text-gray-500">No messages yet. Start the conversation!</p>
                        </div>
                        @else
                        <div class="space-y-4" id="messages-list">
                            @foreach($messages ?? [] as $message)
                            <div class="flex {{ ($message['sender'] ?? '') === 'agent' ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-xs lg:max-w-md">
                                    <div class="flex items-center gap-2 mb-1">
                                        @if(($message['sender'] ?? '') === 'agent')
                                        <span class="text-xs font-medium text-blue-600">You</span>
                                        @else
                                        <span class="text-xs font-medium text-gray-600">Customer</span>
                                        @endif
                                        @if(isset($message['createdAt']) && $message['createdAt'])
                                        <span class="text-xs text-gray-400">
                                            {{ \Carbon\Carbon::parse($message['createdAt'])->format('H:i') }}
                                        </span>
                                        @endif
                                    </div>
                                    <div class="px-4 py-2 rounded-lg shadow-sm {{ ($message['sender'] ?? '') === 'agent' ? 'bg-blue-600 text-white' : 'bg-white text-gray-900 border' }}">
                                        <p class="text-sm">{{ $message['message'] ?? '' }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <!-- Message Input -->
                    <div class="p-6 border-t bg-white">
                        @if($errors->has('newMessage'))
                        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">
                            {{ $errors->first('newMessage') }}
                        </div>
                        @endif
                        <form wire:submit.prevent="sendMessage" class="flex gap-3">
                            <input type="text" wire:model.defer="newMessage" class="flex-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50 @error('newMessage') border-red-300 @enderror" placeholder="Type your message here..." x-data="{
                                            typingTimeout: null,
                                            isTyping: false,
                                            sessionId: '{{ $activeSession }}',
                                            agentId: '{{ auth()->user()->id }}'
                                       }" x-on:input="() => {
                                            if (!isTyping) {
                                                isTyping = true;
                                                window.dispatchEvent(new CustomEvent('agent-typing', { detail: { isTyping: true, sessionId: sessionId }}));
                                            }
                                            clearTimeout(typingTimeout);
                                            typingTimeout = setTimeout(() => {
                                                isTyping = false;
                                                window.dispatchEvent(new CustomEvent('agent-typing', { detail: { isTyping: false, sessionId: sessionId }}));
                                            }, 2000);
                                       }" x-on:keydown="() => {
                                            if (!isTyping) {
                                                isTyping = true;
                                                window.dispatchEvent(new CustomEvent('agent-typing', { detail: { isTyping: true, sessionId: sessionId }}));
                                            }
                                            clearTimeout(typingTimeout);
                                            typingTimeout = setTimeout(() => {
                                                isTyping = false;
                                                window.dispatchEvent(new CustomEvent('agent-typing', { detail: { isTyping: false, sessionId: sessionId }}));
                                            }, 2000);
                                       }" {{ isset($isLoading) && $isLoading ? 'disabled' : '' }}>
                            <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50" {{ isset($isLoading) && $isLoading ? 'disabled' : '' }}>
                                @if(isset($isLoading) && $isLoading)
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Sending...
                                @else
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Send
                                @endif
                            </button>
                        </form>
                    </div>
                    @else
                    <!-- No Session Selected -->
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No active session</h3>
                        <p class="mt-2 text-sm text-gray-500">Select a waiting session from the left panel to start helping a customer.</p>
                    </div>
                    @endif
                </div>
            </div>
            </div>
        </div>

        <!-- Socket.IO Connection -->
        </div>
        <script src="http://localhost:3009/socket.io/socket.io.js"></script>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            let socket;
            let isConnected = false;
            let reconnectAttempts = 0;
            const maxReconnectAttempts = 5;
            const reconnectDelay = 1000;

            const connectionIndicator = document.getElementById('connection-indicator');
            const connectionStatus = document.getElementById('connection-status');

            function updateConnectionStatus(connected, status) {
                isConnected = connected;
                if (connectionIndicator && connectionStatus) {
                    if (connected) {
                        connectionIndicator.className = 'w-3 h-3 bg-green-400 rounded-full';
                        connectionStatus.textContent = status || 'Connected';
                    } else {
                        connectionIndicator.className = 'w-3 h-3 bg-red-400 rounded-full animate-pulse';
                        connectionStatus.textContent = status || 'Disconnected';
                    }
                }
            }

            function connectSocket() {
                const backendUrl = 'http://localhost:3009';

                updateConnectionStatus(false, 'Connecting...');

                socket = io(backendUrl, {
                    query: {
                        username: 'support-agent-{{ auth()->user()->id }}'
                    }
                    , transports: ['websocket', 'polling']
                    , reconnection: true
                    , reconnectionAttempts: maxReconnectAttempts
                    , reconnectionDelay: reconnectDelay
                    , timeout: 10000
                    , forceNew: true
                });

                socket.on('connect', function() {
                    console.log('✅ Socket connected to:', backendUrl);
                    updateConnectionStatus(true, 'Connected');
                    reconnectAttempts = 0;

                    // Notify Livewire component
                    if (window.Livewire) {
                        window.Livewire.dispatch('socketConnected');
                    }

                    // Request waiting list
                    socket.emit('support:waiting-list');
                });

                socket.on('disconnect', function(reason) {
                    console.log('❌ Socket disconnected:', reason);
                    updateConnectionStatus(false, 'Disconnected');

                    // Notify Livewire component
                    if (window.Livewire) {
                        window.Livewire.dispatch('socketDisconnected');
                    }
                });

                socket.on('connect_error', function(error) {
                    console.error('🚫 Socket connection error:', error);
                    reconnectAttempts++;

                    if (reconnectAttempts >= maxReconnectAttempts) {
                        updateConnectionStatus(false, 'Connection Failed');
                        setTimeout(() => {
                            console.log('🔄 Attempting manual reconnection...');
                            connectSocket();
                        }, 5000);
                    } else {
                        updateConnectionStatus(false, `Retrying (${reconnectAttempts}/${maxReconnectAttempts})`);
                    }
                });

                socket.on('reconnect', function(attemptNumber) {
                    console.log('🔄 Socket reconnected after', attemptNumber, 'attempts');
                    updateConnectionStatus(true, 'Reconnected');
                    reconnectAttempts = 0;
                });

                socket.on('reconnect_error', function(error) {
                    console.error('🔄❌ Socket reconnection error:', error);
                    updateConnectionStatus(false, 'Reconnection Failed');
                });

                socket.on('reconnect_failed', function() {
                    console.error('🔄❌ All reconnection attempts failed');
                    updateConnectionStatus(false, 'Reconnection Failed');
                    // Try again after 10 seconds
                    setTimeout(() => {
                        console.log('🔄 Final reconnection attempt...');
                        connectSocket();
                    }, 10000);
                });

                // Support-specific events
                socket.on('support:message', function(message) {
                    console.log('📨 New support message:', message);
                    if (window.Livewire) {
                        // Add message to the current component's messages array
                        window.Livewire.dispatch('newMessage', {
                            message
                        });

                        // Also directly add to messages for immediate display
                        const messagesContainer = document.getElementById('messages-container');
                        if (messagesContainer && message) {
                            // Only add if message is not from current agent to avoid duplicates
                            if (message.sender !== 'agent') {
                                // Create message element
                                const messageDiv = document.createElement('div');
                                messageDiv.className = `flex ${message.sender === 'agent' ? 'justify-end' : 'justify-start'}`;

                                const isAgent = message.sender === 'agent';
                                const timestamp = new Date(message.createdAt).toLocaleTimeString('en-US', {
                                    hour: '2-digit',
                                    minute: '2-digit'
                                });

                                messageDiv.innerHTML = `
                                    <div class="max-w-xs lg:max-w-md">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-xs font-medium ${isAgent ? 'text-blue-600' : 'text-gray-600'}">
                                                ${isAgent ? 'You' : 'Customer'}
                                            </span>
                                            <span class="text-xs text-gray-400">${timestamp}</span>
                                        </div>
                                        <div class="px-4 py-2 rounded-lg shadow-sm ${isAgent ? 'bg-blue-600 text-white' : 'bg-white text-gray-900 border'}">
                                            <p class="text-sm">${message.message}</p>
                                        </div>
                                    </div>
                                `;

                                // Add to messages container
                                const messagesArea = messagesContainer.querySelector('.space-y-4') || messagesContainer;
                                messagesArea.appendChild(messageDiv);
                            }
                        }

                        // Auto-scroll to bottom
                        setTimeout(() => {
                            const container = document.getElementById('messages-container');
                            if (container) {
                                container.scrollTop = container.scrollHeight;
                            }
                        }, 100);
                    }
                });

                socket.on('support:waiting-list-update', function() {
                    console.log('📋 Waiting list updated');
                    if (window.Livewire) {
                        window.Livewire.dispatch('refreshSessions');
                    }
                });

                socket.on('support:session-ended', function(data) {
                    console.log('🏁 Session ended:', data);
                    if (window.Livewire) {
                        window.Livewire.dispatch('sessionEnded', data);
                    }
                });

                socket.on('support:agent-joined', function(data) {
                    console.log('👤 Agent joined:', data);
                });

                socket.on('support:agent-left', function(data) {
                    console.log('👤 Agent left:', data);
                });

                socket.on('support:typing', function(data) {
                    console.log('👤 Customer typing:', data);
                    const typingIndicator = document.getElementById('user-typing-indicator');
                    if (typingIndicator) {
                        if (data.isTyping) {
                            typingIndicator.innerHTML = '<span class="italic animate-pulse text-blue-600">Customer is typing...</span>';
                        } else {
                            typingIndicator.innerHTML = 'Session ID: {{ $activeSession }}';
                        }
                    }
                });

                socket.on('support:agent-typing', function(data) {
                    console.log('👤 Agent typing feedback:', data);
                    // This is feedback from the server about agent typing
                    // Can be used for debugging or additional UI feedback
                });

                socket.on('support:waiting-list', function(sessions) {
                    console.log('📋 Waiting list:', sessions);
                    // Update the sessions list if needed
                });
            }

            // Initialize connection
            connectSocket();

            // Livewire event listeners
            document.addEventListener('livewire:session-joined', function(event) {
                console.log('🎯 Joining session:', event.detail.sessionId);
                if (socket && socket.connected) {
                    socket.emit('support:join', {
                        sessionId: event.detail.sessionId
                        , agentId: '{{ auth()->user()->id }}'
                    });

                    // Clear existing messages when joining new session
                    const messagesContainer = document.getElementById('messages-container');
                    if (messagesContainer) {
                        const messagesList = messagesContainer.querySelector('#messages-list');
                        if (messagesList) {
                            messagesList.innerHTML = '';
                        }
                    }
                } else {
                    console.warn('Socket not connected, cannot join session');
                }
            });

            document.addEventListener('livewire:session-left', function(event) {
                console.log('🚪 Leaving session:', event.detail.sessionId);
                if (socket && socket.connected) {
                    socket.emit('support:leave', {
                        sessionId: event.detail.sessionId
                        , agentId: '{{ auth()->user()->id }}'
                    });
                } else {
                    console.warn('Socket not connected, cannot leave session');
                }
            });

            document.addEventListener('livewire:session-ended', function(event) {
                console.log('🏁 Ending session:', event.detail.sessionId);
                if (socket && socket.connected) {
                    socket.emit('support:end', {
                        sessionId: event.detail.sessionId
                    });
                } else {
                    console.warn('Socket not connected, cannot end session');
                }
            });

            // Listen for agent typing custom event
            window.addEventListener('agent-typing', function(event) {
                console.log('🎯 Agent typing event:', event.detail);
                if (socket && socket.connected) {
                    socket.emit('support:agent-typing', {
                        sessionId: event.detail.sessionId
                        , isTyping: event.detail.isTyping
                    });
                }
            });

            // Manual reconnection button
            document.addEventListener('livewire:manual-reconnect', function() {
                console.log('🔄 Manual reconnection requested');
                if (socket) {
                    socket.disconnect();
                }
                setTimeout(() => {
                    connectSocket();
                }, 1000);
            });

            // Auto-scroll to bottom when new messages arrive
            document.addEventListener('livewire:scroll-to-bottom', function() {
                setTimeout(() => {
                    const container = document.getElementById('messages-container');
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                }, 100);
            });

            // Periodic refresh of sessions
            setInterval(function() {
                if (window.Livewire && socket && socket.connected) {
                    console.log('🔄 Auto-refreshing sessions...');
                    window.Livewire.dispatch('refreshSessions');
                    socket.emit('support:waiting-list');
                }
            }, 30000); // Every 30 seconds

            // Heartbeat to keep connection alive
            setInterval(function() {
                if (socket && socket.connected) {
                    socket.emit('ping');
                }
            }, 10000); // Every 10 seconds

            // Handle page visibility change
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    console.log('👁️ Page hidden, maintaining connection...');
                } else {
                    console.log('👁️ Page visible, refreshing data...');
                    if (window.Livewire) {
                        window.Livewire.dispatch('refreshSessions');
                    }
                }
            });

            // Cleanup on page unload
            window.addEventListener('beforeunload', function() {
                if (socket) {
                    socket.disconnect();
                }
            });
        </script>
</div>
