<?php

namespace App\Filament\User\Resources\ClassRoomResource\Pages;

use App\Filament\User\Resources\ClassRoomResource;
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
            ExamListWidget::make(['record' => $this->record]),
            StudentListWidget::make(['record' => $this->record]),
        ];
    }
}
