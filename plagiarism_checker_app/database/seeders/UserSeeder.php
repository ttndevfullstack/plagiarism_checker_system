<?php

namespace Database\Seeders;

use App\Models\User;
use App\Traits\SeedValidator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use SeedValidator; 

    public function run(): void
    {
        if ($this->isSeeded(User::class)) {
            return;
        }

        $admin = User::firstOrCreate([
            'email' => config('plagiarism-checker.admin_accounts.email'),
        ], [
            'first_name' => 'I am',
            'last_name' => 'Admin',
            'full_name' => 'I am Admin',
            'email' => config('plagiarism-checker.admin_accounts.email'),
            'password' => Hash::make(config('plagiarism-checker.admin_accounts.password')),
            'dob' => now()->subYears(30)->toDateString(),
            'phone' => '1234567890',
            'address' => 'Admin Address',
            'is_admin' => true,
        ]);

        $admin->assignRole(User::ADMIN_ROLE);

        $users = User::factory()->count(100)->create();
        $teachers = $users->take(10);
        $students = $users->skip(10)->take(90);

        foreach ($teachers as $teacher) {
            $teacher->assignRole(User::TEACHER_ROLE);
        }

        foreach ($students as $student) {
            $student->assignRole(User::STUDENT_ROLE);
        }
    }
}