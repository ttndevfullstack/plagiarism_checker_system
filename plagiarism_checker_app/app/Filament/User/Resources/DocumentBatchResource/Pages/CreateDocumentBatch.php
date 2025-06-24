<?php

namespace App\Filament\User\Resources\DocumentBatchResource\Pages;

use App\Filament\User\Resources\DocumentBatchResource;
use App\Imports\DocumentImport;
use App\Jobs\ProcessDocumentBatch;
use App\Models\Subject;
use App\Services\Documents\DocumentBatchService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class CreateDocumentBatch extends CreateRecord
{
    protected static string $resource = DocumentBatchResource::class;

    protected ?int $subject_id = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($data['subject_id'] ?? null) {
            $this->subject_id = $data['subject_id']; 
        }
        return $data;
    }

    protected function afterCreate(): void
    {
        $originalFile = $this->record->media;
        $catalogFile = $this->record->mediaPath ?? null;
        $extractPath = null;

        try {
            DB::beginTransaction();

            $originFilePath = Storage::disk($originalFile->disk)->path($originalFile->path);
            $extractPath = app(DocumentBatchService::class)->extractFileAndArchive($originFilePath);

            if ($catalogFile) {
                $catalogFilePath = Storage::disk($catalogFile->disk)->path($catalogFile->path);
                Excel::import(new DocumentImport($this->record, $extractPath), $catalogFilePath);
            } else {
                // ---- AUTO SCAN FOLDER & IMPORT ALL FILES ----    
                $this->importAllDocumentsFromExtract($extractPath);
            }

            DB::commit();

            ProcessDocumentBatch::dispatch($this->record);
        } catch (\Throwable $th) {
            DB::rollBack();

            throw new \Exception($th->getMessage());
        } finally {
            $this->clearTempDirIfExist($extractPath);
        }
    }

    private function importAllDocumentsFromExtract(string $extractPath): void
    {
        $allFiles = $this->scanFilesRecursively($extractPath);

        if ($this->subject_id) {
            $subject = Subject::find($this->subject_id);
        } else {
            $subject = Subject::where('code', 'CNTT')->first();
        }

        foreach ($allFiles as $fileData) {
            [$relativePath, $fullPath] = $fileData;
            $prefix = dirname($relativePath) !== '.' ? dirname($relativePath) . '/' : '';
            $fileName = $prefix . basename($relativePath); // include folder as prefix

            $documentBatchDir = "media/document_batches/{$this->record->id}";
            $media = new (app(config('curator.model')));
            $media->disk = 'public';
            $media->directory = $documentBatchDir;
            $media->name = $fileName;
            $media->path = Storage::disk('public')->putFileAs($documentBatchDir, $fullPath, $fileName);
            $media->ext = pathinfo($fileName, PATHINFO_EXTENSION);
            $media->size = filesize($fullPath);
            $media->type = 'document';
            $media->save();

            \App\Models\Document::create([
                'class_id'      => null,
                'subject_id'    => $subject->id,
                'batch_id'      => $this->record->id,
                'media_id'      => $media->id,
                'original_name' => $fileName,
                'metadata'      => [
                    'subject_code' => $subject->code,
                ],
                'status'        => \App\Enums\DocumentStatus::PENDING,
            ]);
        }
    }

    /**
     * Recursively scan all files, returns array of [relative_path, full_path]
     */
    private function scanFilesRecursively(string $basePath): array
    {
        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($basePath));
        $files = [];
        foreach ($rii as $file) {
            if ($file->isDir()) continue;
            $relative = ltrim(str_replace($basePath, '', $file->getPathname()), DIRECTORY_SEPARATOR);
            $files[] = [$relative, $file->getPathname()];
        }
        return $files;
    }

    private function clearTempDirIfExist(string $extractPath): void
    {
        if (! $extractPath || ! is_dir($extractPath)) {
            return;
        }

        $this->forceDeleteDirectory($extractPath);
    }

    private function forceDeleteDirectory(string $dir): void
    {
        if (!file_exists($dir)) {
            return;
        }

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
