<?php

namespace App\Filament\Resources\DocumentBatchResource\Pages;

use App\Filament\Resources\DocumentBatchResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDocumentBatch extends ViewRecord
{
    protected static string $resource = DocumentBatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
