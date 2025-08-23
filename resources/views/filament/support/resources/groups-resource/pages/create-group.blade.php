<x-filament-panels::page>
    <form wire:submit.prevent="createGroup" class="space-y-6">
        {{$this->form}}
        <div class="flex justify-end gap-2">
            <x-filament::button type="button" color="gray"  wire:click="cancel">

                {{ __('Cancel') }}
            </x-filament::button>
            <x-filament::button type="submit" icon="heroicon-m-sparkles">

                {{ __('Create Group') }}
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
