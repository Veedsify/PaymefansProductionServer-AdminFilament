<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlatformExchangeRateResource\Pages;
use App\Models\PlatformExchangeRate;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PlatformExchangeRateResource extends Resource
{
    protected static ?string $model           = PlatformExchangeRate::class;
    protected static ?string $navigationGroup = 'Points';
    protected static ?string $navigationLabel = 'Exchange Rates';
    protected static ?string $navigationIcon  = 'heroicon-s-currency-dollar';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Platform Exchange Rate Information')
                    ->description('Add some information about the platform exchange rate.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Currency')
                            ->required(),
                        Forms\Components\TextInput::make('symbol')
                            ->label('Symbol')
                            ->required(),
                        Forms\Components\TextInput::make('rate')
                            ->label('Rate ($)')
                            ->required(),
                        Forms\Components\TextInput::make('buyValue')
                            ->label('Buy Value')
                            ->required(),
                        Forms\Components\TextInput::make('sellValue')
                            ->label('Sell Value')
                            ->required(),
                    ]),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Currency'),
                Tables\Columns\TextColumn::make('symbol')
                    ->searchable()
                    ->sortable()
                    ->label('Symbol'),
                Tables\Columns\TextColumn::make('rate')
                    ->searchable()
                    ->sortable()
                    ->label('Rate ($)'),
                Tables\Columns\TextColumn::make('buyValue')
                    ->searchable()
                    ->sortable()
                    ->label('Buy Value'),
                Tables\Columns\TextColumn::make('sellValue')
                    ->searchable()
                    ->sortable()
                    ->label('Sell Value'),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index'  => Pages\ListPlatformExchangeRates::route('/'),
            'create' => Pages\CreatePlatformExchangeRate::route('/create'),
            'edit'   => Pages\EditPlatformExchangeRate::route('/{record}/edit'),
        ];
    }
}
