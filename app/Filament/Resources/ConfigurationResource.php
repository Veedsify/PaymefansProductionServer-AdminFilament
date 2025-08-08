<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConfigurationResource\Pages\CreateConfiguration;
use App\Filament\Resources\ConfigurationResource\Pages\EditConfiguration;
use App\Filament\Resources\ConfigurationResource\Pages\ListConfigurations;
use App\Filament\Resources\ConfigurationResource\RelationManagers;
use App\Models\Configuration;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConfigurationResource extends Resource
{
    protected static ?string $model = Configuration::class;

    protected static ?string $navigationIcon = "heroicon-o-cog";
    protected static ?string $navigationGroup = "System";

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make("Application Settings")
                ->collapsible()
                ->schema([
                    TextInput::make("app_name")
                        ->label("Application Name")
                        ->required()
                        ->maxLength(255),
                    TextInput::make("app_version")
                        ->label("Version")
                        ->required()
                        ->maxLength(50),
                    Textarea::make("app_description")
                        ->label("Description")
                        ->rows(4)
                        ->maxLength(500),
                    TextInput::make("app_logo")
                        ->label("Logo URL")
                        ->url()
                        ->maxLength(255),
                    TextInput::make("app_url")
                        ->label("Application URL")
                        ->url()
                        ->required()
                        ->maxLength(255),
                ]),

            Section::make("Currency Settings")
                ->collapsible()
                ->schema([
                    Select::make("default_currency")
                        ->label("Default Currency")
                        ->options([
                            "USD" => "US Dollar (USD)",
                            "NGN" => "Nigerian Naira (NGN)",
                            // Add more currencies as needed
                        ])
                        ->required(),
                    TextInput::make("default_rate")
                        ->label("Default Exchange Rate")
                        ->numeric()
                        ->minValue(0)
                        ->required(),
                    TextInput::make("default_symbol")
                        ->label("Currency Symbol")
                        ->required()
                        ->maxLength(10),
                ]),

            Section::make("Point Conversion Settings")
                ->collapsible()
                ->schema([
                    TextInput::make("point_conversion_rate")
                        ->label("Point Conversion Rate (Default)")
                        ->numeric()
                        ->minValue(0)
                        ->required(),
                    TextInput::make("point_conversion_rate_ngn")
                        ->label("Point Conversion Rate (NGN)")
                        ->numeric()
                        ->minValue(0)
                        ->required(),
                ]),

            Section::make("User Welcome Settings")
                ->collapsible()
                ->schema([
                    Textarea::make("welcome_message_title")
                        ->label("Welcome Message Title")
                        ->placeholder("Welcome to our platform!")
                        ->maxLength(255)
                        ->rows(2),

                    Textarea::make("welcome_message_content")
                        ->label("Welcome Message Content")
                        ->placeholder(
                            'Thank you for joining us! Here\'s how to get started...',
                        )
                        ->maxLength(1000)
                        ->rows(5),

                    Toggle::make("welcome_message_enabled")
                        ->label("Enable Welcome Messages")
                        ->default(true)
                        ->helperText(
                            "Send welcome messages to newly registered users",
                        ),

                    Select::make("welcome_message_delay")
                        ->label("Message Delay")
                        ->options([
                            "0" => "Immediately",
                            "300" => "5 minutes",
                            "900" => "15 minutes",
                            "1800" => "30 minutes",
                            "3600" => "1 hour",
                        ])
                        ->default("300")
                        ->helperText(
                            "How long to wait before sending the welcome message",
                        ),
                ]),

            Section::make("Financial Settings")
                ->collapsible()
                ->schema([
                    Fieldset::make("Withdrawal Settings")->schema([
                        TextInput::make("min_withdrawal_amount")
                            ->label("Minimum Withdrawal (Default)")
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                        TextInput::make("min_withdrawal_amount_ngn")
                            ->label("Minimum Withdrawal (NGN)")
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                    ]),
                    Fieldset::make("Deposit Settings")->schema([
                        TextInput::make("min_deposit_amount")
                            ->label("Minimum Deposit (Default)")
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                        TextInput::make("min_deposit_amount_ngn")
                            ->label("Minimum Deposit (NGN)")
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                    ]),
                ]),

            Section::make("Financial Settings Fee")
                ->collapsible()
                ->schema([
                    Fieldset::make("Deposit Settings Fee")->schema([
                        TextInput::make("platform_deposit_fee")
                            ->label("Minimum Withdrawal (Default)")
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                    ]),
                    Fieldset::make("Withdrawal Settings Fee")->schema([
                        TextInput::make("platform_withdrawal_fee")
                            ->label("Minimum Deposit (Default)")
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                    ]),
                ]),

            Section::make("Theme Settings")
                ->collapsible()
                ->schema([
                    Select::make("default_mode")
                        ->label("Default Theme Mode")
                        ->options([
                            "light" => "Light",
                            "dark" => "Dark",
                            "system" => "System",
                        ])
                        ->required(),
                    ColorPicker::make("primary_color")
                        ->label("Primary Color")
                        ->required(),
                    ColorPicker::make("secondary_color")
                        ->label("Secondary Color")
                        ->required(),
                    ColorPicker::make("accent_color")
                        ->label("Accent Color")
                        ->required(),
                ]),

            Section::make("Pagination Limits")
                ->collapsible()
                ->schema([
                    TextInput::make("home_feed_limit")
                        ->label("Home Feed Limit")
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                    TextInput::make("personal_profile_limit")
                        ->label("Personal Profile Limit")
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                    TextInput::make("personal_media_limit")
                        ->label("Personal Media Limit")
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                    TextInput::make("personal_repost_limit")
                        ->label("Personal Repost Limit")
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                    TextInput::make("post_page_comment_limit")
                        ->label("Post Comment Limit")
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                    TextInput::make("post_page_comment_reply_limit")
                        ->label("Comment Reply Limit")
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                    TextInput::make("other_user_profile_limit")
                        ->label("Other User Profile Limit")
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                    TextInput::make("other_user_media_limit")
                        ->label("Other User Media Limit")
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                    TextInput::make("other_user_repost_limit")
                        ->label("Other User Repost Limit")
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                    TextInput::make("notification_limit")
                        ->label("Notification Limit")
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                    TextInput::make("transaction_limit")
                        ->label("Transaction Limit")
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                ]),

            Section::make("Search & Messaging")
                ->collapsible()
                ->schema([
                    Fieldset::make("Search Settings")->schema([
                        TextInput::make("model_search_limit")
                            ->label("Model Search Limit")
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                    ]),
                    Fieldset::make("Messaging Settings")->schema([
                        TextInput::make("conversation_limit")
                            ->label("Conversation Limit")
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                        TextInput::make("message_limit")
                            ->label("Message Limit")
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                    ]),
                ]),

            Section::make("Group Settings")
                ->collapsible()
                ->schema([
                    TextInput::make("group_message_limit")
                        ->label("Group Message Limit")
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                    TextInput::make("group_participant_limit")
                        ->label("Group Participant Limit")
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                    TextInput::make("group_limit")
                        ->label("Group Limit")
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                ]),

            Section::make("Feature Settings")
                ->collapsible()
                ->schema([
                    Fieldset::make("Hookup Settings")->schema([
                        Toggle::make("hookup_enabled")
                            ->label("Enable Hookup Feature")
                            ->required(),
                        TextInput::make("hookup_page_limit")
                            ->label("Hookup Page Limit")
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                    ]),
                    Fieldset::make("Status Settings")->schema([
                        TextInput::make("status_limit")
                            ->label("Status Limit")
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                    ]),
                ]),

            Section::make("User & Subscription Limits")
                ->collapsible()
                ->schema([
                    TextInput::make("subscription_limit")
                        ->label("Subscription Limit")
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                    TextInput::make("subscribers_limit")
                        ->label("Subscribers Limit")
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                    TextInput::make("active_subscribers_limit")
                        ->label("Active Subscribers Limit")
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                    TextInput::make("followers_limit")
                        ->label("Followers Limit")
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                    TextInput::make("upload_media_limit")
                        ->label("User Upload Media Limit")
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                    TextInput::make("model_upload_media_limit")
                        ->label("Model Upload Media Limit")
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                ]),

            Section::make("Success & Error Messages")
                ->collapsible()
                ->schema([
                    Textarea::make("profile_updated_success_message")
                        ->label("Profile Updated Success")
                        ->rows(3)
                        ->required(),
                    Textarea::make("profile_updated_error_message")
                        ->label("Profile Updated Error")
                        ->rows(3)
                        ->required(),
                    Textarea::make("profile_updating_message")
                        ->label("Profile Updating")
                        ->rows(3)
                        ->required(),
                    Textarea::make("profile_image_updated_success_message")
                        ->label("Profile Image Updated Success")
                        ->rows(3)
                        ->required(),
                    Textarea::make("profile_image_updated_error_message")
                        ->label("Profile Image Updated Error")
                        ->rows(3)
                        ->required(),
                    Textarea::make("profile_image_updating_message")
                        ->label("Profile Image Updating")
                        ->rows(3)
                        ->required(),
                    Textarea::make("point_purchase_success_message")
                        ->label("Point Purchase Success")
                        ->rows(3)
                        ->required(),
                    Textarea::make("point_purchase_error_message")
                        ->label("Point Purchase Error")
                        ->rows(3)
                        ->required(),
                    Textarea::make("point_purchasing_message")
                        ->label("Point Purchasing")
                        ->rows(3)
                        ->required(),
                    Textarea::make("point_purchase_minimum_message")
                        ->label("Point Purchase Minimum")
                        ->rows(3)
                        ->required(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("id")
                    ->label("ID")
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make("app_name")
                    ->label("Application Name")
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make("app_version")
                    ->label("Version")
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make("created_at")
                    ->label("Created At")
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make("updated_at")
                    ->label("Updated At")
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
                //
            ];
    }

    public static function getPages(): array
    {
        return [
            "index" => ListConfigurations::route("/"),
            "create" => CreateConfiguration::route("/create"),
            "edit" => EditConfiguration::route("/{record}/edit"),
        ];
    }
}
