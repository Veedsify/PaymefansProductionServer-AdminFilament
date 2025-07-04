<?php

namespace App\Filament\Resources\WithdrawalRequestResource\Pages;

use App\Filament\Resources\WithdrawalRequestResource;
use App\Models\WithdrawalRequest;
use App\Services\PaystackService;
use App\Services\WithdrawalService;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Log;

class ApproveWithdrawal extends ViewRecord
{
    protected static string $resource = WithdrawalRequestResource::class;
    protected static string $view = 'filament.pages.approve-withdrawal';

    public ?string $otp = null;
    public bool $transferInitiated = false;
    public ?string $transferCode = null;
    public ?string $reference = null;
    public ?array $data = [];
    public bool $initiatingTransfer = false;
    public bool $finalizingTransfer = false;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Initialize transfer status from record
        if ($this->record->transfer_code) {
            $this->transferInitiated = true;
            $this->transferCode = $this->record->transfer_code;
            $this->reference = $this->record->reference;
        }

        return $data;
    }

    public function getTitle(): string
    {
        return "Approve Withdrawal Request #{$this->record->id}";
    }

    public function form(Form $form): Form
    {
        // We're using a custom HTML form in the Blade template
        // This form is kept for compatibility but not used
        return $form->schema([]);
    }

    public function initiateTransfer(): void
    {
        $this->initiatingTransfer = true;

        try {
            $paystackService = new PaystackService();

            // Update status to processing
            $this->record->update(['status' => 'processing']);

            // Create recipient if not exists
            if (!$this->record->recipient_code && $this->record->bankAccount) {
                $recipientData = [
                    'account_name' => $this->record->bankAccount->account_name,
                    'account_number' => $this->record->bankAccount->account_number,
                    'bank_code' => $this->record->bankAccount->bank_id,
                ];

                $recipientResult = $paystackService->createTransferRecipient($recipientData);

                if (!$recipientResult['success']) {
                    Notification::make()
                        ->title('Error')
                        ->body($recipientResult['message'])
                        ->danger()
                        ->send();
                    $this->initiatingTransfer = false;
                    return;
                }

                $this->record->update(['recipient_code' => $recipientResult['recipient_code']]);
            }

            // Initiate transfer
            $transferData = [
                'amount' => PaystackService::calculateAmountAfterFee($this->record->amount), // Amount after platform fee
                'recipient_code' => $this->record->recipient_code,
                'reason' => $this->record->reason ?: 'Withdrawal request'
            ];

            $transferResult = $paystackService->initiateTransfer($transferData);

            if ($transferResult['success']) {
                $this->transferCode = $transferResult['transfer_code'];
                $this->reference = $transferResult['reference'];
                $this->transferInitiated = true;

                // Update record with transfer details
                $this->record->update([
                    'transfer_code' => $this->transferCode,
                    'paystack_response' => json_encode($transferResult['data'])
                ]);

                Notification::make()
                    ->title('Transfer Initiated')
                    ->body('Transfer has been initiated. Please enter the OTP sent to your registered phone number.')
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Transfer Failed')
                    ->body($transferResult['message'])
                    ->danger()
                    ->send();
            }
        } catch (\Exception $e) {
            Log::error('Transfer initiation error: ' . $e->getMessage());

            Notification::make()
                ->title('Error')
                ->body('An error occurred while initiating the transfer.')
                ->danger()
                ->send();
        } finally {
            $this->initiatingTransfer = false;
        }
    }

    public function finalizeTransfer(): void
    {
        $this->finalizingTransfer = true;

        $this->validate([
            'data.otp' => 'required|string|size:6|regex:/^[0-9]{6}$/'
        ], [
            'data.otp.required' => 'OTP is required.',
            'data.otp.size' => 'OTP must be exactly 6 digits.',
            'data.otp.regex' => 'OTP must contain only numbers.'
        ]);

        try {
            $paystackService = new PaystackService();

            $result = $paystackService->finalizeTransfer($this->transferCode, $this->data['otp']);

            if ($result['success']) {
                $this->record->update([
                    'status' => 'completed',
                    'paystack_response' => json_encode($result['data'])
                ]);

                // Send notifications and emails
                $notificationResult = WithdrawalService::completeWithdrawal($this->record);

                $message = 'The withdrawal has been successfully processed.';
                if ($notificationResult['email_sent']) {
                    $message .= ' User notified via email.';
                }
                if ($notificationResult['notification_sent']) {
                    $message .= ' App notification sent.';
                }

                Notification::make()
                    ->title('Transfer Completed')
                    ->body($message)
                    ->success()
                    ->send();

                $this->redirect(WithdrawalRequestResource::getUrl('index'));
            } else {
                Notification::make()
                    ->title('Transfer Failed')
                    ->body($result['message'])
                    ->danger()
                    ->send();
            }
        } catch (\Exception $e) {
            Log::error('Transfer finalization error: ' . $e->getMessage());

            Notification::make()
                ->title('Error')
                ->body('An error occurred while finalizing the transfer.')
                ->danger()
                ->send();
        } finally {
            $this->finalizingTransfer = false;
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('reject')
                ->label('Reject Request')
                ->icon('heroicon-s-x-circle')
                ->color('danger')
                ->visible(fn() => $this->record->status === 'pending' || $this->record->status === 'processing')
                ->form([
                    \Filament\Forms\Components\Textarea::make('rejection_reason')
                        ->label('Rejection Reason (Optional)')
                        ->placeholder('Provide a reason for rejection (will be included in email notification)')
                        ->rows(3)
                ])
                ->action(function (array $data) {
                    $result = WithdrawalService::rejectWithdrawal($this->record, $data['rejection_reason'] ?? null);

                    if ($result['success']) {
                        $message = 'Withdrawal request rejected successfully.';
                        if ($result['points_restored']) {
                            $message .= ' User points have been restored.';
                        }
                        if ($result['email_sent']) {
                            $message .= ' Email notification sent.';
                        }
                        if ($result['notifications_sent']['rejection']) {
                            $message .= ' App notification sent.';
                        }

                        Notification::make()
                            ->title('Withdrawal Rejected')
                            ->body($message)
                            ->success()
                            ->send();

                        $this->redirect(WithdrawalRequestResource::getUrl('index'));
                    } else {
                        Notification::make()
                            ->title('Error')
                            ->body($result['message'])
                            ->danger()
                            ->send();
                    }
                })
                ->requiresConfirmation()
                ->modalHeading('Reject Withdrawal Request')
                ->modalDescription('Are you sure you want to reject this withdrawal request? The user\'s points will be restored and they will be notified via email.')
                ->modalSubmitActionLabel('Reject Request'),
            Action::make('back')
                ->label('Back to List')
                ->url(WithdrawalRequestResource::getUrl('index'))
                ->color('gray'),
        ];
    }
}
