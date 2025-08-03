<x-filament-widgets::widget
    columnSpan="full"
>
    <x-filament::section>
        <x-slot name="heading">
            System Health Monitor
        </x-slot>

        <x-slot name="description">
            Real-time platform health and performance metrics
        </x-slot>

        <div class="space-y-6">
            <!-- Overall Health Score -->
            @php
                $healthColor = $this->getViewData()['healthScore']['color'];
                $gradientClass = match($healthColor) {
                    'green' => 'bg-gradient-to-r from-green-500 to-green-600',
                    'yellow' => 'bg-gradient-to-r from-yellow-500 to-yellow-600',
                    'red' => 'bg-gradient-to-r from-red-500 to-red-600',
                    default => 'bg-gradient-to-r from-gray-500 to-gray-600'
                };
                $textClass = match($healthColor) {
                    'green' => 'text-green-100',
                    'yellow' => 'text-yellow-100',
                    'red' => 'text-red-100',
                    default => 'text-gray-100'
                };
            @endphp
            <div class="{{ $gradientClass }} rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Overall Health Score</h3>
                        <p class="{{ $textClass }} text-sm">
                            {{ $this->getViewData()['healthScore']['status'] }}
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="text-4xl font-bold">{{ $this->getViewData()['healthScore']['total'] }}%</div>
                        <div class="text-sm opacity-90">System Health</div>
                    </div>
                </div>

                <!-- Health Score Breakdown -->
                <div class="mt-6 grid grid-cols-2 md:grid-cols-5 gap-4">
                    @foreach($this->getViewData()['healthScore']['breakdown'] as $metric => $score)
                        <div class="text-center">
                            <div class="text-xl font-semibold">{{ round($score) }}%</div>
                            <div class="text-xs capitalize opacity-90">
                                {{ str_replace('_', ' ', $metric) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- System Metrics Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="p-4 bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg dark:bg-green-900/50">
                            <x-filament::icon
                                icon="heroicon-s-users"
                                class="w-6 h-6 text-green-600 dark:text-green-400"
                            />
                        </div>
                        <div class="ml-3">
                            <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ number_format($this->getViewData()['systemMetrics']['active_users_24h']) }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Active Users (24h)</div>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg dark:bg-blue-900/50">
                            <x-filament::icon
                                icon="heroicon-s-photo"
                                class="w-6 h-6 text-blue-600 dark:text-blue-400"
                            />
                        </div>
                        <div class="ml-3">
                            <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ number_format($this->getViewData()['systemMetrics']['posts_24h']) }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Posts (24h)</div>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 rounded-lg dark:bg-purple-900/50">
                            <x-filament::icon
                                icon="heroicon-s-banknotes"
                                class="w-6 h-6 text-purple-600 dark:text-purple-400"
                            />
                        </div>
                        <div class="ml-3">
                            <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                ₦{{ number_format($this->getViewData()['systemMetrics']['revenue_24h'], 2) }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Revenue (24h)</div>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="p-2 bg-red-100 rounded-lg dark:bg-red-900/50">
                            <x-filament::icon
                                icon="heroicon-s-video-camera"
                                class="w-6 h-6 text-red-600 dark:text-red-400"
                            />
                        </div>
                        <div class="ml-3">
                            <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ number_format($this->getViewData()['systemMetrics']['live_streams_active']) }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Live Streams</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- API Status -->
                <div class="bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">API Connectivity</h4>
                    </div>
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-{{ $this->getViewData()['apiStatus']['status'] === 'healthy' ? 'green' : ($this->getViewData()['apiStatus']['status'] === 'warning' ? 'yellow' : 'red') }}-500 mr-3"></div>
                                <span class="font-medium text-gray-900 capitalize dark:text-white">
                                    {{ $this->getViewData()['apiStatus']['status'] }}
                                </span>
                            </div>
                            @if($this->getViewData()['apiStatus']['response_time'])
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ round($this->getViewData()['apiStatus']['response_time'] * 1000) }}ms
                                </span>
                            @endif
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            {{ $this->getViewData()['apiStatus']['message'] }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">
                            Last checked: {{ $this->getViewData()['apiStatus']['last_checked']->diffForHumans() }}
                        </p>
                    </div>
                </div>

                <!-- Database Status -->
                <div class="bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Database Health</h4>
                    </div>
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-{{ $this->getViewData()['databaseHealth']['status'] === 'healthy' ? 'green' : 'red' }}-500 mr-3"></div>
                                <span class="font-medium text-gray-900 capitalize dark:text-white">
                                    {{ $this->getViewData()['databaseHealth']['status'] }}
                                </span>
                            </div>
                            @if($this->getViewData()['databaseHealth']['response_time'])
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $this->getViewData()['databaseHealth']['response_time'] }}ms
                                </span>
                            @endif
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            {{ $this->getViewData()['databaseHealth']['message'] }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">
                            Last checked: {{ $this->getViewData()['databaseHealth']['last_checked']->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- System Alerts -->
            @if(!empty($this->getViewData()['alerts']))
                <div class="bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">System Alerts</h4>
                    </div>
                    <div class="p-4 space-y-3">
                        @foreach($this->getViewData()['alerts'] as $alert)
                            <div class="flex items-start p-3 bg-{{ $alert['type'] === 'critical' ? 'red' : ($alert['type'] === 'warning' ? 'yellow' : 'blue') }}-50 dark:bg-{{ $alert['type'] === 'critical' ? 'red' : ($alert['type'] === 'warning' ? 'yellow' : 'blue') }}-900/20 rounded-lg border border-{{ $alert['type'] === 'critical' ? 'red' : ($alert['type'] === 'warning' ? 'yellow' : 'blue') }}-200 dark:border-{{ $alert['type'] === 'critical' ? 'red' : ($alert['type'] === 'warning' ? 'yellow' : 'blue') }}-800">
                                <div class="flex-shrink-0 mr-3">
                                    <x-filament::icon
                                        :icon="$alert['type'] === 'critical' ? 'heroicon-s-exclamation-triangle' : ($alert['type'] === 'warning' ? 'heroicon-s-exclamation-circle' : 'heroicon-s-information-circle')"
                                        class="w-5 h-5 text-{{ $alert['type'] === 'critical' ? 'red' : ($alert['type'] === 'warning' ? 'yellow' : 'blue') }}-600"
                                    />
                                </div>
                                <div class="flex-1">
                                    <h5 class="font-medium text-{{ $alert['type'] === 'critical' ? 'red' : ($alert['type'] === 'warning' ? 'yellow' : 'blue') }}-800 dark:text-{{ $alert['type'] === 'critical' ? 'red' : ($alert['type'] === 'warning' ? 'yellow' : 'blue') }}-200">
                                        {{ $alert['title'] }}
                                    </h5>
                                    <p class="text-sm text-{{ $alert['type'] === 'critical' ? 'red' : ($alert['type'] === 'warning' ? 'yellow' : 'blue') }}-700 dark:text-{{ $alert['type'] === 'critical' ? 'red' : ($alert['type'] === 'warning' ? 'yellow' : 'blue') }}-300 mt-1">
                                        {{ $alert['message'] }}
                                    </p>
                                    @if($alert['action_url'] && $alert['action_label'])
                                        <a
                                            href="{{ $alert['action_url'] }}"
                                            class="inline-flex items-center text-sm font-medium text-{{ $alert['type'] === 'critical' ? 'red' : ($alert['type'] === 'warning' ? 'yellow' : 'blue') }}-600 hover:text-{{ $alert['type'] === 'critical' ? 'red' : ($alert['type'] === 'warning' ? 'yellow' : 'blue') }}-500 mt-2"
                                        >
                                            {{ $alert['action_label'] }}
                                            <x-filament::icon
                                                icon="heroicon-m-arrow-right"
                                                class="w-4 h-4 ml-1"
                                            />
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- System Uptime -->
            <div class="bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">System Uptime</h4>
                </div>
                <div class="p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-green-600">
                                {{ $this->getViewData()['uptime']['percentage'] }}%
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                Uptime since {{ $this->getViewData()['uptime']['since']->format('M j, Y') }}
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $this->getViewData()['uptime']['total_time'] }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                Total runtime
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Auto-refresh indicator -->
        <div class="mt-4 text-center">
            <span class="text-xs text-gray-500 dark:text-gray-400">
                Auto-refreshes every 60 seconds
            </span>
        </div>
    </x-filament::section>

    <script>
        // Auto-refresh the widget every 60 seconds
        setInterval(() => {
            window.location.reload();
        }, 60000);
    </script>
</x-filament-widgets::widget>
