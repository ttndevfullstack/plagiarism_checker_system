<?php

namespace App\Filament\Components\Actions;

use Filament\Tables\Actions;
use Filament\Support\Colors\Color;

class BaseActionGroup
{
    /**
     * @return array<Filament\Tables\Actions>
     */
    public static function show(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\EditAction::make()->color(Color::Blue),
            Actions\DeleteAction::make(),
        ];
    }
}
