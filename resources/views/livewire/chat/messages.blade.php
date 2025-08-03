<div class="w-full py-5 bg-white lg:p-2 col-span-7 lg:col-span-9 dark:bg-gray-800">
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
            <button class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 dark:text-white" fill="none" viewBox="0 0 24 24"
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
                    <div class="max-w-md p-3 text-sm text-white bg-purple-500 rounded-3xl lg:text-base">
                        <p>Hey! How are you doing today?</p>
                    </div>
                </div>

                <!-- Receiver Message -->
                <div class="flex justify-start">
                    <div class="max-w-md p-3 text-sm bg-gray-100 dark:bg-gray-700 dark:text-white rounded-3xl lg:text-base">
                        <p>I'm doing great, thanks! Check out this photo from yesterday:</p>
                    </div>
                </div>

                <!-- Receiver Image Message -->
                <div class="flex justify-start">
                    <div class="max-w-md p-2 bg-gray-100 dark:bg-gray-700 rounded-3xl">
                        <img src="https://picsum.photos/300/200" class="rounded-2xl">
                    </div>
                </div>

                <!-- Sender Message -->
                <div class="flex justify-end">
                    <div class="max-w-md p-3 text-sm text-white bg-purple-500 rounded-3xl lg:text-base">
                        <p>That looks amazing! Here's a video I wanted to share:</p>
                    </div>
                </div>

                <!-- Sender Video Message -->
                <div class="flex justify-end">
                    <div class="max-w-md p-2 text-white bg-purple-500 rounded-3xl">
                        <video class="rounded-2xl" controls>
                            <source src="sample-video.mp4" type="video/mp4">
                        </video>
                    </div>
                </div>

                <!-- Receiver Message -->
                <div class="flex justify-start">
                    <div class="max-w-md p-3 text-sm bg-gray-100 dark:bg-gray-700 dark:text-white rounded-3xl lg:text-base">
                        <p>Thanks for sharing! Let's meet up soon 😊</p>
                    </div>
                </div>

                <div class="flex justify-end">
                    <div class="max-w-md p-3 text-sm text-white bg-purple-500 rounded-3xl lg:text-base">
                        <p>Hey! How are you doing today?</p>
                    </div>
                </div>
                <div class="flex justify-end">
                    <div class="max-w-md p-2 text-white bg-purple-500 rounded-3xl">
                        <video class="rounded-2xl" controls>
                            <source src="sample-video.mp4" type="video/mp4">
                        </video>
                    </div>
                </div>
            </div>
        </div>

        <!-- Message Input -->
        <div class="p-4 border-t dark:border-gray-700" x-data="{ attachMenu: false }">
            <div class="flex items-center gap-2 lg:gap-4">
                <button @click="attachMenu = !attachMenu" class="rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 dark:text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                </button>
                <input type="text" placeholder="Type your message..."
                    class="flex-1 text-sm border rounded-full dark:border-gray-700 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 lg:text-base lg:px-4 lg:py-3 focus:outline-none focus:border-purple-500">
                <button
                    class="flex items-center p-3 text-white bg-purple-500 rounded-full lg:text-base hover:bg-purple-600 gap-2">
                    <span class="hidden pl-4 text-sm font- lg:block">
                        Send
                    </span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
            </div>

            <!-- Attachment Menu -->
            <div x-show="attachMenu" @click.away="attachMenu = false"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="p-6 bg-white shadow-lg dark:bg-gray-800 rounded-3xl lg:w-96">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold dark:text-white">Add Attachment</h3>
                        <button @click="attachMenu = false" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <button class="flex flex-col items-center p-4 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-xl transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mb-2 text-purple-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Image</span>
                        </button>
                        <button class="flex flex-col items-center p-4 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-xl transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mb-2 text-purple-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Video</span>
                        </button>
                    </div>
                    <div class="mt-6 text-sm text-center text-gray-500 dark:text-gray-400">
                        .jpg, .png, .mp4
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
