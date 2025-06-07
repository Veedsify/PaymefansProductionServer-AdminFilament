<?php

namespace App\Filament\Support\Resources;

use App\Filament\Support\Resources\HelpArticleResource\Pages;
use App\Filament\Support\Resources\HelpArticleResource\RelationManagers;
use App\Models\HelpArticle;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HelpArticleResource extends Resource
{
    protected static ?string $model = HelpArticle::class;

    protected static ?string $navigationIcon = 'ri-question-answer-fill';
    protected static ?string $navigationGroup = 'Help & Support';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Help Article')
                    ->description('Help Article Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter the title of the article'),
                        Forms\Components\RichEditor::make('content')
                            ->label('Content')
                            ->required()
                            ->placeholder('Enter the content of the article'),
                        Forms\Components\Select::make('category_id')
                            ->label('Category')
                            ->required()
                            ->native(false)
                            ->searchable()
                            ->placeholder('Select a category')
                            ->preload()
                            ->options(
                                \App\Models\HelpCategory::all()->pluck('name', 'id')->toArray()
                            ),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('content')
                    ->searchable()
                    ->html()
                    ->words(10)
                    ->sortable(),
                Tables\Columns\TextColumn::make('category_id')
                    ->searchable()
                    ->getStateUsing(function ($record) {
                        return $record->help_category->name;
                    })
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
            'index' => Pages\ListHelpArticles::route('/'),
            'create' => Pages\CreateHelpArticle::route('/create'),
            'edit' => Pages\EditHelpArticle::route('/{record}/edit'),
        ];
    }
}
