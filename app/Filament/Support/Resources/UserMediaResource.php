<?php

namespace App\Filament\Support\Resources;

use App\Filament\Support\Resources\UserMediaResource\Pages;
use App\Filament\Resources\UserMediaResource\RelationManagers;
use App\Models\UserMedia;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserMediaResource extends Resource
{
    protected static ?string $model = UserMedia::class;

    protected static ?string $navigationIcon = 'heroicon-s-video-camera';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationLabel = 'User Media';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('User Media')
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
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->groups(['post_id', 'post.content', 'post.user.name', 'username' => 'post.user.username'])
            ->columns([
                Tables\Columns\TextColumn::make('post.user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('post.user.username')
                    ->label('Username')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->searchable(),
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
            'index' => Pages\ListUserMedia::route('/'),
            'create' => Pages\CreateUserMedia::route('/create'),
            'edit' => Pages\EditUserMedia::route('/{record}/edit'),
        ];
    }
}
