<?php

use function Livewire\Volt\{layout, with, state, usesPagination, mount};
use App\Models\Post;

state(["userId"]);
usesPagination();
layout('layouts.user');
mount(function ($userId) {
    $this->userId = $userId;
});
// Fetch posts for the user
with(fn() => ['posts' => Post::where('user_id', $this->userId)
    ->orderBy('created_at', 'desc')
    ->paginate(20)]);

?>

<div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @if(isset($posts) && count($posts) > 0)
            @foreach($posts as $post)
                <div class="p-4 bg-white rounded-lg shadow dark:bg-gray-800">
                    <!-- Post Image -->
                    @if(isset($post->user_media) && count($post->user_media) > 0)
                        <div class="aspect-w-16 aspect-h-9 mb-4 grid {{count($post->user_media)>1 ? "grid-cols-2": "grid-cols-1"}} gap-2">
                            @foreach($post->user_media as $media)
                                @if($loop->index > 3)
                                    @break
                                @endif
                                @if($media->media_type == 'video')
                                    <video id="my-video" class="video-js vjs-default-skin" controls preload="auto"
                                           poster="{{ $media->poster }}" data-setup='{
                                        "controlBar": {
                                            "playToggle": true,
                                            "volumePanel": { "inline": false },
                                            "fullscreenToggle": true
                                        }
                                    }'>
                                        <source src="{{ $media->url }}" type="video/webm"/>
                                        <p class="vjs-no-js">
                                            To view this video please enable JavaScript, and consider upgrading to a
                                            web browser that
                                            <a href="https://videojs.com/html5-video-support/" target="_blank">supports
                                                HTML5 video</a>
                                        </p>
                                    </video>
                                @else
                                    <img src="{{ $media->url }}" alt="Post" class="object-cover w-full h-full rounded">
                                @endif
                            @endforeach
                        </div>
                    @endif

                    <!-- Post Content -->
                    <div class="space-y-2">
                        <p class="text-sm text-gray-500 dark:text-gray-200">{{ $post->created_at->diffForHumans() }}</p>
                        <p class="text-gray-800 dark:text-white line-clamp-3">{{ $post->content }}</p>

                        <!-- Post Stats -->
                        <div class="flex items-center text-sm text-gray-600 space-x-4 dark:text-white">
                            <span class="flex items-center">
                                @svg('heroicon-o-hand-thumb-up', 'h-5 w-5')
                                {{ $post->post_likes }}
                            </span>
                            <span class="flex items-center">
                                @svg('heroicon-o-chat-bubble-left-ellipsis', 'h-5 w-5')
                                {{ $post->post_comments }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-span-1 md:col-span-2 lg:col-span-4">
                <div class="p-4 text-center">
                    <p class="text-gray-500">No posts found.</p>
                </div>
            </div>
        @endif
    </div>
    <!-- Pagination -->
    <div class="mt-4">
        {{ $posts->links("vendor.livewire.tailwind") }}
    </div>
</div>