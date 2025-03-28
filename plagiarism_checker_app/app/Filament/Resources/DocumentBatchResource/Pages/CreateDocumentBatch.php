<?php

namespace App\Filament\Resources\DocumentBatchResource\Pages;

use App\Filament\Resources\DocumentBatchResource;
use Filament\Resources\Pages\CreateRecord;
use App\Jobs\ProcessDocumentUpload;

class CreateDocumentBatch extends CreateRecord
{
    protected static string $resource = DocumentBatchResource::class;

    protected function afterCreate(): void
    {
        // ProcessDocumentUpload::dispatch($this->record);
    }
}
