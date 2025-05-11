<?php

namespace App\Filament\User\Pages;

use App\Services\PlagiarismService;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use App\Services\DocumentParser;

class PlagiarismCheck extends Page
{
    protected static ?string $navigationIcon = 'heroicon-c-shield-check';

    protected static ?string $navigationLabel = 'Plagiarism Check';

    protected static ?string $navigationGroup = 'Plagiarism Management';

    protected static ?string $title = 'Plagiarism Check Page';

    protected static string $view = 'filament.pages.plagiarism-check';

    public $data = null;

    public $results = null;

    public $isLoading = true;

    public $error = null;

    public string $fileName = '';

    protected array $previewContent = [];

    protected array $highlightContent = [];

    public static function canAccess(): bool
    {
        return true;
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make()
                ->label('Check Plagiarism')
                ->url(\App\Filament\Resources\PlagiarismCheckerResource::getUrl('create')),
        ];
    }

    public function mount()
    {
        if (request()->has('data')) {
            $data = json_decode(base64_decode(request()->get('data')), true);
            
            $documentParser = new DocumentParser();
            $this->previewContent = $documentParser->parse($data['file_path'], $data['extension'], true);
            $cleanedContent = $documentParser->concatGroupedParagraphs($this->previewContent);
            
            $this->isLoading = false;
            $this->fileName = $data['filename'];

            $this->checkPlagiarism($cleanedContent);
        }
    }

    private function checkPlagiarism(array $content): void
    {
        if (empty($content)) { return; }

        try {
            $response = app(PlagiarismService::class)->checkPlagiarism($content);
            $this->results = $response['data'];
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            Notification::make()
                ->danger()
                ->title('Error')
                ->body($e->getMessage())
                ->send();
        }
    }

    protected function getViewData(): array
    {
        if ($this->results) {
            $this->highlightContent = $this->highlightTextColor();
        }

        return [
            'preview_content' => ! empty($this->highlightContent) ? $this->highlightContent : $this->previewContent,
            'filename' => $this->fileName,
            'results' => $this->results,
            'isLoading' => $this->isLoading,
            'error' => $this->error,
        ];
    }

    private function highlightTextColor(): array
    {
        $highlightContent = $this->previewContent;

        foreach ($highlightContent as &$section) {
            foreach ($section as $key => &$elements) {
                $paragraphResult = $this->getParagraphResultByKey($key);
                $paragraphPercent = collect($paragraphResult)->first()['similarity_percentage'] ?? null;
                $highlightClass = ! is_null($paragraphPercent) ? highlight_text_background($paragraphPercent) : '';

                foreach ($elements as &$element) {
                    if ($element['type'] === 'table') {
                        foreach ($element['rows'] as &$row) {
                            foreach ($row as &$cells) {
                                foreach ($cells as &$cell) {
                                    foreach ($cell['content'] as &$content) {
                                        $content['highlight'] = $highlightClass;
                                        $content['paragraph_result'] = $paragraphResult;
                                    }
                                }
                            }
                        }
                    } else {
                        foreach ($element['content'] as &$content) {
                            $content['highlight'] = $highlightClass;
                            $content['paragraph_result'] = $paragraphResult;
                        }
                    }
                }
            }
        }

        return $highlightContent;
    }

    private function getParagraphResultByKey(string $key): array
    {
        if (empty($this->results['paragraphs'])) { return []; }

        return array_filter($this->results['paragraphs'], fn($paragraph) => $paragraph['id'] == $key);
    }
}
