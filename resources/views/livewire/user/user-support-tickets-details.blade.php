<?php

use function Livewire\Volt\{state};

//

?>

<div>
    <div class="space-y-6">
        <div class="p-4 bg-white border border-gray-200 rounded-lg">
            <div class="flex justify-between">
                <div>
                    <h4 class="text-sm font-medium text-gray-900">Billing Issue - Premium Plan</h4>
                    <p class="mt-1 text-xs text-gray-500">Ticket #5329 • Opened on May 15, 2023</p>
                </div>
                <span
                    class="flex items-center h-6 px-2 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">In
                    Progress</span>
            </div>
            <p class="mt-3 text-sm text-gray-600 line-clamp-2">
                I was charged twice for my premium plan subscription this month. Can you please
                check my
                account and issue a refund for the duplicate charge?
            </p>
            <div class="flex justify-end mt-4">
                <button class="text-sm font-medium text-cyan-600 hover:text-cyan-800">
                    View Details
                </button>
            </div>
        </div>

        <div class="p-4 bg-white border border-gray-200 rounded-lg">
            <div class="flex justify-between">
                <div>
                    <h4 class="text-sm font-medium text-gray-900">Feature Request - Dark Mode</h4>
                    <p class="mt-1 text-xs text-gray-500">Ticket #4873 • Opened on April 22,
                        2023</p>
                </div>
                <span
                    class="flex items-center h-6 px-2 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Resolved</span>
            </div>
            <p class="mt-3 text-sm text-gray-600 line-clamp-2">
                Would it be possible to add a dark mode option to the dashboard? It would be much
                easier
                on the eyes when working late at night.
            </p>
            <div class="flex justify-end mt-4">
                <button class="text-sm font-medium text-cyan-600 hover:text-cyan-800">
                    View Details
                </button>
            </div>
        </div>
    </div>
</div>