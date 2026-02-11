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
                    'permission.view',
                    'permission.view.any',
                    'permission.create',
                    'permission.update',
                    'permission.delete',
                    'role.view',
                    'role.view.any',
                    'role.create',
                    'role.update',
                    'role.delete',
                    'user.view',
                    'user.view.any',
                    'user.create',
                    'user.update',
                    'user.delete',
                    'company.view',
                    'company.view.any',
                    'company.create',
                    'company.update',
                    'company.update.any',
                    'company.delete',
                    'company.delete.any',
                    'country.view',
                    'country.view.any',
                    'country.create',
                    'country.update',
                    'country.delete',
                    'event.view',
                    'event.view.any',
                    'event.create',
                    'event.update',
                    'event.delete',
                    'package.view',
                    'package.view.any',
                    'package.create',
                    'package.update',
                    'package.delete',
                    'meeting.view',
                    'meeting.view.any',
                    'meeting.create',
                    'meeting.update',
                    'meeting.delete',
                    'jobrun.view',
                    'jobrun.view.any',
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
                    'country.view',
                    'country.view.any',
                    'country.create',
                    'country.update',
                    'country.delete',
                    'event.view',
                    'event.view.any',
                    'event.create',
                    'event.update',
                    'event.delete',
                    'package.view',
                    'package.view.any',
                    'package.create',
                    'package.update',
                    'package.delete',
                    'meeting.view',
                    'meeting.view.any',
                    'meeting.create',
                    'meeting.update',
                    'meeting.delete',
                    'jobrun.view',
                    'jobrun.view.any',
                ],
            ],
            [
                'name' => 'Sales',
                'slug' => 'sales',
                'description' => 'Sales team member with access to leads and meetings',
                'permissions' => [
                    'company.view',
                    'company.view.any',
                    'company.create',
                    'company.update',
                    'meeting.view',
                    'meeting.view.any',
                    'meeting.create',
                    'meeting.update',
                    'event.view',
                    'event.view.any',
                    'package.view',
                    'package.view.any',
                    'country.view',
                    'country.view.any',
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
