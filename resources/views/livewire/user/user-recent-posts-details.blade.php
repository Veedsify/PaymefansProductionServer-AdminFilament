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
with(fn () => ['posts' => Post::where('user_id', $this->userId)
    ->orderBy('created_at', 'desc')
    ->paginate(20)]);

?>

<div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @if(isset($posts) && count($posts) > 0)
        @foreach($posts as $post)
        <div class="bg-white rounded-lg shadow p-4">
            <!-- Post Image -->
            @if(isset($post->user_media) && count($post->user_media) > 0)
            <div class="aspect-w-16 aspect-h-9 mb-4 grid grid-cols-2 gap-2">
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
                    <source src="{{ $media->url }}" type="video/webm" />
                    <p class="vjs-no-js">
                        To view this video please enable JavaScript, and consider upgrading to a
                        web browser that
                        <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
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
                <p class="text-gray-500 text-sm">{{ $post->created_at->diffForHumans() }}</p>
                <p class="text-gray-800 line-clamp-3">{{ $post->content }}</p>

                <!-- Post Stats -->
                <div class="flex items-center space-x-4 text-sm text-gray-600">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        {{ $post->post_likes }}
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        {{ $post->post_comments }}
                    </span>
                </div>
            </div>
        </div>
        @endforeach
        <!-- Pagination -->
        <div class="col-span-1 md:col-span-2 lg:col-span-4">
            <div class="flex justify-center mt-4">
                {{ $posts->links("vendor.livewire.tailwind") }}
            </div>
        </div>
        @else
        <div class="col-span-1 md:col-span-2 lg:col-span-4">
            <div class="p-4 text-center">
                <p class="text-gray-500">No posts found.</p>
            </div>
        </div>
        @endif
    </div>
</div>