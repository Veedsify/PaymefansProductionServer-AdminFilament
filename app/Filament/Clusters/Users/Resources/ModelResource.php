<?php

namespace App\Filament\Clusters\Users\Resources;

use App\Filament\Clusters\Users;
use App\Filament\Clusters\Users\Resources\ModelResource\Pages;
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

    protected static ?string $cluster = Users::class;

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
                        FileUpload::make('verification_video')
                            ->disk('public')
                            ->directory('verification/videos'),
                        FileUpload::make('verification_image')
                            ->disk('public')
                            ->directory('verification/images'),
                        Select::make('verification_status')
                            ->options([
                                'pending' => 'Pending',
                                'verified' => 'Verified',
                                'rejected' => 'Rejected',
                            ]),
                        TextInput::make('verification_state'),
                        TextInput::make('token')
                            ->disabled()
                            ->dehydrated(false)
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
