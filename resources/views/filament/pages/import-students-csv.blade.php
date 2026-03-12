<x-filament-panels::page>
    <x-filament-panels::form wire:submit="import">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament::button type="submit" icon="heroicon-o-arrow-up-tray">
                Run Student Import
            </x-filament::button>
        </div>
    </x-filament-panels::form>
</x-filament-panels::page>
