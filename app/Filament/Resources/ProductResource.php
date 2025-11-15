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
use Illuminate\Support\Facades\Auth;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = "hugeicons-shirt-01";
    protected static ?string $navigationGroup = "Products";
    protected static ?int $navigationSort = -2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make("Product Information")
                ->description("Enter the product details")
                ->schema([
                    Forms\Components\TextInput::make("user_id")
                        ->label("User ID")
                        ->readOnly()
                        ->default(Auth::user()->id),
                    Forms\Components\TextInput::make("name")
                        ->label("Name")
                        ->required(),
                    Forms\Components\Textarea::make("description")
                        ->label("Description")
                        ->required(),
                    Forms\Components\TextInput::make("price")
                        ->label("Price ₦")
                        ->required(),
                    Forms\Components\TextInput::make("instock")
                        ->label("In Stock")
                        ->required(),
                    Forms\Components\Select::make("Sizes")
                        ->relationship("sizes", "name")
                        ->label("Available Sizes")
                        ->preload()
                        // ->options(ProductSize::all()->pluck('name', 'id')->toArray())
                        ->multiple()
                        ->required(),
                    Forms\Components\TextInput::make("product_id")
                        ->label("Product ID")
                        ->default(uniqid())
                        ->required(),
                    Forms\Components\Select::make("category_id")
                        ->options(
                            fn() => ProductCategory::all()->pluck("name", "id"),
                        )
                        ->native(false)
                        ->label("Category")
                        ->required(),
                    Forms\Components\Repeater::make("product_images")
                        ->relationship("product_images")
                        ->grid(4)
                        ->schema([
                            Forms\Components\FileUpload::make("image_url")
                                ->image()
                                ->label("Image URL")
                                ->directory("store/products")
                                ->disk("s3")
                        ->visibility("public")
                                ->required(),
                        ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort("id", "desc")
            ->columns([
                Tables\Columns\ImageColumn::make("product_images")
                    ->getStateUsing(
                        fn($record) => env("AWS_CLOUDFRONT_URL") .
                            "/" .
                            $record?->product_images?->first()?->image_url ??
                            null,
                    )
                    ->label("Image"),
                Tables\Columns\TextColumn::make("name")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("description")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("price")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("product_category.name")
                    ->label("Category")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("instock")
                    ->searchable()
                    ->sortable(),
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
            "index" => Pages\ListProducts::route("/"),
            "create" => Pages\CreateProduct::route("/create"),
            "edit" => Pages\EditProduct::route("/{record}/edit"),
        ];
    }
}
