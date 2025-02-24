<x-filament-panels::page>
    <x-filament::section>

        <form wire:submit.prevent="create">
            {{ $this->form }}
            <div class="mt-4">
                <x-filament::button type="submit" color="primary">
                    {{ __('Save') }}
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>
</x-filament-panels::page>
