<?php

namespace App\Filament\Resources\GroupsResource\RelationManagers;

use App\Models\GroupMember;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('userId')
                    ->label('User')
                    ->relationship('user', 'username')
                    ->required()
                    ->searchable(),

                Forms\Components\Select::make('role')
                    ->label('Role')
                    ->options([
                        'MEMBER' => 'Member',
                        'MODERATOR' => 'Moderator',
                        'ADMIN' => 'Admin',
                    ])
                    ->default('MEMBER')
                    ->required(),

                Forms\Components\Toggle::make('isMuted')
                    ->label('Is Muted')
                    ->default(false),

                Forms\Components\DateTimePicker::make('mutedUntil')
                    ->label('Muted Until')
                    ->nullable()
                    ->visible(fn (Forms\Get $get) => $get('isMuted')),

                Forms\Components\Toggle::make('isBlocked')
                    ->label('Is Blocked')
                    ->default(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user.username')
            ->columns([
                Tables\Columns\ImageColumn::make('user.avatar')
                    ->label('Avatar')
                    ->circular()
                    ->defaultImageUrl('/images/default-avatar.png'),

                Tables\Columns\TextColumn::make('user.username')
                    ->label('Username')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ADMIN' => 'danger',
                        'MODERATOR' => 'warning',
                        'MEMBER' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\IconColumn::make('isMuted')
                    ->label('Muted')
                    ->boolean(),

                Tables\Columns\TextColumn::make('mutedUntil')
                    ->label('Muted Until')
                    ->dateTime()
                    ->placeholder('Not muted'),

                Tables\Columns\IconColumn::make('isBlocked')
                    ->label('Blocked')
                    ->boolean()
                    ->trueColor('danger')
                    ->falseColor('success'),

                Tables\Columns\TextColumn::make('joinedAt')
                    ->label('Joined At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'MEMBER' => 'Member',
                        'MODERATOR' => 'Moderator',
                        'ADMIN' => 'Admin',
                    ]),

                Tables\Filters\TernaryFilter::make('isMuted')
                    ->label('Muted Status'),

                Tables\Filters\TernaryFilter::make('isBlocked')
                    ->label('Blocked Status'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Member'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('block')
                    ->label('Block')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->visible(fn (GroupMember $record): bool => !$record->isBlocked)
                    ->requiresConfirmation()
                    ->modalHeading('Block Member')
                    ->modalDescription('Are you sure you want to block this member? They will not be able to participate in the group.')
                    ->action(function (GroupMember $record): void {
                        $record->update(['isBlocked' => true]);

                        Notification::make()
                            ->title('Member blocked successfully')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('unblock')
                    ->label('Unblock')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (GroupMember $record): bool => $record->isBlocked)
                    ->requiresConfirmation()
                    ->modalHeading('Unblock Member')
                    ->modalDescription('Are you sure you want to unblock this member? They will be able to participate in the group again.')
                    ->action(function (GroupMember $record): void {
                        $record->update(['isBlocked' => false]);

                        Notification::make()
                            ->title('Member unblocked successfully')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('mute')
                    ->label('Mute')
                    ->icon('heroicon-o-speaker-x-mark')
                    ->color('warning')
                    ->visible(fn (GroupMember $record): bool => !$record->isMuted)
                    ->form([
                        Forms\Components\DateTimePicker::make('mutedUntil')
                            ->label('Mute Until')
                            ->required()
                            ->minDate(now())
                            ->default(now()->addHours(24)),
                    ])
                    ->action(function (GroupMember $record, array $data): void {
                        $record->update([
                            'isMuted' => true,
                            'mutedUntil' => $data['mutedUntil'],
                        ]);

                        Notification::make()
                            ->title('Member muted successfully')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('unmute')
                    ->label('Unmute')
                    ->icon('heroicon-o-speaker-wave')
                    ->color('success')
                    ->visible(fn (GroupMember $record): bool => $record->isMuted)
                    ->requiresConfirmation()
                    ->action(function (GroupMember $record): void {
                        $record->update([
                            'isMuted' => false,
                            'mutedUntil' => null,
                        ]);

                        Notification::make()
                            ->title('Member unmuted successfully')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\DeleteAction::make()
                    ->label('Remove')
                    ->modalHeading('Remove Member')
                    ->modalDescription('Are you sure you want to remove this member from the group?'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('block_selected')
                        ->label('Block Selected')
                        ->icon('heroicon-o-no-symbol')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records): void {
                            $records->each->update(['isBlocked' => true]);

                            Notification::make()
                                ->title('Selected members blocked successfully')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\BulkAction::make('unblock_selected')
                        ->label('Unblock Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records): void {
                            $records->each->update(['isBlocked' => false]);

                            Notification::make()
                                ->title('Selected members unblocked successfully')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Remove Selected'),
                ]),
            ]);
    }
}
