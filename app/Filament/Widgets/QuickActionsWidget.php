<?php

namespace App\Filament\Widgets;

use App\Models\WithdrawalRequest;
use App\Models\User;
use App\Models\Post;
use App\Models\ReportPost;
use App\Models\ReportUser;
use Filament\Widgets\Widget;
use Filament\Support\Enums\IconPosition;

class QuickActionsWidget extends Widget
{
    protected static string $view = "filament.widgets.quick-actions-widget";
    protected static ?int $sort = 1;
    protected int|string|array $columnSpan = 'full';

    public function getViewData(): array
    {
        return [
            "pendingWithdrawals" => WithdrawalRequest::where(
                "status",
                "pending"
            )->count(),
            "pendingReports" => ReportPost::count() + ReportUser::count(),
            "newUsers" => User::where(
                "created_at",
                ">=",
                now()->subDays(7)
            )->count(),
            "recentPosts" => Post::where(
                "created_at",
                ">=",
                now()->subDays(1)
            )->count(),
            "actions" => $this->getQuickActions(),
        ];
    }

    protected function getQuickActions(): array
    {
        return [
            [
                "label" => "Review Withdrawals",
                "icon" => "heroicon-o-banknotes",
                "color" => "warning",
                "url" => "/admin/withdrawal-requests",
                "count" => WithdrawalRequest::where(
                    "status",
                    "pending"
                )->count(),
                "description" => "Pending withdrawal requests",
            ],
            [
                "label" => "Manage Users",
                "icon" => "heroicon-o-users",
                "color" => "info",
                "url" => "/admin/users",
                "count" => User::where(
                    "created_at",
                    ">=",
                    now()->subDays(7)
                )->count(),
                "description" => "New users this week",
            ],
            [
                "label" => "Content Management",
                "icon" => "heroicon-o-photo",
                "color" => "success",
                "url" => "/admin/posts",
                "count" => Post::where(
                    "created_at",
                    ">=",
                    now()->subDay()
                )->count(),
                "description" => "Posts today",
            ],
            [
                "label" => "Handle Reports",
                "icon" => "heroicon-o-flag",
                "color" => "danger",
                "url" => "/admin/report-posts",
                "count" => ReportPost::count() + ReportUser::count(),
                "description" => "Unresolved reports",
            ],
            [
                "label" => "Points Management",
                "icon" => "heroicon-o-star",
                "color" => "purple",
                "url" => "/admin/global-points-buys",
                "count" => null,
                "description" => "Manage point packages",
            ],
            [
                "label" => "Live Streams",
                "icon" => "heroicon-o-video-camera",
                "color" => "red",
                "url" => "/admin/live-streams",
                "count" => null,
                "description" => "Monitor live content",
            ],
            [
                "label" => "Email Campaigns",
                "icon" => "heroicon-o-envelope",
                "color" => "blue",
                "url" => "/admin/send-custom-email",
                "count" => null,
                "description" => "Send notifications",
            ],
            [
                "label" => "Platform Settings",
                "icon" => "heroicon-o-cog-6-tooth",
                "color" => "gray",
                "url" => "/admin/configurations",
                "count" => null,
                "description" => "System configuration",
            ],
        ];
    }
}
