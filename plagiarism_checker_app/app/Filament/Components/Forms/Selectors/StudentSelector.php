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

    public static function showFullInfo($fieldName = 'student_id'): Select
    {
        $students = Student::with('user')->get()->mapWithKeys(function ($student) {
            $user = $student->user;
            $label = "{$user->full_name} - {$user->email} - {$user->phone}";
            return [$student->id => $label];
        });

        return static::make($fieldName)
            ->label(__('Student Details'))
            ->options($students);
    }
}
