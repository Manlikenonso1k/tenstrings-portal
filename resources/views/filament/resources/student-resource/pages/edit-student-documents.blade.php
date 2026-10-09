<x-filament-panels::page>
    <x-filament-panels::form wire:submit="save">
        <x-filament::section icon="heroicon-o-folder-open" heading="DOCUMENT VAULT">
            {{ $this->form }}

            <div class="mt-6 flex justify-end">
                <x-filament::button type="submit" icon="heroicon-o-check">
                    Save Changes
                </x-filament::button>
            </div>
        </x-filament::section>
    </x-filament-panels::form>
</x-filament-panels::page>