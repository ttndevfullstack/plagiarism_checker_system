<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Traits\SeedValidator;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    use SeedValidator;

    public function run(): void
    {
        if ($this->isSkipSeed(Subject::class)) {
            return;
        }

        Subject::factory(10)->create();
    }
}
