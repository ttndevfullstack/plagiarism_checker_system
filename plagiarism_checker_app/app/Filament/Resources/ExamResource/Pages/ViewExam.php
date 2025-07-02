<?php

namespace App\Filament\Resources\ExamResource\Pages;

use App\Filament\Resources\ClassRoomResource\Widgets\SubmittedDocumentListWidget;
use App\Filament\Resources\ExamResource;
use Filament\Resources\Pages\ViewRecord;

class ViewExam extends ViewRecord
{
    protected static string $resource = ExamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\EditAction::make(),
            \Filament\Actions\DeleteAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            SubmittedDocumentListWidget::make(['record' => $this->record, 'classroom' => $this->record->class]),
        ];
    }
}
