<?php

namespace App\Filament\Resources\DocumentBatchResource\Pages;

use App\Filament\Resources\DocumentBatchResource;
use App\Imports\DocumentImport;
use App\Jobs\ProcessDocumentBatch;
use App\Services\Documents\DocumentBatchService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class CreateDocumentBatch extends CreateRecord
{
    protected static string $resource = DocumentBatchResource::class;

    protected function afterCreate(): void
    {
        $originalFile = $this->record->media;
        $catalogFile = $this->record->mediaPath;
        $extractPath = null;

        try {
            DB::beginTransaction();

            $originFilePath = Storage::disk($originalFile->disk)->path($originalFile->path);
            $catalogFilePath = Storage::disk($catalogFile->disk)->path($catalogFile->path);
            $extractPath = app(DocumentBatchService::class)->extractFileAndArchive($originFilePath);
            
            Excel::import(new DocumentImport($this->record, $extractPath), $catalogFilePath);

            DB::commit();

            ProcessDocumentBatch::dispatch($this->record);

        } catch (\Throwable $th) {
            DB::rollBack();

            throw new \Exception($th->getMessage());
        } finally {
            $this->clearTempDirIfExist($extractPath);
        }
    }

    private function clearTempDirIfExist(string $extractPath): void
    {
        if (! $extractPath || ! is_dir($extractPath)) { return; }
        
        $this->forceDeleteDirectory($extractPath);
    }

    private function forceDeleteDirectory(string $dir): void
    {
        if (!file_exists($dir)) { return; }

        $files = array_diff(scandir($dir), ['.', '..']);
        
        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            
            if (is_dir($path)) {
                $this->forceDeleteDirectory($path);
            } else {
                unlink($path);
            }
        }
        
        try {
            rmdir($dir);
        } catch (\Throwable $e) {
            Log::error("Failed to delete directory: {$dir}", ['error' => $e->getMessage()]);
        }
    }
}
