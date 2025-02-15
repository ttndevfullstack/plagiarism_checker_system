<?php

namespace App\Filament\Components\Tables;

use App\Models\User;
use Filament\Forms\Filters\SelectFilter;

class TeacherSelector
{
    public static function show(): SelectFilter
    {
        return SelectFilter::make('teacher')
            ->label('Teacher')
            ->options(static fn () => User::query()->hasRole(User::TEACHER_ROLE)->pluck('full_name', 'id')->toArray())
            ->relationship('roles', 'name');
    }
}
