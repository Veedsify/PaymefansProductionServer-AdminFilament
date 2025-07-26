<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Store';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Order Information')
                    ->schema([
                        Forms\Components\TextInput::make('order_id')
                            ->label('Order ID')
                            ->disabled(),
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Customer')
                            ->disabled(),
                        Forms\Components\TextInput::make('total_amount')
                            ->label('Total Amount (₦)')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required(),
                        Forms\Components\Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                            ])
                            ->disabled(),
                        Forms\Components\TextInput::make('payment_reference')
                            ->label('Payment Reference')
                            ->disabled(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Shipping Address')
                    ->schema([
                        Forms\Components\Textarea::make('shipping_address')
                            ->label('Address')
                            ->disabled()
                            ->formatStateUsing(function ($state) {
                                if (is_string($state)) {
                                    $address = json_decode($state, true);
                                    if (is_array($address)) {
                                        return implode("\n", [
                                            $address['name'] ?? '',
                                            $address['phone'] ?? '',
                                            $address['address'] ?? '',
                                            ($address['city'] ?? '') . ', ' . ($address['state'] ?? ''),
                                            $address['country'] ?? '',
                                        ]);
                                    }
                                }
                                return $state;
                            }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->label('Order ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('NGN')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'primary' => 'processing',
                        'info' => 'shipped',
                        'success' => 'delivered',
                        'danger' => 'cancelled',
                    ]),
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Payment')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'failed',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Order Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Payment Status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Order Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('order_id')
                            ->label('Order ID'),
                        Infolists\Components\TextEntry::make('user.name')
                            ->label('Customer'),
                        Infolists\Components\TextEntry::make('user.email')
                            ->label('Email'),
                        Infolists\Components\TextEntry::make('total_amount')
                            ->label('Total Amount')
                            ->money('NGN'),
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'pending' => 'warning',
                                'processing' => 'primary',
                                'shipped' => 'info',
                                'delivered' => 'success',
                                'cancelled' => 'danger',
                            }),
                        Infolists\Components\TextEntry::make('payment_status')
                            ->label('Payment Status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'pending' => 'warning',
                                'paid' => 'success',
                                'failed' => 'danger',
                            }),
                        Infolists\Components\TextEntry::make('payment_reference')
                            ->label('Payment Reference'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Order Date')
                            ->dateTime(),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Shipping Address')
                    ->schema([
                        Infolists\Components\TextEntry::make('shipping_address')
                            ->label('Address')
                            ->formatStateUsing(function ($state) {
                                if (is_string($state)) {
                                    $address = json_decode($state, true);
                                    if (is_array($address)) {
                                        return implode("\n", [
                                            $address['name'] ?? '',
                                            $address['phone'] ?? '',
                                            $address['address'] ?? '',
                                            ($address['city'] ?? '') . ', ' . ($address['state'] ?? ''),
                                            $address['country'] ?? '',
                                        ]);
                                    }
                                }
                                return $state;
                            })
                            ->html(),
                    ]),

                Infolists\Components\Section::make('Order Items')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('items')
                            ->schema([
                                Infolists\Components\TextEntry::make('product.name')
                                    ->label('Product'),
                                Infolists\Components\TextEntry::make('quantity')
                                    ->label('Quantity'),
                                Infolists\Components\TextEntry::make('price')
                                    ->label('Unit Price')
                                    ->money('NGN'),
                                Infolists\Components\TextEntry::make('size.name')
                                    ->label('Size')
                                    ->placeholder('N/A'),
                                Infolists\Components\TextEntry::make('total')
                                    ->label('Total')
                                    ->state(fn($record) => $record->price * $record->quantity)
                                    ->money('NGN'),
                            ])
                            ->columns(5),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }
}
