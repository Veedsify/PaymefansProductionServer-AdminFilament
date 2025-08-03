<?php

use function Livewire\Volt\{state, usesPagination, layout, mount, with};

state(["userId"]);
usesPagination();
layout('layouts.user');
mount(function ($userId) {
    $this->userId = $userId;
});
// Fetch posts for the user
with(fn() => ['comments' => \Illuminate\Support\Facades\DB::connection("mongodb")->table("comments")->where('userId', $this->userId)
    ->orderBy('date', 'desc')
    ->paginate(20),
    'main_app' => env("FRONTEND_URL"),
]);

?>

<div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        @if(isset($comments) && count($comments) > 0)
            @foreach($comments as $comment)
                <div class="p-4 bg-white rounded-lg shadow  dark:bg-gray-800">
                    <!-- Comment Content -->
                    <div class="space-y-2 dark:text-white">
                        <p class="text-sm text-gray-500 dark:text-white">{{ $comment->date->diffForHumans() }}</p>
                        <p class="text-gray-800 dark:text-white line-clamp-3">{{ $comment->comment }}</p>
                    </div>
                    <!-- Comment Stats -->
                    <div class="flex mt-2 text-sm text-gray-600 items center space-x-4 dark:text-white">
                        <span class="flex items center">
                            @svg('heroicon-o-hand-thumb-up', 'h-5 w-5 text-gray-500')
                            <span class="ml-1">{{ $comment->likes }} Likes</span>
                        </span>
                        <span class="flex items center">
                            @svg('heroicon-o-chat-bubble-left-ellipsis', 'h-5 w-5 text-gray-500')
                            <span class="ml-1">{{ $comment->replies }} Replies</span>
                        </span>
                        <span class="flex items center">
                            @svg('heroicon-o-chart-bar', 'h-5 w-5 text-gray-500')
                            <span class="ml-1">{{ $comment->impressions }} Impression</span>
                        </span>
                    </div>
                    {{-- Post--}}
                    <div class="mt-10">
                        <a target="_blank" href="{{ $main_app . "/posts/" . $comment->postId }}"
                           class="text-blue-500 hover:underline">
                            View Post
                        </a>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-span-4">
                <p class="text-center text-gray-500">No comments found for this user.</p>
            </div>
        @endif
    </div>
    <div class="mt-4">
        {{ $comments->links("vendor.livewire.tailwind") }}
    </div>
</div>