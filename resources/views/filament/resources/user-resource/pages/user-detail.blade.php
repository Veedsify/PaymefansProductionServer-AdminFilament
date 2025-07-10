<x-filament-panels::page>
    <div class="bg-gray-50 dark:bg-gray-950 min-h-screen">
        <!-- Top Section: User Overview -->
        <div class="bg-white dark:bg-gray-900 rounded-lg dark:text-white shadow-sm mb-6">
            <div class="p-6">
                <!-- User Header -->
                <div class="flex flex-col md:flex-row items-start md:items-center">
                    <!-- Avatar -->
                    <div class="relative">
                        <div
                            class="h-24 w-24 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden border-4 border-white shadow">
                            <img src="{{ $user->profile_image }}" alt="Profile" class="h-full w-full object-cover">
                        </div>
                        @if($user->active_status)
                        <div class="absolute bottom-0 right-0 h-6 w-6 bg-green-500 rounded-full border-2 border-white">
                        </div>
                        @else
                        <div class="absolute bottom-0 right-0 h-6 w-6 bg-red-500 rounded-full border-2 border-white">
                        </div>
                        @endif
                    </div>


                    <!-- User Info -->
                    <div class="md:ml-6 mt-4 md:mt-0 flex-grow">
                        <div class="flex flex-col md:flex-row md:items-center justify-between">
                            <div>
                                <h1 class="text-2xl font-bold  dark:text-whitetext-gray-900">{{ $user->name }}</h1>
                                <p class="text-gray-600 dark:text-white">{{ $user->username }}</p>
                                <div class="flex items-center mt-1 text-sm text-gray-500 dark:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $user->location }}
                                </div>
                            </div>

                            <div class="mt-4 md:mt-0 flex space-x-2">
                                <button wire:click="redirectToActions"
                                    class="px-4 py-2 bg-purpled-600 text-white rounded-md hover:bg-purpled-700 transition">
                                    Actions
                                </button>
                                <a href="{{ \App\Filament\Resources\UserResource::getUrl('permissions', ['record' => $user->id]) }}"
                                    class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition inline-flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    Permissions
                                </a>
                                <div class="relative">
                                    <button
                                        class="px-2 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Stats -->
                <div class="mt-6 grid grid-cols-2 md:grid-cols-5 gap-4 border-t border-gray-100 pt-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $postCount }}
                        </div>
                        <div class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Posts</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $user->total_followers }}
                        </div>
                        <div class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Followers</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $user->total_following }}
                        </div>
                        <div class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Following</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $user->total_subscribers }}
                        </div>
                        <div class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Subscribers</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purpled-600">{{ $user->user_point->points }}</div>
                        <div class="text-xs uppercase tracking-wide text-gray-500 font-semibold">
                            Point Balance
                        </div>
                    </div>
                </div>

                <!-- User Details -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-100 pt-6">
                    <div>
                        <h3 class="font-medium text-gray-700 dark:text-white mb-2">Contact Information</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span>
                                    {{ $user->email }}
                                </span>
                            </div>
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <span>
                                    {{ $user->phone }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-700 dark:text-white mb-2">Account Information</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Joined:
                                    {{ $user->created_at->format('F j, Y') }}
                                </span>
                            </div>
                            @if ($user->is_email_verified)
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span>Verified Account</span>
                            </div>
                            @endif

                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <span>
                                    {{ $user->is_model ? 'Model' : 'User' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Section: Tabbed Content -->
            <div class="bg-white rounded-lg shadow-sm">
                <!-- Tabs Navigation -->
                <div class="border-b border-gray-200 dark:bg-gray-950">
                    <nav class="-mb-px flex overflow-x-auto">
                        <button wire:click="$set('activeTab', 'posts')"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $activeTab === 'posts' ? 'border-purpled-500 text-purpled-600' : 'border-transparent text-gray-500 dark:text-white hover:text-gray-700 hover:border-gray-300' }}">
                            Posts
                        </button>
                        <button wire:click="$set('activeTab', 'comments')"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $activeTab === 'comments' ? 'border-purpled-500 text-purpled-600' : 'border-transparent text-gray-500 dark:text-white hover:text-gray-700 hover:border-gray-300' }}">
                            Comments
                        </button>
                        <button wire:click="$set('activeTab', 'activity')"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $activeTab === 'activity' ? 'border-purpled-500 text-purpled-600' : 'border-transparent text-gray-500 dark:text-white hover:text-gray-700 hover:border-gray-300' }}">
                            Activity
                        </button>
                        <button wire:click="$set('activeTab', 'subscriptions')"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $activeTab === 'subscriptions' ? 'border-purpled-500 text-purpled-600' : 'border-transparent text-gray-500 dark:text-white hover:text-gray-700 hover:border-gray-300' }}">
                            Active Subscriptions
                        </button>
                        <button wire:click="$set('activeTab', 'subscriptionHistory')"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $activeTab === 'subscriptionHistory' ? 'border-purpled-500 text-purpled-600' : 'border-transparent text-gray-500 dark:text-white hover:text-gray-700 hover:border-gray-300' }}">
                            Subscription History
                        </button>
                        <button wire:click="$set('activeTab', 'notifications')"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $activeTab === 'notifications' ? 'border-purpled-500 text-purpled-600' : 'border-transparent text-gray-500 dark:text-white hover:text-gray-700 hover:border-gray-300' }}">
                            Notifications
                        </button>
                        <button wire:click="$set('activeTab', 'support')"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $activeTab === 'support' ? 'border-purpled-500 text-purpled-600' : 'border-transparent text-gray-500 dark:text-white hover:text-gray-700 hover:border-gray-300' }}">
                            Support
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6 dark:bg-gray-950">
                    <div x-show="$wire.activeTab === 'posts'">
                        <div class="border-b border-gray-100 dark:border-gray-800 pb-2 mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Posts</h3>
                        </div>

                        {{-- Post Contents --}}
                        @livewire('user.user-recent-posts-details', ['userId' => $this->user->id])
                    </div>
                    <div x-show="$wire.activeTab === 'comments'">
                        <div class="border-b border-gray-100 dark:border-gray-800 pb-2 mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Comments</h3>
                        </div>

                        {{-- Comments Contents --}}
                        @livewire('user.user-comments-details', ['userId' => $this->user->user_id])
                    </div>

                    <!-- Activity Tab -->
                    <div x-show="$wire.activeTab === 'activity'">
                        <div class="border-b border-gray-100 dark:border-gray-800 pb-2 mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Activity</h3>
                        </div>

                        @livewire('user.user-activities-details', ['userId' => $this->user->id])

                        <div class="mt-6 text-center">
                            <button class="text-sm text-purpled-600 hover:text-purpled-800 font-medium">
                                View All Activity
                            </button>
                        </div>
                    </div>

                    <!-- Subscriptions Tab -->
                    <div x-show="$wire.activeTab === 'subscriptions'">
                        <div
                            class="border-b border-gray-100 dark:border-gray-800 pb-2 mb-4 flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Subscription History</h3>
                        </div>
                        @livewire('user.user-active-subscriptions-details', ['userId' => $this->user->id])
                    </div>

                    <!-- Purchases Tab -->
                    <div x-show="$wire.activeTab === 'subscriptionHistory'">
                        <div class="border-b border-gray-100 dark:border-gray-800 pb-2 mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Subscription History</h3>
                        </div>

                        @livewire('user.user-subscription-history-details', ['userId' => $this->user->id])
                    </div>

                    <!-- Notifications Tab -->
                    <div x-show="$wire.activeTab === 'notifications'">
                        @livewire("user.notifications-details",['userId' => $this->user->id])
                    </div>

                    <!-- Support Tab -->
                    <div x-show="$wire.activeTab === 'support'">
                        <div class="border-b border-gray-100 dark:border-gray-800 pb-2 mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Support Tickets</h3>
                        </div>
                        @livewire("user.user-support-tickets-details",['userId' => $this->user->id])
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
