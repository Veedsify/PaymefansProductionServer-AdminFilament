<div
    class="py-5 col-span-5 lg:col-span-3 bg-white dark:bg-gray-800 lg:border-r lg:static fixed w-full left-0 top-16 z-[10] h-screen overflow-hidden">
    <div class="p-3 pb-4 border-b lg:p-4 dark:border-gray-700">
        <div class="flex items-center">
            <img src=" {{ auth()->user()->profile_image }}" alt="Profile Picture"
                class="w-16 h-16 border-2 border-purple-500 rounded-full">
            <div class="flex items-center justify-between">
                <div class="ml-4">
                    <h2 class="text-lg font-semibold text-gray-800 lg:text-xl dark:text-gray-200">
                        {{ auth()->user()->name }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Online</p>
                </div>
                <span>
                    @svg('mdi-close')
                </span>
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

        <div x-show="activeTab === 'messages'" class="mt-4" id="conversations_list">
            <!-- Single Conversation Item -->
        </div>
        <div x-show="activeTab === 'users'" class="mt-4 space-y-4">
            <!-- Users content here -->
            @foreach ($users as $user)
                <div
                    class="flex items-center p-4 bg-white rounded-lg cursor-pointer dark:bg-gray-800 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <div class="relative flex-shrink-0">
                        <img src="{{ asset($user->profile_image) }}" alt="User Avatar"
                            class="w-12 h-12 border-2 border-purple-500 rounded-full">
                    </div>
                    <div class="flex-grow ml-4">
                        <h4 class="text-sm font-semibold text-gray-800 dark:text-white">
                            {{ $user->name }}
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $user->username }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <template class="hidden">
        <div class="flex items-center p-4 bg-white rounded-lg cursor-pointer dark:bg-gray-800 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">
            <div class="relative flex-shrink-0 bg-purple-50 dark:bg-purple-900">
                <img src="https://via.placeholder.com/40" alt="User Avatar"
                    class="w-12 h-12 bg-gray-200 border-2 border-purple-500 rounded-full dark:bg-gray-700">
                <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full dark:border-gray-800"></span>
            </div>
            <div class="flex-grow ml-4">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200">John Doe</h4>
                    <span class="text-xs text-gray-500 dark:text-gray-400">10:30 AM</span>
                </div>
                <p class="text-sm text-gray-600 truncate dark:text-gray-400">Hey, how are you doing today?</p>
            </div>
        </div>
    </template>
</div>
