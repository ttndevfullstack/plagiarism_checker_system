<?php

namespace App\Filament\User\Resources\ClassRoomResource\Pages;

use App\Filament\User\Resources\ClassRoomResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClassRoom extends EditRecord
{
    protected static string $resource = ClassRoomResource::class;

    /**
     * @return array<string, \Filament\Actions\Action> 
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
