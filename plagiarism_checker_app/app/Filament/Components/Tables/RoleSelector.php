<?php

namespace App\Filament\Components\Tables;

use Filament\Tables\Filters\SelectFilter;

class RoleSelector
{
    public static function show(): SelectFilter
    {
        return SelectFilter::make('role')
            ->label('Role')
            ->options(static fn () => Role::all()->pluck('name', 'id')->toArray())
            ->relationship('roles', 'name');
    }
}
