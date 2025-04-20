<?php

namespace App\Constants;


class Actions
{

      public function Actions()
      {

            return  [
                  [
                        'label' => 'Suspend User',
                        'icon' => 'heroicon-o-no-symbol',
                        'color' => 'danger',
                        'route' => 'users.suspend',
                  ],
                  [
                        'label' => 'Reset Password',
                        'icon' => 'heroicon-o-key',
                        'color' => 'warning',
                        'route' => 'users.password.reset',
                  ],
                  [
                        'label' => 'Verify Account',
                        'icon' => 'heroicon-o-check-badge',
                        'color' => 'success',
                        'route' => 'users.verify',
                  ],
                  [
                        'label' => 'Send Notification',
                        'icon' => 'heroicon-o-bell-alert',
                        'color' => 'info',
                        'route' => 'users.notify',
                  ],
                  [
                        'label' => 'Change Role',
                        'icon' => 'heroicon-o-user-group',
                        'color' => 'primary',
                        'route' => 'users.role.change',
                  ],
                  [
                        'label' => 'View Activity',
                        'icon' => 'heroicon-o-document-chart-bar',
                        'color' => 'secondary',
                        'route' => 'users.activity.log',
                  ],
                  [
                        'label' => 'View Transactions',
                        'icon' => 'heroicon-o-banknotes',
                        'color' => 'primary',
                        'route' => 'users.transactions',
                  ],
            ];
      }

      public function SubscriptionActions()
      {
            return  [
                  [
                        'label' => 'View Subscription',
                        'icon' => 'heroicon-o-eye',
                        'color' => 'primary',
                        'route' => 'users.subscription.view',
                  ],
                  [
                        'label' => 'Manage Subscriptions',
                        'icon' => 'heroicon-o-credit-card',
                        'color' => 'success',
                        'route' => 'users.subscriptions',
                  ],
                  [
                        'label' => 'Cancel Subscription',
                        'icon' => 'heroicon-o-x-circle',
                        'color' => 'danger',
                        'route' => 'users.subscription.cancel',
                  ],
                  [
                        'label' => 'Update Payment Method',
                        'icon' => 'heroicon-o-credit-card',
                        'color' => 'success',
                        'route' => 'users.subscription.update.payment.method',
                  ],
            ];
      }

      public function TransactionActions()
      {
            return  [
                  [
                        'label' => 'View Transaction',
                        'icon' => 'heroicon-o-eye',
                        'color' => 'primary',
                        'route' => 'users.transaction.view',
                  ],
                  [
                        'label' => 'Refund Transaction',
                        'icon' => 'heroicon-o-receipt-refund',
                        'color' => 'danger',
                        'route' => 'users.transaction.refund',
                  ],
            ];
      }

      public function ReportActions()
      {
            return  [
                  [
                        'label' => 'View Report',
                        'icon' => 'heroicon-o-eye',
                        'color' => 'primary',
                        'route' => 'users.report.view',
                  ],
                  [
                        'label' => 'Resolve Report',
                        'icon' => 'heroicon-o-check-circle',
                        'color' => 'success',
                        'route' => 'users.report.resolve',
                  ],
            ];
      }

      public function NotificationActions()
      {
            return  [
                  [
                        'label' => 'View Notification',
                        'icon' => 'heroicon-o-eye',
                        'color' => 'primary',
                        'route' => 'users.notification.view',
                  ],
                  [
                        'label' => 'Mark as Read',
                        'icon' => 'heroicon-o-check-circle',
                        'color' => 'success',
                        'route' => 'users.notification.mark.read',
                  ],
            ];
      }

      public function SettingActions()
      {
            return  [
                  [
                        'label' => 'View Settings',
                        'icon' => 'heroicon-o-eye',
                        'color' => 'primary',
                        'route' => 'users.settings.view',
                  ],
                  [
                        'label' => 'Update Settings',
                        'icon' => 'heroicon-o-pencil-square',
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
                        'icon' => 'heroicon-o-eye',
                        'color' => 'primary',
                        'route' => 'models.view',
                  ],
                  [
                        'label' => 'Edit Profile',
                        'icon' => 'heroicon-o-pencil-square',
                        'color' => 'warning',
                        'route' => 'models.edit',
                  ],
                  [
                        'label' => 'Verify Model',
                        'icon' => 'heroicon-o-check-badge',
                        'color' => 'success',
                        'route' => 'models.verify',
                  ],
                  [
                        'label' => 'Review Verification',
                        'icon' => 'heroicon-o-document-magnifying-glass',
                        'color' => 'info',
                        'route' => 'models.verification.review',
                  ],
                  [
                        'label' => 'Toggle Hookup Status',
                        'icon' => 'heroicon-o-arrow-path',
                        'color' => 'secondary',
                        'route' => 'models.hookup.toggle',
                  ],
                  [
                        'label' => 'Delete Model',
                        'icon' => 'heroicon-o-trash',
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
                        'icon' => 'heroicon-o-eye',
                        'color' => 'primary',
                        'route' => 'posts.view',
                  ],
                  [
                        'label' => 'Edit Post',
                        'icon' => 'heroicon-o-pencil-square',
                        'color' => 'warning',
                        'route' => 'posts.edit',
                  ],
                  [
                        'label' => 'Delete Post',
                        'icon' => 'heroicon-o-trash',
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
                        'icon' => 'heroicon-o-eye',
                        'color' => 'primary',
                        'route' => 'comments.view',
                  ],
                  [
                        'label' => 'Edit Comment',
                        'icon' => 'heroicon-o-pencil-square',
                        'color' => 'warning',
                        'route' => 'comments.edit',
                  ],
                  [
                        'label' => 'Delete Comment',
                        'icon' => 'heroicon-o-trash',
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
                        'icon' => 'heroicon-o-trash',
                        'color' => 'danger',
                        'route' => 'messages.delete',
                  ],
            ];
      }
}
