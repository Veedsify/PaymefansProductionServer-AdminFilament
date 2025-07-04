<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WithdrawalRequestResource\Pages;
use App\Models\WithdrawalRequest;
use App\Services\WithdrawalService;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Infolists;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class WithdrawalRequestResource extends Resource
{
    protected static ?string $model = WithdrawalRequest::class;

    protected static ?string $navigationIcon = "heroicon-s-arrows-right-left";
    protected static ?string $navigationGroup = "Finance";
    protected static ?string $navigationLabel = "Withdrawal Requests";

    public static function form(Form $form): Form
    {
        return $form->schema([
            //
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("id")->sortable()->searchable(),
                Tables\Columns\TextColumn::make("user.name")
                    ->label("User")
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make("user.email")
                    ->label("Email")
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make("amount")
                    ->getStateUsing(function (WithdrawalRequest $record) {
                        return "₦" . number_format($record->amount);
                    })
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make("status")
                    ->badge()
                    ->color(
                        fn($state) => match ($state) {
                            "pending" => "warning",
                            "approved" => "success",
                            "rejected" => "danger",
                            "processing" => "info",
                            default => "secondary",
                        }
                    )
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make("reference")
                    ->label("Reference")
                    ->getStateUsing(
                        fn(WithdrawalRequest $record) => $record->reference ?:
                        "N/A"
                    )
                    ->copyable()
                    ->copyMessage("Reference copied!")
                    ->copyMessageDuration(1500)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make("created_at")
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make("Approve")
                    ->hidden(
                        fn(WithdrawalRequest $record) => $record->status ===
                            "completed" || $record->status === "rejected"
                    )
                    ->label("Approve")
                    ->icon("heroicon-s-check-circle")
                    ->color("success")
                    ->url(
                        fn(WithdrawalRequest $record) => static::getUrl(
                            "approve",
                            ["record" => $record->id]
                        )
                    )
                    ->openUrlInNewTab(false),
                Tables\Actions\Action::make("Reject")
                    ->hidden(
                        fn(WithdrawalRequest $record) => $record->status ===
                            "rejected" || $record->status === "completed"
                    )
                    ->label("Reject")
                    ->icon("heroicon-s-x-circle")
                    ->color("danger")
                    ->form([
                        \Filament\Forms\Components\Textarea::make(
                            "rejection_reason"
                        )
                            ->label("Rejection Reason (Optional)")
                            ->placeholder(
                                "Provide a reason for rejection (will be included in email notification)"
                            )
                            ->rows(3),
                    ])
                    ->action(function (WithdrawalRequest $record, array $data) {
                        $result = WithdrawalService::rejectWithdrawal(
                            $record,
                            $data["rejection_reason"] ?? null
                        );

                        if ($result["success"]) {
                            $message =
                                "Withdrawal request rejected successfully.";
                            if ($result["points_restored"]) {
                                $message .= " User points have been restored.";
                            }
                            if ($result["email_sent"]) {
                                $message .= " Email notification sent.";
                            }
                            if ($result["notifications_sent"]["rejection"]) {
                                $message .= " App notification sent.";
                            }

                            Notification::make()
                                ->title("Withdrawal Rejected")
                                ->body($message)
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title("Error")
                                ->body($result["message"])
                                ->danger()
                                ->send();
                        }

                        $record->refresh();
                    })
                    ->requiresConfirmation()
                    ->modalHeading("Reject Withdrawal Request")
                    ->modalDescription(
                        'Are you sure you want to reject this withdrawal request? The user\'s points will be restored and they will be notified via email.'
                    )
                    ->modalSubmitActionLabel("Reject Request"),
                Tables\Actions\Action::make("Mark As Processing")
                    ->hidden(
                        fn(WithdrawalRequest $record) => $record->status ===
                            "processing" ||
                            $record->status === "completed" ||
                            $record->status === "rejected"
                    )
                    ->label("Mark As Processing")
                    ->icon("heroicon-s-arrow-path")
                    ->color("info")
                    ->action(function (WithdrawalRequest $record) {
                        $record->update(["status" => "processing"]);
                        $record->refresh();
                    })
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\TextEntry::make("user.name")->label("User"),
            Infolists\Components\TextEntry::make("user.email")->label("Email"),
            Infolists\Components\TextEntry::make("amount")
                ->label("Amount Requested")
                ->getStateUsing(
                    fn(WithdrawalRequest $record) => "₦" .
                        number_format($record->amount)
                ),
            Infolists\Components\TextEntry::make("platform_fee")
                ->label("Platform Fee (25%)")
                ->getStateUsing(
                    fn(WithdrawalRequest $record) => "₦" .
                        number_format(
                            \App\Services\PaystackService::calculatePlatformFee(
                                $record->amount
                            )
                        )
                ),
            Infolists\Components\TextEntry::make("amount_after_fee")
                ->label("Amount After Fee")
                ->getStateUsing(
                    fn(WithdrawalRequest $record) => "₦" .
                        number_format(
                            \App\Services\PaystackService::calculateAmountAfterFee(
                                $record->amount
                            )
                        )
                ),
            Infolists\Components\TextEntry::make("status")
                ->badge()
                ->color(
                    fn(WithdrawalRequest $record) => match ($record->status) {
                        "pending" => "warning",
                        "approved" => "success",
                        "rejected" => "danger",
                        "processing" => "info",
                        default => "secondary",
                    }
                )
                ->label("Status"),
            Infolists\Components\TextEntry::make("reason")
                ->getStateUsing(
                    fn(WithdrawalRequest $record) => $record->reason ?:
                    "No reason provided"
                )
                ->label("Reason"),
            Infolists\Components\TextEntry::make("bankAccount.bank_name")
                ->label("Bank Name")
                ->getStateUsing(
                    fn(WithdrawalRequest $record) => $record->bankAccount
                        ? $record->bankAccount->bank_name
                        : "No bank account"
                ),
            Infolists\Components\TextEntry::make("bankAccount.account_name")
                ->label("Account Name")
                ->getStateUsing(
                    fn(WithdrawalRequest $record) => $record->bankAccount
                        ? $record->bankAccount->account_name
                        : "No bank account"
                ),
            Infolists\Components\TextEntry::make("bankAccount.account_number")
                ->label("Account Number")
                ->getStateUsing(
                    fn(WithdrawalRequest $record) => $record->bankAccount
                        ? $record->bankAccount->account_number
                        : "No bank account"
                ),
            Infolists\Components\TextEntry::make("transfer_code")
                ->label("Transfer Code")
                ->getStateUsing(
                    fn(WithdrawalRequest $record) => $record->transfer_code ?:
                    "Not initiated"
                )
                ->visible(
                    fn(WithdrawalRequest $record) => !empty(
                        $record->transfer_code
                    )
                ),
            Infolists\Components\TextEntry::make("reference")
                ->label("Reference")
                ->getStateUsing(
                    fn(WithdrawalRequest $record) => $record->reference ?:
                    "Not available"
                )
                ->visible(
                    fn(WithdrawalRequest $record) => !empty($record->reference)
                ),
            Infolists\Components\TextEntry::make("created_at")
                ->label("Requested At")
                ->dateTime("F j, Y, g:i a"),
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
            "index" => Pages\ListWithdrawalRequests::route("/"),
            "create" => Pages\CreateWithdrawalRequest::route("/create"),
            "edit" => Pages\EditWithdrawalRequest::route("/{record}/edit"),
            "approve" => Pages\ApproveWithdrawal::route("/{record}/approve"),
        ];
    }
}
