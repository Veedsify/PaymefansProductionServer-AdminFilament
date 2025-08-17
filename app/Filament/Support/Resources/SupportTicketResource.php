<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupportTicketResource\Pages;
use App\Models\SupportTicket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class SupportTicketResource extends Resource
{
    protected static ?string $model = SupportTicket::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Support';

    protected static ?string $navigationLabel = 'Support Tickets';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Ticket Information')
                    ->schema([
                        Forms\Components\TextInput::make('ticket_id')
                            ->label('Ticket ID')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Select::make('user_id')
                            ->label('User')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('subject')
                            ->label('Subject')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('message')
                            ->label('Message')
                            ->required()
                            ->rows(4),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'open' => 'Open',
                                'pending' => 'Pending',
                                'closed' => 'Closed',
                            ])
                            ->required()
                            ->default('open'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ticket_id')
                    ->label('Ticket ID')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('subject')
                    ->label('Subject')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'open',
                        'warning' => 'pending',
                        'danger' => 'closed',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('supportTicketReplies_count')
                    ->label('Replies')
                    ->counts('supportTicketReplies')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->since(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'open' => 'Open',
                        'pending' => 'Pending',
                        'closed' => 'Closed',
                    ]),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Created from'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Created until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('reply')
                    ->icon('heroicon-o-chat-bubble-left')
                    ->url(fn(SupportTicket $record): string => route('filament.admin.resources.support-tickets.reply', $record)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('mark_as_closed')
                        ->label('Mark as Closed')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['status' => 'closed']);
                            });
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Ticket Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('ticket_id')
                            ->label('Ticket ID')
                            ->copyable(),

                        Infolists\Components\TextEntry::make('user.name')
                            ->label('User'),

                        Infolists\Components\TextEntry::make('user.email')
                            ->label('Email')
                            ->copyable(),

                        Infolists\Components\TextEntry::make('subject')
                            ->label('Subject'),

                        Infolists\Components\TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'open' => 'success',
                                'pending' => 'warning',
                                'closed' => 'danger',
                                default => 'gray',
                            }),

                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Created')
                            ->dateTime(),

                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Updated')
                            ->dateTime(),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Message')
                    ->schema([
                        Infolists\Components\TextEntry::make('message')
                            ->label('')
                            ->prose(),
                    ]),

                Infolists\Components\Section::make('Replies')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('supportTicketReplies')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('user.name')
                                    ->label('From'),
                                Infolists\Components\TextEntry::make('message')
                                    ->label('Message')
                                    ->prose(),
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Date')
                                    ->dateTime(),
                            ])
                            ->columns(3)
                    ])
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
            'index' => Pages\ListSupportTickets::route('/'),
            'create' => Pages\CreateSupportTicket::route('/create'),
            'view' => Pages\ViewSupportTicket::route('/{record}'),
            'edit' => Pages\EditSupportTicket::route('/{record}/edit'),
            'reply' => Pages\ReplySupportTicket::route('/{record}/reply'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'open')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $openTickets = static::getModel()::where('status', 'open')->count();

        if ($openTickets > 10) {
            return 'danger';
        } elseif ($openTickets > 5) {
            return 'warning';
        }

        return 'success';
    }
}
