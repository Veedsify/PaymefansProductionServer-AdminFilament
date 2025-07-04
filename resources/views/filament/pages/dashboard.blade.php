<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Welcome Header -->
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold dark:text-white">Welcome back, {{ auth()->user()->name }}!</h1>
                    <p class="text-purple-100 mt-1">
                        Here's what's happening with PayMeFans today
                    </p>
                </div>
                <div class="hidden md:block">
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-sm text-purple-100">Current Time</div>
                            <div class="text-lg font-semibold" id="current-time"></div>
                        </div>
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                            <x-filament::icon
                                icon="heroicon-s-chart-bar"
                                class="w-6 h-6"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Widgets -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($this->getWidgets() as $widget)
                @livewire($widget)
            @endforeach
        </div>

        <!-- Quick Stats Footer -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-green-100 dark:bg-green-900/50 rounded-lg mb-3">
                        <x-filament::icon
                            icon="heroicon-s-arrow-trending-up"
                            class="w-6 h-6 text-green-600 dark:text-green-400"
                        />
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Growing Platform</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Your platform is actively growing with engaged users and content creators
                    </p>
                </div>

                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-lg mb-3">
                        <x-filament::icon
                            icon="heroicon-s-shield-check"
                            class="w-6 h-6 text-blue-600 dark:text-blue-400"
                        />
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Secure Operations</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        All transactions and user data are protected with enterprise-grade security
                    </p>
                </div>

                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-purple-100 dark:bg-purple-900/50 rounded-lg mb-3">
                        <x-filament::icon
                            icon="heroicon-s-rocket-launch"
                            class="w-6 h-6 text-purple-600 dark:text-purple-400"
                        />
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Optimized Performance</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Advanced analytics and monitoring ensure optimal platform performance
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Real-time Clock Script -->
    <script>
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {
                hour12: true,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('current-time').textContent = timeString;
        }

        // Update time immediately and then every second
        updateTime();
        setInterval(updateTime, 1000);
    </script>

    <!-- Custom Styles for Dashboard -->
    <style>
        .filament-widgets-chart-widget canvas {
            border-radius: 0.5rem;
        }

        .filament-stats-overview-widget .filament-stats-overview-widget-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .filament-stats-overview-widget .filament-stats-overview-widget-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .dashboard-widget {
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</x-filament-panels::page>
