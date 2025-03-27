<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Subject;
use App\Models\Document;
use App\Traits\SeedValidator;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    use SeedValidator;

    public function run(): void
    {
        if ($this->isSkipSeed(Document::class)) {
            return;
        }

        Document::factory(50)
            ->create([
                'subject_id' => fn() => Subject::inRandomOrder()->first()->subject_id,
                'created_by' => fn() => User::inRandomOrder()->first()->id,
            ]);
    }
}
