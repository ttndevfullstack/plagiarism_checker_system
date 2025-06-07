<?php

namespace App\Jobs;

use App\Enums\DocumentStatus;
use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProcessDocumentUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Document $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function handle()
    {
        $this->document->update(['status' => DocumentStatus::PROCESSING]);

        $media = $this->document->media;
        $filePath = Storage::disk($media->disk)->path($media->path);
        
        if (!file_exists($filePath) || !is_readable($filePath)) {
            $this->document->update(['status' => DocumentStatus::FAILED]);
            echo "File not exits at this path: $filePath";
            return;
        }

        $this->processFile($filePath);
    }

    private function processFile(string $filePath): void
    {
        $request = Http::withHeaders([
            'Accept' => 'application/json'
        ]);

        // Attach file with correct filename parameter
        $request->attach(
            'files', 
            fopen($filePath, 'r'),
            $this->document->original_name,
            ['Content-Type' => mime_content_type($filePath)]
        );

        $response = $request->post(
            rtrim(env('FLASK_APP_URL', 'http://localhost:5000'), '/') . '/v1/api/data/upload',
            [
                'document_id' => $this->document->id,
                'subject_code' => $this->document->subject?->code ?? '',
                'original_name' => $this->document->original_name ?? '',
                'metadata' => json_encode($this->document->metadata ?? [])
            ]
        );

        $this->updateDocumentStatus($response->successful());
    }

    private function updateDocumentStatus($successful)
    {
        $status = $successful ? DocumentStatus::COMPLETED : DocumentStatus::FAILED;
        $this->document->update(['status' => $status]);
    }
}
