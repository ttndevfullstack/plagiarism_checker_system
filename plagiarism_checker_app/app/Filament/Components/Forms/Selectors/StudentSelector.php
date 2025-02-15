<?php

namespace App\Filament\Components\Forms\Selectors;

use App\Models\Student;
use Filament\Forms\Components\Select;

class StudentSelector extends Select
{
    public static function show(): Select
    {
        return static::make('student_id')
            ->label(__('Student'))
            ->required()
            ->options(Student::with('user')->get()->pluck('user.full_name', 'id'));
    }
}
