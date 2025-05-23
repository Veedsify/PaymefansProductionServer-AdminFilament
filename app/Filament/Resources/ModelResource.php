<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModelResource\Pages;
use App\Models\Model;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ModelResource extends Resource
{
    protected static ?string $model = Model::class;

    protected static ?string $navigationIcon = 'monoicon-user-check';
    protected static ?string $navigationGroup = 'Users';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Model Informations')->schema([
                    TextInput::make('firstname')->required(),
                    TextInput::make('lastname')->required(),
                    Select::make('user_id')
                        ->relationship('user', 'name')
                        ->required(),
                    Select::make('gender')
                        ->options([
                            'male' => 'Male',
                            'female' => 'Female',
                            'other' => 'Other',
                        ])
                        ->required(),
                    DatePicker::make('dob')->required(),
                    TextInput::make('country')->required(),
                    Toggle::make('hookup'),
                    TextInput::make('verification_video'),
                    TextInput::make('verification_image'),
                    Select::make('verification_state')
                        ->options([
                            'not_started' => 'Not Started',
                            'started' => 'Started',
                            'pending' => 'Pending',
                            'rejected' => 'Rejected',
                            'approved' => 'Approved',
                        ]),
                    Toggle::make('verification_status'),
                    TextInput::make('token')
                        ->label('Token')
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('firstname')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('lastname')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('gender')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('verification_status')->searchable()->sortable(),
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
            'index' => Pages\ListModels::route('/'),
            'create' => Pages\CreateModel::route('/create'),
            'edit' => Pages\EditModel::route('/{record}/edit'),
        ];
    }
}
