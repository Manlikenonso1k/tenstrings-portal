Act as an expert in Laravel 11/12 and Filament v3.2. I need to update my inventory management system with a new UI layout and a transfer logging system.

1. Refactor InventoryItemResource Table Layout
Update the `table()` to display items in a 4-column grid using `->contentGrid(['md' => 2, 'xl' => 4])`. 
Group the items by their Room/Category using `->groups([Group::make('room.name')->collapsible()])`. By default, the grid should be collapsed so only the Room names are visible. When clicked, it should expand to show the items and their images in the 4-column layout.

2. Update InventoryRoomResource
Add a `Forms\Components\FileUpload::make('image')` to the `form()` so we can upload and edit category images directly.

3. Create Transfer Logging System
Create a new model and migration called `InventoryTransfer`. It should track: `item_id`, `destination` (event name or campus name), `type` (enum: internal, external_event, return), and `date`. 

4. Build the Action Modal
In the `InventoryItemResource`, add a custom `Action::make('transfer')` to the table. This action should open a modal form asking for the destination, type, and date. Upon submission, it should save a new `InventoryTransfer` record and update the item's status.

Strict Constraint: Do NOT publish or override Filament's core Blade views. Rely entirely on Filament's PHP Table Builder API for the grid and grouping. If custom CSS is absolutely required, use `FilamentView::registerRenderHook()` scoped specifically to `ListInventoryItems::class`.