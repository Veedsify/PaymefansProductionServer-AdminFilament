<?php

use function Livewire\Volt\{state, layout, with, usesPagination, mount};
use App\Models\User;
use App\Models\Notification;

state(["userId"]);
usesPagination();
layout('layouts.user');
mount(function ($userId) {
    $this->userId = $userId;
});
with(function(){
    return [
        'notifications' => Notification::where('user_id', $this->userId)
            ->orderBy('created_at', 'desc')
            ->paginate(20)
    ];
})
?>

<div>
    <div class="flex items-center justify-between pb-2 mb-4 border-b border-gray-100">
        <h3 class="text-lg font-medium text-gray-900">Notifications</h3>
        <button class="px-3 py-1 text-sm rounded-full bg-cyan-50 text-cyan-700 hover:bg-cyan-100">
            Mark All as Read
        </button>
    </div>

    <div class="space-y-4">
        @foreach($notifications as $notification)
        <div class="p-4 border-l-4 {{$notification->read ? " border-gray-400 bg-gray-50" : "border-cyan-400 bg-cyan-50"
            }} rounded-r-md">
            <div class="flex justify-between">
                <h4 class="text-sm font-medium text-gray-900 {{ $notification->read ? " text-gray-600" : "text-cyan-700"
                    }}">{{ $notification->action ?? 'Notification' }}
                </h4>
                <span class="text-xs text-cyan-600 {{ $notification->read ? " text-gray-600" : "text-cyan-600" }}">
                    {{ $notification->created_at->diffForHumans() }}
                </span>
            </div>
            <p class="mt-1 text-sm {{ $notification->read ? " text-gray-700" : "text-cyan-700" }}">
                {!! $notification->message ?? 'You have a new notification.' !!}
            </p>
        </div>
        @endforeach

        <!-- Pagination -->
        <div class="col-span-1 md:col-span-2 lg:col-span-4">
            <div class="flex justify-center mt-4">
                {{ $notifications->links("vendor.livewire.simple-tailwind") }}
            </div>
        </div>
    </div>

    <div class="mt-6 text-center">
        <button class="text-sm font-medium text-cyan-600 hover:text-cyan-800">
            View All Notifications
        </button>
    </div>
</div>