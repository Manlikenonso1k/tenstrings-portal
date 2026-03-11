<x-filament-panels::page>
    <div class="mb-5">
        <a href="{{ \App\Filament\Portal\Pages\StudentDataPage::getUrl() }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
            <x-heroicon-o-arrow-left class="w-4 h-4"/>
            Back to Student Data Hub
        </a>
    </div>

    <x-filament::section icon="heroicon-o-identification" heading="CORE INFORMATION">
        {{ $this->form }}
        <div class="mt-4">
            <x-filament::button wire:click="save">Save Changes</x-filament::button>
        </div>
    </x-filament::section>
</x-filament-panels::page>
