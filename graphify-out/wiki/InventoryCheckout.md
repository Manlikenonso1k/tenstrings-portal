# InventoryCheckout

> God node · 46 connections · `app/Models/InventoryCheckout.php`

**Community:** [Checkout Model & Policy](Checkout_Model_&_Policy.md)

## Connections by Relation

### calls
- .checkout() `EXTRACTED`
- .markOverdue() `EXTRACTED`
- .test_ceo_sees_checkouts_from_every_branch() `EXTRACTED`

### contains
- InventoryCheckout.php `EXTRACTED`

### imports
- AppServiceProvider.php `EXTRACTED`
- InventoryCheckoutService.php `EXTRACTED`
- CheckoutFormSchema.php `EXTRACTED`
- InventoryCheckoutResource.php `EXTRACTED`
- CheckoutActions.php `EXTRACTED`
- InventoryCheckoutPolicyTest.php `EXTRACTED`
- InventoryItemReturnedBadly.php `EXTRACTED`
- InventoryCheckoutOverdue.php `EXTRACTED`
- InventoryCheckoutPolicy.php `EXTRACTED`

### inherits
- Illuminate\Database\Eloquent\Model `EXTRACTED`

### method
- .getActivitylogOptions() `EXTRACTED`
- .scopeOpen() `EXTRACTED`
- .refreshStatus() `EXTRACTED`
- .nextReference() `EXTRACTED`
- .branch() `EXTRACTED`
- .lines() `EXTRACTED`
- .releasedBy() `EXTRACTED`
- .scopeOverdue() `EXTRACTED`
- .isFullyReturned() `EXTRACTED`
- .hasAnyReturn() `EXTRACTED`

### mixes_in
- ScopedToBranch `EXTRACTED`
- Spatie\Activitylog\Traits\LogsActivity `EXTRACTED`
- Illuminate\Database\Eloquent\SoftDeletes `EXTRACTED`

### references
- InventoryItemReturnedBadly `EXTRACTED`
- .returnItems() `EXTRACTED`
- InventoryCheckoutOverdue `EXTRACTED`
- .returnSummary() `EXTRACTED`
- .notifyBadReturn() `EXTRACTED`
- .returnPhotoSections() `EXTRACTED`
- .openCheckoutFor() `EXTRACTED`
- .runReturn() `EXTRACTED`
- .outstandingLineOptions() `EXTRACTED`
- .outstandingLines() `EXTRACTED`
- .returnQuantityFields() `EXTRACTED`
- .returnSteps() `EXTRACTED`
- .__construct() `EXTRACTED`
- .delete() `EXTRACTED`
- .managePhotos() `EXTRACTED`
- .return() `EXTRACTED`
- .update() `EXTRACTED`
- .view() `EXTRACTED`
- .__construct() `EXTRACTED`

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*