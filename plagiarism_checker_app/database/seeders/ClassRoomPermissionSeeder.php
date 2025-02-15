<?php

namespace Database\Seeders;

use App\Models\User;
use App\Traits\PermissionGenerator;
use Illuminate\Database\Seeder;

class ClassRoomPermissionSeeder extends Seeder
{
    use PermissionGenerator;

    public function run(): void
    {
        $this->seedResourcePermissions('class', 'room')
            ->usingRoles(User::ADMIN_ROLE, User::TEACHER_ROLE)
            ->execute();
    }
}
