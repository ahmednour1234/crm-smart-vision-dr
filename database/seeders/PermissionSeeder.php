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
                $permission
            );
        }
    }
}
