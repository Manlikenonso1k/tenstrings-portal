<?php

namespace App\Policies;

use App\Models\InventoryAudit;
use App\Models\User;

class InventoryAuditPolicy
{
    use Concerns\ChecksBranchAccess;

    public function viewAny(User $user): bool
    {
        return $user->can('audit.view');
    }

    public function view(User $user, InventoryAudit $audit): bool
    {
        return $user->can('audit.view') && $this->sharesBranch($user, $audit->branch_id);
    }

    public function create(User $user): bool
    {
        return $user->can('audit.create');
    }

    public function update(User $user, InventoryAudit $audit): bool
    {
        return $user->can('audit.update')
            && $audit->isEditable()
            && $this->sharesBranch($user, $audit->branch_id);
    }

    public function complete(User $user, InventoryAudit $audit): bool
    {
        return $user->can('audit.complete')
            && $audit->isEditable()
            && $this->sharesBranch($user, $audit->branch_id);
    }

    public function delete(User $user, InventoryAudit $audit): bool
    {
        // Audits are the evidence trail — only the CEO removes one.
        return $user->isSuperAdmin();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isSuperAdmin();
    }
}
