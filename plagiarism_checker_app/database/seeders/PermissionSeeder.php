<?php

namespace Database\Seeders;

use App\Traits\PermissionGenerator;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    use PermissionGenerator;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            UserAccountPermissionSeeder::class,
            UserRolePermissionSeeder::class,
        ]);
    }
}
