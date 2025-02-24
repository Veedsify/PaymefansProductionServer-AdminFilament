<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GroupResource\Pages;
use App\Filament\Resources\GroupResource\RelationManagers;
use App\Models\Group;
use App\Models\Groups;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GroupResource extends Resource
{
    protected static ?string $model = Groups::class;

    protected static ?string $navigationIcon = 'lucide-group';
    protected static ?string $navigationGroup = 'Groups';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make("Group Information")->schema([
                    TextInput::make('name')
                        ->label('Name')
                        ->placeholder('Enter the group name')
                        ->required()
                        ->autofocus(),
                    TextInput::make('group_id')
                        ->label('Group ID')
                        ->default('G_' . uniqid(12))
                        ->required()
                        ->autofocus(),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('No groups')
            ->emptyStateDescription('Groups will be shown here')
            ->columns([
                TextColumn::make("group_id")
                    ->label('Group ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make("group_setting.name")
                    ->label('Name')
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
            'index' => Pages\ListGroups::route('/'),
            'create' => Pages\CreateGroup::route('/create'),
            'edit' => Pages\EditGroup::route('/{record}/edit'),
            'new-group' => Pages\NewGroup::route('/new-group'),
        ];
    }
}
