<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            TeacherSeeder::class,
            StudentSeeder::class,
            ClassRoomSeeder::class,
            AssignmentSeeder::class,
            EnrollmentSeeder::class,
        ]);
    }
}
