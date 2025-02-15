<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class ClassRoomSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = Teacher::all();
        
        foreach ($teachers as $teacher) {
            ClassRoom::create([
                'name' => 'Class ' . fake()->unique()->word,
                'teacher_id' => $teacher->id,
                'room_number' => fake()->unique()->numberBetween(1, 100),
            ]);

            // Create a second class for some teachers
            if (fake()->boolean(60)) {
                ClassRoom::create([
                    'name' => 'Class ' . fake()->unique()->word,
                    'teacher_id' => $teacher->id,
                    'room_number' => fake()->unique()->numberBetween(101, 200),
                ]);
            }
        }
    }
}
