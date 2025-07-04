<?php

namespace App\Filament\Widgets;

use App\Models\UserTransaction;
use App\Models\WithdrawalRequest;
use App\Models\UserPointsPurchase;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class RecentTransactionsWidget extends BaseWidget
{
    protected static ?string $heading = "Recent Transactions Activity";
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->defaultPaginationPageOption(10)
            ->columns([
                Tables\Columns\TextColumn::make("transaction_type")
                    ->label("Type")
                    ->badge()
                    ->color(
                        fn(?string $state): string => match ($state) {
                            "credit" => "success",
                            "debit" => "warning",
                            default => "gray",
                        }
                    )
                    ->icon("heroicon-m-banknotes"),

                Tables\Columns\TextColumn::make("user.name")
                    ->label("User")
                    ->default("Unknown User")
                    ->searchable()
                    ->sortable()
                    ->limit(20),

                Tables\Columns\TextColumn::make("amount")
                    ->label("Amount")
                    ->money("NGN")
                    ->sortable()
                    ->color(
                        fn($record): string => $record->type === "Withdrawal"
                            ? "danger"
                            : "success"
                    ),

                Tables\Columns\TextColumn::make("transaction_message")
                    ->label("Description")
                    ->default("Transaction")
                    ->limit(40)
                    ->tooltip(function ($record): ?string {
                        return $record->transaction_message;
                    }),

                Tables\Columns\TextColumn::make("created_at")
                    ->label("Time")
                    ->dateTime("M j, H:i")
                    ->sortable()
                    ->since()
                    ->tooltip(
                        fn($record): string => $record->created_at->format(
                            'F j, Y \a\t g:i A'
                        )
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn($record): string => $this->getViewUrl($record))
                    ->openUrlInNewTab(false),
            ])
            ->emptyStateHeading("No recent activity")
            ->emptyStateDescription(
                "Recent transactions and activities will appear here."
            )
            ->emptyStateIcon("heroicon-o-banknotes")
            ->defaultSort("created_at", "desc")
            ->poll("30s");
    }

    protected function getTableQuery(): Builder
    {
        return UserTransaction::query()
            ->with(["user:id,name,email"])
            ->latest()
            ->limit(50);
    }

    private function getViewUrl($record): string
    {
        return "/admin/user-transactions/" . $record->id;
    }
}
