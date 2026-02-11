<?php

namespace Database\Seeders;

use App\Models\Permission;
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
                'permissions' => [
                    'company.view',
                    'company.view.any',
                    'company.create',
                    'company.update',
                    'company.update.any',
                    'company.delete',
                    'company.delete.any',
                ],
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'description' => 'Management access with limited administrative permissions',
                'permissions' => [
                    'company.view',
                    'company.view.any',
                    'company.create',
                    'company.update',
                    'company.update.any',
                    'company.delete',
                    'company.delete.any',
                ],
            ],
            [
                'name' => 'Sales',
                'slug' => 'sales',
                'description' => 'Sales team member with access to leads and meetings',
                'permissions' => [
                    'company.view',
                    'company.create',
                    'company.update',
                ],
            ],
        ];

        foreach ($roles as $roleData) {
            $permissions = $roleData['permissions'];
            unset($roleData['permissions']);

            $role = Role::updateOrCreate(
                ['slug' => $roleData['slug']],
                $roleData
            );

            $permissionIds = Permission::whereIn('slug', $permissions)->pluck('id');
            $role->permissions()->sync($permissionIds);
        }
    }
}
