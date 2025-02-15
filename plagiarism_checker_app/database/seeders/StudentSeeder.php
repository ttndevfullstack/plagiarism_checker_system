<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use App\Traits\SeedValidator;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    use SeedValidator;

    public function run(): void
    {
        if ($this->isSeeded(Student::class)) {
            return;
        }

        $users = User::role(User::STUDENT_ROLE)->get();

        foreach ($users as $user) {
            $this->createStudent($user);
        }
    }

    private function createStudent(User $user): void
    {
        Student::updateOrCreate([
            'user_id' => $user->id,
        ], [
            'student_code' => 'STU' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
            'enrollment_date' => now()->subMonths(rand(1, 12)),
        ]);
    }
}
