<?php

namespace Database\Seeders;

use App\Models\User;
use App\Traits\PermissionGenerator;
use Illuminate\Database\Seeder;

class AssignmentPermissionSeeder extends Seeder
{
    use PermissionGenerator;

    public function run(): void
    {
        $this->seedResourcePermissions('class', 'assignment')
            ->usingRoles(User::ADMIN_ROLE)
            ->execute();
    }
}
