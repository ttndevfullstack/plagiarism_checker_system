<?php

namespace App\Filament\Resources\ExamResource\Pages;

use App\Filament\Resources\ExamResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListExams extends ListRecords
{
    protected static string $resource = ExamResource::class;

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
