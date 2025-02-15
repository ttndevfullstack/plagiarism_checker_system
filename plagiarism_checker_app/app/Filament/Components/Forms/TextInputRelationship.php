<?php

namespace App\Filament\Components\Forms;

use Filament\Forms\Components\TextInput;

class TextInputRelationship
{
    public static function show(string $field, string $label, string $relationship): TextInput
    {
        return TextInput::make($field)
            ->label($label)
            ->maxLength(255)
            ->afterStateHydrated(static fn ($component, $record) => $component->state($record?->{$relationship}?->{$field}))
            ->dehydrated(static fn ($state) => filled($state))
            ->mutateDehydratedStateUsing(static fn ($state, $record) => tap($state, static fn () => $record?->{$relationship}?->update([$field => $state])));
    }
}
