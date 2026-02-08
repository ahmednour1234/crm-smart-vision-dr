<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_active;
    }

    public function view(User $user, Company $company): bool
    {
        // Sales can list / view all leads; UI must redact sensitive fields for non-owned leads.
        return $user->is_active;
    }

    public function create(User $user): bool
    {
        return $user->is_active;
    }

    public function update(User $user, Company $company): bool
    {
        if (! $user->is_active) {
            return false;
        }

        return $user->role !== 'sales' || $company->owner_id === $user->id;
    }

    public function delete(User $user, Company $company): bool
    {
        if (! $user->is_active) {
            return false;
        }

        return $user->role !== 'sales' || $company->owner_id === $user->id;
    }
}
