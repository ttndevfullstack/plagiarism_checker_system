<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\ClassRoom;
use Illuminate\Database\Seeder;

class AssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $classes = ClassRoom::with('teacher')->get();

        foreach ($classes as $class) {
            // Create 1-3 assignments per class
            $numAssignments = rand(1, 3);

            for ($i = 0; $i < $numAssignments; $i++) {
                Assignment::updateOrCreate([
                    'class_id' => $class->id,
                    'teacher_id' => $class->teacher_id,
                ], [
                    'assignment_date' => now()->addDays(rand(1, 30)),
                ]);
            }
        }
    }
}
