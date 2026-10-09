# InventoryItem

> God node · 66 connections · `app/Models/InventoryItem.php`

**Community:** [Inventory Item Model](Inventory_Item_Model.md)

## Connections by Relation

### calls
- .returnItems() `EXTRACTED`
- .checkout() `EXTRACTED`
- .item() `EXTRACTED`
- .afterCreate() `EXTRACTED`
- .boot() `EXTRACTED`
- .generate() `EXTRACTED`
- .test_branch_scoping_hides_other_branches_records() `EXTRACTED`
- .availableItemOptions() `EXTRACTED`
- .findItem() `EXTRACTED`
- .beforeSave() `EXTRACTED`
- .resolveRecord() `EXTRACTED`

### contains
- InventoryItem.php `EXTRACTED`

### imports
- AppServiceProvider.php `EXTRACTED`
- InventoryItemResource.php `EXTRACTED`
- InventoryCheckoutService.php `EXTRACTED`
- CheckoutFormSchema.php `EXTRACTED`
- CheckoutActions.php `EXTRACTED`
- AjahInventoryImporter.php `EXTRACTED`
- BuildsInventory.php `EXTRACTED`
- InventoryCheckoutPolicyTest.php `EXTRACTED`
- InventoryItemReturnedBadly.php `EXTRACTED`
- InventoryItemObserver.php `EXTRACTED`
- RelocateInventoryPhotos.php `EXTRACTED`
- CreateInventoryAudit.php `EXTRACTED`
- AssetTagGenerator.php `EXTRACTED`
- InventoryItemPolicy.php `EXTRACTED`

### inherits
- Illuminate\Database\Eloquent\Model `EXTRACTED`

### method
- .openCheckoutLines() `EXTRACTED`
- .scopeNeedsAttention() `EXTRACTED`
- .quantityOut() `EXTRACTED`
- .scopeNotVerifiedSince() `EXTRACTED`
- .needsAttention() `EXTRACTED`
- .getActivitylogOptions() `EXTRACTED`
- .checkoutLines() `EXTRACTED`
- .availableQuantity() `EXTRACTED`
- .isBlockedFromCheckout() `EXTRACTED`
- .creator() `EXTRACTED`
- .updater() `EXTRACTED`
- .branch() `EXTRACTED`
- .room() `EXTRACTED`
- .category() `EXTRACTED`
- .movements() `EXTRACTED`
- .transfers() `EXTRACTED`
- .auditLines() `EXTRACTED`

### mixes_in
- ScopedToBranch `EXTRACTED`
- Spatie\Activitylog\Traits\LogsActivity `EXTRACTED`
- Illuminate\Database\Eloquent\SoftDeletes `EXTRACTED`

### references
- InventoryItemReturnedBadly `EXTRACTED`
- .notifyBadReturn() `EXTRACTED`
- .openCheckoutFor() `EXTRACTED`
- .syncBranchFromRoom() `EXTRACTED`
- .delete() `EXTRACTED`
- .creating() `EXTRACTED`
- .forceDelete() `EXTRACTED`
- .restore() `EXTRACTED`
- .__construct() `EXTRACTED`
- .updated() `EXTRACTED`
- .updating() `EXTRACTED`
- .dispose() `EXTRACTED`
- .transfer() `EXTRACTED`
- .update() `EXTRACTED`
- .view() `EXTRACTED`
- .applyReturnToItem() `EXTRACTED`
- .markItemStatusAfterCheckout() `EXTRACTED`
- .checkoutPayload() `EXTRACTED`
- .canCheckout() `EXTRACTED`

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*