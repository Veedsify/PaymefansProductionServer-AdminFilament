<?php

use function Livewire\Volt\{state};

//

?>

<div>
    <div class="space-y-6">
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="flex justify-between">
                <div>
                    <h4 class="text-sm font-medium text-gray-900">Billing Issue - Premium Plan</h4>
                    <p class="mt-1 text-xs text-gray-500">Ticket #5329 • Opened on May 15, 2023</p>
                </div>
                <span
                    class="px-2 h-6 flex items-center text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">In
                    Progress</span>
            </div>
            <p class="mt-3 text-sm text-gray-600 line-clamp-2">
                I was charged twice for my premium plan subscription this month. Can you please
                check my
                account and issue a refund for the duplicate charge?
            </p>
            <div class="mt-4 flex justify-end">
                <button class="text-sm text-cyan-600 hover:text-cyan-800 font-medium">
                    View Details
                </button>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="flex justify-between">
                <div>
                    <h4 class="text-sm font-medium text-gray-900">Feature Request - Dark Mode</h4>
                    <p class="mt-1 text-xs text-gray-500">Ticket #4873 • Opened on April 22,
                        2023</p>
                </div>
                <span
                    class="px-2 h-6 flex items-center text-xs font-semibold rounded-full bg-green-100 text-green-800">Resolved</span>
            </div>
            <p class="mt-3 text-sm text-gray-600 line-clamp-2">
                Would it be possible to add a dark mode option to the dashboard? It would be much
                easier
                on the eyes when working late at night.
            </p>
            <div class="mt-4 flex justify-end">
                <button class="text-sm text-cyan-600 hover:text-cyan-800 font-medium">
                    View Details
                </button>
            </div>
        </div>
    </div>
</div>