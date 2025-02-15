<?php

namespace Database\Seeders;

use App\Models\User;
use App\Traits\PermissionGenerator;
use Illuminate\Database\Seeder;

class TeacherPermissionSeeder extends Seeder
{
    use PermissionGenerator;

    public function run(): void
    {
        $this->seedResourcePermissions('user', 'teacher')
            ->usingRoles(User::ADMIN_ROLE)
            ->execute();
    }
}
