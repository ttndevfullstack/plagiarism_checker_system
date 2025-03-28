<?php

namespace App\Jobs;

use App\Models\Document;
use App\Models\DocumentStatus;
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

    protected $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function handle()
    {
        $this->document->update(['status' => DocumentStatus::PROCESSING]);
        
        $media = $this->document->media;
        $file = Storage::disk($media->disk)->path($media->path);
        
        if (in_array($media->ext, ['zip', 'rar'])) {
            $this->processArchive($file);
        } else {
            $this->processSingleFile($file);
        }
    }

    private function processSingleFile($file)
    {
        $response = Http::attach(
            'file', 
            file_get_contents($file), 
            $this->document->original_name
        )->post(env('FLASK_APP_URL') . '/data/upload', [
            'metadata' => $this->document->metadata
        ]);

        $this->updateDocumentStatus($response->successful());
    }

    private function processArchive($file)
    {
        // Process archive file
    }

    private function updateDocumentStatus($successful)
    {
        $status = $successful ? DocumentStatus::PROCESSED : DocumentStatus::FAILED;
        $this->document->update(['status' => $status]);
    }
}
