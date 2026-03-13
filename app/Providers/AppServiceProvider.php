<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\FilamentImportPolicy;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Import::class, FilamentImportPolicy::class);

        Gate::before(function (User $user) {
            return $user->isSuperAdmin() ? true : null;
        });
    }
}
