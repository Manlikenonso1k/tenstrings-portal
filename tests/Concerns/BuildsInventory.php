<?php

namespace Tests\Concerns;

use App\Enums\ItemCondition;
use App\Enums\ItemStatus;
use App\Models\Branch;
use App\Models\InventoryCategory;
use App\Models\InventoryItem;
use App\Models\InventoryRoom;
use App\Models\User;
use Database\Seeders\InventoryPermissionSeeder;
use Spatie\Permission\PermissionRegistrar;

trait BuildsInventory
{
    protected function seedInventoryPermissions(): void
    {
        $this->seed(InventoryPermissionSeeder::class);
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    protected function branch(string $slug = 'ajah-branch'): Branch
    {
        return Branch::query()->where('slug', $slug)->firstOrFail();
    }

    /**
     * The legacy `role` column and the Spatie role are both set, because the
     * portal checks either one.
     */
    protected function userWithRole(string $role, ?Branch $branch = null): User
    {
        $user = User::factory()->create([
            'role' => $role,
            'branch_id' => $branch?->getKey(),
        ]);

        $user->assignRole($role);

        return $user->fresh();
    }

    protected function category(string $name = 'Musical Instruments'): InventoryCategory
    {
        return InventoryCategory::query()->firstOrCreate(
            ['slug' => str($name)->slug()->value()],
            ['name' => $name, 'is_active' => true],
        );
    }

    protected function room(Branch $branch, string $name = 'Digital Studio'): InventoryRoom
    {
        return InventoryRoom::query()->create([
            'branch_id' => $branch->getKey(),
            'name' => $name,
            'code' => strtoupper(str($name)->slug('-')->value()),
            'room_type' => 'practice_studio',
            'is_active' => true,
        ]);
    }

    protected function item(
        Branch $branch,
        ?InventoryRoom $room = null,
        int $quantity = 3,
        ItemStatus $status = ItemStatus::InUse,
        ?User $creator = null,
        string $name = 'Yamaha Keyboard',
    ): InventoryItem {
        return InventoryItem::query()->create([
            'branch_id' => $branch->getKey(),
            'inventory_room_id' => $room?->getKey(),
            'inventory_category_id' => $this->category()->getKey(),
            'name' => $name,
            'quantity' => $quantity,
            'unit' => 'unit',
            'condition' => ItemCondition::Good,
            'status' => $status,
            'created_by' => ($creator ?? User::factory()->create())->getKey(),
        ]);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    protected function checkoutPayload(InventoryItem $item, int $quantity = 1, int $photos = 2, array $overrides = []): array
    {
        return array_merge([
            'branch_id' => $item->branch_id,
            'event_name' => 'Christmas Carol Concert',
            'event_venue' => 'Muson Centre',
            'event_date' => now()->toDateString(),
            'responsible_person_name' => 'Ada Obi',
            'responsible_person_phone' => '+2348000000010',
            'expected_return_at' => now()->addDay(),
            'lines' => [[
                'inventory_item_id' => $item->getKey(),
                'quantity' => $quantity,
                'condition_out' => ItemCondition::Good->value,
                'photos_out' => $this->fakePhotos($photos),
            ]],
        ], $overrides);
    }

    /**
     * @return array<int, string>
     */
    protected function fakePhotos(int $count): array
    {
        if ($count < 1) {
            return [];
        }

        return collect(range(1, $count))
            ->map(fn (int $i): string => "inventory/checkouts/photo-{$i}-" . uniqid() . '.jpg')
            ->all();
    }
}
