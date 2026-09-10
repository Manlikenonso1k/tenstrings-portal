# Tenstrings Portal — Inventory: room grid, room photos, and event check-out/return flow

You are working on the Tenstrings Institute portal (Laravel 12 + Filament 3, Spatie permissions, 4 school branches). The inventory module already exists with three pages: Inventory Audits, Inventory Items, and Inventory Rooms. Right now Inventory Items is a single Filament table grouped by room (collapsible "Room: DIGITAL STUDIO", "Room: HR OFFICE" rows) with columns Photo, Name, Asset tag, Category, Quantity, Condition, Status.

We need three things: (1) replace the grouped list with a grid of rooms that each open their own page, (2) let branch managers change room photos, and (3) a proper check-out/return flow for items (mainly musical instruments) taken out for concerts and events, with compulsory before and after photos.

## Step 0 — Read before you write

Before changing anything, inspect and summarise back to me:

- The existing models, migrations, and relationships for inventory rooms, items, categories, and audits (table names, columns, enums/casts for `condition` and `status`).
- How images are stored today (plain `FileUpload` path column vs Spatie Media Library) and which disk.
- The existing roles and permissions (inventory officer, branch manager, CEO, admin, etc.), how branch scoping is enforced (global scope, policy, query filter), and the resource policies for inventory.
- Whether any "checked out"/"on loan" concept already exists.

Then propose the plan and the migration list, and wait for my go-ahead before running migrations. All migrations must be additive — no dropping or renaming columns that hold existing data.

## Step 1 — Fix the broken item thumbnails

In the current Items table every Photo cell shows a broken image. Find the cause before building anything that depends on images (likely candidates: missing `php artisan storage:link` on the server, files on a private disk, wrong `APP_URL`, or the column rendering a raw path instead of a URL). Fix it and tell me exactly what was wrong and whether any server command needs to be run on deploy.

## Step 2 — Rooms as a grid, each room with its own page

Replace the Inventory Items landing view with a grid of room cards.

Each card shows the room photo (cover, fixed aspect ratio ~4:3, with a neutral placeholder showing the room initial when no photo exists), the room name, the branch name, the total item count, and — only when non-zero — a small badge for items currently out at events. Grid: 1 column on mobile, 2 on tablet, 3–4 on desktop. Keep the existing search box and make it filter rooms by name; add a branch filter for users who can see more than one branch. The whole card is clickable.

Clicking a card opens the room page. Implement this as a View page on the Inventory Room resource (or a custom Filament page if that fits the existing structure better — explain your choice), with:

- A header area showing the room photo, name, branch, and item counts.
- The room's items in a table (reuse the current columns: Photo, Name, Asset tag, Category, Quantity, Condition, Status), searchable and filterable by category, condition, and status. A relation manager for items is fine.
- Row actions for "Check out for event" and "Return" (see Step 4), shown only when valid for that item's state and the user's permissions.
- "Add item" pre-fills the room.

Don't lose the ability to search across all items. Keep a flat "All items" table reachable from the grid page (a toggle or tab: "Rooms" / "All items"), so someone looking for "Yamaha keyboard" doesn't have to open rooms one by one.

Visual direction: stay inside the portal's existing dark Filament theme and amber accent — no new colour system. Let the room photos carry the page; keep the card chrome quiet (no heavy shadows, no gradients, no decorative labels). Use sentence case for UI text.

## Step 3 — Room photos, editable by branch managers

- Add a photo to inventory rooms (use whatever image approach the project already uses; if Media Library is installed, a `cover` single-file collection).
- Uploads: images only, max ~5 MB, resized server-side to around 1600px wide, with a thumbnail conversion for the grid.
- New permission, e.g. `inventory_room.update_photo`. Grant it to branch manager (plus admin/inventory officer if they already manage rooms).
- A branch manager can change the photo only for rooms in their own branch. Enforce this in the policy, not just by hiding the button.
- If branch managers don't otherwise have edit rights on rooms, give them a dedicated "Change photo" action (on the room card menu and the room page header) rather than the full edit form, so they can't alter room names or branches by accident.

## Step 4 — Check-out and return flow for events

Instruments leave for concerts and events, often many at once, and must come back in the same condition. Every check-out and every return must have photos.

### Data model

Model it as an event-level checkout with item lines, so one concert can cover many instruments and support partial returns:

`inventory_checkouts`
- `id`, `branch_id`, `reference` (human readable, e.g. `CO-2026-0042`)
- `event_name`, `event_venue`, `event_date`
- `responsible_person_name`, `responsible_person_phone` (the person taking the items — may not be a portal user)
- `expected_return_at`
- `checked_out_by` (user who released the items), `checked_out_at`
- `status`: `out`, `partially_returned`, `returned`, `overdue`
- `notes`, timestamps, soft deletes

`inventory_checkout_items`
- `id`, `inventory_checkout_id`, `inventory_item_id`, `quantity`
- `condition_out`, `notes_out`
- `condition_in` (nullable), `notes_in`, `returned_quantity`, `returned_at`, `received_by` (user)
- `return_status` (nullable): `returned_ok`, `returned_damaged`, `missing`
- Photos: two collections per line — `photos_out` and `photos_in` (or an `inventory_checkout_photos` table with a `stage` enum of `out`/`in` if the project doesn't use Media Library).

Use the existing condition values from items for `condition_out`/`condition_in` rather than inventing new ones.

### Availability rules

- Available quantity = item quantity − quantity currently out on unreturned lines. Show it in the item table and the checkout form.
- Block checking out more than is available, and block items whose status is anything like lost/under repair/disposed (check the existing status enum).
- When an item is fully out, its status shows as checked out (add a `checked_out` status if one doesn't exist); when everything is back, it returns to its previous status. If returned damaged, set the item's condition to what was recorded on return and flag it.
- Wrap checkout and return in DB transactions with row locks on the items so two people can't check out the last unit at the same time.

### Check-out flow (Filament wizard)

Triggered from: the item row action on the room page (pre-selects that item), the All items table (bulk action for multiple selected items), and a "New checkout" button on the Checkouts page.

1. **Event details** — event name, venue, date, responsible person name + phone, expected return date/time (must be after now).
2. **Items** — repeater of item + quantity, filtered to the user's branch, showing available quantity. Items can come from different rooms.
3. **Condition and photos** — for each item line: condition out, optional notes, and **at least 2 photos (required)**. The upload field should open the camera on phones (`accept="image/*"` with capture), since this will mostly be done on a phone at the door.
4. **Review and confirm** — summary of everything; confirm button reads "Check out items".

### Return flow (Filament wizard)

Triggered from the checkout's page ("Return items") and from the item row when that item is out.

1. Pick which lines are coming back (supports partial return) and the returned quantity.
2. For each returning line: **at least 2 return photos (required)**, condition in, return status (returned OK / damaged / missing), notes. Show the checkout photos alongside so the person receiving can compare before and after on the spot.
3. Review and confirm — button reads "Confirm return".

Checkout status updates automatically: all lines back → `returned`; some → `partially_returned`.

### Hard rules

- Photo requirements must be validated server-side (in the action/service), not just marked required in the form.
- Once a checkout or return is confirmed, its photos cannot be deleted or replaced by anyone below admin — this is the evidence trail. Admin changes should be logged.
- Put the logic in a service class (e.g. `InventoryCheckoutService` with `checkout()` and `return()`), called by the Filament actions, so the mobile API can use it later.

### Checkouts page and history

- New "Event checkouts" nav item under Inventory, listing checkouts with reference, event, branch, responsible person, item count, expected return, and status badge. Tabs: Out, Overdue, Returned, All.
- Checkout view page: event details, each item line with before/after photos side by side, who released it, who received it, and timestamps.
- On each item's page, a "Checkout history" relation manager showing every event that item has been to.

### Overdue and alerts

- A daily scheduled command marks checkouts past `expected_return_at` as `overdue`.
- Send a Filament database notification to the branch manager (and inventory officer) of that branch when a checkout goes overdue, and when an item comes back damaged or missing.

### Permissions

Add and wire through policies: `inventory_checkout.view`, `inventory_checkout.create`, `inventory_checkout.return`. Default grants: inventory officer and branch manager get all three for their own branch; CEO gets view across all branches. Everyone stays branch-scoped exactly the way existing inventory records are. Show me the final role → permission table before seeding.

## Acceptance checklist

- Item thumbnails render correctly in all tables.
- Inventory Items opens to a room grid with photos; clicking a room shows only that room's items; "All items" still searches everything.
- A branch manager can change the photo of a room in their branch, cannot for another branch, and cannot edit room names unless they already could.
- Checking out fails without 2+ photos per line (test both via the form and by calling the service directly).
- Can't check out more than the available quantity; concurrent checkouts of the last unit can't both succeed.
- Partial returns work; checkout status and item status update correctly.
- Returning damaged updates the item's condition and notifies the branch manager.
- Overdue command marks and notifies correctly.
- CEO can view checkouts across all branches but can't create or return.
- Pest (or PHPUnit, whichever the project uses) feature tests for the service, the policies, and the photo rules.

When done, give me: the list of migrations, new/changed files, the permission table, any deploy commands (storage link, scheduler, queue), and anything you weren't sure about.