<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use App\Filament\Resources\TeacherResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Colors\Color;

class ViewTeacher extends ViewRecord
{
    protected static string $resource = TeacherResource::class;

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
