<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductSize;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'hugeicons-shirt-01';
    protected static ?string $navigationGroup = 'Products';
    protected static ?int $navigationSort = -2;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Product Information')
                    ->description('Enter the product details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->required(),
                        Forms\Components\TextInput::make('price')
                            ->label('Price')
                            ->required(),
                        Forms\Components\TextInput::make('instock')
                            ->label('In Stock')
                            ->required(),
                        Forms\Components\Select::make('category_id')
                            ->options(function () {
                                return ProductCategory::all()->pluck('name', 'id');
                            })
                            ->label('Category')
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product_category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('instock')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\Product::route('/create'),
            // 'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
