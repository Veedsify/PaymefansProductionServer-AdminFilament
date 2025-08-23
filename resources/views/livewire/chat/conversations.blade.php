<div
    class="py-5 col-span-5 lg:col-span-3 bg-white dark:bg-gray-800 lg:border-r lg:static fixed w-full left-0 top-16 z-[10] h-screen overflow-hidden">
    <div class="p-3 pb-4 border-b lg:p-4 dark:border-gray-700">
        <div class="flex items-center">
            <img src="{{ auth()->user()->profile_image }}" alt="Profile Picture"
                class="w-16 h-16 border-2 border-purple-500 rounded-full">
            <div class="flex items-center justify-between">
                <div class="ml-4">
                    <h2 class="text-lg font-semibold text-gray-800 lg:text-xl dark:text-gray-200">
                        {{ auth()->user()->name }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Online</p>
                </div>
            </div>
        </div>
        <div class="mt-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">Manage your conversations and users from this dashboard. Keep everything
                organized and under control.</p>
        </div>
    </div>

    <div x-data="{ activeTab: 'messages' }" class="mt-5">
        <div class="flex text-center border-b space-x-4 dark:border-gray-700">
            <button @click="activeTab = 'messages'" :class="{ 'border-b-2 border-purple-500': activeTab === 'messages' }"
                class="flex-1 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 mr-2" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                    <path
                        d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
                </svg>
                Messages
            </button>
            <button @click="activeTab = 'users'" :class="{ 'border-b-2 border-purple-500': activeTab === 'users' }"
                class="flex-1 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 mr-2" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path
                        d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                </svg>
                Users
            </button>
        </div>

        <!-- Messages Tab -->
        <div x-show="activeTab === 'messages'" class="mt-4 max-h-[60vh] overflow-y-auto sleek_scrollbar">
            @if($isLoading)
                <div class="flex justify-center p-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-500"></div>
                </div>
            @elseif(count($conversations) > 0)
                @foreach($conversations as $conversation)
                    @php
                        $unreadCount = $this->getConversationUnreadCount($conversation);
                        $isSelected = $selectedConversationId === $conversation['conversation_id'];
                    @endphp
                    <div wire:click="selectConversation('{{ $conversation['conversation_id'] }}')"
                        class="flex items-center p-4 bg-white rounded-lg cursor-pointer dark:bg-gray-800 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200
                        {{ $isSelected ? 'bg-purple-50 dark:bg-purple-900 border-l-4 border-purple-500 shadow-md' : '' }}
                        {{ $unreadCount > 0 ? 'ring-2 ring-purple-200 dark:ring-purple-800' : '' }}"
                        wire:loading.class="opacity-50"
                        wire:target="selectConversation">

                        <div class="relative flex-shrink-0">
                            <img src="{{ $conversation['conversation']['profile_image'] ?? '/default-avatar.png' }}"
                                alt="User Avatar"
                                class="w-12 h-12 border-2 {{ $isSelected ? 'border-purple-600' : 'border-purple-500' }} rounded-full">
                            <span class="absolute bottom-0 right-0 w-3 h-3 bg-gray-400 border-2 border-white rounded-full dark:border-gray-800"
                                data-username="{{ $conversation['conversation']['username'] }}"></span>
                        </div>

                        <div class="flex-grow ml-4 min-w-0">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-semibold text-gray-800 dark:text-white truncate">
                                    {{ $conversation['conversation']['name'] }}
                                </h4>
                                <div class="flex items-center space-x-2">
                                    @if($conversation['lastMessage'])
                                        <span class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($conversation['lastMessage']['created_at'])->format('H:i') }}
                                        </span>
                                    @endif
                                    @if($unreadCount > 0)
                                        <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-purple-500 rounded-full">
                                            {{ $unreadCount }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if($conversation['lastMessage'])
                                <div class="flex items-center justify-between mt-1">
                                    <p class="text-sm text-gray-600 truncate dark:text-gray-400 {{ $unreadCount > 0 ? 'font-semibold' : '' }}">
                                        @if($conversation['lastMessage']['sender_id'] === auth()->user()->user_id)
                                            <span class="text-purple-600 dark:text-purple-400">You:</span>
                                        @endif
                                        {{ $conversation['lastMessage']['message'] ?: '📎 Attachment' }}
                                    </p>
                                    @if($conversation['lastMessage']['sender_id'] === auth()->user()->user_id)
                                        <div class="flex-shrink-0 ml-2">
                                            @if($conversation['lastMessage']['seen'])
                                                <span class="text-purple-500 text-xs">✓✓</span>
                                            @else
                                                <span class="text-gray-400 text-xs">✓</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400 italic">No messages yet</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="flex flex-col items-center justify-center p-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mb-4 text-gray-400 dark:text-gray-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-2">No conversations yet</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Start a conversation with a user from the Users tab</p>
                </div>
            @endif
        </div>

        <!-- Users Tab -->
        <div x-show="activeTab === 'users'" class="mt-4 space-y-2 max-h-[60vh] overflow-y-auto sleek_scrollbar">
            @foreach ($users as $user)
                <div wire:click="startNewConversation('{{ $user->user_id }}')"
                    class="flex items-center p-4 bg-white rounded-lg cursor-pointer dark:bg-gray-800 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 hover:shadow-md"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                    wire:target="startNewConversation">
                    <div class="relative flex-shrink-0">
                        <img src="{{ $user->profile_image ?? '/default-avatar.png' }}" alt="User Avatar"
                            class="w-12 h-12 border-2 border-purple-500 rounded-full">
                        <span class="absolute bottom-0 right-0 w-3 h-3 bg-gray-400 border-2 border-white rounded-full dark:border-gray-800"
                            data-username="{{ $user->username }}"></span>
                    </div>
                    <div class="flex-grow ml-4 min-w-0">
                        <h4 class="text-sm font-semibold text-gray-800 dark:text-white truncate">
                            {{ $user->name }}
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 truncate">
                            {{ $user->username }}
                        </p>
                        @if($user->role)
                            <span class="inline-block px-2 py-1 text-xs font-medium text-purple-600 bg-purple-100 rounded-full dark:bg-purple-900 dark:text-purple-300 mt-1">
                                {{ ucfirst($user->role) }}
                            </span>
                        @endif
                    </div>
                    <div class="flex-shrink-0 flex items-center">
                        <div wire:loading.remove wire:target="startNewConversation">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 dark:text-gray-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <div wire:loading wire:target="startNewConversation">
                            <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-purple-500"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Refresh Button -->
        <div class="mt-4 p-4 border-t dark:border-gray-700">
            <button wire:click="refreshConversations"
                class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-purple-600 bg-purple-100 rounded-lg hover:bg-purple-200 dark:bg-purple-900 dark:text-purple-300 dark:hover:bg-purple-800 transition-all duration-200"
                wire:loading.class="opacity-50 cursor-not-allowed"
                wire:target="refreshConversations">
                <div wire:loading.remove wire:target="refreshConversations">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Refresh Conversations
                </div>
                <div wire:loading wire:target="refreshConversations" class="flex items-center">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-purple-600 mr-2"></div>
                    Refreshing...
                </div>
            </button>
        </div>
    </div>
</div>

<script>
    // Handle real-time conversation updates
    document.addEventListener('livewire:initialized', () => {
        // Auto-refresh conversations every 30 seconds
        setInterval(() => {
            @this.call('refreshConversations');
        }, 30000);

        // Listen for socket events if socket is available
        if (typeof socket !== 'undefined') {
            socket.on('conversations', (data) => {
                console.log('Conversations updated via socket:', data);
                @this.call('refreshConversations');
            });

            socket.on('new_message', (data) => {
                console.log('New message received in conversations:', data);
                @this.dispatch('messageReceived', data);
            });

            socket.on('new-message', (data) => {
                console.log('New message received (new-message event):', data);
                @this.dispatch('messageReceived', {
                    conversationId: data.conversationId,
                    message: data
                });
            });

            socket.on('message-seen', (data) => {
                console.log('Message seen:', data);
                @this.call('refreshConversations');
            });

            // Emit socket connected event to component
            socket.on('connect', () => {
                @this.dispatch('socketConnected');
            });
        }

        // Update active user status indicators
        const updateUserStatus = () => {
            if (typeof activeUsers !== 'undefined' && activeUsers.length > 0) {
                const statusDots = document.querySelectorAll('[data-username]');
                statusDots.forEach(dot => {
                    const username = dot.getAttribute('data-username');
                    const isActive = activeUsers.some(user => user.username === username);

                    if (isActive) {
                        dot.classList.remove('bg-gray-400');
                        dot.classList.add('bg-green-500');
                    } else {
                        dot.classList.remove('bg-green-500');
                        dot.classList.add('bg-gray-400');
                    }
                });
            }
        };

        // Listen for active users updates
        if (typeof socket !== 'undefined') {
            socket.on('active_users', (users) => {
                window.activeUsers = users;
                updateUserStatus();
            });
        }

        // Update status every 5 seconds
        setInterval(updateUserStatus, 5000);
    });
</script>
