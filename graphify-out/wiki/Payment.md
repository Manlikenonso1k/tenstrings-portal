# Payment

> God node · 49 connections · `app/Models/Payment.php`

**Community:** [TGIPay & Webhook Controllers](TGIPay_&_Webhook_Controllers.md)

## Connections by Relation

### calls
- .handleWebhook() `EXTRACTED`
- .getHeaderActions() `EXTRACTED`
- .table() `EXTRACTED`
- .resetStudentPayment() `EXTRACTED`
- .syncFinancialRecords() `EXTRACTED`
- .getViewData() `EXTRACTED`
- .initiatePayment() `EXTRACTED`
- .index() `EXTRACTED`
- .callback() `EXTRACTED`
- .receipts() `EXTRACTED`
- .initializePayment() `EXTRACTED`
- .syncCourseFeeAndStudentSnapshot() `EXTRACTED`
- .table() `EXTRACTED`
- .callback() `EXTRACTED`

### contains
- Payment.php `EXTRACTED`

### imports
- StudentResource.php `EXTRACTED`
- ViewStudent.php `EXTRACTED`
- StudentImporter.php `EXTRACTED`
- PaymentController.php `EXTRACTED`
- PaymentResource.php `EXTRACTED`
- InvoicesRelationManager.php `EXTRACTED`
- PaymentService.php `EXTRACTED`
- FeeWorkflowController.php `EXTRACTED`
- TgiPayController.php `EXTRACTED`
- PaymentsPage.php `EXTRACTED`
- PaymentApiController.php `EXTRACTED`
- DocumentService.php `EXTRACTED`
- PaymentPolicy.php `EXTRACTED`

### inherits
- Illuminate\Database\Eloquent\Model `EXTRACTED`

### method
- .getActivitylogOptions() `EXTRACTED`
- .booted() `EXTRACTED`
- .student() `EXTRACTED`
- .user() `EXTRACTED`
- .invoice() `EXTRACTED`
- .course() `EXTRACTED`

### mixes_in
- Illuminate\Database\Eloquent\Factories\HasFactory `EXTRACTED`
- Spatie\Activitylog\Traits\LogsActivity `EXTRACTED`

### references
- .show() `EXTRACTED`
- .formatPayment() `EXTRACTED`
- .receipt() `EXTRACTED`
- .downloadStudentReceipt() `EXTRACTED`
- .markAdviceAsPaid() `EXTRACTED`
- .downloadReceipt() `EXTRACTED`
- .delete() `EXTRACTED`
- .update() `EXTRACTED`
- .view() `EXTRACTED`
- .generateReceiptPdf() `EXTRACTED`
- .receiptPath() `EXTRACTED`
- .generateReceiptNumber() `EXTRACTED`

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*