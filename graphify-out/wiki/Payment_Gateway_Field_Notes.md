# Payment Gateway Field Notes

> 49 nodes · cohesion 0.05

## Key Concepts

- **inventory_items table** (7 connections) — `prompts/error.md`
- **Titan webhook/callback handler** (6 connections) — `prompts/agent-one.md`
- **Filament\Actions\Imports\Jobs\ImportCsv** (6 connections) — `prompts/error.md`
- **Duplicate inventory_rooms rows created per CSV row** (5 connections) — `prompts/error.md`
- **QueryException: Unknown column 'tenstrings_office_name' on inventory_items insert** (5 connections) — `prompts/error.md`
- **InventoryItemResource (Filament)** (5 connections) — `prompts/ui_prompt.md`
- **PAYMENT_KNOWLEDGE.md deliverable** (4 connections) — `prompts/agent-one.md`
- **TGI Titan payment gateway** (4 connections) — `prompts/agent-one.md`
- **inventory_rooms table** (4 connections) — `prompts/error.md`
- **InventoryTransfer model and migration** (4 connections) — `prompts/ui_prompt.md`
- **config/services.php gateway config keys** (3 connections) — `prompts/agent-one.md`
- **Payment model / payments table** (3 connections) — `prompts/agent-one.md`
- **Paystack payment gateway** (3 connections) — `prompts/agent-one.md`
- **Paystack server-side verification** (3 connections) — `prompts/agent-one.md`
- **CSV helper fields leaking into Eloquent insert** (3 connections) — `prompts/error.md`
- **Filament\Actions\Imports\Importer** (3 connections) — `prompts/error.md`
- **POST /livewire/update (default.livewire.update)** (3 connections) — `prompts/error.md`
- **CSV column 'TENSTRINGS OFFICE NAME'** (3 connections) — `prompts/error.md`
- **No Filament core Blade view overrides** (3 connections) — `prompts/ui_prompt.md`
- **Collapsible grouping by room, collapsed by default** (3 connections) — `prompts/ui_prompt.md`
- **Payment knowledge extraction task (read-only agent brief)** (2 connections) — `prompts/agent-one.md`
- **Read-only extraction constraint** (2 connections) — `prompts/agent-one.md`
- **Titan HMAC/signature construction** (2 connections) — `prompts/agent-one.md`
- **Duplicate login_sessions insert on a single request** (2 connections) — `prompts/error.md`
- **Case-insensitive duplicate item guard (LOWER(name) per room)** (2 connections) — `prompts/error.md`
- *... and 24 more nodes in this community*

## Relationships

- [WhatsApp Reminder Stack](WhatsApp_Reminder_Stack.md) (1 shared connections)

## Source Files

- `prompts/agent-one.md`
- `prompts/error.md`
- `prompts/ui_prompt.md`

## Audit Trail

- EXTRACTED: 43 (69%)
- INFERRED: 18 (29%)
- AMBIGUOUS: 1 (2%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*