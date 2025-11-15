<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GroupsResource\Pages;
use App\Filament\Resources\GroupsResource\RelationManagers;
use App\Models\Group;
use App\Models\GroupMember;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GroupsResource extends Resource
{
    protected static ?string $model = Group::class;

    protected static ?string $navigationIcon = "ri-group-2-line";
    protected static ?string $navigationGroup = "Groups";

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make("Group Details")
                ->description("Manage group information")
                ->schema([
                    Forms\Components\TextInput::make("name")
                        ->label("Group Name")
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make("adminId")
                        ->label("Admin ID")
                        ->required()
                        ->numeric(),

                Forms\Components\FileUpload::make("groupIcon")
                        ->label("Group Icon")
                        ->directory("groups/icon")
                        ->disk("s3")
                    ->visibility("publico"),

                    Forms\Components\Toggle::make("isActive")
                        ->label("Active Status")
                        ->default(true),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make("groupIcon")
                    ->label("Group Icon")
                    ->sortable()
                    ->searchable(),
                TextColumn::make("id")->label("ID")->sortable()->searchable(),
                TextColumn::make("name")
                    ->label("Group Name")
                    ->sortable()
                    ->searchable(),
                TextColumn::make("admin")
                    ->label("Admin")
                    ->sortable()
                    ->searchable()
                    ->getStateUsing(
                        fn(Group $record) => $record->admin
                            ? $record->admin->name
                            : "N/A",
                    ),
                TextColumn::make("createdAt")
                    ->label("Created At")
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
                TextColumn::make("members_count")
                    ->label("Total Members")
                    ->counts("members")
                    ->sortable(),
                IconColumn::make("isActive")->label("Active Status")->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make("manage_members")
                    ->label("Manage Members")
                    ->icon("heroicon-o-users")
                    ->url(
                        fn(Group $record): string => route(
                            "filament.admin.resources.groups.members",
                            $record,
                        ),
                    )
                    ->openUrlInNewTab(false),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [RelationManagers\MembersRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListGroups::route("/"),
            "create" => Pages\CreateGroup::route("/create"),
            "edit" => Pages\EditGroups::route("/{record}/edit"),
            "members" => Pages\ManageGroupMembers::route("/{record}/members"),
        ];
    }
}
