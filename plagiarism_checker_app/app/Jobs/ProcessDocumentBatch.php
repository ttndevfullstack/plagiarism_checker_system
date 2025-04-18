<?php

namespace App\Jobs;

use App\Enums\DocumentStatus;
use App\Models\DocumentBatch;

class ProcessDocumentBatch
{
    public static function dispatch(DocumentBatch $documentBatch)
    {
        $documentBatch->update(['status' => DocumentStatus::PROCESSING]);

        try {
            foreach ($documentBatch->documents as $document) {
                ProcessDocumentUpload::dispatch($document);
            }

            $documentBatch->update(['status' => DocumentStatus::COMPLETED]);
        } catch (\Throwable $th) {
            $documentBatch->update(['status' => DocumentStatus::FAILED]);
            throw $th;
        }
    }
}
