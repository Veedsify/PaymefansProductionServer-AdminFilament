<x-filament-panels::page>
    <form wire:submit="sendEmail" class="space-y-4">
        {{$this->form}}
        <div class="mt-4">
            <x-filament::button type="submit" color="primary">
                {{ __('Send') }}
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>