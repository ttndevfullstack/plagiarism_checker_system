<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
                $this->createClass($teacher, $subjects);

                if (fake()->boolean(60)) {
                    $this->createClass($teacher, $subjects, 100);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function createClass($teacher, $subjects, $roomOffset = 0): void
    {
        $startDate = fake()->dateTimeBetween('now', '+2 months');
        $endDate = fake()->dateTimeBetween($startDate, '+6 months');

        $startYear = $startDate->format('Y');
        $academicYear = $startYear . '-' . ($startYear + 1);

        ClassRoom::create([
            'name' => 'Class ' . Str::title(fake()->unique()->word),
            'teacher_id' => $teacher->id,
            'subject_id' => $subjects->random()->id,
            'room_number' => fake()->unique()->numberBetween(1 + $roomOffset, 100 + $roomOffset),
            'academic_year' => $academicYear,
            'status' => fake()->randomElement(['active', 'inactive']),
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }
}
