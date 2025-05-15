<?php

namespace App\Constants;

use App\Filament\Resources\UserResource\Pages\SuspendUser;

class Actions
{

    public function Actions($userId)
    {

        return [
            [
                'label' => 'Suspend User',
                'icon' => 'heroicon-s-no-symbol',
                'color' => 'danger',
                'route' => SuspendUser::getUrl(["record" => $userId])
            ],
//                  [
//                        'label' => 'Reset Password',
//                        'icon' => 'heroicon-s-key',
//                        'color' => 'warning',
//                        'route' => 'users.password.reset',
//                  ],
            [
                'label' => 'Change Country',
                'icon' => 'heroicon-s-flag',
                'color' => 'success',
                'route' => 'users.verify',
            ],
            [
                'label' => 'Change User Country',
                'icon' => 'heroicon-s-flag',
                'color' => 'success',
                'route' => 'users.verify',
            ],
            [
                'label' => 'Verify Account',
                'icon' => 'heroicon-s-check-badge',
                'color' => 'success',
                'route' => 'users.verify',
            ],
            [
                'label' => 'Send Notification',
                'icon' => 'heroicon-s-bell-alert',
                'color' => 'info',
                'route' => 'users.notify',
            ],
            [
                'label' => 'Change Role',
                'icon' => 'heroicon-s-user-group',
                'color' => 'primary',
                'route' => 'users.role.change',
            ],
            [
                'label' => 'View Activity',
                'icon' => 'heroicon-s-document-chart-bar',
                'color' => 'secondary',
                'route' => 'users.activity.log',
            ],
            [
                'label' => 'View Transactions',
                'icon' => 'heroicon-s-banknotes',
                'color' => 'primary',
                'route' => 'users.transactions',
            ],
        ];
    }

    public function SubscriptionActions()
    {
        return [
            [
                'label' => 'View Subscription',
                'icon' => 'heroicon-s-eye',
                'color' => 'primary',
                'route' => 'users.subscription.view',
            ],
            [
                'label' => 'Manage Subscriptions',
                'icon' => 'heroicon-s-credit-card',
                'color' => 'success',
                'route' => 'users.subscriptions',
            ],
            [
                'label' => 'Cancel Subscription',
                'icon' => 'heroicon-s-x-circle',
                'color' => 'danger',
                'route' => 'users.subscription.cancel',
            ],
            [
                'label' => 'Update Bank Account',
                'icon' => 'heroicon-s-credit-card',
                'color' => 'success',
                'route' => 'users.subscription.update.payment.method',
            ],
        ];
    }

    public function TransactionActions()
    {
        return [
            [
                'label' => 'View Transaction',
                'icon' => 'heroicon-s-eye',
                'color' => 'primary',
                'route' => 'users.transaction.view',
            ],
            [
                'label' => 'Refund Transaction',
                'icon' => 'heroicon-s-receipt-refund',
                'color' => 'danger',
                'route' => 'users.transaction.refund',
            ],
        ];
    }

    public function ReportActions()
    {
        return [
            [
                'label' => 'View Report',
                'icon' => 'heroicon-s-eye',
                'color' => 'primary',
                'route' => 'users.report.view',
            ],
            [
                'label' => 'Resolve Report',
                'icon' => 'heroicon-s-check-circle',
                'color' => 'success',
                'route' => 'users.report.resolve',
            ],
        ];
    }

    public function NotificationActions()
    {
        return [
            [
                'label' => 'View Notification',
                'icon' => 'heroicon-s-eye',
                'color' => 'primary',
                'route' => 'users.notification.view',
            ],
            [
                'label' => 'Mark as Read',
                'icon' => 'heroicon-s-check-circle',
                'color' => 'success',
                'route' => 'users.notification.mark.read',
            ],
        ];
    }

    public function SettingActions()
    {
        return [
            [
                'label' => 'View Settings',
                'icon' => 'heroicon-s-eye',
                'color' => 'primary',
                'route' => 'users.settings.view',
            ],
            [
                'label' => 'Update Settings',
                'icon' => 'heroicon-s-pencil-square',
                'color' => 'success',
                'route' => 'users.settings.update',
            ],
        ];
    }

    public function ModelActions()
    {
        return [
            [
                'label' => 'View Model',
                'icon' => 'heroicon-s-eye',
                'color' => 'primary',
                'route' => 'models.view',
            ],
            [
                'label' => 'Edit Profile',
                'icon' => 'heroicon-s-pencil-square',
                'color' => 'warning',
                'route' => 'models.edit',
            ],
            [
                'label' => 'Verify Model',
                'icon' => 'heroicon-s-check-badge',
                'color' => 'success',
                'route' => 'models.verify',
            ],
            [
                'label' => 'Review Verification',
                'icon' => 'heroicon-s-document-magnifying-glass',
                'color' => 'info',
                'route' => 'models.verification.review',
            ],
            [
                'label' => 'Toggle Hookup Status',
                'icon' => 'heroicon-s-arrow-path',
                'color' => 'secondary',
                'route' => 'models.hookup.toggle',
            ],
            [
                'label' => 'Delete Model',
                'icon' => 'heroicon-s-trash',
                'color' => 'danger',
                'route' => 'models.delete',
            ],
        ];
    }

    public function PostActions()
    {
        return [
            [
                'label' => 'View Posts',
                'icon' => 'heroicon-s-eye',
                'color' => 'primary',
                'route' => 'posts.view',
            ],
            [
                'label' => 'Edit Post',
                'icon' => 'heroicon-s-pencil-square',
                'color' => 'warning',
                'route' => 'posts.edit',
            ],
            [
                'label' => 'Delete Post',
                'icon' => 'heroicon-s-trash',
                'color' => 'danger',
                'route' => 'posts.delete',
            ],
        ];
    }

    public function CommentActions()
    {
        return [
            [
                'label' => 'View Comments',
                'icon' => 'heroicon-s-eye',
                'color' => 'primary',
                'route' => 'comments.view',
            ],
            [
                'label' => 'Edit Comment',
                'icon' => 'heroicon-s-pencil-square',
                'color' => 'warning',
                'route' => 'comments.edit',
            ],
            [
                'label' => 'Delete Comment',
                'icon' => 'heroicon-s-trash',
                'color' => 'danger',
                'route' => 'comments.delete',
            ],
        ];
    }

    public function MessagesActions()
    {
        return [
            [
                'label' => 'Delete Message',
                'icon' => 'heroicon-s-trash',
                'color' => 'danger',
                'route' => 'messages.delete',
            ],
        ];
    }
}
