<?php

namespace App\Filament\Resources\ClassRoomResource\Pages;

use App\Filament\Resources\ClassRoomResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Colors\Color;

class ViewClassRoom extends ViewRecord
{
    protected static string $resource = ClassRoomResource::class;

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
