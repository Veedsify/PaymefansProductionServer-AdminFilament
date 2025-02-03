<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="max-w-lg w-full p-8">
        <!-- Header -->
        <div class="text-center">
            <h2 class="text-2xl font-normal text-gray-900">Sign in</h2>
            <p class="mt-2 text-sm text-gray-600">
                to continue to your account
            </p>
        </div>

        <!-- Form -->
        <div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <form wire:submit="login" class="space-y-5">
                <!-- Email -->
                <div>
                    @error('error')
                        <p class="mb-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div class="relative">
                        <input wire:model="email" id="email" type="email" required
                            class="block w-full px-3 pt-5 pb-2 border border-gray-300 rounded-md text-gray-900 focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-purple-500 peer"
                            placeholder=" ">
                        <label for="email"
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-3 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:-translate-y-4 peer-focus:scale-75">
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
                            class="block w-full px-3 pt-5 pb-2 border border-gray-300 rounded-md text-gray-900 focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-purple-500 peer"
                            placeholder=" ">
                        <label for="password"
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-3 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:-translate-y-4 peer-focus:scale-75">
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
                            class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 text-sm text-gray-900">
                            Stay signed in
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="" class="font-medium text-purple-600 hover:text-purple-700">
                            Forgot password?
                        </a>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-4">
                    <a href="" class="text-sm font-medium text-purple-600 hover:text-purple-700">
                        Create account
                    </a>
                    <button type="submit" wire:loading.attr="disabled"
                        class="px-6 flex items-center gap-2 py-2 text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 rounded">
                        <div>
                            Next
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
