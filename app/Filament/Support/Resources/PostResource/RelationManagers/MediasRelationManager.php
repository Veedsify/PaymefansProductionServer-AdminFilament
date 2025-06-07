<?php

namespace App\Filament\Support\Resources\PostResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MediasRelationManager extends RelationManager
{
    protected static string $relationship = 'user_media';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('media_id')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('post_id')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('media_type')
                    ->options([
                        'image' => 'Image',
                        'video' => 'Video',
                        'audio' => 'Audio',
                    ])
                    ->native(false)
                    ->required(),
                Forms\Components\Select::make('media_state')
                    ->options([
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ])
                    ->native(false)
                    ->required(),
                Forms\Components\TextInput::make('duration')
                    ->maxLength(255),
                Forms\Components\TextInput::make('url')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('blur')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('poster')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('locked')
                    ->required(),
                Forms\Components\Select::make('accessible_to')
                    ->options([
                        'public' => 'Public',
                        'price' => 'Price',
                        'subscribers' => 'Subscribers',
                    ])
                    ->native(false)
                    ->required()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('media_id')
            ->columns([
                Tables\Columns\ImageColumn::make('url')
                    ->circular(),
                Tables\Columns\ImageColumn::make('blur')
                    ->circular(),
                Tables\Columns\TextColumn::make('media_id')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('post_id')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('media_type')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('media_state')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('duration')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('locked')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('accessible_to'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
