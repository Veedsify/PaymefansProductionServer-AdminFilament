<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductSizeResource\Pages;
use App\Filament\Resources\ProductSizeResource\RelationManagers;
use App\Models\ProductSize;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductSizeResource extends Resource
{
    protected static ?string $model = ProductSize::class;

    protected static ?string $navigationIcon = 'mdi-size-xs';
    protected static ?string $modelLabel = 'Product Sizes';
    protected static ?string $navigationGroup = 'Products';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Product Sizes')
                    ->description('Add a new product size.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->placeholder('Enter the name of the product size.')
                            ->required()
                            ->columnSpanFull()
                            ->unique()
                            ->rules(['required', 'string', 'max:255']),
                        Textarea::make('description')
                            ->label('Description')
                            ->placeholder('Enter the description of the product size.')
                            ->columnSpanFull()
                            ->unique()
                            ->rules(['string', 'max:500']),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->sortable(),
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
            'index' => Pages\ListProductSizes::route('/'),
            'create' => Pages\CreateProductSize::route('/create'),
            'edit' => Pages\EditProductSize::route('/{record}/edit'),
        ];
    }
}
