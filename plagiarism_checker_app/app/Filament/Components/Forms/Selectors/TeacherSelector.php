<?php

namespace App\Filament\Components\Forms\Selectors;

use App\Models\Teacher;
use Filament\Forms\Components\Select;

class TeacherSelector extends Select
{
    public static function show(): Select
    {
        return static::make('teacher_id')
            ->label(__('Teacher'))
            ->required()
            ->options(Teacher::with('user')->get()->pluck('user.full_name', 'id'));
    }

    public function moreInfo(): Select
    {
        return $this->options(
            Teacher::all()->mapWithKeys(static function ($model) {
                return [
                    $model->id => sprintf(
                        '%s - %s - Total Classes: %s',
                        $model->user->full_name,
                        $model->user->email,
                        $model->assignments()->count()
                    ),
                ];
            })
        );
    }
}
