<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\Subject;
use App\Models\User;
use App\Models\Classes;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        return [
            'class_id' => Classes::factory(),
            'subject_id' => Subject::factory(),
            'uploaded_by' => User::factory(),
            'media' => $this->faker->filePath(),
        ];
    }
}
