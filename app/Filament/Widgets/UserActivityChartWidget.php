<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\PostComment;
use App\Models\LiveStream;
use App\Models\Message;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class UserActivityChartWidget extends ChartWidget
{
    protected static ?string $heading = "User Activity & Engagement";
    protected static ?int $sort = 7;
    protected static ?string $maxHeight = "300px";
    protected int|string|array $columnSpan = "full";

    public ?string $filter = "7days";

    protected function getFilters(): ?array
    {
        return [
            "7days" => "Last 7 days",
            "14days" => "Last 14 days",
            "30days" => "Last 30 days",
        ];
    }

    protected function getData(): array
    {
        $filter = $this->filter;
        $days = match ($filter) {
            "7days" => 7,
            "14days" => 14,
            "30days" => 30,
            default => 7,
        };

        $startDate = now()->subDays($days);
        $endDate = now();

        // Generate date range
        $dateRange = [];
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateRange[] = $currentDate->format("Y-m-d");
            $currentDate->addDay();
        }

        // Get daily activity data
        $newUsersData = [];
        $postsData = [];
        $likesData = [];
        $commentsData = [];
        $messagesData = [];
        $liveStreamsData = [];

        foreach ($dateRange as $date) {
            // New users registered
            $newUsers = User::whereDate("created_at", $date)
                ->where("role", "!=", "admin")
                ->count();

            // Posts created
            $posts = Post::whereDate("created_at", $date)->count();

            // Likes given
            $likes = PostLike::whereDate("created_at", $date)->count();

            // Comments made
            $comments = PostComment::whereDate("created_at", $date)->count();

            // Messages sent
            $messages = Message::whereDate("created_at", $date)->count();

            // Live streams started
            $liveStreams = LiveStream::whereDate("created_at", $date)->count();

            $newUsersData[] = $newUsers;
            $postsData[] = $posts;
            $likesData[] = $likes;
            $commentsData[] = $comments;
            $messagesData[] = $messages;
            $liveStreamsData[] = $liveStreams;
        }

        return [
            "datasets" => [
                [
                    "label" => "New Users",
                    "data" => $newUsersData,
                    "borderColor" => "rgb(34, 197, 94)",
                    "backgroundColor" => "rgba(34, 197, 94, 0.1)",
                    "fill" => false,
                    "tension" => 0.4,
                ],
                [
                    "label" => "Posts Created",
                    "data" => $postsData,
                    "borderColor" => "rgb(59, 130, 246)",
                    "backgroundColor" => "rgba(59, 130, 246, 0.1)",
                    "fill" => false,
                    "tension" => 0.4,
                ],
                [
                    "label" => "Likes",
                    "data" => $likesData,
                    "borderColor" => "rgb(239, 68, 68)",
                    "backgroundColor" => "rgba(239, 68, 68, 0.1)",
                    "fill" => false,
                    "tension" => 0.4,
                ],
                [
                    "label" => "Comments",
                    "data" => $commentsData,
                    "borderColor" => "rgb(168, 85, 247)",
                    "backgroundColor" => "rgba(168, 85, 247, 0.1)",
                    "fill" => false,
                    "tension" => 0.4,
                ],
                [
                    "label" => "Messages",
                    "data" => $messagesData,
                    "borderColor" => "rgb(245, 158, 11)",
                    "backgroundColor" => "rgba(245, 158, 11, 0.1)",
                    "fill" => false,
                    "tension" => 0.4,
                ],
                [
                    "label" => "Live Streams",
                    "data" => $liveStreamsData,
                    "borderColor" => "rgb(20, 184, 166)",
                    "backgroundColor" => "rgba(20, 184, 166, 0.1)",
                    "fill" => false,
                    "tension" => 0.4,
                ],
            ],
            "labels" => $this->formatLabels($dateRange),
        ];
    }

    protected function getType(): string
    {
        return "line";
    }

    protected function getOptions(): array
    {
        return [
            "plugins" => [
                "legend" => [
                    "display" => true,
                    "position" => "top",
                    "labels" => [
                        "usePointStyle" => true,
                        "padding" => 20,
                    ],
                ],
                "tooltip" => [
                    "mode" => "index",
                    "intersect" => false,
                    "backgroundColor" => "rgba(0, 0, 0, 0.8)",
                    "titleColor" => "white",
                    "bodyColor" => "white",
                    "borderColor" => "rgba(255, 255, 255, 0.1)",
                    "borderWidth" => 1,
                ],
            ],
            "scales" => [
                "x" => [
                    "display" => true,
                    "title" => [
                        "display" => true,
                        "text" => "Date",
                        "color" => "rgba(107, 114, 128, 1)",
                    ],
                    "grid" => [
                        "color" => "rgba(107, 114, 128, 0.1)",
                    ],
                ],
                "y" => [
                    "display" => true,
                    "title" => [
                        "display" => true,
                        "text" => "Activity Count",
                        "color" => "rgba(107, 114, 128, 1)",
                    ],
                    "beginAtZero" => true,
                    "grid" => [
                        "color" => "rgba(107, 114, 128, 0.1)",
                    ],
                    "ticks" => [
                        "precision" => 0,
                    ],
                ],
            ],
            "interaction" => [
                "mode" => "nearest",
                "axis" => "x",
                "intersect" => false,
            ],
            "elements" => [
                "point" => [
                    "radius" => 2,
                    "hoverRadius" => 5,
                ],
                "line" => [
                    "borderWidth" => 2,
                ],
            ],
            "maintainAspectRatio" => false,
            "responsive" => true,
        ];
    }

    private function formatLabels(array $dateRange): array
    {
        return array_map(function ($date) {
            return Carbon::parse($date)->format("M j");
        }, $dateRange);
    }
}
