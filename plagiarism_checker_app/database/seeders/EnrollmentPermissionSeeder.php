<?php

namespace Database\Seeders;

use App\Models\User;
use App\Traits\PermissionGenerator;
use Illuminate\Database\Seeder;

class EnrollmentPermissionSeeder extends Seeder
{
    use PermissionGenerator;

    public function run(): void
    {
        $this->seedResourcePermissions('class', 'enrollment')
            ->usingRoles(User::ADMIN_ROLE, User::TEACHER_ROLE)
            ->execute();
    }
}
