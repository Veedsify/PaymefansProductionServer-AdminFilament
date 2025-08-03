<?php

use function Livewire\Volt\{state, layout, with, usesPagination, mount};

state(["userId"]);
usesPagination();
layout('layouts.user');
mount(function ($userId) {
    $this->userId = $userId;
});
// Fetch posts for the user
with(fn() => ['subscriptions' => \App\Models\UserSubscriptionCurrent::where("user_id", $this->userId)
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
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Subscription ID
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        User
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Model
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Subscription Status
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Subscription
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Subscription End Date
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($subscriptions as $subscription)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            {{ $subscription->id }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                            {{ $subscription->user->username }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            {{ $subscription->model->username }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            {{ $subscription->ends_at > now() ? 'Active' : 'Inactive' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                            {{ $subscription->subscription }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            {{ $subscription->ends_at->format('Y-m-d H:i:s') }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="col-span-4">
                <p class="text-center text-gray-500">No active subscriptions found for this user.</p>
            </div>
        @endif
        <div class="mt-4">
            {{ $subscriptions->links("vendor.livewire.tailwind")}}
        </div>
    </div>
</div>