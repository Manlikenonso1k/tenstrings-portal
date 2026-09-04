<?php

namespace App\Providers;

use App\Models\User;
use App\Models\InventoryAudit;
use App\Models\InventoryCategory;
use App\Models\InventoryItem;
use App\Models\InventoryRoom;
use App\Observers\InventoryItemObserver;
use App\Observers\InventoryRoomObserver;
use App\Policies\InventoryAuditPolicy;
use App\Policies\InventoryCategoryPolicy;
use App\Policies\InventoryItemPolicy;
use App\Policies\InventoryRoomPolicy;
use App\Policies\FilamentImportPolicy;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL; // Add this
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
        // Force HTTPS for all assets and links
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }

        Gate::policy(Import::class, FilamentImportPolicy::class);
        Gate::policy(InventoryRoom::class, InventoryRoomPolicy::class);
        Gate::policy(InventoryItem::class, InventoryItemPolicy::class);
        Gate::policy(InventoryCategory::class, InventoryCategoryPolicy::class);
        Gate::policy(InventoryAudit::class, InventoryAuditPolicy::class);
        InventoryItem::observe(InventoryItemObserver::class);
        InventoryRoom::observe(InventoryRoomObserver::class);

        Gate::before(function (User $user) {
            return $user->isSuperAdmin() ? true : null;
        });
    }
}