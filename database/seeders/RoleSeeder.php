<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Full system access with all permissions',
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'description' => 'Management access with limited administrative permissions',
            ],
            [
                'name' => 'Sales',
                'slug' => 'sales',
                'description' => 'Sales team member with access to leads and meetings',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}
