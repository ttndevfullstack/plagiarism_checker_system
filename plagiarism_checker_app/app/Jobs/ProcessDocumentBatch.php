<?php

namespace App\Jobs;

use App\Enums\DocumentStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\DocumentBatch;

class ProcessDocumentBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $documentBatch;

    public function __construct(DocumentBatch $documentBatch)
    {
        $this->documentBatch = $documentBatch;
    }

    public function handle()
    {
        $this->documentBatch->update(['status' => DocumentStatus::PROCESSING]);

        try {
            foreach ($this->documentBatch->documents as $document) {
                ProcessDocumentUpload::dispatch($document);
            }

            $this->documentBatch->update(['status' => DocumentStatus::COMPLETED]);
        } catch (\Throwable $th) {
            $this->documentBatch->update(['status' => DocumentStatus::FAILED]);
            throw $th;
        }
    }
}
