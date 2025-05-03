<x-filament-panels::page>
    <div class="bg-gray-50 min-h-screen">
        <!-- Top Section: User Overview -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="p-6">
                <!-- User Header -->
                <div class="flex flex-col md:flex-row items-start md:items-center">
                    <!-- Avatar -->
                    <div class="relative">
                        <div
                            class="h-24 w-24 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden border-4 border-white shadow">
                            {{-- <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd" />
                            </svg> --}}
                            <img src="{{ $user->profile_image }}" alt="Profile" class="h-full w-full object-cover">
                        </div>
                        <div class="absolute bottom-0 right-0 h-6 w-6 bg-green-500 rounded-full border-2 border-white">
                        </div>
                    </div>


                    <!-- User Info -->
                    <div class="md:ml-6 mt-4 md:mt-0 flex-grow">
                        <div class="flex flex-col md:flex-row md:items-center justify-between">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                                <p class="text-gray-600">{{ $user->username }}</p>
                                <div class="flex items-center mt-1 text-sm text-gray-500">
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
                                    class="px-4 py-2 bg-cyan-600 text-white rounded-md hover:bg-cyan-700 transition">
                                    Actions
                                </button>
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
                        <div class="text-2xl font-bold text-gray-900">
                            {{ $postCount }}
                        </div>
                        <div class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Posts</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900">
                            {{ $user->total_followers }}
                        </div>
                        <div class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Followers</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900">
                            {{ $user->total_following }}
                        </div>
                        <div class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Following</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900">
                            {{ $user->total_subscribers }}
                        </div>
                        <div class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Subscribers</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-cyan-600">{{ $user->user_point->points }}</div>
                        <div class="text-xs uppercase tracking-wide text-gray-500 font-semibold">
                            Point Balance
                        </div>
                    </div>
                </div>

                <!-- User Details -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-100 pt-6">
                    <div>
                        <h3 class="font-medium text-gray-700 mb-2">Contact Information</h3>
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
                        <h3 class="font-medium text-gray-700 mb-2">Account Information</h3>
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
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex overflow-x-auto">
                        <button wire:click="$set('activeTab', 'posts')"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $activeTab === 'posts' ? 'border-cyan-500 text-cyan-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Posts
                        </button>
                        <button wire:click="$set('activeTab', 'activity')"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $activeTab === 'activity' ? 'border-cyan-500 text-cyan-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Activity
                        </button>
                        <button wire:click="$set('activeTab', 'subscriptions')"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $activeTab === 'subscriptions' ? 'border-cyan-500 text-cyan-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Subscriptions
                        </button>
                        <button wire:click="$set('activeTab', 'purchases')"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $activeTab === 'purchases' ? 'border-cyan-500 text-cyan-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Purchases
                        </button>
                        <button wire:click="$set('activeTab', 'notifications')"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $activeTab === 'notifications' ? 'border-cyan-500 text-cyan-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Notifications
                        </button>
                        <button wire:click="$set('activeTab', 'support')"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $activeTab === 'support' ? 'border-cyan-500 text-cyan-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Support
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <div x-show="$wire.activeTab === 'posts'">
                        <div class="border-b border-gray-100 pb-2 mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Recent Posts</h3>
                        </div>

                        {{-- Post Contents --}}
                        @livewire('user.user-recent-posts-details', ['userId' => $this->user->id])
                    </div>

                    <!-- Activity Tab -->
                    <div x-show="$wire.activeTab === 'activity'">
                        <div class="border-b border-gray-100 pb-2 mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
                        </div>

                        <div class="space-y-6">
                            <!-- Activity Item -->
                            <div class="flex">
                                <div class="flex-shrink-0 mr-4">
                                    <div
                                        class="h-10 w-10 rounded-full bg-cyan-100 flex items-center justify-center text-cyan-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Logged in from a new device in <span
                                            class="font-medium text-gray-900">New York, USA</span></p>
                                    <p class="text-xs text-gray-500 mt-1">2 hours ago</p>
                                </div>
                            </div>

                            <!-- Activity Item -->
                            <div class="flex">
                                <div class="flex-shrink-0 mr-4">
                                    <div
                                        class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center text-green-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Made a purchase of <span
                                            class="font-medium text-gray-900">Premium Annual Plan</span> for <span
                                            class="font-medium text-gray-900">$99.00</span></p>
                                    <p class="text-xs text-gray-500 mt-1">Yesterday at 11:23 AM</p>
                                </div>
                            </div>

                            <!-- Activity Item -->
                            <div class="flex">
                                <div class="flex-shrink-0 mr-4">
                                    <div
                                        class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Updated profile information</p>
                                    <p class="text-xs text-gray-500 mt-1">3 days ago</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 text-center">
                            <button class="text-sm text-cyan-600 hover:text-cyan-800 font-medium">
                                View All Activity
                            </button>
                        </div>
                    </div>

                    <!-- Subscriptions Tab -->
                    <div x-show="$wire.activeTab === 'subscriptions'">
                        <div class="border-b border-gray-100 pb-2 mb-4 flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900">Subscription History</h3>
                            <button class="text-sm bg-cyan-50 text-cyan-700 py-1 px-3 rounded-full hover:bg-cyan-100">
                                Add Subscription
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Plan
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Start Date
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            End Date
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Price
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            Premium
                                            Annual
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Jan 15, 2023</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Jan 15, 2024</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$99.00</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button class="text-cyan-600 hover:text-cyan-900 mr-2">Edit</button>
                                            <button class="text-red-600 hover:text-red-900">Cancel</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Basic
                                            Monthly
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Expired</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Mar 10, 2022</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Jan 10, 2023</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$9.99/mo</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button class="text-cyan-600 hover:text-cyan-900 mr-2">View</button>
                                            <button class="text-green-600 hover:text-green-900">Renew</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Purchases Tab -->
                    <div x-show="$wire.activeTab === 'purchases'">
                        <div class="border-b border-gray-100 pb-2 mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Purchase History</h3>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Order ID
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Item
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Amount
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#ORD-2938</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            Premium
                                            Annual
                                            Plan
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Jan 15, 2023</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$99.00</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button class="text-cyan-600 hover:text-cyan-900">View Receipt</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#ORD-1826</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            Digital
                                            Marketing eBook
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Nov 22, 2022</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$24.95</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button class="text-cyan-600 hover:text-cyan-900">View Receipt</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#ORD-1053</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            Premium
                                            Course
                                            Bundle
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Aug 4, 2022</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$149.00</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Refunded</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button class="text-cyan-600 hover:text-cyan-900">View Details</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                Showing 3 of 24 purchases
                            </div>
                            <div class="flex space-x-2">
                                <button
                                    class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                                    Previous
                                </button>
                                <button
                                    class="px-3 py-1 bg-cyan-600 border border-cyan-600 rounded-md text-sm font-medium text-white hover:bg-cyan-700">
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications Tab -->
                    <div x-show="$wire.activeTab === 'notifications'">
                        @livewire("user.notifications-details",['userId' => $this->user->id])
                    </div>

                    <!-- Support Tab -->
                    <div x-show="$wire.activeTab === 'support'">
                        <div class="border-b border-gray-100 pb-2 mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Support Tickets</h3>
                        </div>

                        <div class="space-y-6">
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">Billing Issue - Premium Plan</h4>
                                        <p class="mt-1 text-xs text-gray-500">Ticket #5329 • Opened on May 15, 2023</p>
                                    </div>
                                    <span
                                        class="px-2 h-6 flex items-center text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">In
                                        Progress</span>
                                </div>
                                <p class="mt-3 text-sm text-gray-600 line-clamp-2">
                                    I was charged twice for my premium plan subscription this month. Can you please
                                    check my
                                    account and issue a refund for the duplicate charge?
                                </p>
                                <div class="mt-4 flex justify-end">
                                    <button class="text-sm text-cyan-600 hover:text-cyan-800 font-medium">
                                        View Details
                                    </button>
                                </div>
                            </div>

                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">Feature Request - Dark Mode</h4>
                                        <p class="mt-1 text-xs text-gray-500">Ticket #4873 • Opened on April 22,
                                            2023</p>
                                    </div>
                                    <span
                                        class="px-2 h-6 flex items-center text-xs font-semibold rounded-full bg-green-100 text-green-800">Resolved</span>
                                </div>
                                <p class="mt-3 text-sm text-gray-600 line-clamp-2">
                                    Would it be possible to add a dark mode option to the dashboard? It would be much
                                    easier
                                    on the eyes when working late at night.
                                </p>
                                <div class="mt-4 flex justify-end">
                                    <button class="text-sm text-cyan-600 hover:text-cyan-800 font-medium">
                                        View Details
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-center">
                            <button class="px-4 py-2 bg-cyan-600 text-white rounded-md hover:bg-cyan-700 transition">
                                Create New Ticket
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>