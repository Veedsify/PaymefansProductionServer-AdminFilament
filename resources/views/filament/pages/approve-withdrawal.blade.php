<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Withdrawal Details Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Withdrawal Details</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">User</label>
                    <p class="text-gray-900 dark:text-white">{{ $record->user->name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                    <p class="text-gray-900 dark:text-white">{{ $record->user->email }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Amount Requested</label>
                    <p class="text-gray-900 dark:text-white font-semibold">₦{{ number_format($record->amount) }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Platform Fee (25%)</label>
                    <p class="text-gray-900 dark:text-white">₦{{ number_format(\App\Services\PaystackService::calculatePlatformFee($record->amount)) }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Amount to Transfer</label>
                    <p class="text-green-600 dark:text-green-400 font-semibold">₦{{ number_format(\App\Services\PaystackService::calculateAmountAfterFee($record->amount)) }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $record->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $record->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $record->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $record->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ ucfirst($record->status) }}
                    </span>
                </div>

                @if($record->bankAccount)
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Bank</label>
                    <p class="text-gray-900 dark:text-white">{{ $record->bankAccount->bank_name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Account Name</label>
                    <p class="text-gray-900 dark:text-white">{{ $record->bankAccount->account_name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Account Number</label>
                    <p class="text-gray-900 dark:text-white">{{ $record->bankAccount->account_number }}</p>
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Reason</label>
                    <p class="text-gray-900 dark:text-white">{{ $record->reason ?: 'No reason provided' }}</p>
                </div>
            </div>
        </div>

        <!-- Action Card -->
        @if(!$transferInitiated && $record->status !== 'completed' && $record->status !== 'rejected')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Approve Withdrawal</h3>

            <div class="space-y-4">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                Clicking "Initiate Transfer" will start the Paystack withdrawal process. You'll need to enter an OTP to complete the transaction.
                            </p>
                        </div>
                    </div>
                </div>

                <button wire:click="initiateTransfer" wire:loading.attr="disabled" wire:target="initiateTransfer" type="button" class="w-full flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed">

                    <!-- Loading spinner -->
                    <div wire:loading wire:target="initiateTransfer" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    </div>

                    <!-- Default content -->
                    <div wire:loading.remove wire:target="initiateTransfer" class="inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Initiate Transfer
                    </div>
                </button>
            </div>
        </div>
        @endif

        <!-- OTP Verification Card -->
        @if($transferInitiated && $record->status !== 'completed' && $record->status !== 'rejected')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Enter OTP</h3>

            <div class="space-y-4">
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700 dark:text-green-300">
                                Transfer initiated successfully! Please enter the OTP sent to your registered phone number to complete the transaction.
                            </p>
                        </div>
                    </div>
                </div>

                @if($transferCode && $reference)
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <label class="block font-medium text-gray-500 dark:text-gray-400">Transfer Code</label>
                            <p class="text-gray-900 dark:text-white font-mono">{{ $transferCode }}</p>
                        </div>
                        <div>
                            <label class="block font-medium text-gray-500 dark:text-gray-400">Reference</label>
                            <p class="text-gray-900 dark:text-white font-mono">{{ $reference }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <form wire:submit="finalizeTransfer">
                    <div class="space-y-4">
                        <!-- OTP Input Field -->
                        <div>
                            <label for="otp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                OTP Code
                            </label>
                            <input wire:model.live="data.otp" type="text" id="otp" name="otp" maxlength="6" placeholder="Enter 6-digit OTP" class="w-full px-3 py-2 text-center text-2xl tracking-widest border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" autocomplete="off" required x-data="" x-init="$nextTick(() => $el.focus())" x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '')" />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Enter the 6-digit OTP sent to your registered phone number
                            </p>
                            @error('data.otp')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" wire:loading.attr="disabled" wire:target="finalizeTransfer" x-data="{ otpValid: false }" x-init="$watch('$wire.data.otp', value => otpValid = value && value.length === 6)" :disabled="!otpValid" :class="otpValid ? 
                                    'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500' : 
                                    'bg-gray-400 cursor-not-allowed'" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200">

                            <!-- Loading spinner -->
                            <div wire:loading wire:target="finalizeTransfer" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Completing Transfer...
                            </div>

                            <!-- Default content -->
                            <div wire:loading.remove wire:target="finalizeTransfer" class="inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m0 0a2 2 0 012 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2V9a2 2 0 012-2m0 0V5a2 2 0 012-2h4a2 2 0 012 2v2M9 7h6"></path>
                                </svg>
                                <span x-show="otpValid">Complete Transfer</span>
                                <span x-show="!otpValid">Enter 6-digit OTP to continue</span>
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- Success Message -->
        @if($record->status === 'completed')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800 dark:text-green-200">Transfer Completed Successfully</h3>
                        <p class="mt-1 text-sm text-green-700 dark:text-green-300">
                            The withdrawal of ₦{{ number_format($record->amount) }} has been successfully processed and sent to {{ $record->bankAccount->account_name }} ({{ $record->bankAccount->account_number }}). The user has been notified via email.
                            @if($record->reference)
                            <br><strong>Reference:</strong> {{ $record->reference }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Rejected Message -->
        @if($record->status === 'rejected')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Withdrawal Request Rejected</h3>
                        <p class="mt-1 text-sm text-red-700 dark:text-red-300">
                            This withdrawal request has been rejected. The user's points have been restored and they have been notified via email.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-filament-panels::page>
