<?php

namespace App\Filament\Resources\ClassRoomResource\Pages;

use App\Filament\Resources\ClassRoomResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Colors\Color;
use App\Filament\Resources\ClassRoomResource\Widgets\ExamListWidget;
use App\Filament\Resources\ClassRoomResource\Widgets\StudentListWidget;

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

    protected function getFooterWidgets(): array
    {
        if (auth()->user()->isStudent()) {
            return [ExamListWidget::make(['record' => $this->record])];    
        }

        return [
            StudentListWidget::make(['record' => $this->record]),
            ExamListWidget::make(['record' => $this->record]),
        ];
    }
}
