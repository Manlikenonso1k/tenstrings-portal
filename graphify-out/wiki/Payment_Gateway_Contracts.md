# Payment Gateway Contracts

> 24 nodes · cohesion 0.13

## Key Concepts

- **PaystackTitanGateway** (10 connections) — `app/Services/Payments/Gateways/PaystackTitanGateway.php`
- **TgiPayGateway** (10 connections) — `app/Services/Payments/Gateways/TgiPayGateway.php`
- **PaymentGatewayInterface** (8 connections) — `app/Contracts/PaymentGatewayInterface.php`
- **TgiPayGateway.php** (5 connections) — `app/Services/Payments/Gateways/TgiPayGateway.php`
- **PaystackTitanGateway.php** (4 connections) — `app/Services/Payments/Gateways/PaystackTitanGateway.php`
- **.secretKey()** (4 connections) — `app/Services/Payments/Gateways/PaystackTitanGateway.php`
- **.__construct()** (3 connections) — `app/Http/Controllers/TgiPayController.php`
- **.baseUrl()** (3 connections) — `app/Services/Payments/Gateways/PaystackTitanGateway.php`
- **.initializePayment()** (3 connections) — `app/Services/Payments/Gateways/PaystackTitanGateway.php`
- **.verifyPayment()** (3 connections) — `app/Services/Payments/Gateways/PaystackTitanGateway.php`
- **.webhookSecret()** (3 connections) — `app/Services/Payments/Gateways/PaystackTitanGateway.php`
- **.baseUrl()** (3 connections) — `app/Services/Payments/Gateways/TgiPayGateway.php`
- **.initializePayment()** (3 connections) — `app/Services/Payments/Gateways/TgiPayGateway.php`
- **.integrationKey()** (3 connections) — `app/Services/Payments/Gateways/TgiPayGateway.php`
- **.verifyPayment()** (3 connections) — `app/Services/Payments/Gateways/TgiPayGateway.php`
- **Illuminate\Support\Facades\Http** (3 connections)
- **.isValidSignature()** (2 connections) — `app/Services/Payments/Gateways/PaystackTitanGateway.php`
- **Illuminate\Support\Arr** (2 connections)
- **PaymentGatewayInterface.php** (1 connections) — `app/Contracts/PaymentGatewayInterface.php`
- **.handleWebhook()** (1 connections) — `app/Contracts/PaymentGatewayInterface.php`
- **.initializePayment()** (1 connections) — `app/Contracts/PaymentGatewayInterface.php`
- **.verifyPayment()** (1 connections) — `app/Contracts/PaymentGatewayInterface.php`
- **.handleWebhook()** (1 connections) — `app/Services/Payments/Gateways/PaystackTitanGateway.php`
- **.handleWebhook()** (1 connections) — `app/Services/Payments/Gateways/TgiPayGateway.php`

## Relationships

- [TGIPay & Webhook Controllers](TGIPay_&_Webhook_Controllers.md) (6 shared connections)
- [Send Whats App Message Job](Send_Whats_App_Message_Job.md) (1 shared connections)

## Source Files

- `app/Contracts/PaymentGatewayInterface.php`
- `app/Http/Controllers/TgiPayController.php`
- `app/Services/Payments/Gateways/PaystackTitanGateway.php`
- `app/Services/Payments/Gateways/TgiPayGateway.php`

## Audit Trail

- EXTRACTED: 44 (100%)
- INFERRED: 0 (0%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*