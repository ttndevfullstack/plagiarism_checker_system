<?php

namespace App\Filament\Pages;

use App\Models\PlagiarismHistory;
use App\Services\PlagiarismService;
use Filament\Pages\Page;

class Loading extends Page
{
    protected static string $layout = 'filament-panels::components.layout.without-nav';

    protected static string $view = 'filament.pages.loading';

    public ?string $error = null;

    public $data = null;

    public function mount()
    {
        $this->data = json_decode(base64_decode(request()->get('data')), true);
    }

    public function generatePlagiarismReport(): void
    {
        $response = app(PlagiarismService::class)->checkPlagiarismByFile($this->data['file_path']);

        $data = $response['results']['data'];
        if ($response['results']['status'] && ! empty($data)) {
            $history = new PlagiarismHistory();
            $history->document_id = $this->metadata['document_id'] ?? null;
            $history->subject_id = $this->metadata['subject_id'] ?? null;
            $history->class_id = $this->metadata['class_id'] ?? null;
            $history->exam_id = $this->metadata['exam_id'] ?? null;
            $history->originality_score = $data['originality_score'] ?? 0;
            $history->similarity_score = $data['similarity_score'] ?? 0;
            $history->source_matched = $data['source_matched'] ?? 0;
            $history->words_analyzed = $data['words_analyzed'] ?? 0;
            $history->encoded_file = $response['encoded_file'];
            $history->results = $response['results'];
            $history->save();
        }
        
        $this->redirect(PlagiarismReport::getUrl(['history_id' => $history->id]));
    }

    protected function getViewData(): array
    {
        return [
            'error' => $this->error,
        ];
    }
}
