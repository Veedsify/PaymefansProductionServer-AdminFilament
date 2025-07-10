<?php

namespace App\Traits;

trait HasSimpleUserPermissions
{
    /**
     * Get permissions from flags field
     */
    protected function getPermissionsFromFlags(): array
    {
        if (!isset($this->flags)) {
            return [];
        }

        $flags = $this->flags;

        // Handle if flags is a JSON string
        if (is_string($flags)) {
            $flags = json_decode($flags, true) ?? [];
        }

        // Return flags directly as array of permission strings (FlagsConfig format)
        return is_array($flags) ? $flags : [];
    }

    /**
     * Check if user can create content
     */
    public function canCreateContent(): bool
    {
        $permissions = $this->getPermissionsFromFlags();
        return in_array("create_posts", $permissions) &&
            $this->is_active &&
            !$this->isShadowBanned();
    }

    /**
     * Check if user can comment on posts
     */
    public function canComment(): bool
    {
        $permissions = $this->getPermissionsFromFlags();
        return in_array("comment_on_posts", $permissions) &&
            $this->is_active &&
            !$this->isShadowBanned();
    }

    /**
     * Check if user can send messages
     */
    public function canMessage(): bool
    {
        $permissions = $this->getPermissionsFromFlags();
        return in_array("send_messages", $permissions) &&
            $this->is_active &&
            !$this->isShadowBanned();
    }

    /**
     * Check if user can create live streams
     */
    public function canLivestream(): bool
    {
        $permissions = $this->getPermissionsFromFlags();
        return in_array("create_livestream", $permissions) &&
            $this->is_active &&
            $this->is_verified &&
            !$this->isShadowBanned();
    }

    /**
     * Check if user can make purchases
     */
    public function canPurchase(): bool
    {
        $permissions = $this->getPermissionsFromFlags();
        return ($permissions["can_purchase"] ?? true) &&
            $this->is_active &&
            $this->active_status;
    }

    /**
     * Check if user can withdraw funds
     */
    public function canWithdraw(): bool
    {
        $permissions = $this->getPermissionsFromFlags();
        return in_array("withdraw_funds", $permissions) &&
            $this->is_active &&
            $this->is_verified &&
            $this->is_model;
    }

    /**
     * Check if user can receive gifts
     */
    public function canReceiveGifts(): bool
    {
        $permissions = $this->getPermissionsFromFlags();
        return in_array("receive_gifts", $permissions) &&
            $this->is_active &&
            !$this->isShadowBanned();
    }

    /**
     * Check if user content requires approval
     */
    public function requiresContentApproval(): bool
    {
        $permissions = $this->getPermissionsFromFlags();
        return in_array("content_requires_approval", $permissions);
    }

    /**
     * Check if user is shadow banned
     */
    public function isShadowBanned(): bool
    {
        $permissions = $this->getPermissionsFromFlags();
        return in_array("is_shadowbanned", $permissions);
    }

    /**
     * Check if user can report content
     */
    public function canReport(): bool
    {
        $permissions = $this->getPermissionsFromFlags();
        return in_array("report_content", $permissions) && $this->is_active;
    }

    /**
     * Check if user is immune to reports
     */
    public function isImmuneToReports(): bool
    {
        $permissions = $this->getPermissionsFromFlags();
        return in_array("immune_to_reports", $permissions);
    }

    /**
     * Check if user can create groups
     */
    public function canCreateGroups(): bool
    {
        $permissions = $this->getPermissionsFromFlags();
        return in_array("create_groups", $permissions) &&
            $this->is_active &&
            !$this->isShadowBanned();
    }

    /**
     * Check if user can access analytics
     */
    public function canAccessAnalytics(): bool
    {
        $permissions = $this->getPermissionsFromFlags();
        return in_array("view_analytics", $permissions) && $this->is_active;
    }

    /**
     * Check if user can use premium features
     */
    public function canUsePremiumFeatures(): bool
    {
        $permissions = $this->getPermissionsFromFlags();
        return in_array("use_premium_features", $permissions) &&
            $this->is_active;
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
        return $this->role === "moderator" && $this->is_active;
    }

    /**
     * Check if user is support staff
     */
    public function isSupportStaff(): bool
    {
        return $this->role === "support" && $this->is_active;
    }

    /**
     * Check if user has any admin-level permissions
     */
    public function hasAdminPermissions(): bool
    {
        return $this->isAdmin() ||
            $this->isModerator() ||
            $this->isSupportStaff();
    }

    /**
     * Check if user account is fully verified
     */
    public function isFullyVerified(): bool
    {
        return $this->is_verified &&
            $this->is_email_verified &&
            $this->is_phone_verified;
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
            "basic" => [
                "is_active" => $this->is_active,
                "is_verified" => $this->is_verified,
                "is_admin" => $this->isAdmin(),
                "is_model" => $this->isContentCreator(),
                "role" => $this->role,
            ],
            "content" => [
                "can_create_content" => $this->canCreateContent(),
                "can_comment" => $this->canComment(),
                "can_livestream" => $this->canLivestream(),
                "requires_approval" => $this->requiresContentApproval(),
                "is_shadowbanned" => $this->isShadowBanned(),
            ],
            "financial" => [
                "can_purchase" => $this->canPurchase(),
                "can_withdraw" => $this->canWithdraw(),
                "can_receive_gifts" => $this->canReceiveGifts(),
            ],
            "social" => [
                "can_message" => $this->canMessage(),
                "can_create_groups" => $this->canCreateGroups(),
                "can_report" => $this->canReport(),
                "immune_to_reports" => $this->isImmuneToReports(),
            ],
            "platform" => [
                "can_access_analytics" => $this->canAccessAnalytics(),
                "can_use_premium_features" => $this->canUsePremiumFeatures(),
            ],
        ];
    }

    /**
     * Check if user has specific permission
     */
    public function hasPermission(string $permission): bool
    {
        // Check if the permission exists as a method
        $methodName = "can" . str_replace("_", "", ucwords($permission, "_"));
        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        }

        // Check if the permission exists as a direct attribute
        if (isset($this->attributes[$permission])) {
            return (bool) $this->$permission;
        }

        // Check in permissions from flags (FlagsConfig format)
        $permissions = $this->getPermissionsFromFlags();
        return in_array($permission, $permissions);
    }

    /**
     * Set a permission in flags
     */
    public function setPermission(string $permission, bool $value): void
    {
        $flags = $this->flags;

        // Handle if flags is a JSON string
        if (is_string($flags)) {
            $flags = json_decode($flags, true) ?? [];
        } elseif (!is_array($flags)) {
            $flags = [];
        }

        // Add or remove permission from array (FlagsConfig format)
        if ($value) {
            if (!in_array($permission, $flags)) {
                $flags[] = $permission;
            }
        } else {
            $flags = array_values(array_diff($flags, [$permission]));
        }

        $this->flags = $flags;
        $this->save();
    }

    /**
     * Remove a permission
     */
    public function removePermission(string $permission): void
    {
        $flags = $this->flags;

        // Handle if flags is a JSON string
        if (is_string($flags)) {
            $flags = json_decode($flags, true) ?? [];
        } elseif (!is_array($flags)) {
            return;
        }

        // Remove permission from array (FlagsConfig format)
        $flags = array_values(array_diff($flags, [$permission]));
        $this->flags = $flags;
        $this->save();
    }

    /**
     * Check if user can perform action based on role hierarchy
     */
    public function canPerformAction(
        string $action,
        ?User $targetUser = null
    ): bool {
        // Admins can do everything
        if ($this->isAdmin()) {
            return true;
        }

        // Moderators can perform moderation actions
        if ($this->isModerator()) {
            $moderatorActions = [
                "ban_user",
                "suspend_user",
                "delete_content",
                "approve_content",
                "manage_reports",
            ];

            if (in_array($action, $moderatorActions)) {
                // Moderators cannot act on admins or other moderators
                if (
                    $targetUser &&
                    ($targetUser->isAdmin() || $targetUser->isModerator())
                ) {
                    return false;
                }
                return true;
            }
        }

        // Support staff can perform support actions
        if ($this->isSupportStaff()) {
            $supportActions = [
                "view_user_details",
                "reset_password",
                "send_notification",
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
        if ($this->admin) {
            $granted[] = "admin";
        }
        if ($this->is_model) {
            $granted[] = "content_creator";
        }

        // Get permissions from flags (FlagsConfig format)
        $permissions = $this->getPermissionsFromFlags();

        // Flags array already contains granted permissions
        return array_merge($granted, $permissions);
    }

    /**
     * Get permissions that have been denied/restricted
     */
    public function getRestrictedPermissions(): array
    {
        $restricted = [];

        if ($this->isShadowBanned()) {
            $restricted[] = "shadowbanned";
        }
        if ($this->requiresContentApproval()) {
            $restricted[] = "requires_approval";
        }
        if (!$this->canCreateContent()) {
            $restricted[] = "no_content_creation";
        }
        if (!$this->canComment()) {
            $restricted[] = "no_commenting";
        }
        if (!$this->canMessage()) {
            $restricted[] = "no_messaging";
        }
        if (!$this->canPurchase()) {
            $restricted[] = "no_purchases";
        }
        if (!$this->canWithdraw()) {
            $restricted[] = "no_withdrawals";
        }

        return $restricted;
    }

    /**
     * Bulk set permissions
     */
    public function setPermissions(array $permissions): void
    {
        // Store permissions directly as array (FlagsConfig format)
        $this->flags = array_values($permissions);
        $this->save();
    }

    /**
     * Apply permission template
     */
    public function applyPermissionTemplate(string $template): bool
    {
        $templates = [
            "user" => [
                "view_profile",
                "edit_profile",
                "change_password",
                "enable_two_factor_auth",
                "disable_two_factor_auth",
                "view_notifications",
                "manage_notifications",
                "view_messages",
                "send_messages",
                "view_posts",
                "create_posts",
                "edit_posts",
                "delete_posts",
                "like_posts",
                "comment_on_posts",
                "share_posts",
                "view_followers",
                "follow_users",
                "unfollow_users",
                "block_users",
                "report_content",
            ],
            "admin" => [
                "delete_accounts",
                "view_sensitive_content",
                "manage_users",
                "view_user_data",
                "manage_content",
                "view_reports",
                "manage_reports",
                "manage_billing",
                "view_analytics",
                "manage_settings",
                "manage_features",
                "send_free_messages",
                "view_paid_posts",
                "view_paid_media",
                "bulk_user_operations",
                "impersonate_users",
                "override_payment_verification",
                "manage_creator_verification",
                "access_audit_logs",
                "export_user_data",
                "manage_content_moderation",
                "configure_payment_methods",
                "manage_subscription_tiers",
                "override_content_restrictions",
                "manage_platform_notifications",
                "access_financial_reports",
                "manage_tax_settings",
                "configure_security_policies",
                "manage_api_access",
                "override_rate_limits",
                "manage_backup_restore",
                "configure_cdn_settings",
                "manage_third_party_integrations",
                "access_system_monitoring",
                "manage_maintenance_mode",
            ],
            "support" => [
                "view_tickets",
                "create_tickets",
                "edit_tickets",
                "delete_tickets",
                "assign_tickets",
                "resolve_tickets",
                "escalate_tickets",
                "view_ticket_history",
                "manage_ticket_categories",
                "view_user_data",
                "access_support_reports",
                "manage_support_settings",
            ],
        ];

        if (!isset($templates[$template])) {
            return false;
        }

        $this->setPermissions($templates[$template]);
        return true;
    }
}
