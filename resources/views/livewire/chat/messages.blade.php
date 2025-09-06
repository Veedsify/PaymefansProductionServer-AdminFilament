<div class="w-full py-5 bg-white lg:p-2 col-span-7 lg:col-span-9 dark:bg-gray-800">
    <div class="flex flex-col h-full">
        <!-- Chat Header -->
        @if($receiver)
        <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
            <div class="flex items-center">
                <div class="block lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </div>
                <div class="flex items-center space-x-3">
                    <img src="{{ $receiver->profile_image ?? '/default-avatar.png' }}" class="w-10 h-10 rounded-full outline outline-purple-400">
                    <div>
                        <h3 class="font-semibold dark:text-white">{{ $receiver->name }}</h3>
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $receiver->username }}</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                @if($hasMore)
                <button wire:click="loadMoreMessages" class="p-2 text-sm text-purple-600 hover:bg-purple-100 rounded-lg dark:text-purple-400 dark:hover:bg-purple-900">
                    Load More
                </button>
                @endif
                <button class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 dark:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                    </svg>
                </button>
            </div>
        </div>
        @else
        <div class="flex items-center justify-center p-4 border-b dark:border-gray-700">
            <div class="text-center">
                <h3 class="text-lg font-medium text-gray-800 dark:text-white">Select a conversation</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Choose a conversation from the left to start messaging</p>
            </div>
        </div>
        @endif

        <!-- Messages Area -->
        <div class="flex-1 overflow-y-auto max-h-[70vh] p-2 lg:p-4 sleek_scrollbar dark:bg-gray-800" id="messages-container">

            @if($receiver && count($messages) > 0)
            @if($isLoading)
            <div class="flex justify-center py-4">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-purple-500"></div>
            </div>
            @endif

            @foreach($groupedMessages as $date => $dayMessages)
            <!-- Date Separator -->
            <div class="flex justify-center mb-4">
                <span class="px-3 py-1 text-xs font-medium text-gray-500 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-400">
                    {{ $this->formatMessageDate($dayMessages[0]['created_at']) }}
                </span>
            </div>

            <!-- Messages for this date -->
            @foreach($dayMessages as $message)
            @php
            $isFromAdmin = $this->isMessageFromAdmin($message['sender_id']);
            $hasAttachment = !empty($message['attachment']);
            $hasMessage = !empty($message['message']);
            @endphp

            <div class="flex {{ $isFromAdmin ? 'justify-end' : 'justify-start' }} mb-4">
                <div class="max-w-md {{ $isFromAdmin ? 'order-2' : 'order-1' }}">

                    <!-- Message Bubble -->
                    <div class="p-3 rounded-3xl text-sm lg:text-base
                                        {{ $isFromAdmin
                                            ? 'bg-purple-500 text-white'
                                            : 'bg-gray-100 dark:bg-gray-700 dark:text-white'
                                        }}">

                        <!-- Text Message -->
                        @if($hasMessage)
                        <p class="break-words">{{ $message['message'] }}</p>
                        @endif

                        <!-- Attachment -->
                        @if($hasAttachment)
                        @php
                        $attachments = [];
                        if (is_string($message['attachment'])) {
                        $attachments = json_decode($message['attachment'], true) ?? [];
                        } elseif (is_array($message['attachment'])) {
                        $attachments = $message['attachment'];
                        }
                        @endphp

                        @if(!empty($attachments))
                        <div class="mt-2 attachment-container {{ count($attachments) > 1 ? 'multiple' : '' }}">
                            @foreach($attachments as $attachment)
                            @php
                            $attachmentUrl = $attachment['url'] ?? '';
                            $attachmentType = $attachment['type'] ?? '';
                            $attachmentName = $attachment['name'] ?? 'Attachment';
                            $attachmentSize = $attachment['size'] ?? 0;
                            $attachmentId = $attachment['id'] ?? '';
                            $posterUrl = $attachment['poster'] ?? '';
                            $extension = $attachment['extension'] ?? '';

                            $isImage = $attachmentType === 'image' || in_array(strtolower(trim($extension, '.')), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            $isVideo = $attachmentType === 'video' || in_array(strtolower(trim($extension, '.')), ['mp4', 'webm', 'ogg', 'mov']);
                            $isCloudflareStream = str_contains($attachmentUrl, '.m3u8') || str_contains($attachmentUrl, 'cloudflarestream.com');
                            @endphp

                            <div class="animate-fadeIn">
                                @if($isImage)
                                <!-- Image Attachment -->
                                <div class="relative group">
                                    <img src="{{ $attachmentUrl }}" alt="{{ $attachmentName }}" class="attachment-image shadow-sm aspect-square" onclick="window.open('{{ $attachmentUrl }}', '_blank')" loading="lazy" style="{{ count($attachments) > 1 ? 'max-height: 200px; object-fit: cover;' : 'max-height: 400px; object-fit:cover;' }}">
                                    <div class="attachment-overlay">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white opacity-0 group-hover:opacity-75 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                        </svg>
                                    </div>
                                    @if($attachmentSize > 0)
                                    <div class="attachment-info">
                                        {{ number_format($attachmentSize / 1024, 1) }} KB
                                    </div>
                                    @endif
                                </div>

                                @elseif($isVideo)
                                <!-- Video Attachment -->
                                @if($isCloudflareStream)
                                <!-- Cloudflare Stream Video with HLS -->
                                <div class="relative overflow-hidden attachment-video">
                                    <video class="w-full h-auto {{ count($attachments) > 1 ? 'max-h-48' : 'max-h-96' }}" controls preload="metadata" @if($posterUrl) poster="{{ $posterUrl }}" @endif data-hls-url="{{ $attachmentUrl }}">
                                        <source src="{{ $attachmentUrl }}" type="application/x-mpegURL">
                                        <!-- Fallback for browsers that don't support HLS -->
                                        <p class="text-sm text-gray-500 p-4">
                                            Your browser doesn't support HLS video playback.
                                            <a href="{{ $attachmentUrl }}" target="_blank" class="text-purple-500 underline">
                                                Click here to view the video
                                            </a>
                                        </p>
                                    </video>
                                    @if($attachmentSize > 0)
                                    <div class="attachment-info">
                                        {{ number_format($attachmentSize / 1024 / 1024, 1) }} MB
                                    </div>
                                    @endif
                                </div>
                                @else
                                <!-- Regular Video -->
                                <div class="relative overflow-hidden attachment-video">
                                    <video class="w-full h-auto {{ count($attachments) > 1 ? 'max-h-48' : 'max-h-96' }}" controls preload="metadata" @if($posterUrl) poster="{{ $posterUrl }}" @endif>
                                        <source src="{{ $attachmentUrl }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                    @if($attachmentSize > 0)
                                    <div class="attachment-info">
                                        {{ number_format($attachmentSize / 1024 / 1024, 1) }} MB
                                    </div>
                                    @endif
                                </div>
                                @endif

                                @else
                                <!-- Other File Types -->
                                <a href="{{ $attachmentUrl }}" target="_blank" class="attachment-file">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <div class="flex-grow min-w-0">
                                        <p class="text-sm font-medium truncate">{{ $attachmentName }}</p>
                                        @if($attachmentSize > 0)
                                        <p class="text-xs opacity-75">{{ number_format($attachmentSize / 1024, 1) }} KB</p>
                                        @endif
                                        @if($extension)
                                        <p class="text-xs opacity-75 uppercase">{{ trim($extension, '.') }} file</p>
                                        @endif
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @endif
                        @endif
                    </div>

                    <!-- Message Time and Status -->
                    <div class="mt-1 {{ $isFromAdmin ? 'text-right' : 'text-left' }}">
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $this->formatMessageTime($message['created_at']) }}
                            @if($isFromAdmin)
                            @if($message['seen'])
                            <span class="ml-1 text-purple-500">✓✓</span>
                            @else
                            <span class="ml-1 text-gray-400">✓</span>
                            @endif
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
            @endforeach

            @elseif($receiver && count($messages) === 0)
            <!-- No Messages Yet -->
            <div class="flex flex-col items-center justify-center h-full text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mb-4 text-gray-400 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-2">No messages yet</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Start the conversation by sending a message below</p>
            </div>

            @else
            <!-- No Conversation Selected -->
            <div class="flex flex-col items-center justify-center h-full text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 mb-4 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a2 2 0 01-2-2v-6a2 2 0 012-2h2m-5 9v-4a2 2 0 012-2h2m5-3V3a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <h3 class="text-xl font-medium text-gray-800 dark:text-white mb-2">Welcome to Admin Chat</h3>
                <p class="text-gray-500 dark:text-gray-400 max-w-md">
                    Select a conversation from the left sidebar to start messaging with users.
                    You can also start a new conversation from the Users tab.
                </p>
            </div>
            @endif
        </div>

        <!-- Message Input -->
        @if($receiver)
        <div class="p-4 border-t dark:border-gray-700" x-data="{ attachMenu: false }">
            <form wire:submit.prevent="sendMessage">
                <div class="flex items-center gap-2 lg:gap-4">

                    <!-- Attachment Button -->
                    <button type="button" @click="attachMenu = !attachMenu" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 dark:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                    </button>

                    <!-- Message Input -->
                    <input type="text" wire:model="newMessage" placeholder="Type your message..." class="flex-1 text-sm border rounded-full dark:border-gray-700 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 lg:text-base lg:px-4 lg:py-3 focus:outline-none focus:border-purple-500" @input="handleTyping()" @keydown.enter.prevent="$wire.sendMessage()">

                    <!-- Send Button -->
                    <button type="submit" class="flex items-center p-3 text-white bg-purple-500 rounded-full lg:text-base hover:bg-purple-600 gap-2 disabled:opacity-50 disabled:cursor-not-allowed" :disabled="!$wire.newMessage.trim() && !$wire.attachment">
                        <span class="hidden pl-4 text-sm font-medium lg:block">
                            Send
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </div>

                <!-- Attachment Preview -->
                @if($attachment && ((is_object($attachment) && method_exists($attachment, 'getClientOriginalName')) || (is_array($attachment) && !empty($attachment) && isset($attachment[0]))))
                <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            @php
                            $fileName = 'Unknown file';
                            $fileSize = 0;
                            $mimeType = '';

                            if (is_object($attachment) && method_exists($attachment, 'getClientOriginalName')) {
                            $fileName = $attachment->getClientOriginalName();
                            $fileSize = $attachment->getSize();
                            $mimeType = $attachment->getMimeType();
                            } elseif (is_array($attachment) && !empty($attachment) && isset($attachment[0]) && is_array($attachment[0])) {
                            $firstFile = $attachment[0];
                            $fileName = $firstFile['name'] ?? 'Unknown file';
                            $fileSize = $firstFile['size'] ?? 0;
                            $mimeType = $firstFile['type'] ?? '';
                            }
                            @endphp

                            @if(str_starts_with($mimeType, 'image/'))
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            @elseif(str_starts_with($mimeType, 'video/'))
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            @endif
                            <div>
                                <span class="text-sm text-gray-700 dark:text-gray-300 font-medium">{{ $fileName }}</span>
                                @if($fileSize > 0)
                                <div class="text-xs text-gray-500">{{ number_format($fileSize / 1024, 1) }} KB</div>
                                @endif
                            </div>
                        </div>
                        <button type="button" wire:click="$set('attachment', null)" class="text-red-500 hover:text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                @endif
            </form>

            <!-- Attachment Menu -->
            <div x-show="attachMenu" @click.away="attachMenu = false" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="p-6 bg-white shadow-lg dark:bg-gray-800 rounded-3xl lg:w-96">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold dark:text-white">Add Attachment</h3>
                        <button @click="attachMenu = false" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="flex flex-col items-center p-4 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-xl transition-colors cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mb-2 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Image</span>
                            <input type="file" wire:model="attachment" accept="image/*" class="hidden" @change="attachMenu = false">
                        </label>
                        <label class="flex flex-col items-center p-4 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-xl transition-colors cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mb-2 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Video</span>
                            <input type="file" wire:model="attachment" accept="video/*" class="hidden" @change="attachMenu = false">
                        </label>
                    </div>
                    <div class="mt-6 text-sm text-center text-gray-500 dark:text-gray-400">
                        Supported formats: JPG, PNG, MP4
                    </div>
                </div>
            </div>
        </div>

        <!-- Typing Indicator -->
        <div id="typing-indicator" class="hidden p-2 text-sm text-gray-500 dark:text-gray-400">
            <span class="typing-dots">{{ $receiver->name }} is typing</span>
        </div>
        @endif
        <script>
            let typingTimer;
            let isTyping = false;

            function handleTyping() {
                @if($conversationId && $receiver)
                if (!isTyping) {
                    isTyping = true;
                    @this.dispatch('emitSocketMessage', {
                        event: 'typing'
                        , data: {
                            conversation_id: '{{ $conversationId }}'
                            , sender_id: '{{ auth()->user()->user_id }}'
                        }
                    });
                }

                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => {
                    isTyping = false;
                    @this.dispatch('emitSocketMessage', {
                        event: 'stop_typing'
                        , data: {
                            conversation_id: '{{ $conversationId }}'
                            , sender_id: '{{ auth()->user()->user_id }}'
                        }
                    });
                }, 1000);
                @endif
            }
            document.addEventListener('livewire:initialized', () => {
                // Auto-scroll to bottom when new messages arrive
                const scrollToBottom = () => {
                    const container = document.getElementById('messages-container');
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                };

                // Initialize HLS for Cloudflare Stream videos
                const initializeHLS = () => {
                    if (typeof Hls !== 'undefined') {
                        const videos = document.querySelectorAll('video[data-hls-url]');
                        videos.forEach(video => {
                            const hlsUrl = video.getAttribute('data-hls-url');
                            if (hlsUrl && (hlsUrl.includes('.m3u8') || hlsUrl.includes('cloudflarestream.com'))) {
                                if (Hls.isSupported()) {
                                    const hls = new Hls({
                                        debug: false
                                        , enableWorker: true
                                        , lowLatencyMode: true
                                    , });
                                    hls.loadSource(hlsUrl);
                                    hls.attachMedia(video);
                                    hls.on(Hls.Events.MANIFEST_PARSED, function() {
                                        console.log('HLS manifest loaded for video');
                                    });
                                    hls.on(Hls.Events.ERROR, function(event, data) {
                                        console.error('HLS error:', data);
                                    });
                                } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                                    // Safari native support
                                    video.src = hlsUrl;
                                }
                            }
                        });
                    }
                };

                // Listen for new messages
                @this.on('messageSent', () => {
                    setTimeout(() => {
                        scrollToBottom();
                        initializeHLS();
                    }, 100);
                });

                @this.on('messageReceived', () => {
                    setTimeout(() => {
                        scrollToBottom();
                        initializeHLS();
                    }, 100);
                });

                // Scroll to bottom when conversation changes
                @this.on('conversationSelected', () => {
                    setTimeout(() => {
                        scrollToBottom();
                        initializeHLS();
                    }, 200);
                });

                // Initialize HLS on page load
                setTimeout(initializeHLS, 100);

                // Handle socket integration
                if (typeof socket !== 'undefined') {
                    socket.on('new_message', (data) => {
                        @this.dispatch('messageReceived', {
                            conversationId: data.conversation_id
                            , message: data.message
                        });
                    });

                    // Emit socket messages
                    @this.on('emitSocketMessage', (event) => {
                        const eventData = event.detail || event[0];
                        if (eventData && eventData.event && eventData.data) {
                            socket.emit(eventData.event, eventData.data);
                        }
                    });
                }
            });

        </script>

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

            /* Animation classes */
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes pulse {

                0%,
                100% {
                    opacity: 1;
                }

                50% {
                    opacity: 0.5;
                }
            }

            .animate-fadeIn {
                animation: fadeIn 0.3s ease-out;
            }

            .animate-pulse {
                animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }

            /* Typing indicator */
            .typing-dots::after {
                content: '';
                animation: typing-dots 1.5s infinite;
            }

            @keyframes typing-dots {
                0% {
                    content: '';
                }

                25% {
                    content: '.';
                }

                50% {
                    content: '..';
                }

                75% {
                    content: '...';
                }

                100% {
                    content: '';
                }
            }

            /* Message bubble animations */
            .message-bubble {
                transform: translateY(20px);
                opacity: 0;
                animation: slideUp 0.3s ease-out forwards;
            }

            @keyframes slideUp {
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            /* Attachment specific styles */
            .attachment-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 8px;
            }

            .attachment-image {
                border-radius: 1rem;
                max-width: 100%;
                height: auto;
                cursor: pointer;
                transition: transform 0 1px 3px rgba(0, 0, 0, 0.1);
            }

            .attachment-image:hover {
                transform: scale(1.02);
            }

            .attachment-video {
                border-radius: 1rem;
                max-width: 100%;
                height: auto;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            }

            .attachment-overlay {
                position: absolute;
                inset: 0;
                background: rgba(0, 0, 0, 0);
                border-radius: 1rem;
                transition: background 0.3s ease;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .attachment-overlay:hover {
                background: rgba(0, 0, 0, 0.1);
            }

            .attachment-file {
                display: flex;
                align-items: center;
                padding: 12px;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 8px;
                transition: all 0.2s ease;
                text-decoration: none;
            }

            .attachment-file:hover {
                background: rgba(255, 255, 255, 0.3);
                transform: translateY(-1px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .attachment-info {
                position: absolute;
                bottom: 8px;
                right: 8px;
                background: rgba(0, 0, 0, 0.5);
                color: white;
                font-size: 0.75rem;
                padding: 4px 8px;
                border-radius: 4px;
                backdrop-filter: blur(4px);
            }

            /* Video loading state */
            .video-loading {
                position: relative;
                background: #f3f4f6;
                border-radius: 1rem;
            }

            .video-loading::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 40px;
                height: 40px;
                border: 3px solid #e5e7eb;
                border-top: 3px solid #8b5cf6;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                0% {
                    transform: translate(-50%, -50%) rotate(0deg);
                }

                100% {
                    transform: translate(-50%, -50%) rotate(360deg);
                }
            }

            /* Multiple attachment display */
            .attachment-container {
                max-width: 400px;
            }

            .attachment-container.multiple {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
            }

            .attachment-container.multiple .attachment-image,
            .attachment-container.multiple .attachment-video {
                max-height: 200px;
                object-fit: cover;
            }

        </style>
    </div>
