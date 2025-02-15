<?php

namespace App\Filament\User\Resources\StudentResource\Pages;

use App\Filament\User\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

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
