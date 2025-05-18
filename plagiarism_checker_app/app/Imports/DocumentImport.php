<?php

namespace App\Imports;

use App\Enums\DocumentStatus;
use App\Models\Document;
use App\Models\DocumentBatch;
use App\Models\Subject;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;

class DocumentImport implements ToCollection, WithHeadingRow
{
    protected DocumentBatch $documentBatch;
    protected string $archivePath;
    protected array $processedFileNames = [];

    public function __construct(DocumentBatch $documentBatch, string $archivePath)
    {
        $this->documentBatch = $documentBatch;
        $this->archivePath = $archivePath;
    }

    public function collection(Collection $rows)
    {
        $cleanedData = $this->cleanData($rows);
        if (empty($cleanedData)) {
            return;
        }

        try {
            foreach ($cleanedData as $row) {
                if (
                    ! isset($row['file_name'])
                    || ! isset($row['major_code'])
                    || is_null($row['file_name'])
                    || is_null($row['major_code'])
                ) {
                    continue;
                }

                if ($this->isDuplicateFileName($row['file_name'])) {
                    Notification::make()
                        ->warning()
                        ->title('Duplicate File')
                        ->body("Skipping duplicate file: {$row['file_name']}")
                        ->send();
                    continue;
                }

                $subject = Subject::where('code', $row['major_code'])->first();
                $filePath = "{$this->archivePath}/{$row['file_name']}";
                if (! file_exists($filePath) || ! $subject) { continue; }
                
                // Add file name to processed list
                $this->processedFileNames[] = $row['file_name'];

                $documentBatchDir = "media/document_batches/{$this->documentBatch->id}";
                $media = new (app(config('curator.model')));
                $media->disk = 'public';
                $media->directory = $documentBatchDir;
                $media->name = $row['file_name'];
                $media->path = Storage::disk('public')->putFile($documentBatchDir, $filePath);
                $media->ext = pathinfo($filePath, PATHINFO_EXTENSION);
                $media->size = filesize($filePath);
                $media->type = 'document';
                $media->save();

                // Create document record linked to the batch
                Document::create([
                    'class_id' => null,
                    'subject_id' => $subject->id,
                    'batch_id' => $this->documentBatch->id,
                    'media_id' => $media->id,
                    'original_name' => $row['file_name'],
                    'metadata' => [
                        'subject_code' => $row['major_code'],
                        'metadata' => $row['metadata'] ?? null
                    ],
                    'status' => DocumentStatus::PENDING
                ]);
            }
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    private function isDuplicateFileName(string $fileName): bool
    {
        return in_array($fileName, $this->processedFileNames);
    }

    private function cleanData(Collection $rows): array
    {
        $cleanedData = [];

        foreach ($rows as $index => $row) {
            $rowArray = $row->toArray();

            if (array_filter($rowArray) === []) { continue; }
            if ($index === 0) { continue; }

            $cleanedData[] = [
                'document_id' => $rowArray[1] ?? null,
                'file_name' => $rowArray[2] ?? null,
                'major_code' => $rowArray[3] ?? null,
                'metadata' => $rowArray[4] ?? null,
            ];
        }

        return $cleanedData;
    }
}
