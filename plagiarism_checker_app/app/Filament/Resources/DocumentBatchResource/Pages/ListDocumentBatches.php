<?php

namespace App\Filament\Resources\DocumentBatchResource\Pages;

use App\Filament\Resources\DocumentBatchResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocumentBatches extends ListRecords
{
    protected static string $resource = DocumentBatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
