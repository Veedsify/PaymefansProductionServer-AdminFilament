<?php

namespace App\Utils;

/**
 * Permission and Role Configuration - PHP Version
 * Mirrors the TypeScript FlagsConfig.ts for consistency between server and admin panel
 */
class FlagsConfig
{
    // Permission definitions
    const PERMISSIONS = [
        // User Management
        'DELETE_ACCOUNTS' => 'delete_accounts',
        'VIEW_SENSITIVE_CONTENT' => 'view_sensitive_content',
        'MANAGE_USERS' => 'manage_users',
        'VIEW_USER_DATA' => 'view_user_data',
        'BULK_USER_OPERATIONS' => 'bulk_user_operations',
        'IMPERSONATE_USERS' => 'impersonate_users',
        'EXPORT_USER_DATA' => 'export_user_data',

        // Content Management
        'MANAGE_CONTENT' => 'manage_content',
        'VIEW_REPORTS' => 'view_reports',
        'MANAGE_REPORTS' => 'manage_reports',
        'MANAGE_CONTENT_MODERATION' => 'manage_content_moderation',
        'OVERRIDE_CONTENT_RESTRICTIONS' => 'override_content_restrictions',

        // Billing & Payments
        'MANAGE_BILLING' => 'manage_billing',
        'OVERRIDE_PAYMENT_VERIFICATION' => 'override_payment_verification',
        'CONFIGURE_PAYMENT_METHODS' => 'configure_payment_methods',
        'MANAGE_SUBSCRIPTION_TIERS' => 'manage_subscription_tiers',
        'ACCESS_FINANCIAL_REPORTS' => 'access_financial_reports',
        'MANAGE_TAX_SETTINGS' => 'manage_tax_settings',

        // Analytics & Monitoring
        'VIEW_ANALYTICS' => 'view_analytics',
        'ACCESS_AUDIT_LOGS' => 'access_audit_logs',
        'ACCESS_SYSTEM_MONITORING' => 'access_system_monitoring',

        // Platform Settings
        'MANAGE_SETTINGS' => 'manage_settings',
        'MANAGE_FEATURES' => 'manage_features',
        'MANAGE_PLATFORM_NOTIFICATIONS' => 'manage_platform_notifications',
        'CONFIGURE_SECURITY_POLICIES' => 'configure_security_policies',
        'MANAGE_API_ACCESS' => 'manage_api_access',
        'OVERRIDE_RATE_LIMITS' => 'override_rate_limits',

        // System Operations
        'MANAGE_BACKUP_RESTORE' => 'manage_backup_restore',
        'CONFIGURE_CDN_SETTINGS' => 'configure_cdn_settings',
        'MANAGE_THIRD_PARTY_INTEGRATIONS' => 'manage_third_party_integrations',
        'MANAGE_MAINTENANCE_MODE' => 'manage_maintenance_mode',

        // Creator Features
        'MANAGE_CREATOR_VERIFICATION' => 'manage_creator_verification',
        'SEND_FREE_MESSAGES' => 'send_free_messages',
        'VIEW_PAID_POSTS' => 'view_paid_posts',
        'VIEW_PAID_MEDIA' => 'view_paid_media',

        // Basic User Actions
        'VIEW_PROFILE' => 'view_profile',
        'EDIT_PROFILE' => 'edit_profile',
        'CHANGE_PASSWORD' => 'change_password',
        'ENABLE_TWO_FACTOR_AUTH' => 'enable_two_factor_auth',
        'DISABLE_TWO_FACTOR_AUTH' => 'disable_two_factor_auth',
        'VIEW_NOTIFICATIONS' => 'view_notifications',
        'MANAGE_NOTIFICATIONS' => 'manage_notifications',
        'VIEW_MESSAGES' => 'view_messages',
        'SEND_MESSAGES' => 'send_messages',
        'VIEW_POSTS' => 'view_posts',
        'CREATE_POSTS' => 'create_posts',
        'EDIT_POSTS' => 'edit_posts',
        'DELETE_POSTS' => 'delete_posts',
        'LIKE_POSTS' => 'like_posts',
        'COMMENT_ON_POSTS' => 'comment_on_posts',
        'SHARE_POSTS' => 'share_posts',
        'VIEW_FOLLOWERS' => 'view_followers',
        'FOLLOW_USERS' => 'follow_users',
        'UNFOLLOW_USERS' => 'unfollow_users',
        'BLOCK_USERS' => 'block_users',
        'REPORT_CONTENT' => 'report_content',

        // Support System
        'VIEW_TICKETS' => 'view_tickets',
        'CREATE_TICKETS' => 'create_tickets',
        'EDIT_TICKETS' => 'edit_tickets',
        'DELETE_TICKETS' => 'delete_tickets',
        'ASSIGN_TICKETS' => 'assign_tickets',
        'RESOLVE_TICKETS' => 'resolve_tickets',
        'ESCALATE_TICKETS' => 'escalate_tickets',
        'VIEW_TICKET_HISTORY' => 'view_ticket_history',
        'MANAGE_TICKET_CATEGORIES' => 'manage_ticket_categories',
        'ACCESS_SUPPORT_REPORTS' => 'access_support_reports',
        'MANAGE_SUPPORT_SETTINGS' => 'manage_support_settings',
    ];

    // Role definitions with permissions
    const ROLES = [
        'ADMIN' => [
            'name' => 'admin',
            'permissions' => [
                'delete_accounts',
                'view_sensitive_content',
                'manage_users',
                'view_user_data',
                'manage_content',
                'view_reports',
                'manage_reports',
                'manage_billing',
                'view_analytics',
                'manage_settings',
                'manage_features',
                'send_free_messages',
                'view_paid_posts',
                'view_paid_media',
                'bulk_user_operations',
                'impersonate_users',
                'override_payment_verification',
                'manage_creator_verification',
                'access_audit_logs',
                'export_user_data',
                'manage_content_moderation',
                'configure_payment_methods',
                'manage_subscription_tiers',
                'override_content_restrictions',
                'manage_platform_notifications',
                'access_financial_reports',
                'manage_tax_settings',
                'configure_security_policies',
                'manage_api_access',
                'override_rate_limits',
                'manage_backup_restore',
                'configure_cdn_settings',
                'manage_third_party_integrations',
                'access_system_monitoring',
                'manage_maintenance_mode',
            ],
        ],
        'USER' => [
            'name' => 'user',
            'permissions' => [
                'view_profile',
                'edit_profile',
                'change_password',
                'enable_two_factor_auth',
                'disable_two_factor_auth',
                'view_notifications',
                'manage_notifications',
                'view_messages',
                'send_messages',
                'view_posts',
                'create_posts',
                'edit_posts',
                'delete_posts',
                'like_posts',
                'comment_on_posts',
                'share_posts',
                'view_followers',
                'follow_users',
                'unfollow_users',
                'block_users',
                'report_content',
            ],
        ],
        'SUPPORT' => [
            'name' => 'support',
            'permissions' => [
                'view_tickets',
                'create_tickets',
                'edit_tickets',
                'delete_tickets',
                'assign_tickets',
                'resolve_tickets',
                'escalate_tickets',
                'view_ticket_history',
                'manage_ticket_categories',
                'view_user_data',
                'access_support_reports',
                'manage_support_settings',
            ],
        ],
    ];

    /**
     * Get all permissions as an array
     */
    public static function getAllPermissions(): array
    {
        return array_values(self::PERMISSIONS);
    }

    /**
     * Get permissions by category
     */
    public static function getPermissionsByCategory(): array
    {
        return [
            'Basic User Actions' => [
                'view_profile',
                'edit_profile',
                'change_password',
                'enable_two_factor_auth',
                'disable_two_factor_auth',
                'view_notifications',
                'manage_notifications',
                'view_messages',
                'send_messages',
                'view_posts',
                'create_posts',
                'edit_posts',
                'delete_posts',
                'like_posts',
                'comment_on_posts',
                'share_posts',
                'view_followers',
                'follow_users',
                'unfollow_users',
                'block_users',
                'report_content',
            ],
            'User Management' => [
                'delete_accounts',
                'view_sensitive_content',
                'manage_users',
                'view_user_data',
                'bulk_user_operations',
                'impersonate_users',
                'export_user_data',
            ],
            'Content Management' => [
                'manage_content',
                'view_reports',
                'manage_reports',
                'manage_content_moderation',
                'override_content_restrictions',
                'manage_creator_verification',
            ],
            'Financial & Billing' => [
                'manage_billing',
                'override_payment_verification',
                'configure_payment_methods',
                'manage_subscription_tiers',
                'access_financial_reports',
                'manage_tax_settings',
            ],
            'Analytics & Monitoring' => [
                'view_analytics',
                'access_audit_logs',
                'access_system_monitoring',
            ],
            'Platform Settings' => [
                'manage_settings',
                'manage_features',
                'manage_platform_notifications',
                'configure_security_policies',
                'manage_api_access',
                'override_rate_limits',
            ],
            'System Operations' => [
                'manage_backup_restore',
                'configure_cdn_settings',
                'manage_third_party_integrations',
                'manage_maintenance_mode',
            ],
            'Support System' => [
                'view_tickets',
                'create_tickets',
                'edit_tickets',
                'delete_tickets',
                'assign_tickets',
                'resolve_tickets',
                'escalate_tickets',
                'view_ticket_history',
                'manage_ticket_categories',
                'access_support_reports',
                'manage_support_settings',
            ],
            'Creator Features' => [
                'send_free_messages',
                'view_paid_posts',
                'view_paid_media',
            ],
        ];
    }

    /**
     * RBAC utility functions
     */
    public static function hasPermission(array $userRoles, string $permission): bool
    {
        foreach ($userRoles as $role) {
            $roleConfig = self::getRoleConfig($role);
            if ($roleConfig && in_array($permission, $roleConfig['permissions'])) {
                return true;
            }
        }
        return false;
    }

    public static function hasAnyPermission(array $userRoles, array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (self::hasPermission($userRoles, $permission)) {
                return true;
            }
        }
        return false;
    }

    public static function hasAllPermissions(array $userRoles, array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!self::hasPermission($userRoles, $permission)) {
                return false;
            }
        }
        return true;
    }

    public static function getRolePermissions(string $roleName): array
    {
        $roleConfig = self::getRoleConfig($roleName);
        return $roleConfig ? $roleConfig['permissions'] : [];
    }

    /**
     * Get role configuration by name
     */
    private static function getRoleConfig(string $roleName): ?array
    {
        foreach (self::ROLES as $role) {
            if ($role['name'] === $roleName) {
                return $role;
            }
        }
        return null;
    }

    /**
     * Get all role names
     */
    public static function getAllRoles(): array
    {
        return array_column(self::ROLES, 'name');
    }

    /**
     * Check if a permission exists
     */
    public static function permissionExists(string $permission): bool
    {
        return in_array($permission, self::getAllPermissions());
    }

    /**
     * Get permission label for display
     */
    public static function getPermissionLabel(string $permission): string
    {
        $labels = [
            'view_profile' => 'View Profile',
            'edit_profile' => 'Edit Profile',
            'change_password' => 'Change Password',
            'enable_two_factor_auth' => 'Enable 2FA',
            'disable_two_factor_auth' => 'Disable 2FA',
            'view_notifications' => 'View Notifications',
            'manage_notifications' => 'Manage Notifications',
            'view_messages' => 'View Messages',
            'send_messages' => 'Send Messages',
            'view_posts' => 'View Posts',
            'create_posts' => 'Create Posts',
            'edit_posts' => 'Edit Posts',
            'delete_posts' => 'Delete Posts',
            'like_posts' => 'Like Posts',
            'comment_on_posts' => 'Comment on Posts',
            'share_posts' => 'Share Posts',
            'view_followers' => 'View Followers',
            'follow_users' => 'Follow Users',
            'unfollow_users' => 'Unfollow Users',
            'block_users' => 'Block Users',
            'report_content' => 'Report Content',
            'delete_accounts' => 'Delete Accounts',
            'view_sensitive_content' => 'View Sensitive Content',
            'manage_users' => 'Manage Users',
            'view_user_data' => 'View User Data',
            'bulk_user_operations' => 'Bulk User Operations',
            'impersonate_users' => 'Impersonate Users',
            'export_user_data' => 'Export User Data',
            'manage_content' => 'Manage Content',
            'view_reports' => 'View Reports',
            'manage_reports' => 'Manage Reports',
            'manage_content_moderation' => 'Content Moderation',
            'override_content_restrictions' => 'Override Content Restrictions',
            'manage_creator_verification' => 'Creator Verification',
            'manage_billing' => 'Manage Billing',
            'override_payment_verification' => 'Override Payment Verification',
            'configure_payment_methods' => 'Configure Payment Methods',
            'manage_subscription_tiers' => 'Manage Subscription Tiers',
            'access_financial_reports' => 'Financial Reports',
            'manage_tax_settings' => 'Tax Settings',
            'view_analytics' => 'View Analytics',
            'access_audit_logs' => 'Audit Logs',
            'access_system_monitoring' => 'System Monitoring',
            'manage_settings' => 'Manage Settings',
            'manage_features' => 'Manage Features',
            'manage_platform_notifications' => 'Platform Notifications',
            'configure_security_policies' => 'Security Policies',
            'manage_api_access' => 'API Access',
            'override_rate_limits' => 'Override Rate Limits',
            'manage_backup_restore' => 'Backup & Restore',
            'configure_cdn_settings' => 'CDN Settings',
            'manage_third_party_integrations' => 'Third-Party Integrations',
            'manage_maintenance_mode' => 'Maintenance Mode',
            'view_tickets' => 'View Tickets',
            'create_tickets' => 'Create Tickets',
            'edit_tickets' => 'Edit Tickets',
            'delete_tickets' => 'Delete Tickets',
            'assign_tickets' => 'Assign Tickets',
            'resolve_tickets' => 'Resolve Tickets',
            'escalate_tickets' => 'Escalate Tickets',
            'view_ticket_history' => 'View Ticket History',
            'manage_ticket_categories' => 'Ticket Categories',
            'access_support_reports' => 'Support Reports',
            'manage_support_settings' => 'Support Settings',
            'send_free_messages' => 'Send Free Messages',
            'view_paid_posts' => 'View Paid Posts',
            'view_paid_media' => 'View Paid Media',
        ];

        return $labels[$permission] ?? ucwords(str_replace('_', ' ', $permission));
    }
}
