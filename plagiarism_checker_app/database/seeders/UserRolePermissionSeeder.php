<?php

namespace Database\Seeders;

use App\Models\User;
use App\Traits\PermissionGenerator;
use Illuminate\Database\Seeder;

class UserRolePermissionSeeder extends Seeder
{
    use PermissionGenerator;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->seedResourcePermissions('user', 'role')
            ->usingRoles(User::ADMIN_ROLE)
            ->execute();
    }
}
