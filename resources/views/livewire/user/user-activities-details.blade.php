<?php

use function Livewire\Volt\{state};

//

?>

<div>
    <div class="space-y-6">
        <!-- Activity Item -->
        <div class="flex">
            <div class="flex-shrink-0 mr-4">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-cyan-100 text-cyan-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600">Logged in from a new device in <span
                        class="font-medium text-gray-900">New
                        York, USA</span></p>
                <p class="mt-1 text-xs text-gray-500">2 hours ago</p>
            </div>
        </div>

        <!-- Activity Item -->
        <div class="flex">
            <div class="flex-shrink-0 mr-4">
                <div class="flex items-center justify-center w-10 h-10 text-green-500 bg-green-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600">Made a purchase of <span class="font-medium text-gray-900">Premium
                        Annual
                        Plan</span> for <span class="font-medium text-gray-900">$99.00</span></p>
                <p class="mt-1 text-xs text-gray-500">Yesterday at 11:23 AM</p>
            </div>
        </div>

        <!-- Activity Item -->
        <div class="flex">
            <div class="flex-shrink-0 mr-4">
                <div class="flex items-center justify-center w-10 h-10 text-purple-500 bg-purple-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600">Updated profile information</p>
                <p class="mt-1 text-xs text-gray-500">3 days ago</p>
            </div>
        </div>
    </div>
</div>