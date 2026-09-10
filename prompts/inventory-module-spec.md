# Ten Strings Portal — Inventory Module + Scoped Inventory Officer Role

**Stack:** Laravel 12, Filament 3, Spatie Laravel Permission, PostgreSQL
**Goal:** Room-by-room asset register across 4 branches, maintained by an Inventory Officer who can touch *nothing else* in the portal. CEO gets read-only visibility across all branches.

---

## 1. Isolation strategy (do this first — it's the whole point)

Use **three layers**, not one. Navigation hiding alone is not security.

**Layer 1 — Separate Filament panel.**
Create `app/Providers/Filament/InventoryPanelProvider.php` serving `/inventory`, with its own resource discovery path (`app/Filament/Inventory/Resources`). The existing admin panel's resources are *never* registered here, so there is no route to guess.

```php
public function panel(Panel $panel): Panel
{
    return $panel
        ->id('inventory')
        ->path('inventory')
        ->login()
        ->colors(['primary' => Color::Amber])
        ->discoverResources(in: app_path('Filament/Inventory/Resources'), for: 'App\\Filament\\Inventory\\Resources')
        ->discoverPages(in: app_path('Filament/Inventory/Pages'), for: 'App\\Filament\\Inventory\\Pages')
        ->discoverWidgets(in: app_path('Filament/Inventory/Widgets'), for: 'App\\Filament\\Inventory\\Widgets')
        ->middleware([...])
        ->authMiddleware([Authenticate::class]);
}
```

**Layer 2 — `canAccessPanel()` on the User model.**

```php
public function canAccessPanel(Panel $panel): bool
{
    return match ($panel->getId()) {
        'inventory' => $this->hasAnyRole(['super_admin', 'ceo', 'inventory_officer', 'branch_manager']),
        'admin'     => $this->hasAnyRole(['super_admin', 'ceo', 'admin', 'accountant']),
        default     => false,
    };
}
```

An Inventory Officer hitting `/admin` gets a 403, not a redirect loop.

**Layer 3 — Policies on every inventory model, plus Spatie permission checks in `Resource::canViewAny()`, `canCreate()`, `canEdit()`, `canDelete()`.**

Add a test that asserts an `inventory_officer` receives 403 on `/admin`, on the student resource, and on the payment resource. Make it a real feature test — this is the requirement most likely to silently regress.

---

## 2. Schema

Reuse the existing `branches` table if the portal already has one. If not, create it with `name`, `code`, `address`, `is_active`.

### `inventory_categories`
`id, name, slug (unique), description, is_active, timestamps`
Seed: Furniture, IT Equipment, Musical Instruments, Appliances, Audio/Visual, Electrical Fittings, Office Supplies, Teaching Aids.

### `inventory_rooms`
```
id, branch_id (fk, cascade), name, code, floor (nullable),
room_type (enum: classroom, practice_studio, office, reception,
           store, library, hall, restroom, other),
description (nullable), is_active (bool, default true),
last_audited_at (timestamp, nullable), timestamps, softDeletes
unique(['branch_id', 'code'])
```

### `inventory_items`
```
id, branch_id (fk), inventory_room_id (fk, nullOnDelete),
inventory_category_id (fk),
name, asset_tag (string, unique, nullable),
serial_number (nullable), brand (nullable), model (nullable),
quantity (unsigned int, default 1), unit (string, default 'unit'),
condition (enum: new, good, fair, poor, damaged, needs_repair),
status (enum: in_use, in_storage, under_repair, disposed, missing),
acquisition_date (date, nullable), purchase_cost (decimal 12,2, nullable),
supplier (nullable), photo_path (nullable), notes (text, nullable),
last_verified_at (timestamp, nullable),
created_by (fk users), updated_by (fk users, nullable),
timestamps, softDeletes
index(['branch_id', 'inventory_room_id']), index('condition'), index('status')
```

Keep `branch_id` denormalised on the item even though the room implies it — it makes branch scoping and reporting cheap, and survives a room being deleted. Sync it in a model observer whenever `inventory_room_id` changes.

### `inventory_movements`
```
id, inventory_item_id (fk), from_room_id (nullable), to_room_id (nullable),
from_branch_id (nullable), to_branch_id (nullable),
quantity, reason (text), moved_by (fk users), moved_at, timestamps
```

### `inventory_audits`
```
id, branch_id (fk), inventory_room_id (fk, nullable — null = whole branch),
title, scheduled_for (date), started_at, completed_at (nullable),
status (enum: draft, in_progress, completed, cancelled),
conducted_by (fk users), notes, timestamps
```

### `inventory_audit_lines`
```
id, inventory_audit_id (fk, cascade), inventory_item_id (fk),
expected_quantity, counted_quantity (nullable),
condition_found (enum, nullable), is_missing (bool default false),
remark (nullable), timestamps
```

---

## 3. Asset tag generation

Auto-generate on create when left blank, in an `InventoryItem` observer:

```
TS-{BRANCH_CODE}-{CATEGORY_SLUG_ABBR}-{0001}
e.g. TS-IKJ-ITE-0042
```

Sequence is per branch + category. Wrap in a DB transaction with a `lockForUpdate()` on the counter row to avoid collisions when two officers save at once. Allow manual override — some assets arrive with existing organisational tags.

---

## 4. Permissions (Spatie)

Register these permissions:

```
inventory.view          inventory.export
room.view    room.create    room.update    room.delete
item.view    item.create    item.update    item.delete
item.dispose item.transfer  item.import
audit.view   audit.create   audit.update   audit.complete
category.manage
inventory.view_all_branches
inventory.view_costs
```

### Roles

| Role | Permissions |
|---|---|
| `super_admin` | everything (use Gate::before bypass) |
| `ceo` | `inventory.view`, `inventory.export`, `room.view`, `item.view`, `audit.view`, `inventory.view_all_branches`, `inventory.view_costs` — **no create/update/delete anywhere** |
| `inventory_officer` | `inventory.view`, `room.*` (view/create/update), `item.view/create/update/transfer/import`, `audit.view/create/update/complete`, `inventory.view_all_branches` |
| `branch_manager` (optional) | same as officer but **without** `inventory.view_all_branches` — scoped to their own branch |

Deliberately withheld from `inventory_officer`: `room.delete`, `item.delete`, `item.dispose`, `inventory.view_costs`, `category.manage`. Deletion and disposal are how an asset register gets quietly falsified — those stay with the CEO/super admin. The officer marks an item `missing` or `needs_repair` and it shows up on the CEO's exceptions list instead.

Hiding `inventory.view_costs` also means the officer never sees purchase prices, which is usually what you actually want from a field role.

---

## 5. Branch scoping

Add a `ScopedToBranch` trait applying a global scope:

```php
protected static function booted(): void
{
    static::addGlobalScope('branch', function (Builder $q) {
        $user = auth()->user();
        if ($user && ! $user->can('inventory.view_all_branches')) {
            $q->where('branch_id', $user->branch_id);
        }
    });
}
```

Apply to `InventoryRoom`, `InventoryItem`, `InventoryAudit`.

---

## 6. Filament resources (in the `inventory` panel)

**RoomResource** — table grouped by branch, columns: code, name, room type badge, item count, total quantity, last audited (with a "stale" warning colour past 90 days). Row action: *View items*. Relation manager for items.

**ItemResource** — the workhorse.
- Table columns: asset tag (copyable), name, category, room, branch, quantity, condition badge, status badge, last verified.
- Filters: branch, room, category, condition, status, `needs_attention` ternary (condition in poor/damaged/needs_repair OR status in under_repair/missing), `not_verified_this_term`.
- Bulk actions: transfer to room, mark verified, export. **No bulk delete for the officer.**
- Form sections: Identification / Location / Condition & status / Acquisition (cost fields visible only with `inventory.view_costs`) / Photo & notes.
- `->defaultGroup('room.name')` so it reads as a room-by-room walkthrough, which matches how the officer physically works.

**AuditResource** — create an audit for a room, auto-generate audit lines from the room's current items, officer fills counted quantity and condition found on a repeater or a custom page with a mobile-friendly list. On complete: update each item's `last_verified_at`, write variances to a report, set `room.last_audited_at`.

**CategoryResource** — visible only with `category.manage`.

### Pages / widgets
- `InventoryOverview` dashboard: stat cards (rooms mapped, items logged, total quantity, items needing attention, rooms not audited in 90 days), a branch-coverage bar chart, and an exceptions table.
- CEO sees the same dashboard with cost values included and every action button absent.

---

## 7. Mobile capture

The officer is walking rooms with a phone. Two things that make or break adoption:
1. Filament's form is usable on mobile but the audit page should be a stripped custom Livewire page — one item per row, big +/- quantity stepper, condition dropdown, sticky save.
2. Photo upload via `FileUpload` with `->image()->imageEditor()` and camera capture attribute, stored on your existing disk, with `->maxSize(4096)`.

Consider an offline-tolerant approach later; for v1, save per-line rather than per-audit so a dropped connection loses one row, not an hour of counting.

---

## 8. Import

Reuse the existing anti-duplicate CSV importer pattern from the portal. Columns: `branch_code, room_code, category, name, brand, model, serial_number, quantity, condition, acquisition_date`. Match on `serial_number` first, then `branch_code + room_code + name + brand`. Report skipped duplicates rather than failing the batch.

Gate behind `item.import`.

---

## 9. Audit trail

Log every create/update/delete on `inventory_items` and `inventory_rooms` with `spatie/laravel-activitylog`, including the acting user and branch. Add a read-only "History" tab on the item view page. When the CEO asks why 6 fans became 4, the answer needs to be one click away.

---

## 10. Acceptance checklist

- [ ] Inventory Officer logs in and lands on `/inventory` with only inventory navigation visible
- [ ] Inventory Officer gets 403 on `/admin`, on student, fee, and payment routes
- [ ] Inventory Officer cannot see purchase cost fields anywhere, including exports
- [ ] Inventory Officer cannot delete or dispose an item; those actions are absent, not just disabled
- [ ] CEO sees all 4 branches, all costs, and zero write actions
- [ ] Branch manager sees only their own branch, verified by a scoped query test
- [ ] Asset tags are unique under concurrent creation
- [ ] Completing an audit updates `last_verified_at` and produces a variance report
- [ ] Feature tests cover each role's access matrix
