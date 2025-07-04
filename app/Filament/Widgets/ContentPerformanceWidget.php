<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\User;
use App\Models\PostLike;
use App\Models\PostComment;
use App\Models\LiveStream;
use App\Models\UserMedia;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ContentPerformanceWidget extends BaseWidget
{
    protected static ?string $heading = "Content Performance";
    protected static ?int $sort = 7;
    protected int|string|array $columnSpan = "full";

    public ?string $filter = "top_posts";

    protected function getFilters(): ?array
    {
        return [
            "top_posts" => "Top Performing Posts",
            "top_creators" => "Top Content Creators",
            "recent_viral" => "Recently Viral Content",
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns($this->getTableColumns())
            ->defaultSort($this->getDefaultSort())
            ->defaultPaginationPageOption(10)
            ->poll("60s")
            ->emptyStateHeading("No performance data available")
            ->emptyStateDescription(
                "Content performance metrics will appear here as users engage with the platform."
            )
            ->emptyStateIcon("heroicon-o-chart-bar")
            ->actions($this->getTableActions());
    }

    protected function getTableQuery(): Builder
    {
        return match ($this->filter) {
            "top_creators" => $this->getTopCreatorsQuery(),
            "recent_viral" => $this->getRecentViralQuery(),
            default => $this->getTopPostsQuery(),
        };
    }

    protected function getTableColumns(): array
    {
        return match ($this->filter) {
            "top_creators" => $this->getCreatorColumns(),
            "recent_viral" => $this->getViralContentColumns(),
            default => $this->getPostColumns(),
        };
    }

    protected function getDefaultSort(): string
    {
        return match ($this->filter) {
            "top_creators" => "total_engagement",
            "recent_viral" => "engagement_rate",
            default => "total_engagement",
        };
    }

    protected function getTopPostsQuery(): Builder
    {
        return Post::select([
            "Post.id",
            "Post.post_id",
            "Post.content",
            "Post.created_at",
            "Post.post_likes",
            "Post.post_comments",
            "Post.post_reposts",
            "Post.post_impressions",
            "users.name as creator_name",
            "users.username as creator_username",
            DB::raw(
                "(\"Post\".post_likes + \"Post\".post_comments + \"Post\".post_reposts) as total_engagement"
            ),
            DB::raw('CASE
                WHEN "Post".post_impressions > 0
                THEN ROUND((("Post".post_likes + "Post".post_comments + "Post".post_reposts) / "Post".post_impressions) * 100, 2)
                ELSE 0
            END as engagement_rate'),
        ])
            ->join("User as users", "Post.user_id", "=", "users.id")
            ->where("Post.created_at", ">=", now()->subDays(30))
            ->where("Post.post_is_visible", true)
            ->orderByDesc("total_engagement");
    }

    protected function getTopCreatorsQuery(): Builder
    {
        return User::select([
            "User.id",
            "User.name",
            "User.username",
            "User.is_model",
            "User.total_followers",
            "User.total_subscribers",
            "User.created_at",
            DB::raw("COUNT(\"Post\".id) as total_posts"),
            DB::raw("SUM(\"Post\".post_likes) as total_likes"),
            DB::raw("SUM(\"Post\".post_comments) as total_comments"),
            DB::raw("SUM(\"Post\".post_reposts) as total_reposts"),
            DB::raw("SUM(\"Post\".post_impressions) as total_views"),
            DB::raw(
                "SUM(\"Post\".post_likes + \"Post\".post_comments + \"Post\".post_reposts) as total_engagement"
            ),
            DB::raw('CASE
                WHEN SUM("Post".post_impressions) > 0
                THEN ROUND((SUM("Post".post_likes + "Post".post_comments + "Post".post_reposts) / SUM("Post".post_impressions)) * 100, 2)
                ELSE 0
            END as avg_engagement_rate'),
        ])
            ->join("Post", "User.id", "=", "Post.user_id")
            ->where("Post.created_at", ">=", now()->subDays(30))
            ->where("User.role", "!=", "admin")
            ->groupBy([
                "User.id",
                "User.name",
                "User.username",
                "User.is_model",
                "User.total_followers",
                "User.total_subscribers",
                "User.created_at",
            ])
            ->having("total_posts", ">", 0)
            ->orderByDesc("total_engagement");
    }

    protected function getRecentViralQuery(): Builder
    {
        return Post::select([
            "Post.id",
            "Post.post_id",
            "Post.content",
            "Post.created_at",
            "Post.post_likes",
            "Post.post_comments",
            "Post.post_reposts",
            "Post.post_impressions",
            "users.name as creator_name",
            "users.username as creator_username",
            DB::raw(
                "(\"Post\".post_likes + \"Post\".post_comments + \"Post\".post_reposts) as total_engagement"
            ),
            DB::raw('CASE
                WHEN "Post".post_impressions > 0
                THEN ROUND((("Post".post_likes + "Post".post_comments + "Post".post_reposts) / "Post".post_impressions) * 100, 2)
                ELSE 0
            END as engagement_rate'),
        ])
            ->join("User as users", "Post.user_id", "=", "users.id")
            ->where("Post.created_at", ">=", now()->subDays(7))
            ->where("Post.post_is_visible", true)
            ->where("Post.post_impressions", ">", 100) // Minimum view threshold
            ->havingRaw("engagement_rate > 5") // Minimum 5% engagement rate
            ->orderByDesc("engagement_rate");
    }

    protected function getPostColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make("creator_name")
                ->label("Creator")
                ->searchable()
                ->sortable()
                ->limit(20),

            Tables\Columns\TextColumn::make("content")
                ->label("Content")
                ->html()
                ->limit(50)
                ->tooltip(function ($record): ?string {
                    return strip_tags($record->content);
                }),

            Tables\Columns\TextColumn::make("post_impressions")
                ->label("Views")
                ->numeric()
                ->sortable()
                ->formatStateUsing(fn($state): string => number_format($state)),

            Tables\Columns\TextColumn::make("post_likes")
                ->label("Likes")
                ->numeric()
                ->sortable()
                ->color("success")
                ->formatStateUsing(fn($state): string => number_format($state)),

            Tables\Columns\TextColumn::make("post_comments")
                ->label("Comments")
                ->numeric()
                ->sortable()
                ->color("info")
                ->formatStateUsing(fn($state): string => number_format($state)),

            Tables\Columns\TextColumn::make("total_engagement")
                ->label("Total Engagement")
                ->numeric()
                ->sortable()
                ->color("warning")
                ->formatStateUsing(fn($state): string => number_format($state)),

            Tables\Columns\TextColumn::make("engagement_rate")
                ->label("Engagement Rate")
                ->sortable()
                ->formatStateUsing(fn($state): string => $state . "%")
                ->color(
                    fn($state): string => match (true) {
                        $state >= 10 => "success",
                        $state >= 5 => "warning",
                        default => "gray",
                    }
                ),

            Tables\Columns\TextColumn::make("created_at")
                ->label("Posted")
                ->dateTime("M j, H:i")
                ->sortable()
                ->since(),
        ];
    }

    protected function getCreatorColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make("name")
                ->label("Creator")
                ->searchable()
                ->sortable()
                ->description(fn($record): string => "@" . $record->username),

            Tables\Columns\IconColumn::make("is_model")
                ->label("Verified")
                ->boolean()
                ->trueIcon("heroicon-o-check-badge")
                ->falseIcon("heroicon-o-user")
                ->trueColor("success")
                ->falseColor("gray"),

            Tables\Columns\TextColumn::make("total_posts")
                ->label("Posts")
                ->numeric()
                ->sortable()
                ->formatStateUsing(fn($state): string => number_format($state)),

            Tables\Columns\TextColumn::make("total_followers")
                ->label("Followers")
                ->numeric()
                ->sortable()
                ->formatStateUsing(fn($state): string => number_format($state)),

            Tables\Columns\TextColumn::make("total_views")
                ->label("Total Views")
                ->numeric()
                ->sortable()
                ->formatStateUsing(fn($state): string => number_format($state)),

            Tables\Columns\TextColumn::make("total_engagement")
                ->label("Total Engagement")
                ->numeric()
                ->sortable()
                ->color("warning")
                ->formatStateUsing(fn($state): string => number_format($state)),

            Tables\Columns\TextColumn::make("avg_engagement_rate")
                ->label("Avg. Engagement Rate")
                ->sortable()
                ->formatStateUsing(fn($state): string => $state . "%")
                ->color(
                    fn($state): string => match (true) {
                        $state >= 10 => "success",
                        $state >= 5 => "warning",
                        default => "gray",
                    }
                ),

            Tables\Columns\TextColumn::make("created_at")
                ->label("Joined")
                ->dateTime("M j, Y")
                ->sortable(),
        ];
    }

    protected function getViralContentColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make("creator_name")
                ->label("Creator")
                ->searchable()
                ->sortable()
                ->limit(20),

            Tables\Columns\TextColumn::make("content")
                ->label("Content")
                ->html()
                ->limit(60)
                ->tooltip(function ($record): ?string {
                    return strip_tags($record->content);
                }),

            Tables\Columns\TextColumn::make("engagement_rate")
                ->label("Engagement Rate")
                ->sortable()
                ->formatStateUsing(fn($state): string => $state . "%")
                ->badge()
                ->color(
                    fn($state): string => match (true) {
                        $state >= 20 => "danger",
                        $state >= 15 => "warning",
                        $state >= 10 => "success",
                        default => "info",
                    }
                ),

            Tables\Columns\TextColumn::make("post_impressions")
                ->label("Views")
                ->numeric()
                ->sortable()
                ->formatStateUsing(fn($state): string => number_format($state)),

            Tables\Columns\TextColumn::make("total_engagement")
                ->label("Engagement")
                ->numeric()
                ->sortable()
                ->color("success")
                ->formatStateUsing(fn($state): string => number_format($state)),

            Tables\Columns\TextColumn::make("created_at")
                ->label("Posted")
                ->dateTime("M j, H:i")
                ->sortable()
                ->since(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\ViewAction::make()
                ->url(
                    fn($record): string => match ($this->filter) {
                        "top_creators" => "/admin/users/" . $record->id,
                        default => "/admin/posts/" . $record->id,
                    }
                )
                ->openUrlInNewTab(false),
        ];
    }
}
