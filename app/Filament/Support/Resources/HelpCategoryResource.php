<?php

namespace App\Filament\Support\Resources;

use App\Filament\Support\Resources\HelpCategoryResource\Pages;
use App\Filament\Support\Resources\HelpCategoryResource\RelationManagers;
use App\Models\HelpCategory;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HelpCategoryResource extends Resource
{
    protected static ?string $model = HelpCategory::class;
    protected static ?string $navigationIcon = 'heroicon-s-question-mark-circle';
    protected static ?string $navigationGroup = 'Help & Support';
    protected static ?int $navigationSort = 2;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Help Category')
                    ->description('Help Category Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter the name of the category'),
                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->maxLength(255)
                            ->placeholder('Enter the description of the category'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make("description")
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
            'index' => Pages\ListHelpCategories::route('/'),
            'create' => Pages\CreateHelpCategory::route('/create'),
            'edit' => Pages\EditHelpCategory::route('/{record}/edit'),
        ];
    }
}
