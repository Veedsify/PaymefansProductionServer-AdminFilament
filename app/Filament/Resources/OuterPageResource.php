<?php
namespace App\Filament\Resources;

use App\Filament\Resources\OuterPageResource\Pages;
use App\Models\OuterPage;
use Filament\Forms\Components;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class OuterPageResource extends Resource
{
    protected static ?string $model           = OuterPage::class;
    protected static ?string $navigationGroup = 'Pages';
    protected static ?string $modelLabel  = 'Pages';
    protected static ?string $navigationLabel = 'Pages';
    protected static ?string $navigationIcon  = 'heroicon-s-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\Section::make('Page Details')
                    ->description('Fill in the details of the page.')
                    ->columns(2)
                    ->columnSpan(2)
                    ->schema([
                        Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->afterStateUpdated(function (callable $set, $state) {
                                $set('slug', Str::slug($state));
                            })
                            ->live(debounce: 500)
                            ->label('Page Title'),
                        Components\TextInput::make('page_id')
                            ->required()
                            ->maxLength(255)
                            ->readOnly()
                            ->default(fn() => (string) Str::uuid())
                            ->label('Page ID'),
                        Components\TextInput::make('slug')
                            ->required()
                            ->label('Slug')
                            ->reactive(),
                        Components\RichEditor::make('content')
                            ->columnSpanFull()
                            ->required()
                            ->maxLength(65535)
                            ->label('Content'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('page_id')
                    ->label('Page ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->action(function (OuterPage $record) {
                        if (in_array($record->slug, ["terms-and-conditions", "privacy-policy"])) {
                            Notification::make("error")
                                ->danger()
                                ->title("Error")
                                ->body("You cannot delete this page.")
                                ->send();
                            return;
                        }
                        $record->delete();
                    })
                    ->requiresConfirmation()
                    ->label('Delete'),
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
            'index'  => Pages\ListOuterPages::route('/'),
            'create' => Pages\CreateOuterPage::route('/create'),
            'edit'   => Pages\EditOuterPage::route('/{record}/edit'),
        ];
    }
}
