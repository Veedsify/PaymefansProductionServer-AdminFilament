<x-filament-panels::page>
    <div class="space-y-6">
        <!-- User Info Header -->
        <div class="p-6 bg-white rounded-lg dark:bg-gray-900 shadow-sm">
            <div class="flex flex-wrap items-center space-x-4 space-y-2">
                <div class="flex items-center justify-center w-16 h-16 overflow-hidden bg-gray-200 rounded-full">
                    @if($record->profile_image)
                    <img src="{{ $record->profile_image }}" alt="Profile" class="object-cover w-full h-full">
                    @else
                    <div class="flex items-center justify-center w-full h-full text-gray-600 bg-gray-300">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    @endif
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $record->name }}</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $record->email }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $record->username }}</p>
                </div>
                <div class="ml-auto">
                    <div class="flex flex-wrap space-x-2">
                        @if($record->is_active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Inactive
                        </span>
                        @endif

                        @if($record->admin)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            Admin
                        </span>
                        @endif

                        @if($record->is_model)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Content Creator
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Permission Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="p-4 bg-white rounded-lg dark:bg-gray-900 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Role</p>
                        <p class="text-xs text-gray-500 capitalize dark:text-gray-400">{{ $record->role ?? 'fan' }}</p>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-white rounded-lg dark:bg-gray-900 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-lg">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Verified</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $record->is_verified ? 'Yes' : 'No' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-white rounded-lg dark:bg-gray-900 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center w-8 h-8 bg-yellow-100 rounded-lg">
                            <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Email Status</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $record->is_email_verified ? 'Verified' : 'Unverified' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-white rounded-lg dark:bg-gray-900 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center w-8 h-8 bg-indigo-100 rounded-lg">
                            <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Phone Status</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $record->is_phone_verified ? 'Verified' : 'Unverified' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions Form -->
        <div class="bg-white rounded-lg dark:bg-gray-900 shadow-sm">
            <div class="p-6">
                <form wire:submit="save">
                    {{ $this->form }}
                </form>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="p-6 bg-white rounded-lg dark:bg-gray-900 shadow-sm">
            <h3 class="mb-4 text-lg font-medium text-gray-900 dark:text-white">Quick Permission Templates</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <button type="button" wire:click="applyTemplate('basic_user')" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <div class="flex items-center justify-center w-8 h-8 mb-2 bg-gray-100 rounded-lg dark:bg-gray-700">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Basic User</span>
                    <span class="text-xs text-center text-gray-500 dark:text-gray-400">Standard user permissions</span>
                </button>

                <button type="button" wire:click="applyTemplate('content_creator')" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <div class="flex items-center justify-center w-8 h-8 mb-2 bg-blue-100 rounded-lg dark:bg-blue-900">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Content Creator</span>
                    <span class="text-xs text-center text-gray-500 dark:text-gray-400">Creator permissions</span>
                </button>

                <button type="button" wire:click="applyTemplate('moderator')" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <div class="flex items-center justify-center w-8 h-8 mb-2 bg-yellow-100 rounded-lg dark:bg-yellow-900">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Moderator</span>
                    <span class="text-xs text-center text-gray-500 dark:text-gray-400">Moderation privileges</span>
                </button>

                <button type="button" wire:click="applyTemplate('admin')" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <div class="flex items-center justify-center w-8 h-8 mb-2 bg-red-100 rounded-lg dark:bg-red-900">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-4 4-4-4 4-4 .257-.257A6 6 0 1118 8zm-6-2a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Administrator</span>
                    <span class="text-xs text-center text-gray-500 dark:text-gray-400">Full admin access</span>
                </button>
            </div>
        </div>

        <!-- Permission History (Optional) -->
        <div class="p-6 bg-white rounded-lg dark:bg-gray-900 shadow-sm">
            <h3 class="mb-4 text-lg font-medium text-gray-900 dark:text-white">Recent Permission Changes</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                    <div>
                        <p class="text-sm text-gray-900 dark:text-white">Account created</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Initial permissions assigned</p>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $record->created_at->format('M j, Y \a\t g:i A') }}
                    </span>
                </div>
                @if($record->updated_at->gt($record->created_at))
                <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                    <div>
                        <p class="text-sm text-gray-900 dark:text-white">Account updated</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Permissions may have been modified</p>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $record->updated_at->format('M j, Y \a\t g:i A') }}
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>
