<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BatchProcessLogResource\Pages;
use App\Models\BatchProcessLog;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BatchProcessLogResource extends Resource
{
    protected static ?string $model           = BatchProcessLog::class;
    protected static ?string $navigationGroup = 'System';
    protected static ?string $navigationIcon  = 'heroicon-s-queue-list';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('id'),
                Infolists\Components\TextEntry::make('job_id'),
                Infolists\Components\TextEntry::make('job_name'),
                Infolists\Components\TextEntry::make('job_data')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('job_id')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('job_name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('job_data')
                    ->words(3)
                    ->sortable()
                    ->searchable(),
            ])->filters([
                //
            ])->headerActions([
                //
            ])->actions([
                //
            ])->bulkActions([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListBatchProcessLogs::route('/'),
            // 'create' => Pages\CreateBatchProcessLog::route('/create'),
            // 'edit' => Pages\EditBatchProcessLog::route('/{record}/edit'),
        ];
    }
}
