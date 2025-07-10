<?php

namespace App\Traits;

trait HasUserPermissions
{
    /**
     * Check if user can create content
     */
    public function canCreateContent(): bool
    {
        return $this->can_create_content && $this->is_active && !$this->is_shadowbanned;
    }

    /**
     * Check if user can comment on posts
     */
    public function canComment(): bool
    {
        return $this->can_comment && $this->is_active && !$this->is_shadowbanned;
    }

    /**
     * Check if user can send messages
     */
    public function canMessage(): bool
    {
        return $this->can_message && $this->is_active && !$this->is_shadowbanned;
    }

    /**
     * Check if user can create live streams
     */
    public function canLivestream(): bool
    {
        return $this->can_livestream && $this->is_active && $this->is_verified && !$this->is_shadowbanned;
    }

    /**
     * Check if user can make purchases
     */
    public function canPurchase(): bool
    {
        return $this->can_purchase && $this->is_active && $this->active_status;
    }

    /**
     * Check if user can withdraw funds
     */
    public function canWithdraw(): bool
    {
        return $this->can_withdraw && $this->is_active && $this->is_verified && $this->is_model;
    }

    /**
     * Check if user can receive gifts
     */
    public function canReceiveGifts(): bool
    {
        return $this->can_receive_gifts && $this->is_active && !$this->is_shadowbanned;
    }

    /**
     * Check if user content requires approval
     */
    public function requiresContentApproval(): bool
    {
        return $this->content_requires_approval;
    }

    /**
     * Check if user is shadow banned
     */
    public function isShadowBanned(): bool
    {
        return $this->is_shadowbanned;
    }

    /**
     * Check if user can report content
     */
    public function canReport(): bool
    {
        return $this->can_report && $this->is_active;
    }

    /**
     * Check if user is immune to reports
     */
    public function isImmuneToReports(): bool
    {
        return $this->immune_to_reports;
    }

    /**
     * Check if user can create groups
     */
    public function canCreateGroups(): bool
    {
        return $this->can_create_groups && $this->is_active && !$this->is_shadowbanned;
    }

    /**
     * Check if user can access analytics
     */
    public function canAccessAnalytics(): bool
    {
        return $this->can_access_analytics && $this->is_active;
    }

    /**
     * Check if user can use premium features
     */
    public function canUsePremiumFeatures(): bool
    {
        return $this->can_use_premium_features && $this->is_active;
    }

    /**
     * Check if user is an administrator
     */
    public function isAdmin(): bool
    {
        return $this->admin && $this->is_active;
    }

    /**
     * Check if user is a content creator/model
     */
    public function isContentCreator(): bool
    {
        return $this->is_model && $this->is_active;
    }

    /**
     * Check if user is a moderator
     */
    public function isModerator(): bool
    {
        return $this->role === 'moderator' && $this->is_active;
    }

    /**
     * Check if user is support staff
     */
    public function isSupportStaff(): bool
    {
        return $this->role === 'support' && $this->is_active;
    }

    /**
     * Check if user has any admin-level permissions
     */
    public function hasAdminPermissions(): bool
    {
        return $this->isAdmin() || $this->isModerator() || $this->isSupportStaff();
    }

    /**
     * Check if user account is fully verified
     */
    public function isFullyVerified(): bool
    {
        return $this->is_verified && $this->is_email_verified && $this->is_phone_verified;
    }

    /**
     * Check if user can perform financial operations
     */
    public function canPerformFinancialOperations(): bool
    {
        return $this->is_active && $this->is_verified && $this->active_status;
    }

    /**
     * Get user permission summary
     */
    public function getPermissionSummary(): array
    {
        return [
            'basic' => [
                'is_active' => $this->is_active,
                'is_verified' => $this->is_verified,
                'is_admin' => $this->isAdmin(),
                'is_model' => $this->isContentCreator(),
                'role' => $this->role,
            ],
            'content' => [
                'can_create_content' => $this->canCreateContent(),
                'can_comment' => $this->canComment(),
                'can_livestream' => $this->canLivestream(),
                'requires_approval' => $this->requiresContentApproval(),
                'is_shadowbanned' => $this->isShadowBanned(),
            ],
            'financial' => [
                'can_purchase' => $this->canPurchase(),
                'can_withdraw' => $this->canWithdraw(),
                'can_receive_gifts' => $this->canReceiveGifts(),
            ],
            'social' => [
                'can_message' => $this->canMessage(),
                'can_create_groups' => $this->canCreateGroups(),
                'can_report' => $this->canReport(),
                'immune_to_reports' => $this->isImmuneToReports(),
            ],
            'platform' => [
                'can_access_analytics' => $this->canAccessAnalytics(),
                'can_use_premium_features' => $this->canUsePremiumFeatures(),
            ],
        ];
    }

    /**
     * Check if user has specific permission
     */
    public function hasPermission(string $permission): bool
    {
        // Check if the permission exists as a method
        $methodName = 'can' . str_replace('_', '', ucwords($permission, '_'));
        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        }

        // Check if the permission exists as a direct attribute
        if (isset($this->attributes[$permission])) {
            return (bool) $this->$permission;
        }

        // Check in additional_permissions JSON field
        if ($this->additional_permissions && is_array($this->additional_permissions)) {
            return $this->additional_permissions[$permission] ?? false;
        }

        return false;
    }

    /**
     * Set a custom permission in additional_permissions
     */
    public function setCustomPermission(string $permission, bool $value): void
    {
        $permissions = $this->additional_permissions ?? [];
        $permissions[$permission] = $value;
        $this->additional_permissions = $permissions;
        $this->save();
    }

    /**
     * Remove a custom permission
     */
    public function removeCustomPermission(string $permission): void
    {
        if ($this->additional_permissions && is_array($this->additional_permissions)) {
            $permissions = $this->additional_permissions;
            unset($permissions[$permission]);
            $this->additional_permissions = $permissions;
            $this->save();
        }
    }

    /**
     * Check if user can perform action based on role hierarchy
     */
    public function canPerformAction(string $action, ?User $targetUser = null): bool
    {
        // Admins can do everything
        if ($this->isAdmin()) {
            return true;
        }

        // Moderators can perform moderation actions
        if ($this->isModerator()) {
            $moderatorActions = [
                'ban_user', 'suspend_user', 'delete_content',
                'approve_content', 'manage_reports'
            ];

            if (in_array($action, $moderatorActions)) {
                // Moderators cannot act on admins or other moderators
                if ($targetUser && ($targetUser->isAdmin() || $targetUser->isModerator())) {
                    return false;
                }
                return true;
            }
        }

        // Support staff can perform support actions
        if ($this->isSupportStaff()) {
            $supportActions = [
                'view_user_details', 'reset_password', 'send_notification'
            ];

            return in_array($action, $supportActions);
        }

        return false;
    }

    /**
     * Get permissions that have been explicitly granted
     */
    public function getGrantedPermissions(): array
    {
        $granted = [];

        // Basic permissions
        if ($this->admin) $granted[] = 'admin';
        if ($this->is_model) $granted[] = 'content_creator';

        // Content permissions
        if ($this->can_create_content) $granted[] = 'create_content';
        if ($this->can_comment) $granted[] = 'comment';
        if ($this->can_message) $granted[] = 'message';
        if ($this->can_livestream) $granted[] = 'livestream';

        // Financial permissions
        if ($this->can_purchase) $granted[] = 'purchase';
        if ($this->can_withdraw) $granted[] = 'withdraw';
        if ($this->can_receive_gifts) $granted[] = 'receive_gifts';

        // Platform permissions
        if ($this->can_create_groups) $granted[] = 'create_groups';
        if ($this->can_access_analytics) $granted[] = 'access_analytics';
        if ($this->can_use_premium_features) $granted[] = 'premium_features';

        // Add custom permissions
        if ($this->additional_permissions && is_array($this->additional_permissions)) {
            foreach ($this->additional_permissions as $permission => $value) {
                if ($value) {
                    $granted[] = $permission;
                }
            }
        }

        return $granted;
    }

    /**
     * Get permissions that have been denied/restricted
     */
    public function getRestrictedPermissions(): array
    {
        $restricted = [];

        if ($this->is_shadowbanned) $restricted[] = 'shadowbanned';
        if ($this->content_requires_approval) $restricted[] = 'requires_approval';
        if (!$this->can_create_content) $restricted[] = 'no_content_creation';
        if (!$this->can_comment) $restricted[] = 'no_commenting';
        if (!$this->can_message) $restricted[] = 'no_messaging';
        if (!$this->can_purchase) $restricted[] = 'no_purchases';
        if (!$this->can_withdraw) $restricted[] = 'no_withdrawals';

        return $restricted;
    }
}
