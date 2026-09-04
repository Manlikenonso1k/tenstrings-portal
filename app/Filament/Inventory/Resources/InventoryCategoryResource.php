<?php
namespace App\Filament\Inventory\Resources;
use App\Filament\Inventory\Resources\InventoryCategoryResource\Pages;
use App\Models\InventoryCategory;
use Filament\Forms; use Filament\Forms\Form; use Filament\Resources\Resource; use Filament\Tables; use Filament\Tables\Table;
class InventoryCategoryResource extends Resource {
 protected static ?string $model=InventoryCategory::class; protected static ?string $navigationIcon='heroicon-o-tag'; protected static ?string $navigationGroup='Inventory';
 public static function canViewAny(): bool { return auth()->user()?->can('category.manage') ?? false; } public static function canCreate(): bool { return auth()->user()?->can('category.manage') ?? false; } public static function canEdit($record): bool { return auth()->user()?->can('category.manage') ?? false; }
 public static function form(Form $form): Form { return $form->schema([Forms\Components\TextInput::make('name')->required(),Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord:true),Forms\Components\Textarea::make('description'),Forms\Components\Toggle::make('is_active')->default(true)]); }
 public static function table(Table $table): Table { return $table->columns([Tables\Columns\TextColumn::make('name')->searchable(),Tables\Columns\TextColumn::make('slug'),Tables\Columns\IconColumn::make('is_active')->boolean()])->actions([Tables\Actions\EditAction::make()])->bulkActions([]); }
 public static function getPages(): array { return ['index'=>Pages\ListInventoryCategories::route('/'),'create'=>Pages\CreateInventoryCategory::route('/create'),'edit'=>Pages\EditInventoryCategory::route('/{record}/edit')]; }
}