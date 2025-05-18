<?php

namespace App\Filament\Pages;

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

    public bool $giveMeFile = false;

    public ?string $outputPath = null;

    public ?string $outputFileName = null;

    protected ?string $previewText = null;

    protected array $previewContent = [];

    protected array $highlightContent = [];

    protected ?\PhpOffice\PhpWord\PhpWord $phpWord = null;

    protected ?DocumentParser $documentParser = null;

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
            
            if ($data['file_path'] ?? null && $data['give_me_file'] ?? false) {   // Return a origin file
                // Get the filename with extension (basename)
                $filenameWithExt = basename($data['file_path']);
                $fileInfo = pathinfo($filenameWithExt);
                $filename = $fileInfo['filename'];
                $extension = isset($fileInfo['extension']) ? '.' . $fileInfo['extension'] : '';
                $this->outputFileName = $filename . '_OUTPUT' . $extension;
                
                $this->documentParser = new DocumentParser();
                $this->previewContent = $this->documentParser->parse($data['file_path'], $data['extension'], true);
                $this->phpWord = $this->documentParser->phpWord;
                $cleanedContent = $this->documentParser->concatGroupedParagraphs($this->previewContent);
            } else if ($data['file_path'] ?? null) {    // Show report and preview content in file
                $this->documentParser = new DocumentParser();
                $this->previewContent = $this->documentParser->parse($data['file_path'], $data['extension'], true);
                $cleanedContent = $this->documentParser->concatGroupedParagraphs($this->previewContent);
            } else {
                $this->previewText = $data['preview_content'];    // Show report and preview content
                $cleanedContent = ['text-1' => $data['preview_content']];
            }
            
            $this->fileName = $data['filename'] ?? '';
            $this->giveMeFile = $data['give_me_file'] ?? false;
            $this->isLoading = false;

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
        if ($this->results && $this->giveMeFile) {
            $this->phpWord = $this->highlightFileContent($this->phpWord);
            $this->outputPath = $this->documentParser->outputDocxFile($this->phpWord, $this->outputFileName);
        } else {
            $this->highlightContent = $this->highlightPreviewContent();
        }

        return [
            'preview_text' => $this->previewText,
            'preview_content' => ! empty($this->highlightContent) ? $this->highlightContent : $this->previewContent,
            'giveMeFile' => $this->giveMeFile,
            'filename' => $this->fileName,
            'results' => $this->results,
            'isLoading' => $this->isLoading,
            'error' => $this->error,
        ];
    }

    private function highlightFileContent(\PhpOffice\PhpWord\PhpWord $phpWord): \PhpOffice\PhpWord\PhpWord
    {
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                    foreach ($element->getElements() as $textElement) {
                        if ($textElement instanceof \PhpOffice\PhpWord\Element\Text) {
                            // Get paragraph similarity data
                            $paragraphResult = $this->getParagraphResultByKey($element->getRelationId());
                            $paragraphPercent = collect($paragraphResult)->first()['similarity_percentage'] ?? null;
                            
                            // Skip if no percentage data available
                            if (is_null($paragraphPercent)) {
                                continue;
                            }

                            // Get appropriate highlight color
                            $highlightConfig = highlight_word_background($paragraphPercent);

                            // Create or modify font style
                            $fontStyle = $textElement->getFontStyle();
                            if ($fontStyle === null) {
                                $fontStyle = new \PhpOffice\PhpWord\Style\Font();
                            }

                            // Apply highlighting
                            $fontStyle->setBgColor($highlightConfig['bgColor']);
                            $fontStyle->setColor($highlightConfig['color']);
                            $textElement->setFontStyle($fontStyle);
                        }
                    }
                }
            }
        }

        return $phpWord;
    }

    private function highlightPreviewContent(): array
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
