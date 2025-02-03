<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
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

        User::factory()->count(100)->create();
    }
}
