# Inventory Room Photo Test Feature Test

> 11 nodes · cohesion 0.18

## Key Concepts

- **InventoryRoomPhotoTest** (12 connections) — `tests/Feature/InventoryRoomPhotoTest.php`
- **InventoryRoomPhotoTest.php** (8 connections) — `tests/Feature/InventoryRoomPhotoTest.php`
- **.test_a_room_without_a_photo_gets_an_initial_placeholder()** (3 connections) — `tests/Feature/InventoryRoomPhotoTest.php`
- **.test_clearing_the_photo_clears_the_thumbnail_too()** (2 connections) — `tests/Feature/InventoryRoomPhotoTest.php`
- **.test_saving_a_photo_downscales_it_and_writes_a_thumbnail()** (2 connections) — `tests/Feature/InventoryRoomPhotoTest.php`
- **Illuminate\Http\UploadedFile** (1 connections)
- **.setUp()** (1 connections) — `tests/Feature/InventoryRoomPhotoTest.php`
- **.test_a_branch_manager_may_change_a_photo_in_their_own_branch_only()** (1 connections) — `tests/Feature/InventoryRoomPhotoTest.php`
- **.test_a_branch_manager_without_room_update_rights_still_cannot_rename_a_room()** (1 connections) — `tests/Feature/InventoryRoomPhotoTest.php`
- **.test_an_inventory_officer_may_change_a_room_photo()** (1 connections) — `tests/Feature/InventoryRoomPhotoTest.php`
- **.test_the_ceo_is_read_only_and_cannot_change_a_room_photo()** (1 connections) — `tests/Feature/InventoryRoomPhotoTest.php`

## Relationships

- [Feature Test Harness](Feature_Test_Harness.md) (4 shared connections)
- [Inventory Room Resource](Inventory_Room_Resource.md) (4 shared connections)
- [Branch Model & Test Builders](Branch_Model_&_Test_Builders.md) (2 shared connections)
- [Inventory Room Model & Policy](Inventory_Room_Model_&_Policy.md) (2 shared connections)
- [TGIPay & Webhook Controllers](TGIPay_&_Webhook_Controllers.md) (1 shared connections)

## Source Files

- `tests/Feature/InventoryRoomPhotoTest.php`

## Audit Trail

- EXTRACTED: 23 (100%)
- INFERRED: 0 (0%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*