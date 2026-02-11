<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            if (preg_match('/^\$2[ayb]\$/', $value) || preg_match('/^\$argon2/', $value)) {
                $this->attributes['password'] = $value;
            } else {
                $this->attributes['password'] = Hash::make($value);
            }
        }
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }

    public function roleModel()
    {
        return Role::where('slug', $this->role)->first();
    }

    public function hasPermission(string $permissionSlug): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $role = $this->roleModel();
        if (! $role) {
            return false;
        }

        return $role->hasPermission($permissionSlug);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if (! $this->is_active) {
            return false;
        }

        return match ($panel->getId()) {
            'admin' => in_array($this->role, ['admin', 'manager', 'sales'], true),
            'employee' => in_array($this->role, ['sales', 'admin', 'manager'], true),
            default => false,
        };
    }
}
