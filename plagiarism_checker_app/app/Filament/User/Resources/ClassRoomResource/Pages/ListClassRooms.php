<?php

namespace App\Filament\User\Resources\ClassRoomResource\Pages;

use App\Filament\User\Resources\ClassRoomResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClassRooms extends ListRecords
{
    protected static string $resource = ClassRoomResource::class;

    /**
     * @return array<string, \Filament\Actions\Action> 
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
