<x-filament-panels::page>
    <div class="space-y-8">
        <!-- User Profile Card -->
        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">User Profile</h2>
                <x-filament::button size="sm" icon="heroicon-o-pencil" tag="a" href="{{ $this->editProfileLink }}">
                    Edit Profile
                </x-filament::button>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Full Name</h3>
                    <p class="text-base font-semibold text-gray-900 dark:text-white mt-1">
                        {{ $this->user->name }}
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Username</h3>
                    <p class="text-base font-semibold text-gray-900 dark:text-white mt-1">
                        {{ $this->user->username }}
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</h3>
                    <p class="text-base font-semibold text-gray-900 dark:text-white mt-1">
                        {{ $this->user->email }}
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Model</h3>
                    <p class="text-base font-semibold text-gray-900 dark:text-white mt-1">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->user->is_model ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                            {{ $this->user->is_model ? 'Yes' : 'No' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 my-8">
            <div
                class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center">
                <div class="bg-indigo-100 dark:bg-indigo-900 p-3 rounded-full">
                    @svg('heroicon-o-document-text', 'w-6 h-6 text-indigo-600 dark:text-indigo-400')
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Posts</h3>
                    <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ $totalPosts ?? 0 }}
                    </p>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center">
                <div class="bg-amber-100 dark:bg-amber-900 p-3 rounded-full">
                    @svg('heroicon-o-clipboard-document-list', 'w-6 h-6 text-amber-600 dark:text-amber-400')
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Comments</h3>
                    <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ $totalComments ?? 0 }}
                    </p>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center">
                <div class="bg-emerald-100 dark:bg-emerald-900 p-3 rounded-full">
                    @svg('heroicon-o-currency-dollar', 'w-6 h-6 text-emerald-600 dark:text-emerald-400')
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Revenue</h3>
                    <p class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ $totalRevenue ?? '$0.00' }}</p>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center">
                <div class="bg-rose-100 dark:bg-rose-900 p-3 rounded-full">
                    @svg('heroicon-o-user-group', 'w-6 h-6 text-rose-600 dark:text-rose-400')
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Followers</h3>
                    <p class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ $totalFollowers ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Actions Tabs -->
        <div x-data="{ activeTab: 'account' }" x-cloak
            class="bg-white rounded-xl shadow-sm border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex flex-wrap -mb-px">
                    <button @click="activeTab = 'account'"
                        :class="{ 'border-primary-500 text-primary-600 dark:text-primary-500': activeTab === 'account', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'account' }"
                        class="py-4 px-6 text-center border-b-2 font-medium text-sm flex items-center space-x-2">
                        @svg('heroicon-o-user', 'w-5 h-5')
                        <span>Account ({{ count($actions) }})</span>
                    </button>
                    <button @click="activeTab = 'subscription'"
                        :class="{ 'border-primary-500 text-primary-600 dark:text-primary-500': activeTab === 'subscription', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'subscription' }"
                        class="py-4 px-6 text-center border-b-2 font-medium text-sm flex items-center space-x-2">
                        @svg('heroicon-o-credit-card', 'w-5 h-5')
                        <span>Subscription ({{ count($subscriptionActions) }})</span>
                    </button>
                    <button @click="activeTab = 'transactions'"
                        :class="{ 'border-primary-500 text-primary-600 dark:text-primary-500': activeTab === 'transactions', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'transactions' }"
                        class="py-4 px-6 text-center border-b-2 font-medium text-sm flex items-center space-x-2">
                        @svg('heroicon-o-currency-dollar', 'w-5 h-5')
                        <span>Transactions ({{ count($transactionActions) }})</span>
                    </button>
                    <button @click="activeTab = 'content'"
                        :class="{ 'border-primary-500 text-primary-600 dark:text-primary-500': activeTab === 'content', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'content' }"
                        class="py-4 px-6 text-center border-b-2 font-medium text-sm flex items-center space-x-2">
                        @svg('heroicon-o-document-text', 'w-5 h-5')
                        <span>Content ({{ count($postActions) + count($commentActions) }})</span>
                    </button>
                    <button @click="activeTab = 'more'"
                        :class="{ 'border-primary-500 text-primary-600 dark:text-primary-500': activeTab === 'more', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'more' }"
                        class="py-4 px-6 text-center border-b-2 font-medium text-sm flex items-center space-x-2">
                        @svg('heroicon-o-clipboard-document-list', 'w-5 h-5')
                        <span>More</span>
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Account Tab -->
                <div x-show="activeTab === 'account'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach ($actions as $action)
                        <a href="{{ $action['route'] }}"
                            class="group flex items-center p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 hover:bg-cyan-50 dark:hover:bg-gray-700 transition-colors">
                            <div
                                class="flex-shrink-0 bg-{{ $action['color'] }}-100 dark:bg-{{ $action['color'] }}-900 p-3 rounded-full group-hover:bg-{{ $action['color'] }}-200 dark:group-hover:bg-{{ $action['color'] }}-800 transition-colors">
                                @svg($action['icon'], 'w-6 h-6 text-' . $action['color'] . '-600 dark:text-' .
                                $action['color'] . '-400')
                            </div>
                            <div class="ml-4">
                                <h3
                                    class="text-base font-medium text-gray-900 dark:text-white group-hover:text-{{ $action['color'] }}-600 dark:group-hover:text-{{ $action['color'] }}-400 transition-colors">
                                    {{ $action['label'] }}</h3>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Subscription Tab -->
                <div x-show="activeTab === 'subscription'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach ($subscriptionActions as $action)
                        <a href="{{ $action['route'] }}"
                            class="group flex items-center p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 hover:bg-cyan-50 dark:hover:bg-gray-700 transition-colors">
                            <div
                                class="flex-shrink-0 bg-{{ $action['color'] }}-100 dark:bg-{{ $action['color'] }}-900 p-3 rounded-full group-hover:bg-{{ $action['color'] }}-200 dark:group-hover:bg-{{ $action['color'] }}-800 transition-colors">
                                @svg($action['icon'], 'w-6 h-6 text-' . $action['color'] . '-600 dark:text-' .
                                $action['color'] . '-400')
                            </div>
                            <div class="ml-4">
                                <h3
                                    class="text-base font-medium text-gray-900 dark:text-white group-hover:text-{{ $action['color'] }}-600 dark:group-hover:text-{{ $action['color'] }}-400 transition-colors">
                                    {{ $action['label'] }}</h3>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Transactions Tab -->
                <div x-show="activeTab === 'transactions'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach ($transactionActions as $action)
                        <a href="{{ $action['route'] }}"
                            class="group flex items-center p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 hover:bg-cyan-50 dark:hover:bg-gray-700 transition-colors">
                            <div
                                class="flex-shrink-0 bg-{{ $action['color'] }}-100 dark:bg-{{ $action['color'] }}-900 p-3 rounded-full group-hover:bg-{{ $action['color'] }}-200 dark:group-hover:bg-{{ $action['color'] }}-800 transition-colors">
                                @svg($action['icon'], 'w-6 h-6 text-' . $action['color'] . '-600 dark:text-' .
                                $action['color'] . '-400')
                            </div>
                            <div class="ml-4">
                                <h3
                                    class="text-base font-medium text-gray-900 dark:text-white group-hover:text-{{ $action['color'] }}-600 dark:group-hover:text-{{ $action['color'] }}-400 transition-colors">
                                    {{ $action['label'] }}</h3>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Content Tab -->
                <div x-show="activeTab === 'content'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div x-data="{ contentSection: 'posts' }" class="space-y-6">
                        <!-- Content Sub-navigation -->
                        <div class="flex space-x-2 mb-2">
                            <button @click="contentSection = 'posts'"
                                :class="{ 'bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300': contentSection === 'posts', 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300': contentSection !== 'posts' }"
                                class="px-4 py-2 rounded-md text-sm font-medium">
                                Posts ({{ count($postActions) }})
                            </button>
                            <button @click="contentSection = 'comments'"
                                :class="{ 'bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300': contentSection === 'comments', 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300': contentSection !== 'comments' }"
                                class="px-4 py-2 rounded-md text-sm font-medium">
                                Comments ({{ count($commentActions) }})
                            </button>
                            <button @click="contentSection = 'messages'"
                                :class="{ 'bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300': contentSection === 'messages', 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300': contentSection !== 'messages' }"
                                class="px-4 py-2 rounded-md text-sm font-medium">
                                Messages ({{ count($messageActions) }})
                            </button>
                        </div>

                        <!-- Posts Section -->
                        <div x-show="contentSection === 'posts'" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach ($postActions as $action)
                            <a href="{{ $action['route'] }}"
                                class="group flex items-center p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 hover:bg-cyan-50 dark:hover:bg-gray-700 transition-colors">
                                <div
                                    class="flex-shrink-0 bg-{{ $action['color'] }}-100 dark:bg-{{ $action['color'] }}-900 p-3 rounded-full group-hover:bg-{{ $action['color'] }}-200 dark:group-hover:bg-{{ $action['color'] }}-800 transition-colors">
                                    @svg($action['icon'], 'w-6 h-6 text-' . $action['color'] . '-600 dark:text-' .
                                    $action['color'] . '-400')
                                </div>
                                <div class="ml-4">
                                    <h3
                                        class="text-base font-medium text-gray-900 dark:text-white group-hover:text-{{ $action['color'] }}-600 dark:group-hover:text-{{ $action['color'] }}-400 transition-colors">
                                        {{ $action['label'] }}</h3>
                                </div>
                            </a>
                            @endforeach
                        </div>

                        <!-- Comments Section -->
                        <div x-show="contentSection === 'comments'" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach ($commentActions as $action)
                            <a href="{{ $action['route'] }}"
                                class="group flex items-center p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 hover:bg-cyan-50 dark:hover:bg-gray-700 transition-colors">
                                <div
                                    class="flex-shrink-0 bg-{{ $action['color'] }}-100 dark:bg-{{ $action['color'] }}-900 p-3 rounded-full group-hover:bg-{{ $action['color'] }}-200 dark:group-hover:bg-{{ $action['color'] }}-800 transition-colors">
                                    @svg($action['icon'], 'w-6 h-6 text-' . $action['color'] . '-600 dark:text-' .
                                    $action['color'] . '-400')
                                </div>
                                <div class="ml-4">
                                    <h3
                                        class="text-base font-medium text-gray-900 dark:text-white group-hover:text-{{ $action['color'] }}-600 dark:group-hover:text-{{ $action['color'] }}-400 transition-colors">
                                        {{ $action['label'] }}</h3>
                                </div>
                            </a>
                            @endforeach
                        </div>

                        <!-- Messages Section -->
                        <div x-show="contentSection === 'messages'" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach ($messageActions as $action)
                            <a href="{{ $action['route'] }}"
                                class="group flex items-center p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 hover:bg-cyan-50 dark:hover:bg-gray-700 transition-colors">
                                <div
                                    class="flex-shrink-0 bg-{{ $action['color'] }}-100 dark:bg-{{ $action['color'] }}-900 p-3 rounded-full group-hover:bg-{{ $action['color'] }}-200 dark:group-hover:bg-{{ $action['color'] }}-800 transition-colors">
                                    @svg($action['icon'], 'w-6 h-6 text-' . $action['color'] . '-600 dark:text-' .
                                    $action['color'] . '-400')
                                </div>
                                <div class="ml-4">
                                    <h3
                                        class="text-base font-medium text-gray-900 dark:text-white group-hover:text-{{ $action['color'] }}-600 dark:group-hover:text-{{ $action['color'] }}-400 transition-colors">
                                        {{ $action['label'] }}</h3>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- More Tab -->
                <div x-show="activeTab === 'more'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Reports Section -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3
                                class="text-lg font-semibold text-gray-900 dark:text-white mb-3 border-b border-gray-200 dark:border-gray-700 pb-2">
                                Reports ({{ count($reportActions) }})</h3>
                            <div class="grid grid-cols-1 gap-3">
                                @foreach ($reportActions as $action)
                                <a href="{{ $action['route'] }}"
                                    class="group flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 hover:bg-cyan-50 dark:hover:bg-gray-700 transition-colors">
                                    <div
                                        class="flex-shrink-0 bg-{{ $action['color'] }}-100 dark:bg-{{ $action['color'] }}-900 p-2 rounded-full group-hover:bg-{{ $action['color'] }}-200 dark:group-hover:bg-{{ $action['color'] }}-800 transition-colors">
                                        @svg($action['icon'], 'w-5 h-5 text-' . $action['color'] . '-600 dark:text-' .
                                        $action['color'] . '-400')
                                    </div>
                                    <div class="ml-3">
                                        <h3
                                            class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-{{ $action['color'] }}-600 dark:group-hover:text-{{ $action['color'] }}-400 transition-colors">
                                            {{ $action['label'] }}</h3>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Notifications Section -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3
                                class="text-lg font-semibold text-gray-900 dark:text-white mb-3 border-b border-gray-200 dark:border-gray-700 pb-2">
                                Notifications ({{ count($notificationActions) }})</h3>
                            <div class="grid grid-cols-1 gap-3">
                                @foreach ($notificationActions as $action)
                                <a href="{{ $action['route'] }}"
                                    class="group flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 hover:bg-cyan-50 dark:hover:bg-gray-700 transition-colors">
                                    <div
                                        class="flex-shrink-0 bg-{{ $action['color'] }}-100 dark:bg-{{ $action['color'] }}-900 p-2 rounded-full group-hover:bg-{{ $action['color'] }}-200 dark:group-hover:bg-{{ $action['color'] }}-800 transition-colors">
                                        @svg($action['icon'], 'w-5 h-5 text-' . $action['color'] . '-600 dark:text-' .
                                        $action['color'] . '-400')
                                    </div>
                                    <div class="ml-3">
                                        <h3
                                            class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-{{ $action['color'] }}-600 dark:group-hover:text-{{ $action['color'] }}-400 transition-colors">
                                            {{ $action['label'] }}</h3>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Settings Section -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3
                                class="text-lg font-semibold text-gray-900 dark:text-white mb-3 border-b border-gray-200 dark:border-gray-700 pb-2">
                                Settings ({{ count($settingsActions) }})</h3>
                            <div class="grid grid-cols-1 gap-3">
                                @foreach ($settingsActions as $action)
                                <a href="{{ $action['route'] }}"
                                    class="group flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 hover:bg-cyan-50 dark:hover:bg-gray-700 transition-colors">
                                    <div
                                        class="flex-shrink-0 bg-{{ $action['color'] }}-100 dark:bg-{{ $action['color'] }}-900 p-2 rounded-full group-hover:bg-{{ $action['color'] }}-200 dark:group-hover:bg-{{ $action['color'] }}-800 transition-colors">
                                        @svg($action['icon'], 'w-5 h-5 text-' . $action['color'] . '-600 dark:text-' .
                                        $action['color'] . '-400')
                                    </div>
                                    <div class="ml-3">
                                        <h3
                                            class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-{{ $action['color'] }}-600 dark:group-hover:text-{{ $action['color'] }}-400 transition-colors">
                                            {{ $action['label'] }}</h3>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>  

                        <!-- Model Section (conditional) -->
                        @if (count($modelActions) > 0 && $user->is_model)
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3
                                class="text-lg font-semibold text-gray-900 dark:text-white mb-3 border-b border-gray-200 dark:border-gray-700 pb-2">
                                Model Features ({{ count($modelActions) }})</h3>
                            <div class="grid grid-cols-1 gap-3">
                                @foreach ($modelActions as $action)
                                <a href="{{ $action['route'] }}"
                                    class="group flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 hover:bg-cyan-50 dark:hover:bg-gray-700 transition-colors">
                                    <div
                                        class="flex-shrink-0 bg-{{ $action['color'] }}-100 dark:bg-{{ $action['color'] }}-900 p-2 rounded-full group-hover:bg-{{ $action['color'] }}-200 dark:group-hover:bg-{{ $action['color'] }}-800 transition-colors">
                                        @svg($action['icon'], 'w-5 h-5 text-' . $action['color'] . '-600 dark:text-' .
                                        $action['color'] . '-400')
                                    </div>
                                    <div class="ml-3">
                                        <h3
                                            class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-{{ $action['color'] }}-600 dark:group-hover:text-{{ $action['color'] }}-400 transition-colors">
                                            {{ $action['label'] }}</h3>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>