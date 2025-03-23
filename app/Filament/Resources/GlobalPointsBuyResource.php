<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GlobalPointsBuyResource\Pages;
use App\Models\GlobalPointsBuy;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GlobalPointsBuyResource extends Resource
{
    protected static ?string $model = GlobalPointsBuy::class;

    protected static ?string $navigationIcon = 'fas-coins';
    protected static ?string $navigationGroup = 'Points';
    protected static ?string $modelLabel = 'Points';
    protected static ?string $name = 'Points';
    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Global Points Buy ')->schema([
                    TextInput::make('points_buy_id')
                        ->default('2' . Str::uuid())
                        ->label('Points Buy ID')
                        ->readOnly(),
                    TextInput::make('points')
                        ->label('Points')
                        ->required(),
                    TextInput::make('amount')
                        ->label('Amount')
                        ->required(),
                    TextInput::make('conversion_rate')
                        ->label('Conversion Rate')
                        ->required(),
                    TextInput::make('currency')
                        ->label('Currency')
                        ->required(),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('points', 'asc')
            ->defaultSortOptionLabel('Points Ascending')
            ->columns([
                Tables\Columns\TextColumn::make('points')
                    ->searchable()
                    ->sortable()
                    ->label('Points'),
                Tables\Columns\TextColumn::make('amount')
                    ->searchable()
                    ->sortable()
                    ->label('Amount'),
                Tables\Columns\TextColumn::make('conversion_rate')
                    ->searchable()
                    ->sortable()
                    ->label('Conversion Rate'),
                Tables\Columns\TextColumn::make('currency')
                    ->searchable()
                    ->sortable()
                    ->label('Currency'),
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
    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        return $record->points;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'points_buy_id',
            'points',
            'amount',
            'conversion_rate',
            'currency',
        ];
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
            'index' => Pages\ListGlobalPointsBuys::route('/'),
            'create' => Pages\CreateGlobalPointsBuy::route('/create'),
            'edit' => Pages\EditGlobalPointsBuy::route('/{record}/edit'),
        ];
    }
}
