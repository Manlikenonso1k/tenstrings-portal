<x-filament-panels::page.simple class="min-h-screen bg-gradient-to-b from-white to-blue-100 flex items-center justify-center">
    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

    <section class="rounded-md shadow-lg w-full overflow-hidden lg:max-w-screen-lg">
        <div class="rounded-md w-full justify-center overflow-hidden md:flex">
            <aside class="bg-gray-50 bg-opacity-50 text-primary p-8 hidden justify-center items-center md:flex md:w-1/3 lg:w-1/2">
                <div
                    class="bg-contain bg-center bg-no-repeat bg-opacity-50 h-64 w-64"
                    style="background-image: url('{{ asset('images/tenstrings-logo.png') }}');"
                    role="img"
                    aria-label="Tenstrings Music Institute"
                ></div>
            </aside>

            <section class="bg-white flex-grow h-screen overflow-y-auto md:h-full">
                <div class="w-full max-w-xl p-6 sm:p-10 md:p-12">
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
            </section>
            </div>
        </div>
    </section>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}
</x-filament-panels::page.simple>
