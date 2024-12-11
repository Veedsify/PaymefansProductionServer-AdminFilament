<div
    class="py-5 col-span-5 lg:col-span-3 bg-white lg:border-r lg:static fixed w-full left-0 top-16 z-[10] h-screen overflow-hidden">
    <div class="border-b pb-4 p-3 lg:p-4">
        <div class="flex items-center">
            <img src=" {{ auth()->user()->profile_image }}" alt="Profile Picture"
                class="w-16 h-16 rounded-full border-2 border-pink-500">
            <div class="flex items-center justify-between">
                <div class="ml-4">
                    <h2 class="text-lg lg:text-xl font-semibold text-gray-800">
                        {{ auth()->user()->name }}
                    </h2>
                    <p class="text-sm text-gray-500">Online</p>
                </div>
                <span>
                    @svg('mdi-close')
                </span>
            </div>
        </div>
        <div class="mt-4">
            <p class="text-sm text-gray-500">Manage your conversations and users from this dashboard. Keep everything
                organized and under control.</p>
        </div>
    </div>

    <div x-data="{ activeTab: 'messages' }" class="mt-5">
        <div class="flex border-b space-x-4 text-center">
            <button @click="activeTab = 'messages'" :class="{ 'border-b-2 border-pink-500': activeTab === 'messages' }"
                class="flex-1 py-2 text-sm font-medium text-gray-600 hover:text-pink-600 transition duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                    <path
                        d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
                </svg>
                Messages
            </button>
            <button @click="activeTab = 'users'" :class="{ 'border-b-2 border-pink-500': activeTab === 'users' }"
                class="flex-1 py-2 text-sm font-medium text-gray-600 hover:text-pink-600 transition duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" viewBox="0 0 20 20"
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
                    class="flex items-center p-4 bg-white rounded-lg shadow-sm hover:bg-gray-50 cursor-pointer transition">
                    <div class="relative flex-shrink-0">
                        <img src="{{ asset($user->profile_image) }}" alt="User Avatar"
                            class="w-12 h-12 rounded-full border-2 border-pink-500">
                    </div>
                    <div class="ml-4 flex-grow">
                        <h4 class="text-sm font-semibold text-gray-800">
                            {{ $user->name }}
                        </h4>
                        <p class="text-sm text-gray-600">
                            {{ $user->username }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <template class="hidden">
        <div class="flex items-center p-4 bg-white rounded-lg shadow-sm hover:bg-gray-50 cursor-pointer transition">
            <div class="relative flex-shrink-0 bg-pink-50">
                <img src="https://via.placeholder.com/40" alt="User Avatar"
                    class="w-12 h-12 rounded-full border-2 border-pink-500 bg-gray-200 ">
                <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
            </div>
            <div class="ml-4 flex-grow">
                <div class="flex justify-between items-center">
                    <h4 class="text-sm font-semibold text-gray-800">John Doe</h4>
                    <span class="text-xs text-gray-500">10:30 AM</span>
                </div>
                <p class="text-sm text-gray-600 truncate">Hey, how are you doing today?</p>
            </div>
        </div>
    </template>
</div>
