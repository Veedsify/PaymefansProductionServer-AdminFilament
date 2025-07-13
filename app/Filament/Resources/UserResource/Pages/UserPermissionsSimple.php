<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class UserPermissionsSimple extends Page
{
    protected static string $resource = UserResource::class;
    protected static string $view = "filament.resources.user-resource.pages.user-permissions";

    public ?array $data = [];
    public User $record;

    public function mount(int|string $record): void
    {
        $this->record = $this->extractUserFromParameter($record);

        // Get existing flags as array
        $existingFlags = $this->record->flags ?? [];

        // Handle if flags is stored as JSON string (for backward compatibility)
        if (is_string($existingFlags)) {
            $existingFlags = json_decode($existingFlags, true) ?? [];
        }

        // Convert array of permission names to boolean flags for form
        $permissions = [];
        if (is_array($existingFlags)) {
            foreach ($existingFlags as $permission) {
                $permissions[$permission] = true;
            }
        }

        // Define default permissions
        $defaultPermissions = [
            "view_profile" => true,
            "edit_profile" => true,
            "change_password" => true,
            "enable_two_factor_auth" => true,
            "view_notifications" => true,
            "manage_notifications" => true,
            "view_messages" => true,
            "send_messages" => true,
            "view_posts" => true,
            "create_posts" => true,
            "edit_posts" => true,
            "delete_posts" => true,
            "like_posts" => true,
            "comment_on_posts" => true,
            "share_posts" => true,
            "follow_users" => true,
            "block_users" => true,
            "report_content" => true,
            "delete_accounts" => false,
            "view_sensitive_content" => false,
            "manage_users" => false,
            "view_user_data" => false,
            "bulk_user_operations" => false,
            "impersonate_users" => false,
            "export_user_data" => false,
            "manage_content" => false,
            "view_reports" => false,
            "manage_reports" => false,
            "manage_content_moderation" => false,
            "override_content_restrictions" => false,
            "manage_creator_verification" => false,
            "manage_billing" => false,
            "override_payment_verification" => false,
            "configure_payment_methods" => false,
            "manage_subscription_tiers" => false,
            "access_financial_reports" => false,
            "manage_tax_settings" => false,
            "view_analytics" => false,
            "access_audit_logs" => false,
            "access_system_monitoring" => false,
            "manage_settings" => false,
            "manage_features" => false,
            "manage_platform_notifications" => false,
            "configure_security_policies" => false,
            "manage_api_access" => false,
            "override_rate_limits" => false,
            "manage_backup_restore" => false,
            "configure_cdn_settings" => false,
            "manage_third_party_integrations" => false,
            "manage_maintenance_mode" => false,
            "view_tickets" => false,
            "create_tickets" => false,
            "edit_tickets" => false,
            "delete_tickets" => false,
            "assign_tickets" => false,
            "resolve_tickets" => false,
            "escalate_tickets" => false,
            "view_ticket_history" => false,
            "manage_ticket_categories" => false,
            "access_support_reports" => false,
            "manage_support_settings" => false,
            "send_free_messages" => false,
            "view_paid_posts" => false,
            "view_paid_media" => false,
            "super_admin" => false,
            "admin" => false,
            "profile_hidden" => false,
            "cant_report" => false,
            "cant_block" => false,
            "cant_comment" => false,
            "profile_not_cached" => false,
            "cant_mention" => false,
            "cant_follow" => false,
        ];

        // Initialize form data
        $formData = [
            "admin" => $this->record->admin ?? false,
            "is_active" => $this->record->is_active ?? true,
            "is_verified" => $this->record->is_verified ?? false,
            "is_email_verified" => $this->record->is_email_verified ?? false,
            "is_phone_verified" => $this->record->is_phone_verified ?? false,
            "is_model" => $this->record->is_model ?? false,
            "active_status" => $this->record->active_status ?? true,
            "role" => $this->record->role ?? "fan",
        ];

        // Merge permissions with defaults
        foreach ($defaultPermissions as $field => $default) {
            $formData[$field] = $permissions[$field] ?? $default;
        }

        $this->form->fill($formData);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make("User Information")
                    ->description("Basic user details")
                    ->schema([
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\TextInput::make("name")
                                ->label("Display Name")
                                ->disabled()
                                ->default($this->record->name),
                            Forms\Components\TextInput::make("email")
                                ->label("Email")
                                ->disabled()
                                ->default($this->record->email),
                            Forms\Components\TextInput::make("username")
                                ->label("Username")
                                ->disabled()
                                ->default($this->record->username),
                        ]),
                    ]),

                Forms\Components\Section::make("Account Permissions & Status")
                    ->description(
                        "Manage user account permissions and status flags"
                    )
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Toggle::make("admin")
                                ->label("Administrator")
                                ->helperText(
                                    "Grants access to admin panel and administrative features"
                                )
                                ->inline(false),

                            Forms\Components\Toggle::make("is_active")
                                ->label("Account Active")
                                ->helperText(
                                    "Controls if the user account is active"
                                )
                                ->inline(false),

                            Forms\Components\Toggle::make("active_status")
                                ->label("Login Status")
                                ->helperText(
                                    "Controls if the user can login to the platform"
                                )
                                ->inline(false),

                            Forms\Components\Toggle::make("is_model")
                                ->label("Content Creator (Model)")
                                ->helperText(
                                    "Grants access to content creation and monetization features"
                                )
                                ->inline(false),
                        ]),
                    ]),

                Forms\Components\Section::make("Verification Status")
                    ->description("User verification flags")
                    ->schema([
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\Toggle::make("is_verified")
                                ->label("Account Verified")
                                ->helperText(
                                    "General account verification status"
                                )
                                ->inline(false),

                            Forms\Components\Toggle::make("is_email_verified")
                                ->label("Email Verified")
                                ->helperText(
                                    "Email address verification status"
                                )
                                ->inline(false),

                            Forms\Components\Toggle::make("is_phone_verified")
                                ->label("Phone Verified")
                                ->helperText("Phone number verification status")
                                ->inline(false),
                        ]),
                    ]),

                Forms\Components\Section::make("Role Assignment")
                    ->description("User role and access level")
                    ->schema([
                        Forms\Components\Select::make("role")
                            ->label("User Role")
                            ->options([
                                "admin" => "Administrator",
                                "fan" => "Regular User/Fan",
                                "model" => "Model",
                                "support" => "Support Staff",
                            ])
                            ->helperText(
                                "Defines the user's role and access level on the platform"
                            )
                            ->native(false)
                            ->required(),
                    ]),

                // User Management Permissions
                Forms\Components\Section::make("User Management Permissions")
                    ->description(
                        "Administrative permissions for user management"
                    )
                    ->schema([
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\Toggle::make("delete_accounts")
                                ->label("Delete Accounts")
                                ->helperText(
                                    "Can permanently delete user accounts"
                                )
                                ->inline(false),

                            Forms\Components\Toggle::make(
                                "view_sensitive_content"
                            )
                                ->label("View Sensitive Content")
                                ->helperText(
                                    "Access to flagged/sensitive content"
                                )
                                ->inline(false),

                            Forms\Components\Toggle::make("manage_users")
                                ->label("Manage Users")
                                ->helperText(
                                    "General user management capabilities"
                                )
                                ->inline(false),

                            Forms\Components\Toggle::make("view_user_data")
                                ->label("View User Data")
                                ->helperText(
                                    "Access to detailed user information"
                                )
                                ->inline(false),

                            Forms\Components\Toggle::make(
                                "bulk_user_operations"
                            )
                                ->label("Bulk User Operations")
                                ->helperText(
                                    "Perform actions on multiple users"
                                )
                                ->inline(false),

                            Forms\Components\Toggle::make("impersonate_users")
                                ->label("Impersonate Users")
                                ->helperText("Login as other users for support")
                                ->inline(false),

                            Forms\Components\Toggle::make("export_user_data")
                                ->label("Export User Data")
                                ->helperText("Export user data for compliance")
                                ->inline(false),
                        ]),
                    ]),

                // Content Management Permissions
                Forms\Components\Section::make("Content Management Permissions")
                    ->description(
                        "Permissions for content moderation and management"
                    )
                    ->schema([
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\Toggle::make("manage_content")
                                ->label("Manage Content")
                                ->helperText(
                                    "Edit, approve, or remove user content"
                                )
                                ->inline(false),

                            Forms\Components\Toggle::make("view_reports")
                                ->label("View Reports")
                                ->helperText("Access content and user reports")
                                ->inline(false),

                            Forms\Components\Toggle::make("manage_reports")
                                ->label("Manage Reports")
                                ->helperText("Take action on reported content")
                                ->inline(false),

                            Forms\Components\Toggle::make(
                                "manage_content_moderation"
                            )
                                ->label("Content Moderation")
                                ->helperText(
                                    "Advanced content moderation tools"
                                )
                                ->inline(false),

                            Forms\Components\Toggle::make(
                                "override_content_restrictions"
                            )
                                ->label("Override Content Restrictions")
                                ->helperText(
                                    "Bypass content posting restrictions"
                                )
                                ->inline(false),

                            Forms\Components\Toggle::make(
                                "manage_creator_verification"
                            )
                                ->label("Creator Verification")
                                ->helperText(
                                    "Verify and manage creator accounts"
                                )
                                ->inline(false),
                        ]),
                    ]),

                // Financial & Billing Permissions
                Forms\Components\Section::make(
                    "Financial & Billing Permissions"
                )
                    ->description(
                        "Permissions for financial operations and billing"
                    )
                    ->schema([
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\Toggle::make("manage_billing")
                                ->label("Manage Billing")
                                ->helperText(
                                    "Access billing and payment systems"
                                )
                                ->inline(false),

                            Forms\Components\Toggle::make(
                                "override_payment_verification"
                            )
                                ->label("Override Payment Verification")
                                ->helperText(
                                    "Bypass payment verification checks"
                                )
                                ->inline(false),

                            Forms\Components\Toggle::make(
                                "configure_payment_methods"
                            )
                                ->label("Configure Payment Methods")
                                ->helperText("Manage payment gateway settings")
                                ->inline(false),

                            Forms\Components\Toggle::make(
                                "manage_subscription_tiers"
                            )
                                ->label("Manage Subscription Tiers")
                                ->helperText(
                                    "Create and modify subscription plans"
                                )
                                ->inline(false),

                            Forms\Components\Toggle::make(
                                "access_financial_reports"
                            )
                                ->label("Financial Reports")
                                ->helperText(
                                    "Access financial analytics and reports"
                                )
                                ->inline(false),

                            Forms\Components\Toggle::make("manage_tax_settings")
                                ->label("Tax Settings")
                                ->helperText(
                                    "Configure tax calculations and reports"
                                )
                                ->inline(false),
                        ]),
                    ]),

                // Analytics & Monitoring Permissions
                Forms\Components\Section::make(
                    "Analytics & Monitoring Permissions"
                )
                    ->description("Access to analytics and system monitoring")
                    ->schema([
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\Toggle::make("view_analytics")
                                ->label("View Analytics")
                                ->helperText(
                                    "Access platform analytics dashboard"
                                )
                                ->inline(false),

                            Forms\Components\Toggle::make("access_audit_logs")
                                ->label("Audit Logs")
                                ->helperText(
                                    "View system audit and activity logs"
                                )
                                ->inline(false),

                            Forms\Components\Toggle::make(
                                "access_system_monitoring"
                            )
                                ->label("System Monitoring")
                                ->helperText(
                                    "Access system health and performance data"
                                )
                                ->inline(false),
                        ]),
                    ]),

                // Platform Settings Permissions
                Forms\Components\Section::make("Platform Settings Permissions")
                    ->description(
                        "Control over platform configuration and settings"
                    )
                    ->schema([
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\Toggle::make("manage_settings")
                                ->label("Manage Settings")
                                ->helperText("Modify platform configuration")
                                ->inline(false),

                            Forms\Components\Toggle::make("manage_features")
                                ->label("Manage Features")
                                ->helperText("Enable/disable platform features")
                                ->inline(false),

                            Forms\Components\Toggle::make(
                                "manage_platform_notifications"
                            )
                                ->label("Platform Notifications")
                                ->helperText("Manage system-wide notifications")
                                ->inline(false),

                            Forms\Components\Toggle::make(
                                "configure_security_policies"
                            )
                                ->label("Security Policies")
                                ->helperText(
                                    "Configure security and privacy settings"
                                )
                                ->inline(false),

                            Forms\Components\Toggle::make("manage_api_access")
                                ->label("API Access")
                                ->helperText(
                                    "Manage API keys and access controls"
                                )
                                ->inline(false),

                            Forms\Components\Toggle::make(
                                "override_rate_limits"
                            )
                                ->label("Override Rate Limits")
                                ->helperText(
                                    "Bypass API and action rate limits"
                                )
                                ->inline(false),
                        ]),
                    ]),

                // System Operations Permissions
                Forms\Components\Section::make("System Operations Permissions")
                    ->description("Advanced system administration capabilities")
                    ->schema([
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\Toggle::make(
                                "manage_backup_restore"
                            )
                                ->label("Backup & Restore")
                                ->helperText(
                                    "Manage system backups and restoration"
                                )
                                ->inline(false),

                            Forms\Components\Toggle::make(
                                "configure_cdn_settings"
                            )
                                ->label("CDN Settings")
                                ->helperText(
                                    "Configure content delivery network"
                                )
                                ->inline(false),

                            Forms\Components\Toggle::make(
                                "manage_third_party_integrations"
                            )
                                ->label("Third-Party Integrations")
                                ->helperText(
                                    "Manage external service integrations"
                                )
                                ->inline(false),

                            Forms\Components\Toggle::make(
                                "manage_maintenance_mode"
                            )
                                ->label("Maintenance Mode")
                                ->helperText(
                                    "Control platform maintenance mode"
                                )
                                ->inline(false),
                        ]),
                    ]),

                // Support System Permissions
                Forms\Components\Section::make("Support System Permissions")
                    ->description("Customer support and ticket management")
                    ->schema([
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\Toggle::make("view_tickets")
                                ->label("View Tickets")
                                ->helperText("Access support tickets")
                                ->inline(false),

                            Forms\Components\Toggle::make("create_tickets")
                                ->label("Create Tickets")
                                ->helperText("Create new support tickets")
                                ->inline(false),

                            Forms\Components\Toggle::make("edit_tickets")
                                ->label("Edit Tickets")
                                ->helperText("Modify existing tickets")
                                ->inline(false),

                            Forms\Components\Toggle::make("delete_tickets")
                                ->label("Delete Tickets")
                                ->helperText("Remove support tickets")
                                ->inline(false),

                            Forms\Components\Toggle::make("assign_tickets")
                                ->label("Assign Tickets")
                                ->helperText("Assign tickets to staff members")
                                ->inline(false),

                            Forms\Components\Toggle::make("resolve_tickets")
                                ->label("Resolve Tickets")
                                ->helperText("Mark tickets as resolved")
                                ->inline(false),

                            Forms\Components\Toggle::make("escalate_tickets")
                                ->label("Escalate Tickets")
                                ->helperText("Escalate tickets to higher level")
                                ->inline(false),

                            Forms\Components\Toggle::make("view_ticket_history")
                                ->label("Ticket History")
                                ->helperText("View complete ticket history")
                                ->inline(false),

                            Forms\Components\Toggle::make(
                                "manage_ticket_categories"
                            )
                                ->label("Ticket Categories")
                                ->helperText("Manage support ticket categories")
                                ->inline(false),

                            Forms\Components\Toggle::make(
                                "access_support_reports"
                            )
                                ->label("Support Reports")
                                ->helperText(
                                    "Access support analytics and reports"
                                )
                                ->inline(false),

                            Forms\Components\Toggle::make(
                                "manage_support_settings"
                            )
                                ->label("Support Settings")
                                ->helperText(
                                    "Configure support system settings"
                                )
                                ->inline(false),
                        ]),
                    ]),

                // Basic User Permissions
                Forms\Components\Section::make("Basic User Permissions")
                    ->description("Standard user capabilities on the platform")
                    ->schema([
                        Forms\Components\Grid::make(4)->schema([
                            Forms\Components\Toggle::make("view_profile")
                                ->label("View Profile")
                                ->helperText("Can view own profile")
                                ->inline(false)
                                ->default(true),

                            Forms\Components\Toggle::make("edit_profile")
                                ->label("Edit Profile")
                                ->helperText("Can modify profile information")
                                ->inline(false)
                                ->default(true),

                            Forms\Components\Toggle::make("change_password")
                                ->label("Change Password")
                                ->helperText("Can change account password")
                                ->inline(false)
                                ->default(true),

                            Forms\Components\Toggle::make(
                                "enable_two_factor_auth"
                            )
                                ->label("Enable 2FA")
                                ->helperText(
                                    "Can enable two-factor authentication"
                                )
                                ->inline(false)
                                ->default(true),

                            Forms\Components\Toggle::make("view_notifications")
                                ->label("View Notifications")
                                ->helperText("Can view notifications")
                                ->inline(false)
                                ->default(true),

                            Forms\Components\Toggle::make(
                                "manage_notifications"
                            )
                                ->label("Manage Notifications")
                                ->helperText(
                                    "Can configure notification settings"
                                )
                                ->inline(false)
                                ->default(true),

                            Forms\Components\Toggle::make("view_messages")
                                ->label("View Messages")
                                ->helperText("Can view direct messages")
                                ->inline(false)
                                ->default(true),

                            Forms\Components\Toggle::make("send_messages")
                                ->label("Send Messages")
                                ->helperText("Can send direct messages")
                                ->inline(false)
                                ->default(true),

                            Forms\Components\Toggle::make("view_posts")
                                ->label("View Posts")
                                ->helperText("Can view posts on platform")
                                ->inline(false)
                                ->default(true),

                            Forms\Components\Toggle::make("create_posts")
                                ->label("Create Posts")
                                ->helperText("Can create new posts")
                                ->inline(false)
                                ->default(true),

                            Forms\Components\Toggle::make("edit_posts")
                                ->label("Edit Posts")
                                ->helperText("Can edit own posts")
                                ->inline(false)
                                ->default(true),

                            Forms\Components\Toggle::make("delete_posts")
                                ->label("Delete Posts")
                                ->helperText("Can delete own posts")
                                ->inline(false)
                                ->default(true),

                            Forms\Components\Toggle::make("like_posts")
                                ->label("Like Posts")
                                ->helperText("Can like/react to posts")
                                ->inline(false)
                                ->default(true),

                            Forms\Components\Toggle::make("comment_on_posts")
                                ->label("Comment on Posts")
                                ->helperText("Can comment on posts")
                                ->inline(false)
                                ->default(true),

                            Forms\Components\Toggle::make("share_posts")
                                ->label("Share Posts")
                                ->helperText("Can share posts")
                                ->inline(false)
                                ->default(true),

                            Forms\Components\Toggle::make("follow_users")
                                ->label("Follow Users")
                                ->helperText("Can follow other users")
                                ->inline(false)
                                ->default(true),

                            Forms\Components\Toggle::make("block_users")
                                ->label("Block Users")
                                ->helperText("Can block other users")
                                ->inline(false)
                                ->default(true),

                            Forms\Components\Toggle::make("report_content")
                                ->label("Report Content")
                                ->helperText("Can report inappropriate content")
                                ->inline(false)
                                ->default(true),
                        ]),
                    ]),

                // Creator Features
                Forms\Components\Section::make("Creator Features")
                    ->description("Special features for content creators")
                    ->schema([
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\Toggle::make("send_free_messages")
                                ->label("Send Free Messages")
                                ->helperText(
                                    "Can send messages without cost restrictions"
                                )
                                ->inline(false),

                            Forms\Components\Toggle::make("view_paid_posts")
                                ->label("View Paid Posts")
                                ->helperText("Can access premium/paid content")
                                ->inline(false),

                            Forms\Components\Toggle::make("view_paid_media")
                                ->label("View Paid Media")
                                ->helperText("Can access premium media content")
                                ->inline(false),
                        ]),
                    ]),

                //Special Permissions
                Forms\Components\Section::make("Special Permissions")
                    ->description("Advanced permissions for special cases")
                    ->columns(3)
                    ->schema([
                        Forms\Components\Toggle::make("super_admin")
                            ->label("Super Admin")
                            ->helperText(
                                "Grants full access to all platform features"
                            )
                            ->inline(false),
                        Forms\Components\Toggle::make("admin")
                            ->label("Super Admin")
                            ->helperText(
                                "Grants full access to all platform features"
                            )
                            ->inline(false),
                        Forms\Components\Toggle::make("profile_hidden")
                            ->label("Profile Hidden")
                            ->helperText(
                                "Hides user profile from public view"
                            )
                            ->inline(false),

                        Forms\Components\Toggle::make("cant_report")
                            ->label("Cannot Report Content")
                            ->helperText(
                                "User cannot report content on the platform"
                            )
                            ->inline(false),

                        Forms\Components\Toggle::make("cant_block")
                            ->label("Cannot Block Users")
                            ->helperText(
                                "User cannot block other users"
                            )
                            ->inline(false),

                        Forms\Components\Toggle::make("cant_comment")
                            ->label("Cannot Comment on Posts")
                            ->helperText(
                                "User cannot comment on any posts"
                            )
                            ->inline(false),

                        Forms\Components\Toggle::make("profile_not_cached")
                            ->label("Profile Not Cached")
                            ->helperText(
                                "User profile data is not cached for performance"
                            )
                            ->inline(false),
                        Forms\Components\Toggle::make("cant_mention")
                            ->label("Cannot Mention User")
                            ->helperText(
                                "User cannot mention this user in posts or comments"
                            )
                            ->inline(false),
                        Forms\Components\Toggle::make("cant_follow")
                            ->label("Cannot Follow User")
                            ->helperText(
                                "Users cannot follow this user on the platform"
                            )
                            ->inline(false),
                    ]),
            ])
            ->statePath("data");
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make("save")
                ->label("Save Permissions")
                ->color("success")
                ->icon("heroicon-o-check")
                ->action("save"),

            Actions\Action::make("reset")
                ->label("Reset to Default")
                ->color("gray")
                ->icon("heroicon-o-arrow-path")
                ->action("resetToDefaults")
                ->requiresConfirmation()
                ->modalHeading("Reset Permissions to Default")
                ->modalDescription(
                    "Are you sure you want to reset all permissions to their default values?"
                ),

            Actions\Action::make("back")
                ->label("Back to User")
                ->color("gray")
                ->icon("heroicon-o-arrow-left")
                ->url(
                    fn() => UserResource::getUrl("view", [
                        "record" => $this->record,
                    ])
                ),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        try {
            // Update basic user fields
            $basicUpdateData = [
                "admin" => $data["admin"] ?? false,
                "is_active" => $data["is_active"] ?? true,
                "is_verified" => $data["is_verified"] ?? false,
                "is_email_verified" => $data["is_email_verified"] ?? false,
                "is_phone_verified" => $data["is_phone_verified"] ?? false,
                "is_model" => $data["is_model"] ?? false,
                "active_status" => $data["active_status"] ?? true,
                "role" => $data["role"] ?? "user",
            ];

            // Collect all permission fields and convert to array of enabled permissions
            $permissionFields = [
                "view_profile",
                "edit_profile",
                "change_password",
                "enable_two_factor_auth",
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
                "follow_users",
                "block_users",
                "report_content",
                "delete_accounts",
                "view_sensitive_content",
                "manage_users",
                "view_user_data",
                "bulk_user_operations",
                "impersonate_users",
                "export_user_data",
                "manage_content",
                "view_reports",
                "manage_reports",
                "manage_content_moderation",
                "override_content_restrictions",
                "manage_creator_verification",
                "manage_billing",
                "override_payment_verification",
                "configure_payment_methods",
                "manage_subscription_tiers",
                "access_financial_reports",
                "manage_tax_settings",
                "view_analytics",
                "access_audit_logs",
                "access_system_monitoring",
                "manage_settings",
                "manage_features",
                "manage_platform_notifications",
                "configure_security_policies",
                "manage_api_access",
                "override_rate_limits",
                "manage_backup_restore",
                "configure_cdn_settings",
                "manage_third_party_integrations",
                "manage_maintenance_mode",
                "view_tickets",
                "create_tickets",
                "edit_tickets",
                "delete_tickets",
                "assign_tickets",
                "resolve_tickets",
                "escalate_tickets",
                "view_ticket_history",
                "manage_ticket_categories",
                "access_support_reports",
                "manage_support_settings",
                "send_free_messages",
                "view_paid_posts",
                "view_paid_media",
                "super_admin",
                "admin",
                "profile_hidden",
                "cant_report",
                "cant_block",
                "cant_comment",
                "profile_not_cached",
                "cant_mention",
                "cant_follow",
            ];

            // Build array of enabled permissions only
            $enabledPermissions = [];
            foreach ($permissionFields as $permission) {
                if ($data[$permission] ?? false) {
                    $enabledPermissions[] = $permission;
                }
            }

            // Store permissions as simple array
            $basicUpdateData["flags"] = $enabledPermissions;

            // Update the user record
            $this->record->update($basicUpdateData);

            Notification::make()
                ->title("Permissions Updated")
                ->body("User permissions have been successfully updated.")
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title("Error")
                ->body("Failed to update permissions: " . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function resetToDefaults(): void
    {
        $this->form->fill([
            "admin" => false,
            "is_active" => true,
            "is_verified" => false,
            "is_email_verified" => false,
            "is_phone_verified" => false,
            "is_model" => false,
            "active_status" => true,
            "role" => "fan",

            // Basic User Permissions (default enabled)
            "view_profile" => true,
            "edit_profile" => true,
            "change_password" => true,
            "enable_two_factor_auth" => true,
            "view_notifications" => true,
            "manage_notifications" => true,
            "view_messages" => true,
            "send_messages" => true,
            "view_posts" => true,
            "create_posts" => true,
            "edit_posts" => true,
            "delete_posts" => true,
            "like_posts" => true,
            "comment_on_posts" => true,
            "share_posts" => true,
            "follow_users" => true,
            "block_users" => true,
            "report_content" => true,

            // All administrative permissions (default disabled)
            "delete_accounts" => false,
            "view_sensitive_content" => false,
            "manage_users" => false,
            "view_user_data" => false,
            "bulk_user_operations" => false,
            "impersonate_users" => false,
            "export_user_data" => false,
            "manage_content" => false,
            "view_reports" => false,
            "manage_reports" => false,
            "manage_content_moderation" => false,
            "override_content_restrictions" => false,
            "manage_creator_verification" => false,
            "manage_billing" => false,
            "override_payment_verification" => false,
            "configure_payment_methods" => false,
            "manage_subscription_tiers" => false,
            "access_financial_reports" => false,
            "manage_tax_settings" => false,
            "view_analytics" => false,
            "access_audit_logs" => false,
            "access_system_monitoring" => false,
            "manage_settings" => false,
            "manage_features" => false,
            "manage_platform_notifications" => false,
            "configure_security_policies" => false,
            "manage_api_access" => false,
            "override_rate_limits" => false,
            "manage_backup_restore" => false,
            "configure_cdn_settings" => false,
            "manage_third_party_integrations" => false,
            "manage_maintenance_mode" => false,
            "view_tickets" => false,
            "create_tickets" => false,
            "edit_tickets" => false,
            "delete_tickets" => false,
            "assign_tickets" => false,
            "resolve_tickets" => false,
            "escalate_tickets" => false,
            "view_ticket_history" => false,
            "manage_ticket_categories" => false,
            "access_support_reports" => false,
            "manage_support_settings" => false,
            "send_free_messages" => false,
            "view_paid_posts" => false,
            "view_paid_media" => false,
        ]);

        Notification::make()
            ->title("Reset Complete")
            ->body(
                'Permissions have been reset to default values. Click "Save Permissions" to apply changes.'
            )
            ->info()
            ->send();
    }

    public function applyTemplate(string $template): void
    {
        $templates = $this->getPermissionTemplates();

        if (!isset($templates[$template])) {
            Notification::make()
                ->title("Error")
                ->body("Invalid template selected.")
                ->danger()
                ->send();
            return;
        }

        $this->form->fill($templates[$template]);

        Notification::make()
            ->title("Template Applied")
            ->body(
                "'{$this->getTemplateLabel($template
                )}' permissions have been applied. Click 'Save Permissions' to save changes."
            )
            ->success()
            ->send();
    }

    protected function getPermissionTemplates(): array
    {
        return [
            "basic_user" => [
                "admin" => false,
                "is_active" => true,
                "is_verified" => false,
                "is_email_verified" => false,
                "is_phone_verified" => false,
                "is_model" => false,
                "active_status" => true,
                "role" => "user",

                // Basic user permissions (enabled)
                "view_profile" => true,
                "edit_profile" => true,
                "change_password" => true,
                "enable_two_factor_auth" => true,
                "view_notifications" => true,
                "manage_notifications" => true,
                "view_messages" => true,
                "send_messages" => true,
                "view_posts" => true,
                "create_posts" => true,
                "edit_posts" => true,
                "delete_posts" => true,
                "like_posts" => true,
                "comment_on_posts" => true,
                "share_posts" => true,
                "follow_users" => true,
                "block_users" => true,
                "report_content" => true,

                // All admin permissions disabled
                "delete_accounts" => false,
                "view_sensitive_content" => false,
                "manage_users" => false,
                "view_user_data" => false,
                "bulk_user_operations" => false,
                "impersonate_users" => false,
                "export_user_data" => false,
                "manage_content" => false,
                "view_reports" => false,
                "manage_reports" => false,
                "manage_content_moderation" => false,
                "override_content_restrictions" => false,
                "manage_creator_verification" => false,
                "manage_billing" => false,
                "override_payment_verification" => false,
                "configure_payment_methods" => false,
                "manage_subscription_tiers" => false,
                "access_financial_reports" => false,
                "manage_tax_settings" => false,
                "view_analytics" => false,
                "access_audit_logs" => false,
                "access_system_monitoring" => false,
                "manage_settings" => false,
                "manage_features" => false,
                "manage_platform_notifications" => false,
                "configure_security_policies" => false,
                "manage_api_access" => false,
                "override_rate_limits" => false,
                "manage_backup_restore" => false,
                "configure_cdn_settings" => false,
                "manage_third_party_integrations" => false,
                "manage_maintenance_mode" => false,
                "view_tickets" => false,
                "create_tickets" => false,
                "edit_tickets" => false,
                "delete_tickets" => false,
                "assign_tickets" => false,
                "resolverified" => true,
                "is_email_verified" => true,
                "is_phone_verified" => true,
                "is_model" => true,
                "active_status" => true,
                "role" => "fan",
                // Basic User Actions
                "view_profile" => true,
                "edit_profile" => true,
                "change_password" => true,
                "view_posts" => true,
                "create_posts" => true,
                "edit_posts" => true,
                "delete_posts" => true,
                "like_posts" => true,
                "comment_on_posts" => true,
                "share_posts" => true,
                "view_messages" => true,
                "send_messages" => true,
                "follow_users" => true,
                "block_users" => true,
                "report_content" => true,
                // User Management
                "view_user_data" => false,
                "manage_users" => false,
                "delete_accounts" => false,
                "impersonate_users" => false,
                "bulk_user_operations" => false,
                // Content Management
                "manage_content" => false,
                "view_reports" => false,
                "manage_reports" => false,
                "manage_content_moderation" => false,
                "view_sensitive_content" => false,
                // Creator Features
                "manage_creator_verification" => false,
                "send_free_messages" => false,
                "view_paid_posts" => false,
                "view_paid_media" => false,
                // Financial
                "manage_billing" => false,
                "access_financial_reports" => false,
                "manage_subscription_tiers" => false,
                "override_payment_verification" => false,
                "can_purchase" => true,
                "can_withdraw" => false,
                // Platform
                "view_analytics" => false,
                "manage_settings" => false,
                "manage_features" => false,
                "access_audit_logs" => false,
                "manage_api_access" => false,
                // Support
                "view_tickets" => false,
                "create_tickets" => true,
                "resolve_tickets" => false,
                "manage_support_settings" => false,
                // Legacy
                "content_requires_approval" => false,
                "is_shadowbanned" => false,
                "can_livestream" => false,
                "can_receive_gifts" => true,
                "immune_to_reports" => false,
                "can_create_groups" => true,
            ],
            "moderator" => [
                "admin" => false,
                "is_active" => true,
                "is_verified" => true,
                "is_email_verified" => true,
                "is_phone_verified" => true,
                "is_model" => false,
                "active_status" => true,
                "role" => "moderator",
                // Basic User permissions matching FlagsConfig USER role
                "view_profile" => true,
                "edit_profile" => true,
                "change_password" => true,
                "view_posts" => true,
                "create_posts" => true,
                "edit_posts" => true,
                "delete_posts" => true,
                "like_posts" => true,
                "comment_on_posts" => true,
                "share_posts" => true,
                "view_messages" => true,
                "send_messages" => true,
                "follow_users" => true,
                "block_users" => true,
                "report_content" => true,
                // Disable advanced permissions
                "view_user_data" => false,
                "manage_users" => false,
                "manage_content" => false,
                "view_reports" => false,
                "manage_billing" => false,
                "view_analytics" => false,
                "manage_settings" => false,
                // Basic financial permissions
                "can_purchase" => true,
                "can_withdraw" => false,
                "can_receive_gifts" => true,
                "can_livestream" => false,
                "can_create_groups" => true,
                // Moderation defaults
                "content_requires_approval" => false,
                "is_shadowbanned" => false,
                "immune_to_reports" => false,
            ],
            "admin" => [
                "admin" => true,
                "is_active" => true,
                "is_verified" => true,
                "is_email_verified" => true,
                "is_phone_verified" => true,
                "is_model" => false,
                "active_status" => true,
                "role" => "admin",
                // All basic user permissions
                "view_profile" => true,
                "edit_profile" => true,
                "change_password" => true,
                "view_posts" => true,
                "create_posts" => true,
                "edit_posts" => true,
                "delete_posts" => true,
                "like_posts" => true,
                "comment_on_posts" => true,
                "share_posts" => true,
                "view_messages" => true,
                "send_messages" => true,
                "follow_users" => true,
                "block_users" => true,
                "report_content" => true,
                // Creator-specific permissions
                "manage_creator_verification" => false, // Only admins should verify
                "send_free_messages" => true,
                "view_paid_posts" => true,
                "view_paid_media" => true,
                // Financial permissions for creators
                "can_purchase" => true,
                "can_withdraw" => true,
                "can_receive_gifts" => true,
                // Platform features
                "view_analytics" => true,
                "can_livestream" => true,
                "can_create_groups" => true,
                // No admin permissions
                "manage_users" => false,
                "manage_content" => false,
                "manage_billing" => false,
                "manage_settings" => false,
                // Moderation defaults
                "content_requires_approval" => false,
                "is_shadowbanned" => false,
                "immune_to_reports" => false,
            ],
        ];
    }

    protected function getTemplateLabel(string $template): string
    {
        $labels = [
            "basic_user" => "Basic User",
            "content_creator" => "Content Creator",
            "moderator" => "Moderator",
            "admin" => "Administrator",
        ];

        return $labels[$template] ?? $template;
    }

    public function getTitle(): string
    {
        return "Manage Permissions - {$this->record->name}";
    }

    protected function getHeaderWidgets(): array
    {
        return [];
    }

    /**
     * Extract user ID from various parameter formats
     */
    private function extractUserFromParameter(int|string $record): User
    {
        $recordId = null;

        if (is_numeric($record)) {
            // Direct ID
            $recordId = (int) $record;
        } elseif (is_string($record)) {
            // Check if it's a JSON string
            if (str_starts_with($record, "{") && str_ends_with($record, "}")) {
                $data = json_decode($record, true);
                if (
                    json_last_error() === JSON_ERROR_NONE &&
                    isset($data["id"])
                ) {
                    $recordId = (int) $data["id"];
                }
            } else {
                // Try to parse as string ID
                $recordId = (int) $record;
            }
        }

        if (!$recordId || $recordId <= 0) {
            throw new \InvalidArgumentException(
                "Invalid record parameter: " . var_export($record, true)
            );
        }

        return User::findOrFail($recordId);
    }
}
