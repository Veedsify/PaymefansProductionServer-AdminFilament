<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserRepostResource\Pages;
use App\Filament\Resources\UserRepostResource\RelationManagers;
use App\Models\UserRepost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserRepostResource extends Resource
{
    protected static ?string $model = UserRepost::class;

    protected static ?string $navigationIcon = 'heroicon-s-arrow-path';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationLabel = 'User Reposts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\TextInput::make('user_id')
                            ->required(),
                        Forms\Components\TextInput::make('repost_id')
                            ->required(),
                        Forms\Components\TextInput::make('created_at')
                            ->required(),
                        Forms\Components\TextInput::make('updated_at')
                            ->required(),
                    ]),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading("User Reposts")
            ->description("List of all user reposts")
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('post.user.name')
                    ->sortable()
                    ->label('User Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.username')
                    ->label('Reposed By')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('post.content')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\ImageColumn::make('post.user_media')
                    ->getStateUsing(function (UserRepost $record) {
                        return $record->post->user_media[0]->url ?? null;
                    })
                    ->label('User Media'),
                Tables\Columns\TextColumn::make('repost_id')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime()
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->sortable()
                    ->dateTime()
                    ->searchable(),
            ])->filters([
                //
            ])->headerActions([
                Tables\Actions\CreateAction::make(),
            ])->actions([
                //
            ])->bulkActions([
                //
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
            'index' => Pages\ListUserReposts::route('/'),
            'create' => Pages\CreateUserRepost::route('/create'),
            'edit' => Pages\EditUserRepost::route('/{record}/edit'),
        ];
    }
}
