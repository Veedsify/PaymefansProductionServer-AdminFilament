<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class UserPermissions extends Page
{
    protected static string $resource = UserResource::class;
    protected static string $view = "filament.resources.user-resource.pages.user-permissions";

    public ?array $data = [];
    public User $record;

    public function mount(int|string $record): void
    {
        $this->record = $this->extractUserFromParameter($record);

        // Handle existing flags field if present
        $existingFlags = [];
        if (isset($this->record->flags) && is_string($this->record->flags)) {
            $existingFlags = json_decode($this->record->flags, true) ?? [];
        }

        $this->form->fill([
            "admin" => $this->record->admin,
            "is_active" => $this->record->is_active,
            "is_verified" => $this->record->is_verified,
            "is_email_verified" => $this->record->is_email_verified,
            "is_phone_verified" => $this->record->is_phone_verified,
            "is_model" => $this->record->is_model,
            "active_status" => $this->record->active_status,
            "role" => $this->record->role,
            // Set default values for new permissions if they don't exist
            "can_create_content" => $this->record->can_create_content ?? true,
            "can_comment" => $this->record->can_comment ?? true,
            "can_message" => $this->record->can_message ?? true,
            "can_livestream" => $this->record->can_livestream ?? false,
            "can_purchase" => $this->record->can_purchase ?? true,
            "can_withdraw" => $this->record->can_withdraw ?? false,
            "can_receive_gifts" => $this->record->can_receive_gifts ?? true,
            "content_requires_approval" =>
                $this->record->content_requires_approval ?? false,
            "is_shadowbanned" => $this->record->is_shadowbanned ?? false,
            "can_report" => $this->record->can_report ?? true,
            "immune_to_reports" => $this->record->immune_to_reports ?? false,
            "can_create_groups" => $this->record->can_create_groups ?? true,
            "can_access_analytics" =>
                $this->record->can_access_analytics ?? false,
            "can_use_premium_features" =>
                $this->record->can_use_premium_features ?? false,
        ]);
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
                                "moderator" => "Moderator",
                                "support" => "Support Staff",
                            ])
                            ->helperText(
                                'Defines the user\'s role and access level on the platform'
                            )
                            ->native(false)
                            ->required(),
                    ]),

                Forms\Components\Section::make("Advanced Permissions")
                    ->description(
                        "Additional permission flags and capabilities"
                    )
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Toggle::make("can_create_content")
                                ->label("Can Create Content")
                                ->helperText(
                                    "Allows user to create and publish content"
                                )
                                ->inline(false)
                                ->default(true),

                            Forms\Components\Toggle::make("can_comment")
                                ->label("Can Comment")
                                ->helperText("Allows user to comment on posts")
                                ->inline(false)
                                ->default(true),

                            Forms\Components\Toggle::make("can_message")
                                ->label("Can Send Messages")
                                ->helperText(
                                    "Allows user to send direct messages"
                                )
                                ->inline(false)
                                ->default(true),

                            Forms\Components\Toggle::make("can_livestream")
                                ->label("Can Live Stream")
                                ->helperText(
                                    "Allows user to create live streams"
                                )
                                ->inline(false)
                                ->default(false),

                            Forms\Components\Toggle::make("can_purchase")
                                ->label("Can Make Purchases")
                                ->helperText(
                                    "Allows user to make purchases on the platform"
                                )
                                ->inline(false)
                                ->default(true),

                            Forms\Components\Toggle::make("can_withdraw")
                                ->label("Can Withdraw Funds")
                                ->helperText("Allows user to withdraw earnings")
                                ->inline(false)
                                ->default(false),
                        ]),
                    ]),

                Forms\Components\Section::make("Content Moderation")
                    ->description("Content and behavior moderation settings")
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Toggle::make(
                                "content_requires_approval"
                            )
                                ->label("Content Requires Approval")
                                ->helperText(
                                    "All content must be approved before publishing"
                                )
                                ->inline(false)
                                ->default(false),

                            Forms\Components\Toggle::make("is_shadowbanned")
                                ->label("Shadow Banned")
                                ->helperText(
                                    "User content has limited visibility"
                                )
                                ->inline(false)
                                ->default(false),

                            Forms\Components\Toggle::make("can_report")
                                ->label("Can Report Content")
                                ->helperText(
                                    "Allows user to report inappropriate content"
                                )
                                ->inline(false)
                                ->default(true),

                            Forms\Components\Toggle::make("immune_to_reports")
                                ->label("Immune to Reports")
                                ->helperText(
                                    "User cannot be reported by other users"
                                )
                                ->inline(false)
                                ->default(false),
                        ]),
                    ]),

                Forms\Components\Section::make("Platform Features")
                    ->description("Access to specific platform features")
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Toggle::make("can_create_groups")
                                ->label("Can Create Groups")
                                ->helperText("Allows user to create groups")
                                ->inline(false)
                                ->default(true),

                            Forms\Components\Toggle::make(
                                "can_access_analytics"
                            )
                                ->label("Can Access Analytics")
                                ->helperText(
                                    "Allows access to analytics dashboard"
                                )
                                ->inline(false)
                                ->default(false),

                            Forms\Components\Toggle::make(
                                "can_use_premium_features"
                            )
                                ->label("Premium Features Access")
                                ->helperText(
                                    "Access to premium platform features"
                                )
                                ->inline(false)
                                ->default(false),

                            Forms\Components\Toggle::make("can_receive_gifts")
                                ->label("Can Receive Gifts")
                                ->helperText(
                                    "Allows user to receive virtual gifts"
                                )
                                ->inline(false)
                                ->default(true),
                        ]),
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
            // First, update basic user fields that always exist
            $basicUpdateData = [
                "admin" => $data["admin"] ?? false,
                "is_active" => $data["is_active"] ?? true,
                "is_verified" => $data["is_verified"] ?? false,
                "is_email_verified" => $data["is_email_verified"] ?? false,
                "is_phone_verified" => $data["is_phone_verified"] ?? false,
                "is_model" => $data["is_model"] ?? false,
                "active_status" => $data["active_status"] ?? true,
                "role" => $data["role"] ?? "fan",
            ];

            // Check if new permission columns exist by examining table schema
            $permissionFields = [
                "can_create_content" => $data["can_create_content"] ?? true,
                "can_comment" => $data["can_comment"] ?? true,
                "can_message" => $data["can_message"] ?? true,
                "can_livestream" => $data["can_livestream"] ?? false,
                "can_purchase" => $data["can_purchase"] ?? true,
                "can_withdraw" => $data["can_withdraw"] ?? false,
                "can_receive_gifts" => $data["can_receive_gifts"] ?? true,
                "content_requires_approval" =>
                    $data["content_requires_approval"] ?? false,
                "is_shadowbanned" => $data["is_shadowbanned"] ?? false,
                "can_report" => $data["can_report"] ?? true,
                "immune_to_reports" => $data["immune_to_reports"] ?? false,
                "can_create_groups" => $data["can_create_groups"] ?? true,
                "can_access_analytics" =>
                    $data["can_access_analytics"] ?? false,
                "can_use_premium_features" =>
                    $data["can_use_premium_features"] ?? false,
            ];

            // Use schema introspection to check which columns exist
            $tableColumns = \Schema::getColumnListing("User");

            foreach ($permissionFields as $field => $value) {
                if (in_array($field, $tableColumns)) {
                    $basicUpdateData[$field] = $value;
                }
            }

            // Handle flags field if it exists and store permission data there as fallback
            if (in_array("flags", $tableColumns)) {
                $existingFlags = [];
                if ($this->record->flags && is_array($this->record->flags)) {
                    $existingFlags = $this->record->flags;
                } elseif (
                    $this->record->flags &&
                    is_string($this->record->flags)
                ) {
                    $existingFlags =
                        json_decode($this->record->flags, true) ?? [];
                }

                // Store permissions in flags as backup
                $existingFlags["permissions"] = $permissionFields;
                $basicUpdateData["flags"] = $existingFlags;
            }

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
            "can_create_content" => true,
            "can_comment" => true,
            "can_message" => true,
            "can_livestream" => false,
            "can_purchase" => true,
            "can_withdraw" => false,
            "content_requires_approval" => false,
            "is_shadowbanned" => false,
            "can_report" => true,
            "immune_to_reports" => false,
            "can_create_groups" => true,
            "can_access_analytics" => false,
            "can_use_premium_features" => false,
            "can_receive_gifts" => true,
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
                "'{$this->getTemplateLabel(
                    $template
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
                "role" => "fan",
                "can_create_content" => true,
                "can_comment" => true,
                "can_message" => true,
                "can_livestream" => false,
                "can_purchase" => true,
                "can_withdraw" => false,
                "content_requires_approval" => false,
                "is_shadowbanned" => false,
                "can_report" => true,
                "immune_to_reports" => false,
                "can_create_groups" => true,
                "can_access_analytics" => false,
                "can_use_premium_features" => false,
                "can_receive_gifts" => true,
            ],
            "content_creator" => [
                "admin" => false,
                "is_active" => true,
                "is_verified" => true,
                "is_email_verified" => true,
                "is_phone_verified" => true,
                "is_model" => true,
                "active_status" => true,
                "role" => "fan",
                "can_create_content" => true,
                "can_comment" => true,
                "can_message" => true,
                "can_livestream" => true,
                "can_purchase" => true,
                "can_withdraw" => true,
                "content_requires_approval" => false,
                "is_shadowbanned" => false,
                "can_report" => true,
                "immune_to_reports" => false,
                "can_create_groups" => true,
                "can_access_analytics" => true,
                "can_use_premium_features" => true,
                "can_receive_gifts" => true,
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
                "can_create_content" => true,
                "can_comment" => true,
                "can_message" => true,
                "can_livestream" => false,
                "can_purchase" => true,
                "can_withdraw" => false,
                "content_requires_approval" => false,
                "is_shadowbanned" => false,
                "can_report" => true,
                "immune_to_reports" => true,
                "can_create_groups" => true,
                "can_access_analytics" => true,
                "can_use_premium_features" => false,
                "can_receive_gifts" => false,
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
                "can_create_content" => true,
                "can_comment" => true,
                "can_message" => true,
                "can_livestream" => true,
                "can_purchase" => true,
                "can_withdraw" => true,
                "content_requires_approval" => false,
                "is_shadowbanned" => false,
                "can_report" => true,
                "immune_to_reports" => true,
                "can_create_groups" => true,
                "can_access_analytics" => true,
                "can_use_premium_features" => true,
                "can_receive_gifts" => true,
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
        return [
                // Can add widgets here for permission summaries, etc.
            ];
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
