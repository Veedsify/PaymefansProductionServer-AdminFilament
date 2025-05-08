<?php

use function Livewire\Volt\{state, layout, with, usesPagination, mount};

state(["userId"]);
usesPagination();
layout('layouts.user');
mount(function ($userId) {
    $this->userId = $userId;
});
// Fetch posts for the user
with(fn() => ['subscriptions' => \App\Models\UserSubscriptionHistory::where("user_id", $this->userId)
    ->orderBy('date', 'desc')
    ->paginate(20),
    'main_app' => env("FRONTEND_URL"),
]);

?>

<div>
    <div class="overflow-x-auto">
        @if(isset($subscriptions) && count($subscriptions) > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Subscription ID
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        User
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Model
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Subscription
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($subscriptions as $subscription)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $subscription->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $subscription->user->username }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $subscription->model->username }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $subscription->subscription }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="col-span-4">
                <p class="text-center text-gray-500">No subscriptions found for this user.</p>
            </div>
        @endif
        <div class="mt-4">
            {{ $subscriptions->links("vendor.livewire.tailwind")}}
        </div>
    </div>
</div>