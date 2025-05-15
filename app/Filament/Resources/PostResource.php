<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Filament\Resources\PostResource\RelationManagers\MediasRelationManager;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-s-photo';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationLabel = 'Posts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Post')
                    ->schema([
                        Forms\Components\TextInput::make('post_id')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('user_id')
                            ->native(false)
                            ->searchable()
                            ->preload()
                            ->relationship('user', 'username')
                            ->required(),
                        Forms\Components\Textarea::make('content')
                            ->required()
                            ->maxLength(65535),
                        Forms\Components\Select::make('post_status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->native(false)
                            ->required(),
                        Forms\Components\Select::make('post_audience')
                            ->options([
                                'public' => 'Public',
                                'price' => 'Price',
                                'subscribers' => 'Subscribers',
                            ])
                            ->native(false)
                            ->required(),
                        Forms\Components\Toggle::make('post_is_visible')
                            ->required(),
                    ]),
                Section::make('Post Statistics')
                    ->schema([
                        Forms\Components\TextInput::make('post_likes')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('post_comments')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('post_reposts')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('post_impressions')
                            ->required()
                            ->maxLength(255),
                    ]),
                Section::make('Post Media')
                    ->schema([
                        Forms\Components\Repeater::make('user_media')
                            ->relationship('user_media')
                            ->reorderableWithButtons()
                            ->collapsed()
                            ->grid(2)
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
                                Forms\Components\Select::make('media_state')
                                    ->options([
                                        'processing' => 'Processing',
                                        'completed' => 'Completed',
                                        'failed' => 'Failed',
                                    ])
                                    ->native(false)
                                    ->required(),
                            ])
                            ->columns(2)
                            ->addActionLabel('Add Media'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\ImageColumn::make('user_media')
                    ->label('Media')
                    ->circular()
                    ->size(40)
                    ->getStateUsing(function (Post $record) {
                        return $record->user_media[0]->url ?? null;
                    }),
                Tables\Columns\TextColumn::make('content')
                    ->html()
                    ->limit(50)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.username')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('post_impressions')
                    ->sortable()
                    ->label("Views")
                    ->searchable(),
                Tables\Columns\TextColumn::make('post_likes')
                    ->sortable()
                    ->label("Likes")
                    ->searchable(),
                Tables\Columns\TextColumn::make('post_comments')
                    ->sortable()
                    ->label("Comments")
                    ->searchable(),
                Tables\Columns\TextColumn::make('post_reposts')
                    ->sortable()
                    ->label("Reposts")
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
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
            MediasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
