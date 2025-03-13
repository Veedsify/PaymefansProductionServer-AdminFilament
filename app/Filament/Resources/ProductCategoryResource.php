<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductCategoryResource\Pages;
use App\Filament\Resources\ProductCategoryResource\RelationManagers;
use App\Models\ProductCategory;
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

class ProductCategoryResource extends Resource
{
    protected static ?string $model = ProductCategory::class;

    protected static ?string $navigationIcon = "tabler-category-filled";
    protected static ?string $modelLabel = "Product Category";
    protected static ?string $navigationGroup = "Products";

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make("Product Category Information")
                ->description(
                    "Add some information about the product category."
                )
                ->schema([
                    TextInput::make("name")
                        ->columnSpanFull()
                        ->label("Name")
                        ->required(),
                    Textarea::make("description")
                        ->columnSpanFull()
                        ->label("Description"),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("name")
                    ->searchable()
                    ->sortable()
                    ->label("Name"),
                Tables\Columns\TextColumn::make("description")
                    ->searchable()
                    ->sortable()
                    ->label("Description"),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            "index" => Pages\ListProductCategories::route("/"),
            "create" => Pages\CreateProductCategory::route("/create"),
            "edit" => Pages\EditProductCategory::route("/{record}/edit"),
        ];
    }
}
