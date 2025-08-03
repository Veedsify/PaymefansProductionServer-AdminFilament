<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Quick Actions
        </x-slot>

        <x-slot name="description">
            Common administrative tasks and shortcuts
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($this->getViewData()['actions'] as $action)
                <div class="relative">
                    <a
                        href="{{ $action['url'] }}"
                        class="flex items-center p-4 bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 group"
                    >
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-{{ $action['color'] }}-100 dark:bg-{{ $action['color'] }}-900/50">
                                <x-filament::icon
                                    :icon="$action['icon']"
                                    class="w-6 h-6 text-{{ $action['color'] }}-600 dark:text-{{ $action['color'] }}-400"
                                />
                            </div>
                        </div>

                        <div class="flex-1 ml-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-{{ $action['color'] }}-600 dark:group-hover:text-{{ $action['color'] }}-400 transition-colors">
                                    {{ $action['label'] }}
                                </h3>

                                @if($action['count'] !== null && $action['count'] > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $action['color'] }}-100 text-{{ $action['color'] }}-800 dark:bg-{{ $action['color'] }}-900/50 dark:text-{{ $action['color'] }}-400">
                                        {{ $action['count'] }}
                                    </span>
                                @endif
                            </div>

                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                {{ $action['description'] }}
                            </p>
                        </div>

                        <div class="flex-shrink-0 ml-2">
                            <x-filament::icon
                                icon="heroicon-m-chevron-right"
                                class="w-4 h-4 text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300 transition-colors"
                            />
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Platform Status Summary -->
        <div class="pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $this->getViewData()['pendingWithdrawals'] }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        Pending Withdrawals
                    </div>
                </div>

                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $this->getViewData()['pendingReports'] }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        Pending Reports
                    </div>
                </div>

                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $this->getViewData()['newUsers'] }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        New Users (7d)
                    </div>
                </div>

                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $this->getViewData()['recentPosts'] }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        Posts Today
                    </div>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
