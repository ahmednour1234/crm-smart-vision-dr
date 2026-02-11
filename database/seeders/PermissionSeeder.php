<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'View Permissions',
                'slug' => 'permission.view',
                'resource' => 'permission',
                'description' => 'Can view permissions list',
            ],
            [
                'name' => 'View Any Permission',
                'slug' => 'permission.view.any',
                'resource' => 'permission',
                'description' => 'Can view any permission details',
            ],
            [
                'name' => 'Create Permission',
                'slug' => 'permission.create',
                'resource' => 'permission',
                'description' => 'Can create new permissions',
            ],
            [
                'name' => 'Update Permission',
                'slug' => 'permission.update',
                'resource' => 'permission',
                'description' => 'Can update permissions',
            ],
            [
                'name' => 'Delete Permission',
                'slug' => 'permission.delete',
                'resource' => 'permission',
                'description' => 'Can delete permissions',
            ],
            [
                'name' => 'View Companies',
                'slug' => 'company.view',
                'resource' => 'company',
                'description' => 'Can view companies list',
            ],
            [
                'name' => 'View Company',
                'slug' => 'company.view.any',
                'resource' => 'company',
                'description' => 'Can view any company details',
            ],
            [
                'name' => 'Create Company',
                'slug' => 'company.create',
                'resource' => 'company',
                'description' => 'Can create new companies',
            ],
            [
                'name' => 'Update Company',
                'slug' => 'company.update',
                'resource' => 'company',
                'description' => 'Can update companies',
            ],
            [
                'name' => 'Update Any Company',
                'slug' => 'company.update.any',
                'resource' => 'company',
                'description' => 'Can update any company (not just owned)',
            ],
            [
                'name' => 'Delete Company',
                'slug' => 'company.delete',
                'resource' => 'company',
                'description' => 'Can delete companies',
            ],
            [
                'name' => 'Delete Any Company',
                'slug' => 'company.delete.any',
                'resource' => 'company',
                'description' => 'Can delete any company (not just owned)',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                [
                    'name' => $permission['name'],
                    'slug' => $permission['slug'],
                    'resource' => $permission['resource'] ?? null,
                    'description' => $permission['description'] ?? null,
                ]
            );
        }
    }
}
