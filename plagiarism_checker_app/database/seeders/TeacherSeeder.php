<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use App\Traits\SeedValidator;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    use SeedValidator;
    
    public function run(): void
    {
        if ($this->isSeeded(Teacher::class)) {
            return;
        }

        $users = User::role(User::TEACHER_ROLE)->get();

        foreach ($users as $user) {
            $this->createTeacher($user);
        }
    }

    private function createTeacher(User $user): void
    {
        Teacher::updateOrCreate([
            'user_id' => $user->id,
        ], [
            'joined_date' => now()->subMonths(rand(1, 12)),
        ]);
    }
}
