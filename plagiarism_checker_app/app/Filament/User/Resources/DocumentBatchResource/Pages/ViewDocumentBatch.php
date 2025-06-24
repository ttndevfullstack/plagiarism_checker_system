<?php

namespace App\Filament\User\Resources\DocumentBatchResource\Pages;

use App\Filament\User\Resources\DocumentBatchResource;
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
