<x-filament-panels::page.simple class="min-h-screen bg-gradient-to-b from-white to-blue-100">
    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

    <div class="grid grid-cols-1 overflow-hidden rounded-2xl shadow-2xl ring-1 ring-gray-950/5 lg:grid-cols-2 lg:min-h-[32rem]">
        <div class="flex min-h-72 items-center justify-center bg-white p-10 shadow-sm lg:min-h-full">
            <img
                src="{{ asset('images/tenstrings-logo.png') }}"
                alt="Tenstrings Music Institute"
                class="h-auto w-full max-w-xs object-contain"
            />
        </div>

        <div class="flex items-center bg-white p-6 shadow-sm sm:p-10 lg:min-h-full">
            <div class="w-full">
                @if (filament()->hasRegistration())
                    <p class="mb-4 text-sm text-gray-600">
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
        </div>
    </div>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}
</x-filament-panels::page.simple>
