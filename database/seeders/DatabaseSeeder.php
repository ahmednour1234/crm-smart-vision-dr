<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@smartvisioneg.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );

        DB::table('countries')->updateOrInsert(
            ['iso2' => 'EG'],
            ['name' => 'Egypt', 'created_at' => now(), 'updated_at' => now()],
        );

        DB::table('countries')->updateOrInsert(
            ['iso2' => 'AE'],
            ['name' => 'UAE', 'created_at' => now(), 'updated_at' => now()],
        );

        DB::table('packages')->updateOrInsert(
            ['name' => 'Basic'],
            ['price' => 0, 'created_at' => now(), 'updated_at' => now()],
        );

        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);

        if (env('SEED_PERFORMANCE') === '1') {
            $this->call(PerformanceSeeder::class);
        }
    }
}
