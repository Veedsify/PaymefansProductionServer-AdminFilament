    <div class="h-full py-6 lg:grid grid-cols-12">
        @livewire('chat.conversations')
        @livewire('chat.messages')

        <!-- Socket Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
        <script src="{{ $backendServer ? $backendServer . '/socket.io/socket.io.js' : 'http://localhost:3001/socket.io/socket.io.js' }}"></script>

        <!-- HLS.js for Cloudflare Stream video support -->
        <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

        <!-- Configuration Scripts -->
        <script>
            const server = "{{ $backendServer }}";
            const userId = "{{ auth()->user()->user_id }}";
            const dingSound = "{{ asset('sounds/ding.mp3') }}";
            let socket;
            let activeUsers = [];
            let joinedRooms = new Set();
        </script>

        <!-- Enhanced Socket Implementation -->
        {{-- // Enhanced Socket Implementation --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize socket connection with query params
                socket = io(server, {
                    query: {
                        username: "{{ auth()->user()->username }}"
                    }
                });

                // Socket connection events
                socket.on('connect', () => {
                    console.log('Connected to socket server');

                    // Emit user-connected event for admin (matching server expectations)
                    socket.emit('user-connected', {
                        username: "{{ auth()->user()->username }}",
                        userId: userId
                    });

                    // Join admin room for notifications
                    socket.emit('join', `admin_${userId}`);
                });

                socket.on('disconnect', () => {
                    console.log('Disconnected from socket server');
                });

                // Handle active users updates
                socket.on('active_users', (data) => {
                    activeUsers = data;
                    updateActiveUsersUI();
                });

                // Handle incoming messages
                socket.on('new_message', (data) => {
                    console.log('New message received:', data);

                    // Play notification sound
                    if (data.sender_id !== userId) {
                        playNotificationSound();
                    }

                    // Update Livewire components
                    Livewire.dispatch('messageReceived', {
                        conversationId: data.conversation_id,
                        message: data.message
                    });
                });

                // Handle conversation updates
                socket.on('conversations', (data) => {
                    console.log('Conversations updated:', data);
                    Livewire.dispatch('refreshConversations');
                });

                // Handle message seen updates
                socket.on('message-seen', (data) => {
                    console.log('Message seen:', data);
                    Livewire.dispatch('messageSeenUpdate', data);
                });

                // Handle typing indicators
                socket.on('typing', (data) => {
                    if (data.sender_id !== userId) {
                        showTypingIndicator(data);
                    }
                });

                socket.on('stop_typing', (data) => {
                    hideTypingIndicator(data);
                });

                // Handle connection acknowledgment
                socket.on('user-connected-ack', (data) => {
                    console.log('User connected acknowledged:', data);
                });

                // Handle error events
                socket.on('error', (error) => {
                    console.error('Socket error:', error);
                });
            });

            // Function to update active users UI
            function updateActiveUsersUI() {
                const users = document.querySelectorAll('[data-username]');

                // Reset all users to offline
                users.forEach(user => {
                    const statusDot = user.querySelector('.w-3.h-3');
                    if (statusDot) {
                        statusDot.classList.remove('bg-green-500');
                        statusDot.classList.add('bg-gray-400');
                    }
                });

                // Update active users
                if (activeUsers.length > 0) {
                    users.forEach(user => {
                        const username = user.getAttribute('data-username');
                        const activeUser = activeUsers.find(au => au.username === username);

                        if (activeUser) {
                            const statusDot = user.querySelector('.w-3.h-3');
                            if (statusDot) {
                                statusDot.classList.remove('bg-gray-400');
                                statusDot.classList.add('bg-green-500');
                            }
                        }
                    });
                }
            }

            // Function to join a conversation room
            function joinConversationRoom(conversationId) {
                if (!joinedRooms.has(conversationId)) {
                    socket.emit('join', conversationId);
                    joinedRooms.add(conversationId);
                    console.log('Joined room:', conversationId);
                }
            }

            // Function to leave a conversation room
            function leaveConversationRoom(conversationId) {
                if (joinedRooms.has(conversationId)) {
                    socket.emit('leave', conversationId);
                    joinedRooms.delete(conversationId);
                    console.log('Left room:', conversationId);
                }
            }

            // Function to play notification sound
            function playNotificationSound() {
                try {
                    const audio = new Audio(dingSound);
                    audio.volume = 0.5;
                    audio.play().catch(e => console.log('Could not play notification sound:', e));
                } catch (e) {
                    console.log('Notification sound not available:', e);
                }
            }

            // Function to show typing indicator
            function showTypingIndicator(data) {
                const conversationElement = document.querySelector(`[data-conversation="${data.conversation_id}"]`);
                if (conversationElement) {
                    const messagePreview = conversationElement.querySelector('.text-sm.text-gray-600');
                    if (messagePreview) {
                        messagePreview.innerHTML = '<em class="text-purple-500">typing...</em>';
                    }
                }
            }

            // Function to hide typing indicator
            function hideTypingIndicator(data) {
                // This would normally restore the last message, but for simplicity we'll refresh
                setTimeout(() => {
                    Livewire.dispatch('refreshConversations');
                }, 1000);
            }

            // Livewire event listeners
            document.addEventListener('livewire:initialized', () => {
                // Listen for conversation selection
                Livewire.on('conversationSelected', (event) => {
                    const conversationId = event.conversationId || event[0]?.conversationId;
                    if (conversationId) {
                        console.log('Joining conversation room:', conversationId);
                        joinConversationRoom(conversationId);

                        // Also emit to get latest conversations
                        socket.emit('get-conversations', { userId: userId });
                    }
                });

                // Listen for outgoing messages
                Livewire.on('messageSent', (event) => {
                    const data = event.data || event[0];
                    if (data && socket) {
                        console.log('Sending message via socket:', data);
                        socket.emit('new-message', {
                            conversationId: data.conversationId,
                            message_id: data.message.message_id,
                            sender_id: userId,
                            receiver_id: data.message.receiver_id,
                            message: data.message.message,
                            attachment: data.message.attachment ? JSON.parse(data.message.attachment) : []
                        });
                    }
                });

                // Listen for socket message emissions from Livewire
                Livewire.on('emitSocketMessage', (event) => {
                    const eventData = event.detail || event[0];
                    if (eventData && socket) {
                        console.log('Emitting socket event:', eventData.event, eventData.data);
                        socket.emit(eventData.event, eventData.data);
                    }
                });

                // Listen for typing events
                Livewire.on('startTyping', (event) => {
                    const data = event.data || event[0];
                    if (data && socket) {
                        socket.emit('typing', {
                            conversation_id: data.conversationId,
                            sender_id: userId
                        });
                    }
                });

                Livewire.on('stopTyping', (event) => {
                    const data = event.data || event[0];
                    if (data && socket) {
                        socket.emit('stop_typing', {
                            conversation_id: data.conversationId,
                            sender_id: userId
                        });
                    }
                });
            });

            // Global socket accessor for components
            window.getSocket = function() {
                return socket;
            };

            // Cleanup on page unload
            window.addEventListener('beforeunload', () => {
                if (socket) {
                    socket.disconnect();
                }
            });
        </script>

        <!-- Custom Styles -->
        <style>
            .sleek_scrollbar::-webkit-scrollbar {
                width: 4px;
            }

            .sleek_scrollbar::-webkit-scrollbar-track {
                background: transparent;
            }

            .sleek_scrollbar::-webkit-scrollbar-thumb {
                background: #cbd5e0;
                border-radius: 4px;
            }

            .sleek_scrollbar::-webkit-scrollbar-thumb:hover {
                background: #a0aec0;
            }

            .dark .sleek_scrollbar::-webkit-scrollbar-thumb {
                background: #4a5568;
            }

            .dark .sleek_scrollbar::-webkit-scrollbar-thumb:hover {
                background: #2d3748;
            }

            /* Message animations */
            @keyframes slideInFromRight {
                from {
                    opacity: 0;
                    transform: translateX(20px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            @keyframes slideInFromLeft {
                from {
                    opacity: 0;
                    transform: translateX(-20px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            .message-sent {
                animation: slideInFromRight 0.3s ease-out;
            }

            .message-received {
                animation: slideInFromLeft 0.3s ease-out;
            }

            /* Conversation selection highlight */
            .conversation-selected {
                background: rgb(248 250 252) !important;
                border-left: 4px solid rgb(168 85 247) !important;
            }

            .dark .conversation-selected {
                background: rgb(30 27 75) !important;
            }

            /* Loading states */
            .loading-pulse {
                animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }

            @keyframes pulse {
                0%, 100% {
                    opacity: 1;
                }
                50% {
                    opacity: .5;
                }
            }

            /* Typing indicator */
            .typing-dots {
                display: inline-block;
            }

            .typing-dots::after {
                content: '';
                animation: typing-dots 1.5s infinite;
            }

            @keyframes typing-dots {
                0% { content: ''; }
                25% { content: '.'; }
                50% { content: '..'; }
                75% { content: '...'; }
                100% { content: ''; }
            }
        </style>
    </div>
