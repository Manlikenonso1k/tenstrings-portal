<?php

namespace Tests\Feature;

use App\Filament\Inventory\Resources\InventoryRoomResource;
use App\Models\InventoryRoom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\BuildsInventory;
use Tests\TestCase;

class InventoryRoomPhotoTest extends TestCase
{
    use BuildsInventory;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedInventoryPermissions();
    }

    public function test_a_branch_manager_may_change_a_photo_in_their_own_branch_only(): void
    {
        $ajah = $this->branch('ajah-branch');
        $agege = $this->branch('agege-branch');

        $ownRoom = $this->room($ajah, 'Ajah Studio');
        $foreignRoom = $this->room($agege, 'Agege Studio');

        $manager = $this->userWithRole('branch_manager', $ajah);

        $this->assertTrue($manager->can('inventory_room.update_photo'));
        $this->assertTrue($manager->can('updatePhoto', $ownRoom));
        $this->assertFalse($manager->can('updatePhoto', $foreignRoom));
    }

    public function test_an_inventory_officer_may_change_a_room_photo(): void
    {
        $branch = $this->branch();
        $officer = $this->userWithRole('inventory_officer', $branch);

        $this->assertTrue($officer->can('updatePhoto', $this->room($branch)));
    }

    public function test_the_ceo_is_read_only_and_cannot_change_a_room_photo(): void
    {
        $room = $this->room($this->branch());
        $ceo = $this->userWithRole('ceo');

        $this->assertFalse($ceo->can('inventory_room.update_photo'));
        $this->assertFalse($ceo->can('updatePhoto', $room));
    }

    public function test_a_branch_manager_without_room_update_rights_still_cannot_rename_a_room(): void
    {
        $branch = $this->branch();
        $room = $this->room($branch);
        $manager = $this->userWithRole('branch_manager', $branch);

        // This portal does grant branch managers room.update; the point of the
        // separate action is that the photo permission is independent of it.
        $this->assertTrue($manager->can('inventory_room.update_photo'));

        $stripped = $this->userWithRole('ceo');
        $stripped->syncPermissions(['inventory_room.update_photo']);
        $stripped = $stripped->fresh();

        $this->assertTrue($stripped->can('updatePhoto', $room));
        $this->assertFalse($stripped->can('update', $room));
    }

    public function test_saving_a_photo_downscales_it_and_writes_a_thumbnail(): void
    {
        Storage::fake('public');

        $room = $this->room($this->branch());

        // 2400px wide, comfortably over the 1600px ceiling.
        $path = 'inventory/rooms/wide.jpg';
        Storage::disk('public')->put($path, UploadedFile::fake()->image('wide.jpg', 2400, 1600)->get());

        InventoryRoomResource::applyRoomPhoto($room, $path);

        $room->refresh();

        $this->assertSame($path, $room->image);
        $this->assertNotNull($room->image_thumb_path);
        Storage::disk('public')->assertExists($room->image_thumb_path);

        [$width] = getimagesizefromstring(Storage::disk('public')->get($room->image));
        $this->assertSame(1600, $width);

        [$thumbWidth] = getimagesizefromstring(Storage::disk('public')->get($room->image_thumb_path));
        $this->assertSame(600, $thumbWidth);
    }

    public function test_clearing_the_photo_clears_the_thumbnail_too(): void
    {
        Storage::fake('public');

        $room = $this->room($this->branch());
        $room->forceFill(['image' => 'a.jpg', 'image_thumb_path' => 'thumbs/a-thumb.jpg'])->save();

        InventoryRoomResource::applyRoomPhoto($room, null);

        $room->refresh();

        $this->assertNull($room->image);
        $this->assertNull($room->image_thumb_path);
    }

    public function test_a_room_without_a_photo_gets_an_initial_placeholder(): void
    {
        $room = new InventoryRoom(['name' => 'Digital Studio']);

        $placeholder = InventoryRoomResource::initialPlaceholder($room);

        $this->assertStringStartsWith('data:image/svg+xml;base64,', $placeholder);
        $this->assertStringContainsString('>D<', base64_decode(substr($placeholder, strlen('data:image/svg+xml;base64,'))));
    }
}
