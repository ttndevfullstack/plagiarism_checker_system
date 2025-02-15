<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\ClassRoom;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();
        $classes = ClassRoom::all();

        foreach ($students as $student) {
            // Enroll each student in 1-3 classes
            $selectedClasses = $classes->random(rand(1, 3));

            foreach ($selectedClasses as $class) {
                Enrollment::updateOrCreate([
                    'student_id' => $student->id,
                    'class_id' => $class->id,
                ], [
                    'enrollment_date' => now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }
}
