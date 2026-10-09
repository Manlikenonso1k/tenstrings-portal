# Send Whats App Message Job

> 10 nodes · cohesion 0.29

## Key Concepts

- **SendWhatsAppMessage** (10 connections) — `app/Jobs/SendWhatsAppMessage.php`
- **SendWhatsAppMessage.php** (8 connections) — `app/Jobs/SendWhatsAppMessage.php`
- **SendFeeReminders.php** (4 connections) — `app/Console/Commands/SendFeeReminders.php`
- **SendFeeReminders** (3 connections) — `app/Console/Commands/SendFeeReminders.php`
- **.handle()** (3 connections) — `app/Console/Commands/SendFeeReminders.php`
- **Illuminate\Contracts\Queue\ShouldQueue** (2 connections)
- **Illuminate\Foundation\Bus\Dispatchable** (2 connections)
- **Illuminate\Queue\InteractsWithQueue** (2 connections)
- **.__construct()** (1 connections) — `app/Jobs/SendWhatsAppMessage.php`
- **.handle()** (1 connections) — `app/Jobs/SendWhatsAppMessage.php`

## Relationships

- [Student Model & Observer](Student_Model_&_Observer.md) (2 shared connections)
- [Copy Sqlite To Mysql Command](Copy_Sqlite_To_Mysql_Command.md) (2 shared connections)
- [Checkout Notifications & Overdue](Checkout_Notifications_&_Overdue.md) (2 shared connections)
- [Branch Student Credentials Mail](Branch_Student_Credentials_Mail.md) (2 shared connections)
- [Payment Gateway Contracts](Payment_Gateway_Contracts.md) (1 shared connections)
- [TGIPay & Webhook Controllers](TGIPay_&_Webhook_Controllers.md) (1 shared connections)

## Source Files

- `app/Console/Commands/SendFeeReminders.php`
- `app/Jobs/SendWhatsAppMessage.php`

## Audit Trail

- EXTRACTED: 23 (100%)
- INFERRED: 0 (0%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*