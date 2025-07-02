<?php

namespace App\Jobs;

use App\Models\PlagiarismHistory;
use App\Services\PlagiarismService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessSubmitDocument implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected ?int $mediaId = null;

    protected array $metadata = [];

    public function __construct(int $mediaId, array $metadata)
    {
        $this->mediaId = $mediaId;
        $this->metadata = $metadata;
    }

    public function handle(): void
    {
        $response = app(PlagiarismService::class)->checkPlagiarismByFile($this->getStoragePathFromUrl(media_path_by_id($this->mediaId)));

        $data = $response['results']['data'];
        if ($response['results']['status'] && ! empty($data)) {
            $plagiarismHistory = new PlagiarismHistory();
            $plagiarismHistory->document_id = $this->metadata['document_id'] ?? null;
            $plagiarismHistory->subject_id = $this->metadata['subject_id'] ?? null;
            $plagiarismHistory->class_id = $this->metadata['class_id'] ?? null;
            $plagiarismHistory->exam_id = $this->metadata['exam_id'] ?? null;
            $plagiarismHistory->originality_score = $data['originality_score'] ?? 0;
            $plagiarismHistory->similarity_score = $data['similarity_score'] ?? 0;
            $plagiarismHistory->source_matched = $data['source_matched'] ?? 0;
            $plagiarismHistory->words_analyzed = $data['words_analyzed'] ?? 0;
            $plagiarismHistory->encoded_file = $response['encoded_file'];
            $plagiarismHistory->results = $response['results'];
            $plagiarismHistory->save();
        }
    }

    public function getStoragePathFromUrl($url): string
    {
        $pos = strpos($url, '/storage/');
        if ($pos === false) {
            throw new \Exception("URL does not contain '/storage/' segment.");
        }

        $relative = substr($url, $pos + strlen('/storage/'));
        return storage_path('app/public/' . $relative);
    }
}
