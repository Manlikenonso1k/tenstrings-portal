<x-filament-panels::page.simple class="min-h-screen bg-gradient-to-b from-white to-blue-100 dark:from-gray-950 dark:to-gray-900 flex items-center justify-center">
    <section class="w-full md:w-[1024px] md:h-[489.594px] overflow-hidden rounded-md shadow-lg flex flex-col md:flex-row items-center justify-center">
        <aside class="hidden md:flex md:w-[416.922px] md:h-[348px] items-center justify-center bg-gray-50/50 dark:bg-gray-800/60 p-8">
            <img
                src="{{ asset('images/tenstrings-logo.png') }}"
                alt="Tenstrings Music Institute"
                class="h-64 w-64 object-contain"
            />
        </aside>

        <section class="w-full max-w-full md:w-[416.922px] h-[45vh] md:h-[348px] overflow-y-auto bg-white dark:bg-gray-900 text-gray-900 dark:text-white flex items-center">
            <div class="w-full p-6 sm:p-10 md:p-12">
                @if (filament()->hasRegistration())
                    <p class="mb-4 text-sm text-gray-700 dark:text-gray-300">
                        {{ __('filament-panels::pages/auth/login.actions.register.before') }}
                        {{ $this->registerAction }}
                    </p>
                @endif

                <x-filament-panels::form id="form" wire:submit="authenticate">
                    {{ $this->form }}

                    <x-filament-panels::form.actions
                        :actions="$this->getCachedFormActions()"
                        :full-width="$this->hasFullWidthFormActions()"
                    />
                </x-filament-panels::form>
            </div>
        </section>
    </section>
</x-filament-panels::page.simple>
