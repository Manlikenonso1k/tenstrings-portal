<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Inventory permissions
    |--------------------------------------------------------------------------
    |
    | Every permission the inventory module recognises. Each of these is
    | registered as a Laravel Gate ability by InventoryServiceProvider, so
    | `$user->can('item.dispose')` works anywhere in the app without a
    | third-party permission package.
    |
    */

    'permissions' => [
        'inventory.view',
        'inventory.export',
        'room.view',
        'room.create',
        'room.update',
        'room.delete',
        'item.view',
        'item.create',
        'item.update',
        'item.delete',
        'item.dispose',
        'item.transfer',
        'item.import',
        'audit.view',
        'audit.create',
        'audit.update',
        'audit.complete',
        'category.manage',
        'inventory.view_all_branches',
        'inventory.view_costs',
    ],

    /*
    |--------------------------------------------------------------------------
    | Role → permission map
    |--------------------------------------------------------------------------
    |
    | `super_admin` is the CEO. It is deliberately absent from this map because
    | AppServiceProvider already installs a Gate::before bypass that grants it
    | every ability — listing it here would be a second source of truth.
    |
    | Withheld from `inventory_officer` on purpose: room.delete, item.delete,
    | item.dispose, inventory.view_costs, category.manage. Deletion and disposal
    | are how an asset register gets quietly falsified, so they stay with the
    | CEO. The officer marks an item `missing` or `needs_repair` instead, and it
    | surfaces on the CEO's exceptions list.
    |
    */

    'roles' => [

        'inventory_officer' => [
            'inventory.view',
            'inventory.export',
            'room.view',
            'room.create',
            'room.update',
            'item.view',
            'item.create',
            'item.update',
            'item.transfer',
            'item.import',
            'audit.view',
            'audit.create',
            'audit.update',
            'audit.complete',
        ],

        // Same as the officer, minus cross-branch visibility: scoped to their
        // own branch by the ScopedToBranch global scope.
        'branch_manager' => [
            'inventory.view',
            'inventory.export',
            'room.view',
            'room.create',
            'room.update',
            'item.view',
            'item.create',
            'item.update',
            'item.transfer',
            'item.import',
            'audit.view',
            'audit.create',
            'audit.update',
            'audit.complete',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Panel access
    |--------------------------------------------------------------------------
    |
    | Roles allowed through User::canAccessPanel() for the `inventory` panel.
    | Everything else gets a 403 — not a redirect loop.
    |
    */

    'panel_roles' => [
        'super_admin',
        'inventory_officer',
        'branch_manager',
    ],

    /*
    |--------------------------------------------------------------------------
    | Behaviour
    |--------------------------------------------------------------------------
    */

    // A room not audited in this many days is flagged stale.
    'stale_audit_days' => 90,

    // Asset tag prefix: TS-{BRANCH_CODE}-{CATEGORY_ABBR}-{0001}
    'asset_tag_prefix' => 'TS',

    // Max photo upload size in kilobytes.
    'photo_max_size' => 4096,

];
