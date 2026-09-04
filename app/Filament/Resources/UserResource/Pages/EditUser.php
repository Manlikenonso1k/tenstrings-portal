<?php
namespace App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Spatie\Permission\Models\Role;
class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (! auth()->user()?->isSuperAdmin() && ($data['role'] ?? null) === 'super_admin') { $data['role'] = $this->record->role === 'super_admin' ? 'admin' : ($data['role'] ?? 'student'); }
        return $data;
    }
    protected function afterSave(): void
    {
        if (Role::query()->where('name', $this->record->role)->where('guard_name', 'web')->exists()) { $this->record->syncRoles([$this->record->role]); }
    }
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
}