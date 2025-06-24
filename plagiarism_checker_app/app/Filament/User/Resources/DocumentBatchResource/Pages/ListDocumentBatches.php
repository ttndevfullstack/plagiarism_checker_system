<?php

namespace App\Filament\User\Resources\DocumentBatchResource\Pages;

use App\Filament\User\Resources\DocumentBatchResource;
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
