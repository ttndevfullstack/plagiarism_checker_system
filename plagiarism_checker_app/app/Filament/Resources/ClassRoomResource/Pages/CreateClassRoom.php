<?php

namespace App\Filament\Resources\ClassRoomResource\Pages;

use App\Filament\Resources\ClassRoomResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Enrollment;

class CreateClassRoom extends CreateRecord
{
    protected static string $resource = ClassRoomResource::class;

    protected function afterCreate(): void
    {
        $studentIds = $this->data['student_id'] ?? [];
        foreach ($studentIds as $studentId) {
            Enrollment::create([
                'student_id' => $studentId,
                'class_id' => $this->record->id,
                'enrollment_date' => now(),
            ]);
        }
    }
}
