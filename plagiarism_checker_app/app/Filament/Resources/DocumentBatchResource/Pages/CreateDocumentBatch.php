<?php

namespace App\Filament\Resources\DocumentBatchResource\Pages;

use App\Enums\DocumentStatus;
use App\Filament\Resources\DocumentBatchResource;
use App\Imports\DocumentImport;
use App\Jobs\ProcessDocumentBatch;
use App\Services\Documents\DocumentBatchService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class CreateDocumentBatch extends CreateRecord
{
    protected static string $resource = DocumentBatchResource::class;

    protected function afterCreate(): void
    {
        $originalFile = $this->record->media;
        $catalogFile = $this->record->mediaPath;

        try {
            $originFilePath = Storage::disk($originalFile->disk)->path($originalFile->path);

            $extractPath = app(DocumentBatchService::class)->extractFileAndArchive($originFilePath);

            // Process Excel file
            $catalogFilePath = Storage::disk($catalogFile->disk)->path($catalogFile->path);
            Excel::import(new DocumentImport($this->record, $extractPath), $catalogFilePath);

            ProcessDocumentBatch::dispatch($this->record);

        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        } finally {
            Storage::deleteDirectory($extractPath);
        }
    }
}
