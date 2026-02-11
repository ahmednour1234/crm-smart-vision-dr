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
            [
                'name' => 'View Roles',
                'slug' => 'role.view',
                'resource' => 'role',
                'description' => 'Can view roles list',
            ],
            [
                'name' => 'View Any Role',
                'slug' => 'role.view.any',
                'resource' => 'role',
                'description' => 'Can view any role details',
            ],
            [
                'name' => 'Create Role',
                'slug' => 'role.create',
                'resource' => 'role',
                'description' => 'Can create new roles',
            ],
            [
                'name' => 'Update Role',
                'slug' => 'role.update',
                'resource' => 'role',
                'description' => 'Can update roles',
            ],
            [
                'name' => 'Delete Role',
                'slug' => 'role.delete',
                'resource' => 'role',
                'description' => 'Can delete roles',
            ],
            [
                'name' => 'View Users',
                'slug' => 'user.view',
                'resource' => 'user',
                'description' => 'Can view users list',
            ],
            [
                'name' => 'View Any User',
                'slug' => 'user.view.any',
                'resource' => 'user',
                'description' => 'Can view any user details',
            ],
            [
                'name' => 'Create User',
                'slug' => 'user.create',
                'resource' => 'user',
                'description' => 'Can create new users',
            ],
            [
                'name' => 'Update User',
                'slug' => 'user.update',
                'resource' => 'user',
                'description' => 'Can update users',
            ],
            [
                'name' => 'Delete User',
                'slug' => 'user.delete',
                'resource' => 'user',
                'description' => 'Can delete users',
            ],
            [
                'name' => 'View Countries',
                'slug' => 'country.view',
                'resource' => 'country',
                'description' => 'Can view countries list',
            ],
            [
                'name' => 'View Any Country',
                'slug' => 'country.view.any',
                'resource' => 'country',
                'description' => 'Can view any country details',
            ],
            [
                'name' => 'Create Country',
                'slug' => 'country.create',
                'resource' => 'country',
                'description' => 'Can create new countries',
            ],
            [
                'name' => 'Update Country',
                'slug' => 'country.update',
                'resource' => 'country',
                'description' => 'Can update countries',
            ],
            [
                'name' => 'Delete Country',
                'slug' => 'country.delete',
                'resource' => 'country',
                'description' => 'Can delete countries',
            ],
            [
                'name' => 'View Events',
                'slug' => 'event.view',
                'resource' => 'event',
                'description' => 'Can view events list',
            ],
            [
                'name' => 'View Any Event',
                'slug' => 'event.view.any',
                'resource' => 'event',
                'description' => 'Can view any event details',
            ],
            [
                'name' => 'Create Event',
                'slug' => 'event.create',
                'resource' => 'event',
                'description' => 'Can create new events',
            ],
            [
                'name' => 'Update Event',
                'slug' => 'event.update',
                'resource' => 'event',
                'description' => 'Can update events',
            ],
            [
                'name' => 'Delete Event',
                'slug' => 'event.delete',
                'resource' => 'event',
                'description' => 'Can delete events',
            ],
            [
                'name' => 'View Packages',
                'slug' => 'package.view',
                'resource' => 'package',
                'description' => 'Can view packages list',
            ],
            [
                'name' => 'View Any Package',
                'slug' => 'package.view.any',
                'resource' => 'package',
                'description' => 'Can view any package details',
            ],
            [
                'name' => 'Create Package',
                'slug' => 'package.create',
                'resource' => 'package',
                'description' => 'Can create new packages',
            ],
            [
                'name' => 'Update Package',
                'slug' => 'package.update',
                'resource' => 'package',
                'description' => 'Can update packages',
            ],
            [
                'name' => 'Delete Package',
                'slug' => 'package.delete',
                'resource' => 'package',
                'description' => 'Can delete packages',
            ],
            [
                'name' => 'View Meetings',
                'slug' => 'meeting.view',
                'resource' => 'meeting',
                'description' => 'Can view meetings list',
            ],
            [
                'name' => 'View Any Meeting',
                'slug' => 'meeting.view.any',
                'resource' => 'meeting',
                'description' => 'Can view any meeting details',
            ],
            [
                'name' => 'Create Meeting',
                'slug' => 'meeting.create',
                'resource' => 'meeting',
                'description' => 'Can create new meetings',
            ],
            [
                'name' => 'Update Meeting',
                'slug' => 'meeting.update',
                'resource' => 'meeting',
                'description' => 'Can update meetings',
            ],
            [
                'name' => 'Delete Meeting',
                'slug' => 'meeting.delete',
                'resource' => 'meeting',
                'description' => 'Can delete meetings',
            ],
            [
                'name' => 'View Job Runs',
                'slug' => 'jobrun.view',
                'resource' => 'jobrun',
                'description' => 'Can view job runs list',
            ],
            [
                'name' => 'View Any Job Run',
                'slug' => 'jobrun.view.any',
                'resource' => 'jobrun',
                'description' => 'Can view any job run details',
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
