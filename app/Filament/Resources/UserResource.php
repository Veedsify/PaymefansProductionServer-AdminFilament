<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Users;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $cluster = Users::class;

    protected static ?string $navigationIcon = 'monoicon-users';
    // protected static ?string $navigationGroup = 'Users';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->description('Primary user account information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->afterStateUpdated(function($state, Set $set){
                                $set('fullname', $state);
                            })
                            ->label('Display Name')
                            ->required()
                            ->maxLength(191),
                        Forms\Components\TextInput::make('fullname')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(191),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(191)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create')
                            ->maxLength(191),
                        Forms\Components\TextInput::make('username')
                            ->required()
                            ->maxLength(191)
                            ->unique(ignoreRecord: true),
                    ])->columns(2),

                Forms\Components\Section::make('Profile Details')
                    ->description('User profile and personal information')
                    ->schema([
                        Forms\Components\FileUpload::make('profile_image')
                            ->avatar()
                            ->directory('profile-images'),
                        Forms\Components\FileUpload::make('profile_banner')
                            ->columnSpanFull()
                            ->default('/site/banner.png'),
                        Forms\Components\Textarea::make('bio')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Location Information')
                    ->schema([
                        Forms\Components\TextInput::make('country'),
                        Forms\Components\TextInput::make('state'),
                        Forms\Components\TextInput::make('city'),
                        Forms\Components\TextInput::make('zip'),
                        Forms\Components\TextInput::make('location'),
                        Forms\Components\TextInput::make('website'),
                    ])->columns(3),

                Forms\Components\Section::make('Account Status')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Toggle::make('is_active')
                                ->label('Account Active')
                                ->default(true),
                            Forms\Components\Toggle::make('is_verified')
                                ->label('Account Verified'),
                            Forms\Components\Toggle::make('is_email_verified')
                                ->label('Email Verified'),
                            Forms\Components\Toggle::make('is_phone_verified')
                                ->label('Phone Verified'),
                        ]),
                    ]),

                Forms\Components\Section::make('Administrative Settings')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Toggle::make('admin')
                                ->label('Admin User'),
                            Forms\Components\Toggle::make('admin_status')
                                ->label('Admin Status'),
                            Forms\Components\Toggle::make('is_model')
                                ->label('Content Creator (Model)'),
                            Forms\Components\Select::make('role')
                                ->native(false)
                                ->options([
                                    'admin' => "Admin",
                                    'fan' => "User",
                                ])
                                ->label('User Role'),
                        ]),
                    ]),

                Forms\Components\Section::make('Statistics')
                    ->schema([
                        Forms\Components\TextInput::make('total_followers')
                            ->numeric()
                            ->default(0)
                            ->disabled(),
                        Forms\Components\TextInput::make('total_following')
                            ->numeric()
                            ->default(0)
                            ->disabled(),
                        Forms\Components\TextInput::make('total_subscribers')
                            ->numeric()
                            ->default(0)
                            ->disabled(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fullname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('username')
                    ->searchable(),
                Tables\Columns\IconColumn::make('admin')
                    ->boolean(),
                Tables\Columns\TextColumn::make('role'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_verified')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_email_verified')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_model')
                    ->boolean(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('profile_image'),
                Tables\Columns\TextColumn::make('total_followers')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_following')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_subscribers')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('admin_status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}