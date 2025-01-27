<?php

namespace Database\Seeders;

use App\Traits\PermissionGenerator;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    use PermissionGenerator;

    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Administrator with full access to manage the system.',
            ],
            [
                'name' => 'teacher',
                'description' => 'Teacher role with permissions to manage students and classes.',
            ],
            [
                'name' => 'student',
                'description' => 'Student role with access to view and participate in classes.',
            ],
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['name' => $roleData['name']],
                ['description' => $roleData['description']]
            );
        }
    }
}
