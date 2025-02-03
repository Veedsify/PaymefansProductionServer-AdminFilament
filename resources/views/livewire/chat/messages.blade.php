<div class="lg:p-2 py-5 col-span-7 lg:col-span-9 bg-white dark:bg-gray-800 w-full">
    <div class="flex flex-col h-full">
        <!-- Chat Header -->
        <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
            <div class="flex items-center">
                <div class="block lg:hidden ">
                    @svg('ionicon-chevron-back')
                </div>
                <div class="flex items-center space-x-3">
                    <img src="https://picsum.photos/150/150" class="w-10 h-10 rounded-full outline outline-purple-400">
                    <div>
                        <h3 class="font-semibold dark:text-white">{{ auth()->user()->name }}</h3>
                        <span class="text-xs font-medium text-green-500">Active Now</span>
                    </div>
                </div>
            </div>
            <button class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 dark:text-white" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                </svg>
            </button>
        </div>

        <!-- Messages Area -->
        <div class="flex-1 overflow-y-auto max-h-[70vh] p-2 lg:p-4 space-y-4 sleek_scrollbar dark:bg-gray-800">
            <div class="flex flex-col space-y-4">
                <!-- Sender Message -->
                <div class="flex justify-end">
                    <div class="bg-purple-500 text-white rounded-3xl p-3 max-w-md text-sm lg:text-base">
                        <p>Hey! How are you doing today?</p>
                    </div>
                </div>

                <!-- Receiver Message -->
                <div class="flex justify-start">
                    <div class="bg-gray-100 dark:bg-gray-700 dark:text-white rounded-3xl p-3 max-w-md text-sm lg:text-base">
                        <p>I'm doing great, thanks! Check out this photo from yesterday:</p>
                    </div>
                </div>

                <!-- Receiver Image Message -->
                <div class="flex justify-start">
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-3xl p-2 max-w-md">
                        <img src="https://picsum.photos/300/200" class="rounded-2xl">
                    </div>
                </div>

                <!-- Sender Message -->
                <div class="flex justify-end">
                    <div class="bg-purple-500 text-white rounded-3xl p-3 max-w-md text-sm lg:text-base">
                        <p>That looks amazing! Here's a video I wanted to share:</p>
                    </div>
                </div>

                <!-- Sender Video Message -->
                <div class="flex justify-end">
                    <div class="bg-purple-500 text-white rounded-3xl p-2 max-w-md">
                        <video class="rounded-2xl" controls>
                            <source src="sample-video.mp4" type="video/mp4">
                        </video>
                    </div>
                </div>

                <!-- Receiver Message -->
                <div class="flex justify-start">
                    <div class="bg-gray-100 dark:bg-gray-700 dark:text-white rounded-3xl p-3 max-w-md text-sm lg:text-base">
                        <p>Thanks for sharing! Let's meet up soon 😊</p>
                    </div>
                </div>

                <div class="flex justify-end">
                    <div class="bg-purple-500 text-white rounded-3xl p-3 max-w-md text-sm lg:text-base">
                        <p>Hey! How are you doing today?</p>
                    </div>
                </div>
                <div class="flex justify-end">
                    <div class="bg-purple-500 text-white rounded-3xl p-2 max-w-md">
                        <video class="rounded-2xl" controls>
                            <source src="sample-video.mp4" type="video/mp4">
                        </video>
                    </div>
                </div>
            </div>
        </div>

        <!-- Message Input -->
        <div class="border-t dark:border-gray-700 p-4" x-data="{ attachMenu: false }">
            <div class="flex items-center gap-2 lg:gap-4">
                <button @click="attachMenu = !attachMenu" class="hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 dark:text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                </button>
                <input type="text" placeholder="Type your message..."
                    class="flex-1 border dark:border-gray-700 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 rounded-full text-sm lg:text-base lg:px-4 lg:py-3 focus:outline-none focus:border-purple-500">
                <button
                    class="p-3 lg:text-base bg-purple-500 text-white rounded-full hover:bg-purple-600 flex items-center gap-2">
                    <span class="text-sm font- pl-4 lg:block hidden">
                        Send
                    </span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
            </div>

            <!-- Attachment Menu -->
            <div x-show="attachMenu" @click.away="attachMenu = false"
                class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-3xl p-6 lg:w-96">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold dark:text-white">Add Attachment</h3>
                        <button @click="attachMenu = false" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <button class="flex flex-col items-center p-4 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-xl transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-500 mb-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Image</span>
                        </button>
                        <button class="flex flex-col items-center p-4 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-xl transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-500 mb-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Video</span>
                        </button>
                    </div>
                    <div class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
                        .jpg, .png, .mp4
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
