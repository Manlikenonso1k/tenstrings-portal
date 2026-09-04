<?php
namespace App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Spatie\Permission\Models\Role;
class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! auth()->user()?->isSuperAdmin() && ($data['role'] ?? null) === 'super_admin') { $data['role'] = 'admin'; }
        return $data;
    }
    protected function afterCreate(): void
    {
        if (Role::query()->where('name', $this->record->role)->where('guard_name', 'web')->exists()) { $this->record->syncRoles([$this->record->role]); }
    }
}