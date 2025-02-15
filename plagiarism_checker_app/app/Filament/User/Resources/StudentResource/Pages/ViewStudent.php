<?php

namespace App\Filament\User\Resources\StudentResource\Pages;

use App\Filament\User\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Colors\Color;

class ViewStudent extends ViewRecord
{
    protected static string $resource = StudentResource::class;

    /**
     * @return \Traversable<int, \Filament\Resources\Pages\Actions\Action> 
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->color(Color::Blue),
            Actions\DeleteAction::make(),
        ];
    }
}
