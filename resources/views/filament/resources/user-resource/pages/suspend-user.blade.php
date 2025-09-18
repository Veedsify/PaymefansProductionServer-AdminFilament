<x-filament-panels::page>
    <div class="flex flex-col items-center justify-center p-6 space-y-4">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
            {{ $user->active_status === true ? 'Suspend' : 'Reactivate' }} {{ explode(' ', $user->name)[0] ??
            $user->name }}'s
            Account</h2>

        <!-- User Profile Card -->
        @if($user->active_status === true)
        <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <div class="flex flex-col items-center">
                <!-- Profile Picture -->
                <div class="w-24 h-24 mb-4 overflow-hidden rounded-full">
                    @if($user->profile_photo_url)
                    <img src="{{ $user->profile_image }}" alt="{{ $user->name }}" class="object-cover w-full h-full">
                    @else
                    <div class="flex items-center justify-center w-full h-full bg-gray-200 dark:bg-gray-700">
                        <span class="text-2xl text-gray-500 dark:text-gray-400">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                    @endif
                </div>

                <!-- User Details -->
                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $user->name }}</h3>
                <p class="mb-4 text-gray-500 dark:text-gray-400">{{ $user->username }}</p>

                <div class="w-full my-4 border-t border-gray-200 dark:border-gray-700"></div>

                <p class="mb-2 text-center text-gray-600 dark:text-gray-300">This action will prevent the user from
                    logging in and
                    accessing their account.</p>
                <p class="text-sm text-center text-red-500 dark:text-red-400">This action can be reversed later.</p>
            </div>
        </div>

        <div class="mt-4">
            {{ $this->suspendUser }}
        </div>
        @else
        <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <div class="flex flex-col items-center">
                <!-- Profile Picture -->
                <div class="w-24 h-24 mb-4 overflow-hidden rounded-full">
                    @if($user->profile_photo_url)
                    <img src="{{ $user->profile_image }}" alt="{{ $user->name }}" class="object-cover w-full h-full">
                    @else
                    <div class="flex items-center justify-center w-full h-full bg-gray-200 dark:bg-gray-700">
                        <span class="text-2xl text-gray-500 dark:text-gray-400">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                    @endif
                </div>

                <!-- User Details -->
                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $user->name }}</h3>
                <p class="mb-4 text-gray-500 dark:text-gray-400">{{ $user->username }}</p>

                <div class="w-full my-4 border-t border-gray-200 dark:border-gray-700"></div>

                <p class="mb-2 text-center text-gray-600 dark:text-gray-300">This user account is already suspended.</p>
                <div class="block mt-4">
                    {{ $this->unSuspendUser }}
                </div>
            </div>
        </div>
        @endif
        <x-filament-actions::modals />
    </div>
</x-filament-panels::page>