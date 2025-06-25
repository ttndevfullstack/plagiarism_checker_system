<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\User;
use App\Models\ClassRoom;
use App\Traits\SeedValidator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExamSeeder extends Seeder
{
    use SeedValidator;

    public function run(): void
    {
        if ($this->isSkipSeed(Exam::class)) {
            return;
        }

        $users = User::all();
        $classRooms = ClassRoom::all();

        if ($classRooms->isEmpty()) {
            throw new \RuntimeException('No classes found. Please run ClassRoomSeeder first.');
        }

        DB::beginTransaction();

        try {
            foreach ($classRooms as $classRoom) {
                $examCount = fake()->numberBetween(1, 2);
                for ($i = 0; $i < $examCount; $i++) {
                    // Generate exam start and end times within the class's date range
                    $classStart = $classRoom->start_date ? \Carbon\Carbon::parse($classRoom->start_date) : now();
                    $classEnd = $classRoom->end_date ? \Carbon\Carbon::parse($classRoom->end_date) : now()->addMonths(6);

                    // Ensure exam is within class period
                    $examStart = fake()->dateTimeBetween($classStart, $classEnd->copy()->subDay());
                    $examEnd = (clone $examStart)->modify('+' . fake()->numberBetween(1, 3) . ' hours');

                    // Prevent exam end after class end
                    if ($examEnd > $classEnd) {
                        $examEnd = $classEnd->copy();
                    }

                    Exam::create([
                        'class_id' => $classRoom->id,
                        'title' => fake()->sentence(4),
                        'description' => fake()->paragraph(),
                        'start_time' => $examStart,
                        'end_time' => $examEnd,
                        'uploaded_by' => $users->random()->id,
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
