<?php

namespace App\Filament\Components\Forms\Selectors;

use App\Models\User;
use Filament\Forms\Components\Select;

class UserSelector
{
    public static function show(): Select
    {
        return Select::make('user_id')
            ->label(__('User'))
            ->required()
            ->options(User::all()->pluck('user.full_name', 'id')->toArray());
    }
}
