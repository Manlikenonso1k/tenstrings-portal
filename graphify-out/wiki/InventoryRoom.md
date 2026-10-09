# InventoryRoom

> God node · 37 connections · `app/Models/InventoryRoom.php`

**Community:** [Inventory Room Model & Policy](Inventory_Room_Model_&_Policy.md)

## Connections by Relation

### calls
- .syncBranchFromRoom() `EXTRACTED`
- .boot() `EXTRACTED`
- .room() `EXTRACTED`
- .test_branch_scoping_hides_other_branches_records() `EXTRACTED`
- .test_a_room_without_a_photo_gets_an_initial_placeholder() `EXTRACTED`
- .resolveRoom() `EXTRACTED`

### contains
- InventoryRoom.php `EXTRACTED`

### imports
- AppServiceProvider.php `EXTRACTED`
- InventoryRoomResource.php `EXTRACTED`
- AjahInventoryImporter.php `EXTRACTED`
- BuildsInventory.php `EXTRACTED`
- InventoryCheckoutPolicyTest.php `EXTRACTED`
- InventoryRoomPhotoTest.php `EXTRACTED`
- InventoryItemObserver.php `EXTRACTED`
- RelocateInventoryPhotos.php `EXTRACTED`
- InventoryRoomObserver.php `EXTRACTED`
- InventoryRoomPolicy.php `EXTRACTED`

### inherits
- Illuminate\Database\Eloquent\Model `EXTRACTED`

### method
- .getActivitylogOptions() `EXTRACTED`
- .branch() `EXTRACTED`
- .items() `EXTRACTED`
- .audits() `EXTRACTED`
- .isAuditStale() `EXTRACTED`

### mixes_in
- ScopedToBranch `EXTRACTED`
- Spatie\Activitylog\Traits\LogsActivity `EXTRACTED`
- Illuminate\Database\Eloquent\SoftDeletes `EXTRACTED`

### references
- .item() `EXTRACTED`
- .delete() `EXTRACTED`
- .forceDelete() `EXTRACTED`
- .restore() `EXTRACTED`
- .applyRoomPhoto() `EXTRACTED`
- .update() `EXTRACTED`
- .updatePhoto() `EXTRACTED`
- .view() `EXTRACTED`
- .initialPlaceholder() `EXTRACTED`
- .saving() `EXTRACTED`
- .updated() `EXTRACTED`

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*