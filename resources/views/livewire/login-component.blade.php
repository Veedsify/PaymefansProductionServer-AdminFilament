<div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-purple-50 via-white to-purple-100">
    <div class="w-full max-w-md p-8">
        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <div class="flex items-center justify-center bg-purple-100 rounded-full shadow h-14 w-14">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" />
                </svg>
            </div>
        </div>
        <!-- Header -->
        <div class="text-center">
            <h2 class="text-3xl font-semibold text-gray-900">Sign in</h2>
            <p class="mt-2 text-base text-gray-600">
                to continue to your account
            </p>
        </div>

        <!-- Form -->
        <div class="p-8 mt-8 bg-white border border-gray-100 shadow-lg rounded-2xl">
            <form wire:submit.prevent="login" class="space-y-6" autocomplete="off">
                <!-- Email -->
                <div>
                    @error('error')
                    <p class="mb-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div class="relative">
                        <input wire:model="email" id="email" type="email" required
                            class="block w-full px-4 pt-6 pb-2 text-gray-900 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-purple-400 peer transition"
                            placeholder=" ">
                        <label for="email"
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-5 z-10 origin-[0] left-4 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:-translate-y-4 peer-focus:scale-75">
                            Email address
                        </label>
                    </div>
                    @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <div class="relative">
                        <input wire:model="password" id="password" type="password" required
                            class="block w-full px-4 pt-6 pb-2 text-gray-900 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-purple-400 peer transition"
                            placeholder=" ">
                        <label for="password"
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-5 z-10 origin-[0] left-4 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:-translate-y-4 peer-focus:scale-75">
                            Password
                        </label>
                    </div>
                    @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input wire:model="remember" id="remember" type="checkbox"
                            class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-400 transition">
                        <label for="remember" class="ml-2 text-sm text-gray-900">
                            Stay signed in
                        </label>
                    </div>
                    <div class="text-sm">
                        <a href="#" class="font-medium text-purple-500 hover:text-purple-700 transition">
                            Forgot password?
                        </a>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-4">
                    <a href="#" class="text-sm font-medium text-purple-500 hover:text-purple-700 transition">
                        Create account
                    </a>
                    <button type="submit" wire:loading.attr="disabled"
                        class="flex items-center py-2 text-sm font-semibold text-white bg-purple-600 rounded-lg shadow px-7 gap-2 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-400 transition">
                        <span wire:loading.remove>Next</span>
                        <div wire:loading wire.target="login" class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>