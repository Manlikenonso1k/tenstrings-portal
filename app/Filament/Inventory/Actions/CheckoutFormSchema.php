<?php

namespace App\Filament\Inventory\Actions;

use App\Enums\ItemCondition;
use App\Enums\ReturnStatus;
use App\Models\InventoryCheckout;
use App\Models\InventoryCheckoutItem;
use App\Models\InventoryItem;
use App\Services\Inventory\InventoryCheckoutService;
use Filament\Forms;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Get;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

/**
 * The wizard steps shared by every entry point into a check-out or a return:
 * the item row action, the bulk action on All items, and the buttons on the
 * checkout page itself.
 *
 * Step 3 builds its per-line photo fields from the state of step 2 rather than
 * repeating the repeater, so each line's photos always land at a predictable
 * `lines.{index}.photos_*` path.
 */
class CheckoutFormSchema
{
    public static function photoMinimum(): int
    {
        return (int) config('inventory.checkout_photo_minimum', InventoryCheckoutService::MINIMUM_PHOTOS);
    }

    /**
     * @return array<int, Step>
     */
    public static function checkoutSteps(?int $branchId = null): array
    {
        return [
            Step::make('Event details')
                ->description('Where the instruments are going')
                ->schema([
                    Forms\Components\Hidden::make('branch_id')
                        ->default($branchId ?? Auth::user()?->branch_id),
                    Forms\Components\TextInput::make('event_name')
                        ->label('Event name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('event_venue')
                        ->label('Venue')
                        ->maxLength(255),
                    Forms\Components\DatePicker::make('event_date')
                        ->label('Event date')
                        ->default(now())
                        ->required(),
                    Forms\Components\DateTimePicker::make('expected_return_at')
                        ->label('Expected return')
                        ->seconds(false)
                        ->default(now()->addDay())
                        ->required()
                        ->after('now')
                        ->helperText('Must be in the future.'),
                    Forms\Components\TextInput::make('responsible_person_name')
                        ->label('Person taking the items')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('responsible_person_phone')
                        ->label('Their phone number')
                        ->tel()
                        ->maxLength(30),
                    Forms\Components\Textarea::make('notes')
                        ->label('Notes')
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Step::make('Items')
                ->description('What is leaving, and how many')
                ->schema([
                    Forms\Components\Repeater::make('lines')
                        ->label('Items')
                        ->live()
                        ->minItems(1)
                        ->defaultItems(1)
                        ->addActionLabel('Add another item')
                        ->schema([
                            Forms\Components\Select::make('inventory_item_id')
                                ->label('Item')
                                ->options(fn (): array => static::availableItemOptions())
                                ->searchable()
                                ->preload()
                                ->required()
                                ->live()
                                ->distinct()
                                ->helperText(function (Get $get): ?string {
                                    $item = static::findItem($get('inventory_item_id'));

                                    if (! $item instanceof InventoryItem) {
                                        return null;
                                    }

                                    return $item->availableQuantity() . ' of ' . $item->quantity . ' available'
                                        . ($item->room ? ' · ' . $item->room->name : '');
                                }),
                            Forms\Components\TextInput::make('quantity')
                                ->label('Quantity')
                                ->numeric()
                                ->minValue(1)
                                ->default(1)
                                ->required()
                                ->maxValue(function (Get $get): int {
                                    $item = static::findItem($get('inventory_item_id'));

                                    return $item instanceof InventoryItem ? max(1, $item->availableQuantity()) : 1;
                                }),
                        ])
                        ->columns(2),
                ]),

            Step::make('Condition and photos')
                ->description('At least ' . static::photoMinimum() . ' photos per item')
                ->schema([
                    Forms\Components\Group::make()
                        ->schema(fn (Get $get): array => static::checkoutPhotoSections($get('lines') ?? [])),
                ]),

            Step::make('Review and confirm')
                ->schema([
                    Forms\Components\Placeholder::make('summary')
                        ->label('')
                        ->content(fn (Get $get): HtmlString => static::checkoutSummary($get)),
                ]),
        ];
    }

    /**
     * @param  array<string, mixed>  $lines
     * @return array<int, Forms\Components\Section>
     */
    protected static function checkoutPhotoSections(array $lines): array
    {
        $sections = [];
        $index = 0;

        foreach ($lines as $key => $line) {
            $item = static::findItem($line['inventory_item_id'] ?? null);
            $label = $item?->name ?? 'Item ' . ($index + 1);

            $sections[] = Forms\Components\Section::make($label)
                ->description($item?->asset_tag ? 'Asset tag ' . $item->asset_tag : null)
                ->schema([
                    Forms\Components\Select::make("lines.{$key}.condition_out")
                        ->label('Condition going out')
                        ->options(ItemCondition::options())
                        ->default($item?->condition?->value ?? ItemCondition::Good->value)
                        ->required(),
                    Forms\Components\Textarea::make("lines.{$key}.notes_out")
                        ->label('Notes')
                        ->rows(2),
                    static::photoUpload("lines.{$key}.photos_out", 'Photos before it leaves')
                        ->columnSpanFull(),
                ])
                ->columns(2);

            $index++;
        }

        if ($sections === []) {
            $sections[] = Forms\Components\Section::make('No items yet')
                ->schema([
                    Forms\Components\Placeholder::make('empty')
                        ->label('')
                        ->content('Go back and add at least one item.'),
                ]);
        }

        return $sections;
    }

    /**
     * @return array<int, Step>
     */
    public static function returnSteps(InventoryCheckout $checkout): array
    {
        return [
            Step::make('What is coming back')
                ->description('Partial returns are fine')
                ->schema([
                    Forms\Components\CheckboxList::make('returning')
                        ->label('Items being returned')
                        ->live()
                        ->options(fn (): array => static::outstandingLineOptions($checkout))
                        ->required()
                        ->columns(1),
                    Forms\Components\Group::make()
                        ->schema(fn (Get $get): array => static::returnQuantityFields($checkout, $get('returning') ?? [])),
                ]),

            Step::make('Condition and photos')
                ->description('At least ' . static::photoMinimum() . ' photos per returning item')
                ->schema([
                    Forms\Components\Group::make()
                        ->schema(fn (Get $get): array => static::returnPhotoSections($checkout, $get('returning') ?? [])),
                ]),

            Step::make('Review and confirm')
                ->schema([
                    Forms\Components\Placeholder::make('summary')
                        ->label('')
                        ->content(fn (Get $get): HtmlString => static::returnSummary($checkout, $get)),
                ]),
        ];
    }

    /**
     * @param  array<int, int|string>  $selected
     * @return array<int, Forms\Components\TextInput>
     */
    protected static function returnQuantityFields(InventoryCheckout $checkout, array $selected): array
    {
        $fields = [];

        foreach (static::outstandingLines($checkout) as $line) {
            if (! in_array((string) $line->getKey(), array_map('strval', $selected), true)) {
                continue;
            }

            $fields[] = Forms\Components\TextInput::make("returns.{$line->getKey()}.returned_quantity")
                ->label($line->item?->name . ' — quantity coming back')
                ->numeric()
                ->minValue(1)
                ->maxValue($line->outstandingQuantity())
                ->default($line->outstandingQuantity())
                ->required()
                ->helperText($line->outstandingQuantity() . ' still out');
        }

        return $fields;
    }

    /**
     * @param  array<int, int|string>  $selected
     * @return array<int, Forms\Components\Section>
     */
    protected static function returnPhotoSections(InventoryCheckout $checkout, array $selected): array
    {
        $sections = [];

        foreach (static::outstandingLines($checkout) as $line) {
            if (! in_array((string) $line->getKey(), array_map('strval', $selected), true)) {
                continue;
            }

            $sections[] = Forms\Components\Section::make((string) ($line->item?->name ?? 'Item'))
                ->description('Went out as ' . ($line->condition_out?->label() ?? 'unknown'))
                ->schema([
                    Forms\Components\Placeholder::make("out_photos_{$line->getKey()}")
                        ->label('Photos when it left')
                        ->content(fn (): HtmlString => static::photoStrip($line)),
                    Forms\Components\Select::make("returns.{$line->getKey()}.return_status")
                        ->label('Return status')
                        ->options(ReturnStatus::options())
                        ->default(ReturnStatus::ReturnedOk->value)
                        ->required()
                        ->live(),
                    Forms\Components\Select::make("returns.{$line->getKey()}.condition_in")
                        ->label('Condition coming back')
                        ->options(ItemCondition::options())
                        ->default($line->condition_out?->value)
                        ->required(),
                    Forms\Components\Textarea::make("returns.{$line->getKey()}.notes_in")
                        ->label('Notes')
                        ->rows(2),
                    static::photoUpload("returns.{$line->getKey()}.photos_in", 'Photos on return')
                        ->columnSpanFull(),
                ])
                ->columns(2);
        }

        if ($sections === []) {
            $sections[] = Forms\Components\Section::make('Nothing selected')
                ->schema([
                    Forms\Components\Placeholder::make('empty')
                        ->label('')
                        ->content('Go back and pick at least one item.'),
                ]);
        }

        return $sections;
    }

    public static function photoUpload(string $statePath, string $label): Forms\Components\FileUpload
    {
        $minimum = static::photoMinimum();

        return Forms\Components\FileUpload::make($statePath)
            ->label($label)
            ->disk('public')
            ->directory('inventory/checkouts')
            ->image()
            ->multiple()
            ->reorderable(false)
            ->openable()
            ->minFiles($minimum)
            ->maxFiles(8)
            ->required()
            ->maxSize((int) config('inventory.photo_max_size', 4096))
            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
            // Opens the camera straight away on a phone, which is where this
            // is filled in — at the door, as the items go out.
            ->extraAttributes(['capture' => 'environment'])
            ->helperText("At least {$minimum} photos. This is the evidence trail and cannot be edited later.");
    }

    /**
     * @return \Illuminate\Support\Collection<int, InventoryCheckoutItem>
     */
    public static function outstandingLines(InventoryCheckout $checkout): \Illuminate\Support\Collection
    {
        return $checkout->lines()
            ->with('item')
            ->get()
            ->filter(fn (InventoryCheckoutItem $line): bool => ! $line->isFullyReturned())
            ->values();
    }

    /**
     * @return array<int, string>
     */
    protected static function outstandingLineOptions(InventoryCheckout $checkout): array
    {
        return static::outstandingLines($checkout)
            ->mapWithKeys(fn (InventoryCheckoutItem $line): array => [
                $line->getKey() => ($line->item?->name ?? 'Item')
                    . ' — ' . $line->outstandingQuantity() . ' still out'
                    . ($line->item?->asset_tag ? ' (' . $line->item->asset_tag . ')' : ''),
            ])
            ->all();
    }

    /**
     * Items in the acting user's branch that still have units on the shelf.
     *
     * @return array<int, string>
     */
    public static function availableItemOptions(): array
    {
        return InventoryItem::query()
            ->with('room')
            ->whereNotIn('status', \App\Enums\ItemStatus::blockedFromCheckout())
            ->orderBy('name')
            ->get()
            ->filter(fn (InventoryItem $item): bool => $item->availableQuantity() > 0)
            ->mapWithKeys(fn (InventoryItem $item): array => [
                $item->getKey() => $item->name
                    . ($item->asset_tag ? ' · ' . $item->asset_tag : '')
                    . ($item->room ? ' · ' . $item->room->name : ''),
            ])
            ->all();
    }

    protected static function findItem(mixed $id): ?InventoryItem
    {
        if (blank($id)) {
            return null;
        }

        return InventoryItem::query()->with('room')->find((int) $id);
    }

    protected static function photoStrip(InventoryCheckoutItem $line): HtmlString
    {
        $photos = $line->photosOut()->get();

        if ($photos->isEmpty()) {
            return new HtmlString('<span class="text-sm text-gray-500">No photos on record.</span>');
        }

        $html = '<div style="display:flex;gap:.5rem;flex-wrap:wrap">';

        foreach ($photos as $photo) {
            $url = e(\Illuminate\Support\Facades\Storage::disk('public')->url($photo->path));
            $html .= '<a href="' . $url . '" target="_blank" rel="noopener">'
                . '<img src="' . $url . '" style="width:88px;height:88px;object-fit:cover;border-radius:.5rem" />'
                . '</a>';
        }

        return new HtmlString($html . '</div>');
    }

    protected static function checkoutSummary(Get $get): HtmlString
    {
        $lines = $get('lines') ?? [];
        $rows = '';

        foreach ($lines as $key => $line) {
            $item = static::findItem($line['inventory_item_id'] ?? null);
            $photos = count(array_filter($line['photos_out'] ?? []));

            $rows .= '<li>' . e($item?->name ?? 'Item') . ' — '
                . e((string) ($line['quantity'] ?? 0)) . ' unit(s), '
                . e((string) $photos) . ' photo(s)</li>';
        }

        $html = '<div class="text-sm">'
            . '<p><strong>' . e((string) $get('event_name')) . '</strong>'
            . ($get('event_venue') ? ' at ' . e((string) $get('event_venue')) : '') . '</p>'
            . '<p>Released to ' . e((string) $get('responsible_person_name'))
            . ' · due back ' . e((string) $get('expected_return_at')) . '</p>'
            . '<ul style="margin-top:.5rem;list-style:disc;padding-left:1.25rem">' . $rows . '</ul>'
            . '</div>';

        return new HtmlString($html);
    }

    protected static function returnSummary(InventoryCheckout $checkout, Get $get): HtmlString
    {
        $returns = $get('returns') ?? [];
        $selected = array_map('strval', $get('returning') ?? []);
        $rows = '';

        foreach (static::outstandingLines($checkout) as $line) {
            if (! in_array((string) $line->getKey(), $selected, true)) {
                continue;
            }

            $data = $returns[$line->getKey()] ?? [];
            $status = ReturnStatus::tryFrom($data['return_status'] ?? '')?->label() ?? 'Returned OK';
            $photos = count(array_filter($data['photos_in'] ?? []));

            $rows .= '<li>' . e($line->item?->name ?? 'Item') . ' — '
                . e((string) ($data['returned_quantity'] ?? 0)) . ' back, '
                . e($status) . ', ' . e((string) $photos) . ' photo(s)</li>';
        }

        return new HtmlString(
            '<div class="text-sm"><p><strong>' . e((string) $checkout->reference) . '</strong> — '
            . e((string) $checkout->event_name) . '</p>'
            . '<ul style="margin-top:.5rem;list-style:disc;padding-left:1.25rem">' . $rows . '</ul></div>'
        );
    }

    /**
     * Reshape wizard state into the payload the service expects.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function toCheckoutPayload(array $data): array
    {
        $lines = [];

        foreach ($data['lines'] ?? [] as $line) {
            $lines[] = [
                'inventory_item_id' => (int) ($line['inventory_item_id'] ?? 0),
                'quantity' => (int) ($line['quantity'] ?? 0),
                'condition_out' => $line['condition_out'] ?? null,
                'notes_out' => $line['notes_out'] ?? null,
                'photos_out' => array_values($line['photos_out'] ?? []),
            ];
        }

        return [
            'branch_id' => $data['branch_id'] ?? Auth::user()?->branch_id,
            'event_name' => $data['event_name'],
            'event_venue' => $data['event_venue'] ?? null,
            'event_date' => $data['event_date'],
            'responsible_person_name' => $data['responsible_person_name'],
            'responsible_person_phone' => $data['responsible_person_phone'] ?? null,
            'expected_return_at' => $data['expected_return_at'],
            'notes' => $data['notes'] ?? null,
            'lines' => $lines,
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<int, array<string, mixed>>
     */
    public static function toReturnPayload(array $data): array
    {
        $selected = array_map('strval', $data['returning'] ?? []);
        $payload = [];

        foreach ($data['returns'] ?? [] as $lineId => $line) {
            if (! in_array((string) $lineId, $selected, true)) {
                continue;
            }

            $payload[] = [
                'line_id' => (int) $lineId,
                'returned_quantity' => (int) ($line['returned_quantity'] ?? 0),
                'condition_in' => $line['condition_in'] ?? null,
                'return_status' => $line['return_status'] ?? null,
                'notes_in' => $line['notes_in'] ?? null,
                'photos_in' => array_values($line['photos_in'] ?? []),
            ];
        }

        return $payload;
    }
}
