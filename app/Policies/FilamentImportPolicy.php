<?php

namespace App\Policies;

use App\Models\User;
use Filament\Actions\Imports\Models\Import;

class FilamentImportPolicy
{
    public function view(User $user, Import $import): bool
    {
        return $user->id === $import->user_id || $user->isSuperAdmin();
    }
}
