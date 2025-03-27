<?php

namespace App\Filament\Components\Forms\Selectors;

use App\Models\Subject;
use Filament\Forms\Components\Select;

class SubjectSelector extends Select
{
    public static function show(): Select
    {
        return static::make('subject_id')
            ->label(__('Subject'))
            ->required()
            ->options(Subject::pluck('name', 'id'))
            ->searchable();
    }
}
