<?php
namespace App\Filament\Inventory\Resources;
use App\Enums\AuditStatus;
use App\Filament\Inventory\Resources\InventoryAuditResource\Pages;
use App\Models\InventoryAudit;
use Filament\Forms; use Filament\Forms\Form; use Filament\Resources\Resource; use Filament\Tables; use Filament\Tables\Table;
class InventoryAuditResource extends Resource {
 protected static ?string $model = InventoryAudit::class; protected static ?string $navigationIcon='heroicon-o-clipboard-document-check'; protected static ?string $navigationGroup='Inventory';
 public static function canViewAny(): bool { return auth()->user()?->can('audit.view') ?? false; } public static function canCreate(): bool { return auth()->user()?->can('audit.create') ?? false; } public static function canEdit($record): bool { return auth()->user()?->can('update',$record) ?? false; }
 public static function form(Form $form): Form { return $form->schema([Forms\Components\Select::make('branch_id')->relationship('branch','name')->required(),Forms\Components\Select::make('inventory_room_id')->relationship('room','name')->searchable(),Forms\Components\TextInput::make('title')->required(),Forms\Components\DatePicker::make('scheduled_for')->required(),Forms\Components\Select::make('status')->options(AuditStatus::options())->default('draft')->required(),Forms\Components\Textarea::make('notes')->columnSpanFull()])->columns(2); }
 public static function table(Table $table): Table { return $table->columns([Tables\Columns\TextColumn::make('title')->searchable(),Tables\Columns\TextColumn::make('branch.name'),Tables\Columns\TextColumn::make('room.name'),Tables\Columns\TextColumn::make('scheduled_for')->date(),Tables\Columns\TextColumn::make('status')->badge(),Tables\Columns\TextColumn::make('completed_at')->dateTime()])->actions([Tables\Actions\EditAction::make()])->bulkActions([]); }
 public static function getPages(): array { return ['index'=>Pages\ListInventoryAudits::route('/'),'create'=>Pages\CreateInventoryAudit::route('/create'),'edit'=>Pages\EditInventoryAudit::route('/{record}/edit')]; }
}