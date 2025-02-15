<?php

namespace Database\Seeders;

use App\Traits\PermissionGenerator;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    use PermissionGenerator;

    public function run(): void
    {

        $this->call([
            UserAccountPermissionSeeder::class,
            UserRolePermissionSeeder::class,
            StudentPermissionSeeder::class,
            TeacherPermissionSeeder::class,
            ClassRoomPermissionSeeder::class,
            AssignmentPermissionSeeder::class,
            EnrollmentPermissionSeeder::class,
        ]);
    }
}
