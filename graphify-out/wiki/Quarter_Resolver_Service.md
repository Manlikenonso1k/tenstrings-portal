# Quarter Resolver Service

> 9 nodes · cohesion 0.33

## Key Concepts

- **QuarterResolver** (6 connections) — `app/Services/Payments/QuarterResolver.php`
- **.__construct()** (4 connections) — `app/Services/Payments/PaymentService.php`
- **.futureQuarter()** (4 connections) — `app/Services/Payments/QuarterResolver.php`
- **.currentQuarter()** (3 connections) — `app/Services/Payments/QuarterResolver.php`
- **Carbon\CarbonImmutable** (3 connections)
- **QuarterResolver.php** (2 connections) — `app/Services/Payments/QuarterResolver.php`
- **.quarterStartMonth()** (2 connections) — `app/Services/Payments/QuarterResolver.php`
- **CarbonImmutable** (2 connections)
- **Illuminate\Database\DatabaseManager** (2 connections)

## Relationships

- [TGIPay & Webhook Controllers](TGIPay_&_Webhook_Controllers.md) (3 shared connections)
- [Resource Create Pages](Resource_Create_Pages.md) (1 shared connections)

## Source Files

- `app/Services/Payments/PaymentService.php`
- `app/Services/Payments/QuarterResolver.php`

## Audit Trail

- EXTRACTED: 16 (100%)
- INFERRED: 0 (0%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*