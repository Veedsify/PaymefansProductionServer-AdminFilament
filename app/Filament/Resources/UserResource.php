<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\PlatformExchangeRate;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = "monoicon-users";
    protected static ?string $navigationGroup = "Users";

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make("Basic Information")
                ->description("Primary user account information")
                ->schema([
                    Forms\Components\TextInput::make("name")
                        ->afterStateUpdated(function ($state, Set $set) {
                            $set("fullname", $state);
                        })
                        ->label("Display Name")
                        ->required()
                        ->maxLength(191),
                    Forms\Components\TextInput::make("fullname")
                        ->label("Full Name")
                        ->required()
                        ->maxLength(191),
                    Forms\Components\TextInput::make("email")
                        ->email()
                        ->required()
                        ->maxLength(191)
                        ->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make("phone")
                        ->tel()
                        ->required()
                        ->maxLength(191)
                        ->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make("username")
                        ->required()
                        ->maxLength(191)
                        ->unique(ignoreRecord: true),
                ])
                ->columns(2),

            Forms\Components\Section::make("Profile Details")
                ->description("User profile and personal information")
                ->schema([
                    Forms\Components\Textarea::make("bio")->columnSpanFull(),
                ])
                ->columns(2),

            Forms\Components\Section::make("Location Information")
                ->schema([
                    Forms\Components\TextInput::make("country"),
                    Forms\Components\TextInput::make("state"),
                    Forms\Components\TextInput::make("city"),
                    Forms\Components\TextInput::make("zip"),
                    Forms\Components\TextInput::make("location"),
                    Forms\Components\TextInput::make("website"),
                ])
                ->columns(3),

            Forms\Components\Section::make("Account Status")->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Toggle::make("is_active")
                        ->label("Account Active")
                        ->default(true),
                    Forms\Components\Toggle::make("is_verified")->label(
                        "Account Verified"
                    ),
                    Forms\Components\Toggle::make("is_email_verified")->label(
                        "Email Verified"
                    ),
                    Forms\Components\Toggle::make("is_phone_verified")->label(
                        "Phone Verified"
                    ),
                    Forms\Components\Select::make("currency")
                        ->native(false)
                        ->required()
                        ->searchable()
                        ->options(
                            PlatformExchangeRate::all()->pluck("name", "name")
                        )
                        ->label("Currency"),
                ]),
            ]),

            Forms\Components\Section::make("Administrative Settings")->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Toggle::make("admin")
                        ->helperText(
                            "This is the admin status of the user on the platform off means the user wont be able to access admin features"
                        )
                        ->label("Admin User"),
                    Forms\Components\Toggle::make("active_status")
                        ->helperText(
                            "This is the status of the user on the platform off means the user is not able to login"
                        )
                        ->label("Active Status"),
                    Forms\Components\Toggle::make("is_model")
                        ->helperText(
                            "This is the model status of the user on the platform off means the user wont be able to access model features"
                        )
                        ->label("Content Creator (Model)"),
                    Forms\Components\Select::make("role")
                        ->native(false)
                        ->options([
                            "admin" => "Admin",
                            "fan" => "User",
                        ])
                        ->label("User Role"),
                ]),
            ]),

            Forms\Components\Section::make("Statistics")
                ->schema([
                    Forms\Components\TextInput::make("total_followers")
                        ->numeric()
                        ->default(0)
                        ->disabled(),
                    Forms\Components\TextInput::make("total_following")
                        ->numeric()
                        ->default(0)
                        ->disabled(),
                    Forms\Components\TextInput::make("total_subscribers")
                        ->numeric()
                        ->default(0)
                        ->disabled(),
                ])
                ->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll("10s")
            ->columns([
                Tables\Columns\ImageColumn::make("profile_image")
                    ->label("Profile Image")
                    ->circular()
                    ->size(50),
                Tables\Columns\TextColumn::make("name")->searchable(),
                Tables\Columns\TextColumn::make("email")->searchable(),
                Tables\Columns\TextColumn::make("username")->searchable(),
                Tables\Columns\IconColumn::make("active_status")->boolean(),
                Tables\Columns\IconColumn::make("is_active")->boolean(),
                Tables\Columns\IconColumn::make("is_verified")->boolean(),
                Tables\Columns\IconColumn::make("is_email_verified")->boolean(),
                Tables\Columns\ToggleColumn::make("is_model"),
                Tables\Columns\TextColumn::make("phone")->searchable(),
                Tables\Columns\TextColumn::make("created_at")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make("updated_at")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make("view")->url(function ($record) {
                    return Pages\UserDetail::getUrl(["record" => $record->id]);
                }),
                Tables\Actions\Action::make("permissions")
                    ->label("Manage Permissions")
                    ->icon("heroicon-o-shield-check")
                    ->color("warning")
                    ->url(function ($record) {
                        return Pages\UserPermissionsSimple::getUrl([
                            "record" => $record->id,
                        ]);
                    }),
                Tables\Actions\EditAction::make(),
            ])
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
            "index" => Pages\ListUsers::route("/"),
            "create" => Pages\CreateUser::route("/create"),
            "edit" => Pages\EditUser::route("/{record}/edit"),
            "view" => Pages\UserDetail::route("/{record}"),
            "actions" => Pages\ViewUserActions::route("/{record}/actions"),
            "suspend-user" => Pages\SuspendUser::route("/{record}/suspend"),
            "permissions" => Pages\UserPermissionsSimple::route(
                "/{record}/permissions"
            ),
        ];
    }
}
