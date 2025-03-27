<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassRoomSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = Teacher::all();
        $subjects = Subject::all();

        if ($subjects->isEmpty()) {
            throw new \RuntimeException('No subjects found. Please run SubjectSeeder first.');
        }
        
        DB::beginTransaction();
        try {
            foreach ($teachers as $teacher) {
                $startDate = fake()->dateTimeBetween('now', '+2 months');
                $endDate = fake()->dateTimeBetween($startDate, '+6 months');
                
                ClassRoom::create([
                    'name' => 'Class ' . fake()->unique()->word,
                    'teacher_id' => $teacher->id,
                    'subject_id' => $subjects->random()->id,
                    'room_number' => fake()->unique()->numberBetween(1, 100),
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ]);

                if (fake()->boolean(60)) {
                    $startDate = fake()->dateTimeBetween('now', '+2 months');
                    $endDate = fake()->dateTimeBetween($startDate, '+6 months');
                    
                    ClassRoom::create([
                        'name' => 'Class ' . fake()->unique()->word,
                        'teacher_id' => $teacher->id,
                        'subject_id' => $subjects->random()->id,
                        'room_number' => fake()->unique()->numberBetween(101, 200),
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                    ]);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
